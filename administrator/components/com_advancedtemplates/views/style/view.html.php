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

/**
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Form\Form as JForm;
use Joomla\CMS\Helper\ContentHelper as JContentHelper;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\MVC\View\HtmlView as JView;
use Joomla\CMS\Object\CMSObject as JObject;
use RegularLabs\Library\Parameters as RL_Parameters;

/**
 * View to edit a template style.
 */
class AdvancedTemplatesViewStyle extends JView
{
	/**
	 * The JObject (on success, false on failure)
	 *
	 * @var   JObject
	 */
	protected $item;

	/**
	 * The form object
	 *
	 * @var   JForm
	 */
	protected $form;

	/**
	 * The model state
	 *
	 * @var   JObject
	 */
	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->form  = $this->get('Form');
		$this->canDo = JContentHelper::getActions('com_templates');
		$this->getAssignments();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Redirect to core for T3 and Gantry templates
		if (
			isset($this->item->xml->t3)
			|| (isset($this->item->xml->files->filename) && in_array('gantry.config.php', (array) $this->item->xml->files->filename)
			)
		)
		{
			if (isset($this->item->xml->t3))
			{
				$framework = 'T3';
			}
			else
			{
				$framework = 'Gantry';
			}

			JFactory::getApplication()->redirect(
				'index.php?option=com_templates&force=1&view=style&id=' . (int) $this->item->id,
				JText::sprintf(JText::_('ATP_TEMPLATE_INCOMPATIBLE_FRAMEWORK', $framework, $framework)),
				'warning'
			);
		}

		$this->getConfig();
		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Function that gets the config settings
	 *
	 * @return    Object
	 */
	protected function getConfig()
	{
		if ( ! isset($this->config))
		{

			$this->config = RL_Parameters::getInstance()->getComponentParams('advancedtemplates');
		}

		return $this->config;
	}

	/**
	 * Function that gets the config settings
	 *
	 * @return    Object
	 */
	protected function getAssignments()
	{
		if ( ! isset($this->assignments))
		{
			$xmlfile     = JPATH_ADMINISTRATOR . '/components/com_advancedtemplates/assignments.xml';
			$assignments = new JForm('assignments', ['control' => 'advancedparams']);
			$assignments->loadFile($xmlfile, 1, '//config');
			$assignments->bind($this->item->advancedparams);
			$this->assignments = $assignments;
		}

		return $this->assignments;
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);
		$canDo = $this->canDo;

		if ($this->config->heading_title)
		{
			JToolbarHelper::title(
				$isNew ? JText::_('COM_TEMPLATES_MANAGER_ADD_STYLE')
					: JText::_('COM_TEMPLATES_MANAGER_EDIT_STYLE'), 'eye thememanager'
			);
		}
		else
		{
			$title = $this->item->title . ' [' . $this->item->template . ']';
			JToolbarHelper::title(JText::sprintf('ATP_HEADING_TEMPLATE_STYLE', $title), 'advancedtemplatemanager icon-reglab');
		}

		// If not checked out, can save the item.
		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::apply('style.apply');
			JToolbarHelper::save('style.save');
		}

		// If an existing item, can save to a copy.
		if ( ! $isNew && $canDo->get('core.create'))
		{
			JToolbarHelper::save2copy('style.save2copy');
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('style.cancel');
		}
		else
		{
			JToolbarHelper::cancel('style.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();

		// Get the help information for the template item.
		$lang = JFactory::getLanguage();
		$help = $this->get('Help');

		if ($lang->hasKey($help->url))
		{
			$debug = $lang->setDebug(false);
			$url   = JText::_($help->url);
			$lang->setDebug($debug);
		}
		else
		{
			$url = null;
		}

		JToolbarHelper::help($help->key, false, $url);

		if ($canDo->get('core.admin'))
		{
			$url  = 'index.php?option=com_config&amp;view=component&amp;component=com_advancedtemplates';
			$name = JText::_('JTOOLBAR_OPTIONS');

			$link = '<a  href="' . $url . '" target="_blank" class="btn btn-small">'
				. ' <span class="icon-options" aria-hidden="true"></span> ' . $name
				. '</a>';

			JToolbar::getInstance('toolbar')->appendButton('Custom', $link, 'options');
		}
	}

	protected function render(&$form, $name = '')
	{
		$items = [];
		foreach ($form->getFieldset($name) as $field)
		{
			$datashowon = '';
			if ($field->showon)
			{
				$formControl = $field->getAttribute('form', $field->formControl);
				$datashowon  = ' data-showon=\'' . json_encode(JFormHelper::parseShowOnConditions($field->showon, $formControl, $field->group)) . '\'';
			}

			$items[] = '<div class="control-group"' . $datashowon . '><div class="control-label">'
				. $field->label
				. '</div><div class="controls">'
				. $field->input
				. '</div></div>';
		}

		if (empty ($items))
		{
			return '';
		}

		return implode('', $items);
	}
}
