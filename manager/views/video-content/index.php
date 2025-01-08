<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$srchFrm->setFormTagAttribute('onsubmit', 'searchPages(this); return(false);');
$srchFrm->setFormTagAttribute('class', 'form');
$submitBtn = $srchFrm->getField('btn_submit');
$submitBtn->developerTags['col'] = 6;
?>
<main class="main">
    <div class="container">
        <div class="breadcrumb-wrap">
            <?php $this->includeTemplate('_partial/header/header-breadcrumb.php'); ?>
            <div class="action-toolbar">
                <?php if ($canEdit) { ?>
                    <a href="javascript:void(0);" onclick="addForm(0);" class="btn btn-primary"><?php echo Label::getLabel('LBL_ADD_NEW'); ?></a>
                <?php } ?>
                <a href="javascript:void(0);" onclick="exportCSV();" class="btn btn-primary"><?php echo Label::getLabel('LBL_EXPORT'); ?></a>
            </div>
        </div>
        <div class="card" style="display:none;">
            <div class="card-head js--filter-trigger">
                <h4> <?php echo Label::getLabel('LBL_Search...'); ?></h4>
            </div>
            <div class="card-body js--filter-target">
                <?php echo $srchFrm->getFormHtml(); ?>
            </div>
        </div>
        <div class="card">
            <div class="card-table">
                <div id="pageListing" class="table-responsive">
                    <div class="table-processing loaderJs">
                        <div class="spinner spinner--sm spinner--brand"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>