<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if ($topRatedTeachers) { ?>
    <section class="section">
        <div class="container container--narrow">
            <div class="section__head">
                <h2><?php echo Label::getLabel('LBL_HOME_TOP_RATED_TEACHERS_TITLE'); ?></h2>
            </div>
            <div class="section__body">
                <div class="teacher-wrapper">
                    <div class="row">
                        <?php foreach ($topRatedTeachers as $teacher) { ?>
                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                <div class="tile">
                                    <div class="tile__head">
                                        <div class="tile__media ratio ratio--1by1">
                                            <a href="<?php echo MyUtility::makeUrl('Teachers', 'view', [$teacher['user_username']]); ?>">
                                                <img src="<?php echo FatCache::getCachedUrl(MyUtility::makeUrl('Image', 'show', [Afile::TYPE_USER_PROFILE_IMAGE, $teacher['user_id'], Afile::SIZE_MEDIUM]), CONF_IMG_CACHE_TIME, '.jpg') ?>" alt="<?php echo $teacher['full_name']; ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="tile__body">
                                        <a class="tile__title" href="<?php echo MyUtility::makeUrl('Teachers', 'view', [$teacher['user_username']]); ?>">
                                            <h4><?php echo $teacher['full_name']; ?></h4>
                                        </a>
                                        <div class="card-element justify-content-center margin-bottom-5 margin-top-2">
                                            <span class="card-element__item">
                                                <?php echo $teacher['testat_students']; ?> <?php echo Label::getLabel('LBL_STUDENTS'); ?>
                                            </span>
                                            <span class="card-element__item">
                                                <?php echo $teacher['testat_lessons'] + $teacher['testat_classes']; ?> <?php echo Label::getLabel('LBL_SESSIONS'); ?>
                                            </span>
                                            <?php if ($isCourseAvailable) { ?>
                                                <span class="card-element__item">
                                                    <?php echo $teacher['courses']; ?> <?php echo Label::getLabel('LBL_COURSES'); ?>
                                                </span>
                                            <?php } ?>
                                        </div>
                                        <div class="info-wrapper">
                                            <div class="info-tag location">
                                                <svg class="icon icon--location">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#location"></use>
                                                </svg>
                                                <span class="lacation__name">
                                                    <?php echo $teacher['country_name']['name'] ?? ''; ?>
                                                </span>
                                            </div>
                                            <div class="info-tag ratings">
                                                <svg class="icon icon--rating">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#rating"></use>
                                                </svg>
                                                <span class="value"><?php echo $teacher['testat_ratings']; ?></span>
                                                <span class="count">(<?php echo $teacher['testat_reviewes']; ?>)</span>
                                            </div>
                                        </div>
                                        <div class="card__row--action ">
                                            <a href="<?php echo MyUtility::makeUrl('Teachers', 'view', [$teacher['user_username']]); ?>" class="btn btn--primary btn--block">
                                                <?php echo Label::getLabel('LBL_VIEW_PROFILE'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="align-center margin-top-5">
                    <a href="<?php echo MyUtility::makeUrl('Teachers'); ?>" class="btn btn--primary btn--wide">
                        <?php echo Label::getLabel('LBL_EXPLORE_ALL_TUTORS'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php }
