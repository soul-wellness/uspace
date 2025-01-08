<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form form--horizontal web_form');
$frm->setFormTagAttribute('onsubmit', 'feedbackSetup(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
$fld = $frm->getField('submit');
$fld->setWrapperAttribute('class', 'form-buttons-group');
?>

<div class="modal-header">
    <h5><?php echo Label::getLabel('LBL_CLASS_FEEDBACK'); ?></h5>
    <button type="button" class="btn-close yocoachmodalJs" data-bs-dismiss="modal" aria-label=""></button> 
</div>
<div class="modal-body">
    <?php echo $frm->getFormHtml(); ?>
</div>
<script>
    $(document).ready(function () {
        $('.star-rating').barrating({
            showSelectedRating: false,
            deselectable: false
        });
    });
</script>