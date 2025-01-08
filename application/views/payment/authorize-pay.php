<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$currency = MyUtility::getSystemCurrency();
$frm->setFormTagAttribute('id', 'frmPaymentForm');
$frm->setFormTagAttribute('class', 'form form--normal');
$frm->setFormTagAttribute('action', MyUtility::makeUrl('Payment', 'return', [$order['order_id']]));
$frm->getField('cc_number')->addFieldTagAttribute('class', 'p-cards');
$frm->getField('cc_number')->addFieldTagAttribute('id', 'cc_number');
$cancelUrl = MyUtility::makeFullUrl('Payment', 'cancel', [$order['order_id']], CONF_WEBROOT_FRONTEND);
$ccNumber = $frm->getField('cc_number');
$ccOwner = $frm->getField('cc_owner');
$ccCVV = $frm->getField('cc_cvv');

?>
<div class="payment-page">
    <div class="cc-payment">
        <div class="logo-payment">
            <?php echo MyUtility::getLogo(); ?>
        </div>
        <div class="reff row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p class=""><?php echo Label::getLabel('LBL_PAYABLE_AMOUNT'); ?> : <strong><?php echo MyUtility::formatMoney($order['order_net_amount']); ?></strong> </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p class=""><?php echo Label::getLabel('LBL_ORDER_NUMBER'); ?>: <strong><?php echo Order::formatOrderId($order["order_id"]); ?></strong></p>
            </div>
        </div>
        <div class="payment-from">
            <?php echo $frm->getFormTag(); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="field-set">
                        <div class="caption-wraper">
                            <label class="field_label"><?php echo Label::getLabel('LBL_CARD_HOLDER_NAME'); ?>
                                <?php if ($ccOwner->requirement->isRequired()) { ?>
                                    <span class="spn_must_field">*</span>
                                <?php } ?>
                            </label>
                        </div>
                        <div class="field-wraper">
                            <div class="field_cover"> <?php echo $ccOwner->getHtml(); ?> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="field-set">
                        <div class="caption-wraper">
                            <label class="field_label">
                                <?php echo Label::getLabel('LBL_ENTER_CREDIT_CARD_NUMBER'); ?>
                                    <?php if ($ccNumber->requirement->isRequired()) { ?>
                                        <span class="spn_must_field">*</span>
                                    <?php } ?>
                                </label>
                        </div>
                        <div class="field-wraper">
                            <div class="field_cover"> <?php echo $ccNumber->getHtml(); ?> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="field-set">
                        <div class="caption-wraper">
                            <label class="field_label"> <?php echo Label::getLabel('LBL_EXPIRY_MONTH'); ?> </label>
                        </div>
                        <div class="field-wraper">
                            <div class="field_cover">
                                <?php
                                $fld = $frm->getField('cc_expire_date_month');
                                $fld->addFieldTagAttribute('id', 'ccExpMonth');
                                $fld->addFieldTagAttribute('class', 'ccExpMonth  combobox required');
                                echo $fld->getHtml();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="caption-wraper">
                        <label class="field_label"> <?php echo Label::getLabel('LBL_EXPIRY_YEAR'); ?> </label>
                    </div>
                    <div class="field-set">
                        <div class="field-wraper">
                            <div class="field_cover">
                                <?php
                                $fld = $frm->getField('cc_expire_date_year');
                                $fld->addFieldTagAttribute('id', 'ccExpYear');
                                $fld->addFieldTagAttribute('class', 'ccExpYear  combobox required');
                                echo $fld->getHtml();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="field-set">
                        <div class="caption-wraper">
                            <label class="field_label"><?php echo Label::getLabel('LBL_CVV_SECURITY_CODE'); ?>
                                    <?php if ($ccCVV->requirement->isRequired()) { ?>
                                        <span class="spn_must_field">*</span>
                                    <?php } ?>
                            </label>
                        </div>
                        <div class="field-wraper">
                            <div class="field_cover"> <?php echo $ccCVV->getHtml(); ?> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="total-pay"><?php echo MyUtility::formatMoney($order['order_net_amount']) ?> <small>(<?php echo Label::getLabel('LBL_TOTAL_PAYABLE'); ?>)</small> </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="field-set">
                        <div class="caption-wraper"><label class="field_label"></label></div>
                        <div class="field-wraper">
                            <div class="field_cover"> 
                                <?php echo $frm->getFieldHtml('order_id'); ?>
                                <?php echo $frm->getFieldHtml('btn_submit'); ?>
                                <a href="<?php echo $cancelUrl; ?>" class="btn btn--medium"><?php echo Label::getLabel('LBL_Cancel'); ?></a>
                            </div>
                        </div>
                        <span class="-gap -hide-mobile"></span>
                        <?php if ($order['order_currency_code'] != $currency['currency_code']) { ?>
                            <p class="-color-secondary"><?php echo MyUtility::getCurrencyDisclaimer($order['order_net_amount']); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            </form>
            <?php echo $frm->getExternalJs(); ?>
            <div id="ajax_message"></div>
        </div>
    </div>
</div>