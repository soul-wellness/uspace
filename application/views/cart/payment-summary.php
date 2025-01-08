<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$checkoutForm->addFormTagAttribute('onsubmit', 'cart.confirmOrder(this.form); return false;');
$orderType = $checkoutForm->getField('order_type');
$orderType->addFieldTagAttribute('class', 'd-none');
$pmethodField = $checkoutForm->getField('order_pmethod_id');
$couponField = $checkoutForm->getField('coupon_code');
$couponField->addFieldTagAttribute('id', 'coupon_code');
$couponField->addFieldTagAttribute('onkeypress', 'cart.disableEnter(event);');
$couponField->addFieldTagAttribute('placeholder', Label::getLabel('LBL_ENTER_COUPON_CODE'));
$submitField = $checkoutForm->getField('submit');
$submitField->addFieldTagAttribute('onclick', 'cart.confirmOrder(this.form);');
$submitField->addFieldTagAttribute('class', 'btn btn--primary btn--large btn--block color-white');
$rewardField = $checkoutForm->getField('apply_reward');
$rewardField->addFieldTagAttribute('id', 'apply_reward');
$rewardField->addFieldTagAttribute('class', 'selection-tabs__input');
$rewardField->addFieldTagAttribute('onclick', 'cart.applyRewards(this);');
$minimumCreditUseLimit = FatApp::getConfig('CONF_REWARD_POINT_MINIMUM_USE');
$disabled = $cartNetAmount == 0 ? 'disabled' : '';
$steps = Cart::getSteps();
$disabledReward = false;
if (!empty($appliedCoupon['coupon_id'])) {
    if ($cartTotal == $appliedCoupon['coupon_discount']) {
        $appliedReward = 0;
        $disabledReward = true;
    }
}
?>
<div class="modal-header modal-header--checkout">
    <?php if (!empty($cartItems[Cart::LESSON]) || !empty($cartItems[Cart::SUBSCR])) { ?>
        <a href="javascript:void(0);" class="btn btn--bordered color-black btn--back" onclick="cart.viewCalendar(cart.prop.ordles_teacher_id, cart.prop.ordles_tlang_id, cart.prop.ordles_duration, cart.prop.ordles_quantity, cart.prop.ordles_type, cart.prop.ordles_offline);">
            <svg class="icon icon--back">
                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#back'; ?>"></use>
            </svg>
            <?php echo Label::getLabel('LBL_BACK'); ?>
        </a>
    <?php } ?>
    <h4 class="flex-1 align-center"><?php echo Label::getLabel('LBL_SELECT_PAYMENT_METHOD'); ?></h4>
    <button type="button" class="btn-close yocoachmodalJs" data-bs-dismiss="modal" aria-label=""></button>
    <?php if (!empty($cartItems[Cart::LESSON]) || !empty($cartItems[Cart::SUBSCR])) { ?>
        <div class="step-nav">
            <ul>
                <?php foreach ($steps as $key => $step) { ?>
                    <li class="step-nav_item <?php echo in_array($key, $stepProcessing) ? 'is-process' : ''; ?> <?php echo in_array($key, $stepCompleted) ? 'is-completed' : ''; ?> ">
                        <a href="javascript:void(0);"><?php echo $step; ?></a><?php if (in_array($key, $stepCompleted)) { ?><span class="step-icon"></span><?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>
<div class="modal-body p-0">
    <div class="box box--checkout">
        <div class="box__body">
            <div class="selection-tabs selection--checkout selection--payment">
                <?php echo $checkoutForm->getFormTag(); ?>
                <?php echo $orderType->getHTML(); ?>
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <div class="selection-title">
                            <p><?php echo Label::getLabel('LBL_SELECT_A_PAYMENT_METHOD'); ?></p>
                        </div>
                        <div class="payment-wrapper">
                            <?php foreach ($pmethodField->options as $id => $name) { ?>
                                <label class="selection-tabs__label payment-method-js <?php echo $disabled; ?>">
                                    <input name="order_pmethod_id" type="radio" class="selection-tabs__input" value="<?php echo $id; ?>" <?php echo ($pmethodField->value == $id) ? 'checked' : ''; ?> <?php echo $disabled; ?> />
                                    <div class="selection-tabs__title">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                            <g>
                                                <path d="M12,22A10,10,0,1,1,22,12,10,10,0,0,1,12,22Zm-1-6,7.07-7.071L16.659,7.515,11,13.172,8.174,10.343,6.76,11.757Z" transform="translate(-2 -2)" />
                                            </g>
                                        </svg>
                                        <div class="payment-type">
                                            <p><?php echo ($id != $walletPayId) ? $name : str_replace(['{balance}'], [MyUtility::formatMoney($walletBalance)], Label::getLabel('LBL_WALLET_BALANCE_({balance})')); ?></p>
                                        </div>
                                    </div>
                                </label>
                            <?php } ?>
                        </div>
                        <p class="color-red text-center"><?php echo str_replace(['{DURATION}'], [FatApp::getConfig('CONF_CANCEL_ORDER_DURATION')], Label::getLabel('LBL_ORDER_AUTO_CANCEL_AFTER_{DURATION}')); ?></p>
                    </div>
                    <div class="col-md-6  col-xl-6">
                        <div class="selection-title">
                            <p><?php echo Label::getLabel('LBL_HAVE_A_COUPON?'); ?></p>
                            <?php if (count($availableCoupons) > 0) { ?>
                                <a href="javascript:void(0);" class="color-primary btn--link slide-toggle-coupon-js"><?php echo Label::getLabel('LBL_VIEW_COUPONS'); ?></a>
                            <?php } ?>
                        </div>
                        <div class="apply-coupon">
                            <svg class="icon icon--price-tag">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#price-tag'; ?>"></use>
                            </svg>
                            <?php echo $couponField->getHTML(); ?>
                            <a href="javascript:void(0);" onclick="cart.applyCoupon();" class="btn btn--secondary btn--small color-white"><?php echo Label::getLabel('LBL_APPLY'); ?></a>
                        </div>
                        <?php if (!empty($appliedCoupon['coupon_id'])) { ?>
                            <div class="coupon-applied">
                                <div class="coupon-type">
                                    <span class="bold-600 coupon-code"><?php echo $appliedCoupon['coupon_code']; ?></span>
                                    <p><?php echo Label::getLabel('LBL_COUPON_APPLIED'); ?></p>
                                </div>
                                <a href="javascript:void(0);" onclick="cart.removeCoupon();" class="btn btn--coupon btn--small"><?php echo Label::getLabel('LBL_REMOVE'); ?></a>
                            </div>
                        <?php } ?>
                        <div class="selection-title">
                            <p><?php echo Label::getLabel('LBL_SUMMARY'); ?></p>
                        </div>
                        <div class="payment-summary">
                            <?php if ((FatApp::getConfig('CONF_ENABLE_REFERRAL_REWARDS') && $rewardBalance >= $minimumCreditUseLimit && RewardPoint::convertToPoints($cartNetAmount) >= $minimumCreditUseLimit) || $appliedReward == 1) { ?>
                                <div class="payment__row">
                                    <label class="checkbox">
                                        <input type="checkbox" name="apply_reward" id="apply_reward" onclick="cart.applyRewards(this);" value="1" <?php echo ($appliedReward == 1) ? 'checked="checked"' : ''; ?> <?php echo ($disabledReward == 1) ? 'disabled' : ''; ?> />
                                        <i class="input-helper"></i>
                                        <?php
                                        $rewardBalanceValue = MyUtility::formatMoney(RewardPoint::convertToValue($rewardBalance));
                                        $rewardBalanceLabel = Label::getLabel('LBL_REWARD_BALANCE_{rewards}_({balance})');
                                        ?>

                                        <b><?php echo str_replace(['{rewards}', '{balance}'], [$rewardBalance, $rewardBalanceValue], $rewardBalanceLabel); ?></b>
                                    </label>
                                </div>
                            <?php } ?>
                            <?php if ($walletBalance > 0 && $walletBalance < $cartNetAmount) { ?>
                                <div class="payment__row">
                                    <label class="checkbox payment-method-js">
                                        <input type="checkbox" name="add_and_pay" value="1" <?php echo ($addAndPay == 1) ? 'checked="checked"' : ''; ?> onclick="cart.selectWallet(this.checked)" />
                                        <i class="input-helper"></i>
                                        <b><?php echo str_replace(['{remaining}'], [MyUtility::formatMoney($walletBalance)], Label::getLabel('LBL_USE_WALLET_BALANCE_({remaining})')); ?></b>
                                    </label>
                                </div>
                            <?php } ?>
                            <?php foreach ($cartItems[Cart::LESSON] as $key => $value) { ?>
                                <?php if (!empty($value['ordles_address'])) { ?>
                                    <div class="payment__row">
                                        <p><?php echo Label::getLabel('LBL_ADDRESS') ?> : <?php echo $value['ordles_address']; ?> </p>
                                    </div>
                                <?php } ?>
                                <div class="payment__row">
                                    <div>
                                        <p><?php echo str_replace('{quantity}', $value['ordles_quantity'], Label::getLabel('LBL_Lesson_Count:_{quantity}')); ?></p>
                                        <p><?php echo str_replace('{duration}', $value['ordles_duration'], Label::getLabel('LBL_Lesson(s)_Duration:_{duration}_Mins/lesson')); ?></p>
                                        <p><?php echo str_replace('{itemprice}', MyUtility::formatMoney($value['ordles_amount']), Label::getLabel('LBL_Item_Price:_{itemprice}/lesson')); ?></p>
                                        <p><?php echo str_replace('{teachlang}', $value['ordles_tlang'], Label::getLabel('LBL_TEACH_LANGUAGE_:_{teachlang}')); ?></p>
                                    </div>

                                    <div><b><?php echo MyUtility::formatMoney($value['ordles_quantity'] * $value['ordles_amount']); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($cartItems[Cart::SUBSCR])) { ?>
                                <?php if (!empty($cartItems[Cart::SUBSCR]['ordles_address'])) { ?>
                                    <div class="payment__row">
                                        <p><?php echo Label::getLabel('LBL_ADDRESS') ?> : <?php echo $cartItems[Cart::SUBSCR]['ordles_address']; ?> </p>
                                    </div>
                                <?php } ?>
                                <div class="payment__row">
                                    <div>
                                        <p><?php echo str_replace('{quantity}', $cartItems[Cart::SUBSCR]['ordles_quantity'], Label::getLabel('LBL_Lesson_Count:_{quantity}')); ?></p>
                                        <p><?php echo str_replace('{duration}', $cartItems[Cart::SUBSCR]['ordles_duration'], Label::getLabel('LBL_Lesson(s)_Duration:_{duration}_Mins/lesson')); ?></p>
                                        <p><?php echo str_replace('{itemprice}', MyUtility::formatMoney($cartItems[Cart::SUBSCR]['ordles_amount']), Label::getLabel('LBL_Item_Price:_{itemprice}/lesson')); ?></p>
                                        <p><?php echo str_replace('{teachlang}', $cartItems[Cart::SUBSCR]['ordles_tlang'], Label::getLabel('LBL_TEACH_LANGUAGE_:_{teachlang}')); ?></p>
                                    </div>
                                    <div><b><?php echo MyUtility::formatMoney($cartItems[Cart::SUBSCR]['ordles_quantity'] * $cartItems[Cart::SUBSCR]['ordles_amount']); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php foreach ($cartItems[Cart::GCLASS] as $key => $class) { ?>
                                <?php if (!empty($class['address'])) { ?>
                                    <div class="payment__row">
                                        <p><?php echo Label::getLabel('LBL_ADDRESS') ?> : <?php echo UserAddresses::format($class['address']); ?> </p>
                                    </div>
                                <?php } ?>
                                <div class="payment__row">
                                    <div>
                                        <b><?php echo $class['grpcls_title']; ?></b>
                                        <p><?php echo str_replace('{itemprice}', MyUtility::formatMoney($class['ordcls_amount']), Label::getLabel('LBL_ITEM_PRICE:_{itemprice}/CLASS')); ?></p>
                                        <p><?php echo Label::getLabel('LBL_START_TIME') . ' : ' . MyDate::showDate(MyDate::formatDate($class['grpcls_start_datetime']), true); ?> </p>
                                        <p><?php echo Label::getLabel('LBL_END_TIME') . ' : ' . MyDate::showDate(MyDate::formatDate($class['grpcls_end_datetime']), true); ?> </p>
                                    </div>
                                    <div><b><?php echo MyUtility::formatMoney($class['ordcls_amount']); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php foreach ($cartItems[Cart::PACKGE] as $key => $package) { ?>
                                <?php if (!empty($package['address'])) { ?>
                                    <div class="payment__row">
                                        <p><?php echo Label::getLabel('LBL_ADDRESS') ?> : <?php echo UserAddresses::format($package['address']); ?></p>
                                    </div>
                                <?php } ?>
                                <div class="payment__row">
                                    <div>
                                        <b><?php echo $package['grpcls_title']; ?></b>
                                        <p><?php echo str_replace('{itemprice}', MyUtility::formatMoney($package['grpcls_amount']), Label::getLabel('LBL_ITEM_PRICE:_{itemprice}/PACKAGE')); ?></p>
                                        <p><?php echo Label::getLabel('LBL_START_TIME') . ' : ' . MyDate::showDate(MyDate::formatDate($package['grpcls_start_datetime']), true); ?> </p>
                                        <p><?php echo Label::getLabel('LBL_END_TIME') . ' : ' . MyDate::showDate(MyDate::formatDate($package['grpcls_end_datetime']), true); ?> </p>
                                        <p><?php echo Label::getLabel('LBL_TOTAL_CLASSES') . ' : ' . count($package['classes']); ?> </p>
                                    </div>
                                    <div><b><?php echo MyUtility::formatMoney($package['grpcls_amount']); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php foreach ($cartItems[Cart::COURSE] as $key => $course) { ?>
                                <div class="payment__row">
                                    <div>
                                        <b><?php echo $course['course_title']; ?></b>
                                        <p><?php echo str_replace('{itemprice}', CourseUtility::formatMoney($course['course_price']), Label::getLabel('LBL_ITEM_PRICE:_{itemprice}/COURSE')); ?></p>
                                        <p><?php echo str_replace('{teachlang}', $course['course_clang_name'], Label::getLabel('LBL_TEACH_LANGUAGE_:_{teachlang}')); ?></p>
                                    </div>
                                    <div><b><?php echo CourseUtility::formatMoney($course['course_price']); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php foreach ($cartItems[Cart::SUBSCRIPTIONPLAN] as $key => $plan) { ?>
                                <div class="payment__row">
                                    <div>
                                        <b><?php echo $plan['plan_name']; ?></b>
                                        <p><?php echo str_replace('{itemprice}', MyUtility::formatMoney($plan['subplan_price']), Label::getLabel('LBL_ITEM_PRICE:_{itemprice}')); ?></p>
                                        <p><?php echo Label::getLabel('LBL_PLAN_VALIDITY') . ': ' . $plan['subplan_validity']; ?></p>
                                        <p><?php echo Label::getLabel('LBL_Lesson_COUNT') . ': ' . $plan['subplan_lesson_count']; ?></p>
                                        <p><?php echo Label::getLabel('LBL_Lesson_duration') . ': ' . $plan['subplan_lesson_duration']; ?></p>
                                    </div>
                                    <div><b><?php echo MyUtility::formatMoney($plan['subplan_price']); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($appliedCoupon['coupon_id'])) { ?>
                                <div class="payment__row">
                                    <div><b><?php echo Label::getLabel('LBL_COUPON_DISCOUNT'); ?></b></div>
                                    <div><b><?php echo MyUtility::formatMoney(-$appliedCoupon['coupon_discount']); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($appliedReward)) { ?>
                                <div class="payment__row">
                                    <div><b><?php echo Label::getLabel('LBL_REWARD_DISCOUNT'); ?></b></div>
                                    <div><b><?php echo MyUtility::formatMoney(-$rewardDiscount); ?></b></div>
                                </div>
                            <?php } ?>
                            <?php if ($addAndPay == AppConstant::YES && $walletBalance > 0 && $walletBalance < $cartNetAmount) { ?>
                                <div class="payment__row">
                                    <div><b><?php echo Label::getLabel('LBL_WALLET_DEDUCTION'); ?></b></div>
                                    <div><b><?php echo MyUtility::formatMoney(-$walletBalance); ?></b></div>
                                </div>
                                <div class="payment__row">
                                    <div><b class="color-primary"><?php echo Label::getLabel('LBL_TOTAL'); ?></b></div>
                                    <div><b class="color-primary"><?php echo MyUtility::formatMoney($cartNetAmount - $walletBalance); ?></b></div>
                                </div>
                            <?php } else { ?>
                                <div class="payment__row">
                                    <div><b class="color-primary"><?php echo Label::getLabel('LBL_TOTAL'); ?></b></div>
                                    <div><b class="color-primary"><?php echo MyUtility::formatMoney($cartNetAmount); ?></b></div>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if (count($availableCoupons) > 0) { ?>
                            <div class="coupon-box slide-target-coupon-js">
                                <div class="coupon-box__head">
                                    <p><?php echo Label::getLabel('LBL_AVAILABLE_COUPONS'); ?></p>
                                    <a href="javascript:void(0);" class="btn btn--bordered color-black btn--close">
                                        <svg class="icon icon--close">
                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#close'; ?>"></use>
                                        </svg>
                                    </a>
                                </div>
                                <div class="coupon-box__body">
                                    <?php foreach ($availableCoupons as $key => $coupon) { ?>
                                        <div class="coupon-list">
                                            <div class="coupon-list__head">
                                                <span class="badge color-secondary"><?php echo $coupon['coupon_code']; ?></span>
                                                <a href="javascript:void(0);" onclick="cart.applyCoupon('<?php echo $coupon['coupon_code']; ?>');" class="btn btn--coupon btn--small color-primary"><?php echo Label::getLabel('LBL_APPLY'); ?></a>
                                            </div>
                                            <div class="coupon-list__content">
                                                <p class="bold-600"><?php echo $coupon['coupon_title']; ?></p>
                                                <?php if (!empty($coupon['coupon_description'])) { ?>
                                                    <p><?php echo nl2br($coupon['coupon_description']); ?> </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php echo $submitField->getHTML(); ?>
                        <p class="payment-note color-secondary"> *
                            <?php echo str_replace("{currencycode}", $currencyData['currency_code'], Label::getLabel('LBL_ALL_PURCHASES_ARE_IN_{currencycode}')); ?>
                            <?php echo Label::getLabel('LBL_FOREIGN_TRANSACTION_FEES_MIGHT_APPLY_ACCORDING_TO_YOUR_BANK_POLICIES'); ?>
                        </p>
                    </div>
                </div>
                <?php echo $checkoutForm->getFieldHTML('ordles_type') ?>
                </form>
                <?php echo $checkoutForm->getExternalJS(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $('.slide-toggle-coupon-js').click(function(e) {
        e.preventDefault();
        $(this).parent('.toggle-dropdown').toggleClass("is-active");
    });
    $(".slide-toggle-coupon-js").click(function() {
        $(".slide-target-coupon-js").slideToggle();
    });
    $('.btn--close').click(function() {
        $('.slide-target-coupon-js').slideUp("slow");
    });
    $('.apply-coupon-js').click(function() {
        let couponCode = $('#coupon_code').val();
        cart.applyPromoCode(couponCode);
    });
    $('input[type=radio][name=order_pmethod_id]').on('change', function() {
        if ($(this).val() == <?php echo $walletPayId; ?>) {
            $('.renew-payment').show();
        } else {
            $('.renew-payment').hide();
        }
    });
</script>