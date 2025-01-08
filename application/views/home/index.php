<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if (isset($slides) && count($slides)) { ?>
    <section class="section-slideshow">
        <div class="slideshow slideshow-js">
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
                $html = '<div><div class="caraousel__item">';
                if (!empty($slideImage[Afile::TYPE_HOME_BANNER_IPAD])) {
                    $imgUrl = MyUtility::makeUrl('Image', 'show', [Afile::TYPE_HOME_BANNER_IPAD, $slide['slide_id'], Afile::SIZE_LARGE, $siteLangId]);
                    $tabletUrl = FatCache::getCachedUrl($imgUrl, CONF_IMG_CACHE_TIME, '.jpg');
                }
                if ($haveUrl) {
                    $html .= '<a target="' . $slide['slide_target'] . '" href="' . CommonHelper::processUrlString($slide['slide_url']) . '">';
                }
                $html .= '<div>
                            <div class="slideshow__item">
                               <picture class="hero-img">
                                  <source data-aspect-ratio="4:3" srcset="' . $mobileUrl . '" media="(max-width: 767px)">
                                  <source data-aspect-ratio="4:3" srcset="' . $tabletUrl . '" media="(max-width: 1024px)">
                                  <source data-aspect-ratio="10:3" srcset="' . $desktopUrl . '">
                                  <img data-aspect-ratio="10:3" srcset="' . $desktopUrl . '" alt="' . $slide['slide_identifier'] . '">
                               </picture>
                           </div>
                        </div>';
                if ($haveUrl) {
                    $html .= '</a>';
                }
                $html .= "</div></div>";
                echo $html;
            }
            ?>
        </div>
        <div class="slideshow-content">
            <h1><?php echo Label::getLabel('LBL_SLIDER_TITLE_TEXT'); ?></h1>
            <p><?php echo Label::getLabel('LBL_SLIDER_DESCRIPTION_TEXT'); ?></p>
            <div class="slideshow__form">
                <form method="POST" class="form" action="<?php echo MyUtility::makeFullUrl('Teachers', 'languages'); ?>" name="homeSearchForm" id="homeSearchForm">
                    <div class="slideshow-input">
                        <svg class="icon icon--search">
                        <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#search'; ?>"></use>
                        </svg>
                        <input type="text" name="language" placeholder="<?php echo Label::getLabel('LBL_I_AM_LEARNING'); ?>" />
                        <input type="hidden" name="teachLangId" />
                        <input type="hidden" name="teachLangSlug" />
                    </div>
                    <button class="btn btn--secondary btn--large btn--block"><?php echo Label::getLabel('LBL_SEARCH_FOR_TEACHERS'); ?></button>
                </form>
            </div>
            <?php
            if (!empty($popularLanguages)) {
                $lastkey = array_key_last($popularLanguages);
                ?>
                <div class="tags-inline">
                    <b><?php echo Label::getLabel("LBL_POPULAR:") ?></b>
                    <ul>
                        <?php
                        foreach ($popularLanguages as $language) {
                            $language['tlang_name'] = ($lastkey != $language['tlang_id']) ? $language['tlang_name'] . ', ' : $language['tlang_name'];
                            ?>
                            <li class="tags-inline__item"><a href="<?php echo MyUtility::makeUrl('teachers', 'languages', [$language['tlang_slug']]) ?>"><?php echo $language['tlang_name']; ?></a></li>
                            <?php
                        }
                        unset($lastkey);
                        ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php
}

if (!empty($contentBlocks)) {
    ?>
    <?php
    foreach ($contentBlocks as $sn => $row) {
        switch ($row['epage_block_type']) {
            case ExtraPage::BLOCK_FEATURED_LANGUAGES:
                ?>
                <?php if (!empty($featuredLanguages)) { ?>
                    <section class="section section--language">
                        <div class="container container--narrow">
                            <div class="section__head">
                                <h2><?php echo Label::getLabel('LBL_WHAT_LANGUAGE_YOU_WANT_TO_LEARN?'); ?></h2>
                            </div>
                            <div class="section__body">
                                <div class="subject-row">
                                    <?php foreach ($featuredLanguages as $language) { ?>
                                        <div class="subject-colum">
                                            <div class="subject">
                                                <div class="subject__media">
                                                    <img src="<?php echo FatCache::getCachedUrl(MyUtility::makeUrl('Image', 'show', [Afile::TYPE_TEACHING_LANGUAGES, $language['tlang_id'], Afile::SIZE_SMALL]), CONF_IMG_CACHE_TIME, '.jpg'); ?>" alt="<?php echo $language['tlang_name']; ?>">
                                                </div>
                                                <div class="subject__name">
                                                    <span class="subject-title"><?php echo $language['tlang_name'] ?></span>
                                                </div>
                                                <a class="subject__action" href="<?php echo MyUtility::makeUrl('Teachers', 'languages', [$language['tlang_slug']]); ?>"></a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="more-info align-center">
                                    <p><?php echo Label::getLabel("LBL_DIFFERENT_LANGUAGE_NOTE"); ?> <a href="<?php echo MyUtility::makeUrl('teachers'); ?>"><?php echo Label::getLabel('LBL_BROWSE_THEM_NOW'); ?></a></p>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php
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
            case ExtraPage::BLOCK_HOW_TO_START_LEARNING:
                echo FatUtility::decodeHtmlEntities($row['epage_content']);
                break;
            case ExtraPage::BLOCK_LATEST_BLOGS:
                echo $this->includeTemplate('home/_partial/latestBlogs.php', ['blogPostsList' => $blogPostsList], false);
                break;
            default:
                break;
        }
    }
}
?>
<script>
    LANGUAGES = <?php echo json_encode($teachLangs); ?>;
</script>