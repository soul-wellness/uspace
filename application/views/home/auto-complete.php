<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<ul>
    <?php if (count($courses) > 0 || count($languages) > 0 || count($classes) > 0 || count($teachers) > 0) { ?>
        <?php if (count($courses) > 0) { ?>
            <?php foreach ($courses as $course) { ?>
                <li class="is-suggestion-course">
                    <a href="<?php echo MyUtility::makeUrl('Courses', 'view', [$course['slug']]); ?>">
                        <span class="auto-suggest__item">
                            <span class="auto-suggest__media">
                                <svg class="icon icon--course">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#icon-course-filter'; ?>"></use>
                                </svg>
                            </span>
                            <span class="auto-suggest__content">
                                <?php echo str_ireplace($keyword, '<b>' . $keyword . '</b>', $course['name']); ?>
                            </span>
                        </span>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        <?php if (count($languages) > 0) { ?>
            <?php foreach ($languages as $language) { ?>
                <li class="is-suggestion-subject">
                    <a href="<?php echo MyUtility::makeUrl('Teachers', 'languages', [$language['slug']]); ?>">
                        <span class="auto-suggest__item">
                            <span class="auto-suggest__media">
                                <svg class="icon icon--subject">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#icon-subject-filter'; ?>"></use>
                                </svg>
                            </span>
                            <span class="auto-suggest__content">
                                <?php echo str_ireplace($keyword, '<b>' . $keyword . '</b>', ucfirst($language['name'])); ?>
                            </span>
                        </span>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        <?php if (count($classes) > 0) { ?>
            <?php foreach ($classes as $class) { ?>
                <li class="is-suggestion-groupclass">
                    <a href="<?php echo MyUtility::makeUrl('GroupClasses', 'view', [$class['slug']]); ?>">
                        <span class="auto-suggest__item">
                            <span class="auto-suggest__media">
                                <svg class="icon icon--subject">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#icon-class-filter'; ?>"></use>
                                </svg>
                            </span>
                            <span class="auto-suggest__content">
                                <?php echo str_ireplace($keyword, '<b>' . $keyword . '</b>', $class['name']); ?>
                            </span>
                        </span>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        <?php if (count($teachers) > 0) { ?>
            <?php foreach ($teachers as $teacher) { ?>
                <li class="is-suggestion-teacher">
                    <a href="<?php echo MyUtility::makeUrl('Teachers', 'view', [$teacher['slug']]); ?>">
                        <span class="auto-suggest__item">
                            <span class="auto-suggest__media">
                                <svg class="icon icon--subject">
                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#icon-teacher-filter'; ?>"></use>
                                </svg>
                            </span>
                            <span class="auto-suggest__content">
                                <?php echo str_ireplace($keyword, '<b>' . $keyword . '</b>', $teacher['name']); ?>
                            </span>
                        </span>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
        <li class="is-suggestion-course">
            <a>
                <span class="auto-suggest__item">
                    <span class="auto-suggest__media"></span>
                    <span class="auto-suggest__content">
                        <?php echo Label::getLabel('LBL_No_Record_Found'); ?>
                    </span>
                </span>
            </a>
        </li>
    <?php } ?>
</ul>