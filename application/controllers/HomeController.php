<?php

use MailchimpMarketing\ApiClient;

/**
 * Home Controller
 *  
 * @package YoCoach
 * @author Fatbit Team
 */
class HomeController extends MyAppController
{

    /**
     * Initialize Home
     * 
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Render Website Homepage
     */
    public function index()
    {
        $contentBlocks = ExtraPage::getPageBlocks(ExtraPage::TYPE_HOMEPAGE, $this->siteLangId);
        $isCourseAvailable = Course::isEnabled();
        $isGrpClsEnabled = GroupClass::isEnabled();
        if (!$isCourseAvailable) {
            unset($contentBlocks[ExtraPage::BLOCK_COURSES]);
            unset($contentBlocks[ExtraPage::BLOCK_TOP_COURSE_CATEGORIES]);
        }
        if (!$isGrpClsEnabled) {
            unset($contentBlocks[ExtraPage::BLOCK_CLASSES]);
        }
        if (!empty($contentBlocks) && count($contentBlocks) > 0) {
            foreach ($contentBlocks as $row) {
                switch ($row['epage_block_type']) {
                    case ExtraPage::BLOCK_FEATURED_LANGUAGES:
                        $data = ['pagesize' => 12, 'tlang_featured' => AppConstant::YES, 'tlang_parent' => 0, 'available' => AppConstant::YES];
                        $this->set('featuredLanguages', TeachLanguage::getTeachLanguages($this->siteLangId, true, $data));
                        break;
                    case ExtraPage::BLOCK_TOP_RATED_TEACHERS:
                        $this->set('topRatedTeachers', TeacherSearch::getTopRatedTeachers($this->siteLangId, 0));
                        break;
                    case ExtraPage::BLOCK_CLASSES:
                        $class = new GroupClassSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
                        $this->set('classes', $class->getUpcomingClasses());
                        break;
                    case ExtraPage::BLOCK_COURSES:
                        $course = new CourseSearch($this->siteLangId, $this->siteUserId, 0);
                        $this->set('courses', $course->getPopularCourses());
                        break;
                    case ExtraPage::BLOCK_TESTIMONIALS:
                        $this->set('testmonialList', Testimonial::getTestimonials($this->siteLangId));
                        break;
                    case ExtraPage::BLOCK_LATEST_BLOGS:
                        $this->set('blogPostsList', BlogPost::getBlogsForGrids($this->siteLangId));
                        break;
                    case ExtraPage::BLOCK_TOP_COURSE_CATEGORIES:
                        $this->set('categories', Category::getTopCategories($this->siteLangId));
                    default:
                        break;
                }
            }
        }
        $slides = Slide::getSlides();
        $this->sets([
            'slides' => $slides,
            'slideImages' => Slide::getSlideImages(array_keys($slides), $this->siteLangId),
            'contentBlocks' => $contentBlocks,
            'bookingBefore' => FatApp::getConfig('CONF_CLASS_BOOKING_GAP')
        ]);
        if (!$isGrpClsEnabled) {
            $class = new GroupClassSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
            $this->set('classes', $class->getUpcomingClasses());
        }
        if (!$isCourseAvailable) {
            $course = new CourseSearch($this->siteLangId, $this->siteUserId, 0);
            $this->set('courses', $course->getPopularCourses());
        }
        $this->set('frm', $this->getSearchForm());
        $this->set('isCourseAvailable', $isCourseAvailable);
        if ($isCourseAvailable) {
            $this->_template->render(true, true, 'home/courses-home.php');
        } else {
            $this->set('popularLanguages', TeachLanguage::getPopularLangs($this->siteLangId));
            $this->_template->render();
        }
    }

