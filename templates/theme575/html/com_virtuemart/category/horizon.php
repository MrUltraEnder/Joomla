<?php
/**
 *
 * Show the products in a category
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @author Max Milbers
 * @todo add pagination
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6556 2012-10-17 18:15:30Z kkmediaproduction $
 */

//vmdebug('$this->category',$this->category);
//vmdebug ('$this->category ' . $this->category->category_name);
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
JHTML::_ ('behavior.modal');
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/

$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { 
			jQuery(this).find('.orderlist').has('div').stop().show();
			jQuery(this).find('.activeOrder.block').append('<i class=\"fa fa-caret-up\"></i>');
			jQuery(this).find('.fa-caret-down').remove();
		},
		function() { 
			jQuery(this).find('.orderlist').has('div').stop().hide();
			jQuery(this).find('.activeOrder.block').append('<i class=\"fa fa-caret-down\"></i>');
			jQuery(this).find('.fa-caret-up').remove();
		}
	)
	jQuery('.orderlistcontainer .orderlist').each(function(){
	 	jQuery(this).parent().find('.activeOrder').addClass('block');            
	})

});

";

$document = JFactory::getDocument ();
$document->addScriptDeclaration ($js);
?>

<div class="page category-view">

	<?php if (!empty($this->keyword)) { ?>
		<div class="page_heading">
			<h3><?php echo JText::_('TM_VMTHEME_SEARCH_TERMS') . ' ' . $this->keyword; ?></h3>
		</div>
	<?php } else { ?>
		<div class="page_heading">
        <?php if ($this->category->category_name) { ?>
			<h1 class="page_title"><?php echo $this->category->category_name; ?></h1>
        <?php } ?>
		</div>
	<?php } ?>

	<?php if (empty($this->keyword) and !empty($this->category)) {	?>
     <?php if ($this->category->category_description) { ?>
		<div class="category-description">
			<?php echo $this->category->category_description; ?>
		</div>
         <?php } ?>
	<?php }

	/* Show child categories */
	if (VmConfig::get ('showCategory', 1) and empty($this->keyword)) {
		if (!empty($this->category->haschildren)) {

			// Category and Columns Counter
			$iCol = 1;
			$iCategory = 1;

			// Calculating Categories Per Row
			$categories_per_row = VmConfig::get ('categories_per_row', 3);
			$category_cellwidth = 'width:' . floor (100 / $categories_per_row) . '%'; ?>

			<div class="category-children categories-listing">
				<?php // Start the Output
				if (!empty($this->category->children)) {
					foreach ($this->category->children as $category) {

						// this is an indicator wether a row needs to be opened or not
						if ($iCol == 1) {
							?>
					<div class="row listing__grid">
						<?php }

						// Category Link
						$caturl = JRoute::_ ('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id, FALSE);

						// Show Category
						?>
						<div class="category__child item item__category" style="<?php echo $category_cellwidth; ?>">
							<div class="category_wrap">							
								<div class="item_image category_image">
									<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
										<?php echo $category->images[0]->displayMediaThumb ("", FALSE); ?>
									</a>
								</div>	
								<h5 class="item_name category_title">
									<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">
										<?php echo $category->category_name ?>
									</a>
								</h5>
							</div>				
						</div>
						<?php
						$iCategory++;

						// Do we need to close the current row now?
						if ($iCol == $categories_per_row) { ?>
					</div>
					<?php
							$iCol = 1;
						} else {
							$iCol++;
						}
						}
					}
					// Do we need a final closing row tag?
					if ($iCol != 1) { ?>
					</div>
			</div>
		<?php } 	
		} ?>
	<?php } ?>

<?php if (!empty($this->keyword)) {

	$category_id  = JRequest::getInt ('virtuemart_category_id', 0); ?>
	<form action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=category&limitstart=0', FALSE); ?>" method="get" class="form-inline search-form">
		<?php echo $this->searchcustom ?>
		<?php echo $this->searchcustomvalues ?>

		<!--BEGIN Search Box -->
		<div class="virtuemart_search form-group">
			<input name="keyword" class="inputbox form-control" type="text" size="20" value="<?php echo $this->keyword ?>"/>
			<input type="submit" value="<?php echo JText::_ ('COM_VIRTUEMART_SEARCH') ?>" class="btn btn-default button" onclick="this.form.keyword.focus();"/>
		</div>

		<input type="hidden" name="search" value="true"/>
		<input type="hidden" name="view" value="category"/>
		<input type="hidden" name="option" value="com_virtuemart"/>
		<input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>
	</form>
	<!-- End Search Box -->
<?php } ?>


<?php // Show child categories
if (!empty($this->products)) {	?>
	<div class="orderby-displaynumber row">
		<div class="col-sm-4">
			<?php echo $this->orderByList['orderby']; ?>
		</div>
        <div class="col-sm-4">
        <?php 
			if (!empty($this->orderByList['manufacturer'])) {
				echo $this->orderByList['manufacturer'];
			} ?>
        </div>
		<div class="col-sm-4 display-number">
			<span class="product-counter"><?php echo $this->vmPagination->getResultsCounter ();?></span>
			<?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?>
		</div>
	</div> <!-- end of orderby-displaynumber -->

	<div class="vm-category_pagination">
		<?php echo $this->vmPagination->getPagesLinks (); ?>
		<div class="pagination-counter"><?php echo $this->vmPagination->getPagesCounter (); ?></div>
	</div>

<div class="vm-category_product-listing products-listing horizon">

	<?php
	// Category and Columns Counter
	$iBrowseCol = 1;
	$iBrowseProduct = 1;

	// Calculating Products Per Row
	$BrowseProducts_per_row = 1;
	$Browsecellwidth = ' width: 100%';

	$BrowseTotalProducts = count($this->products);

	// Start the Output
	foreach ($this->products as $product) {

		// this is an indicator wether a row needs to be opened or not
		if ($iBrowseCol == 1) {
			?>

		<div class="rows listing__grid">
			<?php }				
			// Show Products
			?>
			<div class="product item item_product <?php if (abs($product->prices['discountAmount']) > 0 && ($product->prices['salesPrice'] < $product->prices['salesPriceWithDiscount'] ) ){ echo 'sale';} ?>	" style="<?php echo $Browsecellwidth; ?>">
				<div class="product_wrap">

						<?php
						 if (abs($product->prices['discountAmount']) > 0 && ($product->prices['salesPrice'] < $product->prices['salesPriceWithDiscount'] ) ): ?>							
							<span class="product_sale-label label label-success"><?php echo JText::_('TM_VMTHEME_SALE') ?></span>
						<?php endif; ?>

						<div class="item_image product_image">
						    <a title="<?php echo $product->product_name ?>"  href="<?php echo $product->link; ?>">
								<?php echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false); ?>
							 </a>
						</div>

						<div class="product_content">

							<h4 class="item_name product_title">
								<?php echo JHTML::link ($product->link, $product->product_name); ?>
							</h4>

							<div class="product_rating ratingbox">
								<?php // Output: Average Product Rating
								if ($this->showRating) {
									$maxrating = VmConfig::get('vm_maximum_rating_scale', 5);

									if (empty($product->rating)) { ?>
										<div class="vote">
											<span class="rating-text"><?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . JText::_('COM_VIRTUEMART_UNRATED') ?></span>
										</div>
									<?php
									} else {
										//$ratingwidth = $product->rating * 12; //I don't use round as percetntage with works perfect, as for me
										?>
										<div class="vote">
											<span class="rating-icons">
				                            <?php 
				                            for ($i = 1; $i <= 5 ; $i ++ ) { 		                            	
				                            	if ($i <= $product->rating) {
				                            		echo '<i class="fa fa-star"></i> ';
				                            	} else {
				                            		echo '<i class="fa fa-star-o"></i> ';
				                            	}                     	
				                            } ?>
				                            </span>

			                                <!-- <span title=" <?php /*echo (JText::_("COM_VIRTUEMART_RATING_TITLE") . round($product->rating) . '/' . $maxrating) ?>" class="category-ratingbox" style="display:inline-block;">
			                                    <span class="stars-orange" style="width:<?php echo $ratingwidth.'px';*/ ?>">
			                                    </span>
			                                </span> -->
			                                <div class="clearfix"></div>
			                            </div>
			                    <?php
									}
								} ?>
							</div>

							<div class="product_desc-short">
								<?php // Product Short Description
								if (!empty($product->product_s_desc)) { ?>
									<?php echo shopFunctionsF::limitStringByWord ($product->product_s_desc, 120, '...') ?>
								<?php } ?>
							</div>

							
							<?php if ( VmConfig::get ('display_stock', 1)) { ?>								
								<div class="product_stock">
									<span class="stock-level"><?php echo JText::_ ('COM_VIRTUEMART_STOCK_LEVEL_DISPLAY_TITLE_TIP') ?></span>
									<span class="vmicon vm2-<?php echo $product->stock->stock_level ?>" title="<?php echo $product->stock->stock_tip ?>"></span>
								</div>
							<?php } ?>
						</div>
                        <?php //echo $rowsHeight[$row]['customs'] ?>
                            <div class="vm3pr product_addtocart">
                                <div class="product_price" id="productPrice<?php echo $product->virtuemart_product_id ?>">
                                    <?php
                                    if ($this->show_prices == '1') {
                                        if ($product->prices['salesPrice']<=0 and VmConfig::get ('askprice', 1) and  !$product->images[0]->file_is_downloadable) {
                                            echo JText::_ ('COM_VIRTUEMART_PRODUCT_ASKPRICE');
                                        }
                                        //todo add config settings
                                        echo $this->currency->createPriceDiv ('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices);
                                        
                                        
                                        if (round($product->prices['salesPriceWithDiscount'],$this->currency->_priceConfig['salesPrice'][1]) != $product->prices['salesPrice']) {
                                            echo $this->currency->createPriceDiv ('salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
                                        }
                                        
                                    } ?>
    
                                </div>	
								<?php
                                echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$product)); ?>
                            </div>
                        <div class="clearfix"></div>
				</div>
			</div> 
			<?php

			// Do we need to close the current row now?
			if ($iBrowseCol == $BrowseProducts_per_row || $iBrowseProduct == $BrowseTotalProducts) {
				?>
		   </div> 

			<?php $iBrowseCol = 1;
			} else {
				$iBrowseCol++;
			}

		$iBrowseProduct++;
	} 

	if ($iBrowseCol != 1) {		?>
	<?php } ?>

</div>

<div class="vm-category_pagination">
	<?php echo $this->vmPagination->getPagesLinks (); ?>
	<div class="pagination-counter"><?php echo $this->vmPagination->getPagesCounter (); ?></div>
</div>

	<?php
} elseif (!empty($this->keyword)) {
	echo JText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : '');
}
?>
</div>