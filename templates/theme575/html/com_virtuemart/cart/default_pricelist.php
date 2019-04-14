<?php defined ('_JEXEC') or die('Restricted access');
/**
 *
 * Layout for the shopping cart
 *
 * @package    VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @author Patrick Kohl
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 */
?>


<section class="cart-section cart-section_cart-listing">
	<header>
		<h2><?php echo JText::_('TPL_VMTHEME_CART_STEP3'); ?></h2>
	</header>
	<!-- Cart products listing -->
	<table class="table table-bordered cart-summary">
		<!-- Table heading -->
		<thead>
			<th class="table-column__name"><?php echo JText::_ ('COM_VIRTUEMART_CART_NAME') ?></th>
			<th class="table-column__sku"><?php echo JText::_ ('COM_VIRTUEMART_CART_SKU') ?></th>
			<th class="table-column__price"><?php echo JText::_ ('COM_VIRTUEMART_CART_PRICE') ?></th>
			<th class="table-column__qty"><?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY') ?> / <?php echo JText::_ ('COM_VIRTUEMART_CART_ACTION') ?></th>
			<?php if (VmConfig::get ('show_tax')) { ?>
				<th class="table-column__tax"><?php  echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT'); ?></th>
			<?php } ?>
			<th class="table-column__discount"><?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?></th>
			<th class="table-column__total"><?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?></th>
		</thead>

		<tbody>
			<?php
			$i = 1;
			// 		vmdebug('$this->cart->products',$this->cart->products);

			// Products table
			foreach ($this->cart->products as $pkey => $prow) { 
				?>
				<tr class="cart-product">
					<!-- Product name -->
					<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_NAME') ?>" class="cart-product_name">			
						<?php if ($prow->virtuemart_media_id) { ?>
							<div class="product-image">
								<?php 
									echo $prow->images[0]->displayMediaThumb ('', FALSE);
									//print_r($prow);
								?>	
							</div>
						<?php } ?>
						<span class="product-name">
							<?php echo JHTML::link ($prow->url, $prow->product_name);
							echo $this->customfieldsModel->CustomsFieldCartDisplay ($prow); ?>
						</span>
					</td>

					<!-- Product SKU -->
					<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SKU') ?>" class="cart-product_sku"><?php  echo $prow->product_sku ?></td>

					<!-- product price -->
					<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_PRICE') ?>" class="cart-product_price">
						<?php
							echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $prow->prices, FALSE, FALSE);
						//echo $prow->salesPrice ;
						?>
					</td>

					<!-- Product quantity -->
					<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_QUANTITY') ?>" class="cart-product_qty">
						<?php
						//$step=$prow->min_order_level;
						if ($prow->step_order_level)
							$step=$prow->step_order_level;
						else
							$step=1;
						if($step==0)
							$step=1;
						$alert=JText::sprintf ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED', $step); ?>

			            <script type="text/javascript">
							function check<?php echo $step?>(obj) {
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

						<div class="qty-control">
							<input type="number" title="<?php echo  JText::_('COM_VIRTUEMART_CART_UPDATE') ?>" class="form-control quantity-input js-recalculate vm2-add_quantity" size="4" maxlength="5" name="quantity[<?php echo $prow->cart_item_id ?>]" value="<?php echo $prow->quantity ?>" />

							<button type="submit" class="vm2-add_quantity_cart" name="updatecart.<?php echo $pkey ?>" title="<?php echo  JText::_ ('COM_VIRTUEMART_CART_UPDATE') ?>"><i class="fa fa-refresh"></i>           
							<button type="submit" class="vm2-remove_from_cart" name="delete.<?php echo $pkey ?>" title="<?php echo vmText::_ ('COM_VIRTUEMART_CART_DELETE') ?>"><i class="fa fa-times"></i></button>	 
						</div>
					</td>

					<!-- product tax -->
					<?php if (VmConfig::get ('show_tax')) { ?>
					<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?>" class="cart-product_tax"><?php echo "<span class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $prow->prices, FALSE, FALSE, $prow->quantity) . "</span>" ?></td>
					<?php } ?>

					<td  data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?>" class="cart-product_discount"><?php echo "<span class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $prow->prices, FALSE, FALSE, $prow->quantity) . "</span>" ?></td>

					<td  data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>" class="cart-product_total">
						<?php
		if (VmConfig::get ('checkout_show_origprice', 1) && !empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceWithTax'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceWithTax', '', $prow->prices, TRUE, FALSE, $prow->quantity) . '</span><br />';
		}
		elseif (VmConfig::get ('checkout_show_origprice', 1) && empty($prow->prices['basePriceWithTax']) && $prow->prices['basePriceVariant'] != $prow->prices['salesPrice']) {
			echo '<span class="line-through">' . $this->currencyDisplay->createPriceDiv ('basePriceVariant', '', $prow->prices, TRUE, FALSE, $prow->quantity) . '</span><br />';
		}
		echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $prow->prices, FALSE, FALSE, $prow->quantity) ?>
					</td>
				</tr>
				<?php $i = ($i==1) ? 2 : 1;	
			} ?>
		</tbody>
	</table>

	<!-- Cart total -->
	<table class="table table-bordered cart-summary">
		<thead>
			<th class="table-column__prices-total"><?php echo JText::_ ('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL'); ?></th>
			<?php if (VmConfig::get ('show_tax')) { ?>
				<th class="table-column__tax"></th>
			<?php } ?>
			<th class="table-column__discount"></th>
			<th class="table-column__total"></th>
		</thead>	
		<tbody>
			<tr class="cart-prices-total">	
				<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_ORDER_PRINT_PRODUCT_PRICES_TOTAL'); ?>"></td>		
				<?php if (VmConfig::get ('show_tax')) { ?>
					<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?>" class="cart-prices-total__tax"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('taxAmount', '', $this->cart->cartPrices, FALSE) . "</span>" ?></td>
				<?php } ?>
				<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?>" class="cart-prices-total__discount"><?php echo "<span  class='priceColor2'>" . $this->currencyDisplay->createPriceDiv ('discountAmount', '', $this->cart->cartPrices, FALSE) . "</span>" ?></td>
				<td data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>" class="cart-prices-total__price"><?php echo $this->currencyDisplay->createPriceDiv ('salesPrice', '', $this->cart->cartPrices, FALSE) ?></td>
			</tr>
		</tbody>
	</table>
