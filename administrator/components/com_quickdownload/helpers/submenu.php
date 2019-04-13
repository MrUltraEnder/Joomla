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

class QuickDownloadSubmenuHelper {

	public static function addSubmenu ( $view ) {

		JHtmlSidebar::addEntry(
                        JText::_( 'COM_QUICKDOWNLOAD_FILES' ),
                        'index.php?option=com_quickdownload&view=files', $view == 'files' );
                        
		JHtmlSidebar::addEntry(
						JText::_( 'COM_QUICKDOWNLOAD_CATEGORIES' ),
                        'index.php?option=com_quickdownload&view=categs', $view == 'categs'); 
                        
		JHtmlSidebar::addEntry(
						JText::_( 'COM_QUICKDOWNLOAD_CODES' ),
                        'index.php?option=com_quickdownload&view=codes', $view == 'codes');               
	}
}


?>
