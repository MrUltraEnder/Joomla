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

jimport('joomla.application.component.model');
jimport('joomla.utilities.date');


class QuickDownloadModelCode extends JModelLegacy
{
	protected $now;
	protected $category;
	protected $code;
	protected $_db;
	protected $options;

	public function __construct( $config = array() ) {
		
		// this we need for the case when we get the code from URL; in this case $this->_db is not defined yet.
		$this->_db 			= JFactory::getDbo();
		$this->options		= JComponentHelper::getParams('com_quickdownload');
		$this->code 		= JFactory::getApplication()->getUserState('com_quickdownload.code');
		$code 				= JFactory::getApplication()->input->get('code', '','');
		
		// this is used in case the code is inserted into the url
		if ( $code ) {	
			if (  $this->isAvailable( $code ) ) {
				$this->code = $code;
			}
		}

		$this->category 	= JFactory::getApplication()->input->get('category', '','');

		// get current date
		$tz		= new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
		$jdate 	= new JDate();
		$jdate->setTimezone($tz);
		$this->now = $jdate->toSql(true);

		parent::__construct( $config );
	}


	public function getCategory() {
		return $this->category;
	}

	public function isAvailable ( $code ) {

		$query = ' SELECT COUNT(a.id) FROM #__quickd_codes AS a WHERE a.code = ' . $this->_db->Quote(trim($this->_db->escape($code))); 
		$this->_db->setQuery ( $query );
		$result = $this->_db->loadResult () ;
		
		if ( $result ) {
			
			$isOk = $this->checkCode( $code );

			if ( $isOk ) {
				//set the code into session so we can get the data in the next step
				JFactory::getApplication()->setUserState('com_quickdownload.code', trim($code));
				return true;
			}
		} 
		return false;
	}
	

	// method to check if the code meets all requirements to get files/categories
	// returns false if the code has any validity issues, array with files if code is ok
	protected function checkCode ( $code ) {
		
		if ( $code ) {

			// get details
			$query = 	' SELECT a.limit_hits_total, a.limit_hits_day, a.limit_publish_to, a.limit_publish_from, a.last_access, a.hits, a.hits_day, a.params, a.published ' .
						' FROM #__quickd_codes AS a WHERE a.code = ' . $this->_db->Quote( $this->_db->escape($code) ) . ' LIMIT 1 ';
			$this->_db->setQuery ( $query );
			$result = $this->_db->loadObject () ;

			// check if it's published
			if ($result->published != 1) { 
				$this->setError (JText::_('COM_QUICKDOWNLOAD_CODE_NOT_PUBLISHED'));
				return false;
			}
			
			//check if he started to be publish
			$publishfrom = intval($result->limit_publish_from);
			
			if ( $publishfrom > 0 ) {
				$now 			= strtotime ($this->now);				
				$publishfrom 	= strtotime ($result->limit_publish_from);
				if ($publishfrom > $now) {
					$this->setError (JText::_('COM_QUICKDOWNLOAD_CODE_NOT_AVAILABLE'));
					return false;
				}
			}
			
			
			//check if he stopped to be publish
			$publishto = intval($result->limit_publish_to);
			
			if ( $publishto > 0 ) {
				$now 		= strtotime($this->now);				
				$publishto 	= strtotime($result->limit_publish_to);
				if ($publishto < $now) {
					$this->setError (JText::_('COM_QUICKDOWNLOAD_CODE_NOT_AVAILABLE'));
					return false;
				}
			}
			
			
			// check if total number of hits is bigger than allowed
			if ( $result->limit_hits_total <= $result->hits && $result->limit_hits_total != 0 ) {
				$this->setError (JText::_('COM_QUICKDOWNLOAD_CODE_NOT_AVAILABLE'));
				return false;
			}
			
			
			// check the number of hits/day
			if ( $result->limit_hits_day != 0 ) {
				$today = date ('Y-M-d', strtotime($this->now));
				if ( intval($result->last_access) > 0 ) {
					$last = date ('Y-M-d', strtotime($result->last_access));
					if ($today == $last) {
						if ($result->hits_day >= $result->limit_hits_day) {
							$this->setError (JText::_('COM_QUICKDOWNLOAD_CODE_NOT_AVAILABLE'));
							return false;
						}
					}
				}
			}
			
			// add the last access date and increase daily hit
			$today 	= date ('Y-M-d', strtotime($this->now));
			$last 	= date ('Y-M-d', strtotime($result->last_access));
			if ( intval($result->last_access) > 0 ) {
				// if new day
				if ( $today != $last ) {
					// update last access to today date
					$query = ' UPDATE #__quickd_codes SET last_access = ' . $this->_db->quote($this->now) . ' WHERE code = ' . $this->_db->Quote($code);
					$this->_db->setQuery($query);
					$this->_db->Query();
					
					// update number of clicks with 1
					$query = ' UPDATE #__quickd_codes SET hits_day  = 1 WHERE code = ' . $this->_db->Quote($code);
					$this->_db->setQuery($query);
					$this->_db->Query();
					
				} else {
					// update number of clicks + 1
					$query = ' UPDATE #__quickd_codes SET hits_day  = (hits_day+1) WHERE code = ' . $this->_db->Quote($code);
					$this->_db->setQuery($query);
					$this->_db->Query();
				
				}
				
			} else {
				$query = ' UPDATE #__quickd_codes SET last_access = ' . $this->_db->Quote($this->now) . ' WHERE code = ' . $this->_db->Quote( $code );
				$this->_db->setQuery($query);
				$this->_db->Query();
				
				// update number of clicks with 1
				$query = ' UPDATE #__quickd_codes SET hits_day  = 1 WHERE code = ' . $this->_db->Quote( $code );
				$this->_db->setQuery($query);
				$this->_db->Query();
			}
				
			// add 1 hit for total
			$query = ' UPDATE #__quickd_codes SET hits  = (hits+1) WHERE code = ' . $this->_db->Quote( $code );
			$this->_db->setQuery($query);
			$this->_db->Query();
		}

		return true;
	}


