<?php
/**
 *
 * Layout for the shopper form to change the current shopper
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Maik Künnemann
 *
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2013 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 2458 2013-07-16 18:23:28Z kkmediaproduction $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
?>


<h3><?php echo JText::_ ('COM_VIRTUEMART_CART_CHANGE_SHOPPER'); ?></h3>

<form action="<?php echo JRoute::_ ('index.php'); ?>" method="post" class="inline">
	<table class="table" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td>
				<?php 
				if (!class_exists ('VirtueMartModelUser')) {
					require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'user.php');
				}

				$userList = $this->getUserList();
				$currentUser = $this->cart->user->_data->virtuemart_user_id;

				echo JHTML::_('Select.genericlist', $userList, 'userID', 'class="vm-chzn-select"', 'id', 'displayedName', $currentUser); 

				$adminID = JFactory::getSession()->get('vmAdminID');
				$instance = JFactory::getUser();
				?>
			</td>
			<td>
				<input type="submit" name="changeShopper" title="<?php echo JText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo JText::_('COM_VIRTUEMART_SAVE'); ?>" class="btn btn-default button"/>
				<?php if(isset($adminID) && $instance->id != $adminID) { ?>
					<span><b><?php echo JText::_('COM_VIRTUEMART_CART_ACTIVE_ADMIN') .' '.JFactory::getUser($adminID)->name; ?></b></span>
				<?php } ?>
				<?php echo JHTML::_( 'form.token' ); ?>
				<input type="hidden" name="view" value="cart"/>
				<input type="hidden" name="task" value="changeShopper"/>
			</td>
		</tr>
	</table>
</form>
