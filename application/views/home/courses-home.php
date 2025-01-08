<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="hero">
    <?php if (isset($slides) && count($slides)) { ?>
        <div class="hero-slider slideshow-js">
            <?php
            foreach ($slides as $slide) {
                $desktopUrl = '';
                $tabletUrl = '';
                $mobileUrl = '';
                $haveUrl = ($slide['slide_url'] != '');
                if (empty($slideImages[$slide['slide_id']])) {
                    continue;
                }
                $slideImage = $slideImages[$slide['slide_id']];
                if (!empty($slideImage[Afile::TYPE_HOME_BANNER_DESKTOP])) {
                    $imgUrl = MyUtility::makeUrl('Image', 'show', [Afile::TYPE_HOME_BANNER_DESKTOP, $slide['slide_id'], Afile::SIZE_LARGE, $siteLangId]);
                    $desktopUrl = FatCache::getCachedUrl($imgUrl, CONF_IMG_CACHE_TIME, '.jpg');
                }
                if (!empty($slideImage[Afile::TYPE_HOME_BANNER_MOBILE])) {
                    $imgUrl = MyUtility::makeUrl('Image', 'show', [Afile::TYPE_HOME_BANNER_MOBILE, $slide['slide_id'], Afile::SIZE_LARGE, $siteLangId]);
                    $mobileUrl = FatCache::getCachedUrl($imgUrl, CONF_IMG_CACHE_TIME, '.jpg');
                }
                $html = '<div>';
                if (!empty($slideImage[Afile::TYPE_HOME_BANNER_IPAD])) {
                    $imgUrl = MyUtility::makeUrl('Image', 'show', [Afile::TYPE_HOME_BANNER_IPAD, $slide['slide_id'], Afile::SIZE_LARGE, $siteLangId]);
                    $tabletUrl = FatCache::getCachedUrl($imgUrl, CONF_IMG_CACHE_TIME, '.jpg');
                }
                if ($haveUrl) {
                    $html .= '<a target="' . $slide['slide_target'] . '" href="' . CommonHelper::processUrlString($slide['slide_url']) . '">';
                }
                $html .= '
                            <div class="hero-slider__item">
                               <picture class="hero-img">
                                  <source data-aspect-ratio="4:3" srcset="' . $mobileUrl . '" media="(max-width: 767px)">
                                  <source data-aspect-ratio="4:3" srcset="' . $tabletUrl . '" media="(max-width: 1199px)">
                                  <source data-aspect-ratio="10:3" srcset="' . $desktopUrl . '">
                                  <img data-aspect-ratio="10:3" srcset="' . $desktopUrl . '" alt="' . $slide['slide_identifier'] . '">
                               </picture>
                           </div>
                        ';
                if ($haveUrl) {
                    $html .= '</a>';
                }
                $html .= "</div>";
                echo $html;
            }
            ?>
        </div>
    <?php } ?>
    <div class="hero-content">
        <hgroup>
            <h1><?php echo Label::getLabel('LBL_SLIDER_TITLE_TEXT'); ?></h1>
            <p><?php echo Label::getLabel('LBL_SLIDER_DESCRIPTION_TEXT'); ?></p>
        </hgroup>
        <?php
        $lbl = Label::getLabel('LBL_SEARCH_BY_COURSE,_LANGUAGE,_TEACHERS_AND_CLASSES');
        if (!GroupClass::isEnabled()) {
            $lbl = Label::getLabel('LBL_SEARCH_BY_COURSE,_LANGUAGE,_TEACHERS');
        }
        $keywordFld = $frm->getField('keyword');
        $keywordFld->setFieldTagAttribute('placeholder', $lbl);
        $keywordFld->setFieldTagAttribute('id', 'homeSearchFld');
        $keywordFld->setFieldTagAttribute('autocomplete', 'off');
        ?>
        <div class="site-search">
            <div class="site-search__field">
                <span class="site-search__media">
                    <svg class="icon icon--search">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#search'; ?>"></use>
                    </svg>
                </span>
                <?php echo $keywordFld->getHtml(); ?>
                <div class="site-search__target search-target-js" style="display:none;">
                    <div class="auto-suggest autoSuggestJs"></div>
                </div>
            </div>
            <div class="site-search__dropdown">
                <div class="search-dropdown">
                    <?php $filters = AppConstant::getFilterTypes(); ?>
                    <a href="javascript:void(0)" class="search-dropdown__trigger expand-trigger-js selectedFilterJs">
                        <?php echo $filters[AppConstant::FILTER_ALL] ?>
                    </a>
                    <div class="search-dropdown__target expand-target-js" style="display:none;">
                        <div class="selection-listing">
                            <ul class="filterTypeJs">
                                <?php foreach ($filters as $key => $value) { ?>
                                    <li>
                                        <a href="javascript:void(0)" class="<?php echo ($key == 0) ? 'is-active' : '' ?>" data-filter="<?php echo $key; ?>">
                                            <?php echo $value ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <?php echo $frm->getFieldHtml('type'); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($contentBlocks)) { ?>
    <?php $courseAndLang = false; ?>
    <?php
    foreach ($contentBlocks as $sn => $row) {
        switch ($row['epage_block_type']) {
            case ExtraPage::BLOCK_FEATURED_LANGUAGES:
            case ExtraPage::BLOCK_TOP_COURSE_CATEGORIES:
    ?>
                <?php if ($courseAndLang == false && (!empty($categories) || !empty($featuredLanguages))) { ?>
                    <section class="section">
                        <div class="container container--narrow">
                            <div class="section__body">
                                <nav class="inline-tabs inline-tabs--large js-inline-tabs">
                                    <ul>
                                        <?php
                                        $active = '';
                                        foreach ($contentBlocks as $block) {
                                            switch ($block['epage_block_type']) {
                                                case ExtraPage::BLOCK_FEATURED_LANGUAGES:
                                        ?>
                                                    <?php if (!empty($featuredLanguages)) { ?>
                                                        <li>
                                                            <a href="#inline-content-2" class="<?php echo empty($active) ? 'is-active' : '' ?>">
                                                                <span class="icon icon--courses margin-right-2">
                                                                    <svg class="icon icon--course" width="24" height="24">
                                                                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#icon-subject'; ?>"></use>
                                                                    </svg>
                                                                </span>
                                                                <?php echo Label::getLabel('LBL_POPULAR_LANGUAGES'); ?>
                                                            </a>
                                                        </li>
                                                        <?php
                                                        if (empty($active)) {
                                                            $active = 'lang';
                                                        }
                                                        ?>
                                                    <?php } ?>
                                                    <?php break; ?>
                                                <?php
                                                case ExtraPage::BLOCK_TOP_COURSE_CATEGORIES: ?>
                                                    <?php if (!empty($categories)) { ?>
                                                        <li>
                                                            <a href="#inline-content-1" class="<?php echo empty($active) ? 'is-active' : '' ?>">
                                                                <span class="icon icon--courses margin-right-2">
                                                                    <svg class="icon icon--course">
                                                                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#icon-subject-filter'; ?>"></use>
                                                                    </svg>
                                                                </span>
                                                                <?php echo Label::getLabel('LBL_TOP_COURSES_CATEGORIES'); ?>
                                                            </a>
                                                        </li>
                                                        <?php
                                                        if (empty($active)) {
                                                            $active = 'course';
                                                        }
                                                        ?>
                                                    <?php } ?>
                                                    <?php break; ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </nav>
                                <div class="inline-content-container margin-top-10">
                                    <?php foreach ($contentBlocks as $block) { ?>
                                        <?php
                                        switch ($block['epage_block_type']) {
                                            case ExtraPage::BLOCK_TOP_COURSE_CATEGORIES:
                                        ?>
                                                <?php if (!empty($categories)) { ?>
                                                    <div id="inline-content-1" class="inline-content <?php echo ($active == 'course') ? 'visible' : '' ?>">
                                                        <div class="colum-grid">
                                                            <?php echo $this->includeTemplate('home/_partial/topCourseCategories.php', ['categories' => $categories], false); ?>
                                                        </div>
                                                        <div class="align-center inline-cta">
                                                            <a href="<?php echo MyUtility::generateUrl('Courses'); ?>" class="btn btn--primary btn--wide">
                                                                <?php echo Label::getLabel('LBL_EXPLORE_ALL_COURSES') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php break; ?>
                                            <?php
                                            case ExtraPage::BLOCK_FEATURED_LANGUAGES: ?>
                                                <?php if (!empty($featuredLanguages)) { ?>
                                                    <div id="inline-content-2" class="inline-content <?php echo ($active == 'lang') ? 'visible' : '' ?>">
                                                        <div class="flag-wrapper">
                                                            <?php echo $this->includeTemplate('home/_partial/popularLanguages.php', ['popularLanguages' => $featuredLanguages], false); ?>
                                                        </div>
                                                        <div class="align-center inline-cta">
                                                            <a href="<?php echo MyUtility::makeUrl('teachers'); ?>" class="btn btn--primary btn--wide">
                                                                <?php echo Label::getLabel('LBL_EXPLORE_ALL_LANGUAGES') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php break; ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </section>
<?php
                    $courseAndLang = true;
                }
                break;
            case ExtraPage::BLOCK_COURSES:
                echo $this->includeTemplate('home/_partial/popularCourseSection.php', ['courses' => $courses, 'siteLangId' => $siteLangId, 'siteUserId' => $siteUserId], false);
                break;
            case ExtraPage::BLOCK_TOP_RATED_TEACHERS:
                echo $this->includeTemplate('home/_partial/topTeachers.php', ['topRatedTeachers' => $topRatedTeachers, 'siteLangId' => $siteLangId, 'isCourseAvailable' => $isCourseAvailable], false);
                break;
            case ExtraPage::BLOCK_BROWSE_TUTOR:
                echo FatUtility::decodeHtmlEntities($row['epage_content']);
                break;
            case ExtraPage::BLOCK_CLASSES:
                echo $this->includeTemplate('home/_partial/upcomingClasses.php', ['classes' => $classes, 'bookingBefore' => $bookingBefore, 'siteUserId' => $siteUserId], false);
                break;
            case ExtraPage::BLOCK_WHY_US:
                echo '<section class="section section--services">';
                echo FatUtility::decodeHtmlEntities($row['epage_content']);
                echo '</section>';
                break;
            case ExtraPage::BLOCK_TESTIMONIALS:
                echo $this->includeTemplate('home/_partial/testimonialList.php', ['testmonialList' => $testmonialList], false);
                break;
            case ExtraPage::BLOCK_SERVICES_WE_OFFERING:
                echo '<section class="section ">';
                echo FatUtility::decodeHtmlEntities($row['epage_content']);
                echo '</section>';
                break;
            case ExtraPage::BLOCK_LATEST_BLOGS:
                echo $this->includeTemplate('home/_partial/latestBlogs.php', ['blogPostsList' => $blogPostsList], false);
                break;
            default:
                break;
        }
    }
}
