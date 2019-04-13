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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');


class QuickDownloadModelAssets extends JModelList
{
	public function __construct($config = array()) {
		
		parent::__construct($config);
	}
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// List state information.
		parent::populateState();
	}

	protected function getStoreId($id = '') {

		return parent::getStoreId($id);
	}


	public function getFiles() {

		$location	= COM_QUICKDOWNLOAD_UPLOAD_FOLDER;
		$fileList	= JFolder::files( $location );
		$files 		= array();

		if ( !empty( $fileList ) ) {
			foreach ( $fileList as $file ) {
				if ( $file == 'index.html' ) {
					continue;
				}
				$indexat = new JObject();
				$indexat->title 	= $file;
				$pathinfo 			= pathinfo ( $location . DIRECTORY_SEPARATOR . $file );  
				$indexat->filetype 	= isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
				$indexat->size		= filesize( $location . DIRECTORY_SEPARATOR . $file );
				$files[] = $indexat;

			}
		}
		return $files;
	}

}
?>
