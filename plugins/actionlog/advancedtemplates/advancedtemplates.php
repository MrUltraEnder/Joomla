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
use Joomla\CMS\Language\Text as JText;
use RegularLabs\Library\ArrayHelper as RL_Array;
use RegularLabs\Library\Log as RL_Log;

if ( ! is_file(JPATH_LIBRARIES . '/regularlabs/autoload.php'))
{
	return;
}

require_once JPATH_LIBRARIES . '/regularlabs/autoload.php';

/**
 * Plugin that logs User Actions
 */
class PlgActionlogAdvancedTemplates
	extends \RegularLabs\Library\ActionLogPlugin
{
	public $name  = 'ADVANCED_TEMPLATE_MANAGER';
	public $alias = 'advancedtemplates';

	public function __construct(&$subject, array $config = [])
	{
		parent::__construct($subject, $config);

		$this->items = [
			'style'    => (object) [
				'title' => 'PLG_ACTIONLOG_JOOMLA_TYPE_STYLE',
			],
			'template' => (object) [
				'title' => 'PLG_ACTIONLOG_JOOMLA_TYPE_TEMPLATE',
			],
		];
	}

	public function onAfterTemplateStyleSetHome($context, $id)
	{
		if (strpos($context, $this->option) === false)
		{
			return;
		}

		if ( ! RL_Array::find(['*', 'change_default'], $this->events))
		{
			return;
		}

		$style = $this->getStyleById($id);

		if ( ! $style)
		{
			return;
		}

		$languageKey = 'ATP_ACTIONLOGS_STYLE_SET_HOME';

		$message = [
			'style_name' => $style->title,
			'style_link' => 'index.php?option=com_advancedtemplates&view=style&layout=edit&id=' . $id,
		];

		RL_Log::add($message, $languageKey, $context);
	}

	public function onAfterTemplateCopy($context, $from_name, $to_name)
	{
		if (strpos($context, $this->option) === false)
		{
			return;
		}

		if ( ! RL_Array::find(['*', 'template_copy'], $this->events))
		{
			return;
		}

		$languageKey = 'ATP_ACTIONLOGS_TEMPLATE_COPY';

		$message = [
			'from_name' => $from_name,
			'to_name'   => $to_name,
		];

		RL_Log::add($message, $languageKey, $context);
	}

	public function onAfterTemplateFileCreate($context, $template, $file_name)
	{
		if (strpos($context, $this->option) === false)
		{
			return;
		}

		if ( ! RL_Array::find(['*', 'files'], $this->events))
		{
			return;
		}

		$languageKey = 'ATP_ACTIONLOGS_FILE_ADDED';

		$filelink = 'index.php?option=com_advancedtemplates&view=template&id=' . $template->extension_id
			. '&file=' . base64_encode($file_name);

		$message = [
			'type'     => JText::_('ATP_TEMPLATE_FILE'),
			'template' => $template->name,
			'file'     => ltrim($file_name, '/'),
			'filelink' => $filelink,
		];

		RL_Log::add($message, $languageKey, $context);
	}

	public function onAfterTemplateFileUpdate($context, $template, $file_name)
	{
		if (strpos($context, $this->option) === false)
		{
			return;
		}

		if ( ! RL_Array::find(['*', 'files'], $this->events))
		{
			return;
		}

		$languageKey = 'ATP_ACTIONLOGS_FILE_UPDATED';

		$filelink = 'index.php?option=com_advancedtemplates&view=template&id=' . $template->extension_id
			. '&file=' . base64_encode($file_name);

		$message = [
			'type'     => JText::_('ATP_TEMPLATE_FILE'),
			'template' => $template->name,
			'file'     => ltrim($file_name, '/'),
			'filelink' => $filelink,
		];

		RL_Log::add($message, $languageKey, $context);
	}

	public function onAfterTemplateFileDelete($context, $template, $file_name)
	{
		if (strpos($context, $this->option) === false)
		{
			return;
		}

		if ( ! RL_Array::find(['*', 'files'], $this->events))
		{
			return;
		}

		$languageKey = 'ATP_ACTIONLOGS_FILE_DELETED';

		$message = [
			'type'     => JText::_('ATP_TEMPLATE_FILE'),
			'template' => $template->name,
			'file'     => ltrim($file_name, '/'),
		];

		RL_Log::add($message, $languageKey, $context);
	}

	private function getStyleById($id)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->quoteName('#__template_styles'))
			->where($db->quoteName('id') . ' = ' . (int) $id);
		$db->setQuery($query);

		return $db->loadObject();
	}

}
