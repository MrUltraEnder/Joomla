<?php

 /**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
 if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<div class="contact_form">
	<form id="contact-form" action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate">
		<div class="row">
			<div class="col-md-12">
            <div class="row">
				<div class="form-group col-md-4">
					<?php echo $this->form->getLabel('contact_name'); ?>
					<?php echo $this->form->getInput('contact_name'); ?>
				</div>
				<div class="form-group col-md-4">
					<?php echo $this->form->getLabel('contact_email'); ?>
					<?php echo $this->form->getInput('contact_email'); ?>
				</div>
				<div class="form-group col-md-4">
					<?php echo $this->form->getLabel('contact_subject'); ?>
					<?php echo $this->form->getInput('contact_subject'); ?>
				</div>
            </div>    
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<?php echo $this->form->getLabel('contact_message'); ?>
					<?php echo $this->form->getInput('contact_message'); ?>
				</div>
			</div>
		</div>

		<?php //Dynamically load any additional fields from plugins. ?>
	     <?php foreach ($this->form->getFieldsets() as $fieldset): ?>
	          <?php if ($fieldset->name != 'contact'):?>
	               <?php $fields = $this->form->getFieldset($fieldset->name);?>
	               <div class="form-group1">
	               <?php foreach($fields as $field): ?>
	                    <?php if ($field->hidden): ?>
	                         <?php echo $field->input;?>
	                    <?php else:?>
                            <?php echo $field->label; ?>
                            <?php if (!$field->required && $field->type != "Spacer"): ?>
                               <span class="optional"><?php echo JText::_('COM_CONTACT_OPTIONAL');?></span>
                            <?php endif; ?>
	                        <?php echo $field->input;?>
	                    <?php endif;?>
	               <?php endforeach;?>
	               </div>
	          <?php endif ?>
	     <?php endforeach;?>

	    <div class="row">
	    	<div class="col-md-12">
	    		<?php if ($this->params->get('show_email_copy')){ ?>
				<div class="checkbox">
					<?php echo $this->form->getLabel('contact_email_copy'); ?>
					<?php echo $this->form->getInput('contact_email_copy'); ?>
				</div>
				<?php } ?>	
	    	</div>
	    	<div class="col-md-12">
	    		<button class="btn btn-default button validate" type="submit"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
	    	</div>
	    </div>
	    
		<input type="hidden" name="option" value="com_contact" />
		<input type="hidden" name="task" value="contact.submit" />
		<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
		<input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</form>
</div>
