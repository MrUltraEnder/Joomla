<?php
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');

/**
 * Creates dropdown for selecting a string customfield
 */
class JFormFieldScustom extends JFormField {

	var $type = 'scustom';

	function getInput() {
		VmConfig::loadConfig();
		return JHtml::_('select.genericlist',  $this->_getStringCustoms(), $this->name, 'class="inputbox"   ', 'value', 'text', $this->value, $this->id);
	}

	private function _getStringCustoms() {

		$cModel = VmModel::getModel('custom');
		$cModel->_noLimit = true;
		$q = 'SELECT `virtuemart_custom_id` AS value, custom_title AS text FROM `#__virtuemart_customs` WHERE custom_parent_id="0" AND field_type = "S" ';
		$q .= ' AND `published`=1';
		$db = JFactory::getDBO();
		$db->setQuery ($q);
		$l = $db->loadObjectList ();
		$eOpt = JHtml::_('select.option', '0', vmText::_('COM_VIRTUEMART_NONE'));
		array_unshift($l,$eOpt);

		return $l;

	}

}