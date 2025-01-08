<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php if ($blogPostsList) { ?>
    <section class="section section--random">
        <div class="container container--narrow">
            <div class="section__head d-flex justify-content-between align-items-center">
                <h2><?php echo Label::getLabel('LBL_LATEST_BLOGS'); ?></h2>
                <a class="view-all" href="<?php echo MyUtility::makeUrl('Blog') ?>"><?php echo Label::getLabel('LBL_VIEW_ALL'); ?> </a>
            </div>
            <div class="section__body">
                <div class="blog-wrapper">
                    <div class="slider slider--onehalf slider-onehalf-js">
                        <?php foreach ($blogPostsList as $postDetail) { ?>
                            <div>
                                <div class="slider__item">
                                    <div class="blog-card">
                                        <div class="blog__head">
                                            <div class="blog__media ratio ratio--16by9">
                                                <img src="<?php echo FatCache::getCachedUrl(MyUtility::makeFullUrl('Image', 'show', [Afile::TYPE_BLOG_POST_IMAGE, $postDetail['post_id'], Afile::SIZE_MEDIUM]), CONF_DEF_CACHE_TIME, '.jpg') ?>" alt="<?php echo $postDetail['post_title']; ?>">
                                            </div>
                                        </div>
                                        <div class="blog__body">
                                            <div class="blog__detail">
                                                <div class="tags-inline__item">
                                                    <?php echo ucfirst($postDetail['bpcategory_name'] ?? $postDetail['bpcategory_identifier']); ?>
                                                </div>
                                                <div class="blog__title">
                                                    <h3><?php echo CommonHelper::renderHtml($postDetail['post_title']) ?></h3>
                                                </div>
                                                <div class="blog__date">
                                                    <svg class="icon icon--date">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#date"></use>
                                                    </svg>
                                                    <span>
                                                        <?php
                                                        echo MyDate::showDate($postDetail['post_published_on']);
                                                        ?>
                                                    </span>
                                                </div>
                                                <a href="<?php echo MyUtility::makeUrl('Blog', 'PostDetail', [$postDetail['post_id']]); ?>" class="btn btn--primary">
                                                    <?php echo Label::getLabel('LBL_VIEW_BLOG'); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <a href="<?php echo MyUtility::makeUrl('Blog', 'PostDetail', [$postDetail['post_id']]); ?>" class="blog__action"></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
    </section>
<?php
}
