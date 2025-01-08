<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
if(!$langPage) {
    $sorting = AppConstant::getSortbyArr();
}
$hourStringLabel = Label::getLabel('LBL_{hourstring}_HRS');
$offerPriceLabel = Label::getLabel('LBL_{percentages}%_OFF_ON_{duration}_MINUTES_SESSION');
$isCourseEnabled = Course::isEnabled();
$colorClass = [1 => 'cell-green-40', 2 => 'cell-green-60', 3 => 'cell-green-80', 4 => 'cell-green-100'];
?>
<div class="page-listing__head">
    <?php if(!$langPage) { ?>
    <div class="row justify-content-between align-items-center">
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-center">
                <div class="switch-options">
                    <div class="switch-options__item">
                        <label class="switch-action is-hover switch-filter">
                            <span class="switch switch--small">
                                <input class="switch__label" type="checkbox" onclick="searchOnline(this.checked);" <?php echo empty($post['user_lastseen'] ?? '') ? '' : 'checked'; ?> />
                                <i class="switch__handle bg-green"></i>
                            </span>
                            <span class="switch-action-label margin-left-2"><?php echo Label::getLabel('LBL_ACTIVE_TEACHERS'); ?></span>
                            <span class="tooltip tooltip--top bg-black">
                                <span class="tooltip__content"><?php echo Label::getLabel('LBL_TEACHERS_WHO_ARE_CONNECTED_RIGHT_NOW'); ?></span>
                            </span>
                        </label>
                    </div>
                    <?php if (User::offlineSessionsEnabled()) { ?>
                        <div class="switch-options__item">
                            <label class="switch-action is-hover switch-filter">
                                <span class="switch switch--small">
                                    <input class="switch__label" type="checkbox" onclick="searchOfflineSessions(this.checked);" <?php echo empty($post['user_offline_sessions'] ?? '') ? '' : 'checked'; ?> />
                                    <i class="switch__handle bg-green"></i>
                                </span>
                                <span class="switch-action-label no-wrap  margin-left-2"><?php echo Label::getLabel('LBL_OFFLINE_SESSIONS'); ?></span>
                                <span class="tooltip tooltip--top bg-black">
                                    <span class="tooltip__content"><?php echo Label::getLabel('LBL_TEACHERS_WHO_PROVIDE_OFFLINE_LECTURES'); ?></span>
                                </span>
                            </label>
                        </div>
                        <div class="geo-location_body switch-options__item" style="display: none;">
                            <div class="geo-location">
                                <div class="geo-location-wrap">
                                    <input class="geo-location_input pac-target-input" id="google-autocomplete" size="50" placeholder="<?php echo Label::getLabel('LBL_ADDRESS'); ?>" type="search" name="address" value="<?php echo $post['formatted_address'] ?>">
                                    <span class="close btn-close" id="btnCloseJs" onclick="clearLocation();" style="display:<?php echo empty($post['formatted_address']) ? 'none' : 'block' ?>"></span>
                                    <button class="btn-detect" type="button" onclick="getLocation();">
                                        <svg class="svg" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.05492878,13 L1,13 L1,11 L3.05492878,11 C3.5160776,6.82838339 6.82838339,3.5160776 11,3.05492878 L11,1 L13,1 L13,3.05492878 C17.1716166,3.5160776 20.4839224,6.82838339 20.9450712,11 L23,11 L23,13 L20.9450712,13 C20.4839224,17.1716166 17.1716166,20.4839224 13,20.9450712 L13,23 L11,23 L11,20.9450712 C6.82838339,20.4839224 3.5160776,17.1716166 3.05492878,13 Z M12,5 C8.13400675,5 5,8.13400675 5,12 C5,15.8659932 8.13400675,19 12,19 C15.8659932,19 19,15.8659932 19,12 C19,8.13400675 15.8659932,5 12,5 Z M12,8 C14.209139,8 16,9.790861 16,12 C16,14.209139 14.209139,16 12,16 C9.790861,16 8,14.209139 8,12 C8,9.790861 9.790861,8 12,8 Z M12,10 C10.8954305,10 10,10.8954305 10,12 C10,13.1045695 10.8954305,14 12,14 C13.1045695,14 14,13.1045695 14,12 C14,10.8954305 13.1045695,10 12,10 Z" />
                                        </svg>
                                        <span class="btn-detect-txt"> <?php echo Label::getLabel('LBL_DETECT_MY_LOCATION'); ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="switch-options__item">
                        <label class="switch-action is-hover switch-filter">
                            <span class="switch switch--small">
                                <input class="switch__label" type="checkbox" onclick="searchFeatured(this.checked);" <?php echo empty($post['user_featured'] ?? '') ? '' : 'checked'; ?> />
                                <i class="switch__handle bg-green"></i>
                            </span>
                            <span class="switch-action-label margin-left-2"><?php echo Label::getLabel('LBL_FEATURED'); ?></span>
                            <span class="tooltip tooltip--top bg-black">
                                <span class="tooltip__content"><?php echo Label::getLabel('LBL_TEACHERS_WHO_ARE_FEATURED_AND_VERIFIED_BY_ADMIN'); ?></span>
                            </span>
                        </label>
                    </div>
                    <div class="sorting-options">
                        <div class="sorting-options__item">
                            <div class="sorting-action">
                                <div class="sorting-action__trigger sort-trigger-js switch-filter" onclick="toggleSort();">
                                    <span class="sorting-action__label"><?php echo Label::getLabel('LBL_SORT'); ?>:</span>
                                    <span class="sorting-action__value"><?php echo $sorting[$post['sorting']]; ?></span>
                                </div>
                                <div class="sorting-action__target sort-target-js" style="display: none;">
                                    <div class="filter-dropdown">
                                        <div class="select-list select-list--vertical select-list--scroll">
                                            <ul>
                                                <?php foreach ($sorting as $id => $name) { ?>
                                                    <li>
                                                        <label class="select-option">
                                                            <input class="select-option__input" type="radio" name="sorts" value="<?php echo $id; ?>" <?php echo ($id == $post['sorting']) ? 'checked' : ''; ?> onclick="sortsearch(this.value);" />
                                                            <span class="select-option__item"><?php echo $name; ?></span>
                                                        </label>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sorting-options__item">
                            <div class="btn btn--filters" onclick="openFilter()">
                                <span class="svg-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 402.577 402.577" style="enable-background:new 0 0 402.577 402.577;" xml:space="preserve">
                                        <g>
                                            <path d="M400.858,11.427c-3.241-7.421-8.85-11.132-16.854-11.136H18.564c-7.993,0-13.61,3.715-16.846,11.136
                                            c-3.234,7.801-1.903,14.467,3.999,19.985l140.757,140.753v138.755c0,4.955,1.809,9.232,5.424,12.854l73.085,73.083
                                            c3.429,3.614,7.71,5.428,12.851,5.428c2.282,0,4.66-0.479,7.135-1.43c7.426-3.238,11.14-8.851,11.14-16.845V172.166L396.861,31.413
                                            C402.765,25.895,404.093,19.231,400.858,11.427z"></path>
                                        </g>
                                    </svg>
                                </span>
                                <?php echo Label::getLabel('LBL_FILTERS'); ?>
                                <?php
                                $count = 0;
                                foreach ($post as $field) {
                                    if (is_array($field)) {
                                        $count += count($field);
                                    }
                                }
                                if ($count > 0) {
                                ?>
                                    <span class="filters-count"><?php echo $count; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="search-result text-center pt-4 mt-3">
        <h4><?php echo str_replace('{recordcount}', $recordCount, Label::getLabel('LBL_FOUND_THE_BEST_{recordcount}_TEACHERS_FOR_YOU')) ?></h4>
    </div>
</div>
<?php if (count($teachers)) { ?>
    <div class="page-listing__body">
        <div class="box-wrapper">
            <?php foreach ($teachers as $teacher) {
                $totalSessions = $teacher['testat_lessons'];
                if (GroupClass::isEnabled()) {
                    $totalSessions =  $totalSessions + $teacher['testat_classes'];
                }
            ?>
                <div class="box box-list box-responsive">
                    <div class="box__primary">
                        <div class="list__head">
                            <div class="list__media ">
                                <div class="avtar avtar--centered" data-title="<?php echo CommonHelper::getFirstChar($teacher['user_first_name']); ?>">
                                    <a href="<?php echo MyUtility::makeUrl('teachers', 'view', [$teacher['user_username']]) ?>">
                                        <img src="<?php echo FatCache::getCachedUrl(MyUtility::makeUrl('Image', 'show', [Afile::TYPE_USER_PROFILE_IMAGE, $teacher['user_id'], Afile::SIZE_MEDIUM]), CONF_DEF_CACHE_TIME, '.' . current(array_reverse(explode(".", $teacher['user_photo'])))); ?>" alt="<?php echo $teacher['user_first_name'] . ' ' . $teacher['user_last_name']; ?>">
                                    </a>
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
                                        <?php }
                                        if (!empty($teacher['user_online']['show'])) { ?>
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
                        </div>
                        <div class="list__body">
                            <div class="profile-detail">
                                <div class="profile-detail__head">
                                    <a href="<?php echo MyUtility::makeUrl('teachers', 'view', [$teacher['user_username']]) ?>" class="tutor-name">
                                        <h4><?php echo $teacher['user_first_name'] . ' ' . $teacher['user_last_name']; ?></h4>
                                        <div class="flag">
                                            <img src="<?php echo CONF_WEBROOT_FRONTEND . 'flags/' . strtolower($teacher['user_country_code']) . '.svg'; ?>" alt="<?php echo $teacher['user_country_name']; ?>" style="height: 22px;border: 1px solid #000;" />
                                        </div>
                                    </a>
                                    <?php if (!empty($teacher['offers'])) { ?>
                                        <?php $this->includeTemplate('_partial/offers.php', ['offers' => $teacher['offers'], 'offerPriceLabel' => $offerPriceLabel], false); ?>
                                    <?php } ?>
                                    <div class="follow ">
                                        <?php if ($siteUserId != $teacher['user_id']) { ?>
                                            <a class="<?php echo ($teacher['uft_id']) ? 'is--active' : ''; ?>" onClick="toggleTeacherFavorite(<?php echo $teacher['user_id']; ?>, this)" href="javascript:void(0)">
                                                <svg class="icon icon--heart">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#heart'; ?>"></use>
                                                </svg>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="profile-detail__body">
                                    <div class="info-wrapper">
                                        <div class="info-tag location">
                                            <svg class="icon icon--location">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#location'; ?>"></use>
                                            </svg>
                                            <span class="lacation__name"><?php echo $teacher['user_country_name']; ?></span>
                                        </div>
                                        <div class="info-tag ratings">
                                            <svg class="icon icon--rating">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#rating'; ?>"></use>
                                            </svg>
                                            <span class="value"><?php echo $teacher['testat_ratings']; ?></span>
                                            <span class="count">(<?php echo $teacher['testat_reviewes']; ?>)</span>
                                        </div>
                                        <div class="info-tag list-count">
                                            <div class="total-count"><span class="value"><?php echo $teacher['testat_students']; ?></span><?php echo Label::getLabel('LBL_Students'); ?></div>
                                            <div class="total-count"><span class="value"><?php echo $totalSessions; ?></span><?php echo Label::getLabel('LBL_Sessions'); ?></div>
                                            <?php if ($isCourseEnabled) { ?>
                                                <div class="total-count"><span class="value"><?php echo $teacher['courses']; ?></span><?php echo Label::getLabel('LBL_COURSES'); ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="tutor-info">
                                        <div class="tutor-info__inner">
                                            <div class="info__title">
                                                <h6><?php echo Label::getLabel('LBL_Teaches'); ?></h6>
                                            </div>
                                            <div class="info__language">
                                                <div class="info__language">
                                                    <?php echo $teacher['teacherTeachLanguageName'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tutor-info__inner d-none d-sm-block">
                                            <div class="info__title">
                                                <h6><?php echo Label::getLabel('LBL_Speaks'); ?></h6>
                                            </div>
                                            <div class="info__language">
                                                <?php echo $teacher['spoken_language_names']; ?>
                                            </div>
                                        </div>
                                        <div class="tutor-info__inner info--about d-none d-sm-block">
                                            <div class="info__title">
                                                <h6><?php echo LABEL::getLabel('LBL_About'); ?></h6>
                                            </div>
                                            <div class="about__detail">
                                                <p><?php echo nl2br($teacher['user_biography']) ?></p>
                                                <a href="<?php echo MyUtility::makeUrl('teachers', 'view', [$teacher['user_username']]) ?>"><?php echo Label::getLabel('LBL_View_Profile') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list__action">
                            <div class="list__price">
                                <p><?php echo MyUtility::formatMoney($teacher['testat_minprice']) . ' - ' . MyUtility::formatMoney($teacher['testat_maxprice']); ?></p>
                            </div>
                            <div class="list__action-btn">
                                <?php if ($siteUserId != $teacher['user_id']) { ?>
                                    <a href="javascript:void(0);" onclick="cart.langSlots('<?php echo $teacher['user_id']; ?>', '', '')" class="btn btn--primary btn--block"><?php echo Label::getLabel('LBL_Book_Now'); ?></a>
                                    <a href="javascript:void(0);" onclick="threadForm(<?php echo $teacher['user_id']; ?>,<?php echo Thread::PRIVATE ?>);" class="btn btn--bordered color-primary btn--block">
                                        <svg class="icon icon--envelope">
                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#envelope'; ?>"></use>
                                        </svg>
                                        <?php echo Label::getLabel('LBL_Contact'); ?>
                                    </a>
                                <?php } else { ?>
                                    <a href="javascript:void(0);" class="btn btn--primary btn--block disabled"><?php echo Label::getLabel('LBL_Book_Now'); ?></a>
                                    <a href="javascript:void(0);" class="btn btn--bordered color-primary btn--block disabled">
                                        <svg class="icon icon--envelope">
                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#envelope'; ?>"></use>
                                        </svg>
                                        <?php echo Label::getLabel('LBL_Contact'); ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <a href="javascript:void(0);" onclick="viewCalendar(<?php echo $teacher['user_id']; ?>, 'paid')" class="link-detail"><?php echo Label::getLabel('LBL_View_Full_availability'); ?></a>
                        </div>
                    </div>
                    <div class="box__secondary">
                        <div class="panel-box">
                            <div class="panel-box__head">
                                <ul>
                                    <li class="is--active">
                                        <a class="panel-action" content="calender" href="javascript:void(0)"><?php echo Label::getLabel('LBL_Availability'); ?></a>
                                    </li>
                                    <?php if (!empty($teacher['user_video_link'])) { ?>
                                        <li>
                                            <a class="panel-action" content="video" href="javascript:void(0)"><?php echo Label::getLabel('LBL_Introduction'); ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="panel-box__body">
                                <div class="panel-content calender">
                                    <div class="custom-calendar">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th><?php echo Label::getLabel('LBL_Sun'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Mon'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Tue'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Wed'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Thu'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Fri'); ?></th>
                                                    <th><?php echo Label::getLabel('LBL_Sat'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $timeslots = $teacher['testat_timeslots'] ?? AppConstant::getEmptyDaySlots(); ?>
                                                <?php foreach ($slots as $index => $slot) { ?>
                                                    <tr>
                                                        <td>
                                                            <div class="cal-cell"><?php echo $slot; ?></div>
                                                        </td>
                                                        <?php
                                                        foreach ($timeslots as $day => $hours) {
                                                        ?>
                                                            <?php
                                                            if (!empty($hours[$index])) {
                                                                $hourString = MyDate::getHoursMinutes($hours[$index]);
                                                                $hour = str_replace(":", '.', $hourString);
                                                                $hour = (ceil(FatUtility::float($hour)));
                                                                $hour = ($hour == 0) ? 1 : $hour;
                                                                $hourString = str_replace('{hourstring}', $hourString, $hourStringLabel);
                                                            }
                                                            ?>
                                                            <td class="is-hover">
                                                                <?php if (!empty($hours[$index])) { ?>
                                                                    <div class="cal-cell <?php echo $colorClass[$hour]; ?>"></div>
                                                                    <div class="tooltip tooltip--top bg-black"><?php echo $hourString; ?></div>
                                                                <?php } else { ?>
                                                                    <div class="cal-cell"></div>
                                                                <?php } ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <a href="javascript:void(0);" onclick="viewCalendar(<?php echo $teacher['user_id']; ?>, 'paid')" class="link-detail"><?php echo Label::getLabel('LBL_View_Full_availability'); ?></a>
                                    </div>
                                </div>
                                <?php if (!empty($teacher['user_video_link'])) { ?>
                                    <div class="panel-content video" data-src="<?php echo $teacher['user_video_link']; ?>" style="display:none;">
                                        <iframe width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="show-more">
                <?php
                echo FatUtility::createHiddenFormFromData($post, ['name' => 'frmSearchPaging']);
                $pagingArr = ['page' => $post['pageno'], 'pageCount' => $pageCount, 'recordCount' => $recordCount, 'callBackJsFunc' => 'gotoPage'];
                $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
                ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="page-listing__body">
        <div class="box -padding-30" style="margin-bottom: 30px;">
            <div class="message-display">
                <div class="message-display__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 408">
                        <path d="M488.468,408H23.532A23.565,23.565,0,0,1,0,384.455v-16.04a15.537,15.537,0,0,1,15.517-15.524h8.532V31.566A31.592,31.592,0,0,1,55.6,0H456.4a31.592,31.592,0,0,1,31.548,31.565V352.89h8.532A15.539,15.539,0,0,1,512,368.415v16.04A23.565,23.565,0,0,1,488.468,408ZM472.952,31.566A16.571,16.571,0,0,0,456.4,15.008H55.6A16.571,16.571,0,0,0,39.049,31.566V352.891h433.9V31.566ZM497,368.415a0.517,0.517,0,0,0-.517-0.517H287.524c0.012,0.172.026,0.343,0.026,0.517a7.5,7.5,0,0,1-7.5,7.5h-48.1a7.5,7.5,0,0,1-7.5-7.5c0-.175.014-0.346,0.026-0.517H15.517a0.517,0.517,0,0,0-.517.517v16.04a8.543,8.543,0,0,0,8.532,8.537H488.468A8.543,8.543,0,0,0,497,384.455h0v-16.04ZM63.613,32.081H448.387a7.5,7.5,0,0,1,0,15.008H63.613A7.5,7.5,0,0,1,63.613,32.081ZM305.938,216.138l43.334,43.331a16.121,16.121,0,0,1-22.8,22.8l-43.335-43.318a16.186,16.186,0,0,1-4.359-8.086,76.3,76.3,0,1,1,19.079-19.071A16,16,0,0,1,305.938,216.138Zm-30.4-88.16a56.971,56.971,0,1,0,0,80.565A57.044,57.044,0,0,0,275.535,127.978ZM63.613,320.81H448.387a7.5,7.5,0,0,1,0,15.007H63.613A7.5,7.5,0,0,1,63.613,320.81Z"></path>
                    </svg>
                </div>
                <h5><?php echo Label::getLabel('LBL_NO_RESULT_FOUND!'); ?></h5>
            </div>
        </div>
    </div>
<?php } ?>
<?php if(!$langPage) { ?>
    <script>
        autoCompleteGoogle();
        toggleAdressSrch(<?php echo $post['user_offline_sessions'] ?? 0 ?>);
    </script>
<?php } ?>