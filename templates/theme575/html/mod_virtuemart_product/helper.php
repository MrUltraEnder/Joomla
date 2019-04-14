<?php
	defined ('_JEXEC') or  die('Direct Access to ' . basename (__FILE__) . ' is not allowed.');
	/*
 * Module Helper
 *
 * @package VirtueMart
 * @copyright (C) 2010 - Patrick Kohl
 * @ Email: cyber__fr|at|hotmail.com
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * VirtueMart is Free Software.
 * VirtueMart comes with absolute no warranty.
 *
 * www.virtuemart.net
 */
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

if (!class_exists ('VmConfig')) {
	require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
}
VmConfig::loadConfig ();

// Load the language file of com_virtuemart.
JFactory::getLanguage ()->load ('com_virtuemart');
if (!class_exists ('calculationHelper')) {
	require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'calculationh.php');
}
	if (!class_exists ('CurrencyDisplay')) {
		require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'currencydisplay.php');
	}
	if (!class_exists ('VirtueMartModelVendor')) {
		require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'models' . DS . 'vendor.php');
	}
	if (!class_exists ('VmImage')) {
		require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'image.php');
	}
	if (!class_exists ('shopFunctionsF')) {
		require(JPATH_SITE . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'shopfunctionsf.php');
	}
	if (!class_exists ('calculationHelper')) {
		require(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'cart.php');
	}
	if (!class_exists ('VirtueMartModelProduct')) {
		JLoader::import ('product', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'models');
	}


class mod_virtuemart_product_override {

	static function addtocart ($product) {

		if (!VmConfig::get ('use_as_catalog', 0)) {
			if (isset($product->step_order_level))
				$step=$product->step_order_level;
			else
				$step=1;
			if($step==0)
				$step=1;

			$stockhandle = VmConfig::get ('stockhandle', 'none');
			if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) {
				$button_lbl = JText::_ ('COM_VIRTUEMART_CART_NOTIFY');
				$button_cls = 'notify-button addtocart-button';
				$button_name = 'notifycustomer';
				?>
				<div class="form-group">
			<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="btn btn-primary notify"><?php echo JText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>
				</div>
			<?php
			} else {
				?>
			<div class="addtocart-area">

				<form method="post" class="product" action="index.php">
                <input name="quantity" type="hidden" value="<?php echo $step ?>" />
					
					<div class="addtocart-bar">
					<script type="text/javascript">
								function check(obj) {
								// use the modulus operator '%' to see if there is a remainder
								remainder=obj.value % <?php echo $step?>;
								quantity=obj.value;
								if (remainder  != 0) {
									alert('<?php echo $alert?>!');
									obj.value = quantity-remainder;
									return false;
									}
								return true;
								}
						</script> 
                        <?php // Display the quantity box 
											 if (!empty($product->customfields)) {
													foreach ($product->customfields as $k => $custom) {
														if (!empty($custom->layout_pos)) {
															$product->customfieldsSorted[$custom->layout_pos][] = $custom;
															unset($product->customfields[$k]);
														}
													}
													$product->customfieldsSorted['normal'] = $product->customfields;
													unset($product->customfields);
												}
											$position = 'addtocart';
											if (!empty($product->customfieldsSorted[$position])) { ?>
											<div class="form-group">
                                           <a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="addtocart-button select"><?php echo vmText::_ ('TM_VIRTUEMART_CART_SELECT') ?></a>
                                          </div>
										
										<?php } else { ?>
                                        <?php if ($product->orderable) { ?>
                                        <div class="form-inline hidden">
                                            <div class="form-group">
                                            <span class="quantity-box">
                                            <input type="text" class="quantity-input js-recalculate" name="quantity[]"
                                                   onblur="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                                   onclick="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                                   onchange="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                                   onsubmit="Virtuemart.checkQuantity(this,<?php echo $step?>,'<?php echo vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED')?>');"
                                                   value="<?php if (isset($product->step_order_level) && (int)$product->step_order_level > 0) {
                                                       echo $product->step_order_level;
                                                   } else if(!empty($product->min_order_level)){
                                                       echo $product->min_order_level;
                                                   }else {
                                                       echo '1';
                                                   } ?>"/>
                                        </span>
                                             <span class="quantity-controls js-recalculate">
                                                <i class="fa fa-plus quantity-controls quantity-plus"></i>
                                                <i class="fa fa-minus quantity-controls quantity-minus"></i>
                                               
                                            </span>
                                        </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group">
                                        <?php echo shopFunctionsF::getAddToCartButton($product->orderable); ?>
                                        </div>
                                        <noscript><input type="hidden" name="task" value="add"/></noscript> 
                                      <?php } }?>  
                                        
					</div>
					
					<input type="hidden" class="pname" value="<?php echo $product->product_name ?>"/>
					<input type="hidden" name="option" value="com_virtuemart"/>
					<input type="hidden" name="view" value="cart"/>
					<noscript><input type="hidden" name="task" value="add"/></noscript>
					<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
					<input type="hidden" name="virtuemart_category_id[]" value="<?php echo $product->virtuemart_category_id ?>"/>
				</form>
			</div>
			<?php
			
			}
		}
	}
}
?>