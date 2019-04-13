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

class QuickDownloadViewAssetsList extends JViewLegacy
{
	function display($tpl = null) {

		JResponse::allowCache(false);

		$app = JFactory::getApplication();

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("var ImageManager = window.parent.ImageManager;");
		
		JHTML::_('stylesheet','administrator/components/com_quickdownload/assets/css/popup-imagelist.css');

		$this->files 	= $this->get('documents');
		$this->folders 	= $this->get('folders');
		$this->state 	= $this->get('state');
		$this->baseURL	= COM_QUICKDOWNLOAD_UPLOAD_FOLDER;

		parent::display($tpl);
	}


	function setFolder($index = 0) {
		if (isset($this->folders[$index])) {
			$this->_tmp_folder = &$this->folders[$index];
		} else {
			$this->_tmp_folder = new JObject;
		}
	}


	function setFile($index = 0) {
		if (isset($this->files[$index])) {
			$this->_tmp_file = &$this->files[$index];
		} else {
			$this->_tmp_file = new JObject;
		}
	}
	
	
}
