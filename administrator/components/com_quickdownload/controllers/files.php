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

jimport('joomla.application.component.controlleradmin');

class QuickDownloadControllerFiles extends JControllerAdmin {
	
	protected	$option 		= 'com_quickdownload';
	protected 	$text_prefix	= 'COM_QUICKDOWNLOAD_FILES';
	
	public function getModel($name = 'File', $prefix = 'QuickDownloadModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	

	/**
	 * Method to delete selected folders
	 * 
	 */
	public function fdelete () {
		
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$ids = JRequest::getVar('cid', array(), '', 'array');
	
		
		if (empty($ids)) {
			$url = 'index.php?option=com_jextranet&view=folders';
			$this->setRedirect( $url, JText::_('COM_JEXTRANET_FOLDERS_ERROR_NO_FOLDER_SELECTED'), 'warning' );
		 } else {
			$fid = JRequest::getVar('fid','0','get');
			$url = 'index.php?option=com_jextranet&view=folders&fid=' . $fid;
			
			$model = $this->getModel();
			
			if ( $model->deleteFolders( $ids )) {
				$message = JText::_('COM_JEXTRANET_FOLDERS_N_ITEMS_DELETED');
				$this->setMessage( $message,'message' );
				$this->setRedirect( $url,$message );
			} else {
				$errors = $model->getErrors();
				$this->setMessage( $errors,'error' );
				$this->setRedirect( $url,$errors );
			}
		}	 
	}
	
	
}

?>
