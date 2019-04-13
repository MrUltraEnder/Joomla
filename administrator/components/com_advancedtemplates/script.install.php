<?php
/**
 * @package         Advanced Template Manager
 * @version         3.7.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;

require_once __DIR__ . '/script.install.helper.php';

class Com_AdvancedTemplatesInstallerScript extends Com_AdvancedTemplatesInstallerScriptHelper
{
	public $name           = 'ADVANCED_TEMPLATE_MANAGER';
	public $alias          = 'advancedtemplatemanager';
	public $extname        = 'advancedtemplates';
	public $extension_type = 'component';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'system');
		$this->uninstallPlugin($this->extname, 'actionlog');
	}

	public function onBeforeInstall($route)
	{
		// Fix incorrectly formed versions because of issues in old packager
		$this->fixFileVersions(
			[
				JPATH_ADMINISTRATOR . '/components/com_advancedtemplates/advancedtemplates.xml',
				JPATH_PLUGINS . '/system/advancedtemplates/advancedtemplates.xml',
			]
		);
	}

	public function onAfterInstall($route)
	{
		$this->createTable();
		$this->removeAdminMenu();
		$this->removeFrontendComponentFromDB();
		$this->deleteOldFiles();
		$this->fixAssetsRules();
	}

	public function createTable()
	{
		// main table
		$query = "CREATE TABLE IF NOT EXISTS `#__advancedtemplates` (
			`styleid` INT(11) UNSIGNED NOT NULL DEFAULT '0',
			`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
			`params` TEXT NOT NULL,
			PRIMARY KEY (`styleid`)
		) DEFAULT CHARSET=utf8;";
		$this->db->setQuery($query);
		$this->db->execute();
	}

	public function removeAdminMenu()
	{
		// hide admin menu
		$query = $this->db->getQuery(true)
			->delete('#__menu')
			->where($this->db->quoteName('path') . ' = ' . $this->db->quote('advancedtemplates'))
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('component'))
			->where($this->db->quoteName('client_id') . ' = 1');
		$this->db->setQuery($query);
		$this->db->execute();
	}

	public function removeFrontendComponentFromDB()
	{
		// remove frontend component from extensions table
		$query = $this->db->getQuery(true)
			->delete('#__extensions')
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote('com_advancedtemplates'))
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('component'))
			->where($this->db->quoteName('client_id') . ' = 0');
		$this->db->setQuery($query);
		$this->db->execute();

		JFactory::getCache()->clean('_system');
	}

	private function deleteOldFiles()
	{
		JFile::delete(
			[
				JPATH_ADMINISTRATOR . '/components/com_advancedtemplates/script.advancedtemplates.php',
			]
		);

		$this->delete(
			[
				JPATH_SITE . '/components/com_advancedtemplates',
			]
		);
	}

	public function fixAssetsRules()
	{
		parent::fixAssetsRules();

		// Remove unused assets entry (uses com_templates)
		$query = $this->db->getQuery(true)
			->delete('#__assets')
			->where('name LIKE ' . $this->db->quote('com_advancedtemplates.style.%'));
		$this->db->setQuery($query);
		$this->db->execute();
	}

}
