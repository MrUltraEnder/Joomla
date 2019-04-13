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

use RegularLabs\Library\Protect as RL_Protect;
use RegularLabs\Plugin\System\AdvancedTemplates\Plugin;

// Do not instantiate plugin on install pages
// to prevent installation/update breaking because of potential breaking changes
$input = \Joomla\CMS\Factory::getApplication()->input;
if ($input->get('action') != '' && in_array($input->get('option'), ['com_installer', 'com_regularlabsmanager']))
{
	return;
}

if ( ! is_file(__DIR__ . '/vendor/autoload.php'))
{
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

class PlgSystemAdvancedTemplates extends Plugin
{
	public $_alias       = 'advancedtemplatemanager';
	public $_title       = 'ADVANCED_TEMPLATE_MANAGER';
	public $_lang_prefix = 'ATP';

	public $_page_types      = ['html'];
	public $_enable_in_admin = true;

	public function extraChecks()
	{
		if ( ! RL_Protect::isComponentInstalled('advancedtemplates'))
		{
			return false;
		}

		return true;
	}

	/*
	 * Below are the events that this plugin uses
	 * All handling is passed along to the parent run method
	 */
	public function onContentPrepareForm()
	{
		$this->run();
	}

	public function onAfterRoute()
	{
		$this->run();
	}

	public function onAfterRender()
	{
		$this->run();
	}
}