    /**
     * Setup News Letter
     */
    public function setUpNewsLetter()
    {
        $post = FatApp::getPostedData();
        $apikey = FatApp::getConfig("CONF_MAILCHIMP_KEY");
        $listId = FatApp::getConfig("CONF_MAILCHIMP_LIST_ID");
        $prefix = FatApp::getConfig("CONF_MAILCHIMP_SERVER_PREFIX");
        $status = FatApp::getConfig("CONF_ENABLE_NEWSLETTER_SUBSCRIPTION");
        if (empty($status) || empty($apikey) || empty($listId) || empty($prefix)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_NOT_CONFIGURED_PLEASE_CONTACT_SUPPORT'));
        }
        try {
            $mailchimp = new ApiClient();
            $mailchimp->setConfig(['apiKey' => $apikey, 'server' => $prefix]);
            $response = $mailchimp->ping->get();
            if (!isset($response->health_status)) {
                FatUtility::dieJsonError(Label::getLabel('LBL_CONFIGURED_ERROR_MESSAGE'));
            }
            $subscriber = $mailchimp->lists->addListMember($listId, ['email_address' => $post['email'], 'status' => 'subscribed'], true);
            if ($subscriber->status != 'subscribed') {
                FatUtility::dieJsonError(Label::getLabel('MSG_NEWSLETTER_SUBSCRIPTION_VALID_EMAIL'));
            }
        } catch (Exception $e) {
            $error = strtolower($e->getMessage());
            if (strpos($error, 'member exists') > -1) {
                FatUtility::dieJsonError(Label::getLabel('MSG_YOU_ARE_ALREADY_SUBSCRIBER'));
            } else {
                FatUtility::dieJsonError($error);
            }
        }
        FatUtility::dieJsonSuccess(Label::getLabel('MSG_SUCCESSFULLY_SUBSCRIBED'));
    }

    /**
     * Home languages autocomplete
     *
     * @return json
     */
    public function langAutoComplete()
    {
        $srch = TeachLanguage::getSearchObject($this->siteLangId);
        $srch->addMultiplefields(['tlang_id', 'IFNULL(tlanglang.tlang_name, tlang.tlang_identifier) as tlang_name', 'tlang_slug']);
        $keyword = FatApp::getPostedData('keyword', FatUtility::VAR_STRING, '');
        if (!empty($keyword)) {
            $cnd = $srch->addCondition('tlang_name', 'LIKE', '%' . $keyword . '%');
            $cnd->attachCondition('tlang_identifier', 'LIKE', '%' . $keyword . '%');
        }
        $srch->addCondition('tlang_parent', '=', 0);
        $srch->addCondition('tlang_available', '=', AppConstant::YES);
        $srch->addDirectCondition('tlang_slug IS NOT NULL');
        $srch->addOrder('tlang_order', 'ASC');
        $srch->addOrder('tlang_id', 'ASC');
        $srch->doNotCalculateRecords();
        FatUtility::dieJsonSuccess(['data' => FatApp::getDb()->fetchAll($srch->getResultSet())]);
    }

    /**
     * Auto Complete JSON
     */
    public function autoComplete()
    {
        $keyword = trim(FatApp::getPostedData('keyword', FatUtility::VAR_STRING, ''));
        $type = FatApp::getPostedData('type', FatUtility::VAR_STRING, AppConstant::FILTER_ALL);

        $courses = $teachers = $classes = $languages = [];

        if (Course::isEnabled() && ($type == AppConstant::FILTER_ALL || $type == AppConstant::FILTER_COURSE)) {
            $courses = $this->getCourses($keyword);
        }
        if ($type == AppConstant::FILTER_ALL || $type == AppConstant::FILTER_TEACHER) {
            $teachers = $this->getTeachers($keyword);
        }
        if (GroupClass::isEnabled() && ($type == AppConstant::FILTER_ALL || $type == AppConstant::FILTER_GCLASS)) {
            $classes = $this->getClasses($keyword);
        }
        if ($type == AppConstant::FILTER_ALL || $type == AppConstant::FILTER_LANGUAGE) {
            $languages = $this->getLanguages($keyword);
        }
        $this->sets([
            'courses' => $courses,
            'teachers' => $teachers,
            'classes' => $classes,
            'languages' => $languages,
            'keyword' => $keyword,
        ]);
        $this->_template->render(false, false, 'home/auto-complete.php');
    }

    private function getSearchForm()
    {
        $frm = new Form('frmHomeSearch');
        $frm = CommonHelper::setFormProperties($frm);
        $frm->addTextBox('', 'keyword');
        $frm->addHiddenField('', 'type', AppConstant::FILTER_ALL);
        $btnSubmit = $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_Search'));
        $btnSubmit->attachField($frm->addResetButton('', 'btn_reset', Label::getLabel('LBL_Clear')));
        return $frm;
    }

