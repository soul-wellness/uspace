<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<section class="section padding-bottom-0">
    <div class="container container--fixed">
        <div class="intro-head">
            <h6 class="small-title"><?php echo strtoupper(Label::getLabel('LBL_FAQ')); ?></h6>
            <h2><?php echo Label::getLabel('LBL_faq_title_second') ?></h2>
        </div>
    </div>
</section>
<div class="panel-nav">
    <ul>
        <?php
        $firstCatId = array_key_first($typeArr);
        foreach ($typeArr as $catId => $catName) {
        ?>
        <li class="panel-nav__item <?php echo ($firstCatId == $catId) ? 'is--active' : '' ?>"><a
                href="javascript:void(0)" class="faq-panel-js"
                data-cat-id="<?php echo 'section_' . $catId ?>"><?php echo $catName; ?></a></li>
        <?php } ?>
    </ul>
</div>
<section class="section section--faq">
    <div class="container container--narrow">
        <div class="faq-cover">
            <?php if (!empty($faqs)) { ?>
            <div class="search-panel">
                <svg class="icon icon--search">
                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#search'; ?>"></use>
                </svg>
                <input type="text" name="faq_search"
                    placeholder="<?php echo Label::getLabel('LBL_FAQ_SEARCH_PLACEHOLDER_TXT'); ?>">
            </div>
            <?php } else { ?>
            <h2 class="text--center"><?php echo Label::getLabel('LBL_NO_FAQ_YET'); ?></h2>
            <?php } ?>
            <?php foreach ($faqs as $catId => $faqDetails) { ?>
            <div id="<?php echo 'section_' . $catId ?>"
                <?php echo ($firstCatId != $catId) ? 'style="display:none;"' : ''; ?> class="faq-container">
                <?php foreach ($faqDetails as $ques) {
                    if (empty($ques['faq_title']) || empty($ques['faq_description'])) {
                        continue;
                    }
                    ?>
                    <div class="faq-row faq-group-js">
                        <a href="javascript:void(0)" class="faq-title faq__trigger faq__trigger-js">
                            <h5><?php echo $ques['faq_title']; ?></h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js editor-content">
                            <?php echo nl2br($ques['faq_description']) ?>
                        </div>
                       
                    </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php $this->includeTemplate('_partial/contact-us-section.php', ['siteLangId' => $siteLangId]); ?>
<script>
$(".faq__trigger-js").click(function(e) {
    e.preventDefault();
    var $target = $(this).siblings('.faq__target-js');
    if ($target.is(':visible')) {
        $target.slideUp();
    } else {
        $('.faq__target-js:visible').slideUp();
        $target.slideDown();
    }
});


$(".faq-panel-js").click(function() {
    $(".faq-panel-js").parent().removeClass('is--active');
    $(".faq-container").hide();
    $(this).parent().addClass('is--active');
    $('#' + $(this).attr('data-cat-id')).show();
});
$(document).ready(function() {
    $('input[name="faq_search"]').keyup(function() {
        filter = $(this).val().toUpperCase();
        filter = $.trim(filter);
        if (filter == '') {
            $('.faq-row').show();
            return;
        }
        $('.faq-row').hide();
        $('.faq-row .faq-title h5').each(function() {
            txtValue = $(this).text();
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                $(this).parents('.faq-row').show();
            }
        });
    });
});
</script>