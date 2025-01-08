<?php

/**
 * Cart Controller
 *  
 * @package YoCoach
 * @author Fatbit Team
 */
class CartController extends LoggedUserController
{

    /**
     * Initialize Cart
     * 
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        if ($this->siteUserType == USER::AFFILIATE) {
            MyUtility::dieJsonError(Label::getLabel('LBL_PLEASE_LOGIN_AS_LEARNER'));
        }
        $this->setUserSubscription();
    }

    /**
     * Language and Duration Slots
     */
    public function langSlots()
    {
        $teacherId = FatApp::getPostedData('ordles_teacher_id', FatUtility::VAR_INT, 0);
        $duration = FatApp::getPostedData('ordles_duration', FatUtility::VAR_INT, 0);
        $tlangId = FatApp::getPostedData('ordles_tlang_id', FatUtility::VAR_INT, 0);
        if ($teacherId < 1 || $teacherId == $this->siteUserId) {
            MyUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $user = new User($teacherId);
        if (!$teacher = $user->validateTeacher($this->siteLangId, $this->siteUserId)) {
            MyUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $utl = new UserTeachLanguage($teacherId);
        $langslots = $utl->getLangSlots($this->siteLangId);
        $tlangs = array_keys($langslots);
        $tlangId = (!in_array($tlangId, $tlangs)) ? current($tlangs) : $tlangId;
        $slots = $langslots[$tlangId]['slots'] ?? [];
        $cartSteps = 4;
        if (!empty($this->activePlan)) {
            $duration = (!in_array($this->activePlan['ordsplan_duration'], $slots)) ? 0 : $this->activePlan['ordsplan_duration'];
            if (!$duration) {
                MyUtility::dieJsonError(Label::getLabel('LBL_SLOT_NOT_AVAILABLE'));
            }
            $cartSteps = 3;
        } else {
            $duration = (!in_array($duration, $slots)) ? current($slots) : $duration;
        }
        $this->sets([
            'teacher' => $teacher, 'langslots' => $langslots,
            'tlangId' => $tlangId, 'duration' => $duration,
            'stepCompleted' => [], 'stepProcessing' => [1],
            'activePlan' => $this->activePlan,
            'cartSteps' => $cartSteps
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Lesson Price and lesson Quantity 
     */
    public function priceSlabs()
    {
        $teacherId = FatApp::getPostedData('ordles_teacher_id', FatUtility::VAR_INT, 0);

        $tlangId = FatApp::getPostedData('ordles_tlang_id', FatUtility::VAR_INT, 0);
        $duration = FatApp::getPostedData('ordles_duration', FatUtility::VAR_INT, 0);
        $quantity = FatApp::getPostedData('ordles_quantity', FatUtility::VAR_INT, 0);
        $ordlesType = FatApp::getPostedData('ordles_type', FatUtility::VAR_INT, 0);
        $ordlesType = ($ordlesType < 1) ? Lesson::TYPE_REGULAR : $ordlesType;
        $ordlesOffline = FatApp::getPostedData('ordles_offline', FatUtility::VAR_INT, 0);
        $address = UserAddresses::getDefault($teacherId, $this->siteLangId);
        if ($teacherId < 1 || $tlangId < 1 || $duration < 1 || $teacherId == $this->siteUserId) {
            MyUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if (!empty($this->activePlan) && $duration != $this->activePlan['ordsplan_duration']) {
            MyUtility::dieJsonError(Label::getLabel('LBL_INVALID_DURATION_FOR_SLOT'));
        }
        if (!empty($this->activePlan)) {
            if (!($this->activePlan['ordsplan_lessons'] - $this->activePlan['ordsplan_used_lesson_count'])) {
                MyUtility::dieJsonError(Label::getLabel('LBL_NO_LESSON_LEFT_IN_SUBSCRIPTION'));
            }
        }
        $user = new User($teacherId);
        if (!$teacher = $user->validateTeacher($this->siteLangId, $this->siteUserId)) {
            MyUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if (!$userLangData = (new UserTeachLanguage($teacherId))->getById($tlangId, $this->siteLangId)) {
            MyUtility::dieJsonError(Label::getLabel('LBL_TEACHER_DOES_NOT_HAVE_PRICE'));
        }
        $quantity = empty($quantity) ? 1 : $quantity;
        $discount = 0;
        $offer = OfferPrice::getLessonOffer($this->siteUserId, $teacherId);
        if (!empty($offer['offpri_lesson_price'])) {
            $offers = json_decode($offer['offpri_lesson_price'], 1);
            $offers = array_column($offers, 'offer', 'duration');
            $discount = Fatutility::float(($offers[$duration] ?? 0));
        }
        $price = MyUtility::slotPrice($userLangData['utlang_price'], $duration);
        $cartSteps = 4;
        if (!empty($this->activePlan)) {
            $price = 0;
            $cartSteps = 3;
        }
        $this->sets([
            'subWeek' => FatApp::getConfig('CONF_RECURRING_SUBSCRIPTION_WEEKS'),
            'teacher' => $teacher, 'tlangId' => $tlangId, 'duration' => $duration,
            'quantity' => $quantity, 'discount' => $discount, 'offer' => $offer, 'ordlesType' => $ordlesType,
            'ordlesOffline' => $ordlesOffline, 'tlangName' => $userLangData['tlang_name'], 'price' => $price, 'totalPrice' => $price * $quantity,
            'postedData' => FatApp::getPostedData(), 'stepCompleted' => [1],
            'address' => $address,
            'stepProcessing' => [2],
            'activePlan' => $this->activePlan,
            'cartSteps' => $cartSteps,
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Render Booking Calendar for selecting slots
     */
    public function viewCalendar()
    {
        $teacherId = FatApp::getPostedData('ordles_teacher_id', FatUtility::VAR_INT, 0);
        $tlangId = FatApp::getPostedData('ordles_tlang_id', FatUtility::VAR_INT, 0);
        $duration = FatApp::getPostedData('ordles_duration', FatUtility::VAR_INT, 0);
        $quantity = FatApp::getPostedData('ordles_quantity', FatUtility::VAR_INT, 0);
        $ordlesType = FatApp::getPostedData('ordles_type', FatUtility::VAR_INT, 0);
        $ordlesOffline = FatApp::getPostedData('ordles_offline', FatUtility::VAR_INT, 0);
        $subPlan = 0;
        if (
            $teacherId < 1 || $tlangId < 1 || $duration < 1 || $quantity < 1 ||
            $ordlesType < 1 || $teacherId == $this->siteUserId
        ) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $user = new User($teacherId);
        if (!$teacher = $user->validateTeacher($this->siteLangId, $this->siteUserId)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $userLangData = (new UserTeachLanguage($teacherId))->getById($tlangId, $this->siteLangId);
        $calendarDays = FatApp::getConfig('CONF_AVAILABILITY_UPDATE_WEEK_NO') * 7;
        if ($ordlesType == Lesson::TYPE_SUBCRIP) {
            $startEndDate = MyDate::getSubscriptionDates(FatApp::getConfig('CONF_RECURRING_SUBSCRIPTION_WEEKS') * 7);
            $calendarDays = (FatApp::getConfig('CONF_RECURRING_SUBSCRIPTION_WEEKS') * 7) + 1;
        }
        if (!empty($this->activePlan) && $ordlesType = Lesson::TYPE_REGULAR) {
            $subdays = MyDate::diff(date('Y-m-d H:i:s'), $this->activePlan['ordsplan_end_date']);
            $calendarDays = $subdays + 1;
            $subPlan = 1;
        }
        $cartSteps = 4;
        if ($this->activePlan) {
            $cart = new Cart($this->siteUserId, $this->siteLangId);
            $form = $cart->getCheckoutForm([0 => Label::getLabel('LBL_NA')]);
            $form->fill(['order_type' => Order::TYPE_LESSON]);
            $this->set('form', $form);
            $cartSteps = 3;
        }
        
        $this->sets([
            'teacher' => $teacher, 'tlangId' => $tlangId,
            'duration' => $duration, 'quantity' => $quantity,
            'ordlesType' => $ordlesType, 'tlangName' => ($userLangData['tlang_name'] ?? ''),
            'calendarDays' => $calendarDays, 'ordlesOffline' => $ordlesOffline,
            'nowDate' => MyDate::formatDate(date('Y-m-d H:i:s')),
            'stepCompleted' => [1, 2], 'stepProcessing' => [3],
            'activePlan' => $this->activePlan,
            'cartSteps' => $cartSteps,
            'subPlan' => $subPlan,
            'subEndDate' => $startEndDate['ordsub_enddate'] ?? ''
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Add Lesson(s) to Cart
     */
    public function addLesson()
    {
        $quantity = FatApp::getPostedData('ordles_quantity', FatUtility::VAR_INT, 0);
        $post = FatApp::getPostedData();
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        $frm = $cart->getLessonForm($quantity);
        if (!$post = $frm->getFormDataFromArray($post)) {
            MyUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        if (!empty($post['ordles_starttime']) && !empty($post['ordles_endtime'])) {
            $post['ordles_starttime'] = MyDate::formatToSystemTimezone($post['ordles_starttime']);
            $post['ordles_endtime'] = MyDate::formatToSystemTimezone($post['ordles_endtime']);
        }
        if ($post['ordles_type'] == Lesson::TYPE_REGULAR) {
            $post['lessons'] = $this->formatLessonData($post);
            unset($post['startTime'], $post['endTime']);
        }
        if (
            $post['ordles_type'] == Lesson::TYPE_FTRAIL &&
            Lesson::isTrailAvailed($this->siteUserId, $post['ordles_teacher_id'])
        ) {
            FatUtility::dieJsonError(Label::getLabel('LBL_YOU_ALLREADY_AVAILED_FREE_TRIAL_LESSON'));
        }
        if (isset($post['ordles_offline']) && $post['ordles_offline'] == AppConstant::YES && !User::offlineSessionsEnabled($post['ordles_teacher_id'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_OFFLINE_LESSONS_NOT_AVAILABLE'));
        }
        if (!empty($this->activePlan) && $post['ordles_type'] != Lesson::TYPE_FTRAIL) {
            $post['ordles_ordsplan_id'] = $this->activePlan['ordsplan_id'];
            $cart->applyReward(0);
        }
        if (!$cart->addLesson($post)) {
            MyUtility::dieJsonError($cart->getError());
        }
        if ($post['ordles_type'] == Lesson::TYPE_FTRAIL || API_CALL || !empty($this->activePlan)) {
            MyUtility::dieJsonSuccess(Label::getLabel('LBL_ITEM_ADDED_SUCCESSFULLY'));
        }
        $this->set('post', $post);
        $this->paymentSummary(Order::TYPE_LESSON);
    }

    /**
     * Add Subscription to cart
     */
    public function addSubscription()
    {
        $quantity = FatApp::getPostedData('ordles_quantity', FatUtility::VAR_INT, 0);
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        $frm = $cart->getSubscriptionForm($quantity);
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            MyUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        if (isset($post['ordles_offline']) && $post['ordles_offline'] == AppConstant::YES && !User::offlineSessionsEnabled($post['ordles_teacher_id'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_OFFLINE_LESSONS_NOT_AVAILABLE'));
        }
        $post['lessons'] = $this->formatLessonData($post);
        unset($post['startTime'], $post['endTime']);
        if (!$cart->addSubscription($post)) {
            MyUtility::dieJsonError($cart->getError());
        }
        if (API_CALL) {
            MyUtility::dieJsonSuccess(Label::getLabel('LBL_ITEM_ADDED_SUCCESSFULLY'));
        }
        $this->set('post', $post);
        $this->paymentSummary(Order::TYPE_SUBSCR);
    }

    /**
     * Add Class to cart
     */
    public function addClass()
    {
        $grpclsId = FatApp::getPostedData('grpcls_id', FatUtility::VAR_INT, 0);
        if (empty($grpclsId)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (!$cart->addClass($grpclsId)) {
            FatUtility::dieJsonError($cart->getError());
        }
        $this->set('post', ['grpcls_id' => $grpclsId]);
        $this->paymentSummary(Order::TYPE_GCLASS);
    }

    /**
     * Add Package to cart
     */
    public function addPackage()
    {
        $packageId = FatApp::getPostedData('packageId', FatUtility::VAR_INT, 0);
        if (empty($packageId)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (!$cart->addPackage($packageId)) {
            FatUtility::dieJsonError($cart->getError());
        }
        $this->set('post', ['package_id' => $packageId]);
        $this->paymentSummary(Order::TYPE_PACKGE);
    }

    /**
     * Add Course to cart
     */
    public function addCourse()
    {
        if (!Course::isEnabled()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_COURSE_MODULE_NOT_AVAILABLE'));
        }
        $courseId = FatApp::getPostedData('course_id', FatUtility::VAR_INT, 0);
        if (empty($courseId)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        /* check course already booked */
        $course = CourseSearch::getPurchasedCourses($this->siteUserId, [$courseId]);
        if (!empty($course)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_YOU_HAVE_ALREADY_PURCHASED_THIS_COURSE'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (!$cart->addCourse($courseId)) {
            FatUtility::dieJsonError($cart->getError());
        }
        $this->set('post', ['course_id' => $courseId]);
        $this->paymentSummary(Order::TYPE_COURSE);
    }

    /**
     * Add Course to cart
     */
    public function addSubscriptionPlan()
    {
        if (!SubscriptionPlan::isEnabled()) {
            FatUtility::dieJsonError(Label::getLabel('LBL_SUBSCRIPTION_MODULE_NOT_AVAILABLE'));
        }
        if (!empty($this->activePlan)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_CANCEL_EXISTING_SUBSCRIPTION_PLAN'));
        }
        $planId = FatApp::getPostedData('planId', FatUtility::VAR_INT, 0);
        if (empty($planId)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (!$cart->addSubscriptionPlan($planId)) {
            FatUtility::dieJsonError($cart->getError());
        }
        $this->set('post', ['plan_id' => $planId]);
        $this->paymentSummary(Order::TYPE_SUBPLAN);
    }

    /**
     * Apply Coupon
     */
    public function applyCoupon()
    {
        $code = FatApp::getPostedData('coupon_code', FatUtility::VAR_STRING, '');
        $orderType = FatApp::getPostedData('order_type', FatUtility::VAR_INT, 0);
        if (empty($code) || empty($orderType)) {
            MyUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (!$cart->applyCoupon($code)) {
            MyUtility::dieJsonError($cart->getError());
        }
        $this->paymentSummary($orderType);
    }

    /**
     * Remove Coupon
     */
    public function removeCoupon()
    {
        $orderType = FatApp::getPostedData('order_type', FatUtility::VAR_INT, 0);
        if (empty($orderType)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (!$cart->removeCoupon()) {
            FatUtility::dieJsonError(Label::getLabel("LBL_INVALID_ACTION"));
        }
        $this->paymentSummary($orderType);
    }

    /**
     * Apply Reward Points
     */
    public function applyRewards()
    {
        $orderType = FatApp::getPostedData('order_type', FatUtility::VAR_INT, 0);
        $status = FatApp::getPostedData('apply_reward', FatUtility::VAR_INT, 0);
        if (empty($orderType)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (!$cart->applyReward($status)) {
            MyUtility::dieJsonError($cart->getError());
        }
        $this->paymentSummary($orderType);
    }

    /**
     * Render Payment Summary
     * 
     * @param $orderType
     */
    public function paymentSummary($orderType)
    {
        if ($orderType == Order::TYPE_COURSE && !Course::isEnabled()) {
            MyUtility::dieJsonError(Label::getLabel('LBL_COURSE_MODULE_NOT_AVAILABLE'));
        }
        $addAndPay = FatApp::getPostedData('add_and_pay', FatUtility::VAR_INT, 0);
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if (1 > $cart->getCount()) {
            MyUtility::dieJsonError(Label::getLabel('LBL_CART_IS_EMPTY'));
        }
        if (!$cartItems = $cart->getItems()) {
            MyUtility::dieJsonError($cart->getError());
        }
        $couponCode = $cart->getCoupon()['coupon_code'] ?? '';
        $checkoutFormData = [
            'order_type' => $orderType,
            'coupon_code' => $couponCode,
            'apply_reward' => $cart->appliedReward()
        ];
        $pmethodId = FatApp::getPostedData('order_pmethod_id', FatUtility::VAR_STRING, '');
        if (!empty($pmethodId) && $cart->getNetAmount() > 0) {
            $checkoutFormData['order_pmethod_id'] = $pmethodId;
        }
        $checkoutForm = $cart->getCheckoutForm();
        $checkoutForm->fill($checkoutFormData);
        $coupon = new Coupon(0, $this->siteLangId);
        $this->sets([
            'orderType' => $orderType,
            'addAndPay' => $addAndPay,
            'cartItems' => $cartItems,
            'checkoutForm' => $checkoutForm,
            'cartTotal' => $cart->getTotal(),
            'cartDiscount' => $cart->getDiscount(),
            'cartNetAmount' => $cart->getNetAmount(),
            'appliedCoupon' => $cart->getCoupon(),
            'appliedReward' => $cart->appliedReward(),
            'rewardDiscount' => $cart->getRewardDiscount(),
            'availableCoupons' => $coupon->getCouponList(),
            'currencyData' => MyUtility::getSystemCurrency(),
            'walletBalance' => User::getWalletBalance($this->siteUserId),
            'rewardBalance' => User::getRewardBalance($this->siteUserId),
            'walletPayId' => PaymentMethod::getByCode(WalletPay::KEY)['pmethod_id'],
            'activePlan' => $this->activePlan,
            'stepCompleted' => [1, 2, 3],
            'stepProcessing' => [4],
        ]);
        $this->_template->render(false, false, 'cart/payment-summary.php');
    }

    /**
     * Confirm Order to place
     */
    public function confirmOrder()
    {
        $orderType = FatApp::getPostedData('order_type', FatUtility::VAR_INT, 0);

        if ($orderType == Order::TYPE_COURSE && !Course::isEnabled()) {
            MyUtility::dieJsonError(Label::getLabel('LBL_COURSE_MODULE_NOT_AVAILABLE'));
        }
        if ($orderType == Order::TYPE_SUBPLAN && !SubscriptionPlan::isEnabled()) {
            MyUtility::dieJsonError(Label::getLabel('LBL_SUBSCRIPTION_MODULE_NOT_AVAILABLE'));
        }
        $ordlesType = FatApp::getPostedData('ordles_type', FatUtility::VAR_INT, 0);
        if (!empty($this->activePlan) && Order::TYPE_LESSON == $orderType && $ordlesType != Lesson::TYPE_FTRAIL) {
            if (!($this->activePlan['ordsplan_lessons'] - $this->activePlan['ordsplan_used_lesson_count'])) {
                MyUtility::dieJsonError(Label::getLabel('LBL_NO_LESSON_LEFT_IN_SUBSCRIPTION'));
            }
        }
        if ($orderType == Order::TYPE_SUBPLAN && !empty($this->activePlan)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_CANCEL_EXISTING_SUBSCRIPTION_PLAN'));
        }
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        if ($cart->getCount() < 1) {
            MyUtility::dieJsonError(Label::getLabel('LBL_CART_IS_EMPTY'));
        }
        $frm = $cart->getCheckoutForm();
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData())) {
            MyUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        $order = new Order(0, $this->siteUserId);
        if (!$order->addItems($post['order_type'], $cart->getItems())) {
            MyUtility::dieJsonError($order->getError());
        }
        $rewards = $cart->getRewards();
        if (!$order->applyRewards(...$rewards)) {
            MyUtility::dieJsonError($order->getError());
        }
        if (!$order->applyCoupon($cart->getCoupon())) {
            MyUtility::dieJsonError($order->getError());
        }
        $pmethodId = FatApp::getPostedData('order_pmethod_id', FatUtility::VAR_INT, 0);
        $pmethod = PaymentMethod::getAttributesById($pmethodId);
        if ($cart->getNetAmount() > 0 && (empty($pmethod) || empty($pmethod['pmethod_active']))) {
            MyUtility::dieJsonError(Label::getLabel('LBL_PAYMENT_METHOD_NOT_AVAILABLE'));
        }
        if (!$order->placeOrder($post['order_type'], $pmethodId, $post['add_and_pay'])) {
            MyUtility::dieJsonError($order->getError());
        }
        $orderId = $order->getMainTableRecordId();
        $rootUrl = (API_CALL) ? CONF_WEBROOT_FRONTEND . 'api/' : CONF_WEBROOT_FRONTEND;
        $redirectUrl = MyUtility::makeFullUrl('Payment', 'charge', [$orderId], $rootUrl);
        if (Order::getAttributesById($orderId, 'order_net_amount') == 0) {
            $payment = new OrderPayment($orderId);
            if (!$payment->paymentSettlements('NA', 0, [])) {
                MyUtility::dieJsonError($payment->getError());
            }
            $redirectUrl = MyUtility::makeFullUrl('Payment', 'success', [$orderId], $rootUrl);
        }
        $viewOrderId = $orderId;
        $relatedOrderId = Order::getAttributesById($orderId, 'order_related_order_id');
        if (!empty($relatedOrderId)) {
            $viewOrderId = FatUtility::int($relatedOrderId);
        }
        MyUtility::dieJsonSuccess([
            'order_id' => $orderId,
            'redirectUrl' => $redirectUrl,
            'view_order_id' => $viewOrderId,
            'msg' => Label::getLabel('MSG_PLEASE_WAIT_FOR_A_WHILE'),
        ]);
    }

    /**
     * Render Trail Calendar
     */
    public function trailCalendar()
    {
        $teacherId = FatApp::getPostedData('teacherId', FatUtility::VAR_INT, 0);
        Meeting::zoomVerificationCheck($teacherId);
        if (FatApp::getConfig('CONF_ENABLE_FREE_TRIAL', FatUtility::VAR_INT, 0) != 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $user = new User($teacherId);
        if (!$teacher = $user->validateTeacher($this->siteLangId, $this->siteUserId)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        if ($teacher['user_trial_enabled'] == AppConstant::NO) {
            FatUtility::dieJsonError(Label::getLabel('LBL_FREE_TRIAL_IS_DISABLED_BY_TEACHER'));
        }
        if (Lesson::isTrailAvailed($this->siteUserId, $teacher['user_id'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_YOU_ALLREADY_AVAILED_FREE_TRIAL_LESSON'));
        }
        $teacher['user_country_code'] = Country::getAttributesById($teacher['user_country_id'], 'country_code');
        $duration = FatApp::getConfig('CONF_TRIAL_LESSON_DURATION');
        $cart = new Cart($this->siteUserId, $this->siteLangId);
        $form = $cart->getCheckoutForm([0 => Label::getLabel('LBL_NA')]);
        $form->fill(['order_type' => Order::TYPE_LESSON]);
        $this->set('form', $form);
        $this->sets(['teacher' => $teacher, 'duration' => $duration]);
        $this->_template->render(false, false);
    }

    /**
     * Format Lessons Data
     * 
     * @param array $data
     * @return array
     */
    private function formatLessonData(array $data): array
    {
        $lessonData = [];
        foreach ($data['startTime'] as $key => $value) {
            $lesson = ['ordles_starttime' => null, 'ordles_endtime' => null];
            if (!empty($value) && !empty($data['endTime'][$key])) {
                $lesson['ordles_starttime'] = MyDate::formatToSystemTimezone($value);
                $lesson['ordles_endtime'] = MyDate::formatToSystemTimezone($data['endTime'][$key]);
            }
            array_push($lessonData, $lesson);
        }
        return $lessonData;
    }
}
