<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$recaptchaKey = FatApp::getConfig('CONF_RECAPTCHA_SITEKEY', FatUtility::VAR_STRING, '');
if (!empty($recaptchaKey)) {
    $htmlNote = $frm->getField('htmlNote');
    $htmlNote->value = '<div class="field-set"><div class="caption-wraper"><label class="field_label"></label></div><div class="field-wraper"><div class="field_cover">
    <div class="g-recaptcha" data-sitekey="' . $siteKey . '" data-callback="captchaValidate" data-expired-callback="captchaValidate"></div></div></div></div>';
}
$privacyPolicyLink = empty($privacyPolicyLink) ? 'javascript:void();' : $privacyPolicyLink;
$termsConditionsLink = empty($termsConditionsLink) ? 'javascript:void();' : $termsConditionsLink;
$frm->setFormTagAttribute('class', 'form');
$frm->setFormTagAttribute('name', 'affiliateSignupFrm');
$frm->setFormTagAttribute('id', 'affiliateSignupFrm');
$frm->developerTags['colClassPrefix'] = 'col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->setFormTagAttribute('onsubmit', 'signupAffiliateSetup(this); return(false);');
$fldFirstName = $frm->getField('user_first_name');
$fldFirstName->developerTags['col'] = 6;
$fldLastName = $frm->getField('user_last_name');
$fldLastName->developerTags['col'] = 6;
$fldPassword = $frm->getField('user_password');
$fldPassword->changeCaption('');
$fldPassword->captionWrapper = (array(Label::getLabel('LBL_Password') . '<span class="spn_must_field">*</span><a onClick="togglePassword(this)" href="javascript:void(0)" class="-link-underline -float-right link-color" data-show-caption="' . Label::getLabel('LBL_Show_Password') . '" data-hide-caption="' . Label::getLabel('LBL_Hide_Password') . '">' . Label::getLabel('LBL_Show_Password'), '</a>'));
$termLink = ' <a target="_blank" class = "-link-underline link-color" href="' . $termsConditionsLink . '">' . Label::getLabel('LBL_TERMS_AND_CONDITION') . '</a> ' . Label::getLabel('LBL_AND') . ' <a href="' . $privacyPolicyLink . '" target="_blank" class = "-link-underline link-color" >' . Label::getLabel('LBL_Privacy_Policy') . '</a>';
$terms_caption = '<span>' . $termLink . '</span>';
$frm->getField('agree')->addWrapperAttribute('class', 'terms_wrap set-remember');
$frm->getField('agree')->htmlAfterField = $terms_caption;
?>
<style>
    .affiliate-slogan-txt-container{color:#fff;}
    .affiliate-slogan-txt-container p {color:inherit;}
    .affiliate-slogan-txt-container {
    position: relative;
    height: 100%;
    display: flex;
    align-items: center;
    padding: 0;
}
</style>
<section class="section" style="background:url('<?php  echo FatCache::getCachedUrl(MyUtility::makeFullUrl('image', 'show', [Afile::TYPE_AFFILIATE_REGISTRATION_BANNER, 0, Afile::SIZE_LARGE, $siteLangId], CONF_WEBROOT_URL), CONF_DEF_CACHE_TIME, '.jpg'); ?>');background-size:cover;">
    <div class="container container--fixed">
        <div class="row justify-content-between">
            <div class="col-md-6 col-xl-4 order-1 order-md-0">
                <div class="box -skin">
                    <div class="box__head -align-center">
                        <h4 class="-border-title"><?php echo Label::getLabel('LBL_REGISTER_AS_AFFILIATE'); ?></h4>
                    </div>
                    <div class="box__body -padding-40 div-login-form">
                        <?php
                        echo $frm->getFormHtml();
                        ?>
                        <div class="-align-center">
                            <p><?php echo Label::getLabel('LBL_ALREADY_HAVE_AN_ACCOUNT?'); ?> <a href="javascript:void(0);" onClick="signinForm()" class="-link-underline link-color"><?php echo Label::getLabel('LBL_Sign_In'); ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
           <?php if (!empty($contentBlocks &&  isset($contentBlocks[ExtraPage::BLOCK_AFFILIATE_REGISTRATION_BANNER]))) { ?>
            <div class="col-md-6 col-xl-7 order-0 order-md-1">
                <div class="affiliate-slogan-txt-container">                   
                    <div class="editor-content box__body -padding-40">
                        <?php echo FatUtility::decodeHtmlEntities($contentBlocks[ExtraPage::BLOCK_AFFILIATE_REGISTRATION_BANNER]['epage_content']); ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<script>
    $(document).ready(function() {
        $('#termLabelWrapper label').addClass('field_resp_block');
        $('#termLabelWrapper label').append('<?php echo $termLink; ?>');       
    });   
</script>
<?php if (!empty($siteKey) && !empty($secretKey)) { ?>
    <script src='//www.google.com/recaptcha/api.js'></script>
<?php } ?>