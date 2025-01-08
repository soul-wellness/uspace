<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<!doctype html>
<html lang="<?php echo strtolower($siteLanguage['language_code']); ?>" dir="<?php echo $siteLanguage['language_direction']; ?>">
    <head>
        <!-- Basic Page Needs ======================== -->
        <meta charset="utf-8">
        <!-- MOBILE SPECIFIC METAS ===================== -->
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1.0,user-scalable=0" />
        <!-- FONTS ================================================== -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
        <!-- CSS/JS ================================================== -->
        <?php
        $jsVariables = CommonHelper::htmlEntitiesDecode($jsVariables);
        $sslUsed = (FatApp::getConfig('CONF_USE_SSL', FatUtility::VAR_BOOLEAN, false)) ? 1 : 0;
        $websiteName = FatApp::getConfig('CONF_WEBSITE_NAME_' . $siteLangId, FatUtility::VAR_STRING, '');
        ?>
        <script type="text/javascript">
            var API_CALL = true;
            var langLbl = <?php echo json_encode(CommonHelper::htmlEntitiesDecode($jsVariables)) ?>;
            var layoutDirection = '<?php echo MyUtility::getLayoutDirection(); ?>';
            var SslUsed = '<?php echo $sslUsed; ?>';
            var userTimeZone = '<?php echo MyUtility::getSiteTimezone(); ?>';
            var timeZoneOffset = '<?php echo MyDate::getOffset(MyUtility::getSiteTimezone()); ?>';
            var cookieConsent = <?php echo json_encode($cookieConsent); ?>;
            var userType = <?php echo FatUtility::int($siteUserType); ?>;
            const LEARNER = <?php echo User::LEARNER; ?>;
            const TEACHER = <?php echo User::TEACHER; ?>;
            var ALERT_CLOSE_TIME = '<?php echo FatApp::getConfig("CONF_AUTO_CLOSE_ALERT_TIME", FatUtility::VAR_INT, 0); ?>';
            var confWebRootUrl = '<?php echo CONF_WEBROOT_URL; ?>';
            var confFrontEndUrl = '<?php echo CONF_WEBROOT_FRONTEND; ?>';
            var monthNames = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::getAllMonthName(false, $siteLangId))); ?>;
            var weekDayNames = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::dayNames(false, $siteLangId))); ?>;
            var meridiems = <?php echo json_encode(CommonHelper::htmlEntitiesDecode(MyDate::meridiems(false, $siteLangId))); ?>;
        </script>
        <?php echo $this->getJsCssIncludeHtml(!CONF_DEVELOPMENT_MODE); ?>
        <?php echo Common::setThemeColorStyle(true); ?>
    </head>
    <body>
        <div>
            <!-- ] -->
            <main class="page">