<?php

defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = [
    'ordcls_id' => Label::getLabel('LBL_CLASS_ID'),
    'order_id' => Label::getLabel('LBL_ORDER_ID'),
    'learner_name' => Label::getLabel('LBL_LEARNER'),
    'teacher_name' => Label::getLabel('LBL_TEACHER'),
    'grpcls_language_name' => Label::getLabel('LBL_LANGUAGE'),
    'service_type' => Label::getLabel('LBL_SERVICE_TYPE'),
    'ordcls_net_amount' => Label::getLabel('LBL_NET_TOTAL'),
    'order_payment_status' => Label::getLabel('LBL_PAYMENT'),
    'order_addedon' => Label::getLabel('LBL_DATETIME'),
    'ordcls_status' => Label::getLabel('LBL_STATUS'),
    'action' => Label::getLabel('LBL_ACTION'),
];
$tbl = new HtmlElement('table', ['width' => '100%', 'class' => 'table table--hovered']);
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', [], $val);
}
$paymentMethod = OrderPayment::getMethods();
foreach ($orders as $row) {
    $tr = $tbl->appendElement('tr');
    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'action':
                $ul = $td->appendElement("ul", ["class" => "actions"]);
                $li = $ul->appendElement("li");
                $li->appendElement('a', ['href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_View'), "onclick" => "viewClass(" . $row['ordcls_id'] . ")"], '<svg class="svg" width="18" height="18"><use xlink:href="/admin/images/retina/sprite-actions.svg#view"></use></svg>', true);
                $li1 = $ul->appendElement("li");
                $li1->appendElement('a', ['href' => MyUtility::makeUrl('Orders', 'viewInvoice', [$row['order_id'],$row['ordcls_id']]).'?t='.time(), 'class' => 'button small green', 'title' => Label::getLabel('LBL_DOWNLOAD_INVOICE'), 'target' => '_blank'], '<svg class="svg" width="18" height="18"><use xlink:href="/admin/images/retina/sprite-actions.svg#download"></use></svg>', true);
                break;
            case 'order_id':
                $td->appendElement('plaintext', [], Order::formatOrderId(FatUtility::int($row[$key])), true);
                break;
            case 'learner_name':
                $td->appendElement('plaintext', [], $row['learner_first_name'] . ' ' . $row['learner_last_name'], true);
                break;
            case 'teacher_name':
                $td->appendElement('plaintext', [], $row['teacher_first_name'] . ' ' . $row['teacher_last_name'], true);
                break;
            case 'order_type':
                $td->appendElement('plaintext', [], Order::getTypeArr($row[$key]), true);
                break;
            case 'ordcls_net_amount':
                $netAmount = $row['ordcls_amount'] - $row['ordcls_discount'] - $row['ordcls_reward_discount'];
                $td->appendElement('plaintext', [], MyUtility::formatMoney($netAmount), true);
                break;
            case 'service_type':
                $td->appendElement('plaintext', [], AppConstant::getServiceType($row['grpcls_offline']), true);
                break;
            case 'order_payment_status':
                $td->appendElement('plaintext', [], Order::getPaymentArr($row[$key]), true);
                break;
            case 'order_pmethod_id':
                $td->appendElement('plaintext', [], $paymentMethod[$row[$key]] ?? Label::getLabel('LBL_NA'), true);
                break;
            case 'order_addedon':
                $td->appendElement('plaintext', [], MyDate::showDate($row[$key], true), true);
                break;
            case 'ordcls_status':
                $td->appendElement('plaintext', [], OrderClass::getStatuses($row[$key]), true);
                break;
            default:
                $td->appendElement('plaintext', [], $row[$key], true);
                break;
        }
    }
}
if (count($orders) == 0) {
    $tbl->appendElement('tr')->appendElement('td', ['colspan' => count($arr_flds)], Label::getLabel('LBL_NO_RECORDS_FOUND'));
}
echo $tbl->getHtml();
echo FatUtility::createHiddenFormFromData($post, ['name' => 'srchFormPaging']);
$pagingArr = ['pageCount' => ceil($recordCount / $post['pagesize']), 'pageSize' => $post['pagesize'], 'page' => $post['pageno'], 'recordCount' => $recordCount];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
