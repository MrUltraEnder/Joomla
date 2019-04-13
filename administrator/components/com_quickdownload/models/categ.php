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

jimport('joomla.application.component.modeladmin');
jimport('joomla.utilities.date');

class QuickDownloadModelCateg extends JModelAdmin
{

	protected $text_prefix = 'COM_QUICKDOWNLOAD_CATEGORIES';


	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_quickdownload.categ');
		}
		else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_quickdownload.categ');
		}
		else {
			return parent::canEditState($record);
		}
	}


	public function getTable($type = 'Categ', $prefix = 'QuickDownloadTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_quickdownload.categ', 'categ', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) { 
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_quickdownload.edit.categ.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
	
		return $data;
	}


	protected function prepareTable($table) {
		
		if (empty($table->id)) {
			$query 	= 'SELECT MAX(a.ordering) FROM #__quickd_categories AS a';
			$this->_db->setQuery ($query);
			$max = $this->_db->loadResult();

			$table->ordering = ($max+1);
			$table->published = 1;
		}
	}



}
?>
