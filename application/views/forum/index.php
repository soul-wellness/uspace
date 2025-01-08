<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$srchFrmObj->setFormTagAttribute('id', 'srchQuestionForm');
$srchFrmObj->setFormTagAttribute('onsubmit', 'forumSearch.searchByKeyWord(this); return false;');
$fld = $srchFrmObj->getField('keyword');
$fld->addFieldTagAttribute('placeholder', $fld->getCaption());
$fld->addFieldTagAttribute('id', 'keyword');
$fld->htmlAfterField = "<a id='tag-reset' class='reset-search' href='javascript:void(0);' onclick='forumSearch.resetForumSearch(this);'>x</a>";
$fld = $srchFrmObj->getField('tag_id');
$fld->addFieldTagAttribute('id', 'tag_id');
$fld = $srchFrmObj->getField('pageno');
$fld->addFieldTagAttribute('id', 'pageno');
?>
<!-- [ MAIN BODY ========= -->
<section class="forum-header">
    <div class="container container--narrow" id="maindv__js" data-luser_id="<?php echo $siteUserId; ?>">
        <hgroup>
            <h1 class="bold-700 color-secondary"><?php echo Label::getLabel('LBL_Got_a_question?'); ?> </h1>
            <h4>
                <?php
                $lbl = Label::getLabel('LBL_Ask_{tot-tutots-count}+_expert_tutors_from_all_over_the_world!');
                $repVars = ['{tot-tutots-count}' => $totalTutors];
                echo CommonHelper::replaceStringData($lbl, $repVars);
                ?>
            </h4>
        </hgroup>
        <?php echo $srchFrmObj->getFormTag(); ?>
        <div class="forum-actions margin-top-8">
            <div class="forum-actions__small">
                <a onclick="forum.addNewQuestion('maindv__js');" href="javascript:void(0);" class="btn btn--secondary btn--xlarge btn--block">
                    <svg class="icon icon--qmark margin-right-3">
                    <use xlink:href="<?php echo CONF_WEBROOT_FRONT_URL; ?>images/forum/sprite.svg#q-mark"></use>
                    </svg>
                    <span><?php echo Label::getLabel('LBL_Ask_a_Question'); ?></span>
                </a>
            </div>
            <div class="forum-actions__large">
                <div class="forum-search">
                    <form>
                        <div class="forum-search__field">
                            <?php echo $srchFrmObj->getFieldHTML('keyword'); ?>
                        </div>
                        <div class="forum-search__action forum-search__action--submit">
                            <?php echo $srchFrmObj->getFieldHTML('btn_submit'); ?>
                            <span class="btn btn--equal btn--transparent color-black">
                                <svg class="icon icon--search icon--small">
                                <use xlink:href="<?php echo CONF_WEBROOT_FRONT_URL; ?>images/forum/sprite.svg#icon-search"></use>
                                </svg>
                            </span>
                        </div>
                        <?php
                        echo $srchFrmObj->getFieldHTML('tag_id');
                        echo $srchFrmObj->getFieldHTML('search_type');
                        echo $srchFrmObj->getFieldHTML('pageno');
                        echo $srchFrmObj->getExternalJS();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <?php if (0 < count($popularTags)) { ?>
            <div class="tags tags--overflow margin-top-4 d-sm-block d-none">
                <span class="d-block d-sm-inline-flex margin-bottom-3"><?php echo Label::getLabel('LBL_Popular_Tags'); ?>:</span>
                <div class="tags__overflow">
                    <?php foreach ($popularTags as $key => $name) { ?>
                        <a href="<?php echo MyUtility::makeUrl('Forum') . '?tag=' . $name . '-' . $key; ?>" class="tags__item badge badge--curve color-secondary"><?php echo $name; ?></a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <nav class="tabs tabs--line border-bottom-0 tabs-scrollable-js margin-top-16">
            <ul id="srch_type_tabs">
                <?php
                foreach ($srchTypes as $typeId => $typeName) {
                    echo '<li class="srch_type ' . ($srchWithType == $typeId ? 'is-active' : '') . '"><a class="' . (ForumQuestionSearch::TYPE_ALL == $typeId ? 'default_srch_type' : '') . ' search-type" data-search_type="' . $typeId . '" onclick="forumSearch.setSearchByType(this); return false;" href="javascript:void(0);">' . $typeName . '</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>
</section>
<section class="forum-body">
    <div class="container container--narrow">
        <div class="forum-stat margin-bottom-10">
            <div class="forum-stat__content">
                <?php if (1 > $siteUserId) { ?>
                    <h3 class="margin-bottom-4"><?php echo Label::getLabel('LBL_Forum_questions_listing_page_guest_user_main_heading'); ?></h3>
                    <p class="margin-bottom-10"><?php echo Label::getLabel('LBL_Forum_Questions_Listing_Page_guest_user_sub_heading'); ?></p>
                    <?php $this->includeTemplate('guest-user/_partial/learner-social-media-signup.php', [], false); ?>

                <?php } else { ?>
                    <h3 class="margin-bottom-4 bold-700"><?php echo Label::getLabel('LBL_Join_the_biggest_community_of_learners_for_free'); ?></h3>
                    <p class="margin-bottom-10">
                        <?php echo Label::getLabel('LBL_Sign_up_to_ask_our_experts_any_questions_and_get_helpful_tips_in_your_inbox'); ?>
                    </p>
                    <a href="<?php echo MyUtility::makeUrl('Teachers'); ?>" class="btn btn--primary"><span><?php echo Label::getLabel('LBL_Find_Community_Experts'); ?></span></a>
                    <a onclick="forum.addNewQuestion('maindv__js');" href="javascript:void(0);" class="btn btn--primary-bordered"><span><?php echo Label::getLabel('LBL_Ask_a_Question'); ?></span></a>
                <?php } ?>
            </div>
            <div class="forum-stat__count">
                <div class="forum-counts">
                    <span class="forum-counts__item">
                        <h5><?php echo $totalQuestions; ?></h5>
                        <p><?php echo Label::getLabel('LBL_questions_asked'); ?></p>
                    </span>
                    <span class="forum-counts__item">
                        <h5><?php echo $totalComments; ?></h5>
                        <p><?php echo Label::getLabel('LBL_tutors_answers'); ?></p>
                    </span>
                    <span class="forum-counts__item">
                        <h5><?php echo $totalTutors; ?></h5>
                        <p><?php echo Label::getLabel('LBL_active_tutors'); ?></p>
                    </span>
                </div>
            </div>
            <div class="forum-stat__media">
                <img src="<?php echo CONF_WEBROOT_URL; ?>images/forum/cta-graphic.svg" alt="CTA Image">
            </div>
        </div>
        <section class="flex-panel">
            <div class="flex-panel__large" id="listing"></div>
            <?php
            $vars = [
                'topRatedTeachers' => $topRatedTeachers,
                'popularTags' => $popularTags,
                'recommendedPosts' => $recommendedPosts,
            ];
            echo $this->includeTemplate('forum/right-side-bar.php', $vars, false);
            ?>
        </section>
    </div>
</section>
<!-- ] -->
<!-- [ SIDE BAR SECONDARY ========= -->
<script>
    forumSearch.baseUrl = '<?php echo MyUtility::makeUrl(); ?>';
</script>