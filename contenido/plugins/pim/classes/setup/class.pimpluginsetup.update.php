<?php

/**
 * This file contains abstract class for update plugins
 *
 * @package    Plugin
 * @subpackage PluginManager
 * @author     Frederic Schneider
 * @copyright  four for business AG <www.4fb.de>
 * @license    https://www.contenido.org/license/LIZENZ.txt
 * @link       https://www.4fb.de
 * @link       https://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * Update class for existing plugins, extends PimPluginSetup
 *
 * @package    Plugin
 * @subpackage PluginManager
 * @author     frederic.schneider
 */
class PimPluginSetupUpdate extends PimPluginSetup {

    // Begin of update routine

    /**
     * PimPluginSetupUpdate constructor.
     *
     * @throws cDbException
     * @throws cException
     * @throws cInvalidArgumentException
     */
    public function __construct() {
        parent::__construct();

        // Check same plugin (uuid)
        $this->_checkSamePlugin();

        // Check for update specific sql files
        $this->_updateSql();

        // Delete "old" plugin
        $delete = new PimPluginSetupUninstall();
        $delete->uninstall();

        // Install new plugin
        $new = new PimPluginSetupInstall();
        $new->install();

        // Success message
        parent::info(i18n('The plugin has been successfully updated. To apply the changes please login into backend again.', 'pim'));
    }

    /**
     * Check uuId: You can update only the same plugin
     *
     * @throws cException
     */
    private function _checkSamePlugin() {
        $this->_pimPluginCollection->setWhere('idplugin', parent::_getPluginId());
        $this->_pimPluginCollection->query();
        while ($result = $this->_pimPluginCollection->next()) {
            if (parent::$XmlGeneral->uuid != $result->get('uuid')) {
                parent::error(i18n('You have to update the same plugin', 'pim'));
            }
        }
    }

    /**
     * Check for update specific sql files.
     * If some valid sql file available, PIM does not run uninstall and install sql files.
     *
     * @return bool
     *
     * @throws cDbException
     * @throws cException
     * @throws cInvalidArgumentException
     */
    private function _updateSql() {
        // Build sql filename with installed plugin version and new plugin
        // version, i.e.: "plugin_update_100_to_101.sql" (without dots)
        $tempSqlFilename = "plugin_update_" . str_replace('.', '', $this->_getInstalledPluginVersion()) . "_to_" . str_replace('.', '', parent::$XmlGeneral->version) . ".sql";

        // Filename to update sql file
        $tempSqlFilename = parent::$_PimPluginArchiveExtractor->extractArchiveFileToVariable($tempSqlFilename, 0);

        $pattern = '/^(CREATE TABLE IF NOT EXISTS|INSERT INTO|UPDATE|ALTER TABLE) `?' . parent::PLUGIN_SQL_PREFIX . '([a-zA-Z0-9\-_]+)`?\b/';
        return $this->_processSetupSql($tempSqlFilename, $pattern);
    }

    /**
     * Get installed plugin version.
     *
     * @return string The plugin version or empty string.
     * @throws cException
     */
    private function _getInstalledPluginVersion() {
        $this->_pimPluginCollection->setWhere('idplugin', parent::_getPluginId());
        $this->_pimPluginCollection->query();
        if ($result = $this->_pimPluginCollection->next()) {
            return $result->get('version');
        } else {
            return '';
        }
    }

}
