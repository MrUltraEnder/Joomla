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

namespace RegularLabs\Plugin\System\AdvancedTemplates;

defined('_JEXEC') or die;

use RegularLabs\Library\Parameters as RL_Parameters;

class Params
{
	protected static $params = null;

	public static function get()
	{
		if ( ! is_null(self::$params))
		{
			return self::$params;
		}

		$params = RL_Parameters::getInstance()->getComponentParams('advancedtemplates');

		self::$params = $params;

		return self::$params;
	}
}
