<?php

defined('SYSTEM_INIT') or die('Invalid Usage.');
$arr_flds = [
    'ordsub_id' => Label::getLabel('LBL_ID'),
    'order_id' => Label::getLabel('LBL_ORDER_ID'),
    'ordsub_startdate' => Label::getLabel('LBL_START_DATE'),
    'ordsub_enddate' => Label::getLabel('LBL_END_DATE'),
    'learner_name' => Label::getLabel('LBL_LEARNER'),
    'teacher_name' => Label::getLabel('LBL_TEACHER'),
    'service_type' => Label::getLabel('LBL_SERVICE_TYPE'),
    'order_net_amount' => Label::getLabel('LBL_NET_TOTAL'),
    'order_payment_status' => Label::getLabel('LBL_PAYMENT'),
    'order_addedon' => Label::getLabel('LBL_DATETIME'),
    'ordsub_status' => Label::getLabel('LBL_STATUS'),
    'action' => Label::getLabel('LBL_ACTION'),
];
$tbl = new HtmlElement('table', ['width' => '100%', 'class' => 'table table--hovered']);
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arr_flds as $val) {
    $e = $th->appendElement('th', [], $val);
}
$statuses = Subscription::getStatuses();
$paymentMethod[null] = Label::getLabel('LBL_NA');
foreach ($subscriptions as $row) {
    $tr = $tbl->appendElement('tr');
    foreach ($arr_flds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'action':
                $ul = $td->appendElement("ul", ["class" => "actions"]);
                $li = $ul->appendElement("li");
                $li->appendElement('a', ['href' => MyUtility::makeUrl('Orders', 'viewInvoice', [$row['order_id']]).'?t='.time(), 'class' => 'button small green', 'title' => Label::getLabel('LBL_DOWNLOAD_INVOICE'), 'target' => '_blank'], '<svg class="svg" width="18" height="18"><use xlink:href="/admin/images/retina/sprite-actions.svg#download"></use></svg>', true);
                break;
            case 'order_id':
                $td->appendElement('plaintext', [], Order::formatOrderId(FatUtility::int($row[$key])), true);
                break;
            case 'ordsub_startdate':
            case 'ordsub_enddate':
                $td->appendElement('plaintext', [], MyDate::showDate($row[$key]), true);
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
            case 'order_net_amount':
                $td->appendElement('plaintext', [], MyUtility::formatMoney($row[$key]), true);
                break;
            case 'order_payment_status':
                $td->appendElement('plaintext', [], Order::getPaymentArr($row[$key]), true);
                break;
            case 'service_type':
                $td->appendElement('plaintext', [], AppConstant::getServiceType($row['ordsub_offline']), true);
                break;
            case 'ordsub_status':
                $status = $statuses[$row[$key]];
                if ($row[$key] == Subscription::ACTIVE && strtotime($row['ordsub_enddate']) < $row['ordsub_currenttime_unix']) {
                    $status = Label::getLabel('LBL_EXPIRED');
                }
                $td->appendElement('plaintext', [], $status, true);
                break;
            case 'order_pmethod_id':
                $td->appendElement('plaintext', [], $paymentMethod[$row[$key]] ?? Label::getLabel('LBL_NA'), true);
                break;
            case 'order_addedon':
                $td->appendElement('plaintext', [], MyDate::showDate($row[$key], true), true);
                break;
            default:
                $td->appendElement('plaintext', [], $row[$key], true);
                break;
        }
    }
}
if (count($subscriptions) == 0) {
    $tbl->appendElement('tr')->appendElement('td', ['colspan' => count($arr_flds)], Label::getLabel('LBL_NO_RECORDS_FOUND'));
}
echo $tbl->getHtml();
echo FatUtility::createHiddenFormFromData($post, ['name' => 'srchFormPaging']);
$pagingArr = ['pageCount' => ceil($recordCount / $post['pagesize']), 'pageSize' => $post['pagesize'], 'page' => $post['pageno'], 'recordCount' => $recordCount];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
