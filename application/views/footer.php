<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$newsletter = false;
$apikey = FatApp::getConfig("CONF_MAILCHIMP_KEY");
$listId = FatApp::getConfig("CONF_MAILCHIMP_LIST_ID");
$prefix = FatApp::getConfig("CONF_MAILCHIMP_SERVER_PREFIX");
if (!empty($apikey) && !empty($listId) && !empty($prefix) && FatApp::getConfig('CONF_ENABLE_NEWSLETTER_SUBSCRIPTION')) {
    $newsletter = true;
}
if ($newsletter) {
    $form = MyUtility::getNewsLetterForm();
    $form->developerTags['colClassPrefix'] = 'col-sm-';
    $form->developerTags['fld_default_col'] = 12;
    $form->setFormTagAttribute('onsubmit', 'submitNewsletterForm(this); return false;');
    $form->setFormTagAttribute('class', 'form');
    $emailFld = $form->getField('email');
    $emailFld->developerTags['noCaptionTag'] = true;
    $emailFld->addFieldTagAttribute('placeholder', Label::getLabel('LBL_ENTER_YOUR_EMAIL'));
    $emailFld->addFieldTagAttribute('class', 'input-field');
    $submitBtn = $form->getField('btnSubmit');
    $submitBtn->developerTags['noCaptionTag'] = true;
    $submitBtn->addFieldTagAttribute('class', 'input-submit');
}
$sitePhone = FatApp::getConfig('CONF_SITE_PHONE');
$siteEmail = FatApp::getConfig('CONF_CONTACT_EMAIL');
$address = FatApp::getConfig('CONF_ADDRESS_' . $siteLangId, FatUtility::VAR_STRING, '');
?>

</div>

