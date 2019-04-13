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

require_once __DIR__ . '/script.install.helper.php';

class PlgActionlogAdvancedTemplatesInstallerScript extends PlgActionlogAdvancedTemplatesInstallerScriptHelper
{
	public $name           = 'ADVANCED_TEMPLATE_MANAGER';
	public $alias          = 'advancedtemplates';
	public $extension_type = 'plugin';
	public $plugin_folder  = 'actionlog';

	public function uninstall($adapter)
	{
		$this->uninstallComponent($this->extname);
		$this->uninstallPlugin($this->extname, 'system');
	}
}
