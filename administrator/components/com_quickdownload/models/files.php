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

jimport( 'joomla.application.component.modellist' );


class QuickDownloadModelFiles extends JModelList
{
	protected	$option 		= 'com_quickdownload';
	
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id ',
				'a.title',
				'a.type',
				'a.size',
				'a.created',
				'a.hits',
				'a.published',
			);
		}
		parent::__construct($config);
	}
	
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the filter state.
		$published = $app->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $published);
		
		$state = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $state);
		
		$state = $app->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '', 'string');
		$this->setState('filter.category', $state);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_quickdownload');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getStoreId($id = '') {
		
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.category');

		return parent::getStoreId($id);
	}
	
	
	protected function getListQuery() {
		
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $this->_db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select','a.id, a.title, a.type, a.size, a.created, a.hits, a.published'
			)
		);
		
		$query->from(' #__quickd_files AS a');

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category
		$category = $this->getState('filter.category');
		if ( $category !== '' ) {
			$query->where( 'a.category = ' . (int) $category );
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
				$query->where('( a.title LIKE '.$search.' )');
			}
		}
		
		
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$query->order($this->_db->escape($orderCol).' '.$this->_db->escape($this->getState('list.direction', 'DESC')));

		return $query;
	}
	

	public function getCategories() {

		$query = ' SELECT a.id AS value, a.name AS text' .
				 ' FROM #__quickd_categories AS a ' . 
				 ' ORDER BY a.id ASC ';
		
		$this->_db->setQuery( $query );
		$categories = $this->_db->loadObjectList ();

		array_unshift($categories, JText::_('COM_QUICKDOWNLOAD_FILES_NO_CATEGORY') );

		return $categories;
	}
	

	
}
?>
