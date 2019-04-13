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
		if (task == 'categ.cancel' || document.formvalidator.isValid(document.id('category-form'))) {
			Joomla.submitform(task, document.getElementById('category-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_quickdownload&view=category&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="category-form" class="form-validate  form-horizontal">
	
	<div class="tab-content">
		<div class="tab-pane active" id="general">
			<div class="row-fluid">
				<div class="span12">

					<?php $fields = array ('id', 'name'); ?>
					
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
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

</form>
