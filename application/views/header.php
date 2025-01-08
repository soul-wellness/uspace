<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php $headerClasses = strtolower($controllerName) . ' ' . strtolower($controllerName) . '-' . strtolower($actionName); ?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" class="<?php echo MyUtility::isDemoUrl() ? 'sticky-demo-header' : ''; ?>" data-kit="F!YC">

<head>
    <meta charset="utf-8">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1.0,user-scalable=0" />
    <?php echo $this->writeMetaTags(); ?>
    <link rel="shortcut icon" href="<?php echo MyUtility::getFavicon(); ?>" />
    <link rel="apple-touch-icon" href="<?php echo MyUtility::getFavicon(); ?>" />
    <?php if (!empty($canonicalUrl)) { ?>
        <link rel="canonical" href="<?php echo $canonicalUrl; ?>" />
    <?php } ?>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <?php
    $siteLangCode = $siteLanguages[MyUtility::getSiteLangId()]['lower_language_code'];
    foreach ($siteLanguages as $lang) {
        $requestUrl = !empty($_REQUEST['url']) ? str_replace($siteLangCode, '', $_REQUEST['url'] ?? '') : '';
        if ($lang['language_id'] == MyUtility::getSiteLangId()) {
            continue;
        }
        $hrefLangUrl = MyUtility::makeFullUrl();
        if (Language::getDefaultLang() != $lang['language_id']) {
            $hrefLangUrl .=  $lang['lower_language_code'] . '/';
        }
        $hrefLangUrl .=  trim($requestUrl, '/');
    ?>
        <link rel="alternate" hreflang="<?php echo $lang['lower_language_code'] ?>" href="<?php echo $hrefLangUrl ?>">
    <?php } ?>
    <script type="text/javascript">
        const confWebRootUrl = '<?php echo CONF_WEBROOT_URL; ?>';
        const confFrontEndUrl = '<?php echo CONF_WEBROOT_URL; ?>';
        const confWebDashUrl = '<?php echo CONF_WEBROOT_DASHBOARD; ?>';
        const FTRAIL_TYPE = '<?php echo Lesson::TYPE_FTRAIL; ?>';
        var langLbl = <?php echo json_encode(CommonHelper::htmlEntitiesDecode($jsVariables)); ?>;
        var timeZoneOffset = '<?php echo MyDate::getOffset($siteTimezone); ?>';
        var layoutDirection = '<?php echo $siteLanguage['language_direction']; ?>';
        var SslUsed = '<?php echo FatApp::getConfig('CONF_USE_SSL'); ?>';
        var cookieConsent = <?php echo json_encode($cookieConsent); ?>;
        var ALERT_CLOSE_TIME = <?php echo FatApp::getConfig("CONF_AUTO_CLOSE_ALERT_TIME"); ?>;
        var monthNames = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::getAllMonthName(false, $siteLangId))); ?>;
        var weekDayNames = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::dayNames(false, $siteLangId))); ?>;
        var meridiems = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::meridiems(false, $siteLangId))); ?>;
        var tFmtJs = '<?php echo MyDate::getFormatTime(true); ?>';
        var tFmtSecJs = '<?php echo MyDate::getFormatTime(true, false); ?>';
    </script>
    <?php if (!empty($includeEditor)) { ?>
        <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/innovaeditor.js"></script>
        <script src="<?php echo CONF_WEBROOT_URL; ?>innovas/scripts/common/webfont.js"></script>
    <?php } ?>
    <?php if (FatApp::getConfig('CONF_ENABLE_PWA')) { ?>
        <link rel="manifest" href="<?php echo MyUtility::makeUrl('Pwa'); ?>">
        <script>
            if ("serviceWorker" in navigator) {
                navigator.serviceWorker.register("<?php echo CONF_WEBROOT_FRONTEND; ?>sw.js");
            }
        </script>
    <?php } ?>
    <?php
    echo $this->getJsCssIncludeHtml(!CONF_DEVELOPMENT_MODE);
    echo Common::setThemeColorStyle();
    ?>
    <script>
        $(document).ready(function() {
            <?php if ($siteUserId > 0) { ?>
                setTimeout(getBadgeCount(), 1000);
            <?php } ?>
            <?php if (!empty($messageData['msgs'][0] ?? '')) { ?>
                fcom.success('<?php echo $messageData['msgs'][0]; ?>');
            <?php } ?>
            <?php if (!empty($messageData['dialog'][0] ?? '')) { ?>
                fcom.warning('<?php echo $messageData['dialog'][0]; ?>');
            <?php } ?>
            <?php if (!empty($messageData['errs'][0] ?? '')) { ?>
                fcom.error('<?php echo $messageData['errs'][0]; ?>');
            <?php } ?>
        });
    </script>
    <?php
    $GA4 = 0;
    echo "<!-- Google tag Manager Head Script -->\r\n";
    if (FatApp::getConfig("CONF_GOOGLE_TAG_MANAGER_HEAD_SCRIPT", null, '')) {
        echo FatApp::getConfig("CONF_GOOGLE_TAG_MANAGER_HEAD_SCRIPT", null, '');
        $GA4 = 1;
    }
    ?>
    <!-- Yo!Coach -->
    <!-- F!YC -->
