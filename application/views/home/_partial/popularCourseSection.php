<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if (count($courses) > 0 && count($courses['courses']) > 0) { ?>
    <section class="section section--random">
        <div class="container container--fixed">
            <div class="section__head">
                <h2><?php echo Label::getLabel('LBL_HOME_POPULAR_COURSES_TITLE'); ?></h2>
            </div>
            <div class="section__body">
                <nav class="inline-tabs js-inline-tabs">
                    <ul>
                        <?php $i = 1; ?>
                        <?php foreach ($courses['categories'] as $id => $category) { ?>
                            <?php if (isset($courses['courses'][$id]) && count($courses['courses'][$id]) > 0) { ?>
                                <?php $category = CommonHelper::renderHtml(ucfirst($category)); ?>
                                <li>
                                    <a href="#inline-content-c<?php echo $id ?>" class="<?php echo ($i == 1) ? 'is-active' : ''; ?>">
                                        <?php echo (strlen($category) > 50) ? mb_substr($category, 0, 50, 'utf-8') . '...' : $category; ?>
                                    </a>
                                </li>
                                <?php $i++; ?>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </nav>
                <div class="inline-content-container margin-top-10">
                    <?php $i = 1; ?>
                    <?php foreach ($courses['courses'] as $cateId => $cateCourses) { ?>
                        <?php if (count($cateCourses) > 0) { ?>
                            <div id="inline-content-c<?php echo $cateId ?>" class="inline-content <?php echo ($i == 1) ? 'visible' : ''; ?>">
                                <div class="slider slider-oneforth slider-oneforth-js">
                                    <?php foreach ($cateCourses as $course) { ?>
                                        <div class="slider__item">
                                            <div class="card-cover">
                                                <div class="short-card">
                                                    <div class="short-card__head">
                                                        <div class="short-card__media ratio ratio--16by9">
                                                            <a href="<?php echo MyUtility::makeUrl('Courses', 'view', [$course['course_slug']]); ?>">
                                                                <img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_COURSE_IMAGE, $course['course_id'], 'MEDIUM', $siteLangId], CONF_WEBROOT_FRONT_URL) . '?=' . time(); ?>" alt="<?php echo $course['course_title']; ?>">
                                                            </a>
                                                        </div>
                                                        <div class="short-card__elements">
                                                            <div>
                                                                <a href="javascript:void(0)" class="mark-option <?php echo ($course['is_favorite'] == AppConstant::YES) ? 'is-active' : ''; ?>" onclick="toggleCourseFavorite('<?php echo $course['course_id'] ?>', this)" data-status="<?php echo $course['is_favorite']; ?>">
                                                                    <svg class="icon icon--heart icon--small">
                                                                    <use xlink:href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/sprite.svg#icon-heart"></use>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            <?php if ($course['course_certificate'] == AppConstant::YES) { ?>
                                                                <div>
                                                                    <span class="short-tag">
                                                                        <svg class="icon icon--award margin-right-1">
                                                                        <use xlink:href="<?php echo CONF_WEBROOT_FRONTEND; ?>images/sprite.svg#icon-course-certificate">
                                                                        </use>
                                                                        </svg>
                                                                        <span><?php echo Label::getLabel('LBL_CERTIFICATE'); ?></span>
                                                                    </span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="short-card__body">

                                                        <div class="card-element">
                                                            <small class="card-element__item">
                                                                <?php echo CommonHelper::convertDuration($course['course_duration']); ?>
                                                            </small>
                                                            <small class="card-element__item">
                                                                <?php echo $course['course_lectures'] . ' ' . Label::getLabel('LBL_LECTURES'); ?>
                                                            </small>
                                                            <small class="card-element__item">
                                                                <?php echo $course['course_students'] . ' ' . Label::getLabel('LBL_STUDENTS'); ?>
                                                            </small>
                                                        </div>

                                                        <h6 class="short-card__title  margin-bottom-4 margin-top-1">
                                                            <a href="<?php echo MyUtility::makeUrl('Courses', 'view', [$course['course_slug']]); ?>" class="snakeline-hover">
                                                                <?php echo (strlen($course['course_title']) > 70) ? CommonHelper::renderHtml(mb_substr($course['course_title'], 0, 70, 'utf-8')) . '...' : CommonHelper::renderHtml($course['course_title']); ?>
                                                            </a>
                                                        </h6>
                                                        <?php
                                                        $url = 'javascript:void(0);';
                                                        if ($course['is_profile_complete'] == true) {
                                                            $url = MyUtility::makeUrl('Teachers', 'view', [$course['teacher_username']]);
                                                        }
                                                        ?>
                                                        <a href="<?php echo $url; ?>" class="profile-meta d-flex align-items-center">
                                                            <div class="profile-meta__media margin-right-4">
                                                                <span class="avtar avtar--xsmall avtar--round" data-title="<?php echo CommonHelper::getFirstChar($course['teacher_first_name'], true); ?>">
                                                                    <img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_USER_PROFILE_IMAGE, $course['teacher_id'], Afile::SIZE_SMALL]); ?>" alt="<?php echo ucwords($course['teacher_first_name'] . ' ' . $course['teacher_last_name']); ?>">
                                                                </span>
                                                            </div>
                                                            <div class="profile-meta__details">
                                                                <p class="color-black margin-bottom-1 style-ellipsis">
                                                                    <?php echo ucwords($course['teacher_first_name'] . ' ' . $course['teacher_last_name']); ?>
                                                                </p>
                                                            </div>
                                                        </a>

                                                    </div>

                                                    <div class="short-card__footer">
                                                        <div class="d-flex align-items-center">
                                                            <div class="padding-right-4">
                                                                <?php if ($course['course_type'] != Course::TYPE_FREE) { ?>
                                                                    <h4 class="color-primary bold-700">
                                                                        <?php echo CourseUtility::formatMoney($course['course_price']); ?>
                                                                    </h4>
                                                                <?php } else { ?>
                                                                    <h4 class="free-text color-red bold-700">
                                                                        <?php echo Label::getLabel('LBL_FREE'); ?>
                                                                    </h4>
                                                                <?php } ?>
                                                            </div>
                                                            <div>
                                                                <div class="rating">
                                                                    <svg class="rating__media">
                                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#rating"></use>
                                                                    </svg>
                                                                    <span class="rating__value">
                                                                        <?php echo $course['course_ratings']; ?>
                                                                    </span>
                                                                    <span class="rating__count">
                                                                        <?php echo '(' . $course['course_reviews'] . ' ' . Label::getLabel('LBL_REVIEWS') . ')'; ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="align-center inline-cta">
                                    <a href="<?php echo MyUtility::makeUrl('Courses', 'index') . '?catg=' . $cateId; ?>" class="btn btn--primary btn--wide">
                                        <?php
                                        $lbl = Label::getLabel('LBL_EXPLORE_{category}');
                                        echo str_replace('{category}', CommonHelper::renderHtml(ucfirst($courses['categories'][$cateId] ?? '')), $lbl);
                                        ?>
                                    </a>
                                </div>
                            </div>
                            <?php $i++; ?>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
<?php
}
