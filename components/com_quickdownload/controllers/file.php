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

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.client.helper');
jimport('joomla.utilities.date');

class QuickDownloadControllerFile extends JControllerLegacy {
	
	
	public function download () {
		
		$app 		= JFactory::getApplication();
		$config 	= JComponentHelper::getParams('com_quickdownload');
		$users		= $config->get('users');
		$id 		= $app->input->get('id','','');

		$code = $app->getUserState('com_quickdownload.code'); 

		if ( !$code ) {
			return false;
		}

		// if allow download only to registered users
		if ( $users == 1 ) {
			$user = JFactory::getUser()->get('id');
			if (!$user) {
				$this->setRedirect(JRoute::_('index.php?option=com_quickdownload', false), JText::_('COM_QUICKDOWNLOAD_ONLY_REGISTERED_USERS'), 'warning');
				return false;
			}
		}
		
		// if no id is inserted
		if ( !$id ) {	
			$this->setRedirect( JRoute::_('index.php?option=com_quickdownload', false) ,JText::_('COM_QUICKDOWNLOAD_NO_FILE_SELECTED'), 'warning');
			return;
		}

		// we must check if the inserted id is into allowed id's for that code ;)
		$items      = $this->getModel('Code')->getItems();
		$passed		= false;
		if ($items) 
		{
			foreach ( $items as $item ) 
			{
				if( $item->id == $id ) 
				{
					$passed = true;
				}
			}
		}
		
		if ( $passed == false ) {
			return false;
		}
		
		// so now that we know the file ID is ok, let's check if the file is good to download
		$db 	= JFactory::getDbo();
		$query 	= ' SELECT a.location, a.title, a.publish_up, a.publish_down, a.type, a.external FROM #__quickd_files AS a WHERE a.id = ' . (int) $id . ' AND a.published = 1' ;
		$db->setQuery($query);
		$file = $db->loadObject();

		if (!isset($file->location)) {
			$this->setRedirect(JRoute::_('index.php?option=com_quickdownload', false), JText::_('COM_QUICKDOWNLOAD_NO_LOCATION'), 'warning');
			return false;
		}
		
		// get current date
		$tz		= new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
		$jdate 	= new JDate();
		$jdate->setTimezone($tz);
		$tnow = $jdate->toSql(true);
		
		//check if he started to be publish
		$publishfrom = intval($file->publish_up);
			
		if ( $publishfrom > 0 ) {
			$now 			= strtotime ($tnow);				
			$publishfrom 	= strtotime ($file->publish_up);
			if ($publishfrom > $now) {
				$this->setRedirect(JRoute::_('index.php?option=com_quickdownload', false), JText::_('COM_QUICKDOWNLOAD_FILE_NOT_AVAILABLE'), 'warning');
				return false;
			}
		}
			
			
		//check if he stopped to be publish
		$publishto = intval($file->publish_down);
			
		if ( $publishto > 0 ) {
			$now 		= strtotime($tnow);				
			$publishto 	= strtotime($file->publish_down);
			if ($publishto < $now) {
				$this->setRedirect(JRoute::_('index.php?option=com_quickdownload', false), JText::_('COM_QUICKDOWNLOAD_FILE_NOT_AVAILABLE'), 'warning');
				return false;
			}
		}
		
		// if the reference is not to an external file, check if the file exists on server
		if (!$file->external) {
			$path = COM_QUICKDOWNLOAD_UPLOAD_FOLDER . $file->location;
			if(!is_file($path)) {
				$this->setRedirect($return,JText::_('COM_QUICKDOWNLOAD_NO_LOCATION'),'warning');
				return false;
			}
		} else {
			$path = $file->external;
		}
				

		$query = ' UPDATE #__quickd_files SET hits = (hits+1) WHERE id = ' . (int) $id[0] ;
		$db->setQuery($query);
		$db->Query();
			
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");

		if (!$file->external) {
			$filesize 	= @filesize($path);
			header("Content-Length: " .(string)($filesize) );
		}
		
		header('Content-Disposition: attachment; filename="'.$file->title.'.'.$file->type.'"');
		header("Content-Transfer-Encoding: binary\n");
			
		$fh = fopen( $path, 'rb' );
		while ( !feof($fh) ) {
			ob_clean();
			flush();
			$data = fread($fh, '8192');
			echo $data;
			ob_flush();
			flush();
		}
		fclose($fh);
			
		exit(0);
	}
}

?>
