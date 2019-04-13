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

use AdvancedTemplatesModelStyle;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Form\Form as JForm;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Router\Route as JRoute;
use Joomla\Registry\Registry as JRegistry;
use RegularLabs\Library\Conditions as RL_Conditions;
use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Language as RL_Language;
use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\RegEx as RL_RegEx;

class Document
{
	static $style_params;

	/*
	 * Replace links to com_templates with com_advancedtemplates
	 */
	public static function replaceLinks()
	{
		if ( ! RL_Document::isHtml())
		{
			return;
		}

		RL_Language::load('com_advancedtemplates');

		if (JFactory::getApplication()->input->get('option') == 'com_templates')
		{
			self::replaceLinksInCoreTemplateManager();

			return;
		}

		$body = JFactory::getApplication()->getBody();

		$body = RL_RegEx::replace(
			'((["\'])[^\s"\'%]*\?option=com_)(templates(\2|[^a-z-\"\'].*?\2))',
			'\1advanced\3',
			$body
		);

		$body = str_replace(
			[
				'?option=com_advancedtemplates&force=1',
				'?option=com_advancedtemplates&amp;force=1',
			],
			'?option=com_templates',
			$body
		);

		JFactory::getApplication()->setBody($body);
	}

	public static function replaceLinksInCoreTemplateManager()
	{
		if ( ! Params::get()->show_switch)
		{
			return;
		}

		$body = JFactory::getApplication()->getBody();

		$url = 'index.php?option=com_advancedtemplates';
		if (JFactory::getApplication()->input->get('view') == 'style')
		{
			$url .= '&task=style.edit&id=' . (int) JFactory::getApplication()->input->get('id');
		}

		$link = '<a style="float:right;" href="' . JRoute::_($url) . '">' . JText::_('ATP_SWITCH_TO_ADVANCED_TEMPLATE_MANAGER') . '</a><div style="clear:both;"></div>';
		$body = RL_RegEx::replace('(</div>\s*</form>\s*(<\!--.*?-->\s*)*</div>)', $link . '\1', $body);

		JFactory::getApplication()->setBody($body);
	}

	public static function setTemplate()
	{
		$params = Params::get();

		if (JFactory::getApplication()->input->get('templateStyle'))
		{
			if (isset($params->template_positions_display) && $params->template_positions_display)
			{
				return;
			}
		}

		$active = self::getActiveStyle();

		// return if no active template is found
		if (empty($active))
		{
			return;
		}

		// convert params from json to JRegistry object. setTemplate need that.
		$active->params = new JRegistry($active->params);

		JFactory::getApplication()->setTemplate($active->template, $active->params);
	}

	/**
	 * @return Object  The active style
	 */
	public static function getActiveStyle()
	{
		$styles = self::getStyles();

		$active = null;

		foreach ($styles as $id => &$style)
		{
			if ( ! self::isStyleActive($style, $active))
			{
				continue;
			}

			$active = $style;
			break;
		}

		return $active;
	}

	/**
	 * @return bool  True if the current style should be set as active
	 */
	public static function isStyleActive(&$style, &$active)
	{
		// continue if default language is already set
		if ($active && $style->home)
		{
			return false;
		}

		// check if style is set as language default
		if ($style->home && $style->home == JFactory::getLanguage()->getTag())
		{
			$active = $style;

			return false;
		}

		// check if style is set as main default
		if ($style->home === 1 || $style->home === '1')
		{
			$active = $style;

			return false;
		}

		// continue if style is set as default for a different language
		if ($style->home)
		{
			return false;
		}

		// continue is style assignments don't pass
		if ( ! self::stylePassesAssignments($style))
		{
			return false;
		}

		return true;
	}

	/**
	 * @return Array  An array of template styles with the id as key
	 */
	public static function getStyles()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('s.*')
			->from('#__template_styles as s')
			->where('s.client_id = 0');
		$db->setQuery($query);

		$styles = $db->loadObjectList('id');

		return $styles;
	}

	/**
	 * @param $style
	 *
	 * @return Object  The advanced parameter object
	 */
	public static function getStyleParams($style)
	{
		$params = Params::get();

		$adv_params = self::getAdvancedParams($style);

		if ( ! $params->show_assignto_homepage)
		{
			$adv_params->assignto_homepage = 0;
		}
		if ( ! $params->show_assignto_usergrouplevels)
		{
			$adv_params->assignto_usergrouplevels = 0;
		}
		if ( ! $params->show_assignto_date)
		{
			$adv_params->assignto_date = 0;
		}
		if ( ! $params->show_assignto_languages)
		{
			$adv_params->assignto_languages = 0;
		}
		if ( ! $params->show_assignto_templates)
		{
			$adv_params->assignto_templates = 0;
		}
		if ( ! $params->show_assignto_urls)
		{
			$adv_params->assignto_urls = 0;
		}
		if ( ! $params->show_assignto_devices)
		{
			$adv_params->assignto_devices = 0;
		}
		if ( ! $params->show_assignto_os)
		{
			$adv_params->assignto_os = 0;
		}
		if ( ! $params->show_assignto_browsers)
		{
			$adv_params->assignto_browsers = 0;
		}
		if ( ! $params->show_assignto_components)
		{
			$adv_params->assignto_components = 0;
		}
		if ( ! $params->show_assignto_tags)
		{
			$adv_params->show_assignto_tags = 0;
		}
		if ( ! $params->show_assignto_content)
		{
			$adv_params->assignto_contentpagetypes = 0;
			$adv_params->assignto_cats             = 0;
			$adv_params->assignto_articles         = 0;
		}

		return $adv_params;
	}

	/**
	 * @param $style
	 *
	 * @return bool
	 */
	public static function stylePassesAssignments(&$style)
	{
		$params      = self::getStyleParams($style);
		$assignments = RL_Conditions::getConditionsFromParams($params);

		if ( ! RL_Conditions::hasConditions($assignments))
		{
			return false;
		}

		return RL_Conditions::pass($assignments, $params->match_method);
	}

	/**
	 * @param $id
	 *
	 * @return object  The advanced params for the template style in a json string
	 */
	public static function getAdvancedParams($style)
	{
		if (isset(self::$style_params[$style->id]))
		{
			return self::$style_params[$style->id];
		}

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.params')
			->from('#__advancedtemplates AS a')
			->where('a.styleid = ' . (int) $style->id);
		$db->setQuery($query);

		$params = $db->loadResult();

		// if no params are found in database, get the default params
		if (empty($params))
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_advancedtemplates/models/style.php';
			$model  = new AdvancedTemplatesModelStyle;
			$params = (object) $model->initAssignments($style->id, $style);
		}

		self::$style_params[$style->id] = RL_Parameters::getInstance()->getParams($params, JPATH_ADMINISTRATOR . '/components/com_advancedtemplates/assignments.xml');

		return self::$style_params[$style->id];
	}

	public static function changeMenuItemForm($form)
	{
		if ( ! ($form instanceof JForm))
		{
			return false;
		}

		// Check we are manipulating a valid form.
		$name = $form->getName();
		if ($name != 'com_menus.item')
		{
			return true;
		}

		$form->removeField('template_style_id');

		return true;
	}
}
