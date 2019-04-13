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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.client.helper');
jimport('joomla.utilities.date');

class QuickDownloadModelFile extends JModelAdmin
{

	protected $text_prefix = 'COM_QUICKDOWNLOAD';


	public function getTable($type = 'File', $prefix = 'QuickDownloadTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_quickdownload.file', 'file', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_quickdownload.edit.file.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}



	protected function prepareTable( $table ) {
		
		if (empty($table->id)) {
			
			// set creation date
			$tz		= new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
			$jdate 	= new JDate();
			$jdate->setTimezone($tz);
			$table->created = $jdate->toSql(true);
			
		}
		
		if ( $table->external ){

			$table->type = $this->getExtension ( $table->external );
			$table->size = 0;

		} else {
			// set filetype and size
			$file = COM_QUICKDOWNLOAD_UPLOAD_FOLDER. DIRECTORY_SEPARATOR . $table->location;
			
			if (JFile::exists( $file )) {
				$table->type = JFile::getExt( $file );
				$table->size = filesize ( $file );
			}
		}
	}
	


	
	/*
	 * Method to return the extension for an external file 
	 * 
	 * name: 		getExtension
	 * @param		string $url			the url address of the external file
	 * @return		extension format if success, null on failure
	 */
	protected function getExtension ( $url ) {

		$regex = '/(\.\w*)$/';

		if (preg_match( $regex, $url, $matches )) {
 			 return str_replace('.','',$matches[0]);   
		}

		return null;
	}


	
	/*
	 * Method to check if the url provided is correct
	 * 
	 * name: 		parseURL
	 * @param		string $url			the url address of the external file
	 * @return		string 				the url address
	 */
	protected function parseURL ( $url ) {
		
		$regex = '/^(http|https):\/\//i';

		if (preg_match( $regex, $url, $matches )) {
 			 return $url;   
		} else {
			return 'http://' . $url;
		}
	}

	
	/*
	 * Method to delete selected files 
	 * 
	 * name: 		deleteFiles
	 * @param		array $ids		id of the files to be deleted	
	 * @return		boolean
	 */
	public function deleteFiles ( &$ids ) {
		
		JClientHelper::setCredentialsFromRequest('ftp');
		
		$table	= $this->getTable();
		
		foreach ( $ids as $id ) {
			
			if ($table->load($id)) {
				
				if (!$table->delete( $id )) {
					$this->setError($table->getError());
					return false;
					
				} else {
					$path 		= 	COM_QUICKDOWNLOAD_UPLOAD_FOLDER . DIRECTORY_SEPARATOR . $table->location;
					
					if (!$this->getFileDependencies($table->location)) {
						if (JFile::exists($path)) {
							if (!JFile::delete($path)) {
								$this->setError ( JText::_('COM_QUICKDOWNLOAD_FILES_ERROR_DELETE_FTP') . $table->location );
								return false;
							} 
						}
					}
				}
				
			}
		}

		return true;
	}
	
	
	
	/*
	 * Method to check if more files are using the same physical file
	 * 
	 * name: 		getFileDependencies
	 * @param		string $file		the file alias (path on ftp)
	 * @return		boolean				true if any other file is using the same physical file
	 */
	protected function getFileDependencies ( $file ) {
		
		$db = JFactory::getDbo();
		
		$query = 	' SELECT COUNT(a.id) AS count ' .
					' FROM #__quickd_files AS a ' .
					' WHERE a.location = ' . $db->Quote($db->escape($file));

		$db->setQuery ( $query );
		$dependencies = $db->loadResult();

		if (empty($dependencies)) {
			return false;
		} else {
			return true;
		}
			
	}
	



}
?>
