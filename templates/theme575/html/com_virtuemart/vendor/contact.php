<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage vendor
* @author Kohl Patrick, Eugen Stranz
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 2701 2011-02-11 15:16:49Z impleri $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>

<div class="page vendor-view vendor-view__contact">
	<div class="page_heading">
		<h1 class="page_title"><?php echo $this->vendor->vendor_store_name; ?></h1>
	</div>

	

		<?php if (!empty($this->vendor->images[0])) { ?>
			<div class="vendor_image">
				<?php echo $this->vendor->images[0]->displayMediaThumb('',false); ?>
			</div>
		<?php
		} ?>	

		<div class="vendor_address">
			<?php if(!class_exists('ShopFunctions')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'shopfunctions.php');
			echo shopFunctions::renderVendorAddress($this->vendor->virtuemart_vendor_id);

			$min = VmConfig::get('asks_minimum_comment_length', 50);
			$max = VmConfig::get('asks_maximum_comment_length', 2000) ;
			vmJsApi::JvalideForm();
			$document = JFactory::getDocument();
			$document->addScriptDeclaration('
				jQuery(function($){
						$("#askform").validationEngine("attach");
						$("#comment").keyup( function () {
							var result = $(this).val();
								$("#counter").val( result.length );
						});
				});
			');	?>
		</div>

	<h3 class="form_title"><?php echo JText::_('COM_VIRTUEMART_VENDOR_ASK_QUESTION')  ?></h3>

	<div class="vendor_contact-form">
		<form method="post" class="form-validate" action="<?php echo JRoute::_('index.php') ; ?>" name="askform" id="askform">

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
					    <label><?php echo JText::_('COM_VIRTUEMART_USER_FORM_NAME')  ?> </label>
					    <input type="text" class="validate[required,minSize[4],maxSize[64]]" value="<?php echo $this->user->name ?>" name="name" id="name" size="30"  validation="required name"/>
					  </div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label><?php echo JText::_('COM_VIRTUEMART_USER_FORM_EMAIL')  ?></label>
						<input type="text" class="validate[required,custom[email]]" value="<?php echo $this->user->email ?>" name="email" id="email" size="30"  validation="required email"/>
					  </div>
				</div>
			</div>

			<div class="form-group">
				<label>
					<?php $ask_comment = JText::sprintf('COM_VIRTUEMART_ASK_COMMENT', $min, $max);
						echo $ask_comment;
					?>
				</label>
				<textarea title="<?php echo $ask_comment ?>" class="form-control validate[required,minSize[<?php echo $min ?>],maxSize[<?php echo $max ?>]] field" id="comment" name="comment" cols="30" rows="10"></textarea>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="chars-counter">
						<label><?php echo JText::_('COM_VIRTUEMART_ASK_COUNT')  ?></label>
						<input type="text" value="0" size="4" class="counter" id="counter" name="counter" maxlength="4" readonly="readonly" />
					</div>
				</div>
				<div class="col-md-6">
					<input class="btn btn-default highlight-button pull-right" type="submit" name="submit_ask" title="<?php echo JText::_('COM_VIRTUEMART_ASK_SUBMIT')  ?>" value="<?php echo JText::_('COM_VIRTUEMART_ASK_SUBMIT')  ?>" />
				</div>
			</div>


			<input type="hidden" name="view" value="vendor" />
			<input type="hidden" name="virtuemart_vendor_id" value="<?php echo $this->vendor->virtuemart_vendor_id ?>" />
			<input type="hidden" name="option" value="com_virtuemart" />
			<input type="hidden" name="task" value="mailAskquestion" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>

	</div>

	<ul class="list">
		<li><?php echo $this->linkdetails ?></li>
		<li><?php echo $this->linktos ?></li>
	</ul>	
</div>