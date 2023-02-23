<?php

/**
 * Backend action file str_makepublic
 *
 * @package          Core
 * @subpackage       Backend
 * @author           Dominik Ziegler
 * @copyright        four for business AG <www.4fb.de>
 * @license          https://www.contenido.org/license/LIZENZ.txt
 * @link             https://www.4fb.de
 * @link             https://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

cInclude('includes', 'functions.str.php');

if ($perm->have_perm_area_action("str", "str_makepublic") || $perm->have_perm_area_action_item("str", "str_makepublic", $idcat)) {
    strMakePublic($idcat, $lang, !$public);
} else {
    $notification->displayNotification("error", i18n("Permission denied"));
}

?>