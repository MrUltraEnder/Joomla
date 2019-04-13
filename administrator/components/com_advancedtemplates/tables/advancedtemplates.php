<?php
/**
 * @package         Advanced Template Manager
 * @version         3.7.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table as JTable;

class AdvancedTemplatesTable extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__advancedtemplates', 'styleid', $db);
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_advancedtemplates.style.' . (int) $this->{$k};
	}

	protected function _getAssetTitle()
	{
		if (isset($this->_title))
		{
			return $this->_title;
		}

		$k = (int) $this->_tbl_key;

		if (empty($this->{$k}))
		{
			return parent::_getAssetTitle();
		}

		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select('title')
			->from('#__template_styles')
			->where('id = ' . (int) $this->{$k});
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Method to get the parent asset id for the record
	 *
	 * @param   JTable  $table A JTable object for the asset parent
	 * @param   integer $id
	 *
	 * @return  integer
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$db = $this->getDbo();

		$query = $db->getQuery(true)
			->select('id')
			->from('#__assets')
			->where('name = ' . $db->quote('com_templates'));
		$db->setQuery($query);

		if ($assetId = $db->loadResult())
		{
			return $assetId;
		}

		return parent::_getAssetParentId($table, $id);
	}
}

class AdvancedTemplatesTableAdvancedTemplates extends AdvancedTemplatesTable
{
}
