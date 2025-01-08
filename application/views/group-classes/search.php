<?php defined('SYSTEM_INIT') or die('Invalid Usage.'); ?>
<div class="page-listing__head">
    <div class="row justify-content-between align-items-center">

        <div class="col-xl-auto col-sm-auto">
            <div class="sorting-options">
                <div class="sorting-options__item">
                    <div class="btn btn--filters" onclick="openFilter()">
                        <span class="svg-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 402.577 402.577" style="enable-background:new 0 0 402.577 402.577;" xml:space="preserve">
                                <g>
                                    <path d="M400.858,11.427c-3.241-7.421-8.85-11.132-16.854-11.136H18.564c-7.993,0-13.61,3.715-16.846,11.136
                                          c-3.234,7.801-1.903,14.467,3.999,19.985l140.757,140.753v138.755c0,4.955,1.809,9.232,5.424,12.854l73.085,73.083
                                          c3.429,3.614,7.71,5.428,12.851,5.428c2.282,0,4.66-0.479,7.135-1.43c7.426-3.238,11.14-8.851,11.14-16.845V172.166L396.861,31.413
                                          C402.765,25.895,404.093,19.231,400.858,11.427z"></path>
                                </g>
                            </svg>
                        </span>
                        <?php echo Label::getLabel('LBL_FILTERS'); ?>
                        <?php
                        $count = 0;
                        foreach ($post as $field) {
                            if (is_array($field)) {
                                $count += count($field);
                            }
                        }
                        if ($count > 0) {
                        ?>
                            <span class="filters-count"><?php echo $count; ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="d-sm-flex align-items-center justify-content-center">
                <div class="switch-options">
                    <?php if (User::offlineSessionsEnabled()) { ?>
                        <div class="switch-options__item">
                            <label class="switch-action is-hover switch-filter">
                                <span class="switch switch--small">
                                    <input class="switch__label" type="checkbox" onclick="searchOfflineClasses(this.checked);" <?php echo empty($post['grpcls_offline'] ?? '') ? '' : 'checked'; ?> />
                                    <i class="switch__handle bg-green"></i>
                                </span>
                                <span class="switch-action-label no-wrap  margin-left-2"><?php echo Label::getLabel('LBL_OFFLINE_SESSIONS'); ?></span>
                                <span class="tooltip tooltip--top bg-black">
                                    <span class="tooltip__content"><?php echo Label::getLabel('LBL_CLASSES_THAT_ARE_AVAILABLE_OFFLINE'); ?></span>
                                </span>
                            </label>
                        </div>
                        <div class="geo-location_body switch-options__item" style="display: none;">
                            <div class="geo-location">
                                <div class="geo-location-wrap">
                                    <input class="geo-location_input pac-target-input" id="google-autocomplete" size="50" placeholder="<?php echo Label::getLabel('LBL_ADDRESS'); ?>" type="search" name="address" value="<?php echo $post['formatted_address'] ?>">
                                    <span class="close btn-close" id="btnCloseJs" onclick="clearLocation();" style="display:<?php echo empty($post['formatted_address']) ? 'none' : 'block' ?>"></span>
                                    <button class="btn-detect" type="button" onclick="getLocation();">
                                        <svg class="svg" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.05492878,13 L1,13 L1,11 L3.05492878,11 C3.5160776,6.82838339 6.82838339,3.5160776 11,3.05492878 L11,1 L13,1 L13,3.05492878 C17.1716166,3.5160776 20.4839224,6.82838339 20.9450712,11 L23,11 L23,13 L20.9450712,13 C20.4839224,17.1716166 17.1716166,20.4839224 13,20.9450712 L13,23 L11,23 L11,20.9450712 C6.82838339,20.4839224 3.5160776,17.1716166 3.05492878,13 Z M12,5 C8.13400675,5 5,8.13400675 5,12 C5,15.8659932 8.13400675,19 12,19 C15.8659932,19 19,15.8659932 19,12 C19,8.13400675 15.8659932,5 12,5 Z M12,8 C14.209139,8 16,9.790861 16,12 C16,14.209139 14.209139,16 12,16 C9.790861,16 8,14.209139 8,12 C8,9.790861 9.790861,8 12,8 Z M12,10 C10.8954305,10 10,10.8954305 10,12 C10,13.1045695 10.8954305,14 12,14 C13.1045695,14 14,13.1045695 14,12 C14,10.8954305 13.1045695,10 12,10 Z" />
                                        </svg>
                                        <span class="btn-detect-txt"> <?php echo Label::getLabel('LBL_DETECT_MY_LOCATION'); ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
    <div class="search-result text-center pt-4 mt-3">
        <h4><?php echo str_replace('{recordcount}', $recordCount, Label::getLabel('LBL_FOUND_THE_BEST_{recordcount}_CLASSES_FOR_YOU')) ?></h4>
    </div>
</div>
<?php if (count($classes)) { ?>
    <div class="page-listing__body">
        <div class="group-cover">
            <div class="group__list">
                <div class="row">
                    <?php
                    foreach ($classes as $class) {
                        $classData = ['class' => $class, 'siteUserId' => $siteUserId, 'bookingBefore' => $bookingBefore, 'cardClass' => 'col-xl-4 col-md-6 margin-bottom-20'];
                        $this->includeTemplate('group-classes/card.php', $classData, false);
                    }
                    ?>
                </div>
            </div>
            <?php
            $pagingArr = [
                'page' => $post['pageno'],
                'pageSize' => $post['pagesize'],
                'recordCount' => $recordCount,
                'pageCount' => ceil($recordCount / $post['pagesize']),
            ];
            echo FatUtility::createHiddenFormFromData($post, ['name' => 'frmSearchPaging']);
            $this->includeTemplate('_partial/pagination.php', $pagingArr, false);
            ?>
        </div>
    </div>
<?php } else { ?>
    <div class="page-listing__body">
        <div class="box -padding-30" style="margin-bottom: 30px;">
            <div class="message-display">
                <div class="message-display__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 408">
                        <path d="M488.468,408H23.532A23.565,23.565,0,0,1,0,384.455v-16.04a15.537,15.537,0,0,1,15.517-15.524h8.532V31.566A31.592,31.592,0,0,1,55.6,0H456.4a31.592,31.592,0,0,1,31.548,31.565V352.89h8.532A15.539,15.539,0,0,1,512,368.415v16.04A23.565,23.565,0,0,1,488.468,408ZM472.952,31.566A16.571,16.571,0,0,0,456.4,15.008H55.6A16.571,16.571,0,0,0,39.049,31.566V352.891h433.9V31.566ZM497,368.415a0.517,0.517,0,0,0-.517-0.517H287.524c0.012,0.172.026,0.343,0.026,0.517a7.5,7.5,0,0,1-7.5,7.5h-48.1a7.5,7.5,0,0,1-7.5-7.5c0-.175.014-0.346,0.026-0.517H15.517a0.517,0.517,0,0,0-.517.517v16.04a8.543,8.543,0,0,0,8.532,8.537H488.468A8.543,8.543,0,0,0,497,384.455h0v-16.04ZM63.613,32.081H448.387a7.5,7.5,0,0,1,0,15.008H63.613A7.5,7.5,0,0,1,63.613,32.081ZM305.938,216.138l43.334,43.331a16.121,16.121,0,0,1-22.8,22.8l-43.335-43.318a16.186,16.186,0,0,1-4.359-8.086,76.3,76.3,0,1,1,19.079-19.071A16,16,0,0,1,305.938,216.138Zm-30.4-88.16a56.971,56.971,0,1,0,0,80.565A57.044,57.044,0,0,0,275.535,127.978ZM63.613,320.81H448.387a7.5,7.5,0,0,1,0,15.007H63.613A7.5,7.5,0,0,1,63.613,320.81Z"></path>
                    </svg>
                </div>
                <h5><?php echo Label::getLabel('LBL_NO_CLASS_FOUND!'); ?></h5>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    autoCompleteGoogle();
    toggleAdressSrch(<?php echo $post['grpcls_offline'] ?>);
</script>