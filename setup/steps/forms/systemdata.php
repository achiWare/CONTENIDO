<?php

/**
 * This file contains the system data setup mask.
 *
 * @package    Setup
 * @subpackage Form
 * @author     Unknown
 * @copyright  four for business AG <www.4fb.de>
 * @license    https://www.contenido.org/license/LIZENZ.txt
 * @link       https://www.4fb.de
 * @link       https://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * System data setup mask.
 *
 * @package    Setup
 * @subpackage Form
 */
class cSetupSystemData extends cSetupMask {

    /**
     * cSetupSystemData constructor.
     * @param string $step
     * @param bool $previous
     * @param $next
     */
    public function __construct($step, $previous, $next) {
        $cfg = cRegistry::getConfig();

        cSetupMask::__construct('templates/setup/forms/systemdata.tpl', $step);

        cArray::initializeKey($_SESSION, 'dbprefix', '');
        cArray::initializeKey($_SESSION, 'dbhost', '');
        cArray::initializeKey($_SESSION, 'dbuser', '');
        cArray::initializeKey($_SESSION, 'dbname', '');
        cArray::initializeKey($_SESSION, 'dbpass', '');
        cArray::initializeKey($_SESSION, 'dbengine', '');
        cArray::initializeKey($_SESSION, 'dbcharset', '');
        cArray::initializeKey($_SESSION, 'dbcollation', '');
        cArray::initializeKey($_SESSION, 'dboptions', []);

        $this->takeoverExistingDbSettingsToSession();

        $this->setHeader(i18n("Database Parameters", "setup"));
        $this->_stepTemplateClass->set('s', 'TITLE', i18n("Database Parameters", "setup"));

        switch ($_SESSION['setuptype']) {
            case 'setup':
                $this->_stepTemplateClass->set('s', 'DESCRIPTION', i18n("Please enter the required database information. If you are unsure about the data, ask your provider or administrator.", "setup") . " " . i18n("If the database does not exist and your database user has the sufficient permissions, setup will create the database automatically.", "setup"));
                break;
            case 'upgrade':
                $this->_stepTemplateClass->set('s', 'DESCRIPTION', i18n("Please enter the required database information. If the database data of your previous installation could have been read, the data will be inserted automatically. If you are unsure about the data, please ask your provider or administrator.", "setup"));
                break;
        }

        if ($_SESSION['dbprefix'] == '') {
            $_SESSION['dbprefix'] = CON_DB_PREFIX;
        }

        if ($_SESSION['dbengine'] == '' && $_SESSION['setuptype'] == 'setup') {
            $_SESSION['dbengine'] = CON_DB_ENGINE;
        }

        if ($_SESSION['dbcharset'] == '' && $_SESSION['setuptype'] == 'setup') {
            $_SESSION['dbcharset'] = CON_DB_CHARSET;
        }

        if ($_SESSION['dbcollation'] == '' && $_SESSION['setuptype'] == 'setup') {
            $_SESSION['dbcollation'] = CON_DB_COLLATION;
        }

        unset($_SESSION['install_failedchunks']);
        unset($_SESSION['install_failedupgradetable']);
        unset($_SESSION['configsavefailed']);
        unset($_SESSION['htmlpath']);
        unset($_SESSION['frontendpath']);

        $dbhost = new cHTMLTextbox('dbhost', $_SESSION['dbhost'], 30, 255);
        $dbname = new cHTMLTextbox('dbname', $_SESSION['dbname'], 30, 255);
        $dbuser = new cHTMLTextbox('dbuser', $_SESSION['dbuser'], 30, 255);

        if ($_SESSION['dbpass'] != '') {
            $mpass = str_repeat('*', cString::getStringLength($_SESSION['dbpass']));
        } else {
            $mpass = '';
        }

        $dbpass = new cHTMLPasswordbox('dbpass', $mpass, 30, 255);
        $dbpass->attachEventDefinition('onchange handler', 'onchange', "document.setupform.dbpass_changed.value = 'true';");
        $dbpass->attachEventDefinition('onchange handler', 'onkeypress', "document.setupform.dbpass_changed.value = 'true';");

        $dbpass_hidden = new cHTMLHiddenField('dbpass_changed', 'false');

        $dbprefix = new cHTMLTextbox('dbprefix', $_SESSION['dbprefix'], 10, 30);
        $dbcharset = new cHTMLSelectElement('dbcharset');
        $dbEngine = new cHTMLSelectElement('dbengine');
        $dbcollation = new cHTMLSelectElement('collationSelect', '1', 'collationSelect');
        $dbcollation->setAttribute("onchange", "comboBox('collationSelect', 'collationText')");

        // Compose charset and collation select box, only if CON_UTF8 flag is not set
        if (!cFileHandler::exists($cfg['path']['contenido_config'] . 'config.php') || (defined('CON_UTF8') && CON_UTF8 === true)) {
            // database charset
            $hiddenFieldDbCharset = new cHTMLHiddenField('dbcharset', CON_DB_CHARSET);
            $dbcharsetTextbox = $hiddenFieldDbCharset . CON_DB_CHARSET;

            // database storage engine
            $hiddenFieldDbEngine = new cHTMLHiddenField('dbengine', CON_DB_ENGINE);
            $dbEngineTextbox = $hiddenFieldDbEngine . CON_DB_ENGINE;

            // database collation
            $hiddenFieldDbCollation = new cHTMLHiddenField('dbcollation', CON_DB_COLLATION);
            $dbCollationTextbox = $hiddenFieldDbCollation . CON_DB_COLLATION;
        } else {
            // database charset
            $pos = 0;
            $option = new cHTMLOptionElement('-- ' . i18n("No character set", "setup") . ' --', '');
            $dbcharset->addOptionElement(++$pos, $option);
            $selectedCharset = !empty($_SESSION['dbcharset']) ? $_SESSION['dbcharset'] : '';
            $aCharsets = fetchMySQLCharsets();
            foreach ($aCharsets as $p => $charset) {
                $selected = ($selectedCharset == $charset);
                $option = new cHTMLOptionElement($charset, $charset, $selected);
                $dbcharset->addOptionElement(++$pos, $option);
            }
            $dbcharsetTextbox = $dbcharset->render();

            // database storage engine
            $hiddenFieldDbEngine = new cHTMLHiddenField('dbengine', CON_DB_ENGINE);
            $dbEngineTextbox = $hiddenFieldDbEngine . CON_DB_ENGINE;

            // database collation
            $pos = 0;
            $noOp = new cHTMLOptionElement('-- ' . i18n("Other", "setup") . ' --', '');
            $dbcollation->addOptionElement(++$pos, $noOp);
            $selectedCollation = !empty($_SESSION['dbcollation']) ? $_SESSION['dbcollation'] : CON_DB_COLLATION;
            $collations = fetchMySQLCollations();
            foreach ($collations as $p => $collation) {
                $selected = ($selectedCollation == $collation);
                $option = new cHTMLOptionElement($collation, $collation, $selected);
                $dbcollation->addOptionElement(++$pos, $option);
            }
            $dbCollationTextbox = new cHTMLTextbox('dbcollation', $selectedCollation, '', '', 'collationText'). $dbcollation->render();
        }

        if (isset($_SESSION['dboptions'][MYSQLI_INIT_COMMAND])) {
            $dbOptionInitCommand = new cHTMLTextbox('dboptions[' . MYSQLI_INIT_COMMAND . ']', $_SESSION['dboptions'][MYSQLI_INIT_COMMAND], 20, 256);
        } else {
            $dbOptionInitCommand = new cHTMLTextbox('dboptions[' . MYSQLI_INIT_COMMAND . ']', CON_SETUP_DB_OPTION_MYSQLI_INIT_COMMAND, 20, 256);
        }

        $this->_stepTemplateClass->set('s', 'LABEL_DBHOST', i18n("Database Server (IP or name)", "setup"));

        if ($_SESSION['setuptype'] == 'setup') {
            $this->_stepTemplateClass->set('s', 'LABEL_DBNAME', i18n("Database Name", "setup") . '<br>' . i18n("(use empty or non-existent database)", "setup"));
        } else {
            $this->_stepTemplateClass->set('s', 'LABEL_DBNAME', i18n("Database Name", "setup"));
            $dbcharset->setDisabled(true);
        }

        $this->_stepTemplateClass->set('s', 'LABEL_DBUSERNAME', i18n("Database Username", "setup"));
        $this->_stepTemplateClass->set('s', 'LABEL_DBPASSWORD', i18n("Database Password", "setup"));
        $this->_stepTemplateClass->set('s', 'LABEL_DBPREFIX', i18n("Table Prefix", "setup"));
        $this->_stepTemplateClass->set('s', 'LABEL_DBADVANCED', i18n("Advanced Settings", "setup"));
        $this->_stepTemplateClass->set('s', 'LABEL_DBCHARSET', i18n("Database character set", "setup"));
        $this->_stepTemplateClass->set('s', 'LABEL_DBENGINE', i18n("Database storage engine", "setup"));
        $this->_stepTemplateClass->set('s', 'LABEL_DBCOLLATION', i18n("Database collation", "setup"));
        $this->_stepTemplateClass->set('s', 'LABEL_DBOPTION_INIT_COMMAND', i18n("Database option MYSQLI_INIT_COMMAND", "setup"));

        $this->_stepTemplateClass->set('s', 'INPUT_DBHOST', $dbhost->render());
        $this->_stepTemplateClass->set('s', 'INPUT_DBNAME', $dbname->render());
        $this->_stepTemplateClass->set('s', 'INPUT_DBUSERNAME', $dbuser->render());
        $this->_stepTemplateClass->set('s', 'INPUT_DBPASSWORD', $dbpass->render() . $dbpass_hidden->render());
        $this->_stepTemplateClass->set('s', 'INPUT_DBPREFIX', $dbprefix->render());
        $this->_stepTemplateClass->set('s', 'INPUT_DBCHARSET', $dbcharsetTextbox);
        $this->_stepTemplateClass->set('s', 'INPUT_DBENGINE', $dbEngineTextbox);
        $this->_stepTemplateClass->set('s', 'INPUT_DBCOLLATION', $dbCollationTextbox);
        $this->_stepTemplateClass->set('s', 'INPUT_DBOPTION_INIT_COMMAND', $dbOptionInitCommand);

        $this->setNavigation($previous, $next);
    }

