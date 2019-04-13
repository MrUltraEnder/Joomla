<?php
/*
 * @component QuickDownload
 * @version 3.1 'QD-03'
 * @website : http://www.ionutlupu.me
 * @copyright Ionut Lupu. All rights reserved.
 * @license : http://www.gnu.org/copyleft/gpl.html GNU/GPL , see license.txt
 */
 
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class QuickDownloadViewCodes extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $sidebar;

	function display($tpl = null) {
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		QuickDownloadSubmenuHelper::addSubmenu ('codes');
		$this->sidebar = JHtmlSidebar::render();

		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	public function addToolbar() {	
		
		JHtml::stylesheet( 'administrator/components/com_quickdownload/assets/css/default.css' );
		
		$canDo	= QuickDownloadHelper::getActions();
		
		JToolBarHelper::title(   'QuickDownload :: ' . JText::_( 'COM_QUICKDOWNLOAD_CODES' ), 'codes' );	
		
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('code.add', 'JTOOLBAR_NEW');
		}
		
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('code.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.delete')) {
			JToolBarHelper::custom('codes.delete', 'delete.png', 'delete.png', 'JTOOLBAR_DELETE' , false);
		}
		
		JToolBarHelper::divider();
		
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::custom('codes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('codes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::divider();
		}
	
		JToolBarHelper::divider();
		
		JToolBarHelper::custom('cpanel', 'default.png', 'default.png', 'COM_QUICKDOWNLOAD_CPANEL' , false);
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_quickdownload');
			JToolBarHelper::divider();
		}		

	}
	
}
?>