</head>
<?php $isPreviewOn = MyUtility::isDemoUrl() ? 'is-preview-on' : '';
?>


<body class="<?php echo $headerClasses . ' ' . $isPreviewOn; ?>" dir="<?php echo $siteLanguage['language_direction']; ?>">
    <!-- Custom Loader -->
    <div id="app-alert" class="alert-position alert-position--top-right fadeInDown animated"></div>
    <?php
    echo "<!-- Google tag Manager Body Script -->\r\n";
    if (FatApp::getConfig("CONF_GOOGLE_TAG_MANAGER_BODY_SCRIPT", null, '')) {
        echo FatApp::getConfig("CONF_GOOGLE_TAG_MANAGER_BODY_SCRIPT", null, '');
        $GA4 = 1;
    }
    if (MyUtility::isDemoUrl()) {
        include(CONF_INSTALLATION_PATH . 'public/demo-header.php');
    }
    if (isset($_SESSION['preview_theme'])) {
        $this->includeTemplate('_partial/preview.php', array(), false);
    }
    $websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, FatUtility::VAR_STRING, '');
    if (!isset($exculdeMainHeaderDiv)) {
    ?>
        <script>
            GA4 = <?php echo $GA4; ?>;
        </script>
        <header class="header">
            <div class="header-primary">
                <div class="container">
                    <div class="header-flex d-flex justify-content-between align-items-center">
                        <div class="header__left">
                            <?php if (!empty($headerNav)) { ?>
                                <a href="javascript:void(0)" class="toggle toggle--nav toggle--nav-js">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 515.555 515.555">
                                        <path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0" />
                                        <path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0" />
                                        <path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0" />
                                    </svg>
                                </a>
                            <?php } else { ?>
                                <a class="toggle toggle--nav"></a>
                            <?php } ?>
                            <div class="header__logo">
                                <a href="<?php echo MyUtility::makeUrl(); ?>">
                                    <?php echo MyUtility::getLogo(); ?>
                                </a>
                            </div>
                        </div>
                        <div class="header__middle">
                            <?php if (!empty($headerNav)) { ?>
                                <span class="overlay overlay--nav toggle--nav-js is-active"></span>
                                <nav class="menu nav--primary-offset">
                                    <ul>
                                        <?php foreach ($headerNav as $nav) { ?>
                                            <?php
                                            if ($nav['pages']) {
                                                foreach ($nav['pages'] as $link) {
                                                    $display = true;
                                                    if (($siteUserId < 1 && $link['nlink_login_protected'] == NavigationLinks::NAVLINK_LOGIN_YES) ||
                                                        ($siteUserId > 0 && $link['nlink_login_protected'] == NavigationLinks::NAVLINK_LOGIN_NO)
                                                    ) {
                                                        $display = false;
                                                    }
                                                    if ($display == true) {
                                                        $navUrl = CommonHelper::getnavigationUrl($link['nlink_type'], $link['nlink_url'], $link['nlink_cpage_id']);
                                            ?>
                                                        <li class="menu__item">
                                                            <a target="<?php echo $link['nlink_target']; ?>" href="<?php echo $navUrl; ?>">
                                                                <?php echo CommonHelper::renderHtml($link['nlink_caption']); ?>
                                                            </a>
                                                        </li> <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                                ?>
                                    </ul>
                                </nav>
                            <?php } ?>
                        </div>
                        <div class="header__right">
                            <div class="header-controls">
                                <div class="header-controls__item">
                                    <a href="<?php echo MyUtility::makeUrl('', '', [], CONF_WEBROOT_FRONTEND); ?>" class="header-controls__action mobile-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                            <path d="M13 19h6V9.978l-7-5.444-7 5.444V19h6v-6h2v6zm8 1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.49a1 1 0 0 1 .386-.79l8-6.222a1 1 0 0 1 1.228 0l8 6.222a1 1 0 0 1 .386.79V20z" />
                                        </svg>
                                        <span class="mobile-action-label"><?php echo Label::getLabel('LBL_HOME') ?></span>
                                    </a>
                                </div>
                                <div class="header-controls__item header-dropdown header-dropdown--arrow">
                                    <?php if (count($siteLanguages) > 0 || count($siteCurrencies) > 0) { ?>
                                        <a class="header-controls__action header-dropdown__trigger trigger-js mobile-action" href="#languages-nav">
                                            <svg class="icon icon--globe">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#globe-icon'; ?>"></use>
                                            </svg>
                                            <span class="lang mobile-action-label"><?php echo $siteLanguage['language_code'] . ' - ' . $siteCurrency['currency_code']; ?></span>
                                            <svg class="icon icon--arrow">
                                                <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#arrow-black' ?>"></use>
                                            </svg>
                                        </a>
                                        <div id="languages-nav" class="header-dropdown__target">
                                            <div class="dropdown__cover">
                                                <div class="settings-group">
                                                    <?php if (count($siteLanguages) > 0) { ?>
                                                        <div class="settings toggle-group">
                                                            <div class="dropdaown__title"><?php echo Label::getLabel('LBL_SITE_LANGUAGE') ?></div>
                                                            <a class="btn btn--bordered color-black btn--block btn--dropdown settings__trigger settings__trigger-js"><?php echo $siteLanguage['language_name']; ?></a>
                                                            <div class="settings__target settings__target-js" style="display: none;">
                                                                <ul>
                                                                    <?php foreach ($siteLanguages as $language) { ?>
                                                                        <li <?php echo ($siteLangId == $language['language_id']) ? 'class="is--active"' : ''; ?>>
                                                                            <a <?php echo ($siteLangId != $language['language_id']) ? 'onclick="setSiteLanguage(' . $language['language_id'] . ')"' : ''; ?> href="javascript:void(0)"><?php echo $language['language_name']; ?></a>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (count($siteCurrencies) > 0) { ?>
                                                        <div class="settings toggle-group">
                                                            <div class="dropdaown__title"><?php echo Label::getLabel('LBL_SITE_CURRENCY'); ?></div>
                                                            <a class="btn btn--bordered color-black btn--block btn--dropdown settings__trigger settings__trigger-js"><?php echo $siteCurrency['currency_name']; ?></a>
                                                            <div class="settings__target settings__target-js" style="display: none;">
                                                                <ul>
                                                                    <?php foreach ($siteCurrencies as $currency) { ?>
                                                                        <li <?php echo ($siteCurrency['currency_id'] == $currency['currency_id']) ? 'class="is--active"' : ''; ?>>
                                                                            <a <?php echo ($siteCurrency['currency_id'] != $currency['currency_id']) ? 'onclick="setSiteCurrency(' . $currency['currency_id'] . ')"' : ''; ?> href="javascript:void(0);"><?php echo $currency['currency_code']; ?></a>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if ($siteUserId > 0) { ?>
                                    <div class="header-controls__item header--notification d-md-block">
                                        <a href="<?php echo MyUtility::makeUrl('Notifications', '', [], CONF_WEBROOT_DASHBOARD); ?>" class="header-controls__action mobile-action" title="<?php echo Label::getLabel('LBL_NOTIFICATIONS'); ?>">
                                            <span class="notification-count-js"></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                                                <path d="M20 17h2v2H2v-2h2v-7a8 8 0 1 1 16 0v7zm-2 0v-7a6 6 0 1 0-12 0v7h12zm-9 4h6v2H9v-2z" />
                                            </svg>
                                            <span class="mobile-action-label d-md-none d-block"><?php echo Label::getLabel('LBL_NOTIFICATIONS'); ?></span>
                                        </a>
                                    </div>
                                    <div class="header-controls__item header--message d-md-block">
                                        <a href="<?php echo MyUtility::makeUrl('Chats', '', [], CONF_WEBROOT_DASHBOARD); ?>" class="header-controls__action mobile-action" title="<?php echo Label::getLabel('LBL_MESSAGES'); ?>">
                                            <span class="message-count-js"></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                                                <path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 4.238l-7.928 7.1L4 7.216V19h16V7.238zM4.511 5l7.55 6.662L19.502 5H4.511z" />
                                            </svg>
                                            <span class="mobile-action-label d-md-none d-block"><?php echo Label::getLabel('LBL_MESSAGES'); ?></span>
                                        </a>
                                    </div>
                                    <div class="header-dropdown header-dropwown--profile">
                                        <a class="header-dropdown__trigger trigger-js mobile-action" href="#profile-nav">
                                            <div class="teacher-profile">
                                                <div class="teacher__media">
                                                    <div class="avtar avtar--small avtar--round" data-title="<?php echo CommonHelper::getFirstChar($siteUser['user_first_name']); ?>">
                                                        <?php echo '<img src="' . MyUtility::makeUrl('Image', 'show', array(Afile::TYPE_USER_PROFILE_IMAGE, $siteUserId, Afile::SIZE_SMALL)) . '?' . time() . '" alt="" />'; ?>
                                                    </div>
                                                </div>
                                                <div class="teacher__name mobile-action-label"><?php echo $siteUser['user_first_name']; ?></div>
                                                <svg class="icon icon--arrow">
                                                    <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#arrow-black' ?>"></use>
                                                </svg>
                                            </div>
                                        </a>
                                        <div id="profile-nav" class="header-dropdown__target">
                                            <div class="dropdown__cover">
                                                <nav class="menu--inline">
                                                    <ul>
                                                        <?php if ($siteUserType == User::TEACHER) { ?>
                                                            <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Teacher', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Dashboard'); ?></a></li>
                                                            <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Students', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_My_Students'); ?></a></li>
                                                        <?php
                                                        }
                                                        if ($siteUserType == User::LEARNER) {
                                                        ?>
                                                            <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Learner', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Dashboard'); ?></a></li>
                                                            <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Teachers', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_My_Teachers'); ?></a></li>
                                                        <?php }
                                                        ?>
                                                        <?php if ($siteUserType == User::LEARNER || $siteUserType == User::TEACHER) { ?>
                                                            <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Lessons', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Lessons'); ?></a></li>
                                                            <?php if (GroupClass::isEnabled()) { ?>
                                                                <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Classes', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Classes'); ?></a></li>
                                                            <?php } ?>
                                                            <?php if (Course::isEnabled()) { ?>
                                                                <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Courses', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Courses'); ?></a></li>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if ($siteUserType == User::AFFILIATE) { ?>
                                                            <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Affiliate', '', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Dashboard'); ?></a></li>
                                                        <?php
                                                        } ?>
                                                        <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Account', 'ProfileInfo', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Settings'); ?></a></li>
                                                        <li class="menu__item"><a href="<?php echo MyUtility::makeUrl('Account', 'logout', [], CONF_WEBROOT_DASHBOARD); ?>"><?php echo Label::getLabel('LBL_Logout'); ?></a></li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="header-controls__item header-action">
                                        <div class="header__action">
                                            <a href="javascript:void(0)" onClick="signinForm();" class="header-controls__action btn btn--bordered color-primary user-click mobile-action">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                                    <path d="M10 11V8l5 4-5 4v-3H1v-2h9zm-7.542 4h2.124A8.003 8.003 0 0 0 20 12 8 8 0 0 0 4.582 9H2.458C3.732 4.943 7.522 2 12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10c-4.478 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span class="mobile-action-label">
                                                    <?php echo Label::getLabel('LBL_Login'); ?>
                                                </span>
                                            </a>
                                            <a href="javascript:void(0)" onClick="signupForm();" class="btn btn--primary user-click mobile-action">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                                    <path d="M14 14.252v2.09A6 6 0 0 0 6 22l-2-.001a8 8 0 0 1 10-7.748zM12 13c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm6 6v-3h2v3h3v2h-3v3h-2v-3h-3v-2h3z" />
                                                </svg>
                                                <span class="mobile-action-label"><?php echo Label::getLabel('LBL_SIGN_UP'); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div id="body" class="body">
        <?php } ?>