<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
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
                <h4> <?php echo Label::getLabel('LBL_Search...'); ?></h4>
            </div>
            <div class="card-body js--filter-target" style="display:none;">
                <?php
                $frmSearch->setFormTagAttribute('onsubmit', 'searchEtpls(this); return(false);');
                $frmSearch->setFormTagAttribute('id', 'frmEtplsSearch');
                $frmSearch->setFormTagAttribute('class', 'form');
                $frmSearch->developerTags['colClassPrefix'] = 'col-md-';
                $frmSearch->developerTags['fld_default_col'] = 6;
                $btn = $frmSearch->getField('btn_clear');
                $btn->setFieldTagAttribute('onClick', 'clearSearch()');
                echo $frmSearch->getFormHtml();
                ?>
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