<footer class="footer">
    <div class="footer-wrapper">
        <?php if ($newsletter) { ?>
            <div class="footer-upper">
                <div class="container container--narrow">
                    <div class="site-subscribe">
                        <p><?php echo Label::getLabel('LBL_NEWSLETTER_DESCRITPTION'); ?></p>
                        <div class="site-subscribe__form">
                            <?php echo $form->getFormTag(); ?>
                            <span class="icon icon--small">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 14.4">
                                    <path d="M2.8,3H17.2a.8.8,0,0,1,.8.8V16.6a.8.8,0,0,1-.8.8H2.8a.8.8,0,0,1-.8-.8V3.8A.8.8,0,0,1,2.8,3ZM16.4,6.39l-6.342,5.68L3.6,6.373V15.8H16.4ZM4.009,4.6l6.04,5.33L16,4.6Z" transform="translate(-2 -3)" />
                                </svg>
                            </span>
                            <?php echo $emailFld->getHtml(); ?>
                            <?php echo $submitBtn->getHtml(); ?>
                            </form>
                            <?php echo $form->getExternalJs(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="footer-middle">
            <div class="container container--narrow">
                <div class="row justify-content-between">
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-colum toggle-group">
                            <div class="footer-colum__trigger toggle-trigger-js">
                                <h5><?php echo Label::getLabel('LBL_GET_IN_TOUCH'); ?></h5>
                            </div>
                            <div class="footer-colum__target toggle-target-js">
                                <div class="footer-list">
                                    <ul>
                                        <?php if (!empty($address)) { ?>
                                            <li>
                                                <span class="footer-list__group">
                                                    <svg class="icon icon--pin icon--small margin-right-3 margin-top-2">
                                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#pin"></use>
                                                    </svg>
                                                    <span class="footer-list__title">
                                                        <?php echo FatApp::getConfig('CONF_ADDRESS_' . $siteLangId, FatUtility::VAR_STRING, ''); ?>
                                                    </span>
                                                </span>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <span class="footer-list__group">
                                                <svg class="icon icon--pin icon--small margin-right-3 margin-top-2">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#email"></use>
                                                </svg>
                                                <span class="footer-list__title">
                                                    <a href="mailto:<?php echo $siteEmail; ?>"> <?php echo $siteEmail; ?></a>
                                                </span>
                                            </span>
                                        </li>
                                        <?php if (!empty($sitePhone)) { ?>
                                            <li>
                                                <span class="footer-list__group">
                                                    <svg class="icon icon--pin icon--small margin-right-3 margin-top-2">
                                                        <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/sprite.svg#phone"></use>
                                                    </svg>
                                                    <span class="footer-list__title">
                                                        <a dir="ltr" href="tel:<?php echo $sitePhone; ?>"> <?php echo $sitePhone; ?></a>
                                                    </span>
                                                </span>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($socialPlatforms)) { ?>
                        <div class="col-md-6 col-lg-2">
                            <div class="footer-colum toggle-group">
                                <div class="footer-colum__trigger toggle-trigger-js">
                                    <h5><?php echo Label::getLabel('LBL_FOLLOW_US'); ?></h5>
                                </div>
                                <div class="footer-colum__target toggle-target-js">
                                    <div class="footer-list">
                                        <ul>
                                            <?php foreach ($socialPlatforms as $name => $link) { ?>
                                                <?php $name = strtolower($name) ?>
                                                <li>
                                                    <a href="<?php echo $link; ?>" target="_blank">
                                                        <svg class="icon icon--<?php echo $name ?>">
                                                            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#' . ($name == 'x' ? 'twitter' : $name); ?>"></use>
                                                        </svg>
                                                        <span><?php echo ucfirst($name); ?></span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($footerOneNav)) { ?>
                        <div class="col-md-6 col-lg-2">
                            <div class="footer-colum toggle-group">
                                <div class="footer-colum__trigger toggle-trigger-js">
                                    <h5><?php echo current($footerOneNav)['parent']; ?></h5>
                                </div>
                                <div class="footer-colum__target toggle-target-js">
                                    <div class="footer-list">
                                        <ul>
                                            <?php foreach ($footerOneNav as $nav) { ?>
                                                <?php if ($nav['pages']) { ?>
                                                    <?php foreach ($nav['pages'] as $link) { ?>
                                                        <?php
                                                        $display = true;
                                                        if (($siteUserId < 1 && $link['nlink_login_protected'] == NavigationLinks::NAVLINK_LOGIN_YES) ||
                                                                ($siteUserId > 0 && $link['nlink_login_protected'] == NavigationLinks::NAVLINK_LOGIN_NO)) {
                                                            $display = false;
                                                        }
                                                        if ($display == true) {
                                                            $navUrl = CommonHelper::getnavigationUrl($link['nlink_type'], $link['nlink_url'], $link['nlink_cpage_id']);
                                                        ?>
                                                            <li>
                                                                <a target="<?php echo $link['nlink_target']; ?>" href="<?php echo $navUrl; ?>">
                                                                    <?php echo $link['nlink_caption']; ?>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($footerTwoNav)) { ?>
                        <div class="col-md-6 col-lg-2">
                            <div class="footer-colum toggle-group">
                                <div class="footer-colum__trigger toggle-trigger-js">
                                    <h5><?php echo current($footerTwoNav)['parent']; ?></h5>
                                </div>
                                <div class="footer-colum__target toggle-target-js">
                                    <div class="footer-list">
                                        <ul>
                                            <?php foreach ($footerTwoNav as $nav) { ?>
                                                <?php if ($nav['pages']) { ?>
                                                    <?php foreach ($nav['pages'] as $link) { ?>
                                                        <?php
                                                        $display = true;
                                                        if (($siteUserId < 1 && $link['nlink_login_protected'] == NavigationLinks::NAVLINK_LOGIN_YES) ||
                                                                ($siteUserId > 0 && $link['nlink_login_protected'] == NavigationLinks::NAVLINK_LOGIN_NO)) {
                                                            $display = false;
                                                        }
                                                        if ($display == true) {
                                                            $navUrl = CommonHelper::getnavigationUrl($link['nlink_type'], $link['nlink_url'], $link['nlink_cpage_id']);
                                                            ?>
                                                            <li>
                                                                <a target="<?php echo $link['nlink_target']; ?>" href="<?php echo $navUrl; ?>" class="bullet-list__action"><?php echo $link['nlink_caption']; ?></a>
                                                            </li>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
    <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php } ?>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-colum toggle-group">
                            <div class="footer-colum__trigger toggle-trigger-js">
                                <h5><?php echo Label::getLabel('LBL_LANGUAGE_&_CURRENCY'); ?></h5>
                            </div>
                            <div class="footer-colum__target toggle-target-js">
                                <div class="settings-group margin-top-6">
                                    <div class="settings toggle-group">
                                        <a class="btn btn--bordered btn--block btn--dropdown settings__trigger settings__trigger-js"><?php echo $siteLanguage['language_name']; ?></a>
                                        <?php if (count($siteLanguages) > 0) { ?>
                                            <div class="settings__target settings__target-js" style="display: none;">
                                                <ul>
                                                    <?php foreach ($siteLanguages as $language) { ?>
                                                        <li <?php echo ($siteLangId == $language['language_id']) ? 'class="is--active"' : ''; ?>>
                                                            <a <?php echo ($siteLangId != $language['language_id']) ? 'onclick="setSiteLanguage(' . $language['language_id'] . ')"' : ''; ?> href="javascript:void(0)"><?php echo $language['language_name'] ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="settings toggle-group">
                                        <a class="btn btn--bordered btn--block btn--dropdown settings__trigger settings__trigger-js"><?php echo $siteCurrency['currency_code']; ?></a>
                                        <?php if (count($siteCurrencies) > 0) { ?>
                                            <div class="settings__target settings__target-js" style="display: none;">
                                                <ul>
                                                    <?php foreach ($siteCurrencies as $currency) { ?>
                                                        <li <?php echo ($siteCurrency['currency_id'] == $currency['currency_id']) ? 'class="is--active"' : ''; ?>>
                                                            <a <?php echo ($siteCurrency['currency_id'] != $currency['currency_id']) ? 'onclick="setSiteCurrency(' . $currency['currency_id'] . ')"' : ''; ?> href="javascript:void(0);"><?php echo $currency['currency_code']; ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="settings toggle-group">
                                        <img src="<?php echo CONF_WEBROOT_FRONTEND . 'images/payment.jpg'; ?>" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if (MyUtility::isDemoUrl() || true == WHITE_LABELED) { ?>
            <?php
            $url = (false == WHITE_LABELED) ? '<a target="_blank"  href="http://yo-coach.com">' . FatApp::getConfig('CONF_WEBSITE_NAME_1', FatUtility::VAR_STRING, 'Yo!Coach') . '</a>' : FatApp::getConfig('CONF_WEBSITE_NAME_1', FatUtility::VAR_STRING, 'Yo!Coach');
            $replacements = array(
                '{year}' => '&copy; ' . date("Y"),
                '{product}' => '<span class="bold-600">' . $url . '</span>',
                '{owner}' => '<a target="_blank" rel="nofollow" class="bold-600" href="https://www.fatbit.com">FATbit Technologies</a>',
            );
            ?>
            <div class="footer-lower">
                <div class="container container--narrow">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-5">
                            <p>
                                <?php echo $str = CommonHelper::replaceStringData(Label::getLabel('LBL_COPYRIGHT_TEXT', $siteLangId), $replacements); ?>
                            </p>
                        </div>
                        <div class="col-md-auto">
                            <div class="footer__logo">
                                <?php
                                if (false == WHITE_LABELED) {
                                    echo $str = CommonHelper::replaceStringData(Label::getLabel('LBL_DEVELOPED_BY_TEXT', $siteLangId), $replacements);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?php if (strtolower($controllerName) == 'home' && strtolower($actionName) == 'index') { ?>
                <div class="footer-lower">
                    <div class="container container--narrow">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-5">
                                <p>
                                    Copyright &copy; <?php echo date("Y"); ?>
                                    <span class="bold-600">
                                        <?php echo FatApp::getConfig('CONF_WEBSITE_NAME_1', FatUtility::VAR_STRING, ''); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-auto">
                                <div class="footer__logo">
                                    Technology Partner: <a target="_blank" rel="nofollow" class="bold-600" href="https://www.fatbit.com">FATbit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</footer>

<a href="#top" class="gototop" title="Back to Top"></a>
    <?php if (FatApp::getConfig('CONF_ENABLE_COOKIES', FatUtility::VAR_INT, 1) && empty($cookieConsent)) { ?>
    <div class="cc-window cc-banner cc-type-info cc-theme-block cc-bottom cookie-alert no-print">
    <?php if (FatApp::getConfig('CONF_COOKIES_TEXT_' . $siteLangId, FatUtility::VAR_STRING, '')) { ?>
            <div class="box-cookies">
                <span id="cookieconsent:desc" class="cc-message">
                    <?php echo FatUtility::decodeHtmlEntities(FatApp::getConfig('CONF_COOKIES_TEXT_' . $siteLangId, FatUtility::VAR_STRING, '')); ?>
                    <?php
                    $readMorePage = FatApp::getConfig('CONF_COOKIES_BUTTON_LINK', FatUtility::VAR_INT, 0);
                    if ($readMorePage) {
                        ?>
                        <a href="<?php echo MyUtility::makeUrl('cms', 'view', [$readMorePage]); ?>"><?php echo Label::getLabel('LBL_READ_MORE'); ?></a></span>
        <?php } ?>
                </span>
                <a href="javascript:void(0)" class="cc-close" onClick="acceptAllCookies();"><?php echo Label::getLabel('LBL_ACCEPT_COOKIES'); ?></a>
                <a href="javascript:void(0)" class="cc-close" onClick="cookieConsentForm();"><?php echo Label::getLabel('LBL_CHOOSE_COOKIES'); ?></a>
            </div>
    <?php } ?>
    </div>
<?php } ?>
<?php
if (!empty(FatApp::getConfig('CONF_ENABLE_LIVECHAT', FatUtility::VAR_STRING, ''))) {
    echo FatApp::getConfig('CONF_LIVE_CHAT_CODE', FatUtility::VAR_STRING, '');
}
if (!empty(FatApp::getConfig('CONF_SITE_TRACKER_CODE', FatUtility::VAR_STRING, '')) && !empty($cookieConsent[CookieConsent::STATISTICS])) {
    echo FatApp::getConfig('CONF_SITE_TRACKER_CODE', FatUtility::VAR_STRING, '');
}
?>
</body>

</html>