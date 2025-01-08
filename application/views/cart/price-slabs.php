<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$steps = Cart::getSteps($cartSteps);
$price = round($price - (($discount * $price) / 100), 2);
$min = 1;
$max = 99;
if (!empty($activePlan)) {
    $max = $activePlan['subplan_lesson_count'] - $activePlan['ordsplan_used_lesson_count'];
}
if (!$max) {
    $min = 0;
}
?>
<div class="modal-header modal-header--checkout">
    <a href="javascript:void(0);" onclick="cart.langSlots('<?php echo $teacher['user_id']; ?>', '<?php echo $tlangId; ?>', '<?php echo $duration; ?>');" class="btn btn--bordered color-black btn--back">
        <svg class="icon icon--back">
            <use xlink:href="<?php echo CONF_WEBROOT_URL . 'images/sprite.svg#back'; ?>"></use>
        </svg>
        <?php echo Label::getLabel('LBL_BACK'); ?>
    </a>
    <h4 class="flex-1 align-center"><?php echo Label::getLabel('LBL_SELECT_LESSON_QUANTITY'); ?></h4>
    <button type="button" class="btn-close yocoachmodalJs" data-bs-dismiss="modal" aria-label=""></button>
    <div class="step-nav">
        <ul>
            <?php foreach ($steps as $key => $step) { ?>
                <li class="step-nav_item <?php echo in_array($key, $stepProcessing) ? 'is-process' : ''; ?> <?php echo in_array($key, $stepCompleted) ? 'is-completed' : ''; ?> ">
                    <a href="javascript:void(0);"><?php echo $step; ?></a>
                    <?php if (in_array($key, $stepCompleted)) { ?><span class="step-icon"></span><?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<div class="modal-body p-0">
    <div class="box box--checkout">
        <div class="box__body">
            <div class="checkout-title">
                <p> </p>
            </div>
            <div class="d-flex justify-content-center">
                <div class="col-lg-6 col-md-10 col-xl-4">
                    <div class="total-price">
                        <?php if (User::offlineSessionsEnabled($teacher['user_id'])) { ?>
                            <div class="selector-switch  margin-bottom-12">
                                <span class="selector-switch__info mb-3">
                                    <?php echo Label::getLabel('LBL_CHECKOUT_OFFLINE_TITLE'); ?>
                                </span>
                                <label class="selector-switch__control">
                                    <span class="selector-switch__label"><?php echo Label::getLabel('LBL_OFFLINE_LESSON'); ?></span>
                                    <span class="selector-switch__action">
                                        <span class="switch switch--small">
                                            <input class="switch__label" type="checkbox" name="ordles_offline" onclick="cart.selectOfflineSession('<?php echo $address['usradd_id']; ?>');" value="1" <?php echo ($ordlesOffline) ? 'checked' : ''; ?> />
                                            <i class="switch__handle bg-green"></i>
                                        </span>
                                    </span>
                                </label>
                                <span class="selector-switch__info">
                                    <?php echo Label::getLabel('LBL_SEE_ADDRESS_INFO'); ?>
                                    <span class="selector-switch__info-media is-hover">
                                        <svg class="icon icon--info" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <g transform="translate(0.143)">
                                                <path d="M8,14a6,6,0,1,1,6-6A6,6,0,0,1,8,14Zm0-1.2A4.8,4.8,0,1,0,3.2,8,4.8,4.8,0,0,0,8,12.8ZM7.4,5H8.6V6.2H7.4Zm0,2.4H8.6V11H7.4Z" transform="translate(3.857 4)" fill="#0037b4"></path>
                                            </g>
                                        </svg>
                                        <div class="tooltip tooltip--top bg-black"><?php echo UserAddresses::format($address); ?></div>
                                    </span>
                                </span>
                            </div>
                        <?php } ?>
                        <span class="selector-switch__info mb-3">
                            <?php echo Label::getLabel('LBL_CHECKOUT_SLAB_TITLE'); ?>
                        </span>
                        <div class="qty-option">
                            <button class="btn btn--count" onclick="cart.updateQuantity('-')"><?php echo Label::getLabel('LBL_-'); ?></button>
                            <input type="text" name="ordles_quantity" onchange="cart.prop.ordles_quantity = parseInt(this.value)" min="<?php echo $min; ?>" max="<?php echo $max; ?>" value="<?php echo $quantity; ?>" readonly="readonly" />
                            <button class="btn btn--count" onclick="cart.updateQuantity('+')"><?php echo Label::getLabel('LBL_+'); ?></button>
                        </div>
                        <?php if (empty($activePlan)) { ?>
                            <div class="selector-switch  margin-bottom-12">
                                <label class="selector-switch__control">
                                    <span class="selector-switch__media">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <g transform="translate(2 2)">
                                                <g transform="translate(0 0)">
                                                    <g>
                                                        <path d="M16.361,2.309c0-.479,0-.91,0-1.342A.907.907,0,0,1,17.1.033.892.892,0,0,1,18.176.88q.02,1.867,0,3.734a.863.863,0,0,1-.846.852q-1.874.017-3.749,0a.9.9,0,0,1,0-1.8c.426-.014.852,0,1.278-.006h.2l.02-.056c-.266-.183-.524-.378-.8-.546A8.188,8.188,0,1,0,8.605,18.1a11.985,11.985,0,0,0,1.355.122.915.915,0,1,1-.028,1.828A10.007,10.007,0,0,1,6.39.712a9.738,9.738,0,0,1,9.757,1.433c.056.042.112.086.213.164Z" transform="translate(0.002 0.002)"></path>
                                                        <path d="M159.8,68.01c0-.925-.007-1.849,0-2.773a.881.881,0,0,1,.768-.862.892.892,0,0,1,1,.642,1.518,1.518,0,0,1,.038.4c0,1.557,0,3.115,0,4.672a.568.568,0,0,0,.18.447q1.139,1.122,2.261,2.262a.906.906,0,0,1-.361,1.539.841.841,0,0,1-.87-.2q-1.393-1.38-2.772-2.775a.911.911,0,0,1-.248-.682c0-.889,0-1.778,0-2.667h0Z" transform="translate(-150.706 -60.705)"></path>
                                                        <path d="M274.448,274.188a.909.909,0,0,1-1.818-.009.909.909,0,1,1,1.818.009Z" transform="translate(-257.116 -257.716)"></path>
                                                        <path d="M221.777,308.31a.91.91,0,0,1-.009,1.82.91.91,0,1,1,.009-1.82Z" transform="translate(-208.288 -290.766)"></path>
                                                        <path d="M320.442,162.252a.906.906,0,1,1,.914-.9.9.9,0,0,1-.914.9Z" transform="translate(-301.357 -151.31)"></path>
                                                        <path d="M308.44,223.652a.906.906,0,1,1,.914-.9.9.9,0,0,1-.914.9Z" transform="translate(-290.04 -209.216)"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>

                                    <span class="selector-switch__label"><?php echo Label::getLabel('LBL_RECURRING_BUY'); ?></span>
                                    <span class="selector-switch__action">
                                        <span class="switch switch--small">
                                            <input class="switch__label" type="checkbox" name="ordles_type" onclick="cart.selectSubscription();" value="<?php echo Lesson::TYPE_SUBCRIP; ?>" <?php echo (Lesson::TYPE_SUBCRIP == $ordlesType) ? 'checked' : ''; ?> />
                                            <i class="switch__handle bg-green"></i>
                                        </span>
                                    </span>

                                </label>
                                <span class="selector-switch__info"><?php echo Label::getLabel('LBL_REPEAT_ON'); ?>
                                    <strong class="color-primary margin-left-1"> <?php echo str_replace('{number}', $subWeek, Label::getLabel('LBL_EVERY_{NUMBER}_WEEKS')); ?> </strong>
                                    <span class="selector-switch__info-media is-hover">
                                        <svg class="icon icon--info" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <g transform="translate(0.143)">
                                                <path d="M8,14a6,6,0,1,1,6-6A6,6,0,0,1,8,14Zm0-1.2A4.8,4.8,0,1,0,3.2,8,4.8,4.8,0,0,0,8,12.8ZM7.4,5H8.6V6.2H7.4Zm0,2.4H8.6V11H7.4Z" transform="translate(3.857 4)" fill="#0037b4"></path>
                                            </g>
                                        </svg>
                                        <div class="tooltip tooltip--top bg-black"><?php echo Label::getLabel('LBL_SUBSCRIPTION_HELP_TEXT'); ?></div>
                                    </span>
                                </span>
                            </div>
                            <p><?php echo Label::getLabel('LBL_TOTAL_PRICE'); ?> : <strong id="price-js"> <?php echo MyUtility::formatMoney($price * $quantity); ?></strong></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-foot">
            <div class="box-foot__left">
                <div class="teacher-profile">
                    <div class="teacher__media">
                        <div class="avtar avtar-md">
                            <img src="<?php echo MyUtility::makeUrl('Image', 'show', [Afile::TYPE_USER_PROFILE_IMAGE, $teacher['user_id'], Afile::SIZE_SMALL]) . '?' . time(); ?>" alt="><?php echo $teacher['user_first_name'] . ' ' . $teacher['user_last_name']; ?>">
                        </div>
                    </div>
                    <div class="teacher__name"><?php echo $teacher['user_first_name'] . ' ' . $teacher['user_last_name']; ?></div>
                </div>
                <div class="step-breadcrumb">
                    <ul>
                        <li><a href="javascript:void(0);"><?php echo $tlangName; ?>, <?php echo str_replace('{duration}', $duration, Label::getLabel('LBL_{duration}_Mins')); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="box-foot__right">
                <a href="javascript:void(0);" onclick="cart.viewCalendar('<?php echo $teacher['user_id']; ?>', '<?php echo $tlangId; ?>', '<?php echo $duration; ?>', cart.prop.ordles_quantity, cart.prop.ordles_type, cart.prop.ordles_offline);" class="btn btn--primary color-white"><?php echo LabeL::getLabel('LBL_NEXT'); ?></a>
            </div>
        </div>
    </div>
</div>
<script>
    LESSON_TYPE_REGULAR = '<?php echo Lesson::TYPE_REGULAR; ?>';
    LESSON_TYPE_SUBCRIP = '<?php echo Lesson::TYPE_SUBCRIP; ?>';
    cart.prop.ordles_quantity = parseInt('<?php echo $quantity; ?>');
    cart.prop.ordles_type = parseInt('<?php echo $ordlesType; ?>');
    cart.prop.ordles_offline = parseInt('<?php echo $ordlesOffline; ?>');
    var price = <?php echo MyUtility::convertToSiteCurrency($price); ?>;
    var minValue = <?php echo $min; ?>;
    var maxValue = <?php echo $max; ?>;
</script>