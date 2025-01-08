<?php
defined('SYSTEM_INIT') or die('Invalid Usage.');
$frm->setFormTagAttribute('onsubmit', 'setup(this);return false;');
$frm->setFormTagAttribute('class', 'form form--normal');
$frm->developerTags['colClassPrefix'] = 'col-lg-12 col-md-12 col-sm-';
$frm->developerTags['fld_default_col'] = 12;
$frm->getField('customerPhone')->setFieldTagAttribute('placeholder', Label::getLabel('LBL_PHONE_NUMBER'));
$frm->getField('TransactionDesc')->setFieldTagAttribute('placeholder', Label::getLabel('LBL_Write_your_notes_for_this_transaction.'));
?>
<div class="payment-page">
    <div class="cc-payment">
        <div class="logo-payment">
            <?php echo MyUtility::getLogo(); ?>
        </div>
        <div class="reff row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p><?php echo Label::getLabel('LBL_PAYABLE_AMOUNT'); ?> : <strong><?php echo MyUtility::formatMoney($order['order_net_amount']) ?></strong> </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <p><?php echo Label::getLabel('LBL_ORDER_NUMBER'); ?>: <strong><?php echo Order::formatOrderId($order["order_id"]); ?></strong></p>
            </div>
        </div>
        <div class="payment-from">
            <div class="payable-form__body">
                <h6 class="align--center"><?php echo Label::getLabel('LBL_ENTER_YOUR_MPESA_MOBILE_NUMBER'); ?></h6>
                <hr>
                <div><?php echo $frm->getFormHtml(); ?></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        setup = function (frm) {
            if (!$(frm).validate()) {
                return;
            }
            fcom.process();
            var action = fcom.makeUrl('Payment', 'return', [<?php echo $order['order_id']; ?>]);
            fcom.updateWithAjax(action, fcom.frmData(frm), function (res) {
                if (res.url) {
                    window.location.href = res.url;
                }
            }, {fOutMode: 'json', failed: true});
        };
    });
</script>