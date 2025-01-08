<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$langId = MyUtility::getSiteLangId();
$websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_' . $langId, FatUtility::VAR_STRING, '');
$bookingDuration = '';
$disabledClass = '';
$bookNowOnClickClick = 'onclick="cart.langSlots(' . $teacher['user_id'] . ',\'\',\'\');"';
$contactClick = 'onclick="threadForm(' . $teacher['user_id'] . ',' . Thread::PRIVATE . ');"';
if ($siteUserId == $teacher['user_id']) {
    $disabledClass = 'disabled';
    $bookNowOnClickClick = '';
    $contactClick = '';
}
$isCourseEnabled = Course::isEnabled();
$totalSessions = $teacher['testat_lessons'];
if (GroupClass::isEnabled()) {
    $totalSessions =  $totalSessions + $teacher['testat_classes'];
} else {
    $classes = [];
}
?>
<section class="section section--profile">
    <div class="container container--fixed">
        <div class="profile-cover">
            <div class="profile-head">
                <div class="detail-wrapper">
                    <div class="profile__media">
                        <div class="avtar avtar--xlarge" data-title="<?php echo CommonHelper::getFirstChar($teacher['user_first_name']); ?>">
                            <?php
                            $img = FatCache::getCachedUrl(MyUtility::makeUrl('Image', 'show', [Afile::TYPE_USER_PROFILE_IMAGE, $teacher['user_id'], Afile::SIZE_MEDIUM]), CONF_DEF_CACHE_TIME, '.' . current(array_reverse(explode(".", $teacher['user_photo']))));
                            echo '<img src="' . $img . '" alt="' . $teacher['user_first_name'] . ' ' . $teacher['user_last_name'] . '" />';
                            ?>
                        </div>
                        <?php if (!empty($teacher['user_online']['show']) || !empty($teacher['user_featured'])) { ?>
                            <div class="avtar-elements">
                                <?php if (!empty($teacher['user_featured'])) { ?>
                                    <div class="margin-right-auto">
                                        <div class="badge-secure is-hover">
                                            <svg class="icon icon--featured" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 384">
                                                <g>
                                                    <path d="m202.11,0c7.6,1.24,15.29,2.11,22.79,3.79,56.77,12.76,101.83,60.76,111.26,118.29,11.45,69.9-25.96,137.29-92.18,162.05-55.47,20.74-106.31,9.96-150.42-29.48-25.32-22.64-39.92-51.66-45.46-85.21-9.59-58.06,19.35-119.27,70.33-149.04C137.55,9.24,158.02,2.4,180.13.6c.85-.07,1.68-.39,2.51-.6,6.49,0,12.97,0,19.46,0Zm65.58,128.01c-.13-7.98-4.29-12.25-10.99-13.22-10.85-1.58-21.69-3.17-32.55-4.67-2.95-.41-5.02-1.27-6.46-4.42-4.61-10.09-9.78-19.92-14.6-29.92-2.18-4.52-5.57-6.89-10.57-6.93-5.17-.05-8.64,2.37-10.88,7.04-4.86,10.12-9.99,20.1-14.79,30.25-1.16,2.44-2.64,3.55-5.22,3.9-10.99,1.49-21.94,3.22-32.93,4.66-5.12.67-9.18,2.68-10.92,7.73-1.73,5.02-.02,9.19,3.76,12.82,8.29,7.94,16.5,15.97,24.57,24.13,1.07,1.08,1.8,3.26,1.57,4.76-1.72,11.35-3.59,22.68-5.69,33.96-.96,5.19.04,9.47,4.33,12.61,4.36,3.19,8.81,2.5,13.34.06,9.87-5.31,19.86-10.42,29.71-15.77,2.23-1.21,3.94-1.17,6.14.02,10.3,5.57,20.68,10.99,31.05,16.43,4.13,2.16,8.24,1.99,11.98-.79,3.85-2.85,5.24-6.79,4.42-11.56-2-11.56-3.85-23.14-5.98-34.67-.42-2.29.08-3.66,1.69-5.21,8.19-7.87,16.4-15.72,24.31-23.87,2.37-2.44,3.78-5.83,4.73-7.34Z" />
                                                    <path d="m125.75,384c-6.03-1.71-9.2-5.91-11.49-11.64-5.13-12.84-10.83-25.46-16.31-38.17-.49-1.13-1.05-2.23-1.71-3.64-10.34,3.52-20.55,7-30.77,10.46-5.54,1.88-11.08,3.74-16.63,5.59-4.67,1.56-8.91.86-12.33-2.82-3.44-3.68-3.9-8.04-1.93-12.52,10.32-23.38,20.72-46.72,31.09-70.07.18-.41.42-.8.85-1.6,26.69,29.35,59.26,47.51,98.36,54.49-.62,1.54-1.07,2.77-1.6,3.97-8.27,18.69-16.64,37.35-24.79,56.1-1.93,4.43-4.41,8.02-9,9.85h-3.74Z" />
                                                    <path d="m255.25,384c-4.6-1.82-7.07-5.43-8.99-9.85-8.15-18.75-16.51-37.41-24.8-56.11-.5-1.13-.97-2.27-1.67-3.89,39-6.97,71.73-25.08,98.53-54.68,3.15,7.1,6.06,13.68,8.98,20.25,7.54,16.98,15.12,33.94,22.6,50.95,3.12,7.08.38,14.29-6.42,16.05-2.7.7-6.01.35-8.71-.53-14.56-4.73-29.01-9.78-43.5-14.72-.82-.28-1.65-.51-2.79-.86-.59,1.27-1.19,2.47-1.72,3.7-5.37,12.48-10.93,24.89-16.02,37.49-2.25,5.57-5.1,10.15-10.99,12.21h-4.49Z" />
                                                </g>
                                            </svg>
                                            <div class="tooltip tooltip--top tooltip--round bg-black no-wrap"><?php echo Label::getLabel('LBL_FEATURED'); ?></div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if (!empty($teacher['user_online']['show'])) {
                                ?>
                                    <div class="margin-left-auto">
                                        <span class="status status--<?php echo $teacher['user_online']['class']; ?> is-hover">
                                            <span class="status__badge"></span>
                                            <div class="tooltip tooltip--top tooltip--round bg-black no-wrap"><?php echo $teacher['user_online']['tooltip']; ?></div>
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="profile-detail">
                        <div class="profile-detail__head">
                            <div class="tutor-name">
                                <h1><?php echo $teacher['user_first_name'] . ' ' . $teacher['user_last_name']; ?></h1>
                                <div class="flag">
                                    <?php if ($teacher['user_country_id'] > 0) { ?>
                                        <img src="<?php echo CONF_WEBROOT_FRONTEND . 'flags/' . strtolower($teacher['user_country_code']) . '.svg'; ?>" alt="<?php echo $teacher['user_country_name']; ?>" style="border: 1px solid #000;" />
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if (!empty($teacher['offers'])) { ?>
                                <?php $this->includeTemplate('_partial/offers.php', ['offers' => $teacher['offers']], false); ?>
                            <?php } ?>
                        </div>
                        <div class="profile-detail__body">
                            <div class="info-wrapper">
                                <div class="info-tag location">
                                    <svg class="icon icon--location">
                                        <use xlink:href=" <?php echo CONF_WEBROOT_URL . 'images/sprite.svg#location' ?>"></use>
                                    </svg>
                                    <span class="lacation__name"><?php echo $teacher['user_country_name']; ?></span>
                                </div>
                                <div class="info-tag ratings">
                                    <svg class="icon icon--rating">
                                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#rating' ?>"></use>
                                    </svg>
                                    <span class="value"><?php echo $teacher['testat_ratings']; ?></span>
                                    <span class="count"><?php echo $teacher['testat_reviewes'] . ' ' . Label::getLabel('LBL_REVIEW(S)'); ?></span>
                                </div>
                                <div class="info-tag list-count">
                                    <div class="total-count"><span class="value"><?php echo $teacher['testat_students']; ?></span><?php echo Label::getLabel('LBL_Students') ?></div>
                                    <div class="total-count"><span class="value"><?php echo $totalSessions; ?></span><?php echo Label::getLabel('LBL_SESSIONS'); ?></div>
                                    <?php if ($isCourseEnabled) { ?>
                                        <div class="total-count"><span class="value"><?php echo $teacher['courses']; ?></span><?php echo Label::getLabel('LBL_COURSES'); ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="har-rate"><?php echo Label::getLabel('LBL_TEACHER_PRICING'); ?><b> <?php echo MyUtility::formatMoney($teacher['testat_minprice']); ?> - <?php echo MyUtility::formatMoney($teacher['testat_maxprice']); ?></b></div>
                            <div class="tutor-lang">
                                <b><?php echo Label::getLabel('LBL_TEACHES:'); ?></b> 
                                <?php echo $teacher['teacherTeachLanguageName']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="detail-actions">
                        <?php
                        $disabledText = 'disabled';
                        $onclick = "";
                        if ($siteUserId != $teacher['user_id']) {
                            $disabledText = '';
                            $onclick = 'onclick="toggleTeacherFavorite(' . $teacher["user_id"] . ', this)"';
                        }
                        ?>
                        <a href="javascript:void(0);" <?php echo $onclick; ?> class="btn btn--bordered color-black <?php echo $disabledText; ?> <?php echo ($teacher['uft_id']) ? 'is--active' : ''; ?>" <?php echo $disabledText; ?>>
                            <svg class="icon icon--heart">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#heart'; ?>"></use>
                            </svg>
                            <?php echo Label::getLabel('LBL_FAVORITE'); ?>
                        </a>
                        <div class="toggle-dropdown">
                            <a href="#" class="btn btn--bordered color-black toggle-dropdown__link-js">
                                <svg class="icon icon--share">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#share'; ?>"></use>
                                </svg>
                                <?php echo Label::getLabel('LBL_Share'); ?>
                            </a>
                            <div class="toggle-dropdown__target toggle-dropdown__target-js">
                                <h6><?php echo Label::getLabel('LBL_SHARE_ON'); ?></h6>
                                <ul class="social--share clearfix">
                                    <li class="social--fb"><a class='st-custom-button' data-network="facebook" displayText='<?php echo Label::getLabel('LBL_FACEBOOK'); ?>' title='<?php echo Label::getLabel('LBL_FACEBOOK'); ?>'><img src="<?php echo CONF_WEBROOT_URL; ?>images/social_01.svg" alt="<?php echo Label::getLabel('LBL_FACEBOOK'); ?>"></a></li>
                                    <li class="social--tw"><a class='st-custom-button' data-network="twitter" displayText='<?php echo Label::getLabel('LBL_X'); ?>' title='<?php echo Label::getLabel('LBL_X'); ?>'><img src="<?php echo CONF_WEBROOT_URL; ?>images/social_02.svg" alt="<?php echo Label::getLabel('LBL_X'); ?>"></a></li>
                                    <li class="social--pt"><a class='st-custom-button' data-network="pinterest" displayText='<?php echo Label::getLabel('LBL_PINTEREST'); ?>' title='<?php echo Label::getLabel('LBL_PINTEREST'); ?>'><img src="<?php echo CONF_WEBROOT_URL; ?>images/social_05.svg" alt="<?php echo Label::getLabel('LBL_PINTEREST'); ?>"></a></li>
                                    <li class="social--mail"><a class='st-custom-button' data-network="email" displayText='<?php echo Label::getLabel('LBL_EMAIL'); ?>' title='<?php echo Label::getLabel('LBL_EMAIL'); ?>'><img src="<?php echo CONF_WEBROOT_URL; ?>images/social_06.svg" alt="<?php echo Label::getLabel('LBL_EMAIL'); ?>"></a></li>
                                </ul>
                            </div>
                        </div>
                        <a href="#lessons-prices" class="color-primary btn--link scroll"><?php Label::getLabel('LBL_VIEW_LESSONS_PACKAGES'); ?></a>
                    </div>
                </div>
            </div>
            <div class="profile-primary">
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js is-active">
                        <h3><?php echo Label::getLabel('LBL_About'); ?> <?php echo $teacher['user_first_name'] . ' ' . $teacher['user_last_name']; ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js" style="display:block;">
                        <div class="content__row">
                            <p><?php echo nl2br($teacher['user_biography']); ?></p>
                        </div>
                        <div class="content__row">
                            <h4><?php echo Label::getLabel('LBL_Speaks'); ?></h4>
                            <p><?php $this->includeTemplate('teachers/_partial/SpeakLanguages.php', $teacher, false); ?></p>
                        </div>
                    </div>
                </div>

                <div class="panel-cover ">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_Pricing') ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="table-md-scroll">
                            <table class="table table--aligned-middle table--pricing">
                                <thead>
                                    <tr>
                                        <th>
                                            <?php echo Label::getLabel('LBL_TEACHING_LANGUAGES'); ?>
                                        </th>
                                        <th>
                                            <?php echo Label::getLabel('LBL_SLOT_PRICE'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $firstSlot = current($teacher['user_slots']); ?>
                                    <?php foreach ($userLangData as $row) { ?>
                                        <tr>
                                            <td> <?php echo $row['tlang_name']; ?></td>
                                            <td>
                                                <div class="d-inline-flex align-items-center">
                                                    <div class="small-field mx-3">
                                                        <?php foreach ($teacher['user_slots'] as $slot) { ?>
                                                            <span onclick="cart.langSlots('<?php echo $teacher['user_id']; ?>', <?php echo $row['tlang_id'] ?>, <?php echo $slot ?>)" class="price-<?php echo $slot ?> cursor-pointer" style="display: <?php echo ($slot == $firstSlot) ? 'block' : 'none'; ?>;">
                                                                <?php echo MyUtility::formatMoney(MyUtility::slotPrice($row['utlang_price'] ?? 0, $slot)); ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <select class="price-select" name="slots" onchange="showPrice(this);">
                                                        <?php foreach ($teacher['user_slots'] as $slot) { ?>
                                                            <option value="<?php echo $slot ?>"><?php echo $slot ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="panel-cover panel--calendar">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js calendar--trigger-js">
                        <h3><?php echo Label::getLabel('LBL_Schedule') ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js">
                        <div class="calendar-wrapper">
                            <div id="availbility" class="calendar-wrapper__body"></div>
                        </div>
                        <div class="-gap"></div>
                        <div class="note note--blank note--vertical-border">
                            <svg class="icon icon--sound">
                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#sound'; ?>"></use>
                            </svg>
                            <p>
                                <b><?php echo Label::getLabel('LBL_Note:') ?></b>
                                <?php echo Label::getLabel('LBL_NOT_FINDING_YOUR_IDEAL_TIME'); ?>
                                <a class="bold-600" href="javascript:void(0)" <?php echo $contactClick; ?>><?php echo Label::getLabel('LBL_Contact'); ?></a>
                                <?php echo Label::getLabel('LBL_REQUEST_A_SLOT_OUTSIDE_OF_THEIR_CURRENT_SCHEDULE'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php if (count($classes) > 0) { ?>

                    <div class="panel-cover">
                        <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                            <h3><?php echo Label::getLabel('LBL_GROUP_CLASSES'); ?></h3>
                        </div>
                        <div class="panel-cover__body panel__body-target panel__body-target-js">
                            <div class="slider author-slider slider--onethird slider-onethird-js">
                                <?php
                                foreach ($classes as $class) {
                                    $classData = ['class' => $class, 'siteUserId' => $siteUserId, 'bookingBefore' => $bookingBefore, 'cardClass' => 'card-class-cover'];
                                    $this->includeTemplate('group-classes/card.php', $classData, false);
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_TEACHING_EXPERTISE'); ?></h3>
                    </div>
                    <?php
                    foreach ($preferencesType as $type => $preference) {
                        if (empty($userPreferences[$type])) {
                            continue;
                        }
                    ?>
                        <div class="panel-cover__body panel__body-target panel__body-target-js">
                            <div class="content-wrapper content--tick">
                                <div class="content__head">
                                    <h4><?php echo $preference; ?></h4>
                                </div>
                                <div class="content__body">
                                    <div class="tick-listing tick-listing--onethird">
                                        <ul>
                                            <?php foreach ($userPreferences[$type] as $preference) { ?>
                                                <li><?php echo $preference['prefer_title']; ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="panel-cover">
                    <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                        <h3><?php echo Label::getLabel('LBL_TEACHING_QUALIFICATIONS'); ?></h3>
                    </div>
                    <div class="panel-cover__body panel__body-target panel__body-target-js" id="qualificationsList">
                        <?php
                        foreach ($qualificationType as $type => $name) {
                            if (empty($userQualifications[$type])) {
                                continue;
                            }
                            $first = true;
                        ?>
                            <div class="row row--resume">
                                <?php foreach ($userQualifications[$type] as $qualification) { ?>
                                    <div class="col-xl-4 col-lg-4 col-sm-4">
                                        <?php
                                        if ($first) {
                                            $first = false;
                                        ?>
                                            <h4 class="color-dark"><?php echo $name; ?></h4>
                                        <?php } ?>
                                    </div>
                                    <div class="col-xl-8 col-lg-8 col-sm-8">
                                        <div class="resume-wrapper">
                                            <div class="row">
                                                <div class="col-4 col-sm-4">
                                                    <div class="resume__primary"><b><?php echo $qualification['uqualification_start_year']; ?> - <?php echo $qualification['uqualification_end_year']; ?></b></div>
                                                </div>
                                                <div class="col-7 col-sm-7 offset-1">
                                                    <div class="resume__secondary">
                                                        <b><?php echo $qualification['uqualification_title']; ?></b>
                                                        <p class="-no-margin-bottom"><?php echo $qualification['uqualification_institute_name']; ?></p>
                                                        <p class="-no-margin-bottom"><?php echo $qualification['uqualification_institute_address']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($teacher['testat_reviewes'] > 0) { ?>
                    <div class="panel-cover">
                        <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                            <h3><?php echo Label::getLabel('LBL_REVIEW'); ?></h3>
                        </div>
                        <?php echo $reviewFrm->getFormHtml(); ?>
                        <div class="panel-cover__body panel__body-target panel__body-target-js">
                            <div class="rating-details">
                                <div class="rating__count">
                                    <h1><?php echo $teacher['testat_ratings']; ?></h1>
                                </div>
                                <div class="rating__info">
                                    <b><?php echo Label::getLabel('LBL_OVERALL_RATINGS'); ?></b>
                                </div>
                            </div>
                            <div class="reviews-wrapper">
                                <div class="reviews-wrapper__head">
                                    <p id="recordToDisplay"></p>
                                    <div class="review__shorting">
                                        <select name="sorting" onchange="loadReviews('<?php echo $teacher['user_id']; ?>', 1)">
                                            <?php $sortArr = RatingReview::getSortTypes(); ?>
                                            <?php foreach ($sortArr as $key => $value) { ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="listing-reviews" class="reviews-wrapper__body"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($isCourseEnabled && $moreCourses) { ?>
                    <div class="panel-cover">
                        <div class="panel-cover__head panel__head-trigger panel__head-trigger-js">
                            <h3>
                                <?php
                                echo Label::getLabel('LBL_COURSES');
                                ?>
                            </h3>
                        </div>
                        <div class="panel-cover__body panel__body-target panel__body-target-js">
                            <?php
                            echo $this->includeTemplate('teachers/courses.php', [
                                'moreCourses' => $moreCourses,
                                'checkoutForm' => $checkoutForm,
                                'siteLangId' => $siteLangId,
                                'siteUserId' => $siteUserId,
                            ]);
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="profile-secondary">

                <?php if (!empty(MyUtility::validateYoutubeUrl($teacher['user_video_link']))) { ?>
                    <div class="dummy-video">
                        <div class="video-media ratio ratio--16by9">
                            <iframe width="100%" height="100%" src="<?php echo MyUtility::validateYoutubeUrl($teacher['user_video_link']); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="-gap"></div>
                <?php
                }
                ?>

                <div class="right-panel">

                    <div class="box box--book">
                        <div class="book__actions">
                            <a href="javascript:void(0);" class="btn btn--primary btn--xlarge btn--block color-white <?php echo $disabledClass; ?>" <?php echo $bookNowOnClickClick; ?>><?php echo Label::getLabel('LBL_Book_Now'); ?></a>
                            <a href="javascript:void(0);" <?php echo $contactClick; ?> class="btn btn--bordered btn--xlarge btn--block btn--contact color-primary <?php echo $disabledClass; ?>">
                                <svg class="icon icon--envelope">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#envelope'; ?>"></use>
                                </svg>
                                <?php echo Label::getLabel('LBL_CONTACT'); ?>
                            </a>
                            <a href="#availbility" onclick="viewFullAvailbility()" class="color-primary btn--link scroll"><?php echo Label::getLabel('LBL_VIEW_FULL_AVAILBILITY'); ?></a>
                            <div class="-gap"></div>
                            <div class="-gap"></div>
                            <?php
                            if ($freeTrialEnabled) {
                                $btnText = "LBL_YOU_ALREADY_HAVE_AVAILED_THE_TRIAL";
                                $onclick = "";
                                $btnClass = "btn-secondary";
                                $disabledText = "disabled";
                                if (!$isFreeTrailAvailed) {
                                    $disabledText = "";
                                    $onclick = "onclick=\"cart.trailCalendar('" . $teacher['user_id'] . "')\"";
                                    $btnClass = 'btn-primary';
                                    $btnText = "LBL_BOOK_FREE_TRIAL";
                                }
                                if ($siteUserId == $teacher['user_id']) {
                                    $onclick = "";
                                    $disabledText = "disabled";
                                }
                            ?>
                                <a href="javascript:void(0);" <?php echo $onclick; ?> class="btn btn--secondary btn--trial btn--block color-white <?php echo $btnClass . ' ' . $disabledText; ?> " <?php echo $disabledText; ?>>
                                    <span><?php echo Label::getLabel($btnText); ?></span>
                                </a>
                                <p><?php echo Label::getLabel('LBL_TRIAL_LESSON_ONE_TIME'); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function() {
        viewFullAvailbility = function() {
            if ($(window).width() < 768) {
                if (!$('.calendar--trigger-js').hasClass('is-active')) {
                    $('.calendar--trigger-js').click();
                }
            }
        };
        viewCalendar(<?php echo $teacher['user_id'] . ', "paid"'; ?>);
        $('.panel__head-trigger-js').click(function() {
            if ($(this).hasClass('is-active')) {
                $(this).removeClass('is-active');
                $(this).siblings('.panel__body-target-js').slideUp();
                return false;
            }
            $('.panel__head-trigger-js').removeClass('is-active');
            $(this).addClass("is-active");
            $('.panel__body-target-js').slideUp();
            $(this).siblings('.panel__body-target-js').slideDown();
            $('.slider-onethird-js').slick('reinit');
            if ($(this).hasClass('calendar--trigger-js')) {
                window.viewOnlyCal.render();
            }
        });
        <?php if ($teacher['testat_reviewes'] > 0) { ?>
            loadReviews('<?php echo $teacher['user_id']; ?>', 1);
        <?php } ?>
    });
</script>
<?php echo $this->includeTemplate('_partial/shareThisScript.php'); ?>