    protected function _createNavigation() {
        $link = new cHTMLLink('#');

        if ($_SESSION['setuptype'] == 'setup') {
            $checkScript = sprintf(
                "var msg = ''; if (document.setupform.dbhost.value == '') { msg += '%s '; } if (document.setupform.dbname.value == '') { msg += '%s '; } if (document.setupform.dbuser.value == '') { msg += '%s '; } if (document.setupform.dbhost.value != '' && document.setupform.dbname.value != '' && document.setupform.dbuser.value != '') { document.setupform.submit(); } else { alert(msg); }", i18n("You need to enter a database host."), i18n("You need to enter a database name."), i18n("You need to enter a database user.")
            );
            $link->attachEventDefinition('pageAttach', 'onclick', "document.setupform.step.value = '" . $this->_nextstep . "';");
            $link->attachEventDefinition('submitAttach', 'onclick', $checkScript);
        } else {
            $link->attachEventDefinition('pageAttach', 'onclick', "document.setupform.step.value = '" . $this->_nextstep . "'; document.setupform.submit();");
        }
        $link->setClass("nav");
        $link->setContent("<span>&raquo;</span>");

        $this->_stepTemplateClass->set('s', 'NEXT', $link->render());

        $backlink = new cHTMLLink('#');
        $backlink->attachEventDefinition('pageAttach', 'onclick', "document.setupform.step.value = '" . $this->_backstep . "';");
        $backlink->attachEventDefinition('submitAttach', 'onclick', 'document.setupform.submit();');
        $backlink->setClass("nav navBack");
        $backlink->setContent("<span>&raquo;</span>");
        $this->_stepTemplateClass->set('s', 'BACK', $backlink->render());
    }

