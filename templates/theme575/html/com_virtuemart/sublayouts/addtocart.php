<?php
/**
 *
 * Show the product details page
 *
 * @package    VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_addtocart.php 7833 2014-04-09 15:04:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$product = $viewData['product'];

if(isset($viewData['rowHeights'])){
	$rowHeights = $viewData['rowHeights'];
} else {
	$rowHeights['customfields'] = TRUE;
}

$addtoCartButton = '';
if(!VmConfig::get('use_as_catalog', 0)){
	if($product->addToCartButton){
		$addtoCartButton = $product->addToCartButton;
	} else {
		$addtoCartButton = shopFunctionsF::getAddToCartButton ($product->orderable);
	}

}
$position = 'addtocart';
//if (!empty($product->customfieldsSorted[$position]) or !empty($addtoCartButton)) {
if (isset($product->step_order_level))
	$step=$product->step_order_level;
else
	$step=1;
if($step==0)
	$step=1;

?>

	<div class="addtocart-area www">
		<form method="post" class="product js-recalculate" action="<?php echo JRoute::_ ('index.php',false); ?>">
        <input name="quantity" type="hidden" value="<?php echo $step ?>" />
			<?php

			if(!empty($rowHeights['customfields'])) echo shopFunctionsF::renderVmSubLayout('customfieldscategory',array('product'=>$product,'position'=>'addtocart'));

			if (!VmConfig::get('use_as_catalog', 0)  ) { ?>

				<div class="addtocart-bar">
				<?php
				// Display the quantity box
				$stockhandle = VmConfig::get ('stockhandle', 'none');
				if (($stockhandle == 'disableit' or $stockhandle == 'disableadd') and ($product->product_in_stock - $product->product_ordered) < 1) { ?>
                <div class="form-group">
					<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="addtocart-button notify"><?php echo vmText::_ ('COM_VIRTUEMART_CART_NOTIFY') ?></a>
				</div>	
					<?php
				} else {
					
				if (!empty($product->customfieldsSorted[$position])) { 
					//echo '1'; ?>
					 <div class="form-group">
						<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id); ?>" class="addtocart-button select"><?php echo vmText::_ ('TM_VIRTUEMART_CART_SELECT') ?></a>
					</div>		
				<?php }else {
					//echo '0';
					$tmpPrice = (float) $product->prices['costPrice'];
					if (!( VmConfig::get('askprice', true) and empty($tmpPrice) ) ) { ?>
						<?php if ($product->orderable) { ?>
							<!-- <label for="quantity<?php echo $product->virtuemart_product_id; ?>" class="quantity_box"><?php echo vmText::_ ('COM_VIRTUEMART_CART_QUANTITY'); ?>: </label> -->
							<div class="form-inline category">
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
						<?php }?>

						<div class="clearfix"></div>
						<div class="form-group">
							<?php echo $addtoCartButton ?>
						</div>
						<noscript><input type="hidden" name="task" value="add"/></noscript> <?php
					} 
				}
	
					
					
				} ?>

				</div><?php
			} ?>
			<input type="hidden" name="option" value="com_virtuemart"/>
			<input type="hidden" name="view" value="cart"/>
			<input type="hidden" name="virtuemart_product_id[]" value="<?php echo $product->virtuemart_product_id ?>"/>
			<input type="hidden" class="pname" value="<?php echo htmlentities($product->product_name, ENT_QUOTES, 'utf-8') ?>"/>
			<?php
			$itemId=vRequest::getInt('Itemid',false);
			if($itemId){
				echo '<input type="hidden" name="Itemid" value="'.$itemId.'"/>';
			} ?>
		</form>

	</div>

<?php // }
?>