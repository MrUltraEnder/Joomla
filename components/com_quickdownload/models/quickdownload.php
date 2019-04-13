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

jimport( 'joomla.application.component.model' );


class QuickDownloadModelQuickDownload extends JModelLegacy
{
	protected	$option 		= 'COM_QUICKDOWNLOAD';
	
	
	public function __construct($config = array()) {

		parent::__construct($config);
		
	}
	
	
	public function getFirstpage () {
		
		$config 	= JComponentHelper::getParams('com_quickdownload');
		$firstpage	= $config->get('firstpage');
		
		return $firstpage;
	}
	
	
	
	
}
?>
