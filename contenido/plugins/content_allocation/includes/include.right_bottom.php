<?php

/**
 * This file contains the right bottom frame backend page for the content allocation plugin in content area.
 *
 * @package    Plugin
 * @subpackage ContentAllocation
 * @author     Unknown
 * @copyright  four for business AG <www.4fb.de>
 * @license    https://www.contenido.org/license/LIZENZ.txt
 * @link       https://www.4fb.de
 * @link       https://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * @var cDb $db
 * @var cSession $sess
 * @var string $area
 * @var int $frame
 */

if (isset($_REQUEST['treeItem'])) {
    die('Illegal call!');
}

$requestTreeItem = isset($_REQUEST['treeItemPost']) && is_array($_REQUEST['treeItemPost']) ? $_REQUEST['treeItemPost'] : [];
$requestIdPicaAlloc = cSecurity::toInteger($_GET['idpica_alloc'] ?? '0');
$requestPostStep = $_POST['step'] ?? '';
$requestGetStep = $_GET['step'] ?? '';
$action = $action ?? '';

$aPostTreeItem = [];
if (!is_object($db)) {
    $db = cRegistry::getDb();
}

if (isset($requestTreeItem['idpica_alloc'])) {
  $aPostTreeItem['idpica_alloc'] = cSecurity::toInteger($requestTreeItem['idpica_alloc']);
}

if (isset($requestTreeItem['parentid'])) {
  $aPostTreeItem['parentid'] = cSecurity::toInteger($requestTreeItem['parentid']);
}

if (isset($requestTreeItem['name'])) {
  $sName = stripslashes($requestTreeItem['name']);
  $sName = $db->escape($sName);
  $aPostTreeItem['name'] = $sName;
}

$oPage = new cGuiPage("content_allocation_edit", "content_allocation");
$oTree = new pApiContentAllocationTreeView('f7771624-4874-4745-8b7e-21a49a71a447');

// store item
if ($requestPostStep == 'store') {
    $oTree->storeItem($aPostTreeItem);
    $oPage->displayOk(sprintf(i18n("New Category %s successfully stored!", 'content_allocation'), $requestTreeItem['name']));
}
// rename item
if ($requestPostStep == 'storeRename') {
    $oTree->storeItem($aPostTreeItem);
    $oPage->displayOk(sprintf(i18n("Category %s successfully renamed!", 'content_allocation'), $requestTreeItem['name']));
}
// rename item
if ($requestGetStep == 'moveup') {
    $oTree->itemMoveUp($requestIdPicaAlloc);
}

if ($requestGetStep == 'deleteItem') { // delete item
    $oPage->displayOk(i18n("Category successfully deleted!", 'content_allocation'));
    $oTree->deleteItem($requestIdPicaAlloc);
}
if ($requestGetStep == 'collapse') {
    $oTree->setTreeStatus($requestIdPicaAlloc);
}
if ($requestGetStep == 'online') {
    $oTree->setOnline($requestIdPicaAlloc);
}
if ($requestGetStep == 'offline') {
    $oTree->setOffline($requestIdPicaAlloc);
}
if ($requestGetStep == 'expanded') {
	$oTree->setTreeStatus($requestIdPicaAlloc);
}

$oDiv = new cHTMLDiv();
$oDiv->setClass('mgb10');
$sTemp = '';

if ($requestGetStep == 'createRoot') { // create new root item
    $form = piContentAllocationBuildContentAllocationForm(
        $requestGetStep, 'store', $action, $frame, $sess->id, $area, 'treeItemPost[parentid]', 'root', ''
    );
    $oDiv->setClass('mgb10 pd5 border_b3');
    $oDiv->updateAttributes(['style' => 'width: 400px;']);
    $oDiv->setContent($form);
} else {
    $oDiv->setContent('<a class="con_func_button addfunction" href="main.php?action=' . $action . '&step=createRoot&frame=' . $frame . '&area=' . $area . '&contenido=' . $sess->id . '">' . i18n("Create new tree", 'content_allocation') . '</a>');
}

$treeDiv = new cHTMLDiv();
$result = $oTree->renderTree(true);

if ($result === false) {
    $result = '&nbsp;';
}
$treeDiv->setContent($result);

$js = '
<script type="text/javascript">
// Function for deleting categories
function piContentAllocationDeleteCategory(idpica_alloc) {
    var url = "main.php?area=' . $area . '&action=' . $action . '&step=deleteItem&idpica_alloc=" + idpica_alloc + "&frame=' . $frame . '&contenido=' . $sess->id . '";
    window.location.href = url;
}
</script>';

$oPage->addScript($js);

$oPage->setContent([$oDiv, $treeDiv]);
$oPage->render();
