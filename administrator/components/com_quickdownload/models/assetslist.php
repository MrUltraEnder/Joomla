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
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');


class QuickDownloadModelAssetsList extends JModel
{
	function getState($property = null, $default = null) {
		
		static $set;

		if (!$set) {
			$folder = JRequest::getVar('folder', '', '', 'path');
			$this->setState('folder', $folder);

			$parent = str_replace("\\", "/", dirname($folder));
			$parent = ($parent == '.') ? null : $parent;
			$this->setState('parent', $parent);
			$set = true;
		}

		return parent::getState($property, $default);
	}


	function getFolders() {
		$list = $this->getList();
		return $list['folders'];
	}
	

	function getDocuments() {
		$list = $this->getList();
		return $list['docs'];
	}


	function getList()
	{
		
		static $list;

		// Only process the list once per request
		if (is_array($list)) {
			return $list;
		}

		// Get current path from request
		$current = $this->getState('folder');

		// If undefined, set to empty
		if ($current == 'undefined') {
			$current = '';
		}

		// Initialise variables.
		if (strlen($current) > 0) {
			$basePath = COM_QUICKDOWNLOAD_UPLOAD_FOLDER .'/'.$current;
		}
		else {
			$basePath = COM_QUICKDOWNLOAD_UPLOAD_FOLDER;
		}

		$mediaBase = str_replace(DIRECTORY_SEPARATOR, '/', COM_QUICKDOWNLOAD_UPLOAD_FOLDER .'/');

		$folders	= array ();
		$docs		= array ();

		// Get the list of files and folders from the given folder
		$fileList	= JFolder::files($basePath);
		$folderList = JFolder::folders($basePath);

		// Iterate over the files if they exist
		if ($fileList !== false) {
			foreach ($fileList as $file)
			{	
				if (is_file($basePath.'/'.$file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html') {
					$tmp = new JObject();
					$tmp->name 	= $file;
					$tmp->title = $file;
					$tmp->path 	= str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($basePath.DIRECTORY_SEPARATOR.$file));
					$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
					$tmp->size 	= filesize($tmp->path);
					$docs[] 	= $tmp;
				}
			}
		}
		

		// Iterate over the folders if they exist
		if ($folderList !== false) {
			foreach ($folderList as $folder)
			{
				$tmp = new JObject();
				$tmp->name = basename($folder);
				$tmp->path = str_replace(DIRECTORY_SEPARATOR, '/', JPath::clean($basePath.DIRECTORY_SEPARATOR.$folder));
				$tmp->path_relative = str_replace($mediaBase, '', $tmp->path);
				$count = QuickDownloadHelper::countFiles($tmp->path);
				$tmp->files = $count[0];
				$tmp->folders = $count[1];
				$folders[] = $tmp;
			}
		}

		$list = array('folders' => $folders, 'docs' => $docs);
		
		return $list;
	}
}
?>
