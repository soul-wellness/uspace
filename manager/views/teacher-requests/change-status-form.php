<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'updateStatus(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$fld = $frm->getField('tereq_status');
$fld->setFieldTagAttribute('onChange', 'showHideCommentBox(this.value)');
$fldBl = $frm->getField('tereq_comments');
$fldBl->setFieldTagAttribute('id', 'comments');
?>
<div class="card">
    <div class="card-head">
        <div class="card-head-label">
            <h3 class="card-head-title"><?php echo Label::getLabel('LBL_UPDATE_STATUS'); ?></h3>
        </div>
    </div>
    <div class="card-body">
        <?php echo $frm->getFormHtml(); ?>
    </div>
</div>
<script>
    $('#comments').parents('.row').addClass('hide');
</script>