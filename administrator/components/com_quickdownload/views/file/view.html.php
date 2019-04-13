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

class QuickDownloadViewFile extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;

	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		
		JHtml::stylesheet( 'administrator/components/com_quickdownload/assets/css/default.css' );
		JRequest::setVar('hidemainmenu', true);
		
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$canDo		= QuickDownloadHelper::getActions();
		
		if ($isNew) { 
			JToolBarHelper::title(JText::_('COM_QUICKDOWNLOAD_FILES_ADD'), 'files.png');
		} else {
			JToolBarHelper::title(JText::_('COM_QUICKDOWNLOAD_FILES_EDIT'), 'files.png');
		}

		// If not checked out, can save the item.
		if ($canDo->get('core.edit')||($canDo->get('core.create'))) {
			JToolBarHelper::apply('file.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('file.save', 'JTOOLBAR_SAVE');
		}
		if ($canDo->get('core.create')){		
			JToolBarHelper::custom('file.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if ($canDo->get('core.create')) {
			JToolBarHelper::custom('file.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('file.cancel','JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('file.cancel', 'JTOOLBAR_CLOSE');
		}


	}
}
?>
