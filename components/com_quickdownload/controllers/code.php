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


class QuickDownloadControllerCode extends JControllerLegacy {
	
	protected	$option 		= 'com_quickdownload';
	protected 	$text_prefix	= 'COM_QUICKDOWNLOAD_CODES';
	
	public function getModel($name = 'Code', $prefix = 'QuickDownloadModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	 
	public function show () {
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$code		= JRequest::getVar('codetext', '', '', 'string');
		
		if ( $code ) {
			$model = $this->getModel();
			if ($model->isAvailable($code)) {
				$this->setRedirect ( JRoute::_('index.php?option=com_quickdownload&view=code',false)  ) ;
			} else {
				$this->setRedirect ( JRoute::_('index.php?option=com_quickdownload',false) , JText::_('COM_QUICKDOWNLOAD_INVALID_CODE'), 'warning' ) ;
			}
		} else {
			$this->setRedirect ( JRoute::_('index.php?option=com_quickdownload',false) , JText::_('COM_QUICKDOWNLOAD_NO_CODE_INSERTED'), 'warning' ) ;
		}
		
	}
	
	
}

?>
