<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('class', 'form form_horizontal');
$frm->setFormTagAttribute('onsubmit', 'setup(this); return(false);');
$frm->developerTags['colClassPrefix'] = 'col-md-';
$frm->developerTags['fld_default_col'] = 12;
?>

<div class="card-head">
    <div class="card-head-label">
        <h3 class="card-head-title"><?php echo Label::getLabel('LBL_navigation_Setup'); ?></h3>
    </div>
</div>
<div class="form-edit-head">
    <nav class="tab tab-inline">
        <ul class="tabs-nav">
            <li><a class="active" href="javascript:void(0)" onclick="addForm(<?php echo $nav_id ?>);"><?php echo Label::getLabel('LBL_General'); ?></a></li>
            <?php
            $inactive = ($nav_id == 0) ? 'is-inactive' : '';
            foreach ($languages as $langId => $langName) {
            ?>
                <li class="<?php echo $inactive; ?>"><a href="javascript:void(0);" data-id="<?php echo $langId; ?>" <?php if ($nav_id > 0) { ?> onclick="langForm(<?php echo $nav_id ?>, <?php echo $langId; ?>);" <?php } ?>>
                        <?php echo $langName; ?></a></li>
            <?php } ?>
        </ul>
    </nav>
</div>
<div class="form-edit-body">
    <?php echo $frm->getFormHtml(); ?>
</div>