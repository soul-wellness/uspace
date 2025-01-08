<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$srchFrm->setFormTagAttribute('onsubmit', 'search(this); return(false);');
$srchFrm->setFormTagAttribute('class', 'form');
$srchFrm->developerTags['colClassPrefix'] = 'col-md-';
$srchFrm->developerTags['fld_default_col'] = 3;
($srchFrm->getField('btn_clear'))->addFieldTagAttribute('onclick', 'clearSearch()');
?>
<?php
$disabledHelpTxt = '';
if ($isCourseRemoved == false) {
    $disabledHelpTxt = "<div class='page-alert'>" .
        "<div class='alert alert--info'>
            <span>" . Label::getLabel('LBL_INFO_FOR_DISABLED_COURSES_STATS') .
        "</span>" .
        "</div>" .
        "</div>";
} ?>
<?php if ($isGroupClassRemoved == false) {
    $disabledHelpTxt = "<div class='page-alert'>" .
        "<div class='alert alert--info'>
            <span>" . Label::getLabel('LBL_INFO_FOR_DISABLED_CLASSES_STATS') .
        "</span>" .
        "</div>" .
        "</div>";
} ?>
<?php if ($isCourseRemoved == false && $isGroupClassRemoved  == false) {
    $disabledHelpTxt = "<div class='page-alert'>" .
        "<div class='alert alert--info'>
            <span>" . Label::getLabel('LBL_INFO_FOR_DISABLED_COURSE_CLASSES_STATS') .
        "</span>" .
        "</div>" .
        "</div>";
} ?>
<?php echo  $disabledHelpTxt; ?>
<main class="main">
    <div class="container">
        <div class="breadcrumb-wrap">
            <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
            <div class="action-toolbar">
                <a href="javascript:void(0);" onclick="exportCSV();" class="btn btn-primary"><?php echo Label::getLabel('LBL_EXPORT'); ?></a>
            </div>
        </div>
        <div class="card">
            <div class="card-head js--filter-trigger">
                <h4><?php echo Label::getLabel('LBL_Search...'); ?></h4>
            </div>
            <div class="card-body js--filter-target" style="display:none;">
                <?php echo $srchFrm->getFormHtml(); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-table">
                <div id="listing" class="table-responsive">
                    <div class="table-processing loaderJs">
                        <div class="spinner spinner--sm spinner--brand"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>