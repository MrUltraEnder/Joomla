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

use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Helper\ContentHelper as JContentHelper;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\MVC\View\HtmlView as JView;
use RegularLabs\Library\Parameters as RL_Parameters;

/**
 * View class for a list of template styles.
 */
class AdvancedTemplatesViewStyles extends JView
{
	protected $items;

	protected $pagination;

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
		$this->items      = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state      = $this->get('State');
		$this->preview    = JComponentHelper::getParams('com_templates')->get('template_positions_display');

		foreach ($this->items as $i => $item)
		{
			$this->items[$i]->params = json_decode($item->advancedparams);
			if (is_null($this->items[$i]->params))
			{
				$this->items[$i]->params = (object) [];
			}
		}

		AdvancedTemplatesHelper::addSubmenu('styles');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->getConfig();
		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

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
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolbar()
	{
		$canDo = JContentHelper::getActions('com_templates');

		if ($this->config->heading_title)
		{
			JToolbarHelper::title(JText::_('COM_TEMPLATES_MANAGER_STYLES'), 'eye thememanager');
		}
		else
		{
			JToolbarHelper::title(JText::sprintf('ATP_HEADING', JText::_('COM_TEMPLATES_SUBMENU_STYLES')), 'advancedtemplatemanager icon-reglab');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::makeDefault('styles.setDefault', 'COM_TEMPLATES_TOOLBAR_SET_HOME');
			JToolbarHelper::divider();
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('style.edit');
		}

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::custom('styles.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			JToolbarHelper::divider();
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'styles.delete');
			JToolbarHelper::divider();
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			JToolbarHelper::preferences('com_advancedtemplates');
			JToolbarHelper::divider();
		}

		JToolbarHelper::help('JHELP_EXTENSIONS_TEMPLATE_MANAGER_STYLES');

		JHtmlSidebar::setAction('index.php?option=com_advancedtemplates&view=styles');

		JHtmlSidebar::addFilter(
			JText::_('JGLOBAL_FILTER_CLIENT'),
			'filter_client_id',
			JHtml::_(
				'select.options',
				AdvancedTemplatesHelper::getClientOptions(),
				'value',
				'text',
				$this->state->get('filter.client_id', 0)
			),
			true
		);

		JHtmlSidebar::addFilter(
			JText::_('COM_TEMPLATES_FILTER_TEMPLATE'),
			'filter_template',
			JHtml::_(
				'select.options',
				AdvancedTemplatesHelper::getTemplateOptions($this->state->get('filter.client_id', 0)),
				'value',
				'text',
				$this->state->get('filter.template')
			)
		);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return [
			'color'       => JText::_('ATP_COLOR'),
			'a.title'     => JText::_('COM_TEMPLATES_HEADING_STYLE'),
			'a.home'      => JText::_('COM_TEMPLATES_HEADING_DEFAULT'),
			'a.client_id' => JText::_('JCLIENT'),
			'a.id'        => JText::_('JGRID_HEADING_ID'),
		];
	}
}
