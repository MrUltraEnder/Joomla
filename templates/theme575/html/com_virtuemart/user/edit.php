<?php
/**
*
* Modify user form view
*
* @package	VirtueMart
* @subpackage User
* @author Oscar van Eijk
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 6472 2012-09-19 08:46:21Z alatak $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

//AdminMenuHelper::startAdminArea($this);
// vmdebug('User edit',$this);
// Implement Joomla's form validation
JHTML::_('behavior.formvalidation');
JHTML::stylesheet('vmpanels.css', JURI::root().'components/com_virtuemart/assets/css/'); // VM_THEMEURL
?>

<style type="text/css">
	.invalid {
		border-color: #f00;
		background-color: #ffd;
		color: #000;
	}
	label.invalid {
		background-color: #fff;
		color: #f00;
	}
</style>
<script language="javascript">
	function myValidator(f, t)
	{
		f.task.value=t;
		if (document.formvalidator.isValid(f)) {
			f.submit();
			return true;
		} else {
			var msg = '<?php echo addslashes( JText::_('COM_VIRTUEMART_USER_FORM_MISSING_REQUIRED_JS') ); ?>';
			alert (msg);
		}
		return false;
	}
</script>

<div class="page account-view">
	<div class="page_heading">
		<h1 class="page_title"><?php echo $this->page_title ?></h1>
	</div>

	<div class="form-login">
		<?php echo shopFunctionsF::getLoginForm(false); ?>
	</div>

	<h2><?php if($this->userDetails->virtuemart_user_id==0) {
		echo JText::_('COM_VIRTUEMART_YOUR_ACCOUNT_REG');
	}?></h2>

	<form method="post" id="adminForm" name="userForm" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user',$this->useXHTML,$this->useSSL) ?>" class="form-horizontal form-validate">
		<?php 		
		    echo $this->loadTemplate ( 'shopper' );

		// captcha addition
		if(VmConfig::get ('reg_captcha')){
			JHTML::_('behavior.framework');
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance(); $dispatcher->trigger('onInit','dynamic_recaptcha_1');
			?>
			<div id="dynamic_recaptcha_1"></div>
		<?php 
		}
		// end of captcha addition 
		?>
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="controller" value="user" />
		<?php echo JHTML::_( 'form.token' ); ?>

		<?php if($this->userDetails->user_is_vendor){ ?>
		    <div class="control-buttons">
				<button class="btn btn-primary button" type="submit" onclick="javascript:return myValidator(userForm, 'saveUser');" ><?php echo $this->button_lbl ?></button>
				<button class="btn btn-default button" type="reset" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_virtuemart&view=user', FALSE); ?>'" ><?php echo JText::_('COM_VIRTUEMART_CANCEL'); ?></button>
			</div>
		<?php } ?>		
	</form>

</div>