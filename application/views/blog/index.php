<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $bpCategoryId = isset($bpCategoryId) ? $bpCategoryId : 0; ?>
<section class="banner banner--main <?php echo (isset($bpCategoryId)) ? '' : 'banner--main'; ?>">
    <div class="banner__media"><img src="<?php echo FatCache::getCachedUrl(MyUtility::makeUrl('Image', 'show', [Afile::TYPE_BLOG_PAGE_IMAGE, 0, Afile::SIZE_LARGE]), CONF_DEF_CACHE_TIME, '.jpg'); ?>" alt="<?php echo Label::getLabel('LBL_BLOG'); ?>"></div>
    <div class="banner__content banner__content--centered">
        <h1><?php echo Label::getLabel('LBL_Blog'); ?></h1>
        <p><?php echo Label::getLabel('LBL_The_place_where_we_write_some_words'); ?></p>
        <div class="form-search form-search--blog">
            <a href="javascript:void(0)" class="blog-toggle blog-toggle-js"><span></span></a>
            <form method="post" onsubmit="searchBlogs(this);return false;">
                <div class="form__element">
                    <input class="form__input" placeholder="<?php echo Label::getLabel('LBL_Blog_Search'); ?>" name="keyword" type="text" />
                    <span class="form__action-wrap">
                        <input class="form__action" value="" type="submit" />
                        <span class="svg-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14.844" height="14.843" viewBox="0 0 14.844 14.843">
                                <path d="M251.286,196.714a4.008,4.008,0,1,1,2.826-1.174A3.849,3.849,0,0,1,251.286,196.714Zm8.241,2.625-3.063-3.062a6.116,6.116,0,0,0,1.107-3.563,6.184,6.184,0,0,0-.5-2.442,6.152,6.152,0,0,0-3.348-3.348,6.271,6.271,0,0,0-4.884,0,6.152,6.152,0,0,0-3.348,3.348,6.259,6.259,0,0,0,0,4.884,6.152,6.152,0,0,0,3.348,3.348,6.274,6.274,0,0,0,6-.611l3.063,3.053a1.058,1.058,0,0,0,.8.34,1.143,1.143,0,0,0,.813-1.947h0Z" transform="translate(-245 -186.438)"></path>
                            </svg>
                        </span>
                    </span>
                </div>
            </form>
        </div>
    </div>
</section>
<section class="section section--nav">
    <div class="container container--fixed">
        <span class="overlay overlay--blog blog-toggle-js"></span>
        <nav class="nav-categories">
            <?php if (!empty($allcats)) { ?>
                <ul>
                    <li class="<?php echo ($actionName == 'index') ? 'is-active' : ''; ?>"><a href="<?php echo MyUtility::makeUrl('Blog'); ?>"><?php echo Label::getLabel('LBL_All_Blogs'); ?></a></li>
                    <?php
                    foreach ($allcats as $category) {
                      
                        $categoryId = FatUtility::int($category['bpcategory_id']);
                        $blogPostCount =  array_sum(array_column($category['children'], 'countChildBlogPosts')) + $category['countChildBlogPosts'];
                        if($blogPostCount>0) {
                        ?>
                        <li class="has-categories-dropdown <?php echo ($bpCategoryId == $categoryId) ? 'is-active' : ''; ?>">
                            <a href="<?php echo MyUtility::makeUrl('Blog', 'category', [$categoryId]); ?>">
                                <?php
                                echo $category['bpcategory_name'];
                                echo ($blogPostCount > 0) ? " (" . $blogPostCount . ")" : '';
                                ?>
                            </a>
                            <span class="categories-touch-trigger cate-trigger-js"></span>
                            <?php if (count($category['children']) > 0) { ?>
                                <div class="has-categories-target cate-target-js">
                                    <nav class="nav nav--toggled">
                                        <ul>
                                            <?php foreach ($category['children'] as $childCat) { 
                                                if($childCat['countChildBlogPosts'] > 0) {
                                                ?>
                                                <li>
                                                    <a href="<?php echo MyUtility::makeUrl('Blog', 'category', [$childCat['bpcategory_id']]); ?>">
                                                        <?php
                                                        echo $childCat['bpcategory_name'];
                                                        echo ($childCat['countChildBlogPosts'] > 0) ? " (" . $childCat['countChildBlogPosts'] . ")" : '';
                                                        ?>
                                                    </a>
                                                </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </nav>
                                </div>
                            </li>
                        <?php }
                        } ?>
                    <?php } ?>
                </ul>
            <?php } ?>
        </nav>
    </div>
</section>
<section class="section--blogs">
    <div class="container container--narrow">
        <div class="row">
            <div class="container">
                <div class="breadcrumb-list padding-bottom-0">
                    <ul>
                        <li><a href="<?php echo MyUtility::makeUrl(); ?>"><?php echo Label::getLabel('LBL_Home'); ?></a></li>
                        <li><a href="<?php echo MyUtility::makeUrl('Blog'); ?>"><?php echo Label::getLabel('LBL_Blog'); ?></a></li>
                        <li><?php echo (empty($categoryName) ? Label::getLabel('LBL_All') : $categoryName); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div id='listing'></div>
    </div>
</section>
<script>
    var bpCategoryId = <?php echo!empty($currCategoryId) ? $currCategoryId : 0; ?>;
</script>
</div>
<script>
    /* FOR BLOG CATEGORIES */
    $('.blog-toggle-js').click(function () {
        $(this).toggleClass("is-active");
        $('html').toggleClass("show-categories-js");
    });


    /* FOR FOOTER TOGGLES */
    $('.cate-trigger-js').click(function () {
        if ($(this).hasClass('is-active')) {
            $(this).removeClass('is-active');
            $(this).siblings('.cate-target-js').slideUp();
            return false;
        }
        $('.cate-trigger-js').removeClass('is-active');
        $(this).addClass("is-active");
        $('.cate-target-js').slideUp();
        $(this).siblings('.cate-target-js').slideDown();
    });

</script>
