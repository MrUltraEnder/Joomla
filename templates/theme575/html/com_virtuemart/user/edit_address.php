<?php
/**
 *
 * Enter address data for the cart, when anonymous users checkout
 *
 * @package    VirtueMart
 * @subpackage User
 * @author Oscar van Eijk, Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: edit_address.php 7988 2014-05-23 17:21:45Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
error_reporting('E_ALL');
// vmdebug('user edit address',$this->userFields['fields']);
// Implement Joomla's form validation
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
//JHtml::_ ('behavior.formvalidation');
JHtml::stylesheet ('vmpanels.css', JURI::root () . 'components/com_virtuemart/assets/css/');

?>
<div class="cart-view">

<h3 class="module-title"><?php echo $this->page_title ?></h3>
<div class="billing-box shoper">
<div class="login-box">
<?php
if (!class_exists('VirtueMartCart')) require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');

$this->cart = VirtueMartCart::getCart();
//var_dump($this->cart);
//var_dump($this->cart->user->virtuemart_user_id);
//var_dump ($this->cart->_inCheckOut);
//var_dump ($this->cart->_fromCart);
$url = 0;
if ((($this->cart->_fromCart or $this->cart->_inCheckOut)&& (empty($this->cart->user->virtuemart_user_id))) or (empty($this->cart->user->virtuemart_user_id))) {
	$rview = 'cart';
}
else {
	$rview = 'user';
}
//var_dump ($rview);
$task = '';
if ($this->cart->getInCheckOut()){
	$task = '&task=checkout';
}
$url = JRoute::_ ('index.php?option=com_virtuemart&view='.$rview.$task, $this->useXHTML, $this->useSSL);

echo shopFunctionsF::getLoginForm (TRUE, FALSE, $url);?>
</div>

<script language="javascript">
	function myValidator(f) {
		//f.task.value = t; //this is a method to set the task of the form on the fTask.
		if (document.formvalidator.isValid(f)) {
			if (jQuery('#recaptcha_wrapper').is(':hidden') && ((t == 'registercartuser') || (t == 'registercheckoutuser'))) {
				jQuery('#recaptcha_wrapper').show();
				var msg = '<?php echo addslashes (vmText::_ ('COM_VIRTUEMART_USER_FORM_CAPTCHA')); ?>';
				alert(msg + ' ');
			} else {
				f.submit();
				return true;
			}
		} else {
			if (jQuery('#recaptcha_wrapper').is(':hidden') && ((t == 'registercartuser') || (t == 'registercheckoutuser'))) {
				jQuery('#recaptcha_wrapper').show();
				var msg = '<?php echo addslashes (vmText::_ ('COM_VIRTUEMART_USER_FORM_MISSING_REQUIRED_JS')); ?>'+'\n'+'<?php echo addslashes (vmText::_ ('COM_VIRTUEMART_USER_FORM_CAPTCHA')); ?>';
				alert(msg + ' ');
			} else {
				var msg = '<?php echo addslashes (vmText::_ ('COM_VIRTUEMART_USER_FORM_MISSING_REQUIRED_JS')); ?>';
				alert(msg + ' ');
			}
		}
		return false;
	}

	function callValidatorForRegister(f) {

		var elem = jQuery('#username_field');
		elem.attr('class', "required");

		var elem = jQuery('#name_field');
		elem.attr('class', "required");

		var elem = jQuery('#password_field');
		elem.attr('class', "required");

		var elem = jQuery('#password2_field');
		elem.attr('class', "required");

		//var elem = jQuery('#userForm');

		return myValidator(f);

	}
</script>

<form method="post" id="userForm" name="userForm" class="form-validate" action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=user',$this->useXHTML,$this->useSSL) ?>" >
<fieldset>
	<h2><?php
		if ($this->address_type == 'BT') {
			echo vmText::_ ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL');
		}
		else {
			echo vmText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL');
		}
		?>
	</h2>


		<!--<form method="post" id="userForm" name="userForm" action="<?php echo JRoute::_ ('index.php'); ?>" class="form-validate">-->
		

	<?php
		// captcha addition
		if(VmConfig::get ('reg_captcha')){
			JHTML::_('behavior.framework');
			JPluginHelper::importPlugin('captcha');
			$captcha_visible = vRequest::getVar('captcha');
			$dispatcher = JDispatcher::getInstance(); $dispatcher->trigger('onInit','dynamic_recaptcha_1');
			$hide_captcha = (VmConfig::get ('oncheckout_only_registered') or $captcha_visible) ? '' : 'style="display: none;"';
			?>
			<fieldset id="recaptcha_wrapper" <?php echo $hide_captcha ?>>
				<?php if(!VmConfig::get ('oncheckout_only_registered')) { ?>
					<span class="userfields_info"><?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_CAPTCHA'); ?></span>
				<?php } ?>
				<div id="dynamic_recaptcha_1"></div>
			</fieldset>
		<?php
		}
		// end of captcha addition


		if (!class_exists ('VirtueMartCart')) {
			require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');
		}

		if (count ($this->userFields['functions']) > 0) {
			echo '<script language="javascript">' . "\n";
			echo join ("\n", $this->userFields['functions']);
			echo '</script>' . "\n";
		}
		echo $this->loadTemplate ('userfields');

		?>
        <div class="pad-top">
			<?php


			if ($this->cart->getInCheckOut() || $this->address_type == 'ST') {
				$buttonclass = 'button btn-primary btn';
			}
			else {
				$buttonclass = 'button vm-button-correct btn-primary btn';
			}


			if (VmConfig::get ('oncheckout_show_register', 1) && $this->userDetails->JUser->id == 0 && !VmConfig::get ('oncheckout_only_registered', 0) && $this->address_type == 'BT') {
				echo '<div id="reg_text">'.vmText::sprintf ('COM_VIRTUEMART_ONCHECKOUT_DEFAULT_TEXT_REGISTER', vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'), vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST')).'</div>';			}
			else {
				//echo vmText::_('COM_VIRTUEMART_REGISTER_ACCOUNT');
			}
			if (VmConfig::get ('oncheckout_show_register', 1) && $this->userDetails->JUser->id == 0 && $this->address_type == 'BT') {
				?>
				<button name="register" class="<?php echo $buttonclass ?>" type="submit" onclick="javascript:return callValidatorForRegister(userForm);"
				        title="<?php echo vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?>"><?php echo vmText::_ ('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?></button>
				<?php if (!VmConfig::get ('oncheckout_only_registered', 0)) { ?>
					<button name="save" class="<?php echo $buttonclass ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?>" type="submit"
					        onclick="javascript:return myValidator(userForm);"><?php echo vmText::_ ('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?></button>
					<?php } ?>
				<button class="btn btn-default button default reset" type="reset"
				        onclick="window.location.href='<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=' . $rview); ?>'"><?php echo vmText::_ ('COM_VIRTUEMART_CANCEL'); ?></button>
				<?php
			}
			else {
				?>
				<button class="<?php echo $buttonclass ?>" type="submit"
				        onclick="javascript:return myValidator(userForm);"><?php echo vmText::_ ('COM_VIRTUEMART_SAVE'); ?></button>
				<button class="button default btn btn-default reset" type="reset"
				        onclick="window.location.href='<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=' . $rview); ?>'"><?php echo vmText::_ ('COM_VIRTUEMART_CANCEL'); ?></button>
				<?php } ?>
               
		</div>
        

<?php // }
if ($this->userDetails->JUser->get ('id')) {
	echo ' <br />';
	//echo $this->loadTemplate ('addshipto');
} ?>

<input type="hidden" name="option" value="com_virtuemart"/>
<input type="hidden" name="view" value="user"/>
<input type="hidden" name="controller" value="user"/>
<input type="hidden" name="task" value="saveUser"/>
<input type="hidden" name="layout" value="<?php echo $this->getLayout (); ?>"/>
<input type="hidden" name="address_type" value="<?php echo $this->address_type; ?>"/>
<?php if (!empty($this->virtuemart_userinfo_id)) {
	echo '<input type="hidden" name="shipto_virtuemart_userinfo_id" value="' . (int)$this->virtuemart_userinfo_id . '" />';
}
echo JHtml::_ ('form.token');
?>

</fieldset>
</form>
</div>
</div>