</section>


<?php if (count($this->cart->cartData['DBTaxRulesBill']) > 0 || count($this->cart->cartData['taxRulesBill']) > 0 || count($this->cart->cartData['DATaxRulesBill']) > 0) { ?>
<section class="cart-section cart-section_tax">
	<header>
		<h2><?php echo JText::_('TPL_VMTHEME_CART_STEP4'); ?></h2>
	</header>

	<!-- Tax rules -->
	<table class="table table-bordered cart-summary">		
		<tbody>
			<?php
			// Tax rules
			foreach ($this->cart->cartData['DBTaxRulesBill'] as $rule) { ?>
				<tr class="cart-tax-rules cart-tax-rules__db">
					<td class="table-column__tax-name" data-title="<?php echo JText::_ ('TPL_VMTHEME_CART_TAX_NAME') ?>"><?php echo $rule['calc_name'] ?> </td>

					<?php if (VmConfig::get ('show_tax')) { ?>
						<td class="table-column__tax" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?>"></td>
					<?php } ?>
					<td class="table-column__discount" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?>"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?>/td>
					<td class="table-column__total" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></td>
				</tr>
					<?php
					if ($i) {
						$i = 1;
					} else {
						$i = 0;
					}
			} ?>

			<?php
			// Tax rules
			foreach ($this->cart->cartData['taxRulesBill'] as $rule) { ?>
				<tr class="cart-tax-rules">
					<td class="table-column__tax-name" data-title="<?php echo JText::_ ('TPL_VMTHEME_CART_TAX_NAME') ?>"><?php echo $rule['calc_name'] ?> </td>
					<?php if (VmConfig::get ('show_tax')) { ?>
						<td class="table-column__tax" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?>"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></td>
					<?php } ?>
					<td class="table-column__discount" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?>"><?php ?> </td>
					<td class="table-column__total" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?></td>
				</tr>
				<?php
				if ($i) {
					$i = 1;
				} else {
					$i = 0;
				}
			}



			// Tax rules
			foreach ($this->cart->cartData['DATaxRulesBill'] as $rule) { ?>
				<tr class="cart-tax-rules cart-tax-rules__da">
					<td class="table-column__tax-name" data-title="<?php echo JText::_ ('TPL_VMTHEME_CART_TAX_NAME') ?>"><?php echo   $rule['calc_name'] ?> </td>

					<?php if (VmConfig::get ('show_tax')) { ?>
						<td class="table-column__tax" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_TAX_AMOUNT') ?>"></td>
					<?php } ?>
					<td class="table-column__discount" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_SUBTOTAL_DISCOUNT_AMOUNT') ?>"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?> </td>
					<td class="table-column__total" data-title="<?php echo JText::_ ('COM_VIRTUEMART_CART_TOTAL') ?>"><?php echo $this->currencyDisplay->createPriceDiv ($rule['virtuemart_calc_id'] . 'Diff', '', $this->cart->cartPrices[$rule['virtuemart_calc_id'] . 'Diff'], FALSE); ?> </td>
				</tr>
				<?php
				if ($i) {
					$i = 1;
				} else {
					$i = 0;
				}
			} ?>
		</tbody>
	</table>
</section>
<?php };?>

