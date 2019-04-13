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
<div id="quickdownload">
	<?php if ($this->firstpage) {?> 
	<div class="well well-sm">
		<?php echo $this->firstpage; ?>
	</div>
	<?php } ?>
	
	<form action="<?php echo JRoute::_('index.php?option=com_quickdownload', false); ?>" method="post" name="adminForm" id="adminForm">
		<div class="panel panel-primary">
			<div class="panel-heading">
			</div>
			<div class="panel-body">
				<input type="text" name="codetext" value="" class="input-sm" />
				<input type="submit" value="<?php echo JText::_('COM_QUICKDOWNLOAD_GET_FILES'); ?>" name="show" class="btn btn-primary" />
				
				<input type="hidden" name="task" value="code.show" />
				<input type="hidden" name="boxchecked" value="0" />
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>

