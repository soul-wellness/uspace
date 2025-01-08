<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$stickyDemoHeader = MyUtility::isDemoUrl() ? 'sticky-demo-header' : '';
?>
<!doctype html>
<html lang="en" dir="<?php echo $siteLanguage['language_direction']; ?>" class="<?php echo $stickyDemoHeader; ?>">

    <head>
        <!-- Basic Page Needs ======================== -->
        <meta charset="utf-8">
        <?php echo $this->writeMetaTags(); ?>
        <!-- MOBILE SPECIFIC METAS ===================== -->
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, maximum-scale=1.0,user-scalable=0" />
        <!-- FONTS ================================================== -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
        <!-- FAVICON ================================================== -->
        <link rel="shortcut icon" href="<?php echo MyUtility::getFavicon(); ?>" />
        <link rel="apple-touch-icon" href="<?php echo MyUtility::getFavicon(); ?>" />
        <!-- CSS/JS ================================================== -->
        <script type="text/javascript">
            var langLbl = <?php echo json_encode(CommonHelper::htmlEntitiesDecode($jsVariables)) ?>;
            var layoutDirection = '<?php echo MyUtility::getLayoutDirection(); ?>';
            const LEARNER = <?php echo User::LEARNER; ?>;
            const TEACHER = <?php echo User::TEACHER; ?>;
            const confWebRootUrl = '<?php echo CONF_WEBROOT_URL; ?>';
            const confFrontEndUrl = '<?php echo CONF_WEBROOT_FRONTEND; ?>';
        </script>
        <?php
        echo $this->getJsCssIncludeHtml(!CONF_DEVELOPMENT_MODE);
        echo Common::setThemeColorStyle(true);
        ?>
    </head>
    <body class="dashboard-learner">
        <div class="site">
            <main class="page">
                <div class="page-body">
                    <div class="container container--narrow">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="box-view box-view--space">
                                    <hgroup class="margin-bottom-4">
                                        <h4 class="margin-bottom-2">
                                            <?php echo Label::getLabel('LBL_QUIZ_SOLVING_INSTRUCTIONS_HEADING'); ?>
                                        </h4>
                                    </hgroup>
                                    <div class="check-list margin-bottom-10 iframe-content">
                                        <iframe onload="resetIframe(this);" src="<?php echo MyUtility::makeUrl('CoursePreview', 'frame', [$data['quilin_id'], 'quiz']); ?>" style="border:none; width:100%; height:30px;"></iframe>
                                    </div>
                                    <div class="repeat-items margin-bottom-10">
                                        <div class="repeat-element">
                                            <div class="repeat-element__title">
                                                <?php echo Label::getLabel('LBL_QUIZ_TYPE'); ?>
                                            </div>
                                            <div class="repeat-element__content">
                                                <?php echo Quiz::getTypes($data['quiz_type']) ?>
                                            </div>
                                        </div>
                                        <div class="repeat-element">
                                            <div class="repeat-element__title">
                                                <?php echo Label::getLabel('LBL_TOTAL_NO._OF_QUESTIONS') ?>
                                            </div>
                                            <div class="repeat-element__content">
                                                <?php echo $data['quiz_questions'] ?>
                                            </div>
                                        </div>
                                        <div class="repeat-element">
                                            <div class="repeat-element__title">
                                                <?php echo Label::getLabel('LBL_DURATION'); ?>
                                            </div>
                                            <div class="repeat-element__content">
                                                <?php
                                                if ($data['quiz_duration'] > 0) {
                                                    echo CommonHelper::convertDuration($data['quiz_duration']);
                                                } else {
                                                    echo Label::getLabel('LBL_NO_LIMIT');
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="repeat-element">
                                            <div class="repeat-element__title">
                                                <?php echo Label::getLabel('LBL_ATTEMPTS_AVAILABLE'); ?>
                                            </div>
                                            <div class="repeat-element__content">
                                                <?php
                                                $label = Label::getLabel('LBL_{attempts}/{total}');
                                                echo str_replace(
                                                    ['{attempts}', '{total}'],
                                                    [($data['quiz_attempts'] - 0), $data['quiz_attempts']],
                                                    $label
                                                );
                                                ?>
                                            </div>
                                        </div>
                                        <div class="repeat-element">
                                            <div class="repeat-element__title">
                                                <?php echo Label::getLabel('LBL_PASS_PERCENTAGE'); ?>
                                            </div>
                                            <div class="repeat-element__content">
                                                <?php echo MyUtility::formatPercent($data['quiz_passmark']) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0);"  class="btn btn--primary btn--wide">
                                        <?php echo Label::getLabel('LBL_START_NOW'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>