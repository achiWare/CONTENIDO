<?php

/**
 * This file contains various function for the plugin content allocation.
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
 * @return array
 */
function pica_RegisterCustomTab()
{
    return ["con_contentallocation"];
}

/**
 * @param $sIntName
 *
 * @return array|void
 */
function pica_GetCustomTabProperties($sIntName)
{
    if ($sIntName == "con_contentallocation") {
        return ["con_contentallocation", "con_edit", ""];
    }
}

/**
 * @param array $aActions
 *
 * @return array
 */
function pica_ArticleListActions(array $aActions)
{
    $aTmpActions["con_contentallocation"] = "con_contentallocation";

    return $aTmpActions + $aActions;
}

/**
 * @param int $idcat
 * @param int $idart
 * @param int $idartlang
 * @param string $actionkey
 *
 * @return string
 * @throws cException
 */
function pica_RenderArticleAction($idcat, $idart, $idartlang, $actionkey)
{
    $sess = cRegistry::getSession();

    $anchor = '';
    if ($actionkey == 'con_contentallocation') {
        $label  = i18n('Tagging', 'content_allocation');
        $url    = $sess->url(
            'main.php?area=con_contentallocation&action=con_edit&idart=' . $idart . '&idartlang=' . $idartlang
            . '&idcat=' . $idcat . '&frame=4'
        );
        $image  = '<img src="plugins/content_allocation/images/call_contentallocation.gif" alt="' . $label . '">';
        $anchor = '<a title="' . $label . '" href="' . $url . '">' . $image . '</a>';
    }

    return $anchor;
}

/**
 * Takeover allocations of a copied article.
 * @param array $data ['oldidartlang' => int, 'idartlang' => int, 'idart' => int, 'idlang' => int, 'idtplcfg' => int, 'title' => string]
 * @throws cDbException
 */
function pica_CopyArticleAllocations(array $data)
{
    if (!empty($data['oldidartlang']) && !empty($data['idartlang'])) {
        $oldidartlang = cSecurity::toInteger($data['oldidartlang']);
        $idartlang = cSecurity::toInteger($data['idartlang']);
        $oAlloc = new pApiContentAllocation();
        $allocations = $oAlloc->loadAllocations($oldidartlang);
        if (count($allocations)) {
            $oAlloc->storeAllocations($idartlang, $allocations);
        }
    }
}

/**
 * Delete allocations of a deleted article.
 * @param int $idart Id of deleted article
 * @param int $idartlang Id of deleted article language
 * @throws cDbException
 */
function pica_DeleteArticleAllocations($idart, $idartlang)
{
    $idartlang = cSecurity::toInteger($idartlang);
    if ($idartlang > 0) {
        $oAlloc = new pApiContentAllocation();
        $oAlloc->deleteAllocationsByIdartlang($idartlang);
    }
}