    public function requestDemo()
    {
        $this->_template->render(false, false);
    }

    /**
     * Function to get courses for autocomplete filter
     *
     * @param string $keyword
     * @return array
     */
    private function getCourses(string $keyword)
    {
        $srch = new CourseSearch($this->siteLangId, $this->siteUserId, 0);
        $srch->applyPrimaryConditions();
        $srch->addCondition('course.course_status', '=', Course::PUBLISHED);
        $srch->addCondition('course.course_active', '=', AppConstant::ACTIVE);
        $srch->addCondition('teacher.user_username', '!=', "");
        $srch->addMultiplefields(['course.course_id as id', 'crsdetail.course_title as name', 'course.course_slug as slug']);
        if (!empty($keyword)) {
            $srch->addCondition('crsdetail.course_title', 'LIKE', '%' . $keyword . '%');
        }
        $srch->applyOrderBy(AppConstant::SORT_POPULARITY);
        $srch->setPageSize(5);
        $srch->doNotCalculateRecords();
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }

    /**
     * Function to get teachers for autocomplete filter
     *
     * @param string $keyword
     * @return array
     */
    private function getTeachers(string $keyword)
    {
        $srch = new TeacherSearch($this->siteLangId, $this->siteUserId, User::LEARNER);
        $srch->applyPrimaryConditions();
        $cnd = $srch->addCondition('teacher.user_first_name', 'LIKE', '%' . $keyword . '%');
        $cnd->attachCondition('teacher.user_last_name', 'LIKE', '%' . $keyword . '%', 'OR');
        $cnd->attachCondition('mysql_func_CONCAT(teacher.user_first_name, " ", teacher.user_last_name)', 'LIKE', '%' . $keyword . '%', 'OR', true);
        $srch->addMultiplefields(['teacher.user_id as id', 'CONCAT(teacher.user_first_name, " ", teacher.user_last_name) as name', 'teacher.user_username as slug']);
        $srch->addOrder('teacher.user_first_name', 'ASC');
        $srch->applyOrderBy(0);
        $srch->setPageSize(5);
        $srch->doNotCalculateRecords();
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }

    /**
     * Function to get group classes for autocomplete filter
     *
     * @param string $keyword
     * @return array
     */
    private function getClasses(string $keyword)
    {
        $srch = new GroupClassSearch($this->siteLangId, $this->siteUserId, User::LEARNER);
        $srch->applyPrimaryConditions();
        $srch->addCondition('grpcls_start_datetime', '>', date('Y-m-d H:i:s'));
        $cond = $srch->addCondition('gclang.grpcls_title', 'LIKE', '%' . $keyword . '%');
        $cond->attachCondition('grpcls.grpcls_title', 'LIKE', '%' . $keyword . '%');
        $srch->addMultipleFields(['grpcls.grpcls_id as id', 'IFNULL(gclang.grpcls_title, grpcls.grpcls_title) as name', 'grpcls.grpcls_slug as slug']);
        $srch->addOrder('grpcls_start_datetime', 'ASC');
        $srch->addOrder('gclang.grpcls_title', 'ASC');
        $srch->setPageSize(5);
        $srch->doNotCalculateRecords();
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }

    /**
     * Function to get languages for autocomplete filter
     * 
     * @return array
     */
    public function getLanguages(string $keyword): array
    {
        if (empty($keyword)) {
            return [];
        }
        $srch = TeachLanguage::getSearchObject($this->siteLangId, true);
        $cond = $srch->addCondition('tlang_identifier', 'LIKE', '%' . $keyword . '%');
        $cond->attachCondition('tlang_name', 'LIKE', '%' . $keyword . '%', 'OR', true);
        $srch->addCondition('tlang_parent', '=', 0);
        $srch->addCondition('tlang_available', '=', AppConstant::YES);
        $srch->addMultipleFields([
            'tlang.tlang_id as id', 'IFNULL(tlanglang.tlang_name, tlang.tlang_identifier) as name', 'tlang.tlang_slug as slug'
        ]);
        $srch->setPageSize(5);
        $srch->doNotCalculateRecords();
        return FatApp::getDb()->fetchAll($srch->getResultSet());
    }
}
