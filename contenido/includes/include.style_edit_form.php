<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Edit file
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package    CONTENIDO Backend Includes
 * @version    1.0.2
 * @author     Olaf Niemann, Willi Mann
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since CONTENIDO release <= 4.6
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}


cInclude('external', 'codemirror/class.codemirror.php');

$sFileType = 'css';

$sFilename = '';
$page = new cGuiPage("style_edit_form");
$page->setEncoding('utf-8');

$tpl->reset();

if (!$perm->have_perm_area_action($area, $action)) {
    $page->displayCriticalError(i18n('Permission denied'));
    $page->render();
    return;
}

if (!(int) $client > 0) {
    // If there is no client selected, display empty page
    $page->render();
    return;
}

if ($action == 'style_delete') {
    $path = $cfgClient[$client]['css']['path'];
    // Delete file
    // TODO also delete the versioning files
    if (!strrchr($_REQUEST['delfile'], '/')) {
        if (cFileHandler::exists($path . $_REQUEST['delfile'])) {
            unlink($path . $_REQUEST['delfile']);
            removeFileInformation($client, $_REQUEST['delfile'], 'css', $db);
            $page->displayInfo(i18n('Deleted CSS file successfully!'));
        }
    }

    $page->setReload();
    $page->render();
} else {
    $path = $cfgClient[$client]['css']['path'];
    if (stripslashes($_REQUEST['file'])) {
        $sReloadScript = "<script type=\"text/javascript\">
                             var left_bottom = parent.parent.frames['left'].frames['left_bottom'];
                             if (left_bottom) {
                                 var href = left_bottom.location.href;
                                 href = href.replace(/&file.*/, '');
                                 left_bottom.location.href = href+'&file='+'".$_REQUEST['file']."';
                             }
                         </script>";
    } else {
        $sReloadScript = '';
    }

    $sTempFilename = stripslashes($_REQUEST['tmp_file']);
    $sOrigFileName = $sTempFilename;

    if (getFileType($_REQUEST['file']) != $sFileType && strlen(stripslashes(trim($_REQUEST['file']))) > 0) {
        $sFilename .= stripslashes($_REQUEST['file']) . '.' . $sFileType;
    } else {
        $sFilename .= stripslashes($_REQUEST['file']);
    }

    // Content Type is css
    $sTypeContent = 'css';
    $aFileInfo = getFileInformation($client, $sTempFilename, $sTypeContent, $db);

    // Create new file
    if ($_REQUEST['action'] == 'style_create' && $_REQUEST['status'] == 'send') {
        $sTempFilename = $sFilename;
        // check filename and create new file
        cFileHandler::validateFilename($sFilename);
        cFileHandler::create($path . $sFilename, $_REQUEST['code']);
        $bEdit = cFileHandler::read($path . $sFilename);
        updateFileInformation($client, $sFilename, 'css', $auth->auth['uid'], $_REQUEST['description'], $db);
        $sReloadScript .= "<script type=\"text/javascript\">
                     var right_top = top.content.right.right_top;
                     if (right_top) {
                         var href = '".$sess->url("main.php?area=$area&frame=3&file=$sTempFilename")."';
                         right_top.location.href = href;
                     }
                     </script>";
        if ($bEdit) {
            $page->displayInfo(i18n('Created new CSS file successfully!'));
        }
    }

    // Edit selected file
    if ($_REQUEST['action'] == 'style_edit' && $_REQUEST['status'] == 'send') {
        $tempTemplate = $sTempFilename;
        if ($sFilename != $sTempFilename) {
            cFileHandler::validateFilename($sFilename);
            if (cFileHandler::rename($path . $sTempFilename, $sFilename)) {
                $sTempFilename = $sFilename;
            } else {
                $notification->displayNotification("error", sprintf(i18n("Can not rename file %s"), $path . $sTempFilename));
                exit;
            }
            $sReloadScript .= "<script type=\"text/javascript\">
                     var right_top = top.content.right.right_top;
                     if (right_top) {
                         var href = '".$sess->url("main.php?area=$area&frame=3&file=$sTempFilename")."';
                         right_top.location.href = href;
                     }
                     </script>";
        } else {
            $sTempFilename = $sFilename;
        }

        cFileHandler::validateFilename($sFilename);
        cFileHandler::write($path . $sFilename, $_REQUEST['code']);
        $bEdit = cFileHandler::read($path . $sFilename);

        // Show message
        if ($sFilename != $tempTemplate && $bEdit) {
            $page->displayInfo(i18n('Renamed CSS file successfully!'));
        } elseif ($bEdit) {
            $page->displayInfo(i18n('Saved changes successfully!'));
        } else {
            $page->displayError(i18n("Can't save file!"));
        }
        updateFileInformation($client, $sOrigFileName, 'css', $auth->auth['uid'], $_REQUEST['description'], $db, $sFilename);

        // Track version
        // For read Fileinformation an get the id of current File
        cInclude('includes', 'functions.file.php');

        if ((count($aFileInfo) == 0) || ((int)$aFileInfo['idsfi'] == 0)) {
            $aFileInfo = getFileInformation($client, $sTempFilename, $sTypeContent, $db);
            $aFileInfo['description'] = '';
        }

        if ((count($aFileInfo) == 0) || ($aFileInfo['idsfi'] != '')) {
            $oVersion = new cVersionFile($aFileInfo['idsfi'], $aFileInfo, $sFilename, $sTypeContent, $cfg, $cfgClient, $db, $client, $area, $frame, $sOrigFileName);
            // Create new version
            $oVersion->createNewVersion();
        }
    }

    // Generate edit form
    if (isset($_REQUEST['action'])) {
        $sAction = ($bEdit) ? 'style_edit' : $_REQUEST['action'];

        if ($_REQUEST['action'] == 'style_edit') {
            $sCode = cFileHandler::read($path . $sFilename);
        } else {
            $sCode = stripslashes($_REQUEST['code']); // stripslashes is required here in case of creating a new file
        }

        $aFileInfo = getFileInformation($client, $sTempFilename, 'css', $db);

        $form = new cGuiTableForm('file_editor');
        $form->addHeader(i18n('Edit file'));
        $form->setVar('area', $area);
        $form->setVar('action', $sAction);
        $form->setVar('frame', $frame);
        $form->setVar('status', 'send');
        $form->setVar('tmp_file', $sTempFilename);

        $tb_name = new cHTMLTextbox('file', $sFilename, 60);
        $ta_code = new cHTMLTextarea('code', htmlspecialchars($sCode), 100, 35, 'code');
        $descr = new cHTMLTextarea('description', htmlspecialchars($aFileInfo['description']), 100, 5);


        $ta_code->setStyle('font-family:monospace;width:100%;');
        $descr->setStyle('font-family:monospace;width:100%;');
        $ta_code->updateAttributes(array('wrap' => getEffectiveSetting('style_editor', 'wrap', 'off')));

        $form->add(i18n('Name'), $tb_name);
        $form->add(i18n('Description'), $descr->render());
        $form->add(i18n('Code'), $ta_code);

        $page->setContent(array($form));

        $oCodeMirror = new CodeMirror('code', 'css', substr(strtolower($belang), 0, 2), true, $cfg);
        $page->addScript($oCodeMirror->renderScript());

        if (!empty($sReloadScript)) {
            $page->addScript($sReloadScript);
        }
        $page->render();
    }
}
