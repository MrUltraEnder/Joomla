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

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.client.helper');

class QuickDownloadControllerFile extends JControllerForm {
	
	protected	$option 		= 'com_quickdownload';
	protected 	$text_prefix	= 'COM_QUICKDOWNLOAD_FILES';
	
	 
	 function upload() {
		 
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		// Get the user
		$user		= JFactory::getUser();

		// Get some data from the request
		$file		= JRequest::getVar('Filedata', '', 'files', 'array');
		$folder		= JRequest::getVar('folder', '', '', 'path');
		$return		= JRequest::getVar('return-url', null, 'post', 'base64');
		

		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// Set the redirect
		if ($return) {
			$this->setRedirect(base64_decode($return).'&folder='.$folder);
		}

		// Make the filename safe
		$file['name']	= JFile::makeSafe($file['name']);

		if (isset($file['name'])) {
			
			$filepath = JPath::clean(COM_QUICKDOWNLOAD_UPLOAD_FOLDER.DIRECTORY_SEPARATOR.strtolower($file['name']));

			$object_file = new JObject($file);
			$object_file->filepath = $filepath;
			$file = (array) $object_file;

			if (JFile::exists($file['filepath'])) {
				// File exists
				JError::raiseWarning(100, JText::_('COM_QUICKDOWNLOAD_FILES_ERROR_FILE_EXISTS'));
				return false;
			} elseif (!$user->authorise('core.create', 'com_quickdownload')) {
				// File does not exist and user is not authorised to create
				JError::raiseWarning(403, JText::_('COM_QUICKDOWNLOAD_FILES_ERROR_CREATE_NOT_PERMITTED'));
				return false;
			}

			if (!JFile::upload($file['tmp_name'], $file['filepath'])) {
				// Error in upload
				JError::raiseWarning(100, JText::_('COM_QUICKDOWNLOAD_FILES_ERROR_UPLOAD_FILE'));
				return false;
			} else {
				$this->setMessage(JText::_('COM_QUICKDOWNLOAD_FILES_UPLOAD_FILE'));
				return true;
			}
			
		} else {
			$this->setRedirect('index.php?option=com_quickdownload&view=files', JText::_('COM_QUICKDOWNLOAD_FILES_INVALID_REQUEST'), 'error');
			return false;
		}
	}
	
	
	
	/*
	 * Method to remove files
	 * 
	 * name: 		fdelete 
	 * @param
	 * @return		boolean
	 */
	public function fdelete () {
		
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$url = 'index.php?option=com_quickdownload&view=files';
		
		if (empty($ids)) {
			
			$this->setRedirect( $url, JText::_('COM_QUICKDOWNLOAD_FILES_ERROR_NO_FILES_SELECTED'), 'warning' );
		 
		 } else {
			 
			$model = $this->getModel();
			
			if ( $model->deleteFiles( $ids )) {
				$message = JText::_('COM_QUICKDOWNLOAD_FILES_N_ITEMS_DELETED');
				$this->setMessage( $message,'message' );
				$this->setRedirect( $url,$message );
			} else {
				$errors = $model->getError();
				$this->setMessage( $errors,'error' );
				$this->setRedirect( $url,$errors );
			}
		 }
	}
	
	
}

?>
