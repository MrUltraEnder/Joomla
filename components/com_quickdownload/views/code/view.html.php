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

class QuickDownloadViewCode extends JViewLegacy
{
	protected $items;
	protected $category;
	protected $categories;
	protected $options;
	protected $model;
	
	public function display($tpl = null)
	{
		$this->options		= JComponentHelper::getParams('com_quickdownload');
		$this->category 	= $this->get('Category');
		$this->items		= $this->get('Items');
		$this->categories 	= $this->get('Categories');
		$this->model		= $this->getModel('Code');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		JHtml::stylesheet( 'components/com_quickdownload/bootstrap.css' );
		JHtml::stylesheet( 'components/com_quickdownload/style.css' );
	}
	

}
?>