	public function getItems () {
		
		if ( !$this->code ) {
			return;
		}
		
		$params = $this->getCodeParams();
		$permissions = $this->getPermissions($params);

		$query = $this->_db->getQuery( true );
		$query->clear();
		$query->select('a.id, a.title,a.category,a.type,a.size');
		$query->from('#__quickd_files AS a');
		
		if (($this->category && !in_array($this->category,$permissions->folders)) && ($this->category && !in_array('all', $permissions->folders)))
		{
			$query->where('a.id IN ( ' . implode(',',$permissions->files) . ' )');
		}
		
		if ( $this->category !== '' ) 
		{
			$query->where('a.category = ' . $this->category );
		}
		
		$query->where('a.published = 1');
		
		// get files ID and title 
		$this->_db->setQuery($query);
		$files = $this->_db->loadObjectList();

		return $files;
	}


	public function getCategories() {
		
		if ( !$this->code ) {
			return false;
		}
		
		$categories = array();
		$params = $this->getCodeParams();
		$permissions = $this->getPermissions($params);

		if(!empty($permissions->folders)) 
		{
			$query = $this->_db->getQuery( true );
			$query->select('a.id, a.name');
			$query->from('#__quickd_categories AS a');
			if (!in_array('all',$permissions->folders)) 
			{
				$query->where ( 'a.id IN (' . implode(',', $permissions->folders) . ')' );
			}
			$query->where('a.published = 1');
			$query->order('a.ordering ASC');

			$this->_db->setQuery($query);
			$categories = $this->_db->loadObjectList();
		
			// we add uncategorised category :)
			if (in_array(0, $permissions->folders)) {
				$none = new stdclass;
				$none->id = 0;
				$none->name = JText::_('COM_QUICKDOWNLOAD_FILE_UNCATEGORIZED');
				array_unshift( $categories, $none );
			}
		}

		return $categories;
	}

	
	protected function getCodeParams()
	{
		$query = $this->_db->getQuery( true );
		$query->select('a.params');
		$query->from('#__quickd_codes AS a');
		$query->where('a.code = ' . $this->_db->Quote( $this->_db->escape($this->code) ) );

		$this->_db->setQuery ( $query );
		return $this->_db->loadResult();
	}


	protected function getPermissions($params)
	{
		$permissions = new stdclass;
		$permissions->files = array();
		$permissions->folders = array();
		
		if ($params) 
		{
			$registry = json_decode($params);
			if ($registry->files) 
			{
				$permissions->files = $registry->files;
			}
			if ($registry->folders)
			{
				$permissions->folders = $registry->folders;
			}
		}

		return $permissions;
	}


	public function parseSize( $size ) {
		if ($size < 1024) {
			return $size . ' B';
		}
		elseif ($size < 1024 * 1024) {
			return sprintf('%01.2f', $size / 1024.0) . ' KB';
		}
		else {
			return sprintf('%01.2f', $size / (1024.0 * 1024)) . ' MB';
		}
	}
}
?>
