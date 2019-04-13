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
?>

<div class="item">
	<a href="javascript:ImageManager.populateFields('<?php echo $this->_tmp_file->path_relative; ?>')" title="<?php echo $this->_tmp_file->name; ?>" >
		<span title="<?php echo $this->_tmp_file->name; ?>"><?php echo QuickDownloadHelper::parseSize( $this->_tmp_file->size ); ?></span>
		<?php echo $this->_tmp_file->title; ?>
	</a>
</div>

