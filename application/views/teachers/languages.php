<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$teachs = $srchFrm->getField('teachs');
$pageno = $srchFrm->getField('pageno');
$jslabels = json_encode([
    'allLanguages' => Label::getLabel('LBL_ALL_TEACH_LANGUAGES'),
    'selectTiming' => Label::getLabel('LBL_SELECT_TIMING'),
    'allPrices' => Label::getLabel('LBL_ALL_PRICES'),
]);
?>
<script>
    LABELS = <?php echo $jslabels; ?>;
</script>
<section class="section section--gray section--listing">
    <div class="container container--narrow">
        <h1 class="page-title text-center mb-5">
            <?php echo str_replace('{teachlang}', ($sLanguage['tlang_name']), Label::getLabel('LBL_{teachlang}_LANGUAGE_PAGE_HEADING')); ?>
        </h1>
    </div>
    <div class="section-filters">
        <?php echo $srchFrm->getFormTag(); ?>
        <div class="container container--narrow">
            <div id="filter-panel" class="filter-panel">
                <div class="filter-panel__head">
                    <h4><?php echo Label::getLabel('LBL_FILTERS'); ?></h4>
                    <a href="javascript:closeFilter();" class="close"></a>
                </div>
                <div class="filter-panel__body">
                    <div class="filters-layout">
                        <!-- [ LANGUAGE FILTER ========= -->
                        <div class="filters-layout__item filters-layout__item-first">
                            <div class="filter-item">
                                <div class="filter-item__trigger filter-item__trigger-js cursor-pointer">
                                    <div class="filter-item__label">
                                        <?php echo Label::getLabel('LBL_TEACH_LANGUAGE'); ?>
                                        <span class="filters-count d-sm-none language-count-js" style="display: none;"></span>
                                    </div>
                                    <div class="filter-item__field d-none d-sm-block">
                                        <div class="filter-item__select filter-item__select--arrow teachlang-placeholder-js"><?php echo Label::getLabel('LBL_ALL_LANGUAGE'); ?></div>
                                    </div>
                                </div>
                                <div class="filter-item__target filter-item__target-js" style="display: none;">
                                    <div class="filter-dropdown">
                                        <div class="filter-dropdown__head d-block d-sm-none">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h5><?php echo Label::getLabel('LBL_SELECT_LANGUAGE'); ?></h5>
                                                </div>
                                                <div><a href="javascript:clearMore('teachs[]');" class="clear-link bold-600 color-primary underline"><?php echo Label::getLabel('LBL_CLEAR'); ?></a></div>
                                            </div>
                                        </div>
                                        <div class="filter-dropdown__body">
                                            <div class="search-form-cover">
                                                <div class="search-form">
                                                    <div class="search-form__field">
                                                        <input type="text" name="teach_language" onkeyup="onkeyupLanguage();" placeholder="<?php echo Label::getLabel('LBL_SEARCH_LANGUAGE'); ?>">
                                                    </div>
                                                    <div class="search-form__action">
                                                        <span class="btn btn--equal btn--transparent color-black">
                                                            <svg class="icon icon--search icon--small">
                                                                <use xlink:href="<?php echo CONF_WEBROOT_URL ?>images/sprite.svg#search"></use>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="select-list select-list--vertical select-list--scroll">
                                                <?php $this->includeTemplate('_partial/teach-languages.php', ['teachLanguages' => $teachs->options, 'values' => $teachs->value, 'langPage' => true]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ] -->
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="pageno" value="<?php echo $pageno->value; ?>" />
        </form>
    </div>
    <div class="container container--narrow">
        <div class="page-listing" id="listing"></div>
    </div>
    <?php if (!empty($sLanguage['tlang_description'])) { ?>
        <div class="container container--narrow">
            <?php
            $desclbl = Label::getLabel('LBL_{language}_DESCRIPTION');
            $desclbl = str_replace('{language}', $sLanguage['tlang_name'], $desclbl);
            ?>
            <h4><?php echo $desclbl ?></h4>
            <p><?php echo htmlspecialchars_decode($sLanguage['tlang_description'] ?? ''); ?></p>
        </div>
    <?php  } ?>
</section>