<?php
/**
 *
 * Layout for the shopping cart
 *
 * @package    VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 *
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: cart.php 2551 2010-09-30 18:52:40Z milbo $
 */

// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

//JHtml::_ ('behavior.formvalidation');
vmJsApi::addJScript('vm.STisBT',"
//<![CDATA[
	jQuery(document).ready(function($) {
		if ( $('#STsameAsBTjs').is(':checked') ) {
			$('#output-shipto-display').hide();
		} else {
			$('#output-shipto-display').show();
		}
		$('#STsameAsBTjs').click(function(event) {
			if($(this).is(':checked')){
				$('#STsameAsBT').val('1') ;
				$('#output-shipto-display').hide();
			} else {
				$('#STsameAsBT').val('0') ;
				$('#output-shipto-display').show();
			}
			location.reload();
		});
	});
//]]>
");

vmJsApi::addJScript('vm.checkoutFormSubmit','
//<![CDATA[
	jQuery(document).ready(function($) {
		jQuery(this).vm2front("stopVmLoading");
		jQuery("#checkoutFormSubmit").bind("click dblclick", function(e){
			jQuery(this).vm2front("startVmLoading");
			e.preventDefault();
			jQuery(this).attr("disabled", "true");
			jQuery(this).removeClass( "vm-button-correct" );
			jQuery(this).addClass( "vm-button" );
			jQuery(this).fadeIn( 400 );
			var name = jQuery(this).attr("name");
			$("#checkoutForm").append("<input name=\""+name+"\" value=\"1\" type=\"hidden\">");
			$("#checkoutForm").submit();
		});
	});
//]]>
');
 ?>

<?php if (!empty($this->cart->products)){ ?>
<div class="page cart-view">

	<div class="page_heading">
		<h1 class="module_title"><span><?php echo JText::_ ('COM_VIRTUEMART_CART_TITLE'); ?></span></h1>
	</div>

	<!-- Checkout steps -->
	<?php if (VmConfig::get ('oncheckout_show_steps', 1) && $this->checkout_task === 'confirm') {
		vmdebug ('checkout_task', $this->checkout_task);
		echo '<div class="checkoutStep" id="checkoutStep4">' . JText::_ ('COM_VIRTUEMART_USER_FORM_CART_STEP4') . '</div>';
	} ?>

	<section class="cart-section cart-section__login">
		<header>
			<h2><?php echo JText::_('TPL_VMTHEME_CART_STEP1'); ?></h2>
		</header>
		
		<div class="cart-block_title">
			<i class="fa fa-key"></i>
			<h3><?php echo JText::_('TPL_VMTHEME_RETURNING_CUSTOMER'); ?></h3>
			<span><?php echo JText::_('COM_VIRTUEMART_ORDER_CONNECT_FORM'); ?></span>
		</div>
		<div class="cart_login">
			<?php echo shopFunctionsF::getLoginForm ($this->cart, FALSE); ?>
		</div>

	</section>
		

	<?php 
	// This displays the form to change the current shopper
	$adminID = JFactory::getSession()->get('vmAdminID');
	if ((JFactory::getUser()->authorise('core.admin', 'com_virtuemart') || JFactory::getUser($adminID)->authorise('core.admin', 'com_virtuemart')) && (VmConfig::get ('oncheckout_change_shopper', 0))) { 
		echo $this->loadTemplate ('shopperform');
	}

	$taskRoute = '';
	 ?>
	<form method="post" id="checkoutForm" name="checkoutForm" action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=cart' . $taskRoute, $this->useXHTML, $this->useSSL); ?>">
    <?php
		if(VmConfig::get('multixcart')=='byselection'){
			if (!class_exists('ShopFunctions')) require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
			echo shopFunctions::renderVendorFullVendorList($this->cart->vendorId);
			?>
            <input type="submit" name="updatecart" title="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" value="<?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?>" class="button"  style="margin-left: 10px;"/><?php
		}
	
		// This displays the pricelist MUST be done with tables, because it is also used for the emails
		echo $this->loadTemplate ('pricelist');
		// added in 2.0.8
    ?>

    <div class="container2">
            <div class="top-cart">
              <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  	<section class="cart-section billto-shipto">
                        <header>
                            <h2><?php echo JText::_('TPL_VMTHEME_CART_STEP2'); ?></h2>
                        </header>
                        <div class="row">
                            <div class="col-md-12 billto">
                                <div class="cart-form_title">
                                    <i class="fa fa-cubes"></i>
                                    <h3><?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_LBL'); ?></h3>
                                </div>
                                <?php // Output Bill To Address ?>
                                <div class="output-billto">
									<?php
                                    $cartfieldNames = array();
                                    foreach( $this->userFieldsCart['fields'] as $fields){
                                        $cartfieldNames[] = $fields['name'];
                                    }
                        
                                    foreach ($this->cart->BTaddress['fields'] as $item) {
                                        if(in_array($item['name'],$cartfieldNames)) continue;
                                        if (!empty($item['value'])) {
                                            if ($item['name'] === 'agreed') {
                                                $item['value'] = ($item['value'] === 0) ? vmText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_NO') : vmText::_ ('COM_VIRTUEMART_USER_FORM_BILLTO_TOS_YES');
                                            }
                                            ?><!-- span class="titles"><?php echo $item['title'] ?></span -->
                                    <span class="values vm2<?php echo '-' . $item['name'] ?>"><?php echo $this->escape ($item['value']) ?></span>
                                    <?php if ($item['name'] != 'title' and $item['name'] != 'first_name' and $item['name'] != 'middle_name' and $item['name'] != 'zip') { ?>
                                        <br class="clear"/>
                                    <?php
                                    }
                                    }
                                    } ?>
                                    <div class="clear"></div>
                                </div>
                        
                                <a class="details" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=BT', $this->useXHTML, $this->useSSL) ?>" rel="nofollow">
                                    <?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_EDIT_BILLTO_LBL'); ?>
                                </a>
                        
                                <input type="hidden" name="billto" value="<?php echo $this->cart->lists['billTo']; ?>"/>
                            </div>
                    
                            <div class="col-md-12 shipto">
                                <div class="cart-form_title">
                                    <i class="fa fa-truck"></i>
                                    <h3><?php echo JText::_ ('COM_VIRTUEMART_USER_FORM_SHIPTO_LBL'); ?></h3>
                                </div>
                                <?php // Output Bill To Address ?>
                                <div class="output-shipto">
									<?php
                                    if (!class_exists ('VmHtml')) {
                                        require(VMPATH_ADMIN . DS . 'helpers' . DS . 'html.php');
                                    }
                                    if($this->cart->user->virtuemart_user_id==0){
                        
                                        echo vmText::_ ('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT');
                                        echo VmHtml::checkbox ('STsameAsBTjs', $this->cart->STsameAsBT) . '<br />';
                                    } else if(!empty($this->cart->lists['shipTo'])){
                                        echo $this->cart->lists['shipTo'];
                                    }
                        
                                    if(!empty($this->cart->ST) and  !empty($this->cart->STaddress['fields'])){
                        
                        
                        
                                        ?>
                                        <div id="output-shipto-display">
                                            <?php
                                            foreach ($this->cart->STaddress['fields'] as $item) {
                                                if (!empty($item['value'])) {
                                                    ?>
                                                    <!-- <span class="titles"><?php echo $item['title'] ?></span> -->
                                                    <?php
                                                    if ($item['name'] == 'first_name' || $item['name'] == 'middle_name' || $item['name'] == 'zip') {
                                                        ?>
                                                        <span class="values<?php echo '-' . $item['name'] ?>"><?php echo $this->escape ($item['value']) ?></span>
                                                    <?php } else { ?>
                                                        <span class="values"><?php echo $this->escape ($item['value']) ?></span>
                                                        <br class="clear"/>
                                                    <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="clear"></div>
                                </div>
                                <?php if (!isset($this->cart->lists['current_id'])) {
                                    $this->cart->lists['current_id'] = 0;
                        
                                } ?>
                                <a class="details" href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=user&task=editaddresscart&addrtype=ST&virtuemart_user_id[]=' . $this->cart->lists['current_id'], $this->useXHTML, $this->useSSL) ?>" rel="nofollow">
                                    <?php echo vmText::_ ('COM_VIRTUEMART_USER_FORM_ADD_SHIPTO_LBL'); ?>
                                </a>
                                            
                            </div>
                        </div>
</section>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <?php if ( 	VmConfig::get('oncheckout_opc',true) or
						!VmConfig::get('oncheckout_show_steps',false) or
						(!VmConfig::get('oncheckout_opc',true) and VmConfig::get('oncheckout_show_steps',false) and
							!empty($this->cart->virtuemart_shipmentmethod_id) )
					) { ?>

                    <section class="cart-section cart-section_shipping">
                        <header>
                            <h2><?php echo JText::_('TPL_VMTHEME_CART_STEP5'); ?></h2>
                        </header>

						<!-- Shipments -->
                        <div class="cart_shipment-select">
                            <?php if (!$this->cart->automaticSelectedShipment) { ?>					
                                <?php /*echo $this->cart->cartData['shipmentName'];         */ ?>
                                <?php
                               if (!empty($this->layoutName) and $this->layoutName == 'default') {
									if (VmConfig::get('oncheckout_opc', 0)) {
										$previouslayout = $this->setLayout('select');
										echo $this->loadTemplate('shipment');
										$this->setLayout($previouslayout);
                                    } else {
                                       echo JHtml::_('link', JRoute::_('index.php?option=com_virtuemart&view=cart&task=edit_shipment', $this->useXHTML, $this->useSSL), $this->select_shipment_text, 'class=""');
                                    }
                                } else {
                                    echo JText::_ ('COM_VIRTUEMART_CART_SHIPPING');
                                }?>
                            <?php } else { ?>					
                                    <?php echo $this->cart->cartData['shipmentName']; ?>					
                            <?php } ?>
                        </div>

						<?php if (VmConfig::get ('show_tax')) { ?>
                           <?php echo "" . $this->currencyDisplay->createPriceDiv ('shipmentTax', '', $this->cart->cartPrices['shipmentTax'], FALSE) . ""; ?>
                        <?php } ?>
                    
                       <?php if($this->cart->cartPrices['salesPriceShipment'] < 0) echo $this->currencyDisplay->createPriceDiv ('salesPriceShipment', '', $this->cart->cartPrices['salesPriceShipment'], FALSE); ?>
                        <?php echo $this->currencyDisplay->createPriceDiv ('salesPriceShipment', '', $this->cart->cartPrices['salesPriceShipment'], FALSE); ?>
                    
                    </section>
					<?php } ?>
                    
					<?php if ($this->cart->pricesUnformatted['salesPrice']>0.0 and
                        ( 	VmConfig::get('oncheckout_opc',true) or
                            !VmConfig::get('oncheckout_show_steps',false) or
                            ( (!VmConfig::get('oncheckout_opc',true) and VmConfig::get('oncheckout_show_steps',false) ) and !empty($this->cart->virtuemart_paymentmethod_id))
                        )
                    ) { ?>
                    <section class="cart-section cart-section_payment">
                        <header>
                            <h2><?php echo JText::_('TPL_VMTHEME_CART_STEP6'); ?></h2>
                        </header>
                    
                        <?php if (!$this->cart->automaticSelectedPayment) { ?>
                                <?php //echo $this->cart->cartData['paymentName']; ?>
                               <?php if (!empty($this->layoutName) && $this->layoutName == 'default') {
									if (VmConfig::get('oncheckout_opc', 0)) {
										$previouslayout = $this->setLayout('select');
										echo $this->loadTemplate('payment');
										$this->setLayout($previouslayout);
                                    } else {
                                       echo JHtml::_('link', JRoute::_('index.php?option=com_virtuemart&view=cart&task=editpayment', $this->useXHTML, $this->useSSL), $this->select_payment_text, 'class=""');
                                    }
                                } else {
                                    echo JText::_ ('COM_VIRTUEMART_CART_PAYMENT');
                                } ?> 
                        <?php } else { ?>
                            <?php echo $this->cart->cartData['paymentName']; ?>
                        <?php } ?>
                    
                        <?php if (VmConfig::get ('show_tax')) { ?>
                            <?php echo "" . $this->currencyDisplay->createPriceDiv ('paymentTax', '', $this->cart->cartPrices['paymentTax'], FALSE) . ""; ?>
                        <?php } ?>
                    
                       <?php if($this->cart->cartPrices['salesPriceShipment'] < 0) echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'], FALSE); ?>
                       <?php  echo $this->currencyDisplay->createPriceDiv ('salesPricePayment', '', $this->cart->cartPrices['salesPricePayment'], FALSE); ?>
                    </section>
                    <?php } ?>
                    
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
      				<section class="cart-section cart-section_total">
                        <header>
                            <h2><?php echo JText::_('TPL_VMTHEME_CART_TOTAL'); ?></h2>
                        </header>
                        <?php
							if (VmConfig::get ('coupons_enable')) {
						?>

                                	<!-- Coupon code -->
						<?php if (!empty($this->layoutName) && $this->layoutName == 'default') {
                            // echo JHTML::_('link', JRoute::_('index.php?view=cart&task=edit_coupon',$this->useXHTML,$this->useSSL), JText::_('COM_VIRTUEMART_CART_EDIT_COUPON'));
                            echo $this->loadTemplate ('coupon');
                        } ?>
                        
                        <?php if (isset($this->cart->cartData['couponCode'])) { ?>
                        <table class="table cart-summary">
                            <tbody>
                                <tr class="cart-coupon-code">
                                    <td class="table-column__coupon-code" data-title="<?php echo JText::_ ('TPL_VMTHEME_CART_COUPON_CODE') ?>">				
                                        <?php 
                                            echo '<p class="coupon_code">' . $this->cart->cartData['couponCode'] . '</p>';
                                            echo '<p class="coupon_description">' . $this->cart->cartData['couponDescr'] ? ( $this->cart->cartData['couponDescr'] ) : '' . '</p>'; 
                                        ?>		
                                    </td>
                                    <?php if (VmConfig::get ('show_tax')) { ?>
                                        <td class="table-column__tax" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?>"><?php echo $this->currencyDisplay->createPriceDiv ('couponTax', '', $this->cart->pricesUnformatted['couponTax'], FALSE); ?></td>
                                    <?php } ?>
                        
                                    <td class="table-column__discount" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?>"><?php echo $this->currencyDisplay->createPriceDiv ('salesPriceCoupon', '', $this->cart->pricesUnformatted['salesPriceCoupon'], FALSE); ?></td>
                                    <td class="table-column__total"></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                     	<?php } ?>
   
                        <!-- Cart total -->
                        <table class="table table-bordered cart-summary">
                            <tbody>
                            <tr class="cart-total">
                                <td class="table-column__total-label" data-title="<?php echo JText::_ ('') ?>"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>:</td>
                    
                                <?php if (VmConfig::get ('show_tax')) { ?>
                                    <td class="table-column__tax" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?>"><?php echo "" . $this->currencyDisplay->createPriceDiv ('billTaxAmount', '', $this->cart->cartPrices['billTaxAmount'], FALSE) . "" ?> </td>
                                    <?php } ?>
                                <td class="table-column__discount" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?>"><?php echo "" . $this->currencyDisplay->createPriceDiv ('billDiscountAmount', '', $this->cart->cartPrices['billDiscountAmount'], FALSE) . "" ?> </td>
                                <td class="table-column__total" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>"><div class="bold"><?php echo $this->currencyDisplay->createPriceDiv ('billTotal', '', $this->cart->cartPrices['billTotal'], FALSE); ?></div></td>
                            </tr>
                    
                            <?php if ($this->totalInPaymentCurrency) { ?>
                                <tr class="cart-total-currency totalInPaymentCurrency">
                                    <td class="table-column__total-label" data-title="<?php echo JText::_ ('') ?>"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL_PAYMENT') ?>:</td>
                    
                                    <?php if (VmConfig::get ('show_tax')) { ?>
                                        <td class="table-column__tax" data-title="<?php echo JText::_ ('') ?>"></td>
                                    <?php } ?>
                                    <td class="table-column__discount" data-title="<?php echo JText::_ ('') ?>"></td>
                                    <td class="table-column__total" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>"><strong><?php echo $this->totalInPaymentCurrency; ?></strong></td>
                                </tr>
                            <?php }	?>
                    
                            </tbody>
                        </table>
                    </section>
                  </div>
              </div>

            </div>
          </div>
		<?php if (!empty($this->checkoutAdvertise)) {
			?> <div id="checkout-advertise-box"> <?php
			foreach ($this->checkoutAdvertise as $checkoutAdvertise) {
				?>
				<div class="checkout-advertise">
					<?php echo $checkoutAdvertise; ?>
				</div>
				<?php
			}
			?></div>
            <?php }
				echo $this->loadTemplate ('cartfields');
			 ?>
		

			<div class="cart_buttons row">
				<div class="col-sm-6">
					<?php if (!empty($this->continue_link_html)) {
						echo $this->continue_link_html;
					} ?>
				</div>
				<div class="col-sm-6">
					<?php echo $this->checkout_link_html; ?>
				</div>
			</div>

			

		<?php // Continue and Checkout Button END ?>
		<input type='hidden' name='order_language' value='<?php echo $this->order_language; ?>'/>
		<input type='hidden' name='task' value='updatecart'/>
		<input type='hidden' name='option' value='com_virtuemart'/>
		<input type='hidden' name='view' value='cart'/>
	</form>
</div>
<?php }else {
	echo '<h4>'.JText::_ ('COM_VIRTUEMART_CART_EMPTY').'</h4>';
} ?>
<?php vmTime('Cart view Finished task ','Start'); ?>
