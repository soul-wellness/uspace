<?php
defined('SYSTEM_INIT') or die('Invalid Usage');
$pagination = '';
if ($pageCount <= 1) {
    return $pagination;
}
/* Number of links to display */
$linksToDisp = isset($linksToDisp) ? $linksToDisp : 3;
/* Current page number */
$pageNumber = $page;
/* arguments mixed(array/string(comma separated)) // function arguments */
$arguments = (isset($arguments)) ? $arguments : null;
$pageSize = (isset($pageSize)) ? $pageSize : FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
/* padArgListTo boolean(T/F) // where to pad argument list (left/right) */
$padArgToLeft = (isset($padArgToLeft)) ? $padArgToLeft : true;
/* On clicking page link which js function need to call */
$callBackJsFunc = isset($callBackJsFunc) ? $callBackJsFunc : 'goToSearchPage';
if (null != $arguments) {
    if (is_array($arguments)) {
        $args = implode(', ', $arguments);
    } elseif (is_string($arguments)) {
        $args = $arguments;
    }
    if ($padArgToLeft) {
        $callBackJsFunc = $callBackJsFunc . '(' . $args . ', xxpagexx);';
    } else {
        $callBackJsFunc = $callBackJsFunc . '(xxpagexx, ' . $args . ');';
    }
} else {
    $callBackJsFunc = $callBackJsFunc . '(xxpagexx);';
}
$pagination .= FatUtility::getPageString(
                '<li><a href="javascript:void(0);" onclick="' . $callBackJsFunc . '">xxpagexx</a></li>',
                $pageCount, $pageNumber,
                ' <li class="selected"><a href="javascript:void(0);">xxpagexx</a></li>',
                ' ',
                $linksToDisp,
                ' <li><a href="javascript:void(0);" onclick="' . $callBackJsFunc . '"><i class="ion-ios-arrow-left"></i><i class="ion-ios-arrow-left"></i></a></li>',
                ' <li><a href="javascript:void(0);" onclick="' . $callBackJsFunc . '"><i class="ion-ios-arrow-right"></i><i class="ion-ios-arrow-right"></i></a></li>',
                ' <li><a href="javascript:void(0);" onclick="' . $callBackJsFunc . '"><i class="ion-ios-arrow-left"></i></a></li>',
                ' <li><a href="javascript:void(0);" onclick="' . $callBackJsFunc . '"><i class="ion-ios-arrow-right"></i></a></li>'
);
$ul = new HtmlElement('ul', ['class' => 'pagination',], $pagination, true);
?>
<div class="section footinfo">
    <aside class="grid_1"><?php echo $ul->getHtml(); ?></aside>
    <aside class="grid_2">
        <?php echo Label::getLabel('LBL_Showing'); ?> 
        <?php echo $startIdx = ($pageNumber - 1) * $pageSize + 1; ?> 
        <?php echo Label::getLabel('LBL_to'); ?> 
        <?php echo ($recordCount < $startIdx + $pageSize - 1 ) ? $recordCount : $startIdx + $pageSize - 1; ?> 
        <?php echo Label::getLabel('LBL_of'); ?> 
        <?php echo ($recordCount == SEARCH_MAX_COUNT) ? $recordCount . '+' : $recordCount; ?> 
        <?php echo Label::getLabel('LBL_Entries'); ?>
    </aside>
</div>