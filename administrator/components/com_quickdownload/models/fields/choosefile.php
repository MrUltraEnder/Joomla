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

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');


class JFormFieldChoosefile extends JFormFieldList {

	protected $type = 'Choosefile';

	public function getInput() {
		
		
		$script = array();
		$script[] = '	function jInsertFieldValue(value,id) {';
		$script[] = '		var old_id = document.getElementById(id).value;';
		$script[] = '		if (old_id != id) {';
		$script[] = '			document.getElementById(id).value = value;';
		$script[] = '		}';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		

		
		// Initialize variables.
		$html = array();
		$link = 'index.php?option=com_quickdownload&view=assets&tmpl=component&fieldid='.$this->id;

		// Initialize some field attributes.
		$attr = $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		$html[] = '<div class="pull-left">';
		$html[] = '<input id="'.$this->id.'" type="text" value="'. $this->value.'" name="'.$this->name.'" '.$attr.'>';
		$html[] = '</div>';

		$html[] = '<div class="button2-left">';
		$html[] = '<div class="blank">';
		$html[] = '<a class="modal btn pull-left" rel="{handler: \'iframe\', size: {x: 800, y: 500}}" href="'.$link.'" title="'.JText::_('COM_QUICKDOWNLOAD_FILES_SELECT').'">'.JText::_('COM_QUICKDOWNLOAD_FILES_SELECT').'</a>';
		$html[] = '</div>';
		$html[] = '</div>';

		$html[] = '<div class="button2-left">';
		$html[] = '<div class="blank">';
		$html[] = '<a class="btn pull-left" onclick="javascript:document.getElementById(\''.$this->id.'\').value=\'\';" href="#" title="'.JText::_('COM_QUICKDOWNLOAD_FILES_CLEAR').'">'.JText::_('COM_QUICKDOWNLOAD_FILES_CLEAR').'</a>';
		$html[] = '</div>';
		$html[] = '</div>';
		

		return implode( $html );
	}
	
}
?>
