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

class QuickDownloadViewQuickdownload extends JViewLegacy
{
	
	protected $firstpage;
	
	public function display($tpl = null) {
		$this->addToolbar();
		$this->firstpage	= $this->get('firstPage');
		parent::display($tpl);
	}
	

	public function addToolbar() {	
		JHtml::stylesheet( 'components/com_quickdownload/bootstrap.css' );
		JHtml::stylesheet( 'components/com_quickdownload/style.css' );
	}
}
?>