    protected function takeoverExistingDbSettingsToSession() {
        $cfg = cRegistry::getConfig();

        if (cFileHandler::exists($cfg['path']['contenido_config'] . 'config.php')) {
            $cfgBackup = $cfg;

            @include($cfg['path']['contenido_config'] . 'config.php');
            if (cFileHandler::exists($cfg['path']['contenido_config'] . 'config.local.php')) {
                @include($cfg['path']['contenido_config'] . 'config.local.php');
            }

            $aVars = [
                'dbhost' => $cfg['db']['connection']['host'],
                'dbuser' => $cfg['db']['connection']['user'],
                'dbname' => $cfg['db']['connection']['database'],
                'dbpass' => $cfg['db']['connection']['password'],
                'dbprefix' => $cfg['sql']['sqlprefix'],
                'dbcharset' => $cfg['db']['connection']['charset'],
                'dbengine' => $cfg['db']['engine'] ?? CON_DB_ENGINE,
                'dbcollation' => $cfg['db']['collation'] ?? CON_DB_COLLATION,
                'dboptions' => !empty($cfg['db']['connection']['options']) ? $cfg['db']['connection']['options'] : []
            ];

            $cfg = $cfgBackup;
            unset($cfgBackup);

            foreach ($aVars as $aVar => $sValue) {
                if ($_SESSION[$aVar] == '') {
                    $_SESSION[$aVar] = $sValue;
                }
            }
        }
    }
}
