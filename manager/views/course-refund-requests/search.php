<?php

defined('SYSTEM_INIT') or die('Invalid Usage.');
$arrFlds = [
    'listserial' => Label::getLabel('LBL_Sr._No'),
    'course_title' => Label::getLabel('LBL_COURSE_NAME'),
    'user_name' => Label::getLabel('LBL_LEARNER_NAME'),
    'corere_status' => Label::getLabel('LBL_STATUS'),
    'corere_created' => Label::getLabel('LBL_REQUESTED_ON'),
    'action' => Label::getLabel('LBL_ACTION'),
];
$tbl = new HtmlElement('table', ['width' => '100%', 'class' => 'table']);
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arrFlds as $val) {
    $e = $th->appendElement('th', [], $val);
}
$srNo = $page == 1 ? 0 : $pageSize * ($page - 1);
foreach ($arrListing as $sn => $row) {
    $srNo++;
    $tr = $tbl->appendElement('tr');
    foreach ($arrFlds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', [], $srNo);
                break;
            case 'user_name':
                $td->appendElement('plaintext', [], CommonHelper::renderHtml($row['user_first_name'] . ' ' . $row['user_last_name']));
                break;
            case 'corere_status':
                $td->appendElement('plaintext', [], $requestStatus[$row['corere_status']]);
                break;
            case 'corere_created':
                $td->appendElement('plaintext', [], MyDate::showDate($row['corere_created'], true));
                break;
            case 'action':
                $action = new Action($row['corere_id']);
                $action->addViewBtn(Label::getLabel('LBL_VIEW'),  'view(' . $row['corere_id'] . ')');
                if ($canEdit && $row['corere_status'] == Course::REFUND_PENDING) {
                    $action->addOtherBtn(Label::getLabel('LBL_CHANGE_STATUS'), 'changeStatusForm("' . $row['corere_id'] . '")', 'edit', 'javascript:void(0)');
                }
                $td->appendElement('plaintext', ['class' => 'align-right'], $action->renderHtml(), true);
                break;
            default:
                $td->appendElement('plaintext', [], CommonHelper::renderHtml($row[$key] ?? '-'));
                break;
        }
    }
}

if (count($arrListing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', ['colspan' => count($arrFlds)], Label::getLabel('LBL_NO_RECORDS_FOUND'));
}
echo $tbl->getHtml();
echo FatUtility::createHiddenFormFromData($postedData, ['name' => 'frmPaging']);
$pagingArr = ['pageCount' => ceil($recordCount / $pageSize), 'page' => $page, 'pageSize' => $pageSize, 'recordCount' => $recordCount];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);