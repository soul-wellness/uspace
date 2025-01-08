<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$form->setFormTagAttribute('action', MyUtility::makeUrl('Account', 'setupProfileImage'));
$form->setFormTagAttribute('onsubmit', 'setupVideoLink(this, false); return(false);');
$form->setFormTagAttribute('id', 'frmProfile');
$form->setFormTagAttribute('class', 'form form--horizontal');
$imgFld = $form->getField('user_profile_image');
$imgFld->addFieldTagAttribute('onchange', 'popupImage(this);');
$imgFld->addFieldTagAttribute('accept', 'image/*');
$extensionLabel = Label::getLabel('LBL_ALLOWED_FILE_EXTS_{ext}');
$dimensionsLabel = Label::getLabel('LBL_PREFERRED_DIMENSIONS_{dimensions}');
if ($form->getField('user_video_link')) {
    $videoFld = $form->getField('user_video_link');
    $videoFld->addFieldTagAttribute('onblur', 'validateVideolink(this);');
    $videoFld->addFieldTagAttribute('placeholder', Label::getLabel('LBL_VIDEO_LINK_PLACEHOLDER'));
}
$infoText = Label::getLabel('LBL_PROFILE_PICTURE_INFO_TEXT_{size}_{ext}');
$nextButton = $form->getField('btn_next');
$nextButton->addFieldTagAttribute('onClick', 'setupVideoLink(this.form, true); return(false);');
$imageExt = implode(", ", $imageExt);
$fileSize = MyUtility::convertBitesToMb($fileSize) . ' MB';
?>
<script>
    /**
     * used label in profile-info-js
     */
    lblRL = '<?php echo Label::getLabel('LBL_ROTATE_LEFT'); ?>';
    lblRR = '<?php echo Label::getLabel('LBL_ROTATE_RIGHT'); ?>';
    lblUpdatePic = '<?php echo Label::getLabel('LBL_UPDATE_PROFILE_PICTURE'); ?>';
    lblCroperInfoText = '<?php echo Label::getLabel('LBL_USE_MOUSE_SCROLL_TO_ADJUST_IMAGE'); ?>';
</script>
<div class="padding-6">
    <div class="max-width-80">
        <?php echo $form->getFormTag(); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="field-set">
                    <div class="caption-wraper">
                        <label class="field_label"><?php echo $imgFld->getCaption(); ?>
                            <?php if ($imgFld->requirement->isRequired()) { ?>
                                <span class="spn_must_field">*</span>
                            <?php } ?>
                        </label>
                        <small class="margin-0"><?php echo str_replace(['{size}', '{ext}'], [$fileSize, $imageExt], $infoText); ?></small>
                    </div>
                    <div class="field-wraper">
                        <div class="field_cover">
                            <div class="profile-media">
                                <div class="avtar avtar--xlarge" data-title="<?php echo ''; ?>">
                                    <?php echo '<img src="' . MyUtility::makeUrl('Image', 'show', [Afile::TYPE_USER_PROFILE_IMAGE, $siteUserId, Afile::SIZE_MEDIUM], CONF_WEBROOT_FRONT_URL) . '?' . time() . '" />'; ?>
                                </div>
                                <div class="buttons-group margin-top-4">
                                    <span class="btn btn--bordered color-primary btn--small btn--fileupload btn--wide margin-right-2">
                                        <?php
                                        echo $imgFld->getHTML();
                                        echo ($userImage) ? Label::getLabel('LBL_EDIT') : Label::getLabel('LBL_ADD');
                                        ?>
                                    </span>
                                    <?php if ($userImage) { ?>
                                        <a class="btn btn--bordered color-red btn--small btn--wide" href="javascript:void(0);" onClick="removeProfileImage();"><?php echo Label::getLabel('LBL_REMOVE'); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($videoFld)) { ?>
            <div class="row margin-top-5 margin-bottom-5">
                <div class="col-md-12">
                    <div class="field-set">
                        <div class="caption-wraper">
                            <label class="field_label"><?php echo $videoFld->getCaption(); ?>
                                <?php if ($videoFld->requirement->isRequired()) { ?>
                                    <span class="spn_must_field">*</span>
                                <?php } ?>
                            </label>
                            <small class="margin-0"><?php echo Label::getLabel('LBL_PROFILE_VIDEO_FIELD_INFO'); ?></small>
                        </div>
                        <div class="field-wraper">
                            <div class="field_cover">
                                <?php echo $videoFld->getHTML(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row submit-row">
                <div class="col-sm-12">
                    <div class="field-set">
                        <div class="field-wraper">
                            <div class="field_cover">
                                <?php
                                echo $form->getFieldHtml('btn_submit');
                                if ($siteUserType == User::TEACHER) {
                                    echo $nextButton->getHTML('btn_next');
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </form>
        <?php echo $form->getExternalJS(); ?>
    </div>
</div>