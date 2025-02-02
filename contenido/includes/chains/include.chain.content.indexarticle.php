<?php

/**
 * CONTENIDO Chain.
 * Generate index of article content entries.
 *
 * @package    Core
 * @subpackage Chain
 * @author     Marcus Gnaß <marcus.gnass@4fb.de>
 * @copyright  four for business AG <www.4fb.de>
 * @license    https://www.contenido.org/license/LIZENZ.txt
 * @link       https://www.4fb.de
 * @link       https://www.contenido.org
 */

// assert CONTENIDO framework
defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * Generate index of article content entries.
 *
 * @param array $articleIds
 *         containing keys idclient, idlang, idcat, idcatlang, idart, idartlang
 *
 * @throws cDbException
 * @throws cInvalidArgumentException
 */
function cecIndexArticle(array $articleIds) {

    $cfg = cRegistry::getConfig();
    $db = cRegistry::getDb();

    // Indexing an article depends on the complete content with all content
    // types, i.e it can not by differentiated by specific content types.
    // Therefore one must fetch the complete content array.
    $aContent = conGetContentFromArticle($articleIds['idartlang']);

    // cms types to be excluded from indexing
    $aOptions = $cfg['search_index']['excluded_content_types'];

    // start indexing
    $index = new cSearchIndex($db);
    $index->start($articleIds['idart'], $aContent, 'auto', $aOptions);
}
