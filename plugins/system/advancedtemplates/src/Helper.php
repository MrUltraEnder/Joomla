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

use RegularLabs\Library\Document as RL_Document;

class Helper
{
	public function onContentPrepareForm($form, $data)
	{
		if ( ! RL_Document::isAdmin())
		{
			return true;
		}

		return Document::changeMenuItemForm($form);
	}

	public function onAfterRoute()
	{
		if ( ! RL_Document::isClient('site'))
		{
			return;
		}

		Document::setTemplate();
	}

	public function onAfterRender()
	{

		if ( ! RL_Document::isAdmin())
		{
			return;
		}

		Document::replaceLinks();
	}
}
