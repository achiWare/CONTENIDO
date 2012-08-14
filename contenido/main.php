<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * CONTENIDO main file
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package    CONTENIDO Backend
 * @version    1.0.7
 * @author     Olaf Niemann, Jan Lengowski
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since CONTENIDO release <= 4.6
 *
 * {@internal
 *   created  2003-01-20
 *   $Id$:
 * }}
 */

if (!defined('CON_FRAMEWORK')) {
    define('CON_FRAMEWORK', true);
}

// CONTENIDO startup process
include_once('./includes/startup.php');

$cfg['debug']['backend_exectime']['fullstart'] = getmicrotime();

cInclude('includes', 'functions.api.php');
cInclude('includes', 'functions.forms.php');

cRegistry::bootstrap(array(
    'sess' => 'cSession',
    'auth' => 'Contenido_Challenge_Crypt_Auth',
    'perm' => 'cPermission'
));

i18nInit($cfg['path']['contenido_locale'], $belang);

require_once($cfg['path']['contenido'] . $cfg['path']['includes'] . 'functions.includePluginConf.php');

require_once($cfg['path']['contenido_config'] . 'cfg_actions.inc.php');

if ($cfg['use_pseudocron'] == true) {
    // Include cronjob-Emulator, but only for frame 1
    if ($frame == 1) {
        $sess->freeze();

        $oldpwd = getcwd();

        chdir($cfg['path']['contenido'] . $cfg['path']['cronjobs']);
        cInclude('includes', 'pseudo-cron.inc.php');
        chdir($oldpwd);

        if ($bJobRunned == true) {
            // Some cronjobs might overwrite important system variables.
            // We are thaw'ing the session again to re-register these variables.
            $sess->thaw();
        }
    }
}

// Remove all own marks, only for frame 1 and 4  if $_REQUEST['appendparameters'] == 'filebrowser'
// filebrowser is used in tiny in this case also do not remove session marks
if (($frame == 1 || $frame == 4) && $_REQUEST['appendparameters'] != 'filebrowser') {
    $col = new cApiInUseCollection();
    $col->removeSessionMarks($sess->id);
}

// If the override flag is set, override a specific cApiInUse
if (isset($overrideid) && isset($overridetype)) {
    $col = new cApiInUseCollection();
    $col->removeItemMarks($overridetype, $overrideid);
}

// Create CONTENIDO classes
// FIXME: Correct variable names, instances of classes at objects, not classes!
$db = cRegistry::getDb();
$notification = new cGuiNotification();
$classarea = new cApiAreaCollection();
$classlayout = new cApiLayout();
$classclient = new cApiClientCollection();
/** @deprecated [2012-03-27] Uninitialized global cApiUser instance is no more needed */
$classuser = new cApiUser();

$currentuser = new cApiUser($auth->auth['uid']);

// Change client
if (isset($changeclient) && is_numeric($changeclient)) {
    $client = $changeclient;
    unset($lang);
}

// Change language
if (isset($changelang) && is_numeric($changelang)) {
    unset($area_rights);
    unset($item_rights);
    $lang = $changelang;
}

if (!is_numeric($client) ||
    (!$perm->have_perm_client('client['.$client.']') &&
    !$perm->have_perm_client('admin['.$client.']')))
{
    // use first client which is accessible
    $sess->register('client');
    $oClientColl = new cApiClientCollection();
    if ($oClient = $oClientColl->getFirstAccessibleClient()) {
        unset($lang);
        $client = $oClient->get('idclient');
    }
} else {
    $sess->register('client');
}

if (!is_numeric($lang) || $lang == '') {
    $sess->register('lang');
    // search for the first language of this client
    $sql = "SELECT * FROM ".$cfg['tab']['lang']." AS A, ".$cfg['tab']['clients_lang']." AS B WHERE A.idlang=B.idlang AND idclient=".cSecurity::toInteger($client)." ORDER BY A.idlang ASC";
    $db->query($sql);
    $db->next_record();
    $lang = $db->f('idlang');
} else {
    $sess->register('lang');
}

// send right encoding http header
sendEncodingHeader($db, $cfg, $lang);

$perm->load_permissions();

// Create CONTENIDO classes
$tpl = new cTemplate();
$backend = new cBackend();

// Register session variables
$sess->register('sess_area');

if (isset($area)) {
    $sess_area = $area;
} else {
    $area = (isset($sess_area) && $sess_area != '') ? $sess_area : 'login';
}

$sess->register('cfgClient');
$sess->register('errsite_idcat');
$sess->register('errsite_idart');

if ($cfgClient['set'] != 'set') {
     rereadClients();
}

// Initialize CONTENIDO_Backend.
// Load all actions from the DB and check if permission is granted.
$oldmemusage = memory_get_usage();

// Select frameset
$backend->setFrame($frame);

// Select area
$backend->select($area);

$cfg['debug']['backend_exectime']['start'] = getmicrotime();

// Include all required 'include' files. Can be an array of files, if more than
// one file is required.
if (is_array($backend->getFile('inc'))) {
    foreach ($backend->getFile('inc') as $filename) {
        include_once($cfg['path']['contenido'].$filename);
    }
}

// If $action is set -> User klicked some button/link
// get the appopriate code for this action and evaluate it.
if (isset($action) && $action != '') {
    if (!isset($idart)) {
        $idart = 0;
    }
    $backend->log($idcat, $idart, $client, $lang, $action);
}


if (isset($action)) {
    $actionCodeFile = $cfg['path']['contenido'] . 'includes/type/action/include.' . $action . '.action.php';
    if (cFileHandler::exists($actionCodeFile)) {
        cDebug::out('Including action file for ' . $action);
        include_once $actionCodeFile;
    } else {
        cDebug::out('No action file found for ' . $action);
    }
}

// Include the 'main' file for the selected area. Usually there is only one main file
$sFilename = "";
if (is_array($backend->getFile('main'))) {
    foreach ($backend->getFile('main') as $id => $filename) {
        $sFilename = $filename;
        include_once($cfg['path']['contenido'].$filename);
    }
} elseif ($frame == 3) {
    include_once($cfg['path']['contenido'] . $cfg['path']['includes'] . 'include.default_subnav.php');
    $sFilename = "include.default_subnav.php";
} else {
    include_once($cfg['path']['contenido'] . $cfg['path']['includes'] . 'include.blank.php');
    $sFilename = "include.blank.php";
}

$cfg['debug']['backend_exectime']['end'] = getmicrotime();

$debugInfo = array(
    'Building this page (excluding CONTENIDO includes) took: ' . ($cfg['debug']['backend_exectime']['end'] - $cfg['debug']['backend_exectime']['start']).' seconds',
    'Building the complete page took: ' . ($cfg['debug']['backend_exectime']['end'] - $cfg['debug']['backend_exectime']['fullstart']).' seconds',
    'Include memory usage: '.humanReadableSize(memory_get_usage()-$oldmemusage),
    'Complete memory usage: '.humanReadableSize(memory_get_usage()),
    "*****".$sFilename."*****"
);
cDebug::out(implode("\n", $debugInfo));

// Do user tracking (who is online)
$oActiveUser = new cApiOnlineUserCollection();
$oActiveUser->startUsersTracking();

cRegistry::shutdown();

?>