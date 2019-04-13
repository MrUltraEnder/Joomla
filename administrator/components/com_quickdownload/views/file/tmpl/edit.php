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


JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'file.cancel' || document.formvalidator.isValid(document.id('file-form'))) {
			Joomla.submitform(task, document.getElementById('file-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_quickdownload&view=files&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="file-form" class="form-validate form-horizontal">
	
	<ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('COM_QUICKDOWNLOAD_FILES_DETAILS') ; ?></a></li>
		<li><a href="#metadata" data-toggle="tab"><?php echo JText::_('COM_QUICKDOWNLOAD_FILES_PUBLISH_OPTIONS');?></a></li>
	</ul>
	<div class="tab-content">

		<div class="tab-pane active" id="general">
			<div class="row-fluid">
				<div class="span6">

					<?php $fields = array ('id', 'title','location', 'external','category','hits'); ?>
					
					<?php foreach ( $fields as $field ) { ?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel( $field ); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput( $field ); ?>
							</div>
						</div>
					<?php } ?>

				</div>
			</div>
		</div>

		
		<div class="tab-pane" id="metadata">
			<div class="row-fluid">
				<div class="span6">
					
					<?php $fields = array ('published', 'publish_up', 'publish_down'); ?>
					<?php foreach ( $fields as $field ) { ?>

					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel( $field ); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput( $field ); ?>
						</div>
					</div>
					<?php }  ?>

				</div>
			</div>
		</div>

	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
