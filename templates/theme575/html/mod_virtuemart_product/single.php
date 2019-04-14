<?php // no direct access
defined ('_JEXEC') or die('Restricted access');
// add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
vmJsApi::jPrice();

$doc = JFactory::getDocument(); 
include_once JPATH_THEMES.'/'.$doc->template.'/html/mod_virtuemart_product/helper.php'; // load helper.php
$col= 1 ;

$pwidth = ' width:' . floor (100 / $products_per_row) . '%';
?>

<div id="slider" class="vm-products <?php echo $params->get ('moduleclass_sfx') ? 'vm-products__' . $params->get ('moduleclass_sfx') : '' ?>">

	<!-- Header text -->
	<?php if ($headerText): ?>
		<div class="header-text">
			<?php echo $headerText ?>
		</div>
	<?php endif ?>


	<!-- Products listing -->
		<?php 
        $last = count($products)-1;
        ?>
		<?php if ($display_style == "div") { 
		?>
		<?php  $count=1; ?>
			<!-- Display style: DIV -->
			<div  class="products-listing listing__grid <?php echo 'owl-demo-'.$params->get ('moduleclass_sfx'); ?>">
				<div class="rows">
					<?php 
					$i = 0;
					foreach ($products as $product): 					
					?>
						<div class="item item_product <?php if (abs($product->prices['discountAmount']) > 0 && ($product->prices['salesPrice'] < $product->prices['salesPriceWithDiscount'] ) ){ echo 'sale';} ?>	" style="<?php echo $pwidth; ?>">

							<div class="product_wrap">
								<?php
								
								 if (abs($product->prices['discountAmount']) > 0 && ($product->prices['salesPrice'] < $product->prices['salesPriceWithDiscount'] ) ): ?>							
                                    <span class="product_sale-label label label-success"><?php echo JText::_('TM_VMTHEME_SALE') ?></span>
                                <?php endif; ?>
								<div class="item_image product_image">
									<?php 
									if (!empty($product->images[0])) {
										$image = $product->images[0]->displayMediaThumb ('class="featuredProductImage"', FALSE);
									} else {
										$image = '';
									}

									echo JHTML::_ ('link', JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name));
									?>
								</div>
								
								<h4 class="item_name product_title">
									<?php $url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
										$product->virtuemart_category_id); ?>

									<a href="<?php echo $url ?>"><?php echo shopFunctionsF::limitStringByWord($product->product_name,'30', '...'); ?></a> 
								</h4>  
                            <div class="product_price">     

								<?php 
								if ($show_price) {
									
									// echo $currency->priceDisplay($product->prices['salesPrice']);
									if (!empty($product->prices['salesPrice'])) {
										echo $currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
									}
									if (!empty($product->prices['salesPriceWithDiscount'])) {
										echo $currency->createPriceDiv ('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
									}
									// if ($product->prices['salesPriceWithDiscount']>0) echo $currency->priceDisplay($product->prices['salesPriceWithDiscount']);
									
								} ?>
								</div>
								<?php /*?><div class="product_rating ratingbox">
						<?php // Output: Average Product Rating
							$ratingModel = VmModel::getModel('ratings');
							$rating = $ratingModel->getRatingByProduct($product->virtuemart_product_id);
						 ?>
						
                        <?php                    
						//if ($this->showRating) {
							$maxrating = VmConfig::get('vm_maximum_rating_scale', 5);

							if (empty( $rating)) { ?>
								<div class="vote">
									<span class="rating-text"><?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . JText::_('COM_VIRTUEMART_UNRATED') ?></span>
								</div>
							<?php
							} else {
								?>
								<div class="vote">
									<span class="rating-icons">
		                            <?php 
		                            for ($i = 1; $i <= 5 ; $i ++ ) { 		                            	
		                            	if ($i <= $rating->rating) {
		                            		echo '<i class="fa fa-star"></i> ';
		                            	} else {
		                            		echo '<i class="fa fa-star-o"></i> ';
		                            	}                     	
		                            } ?>
		                            </span>
		                        </div>
		                <?php
							//}
						} ?>
					</div><?php */?>
								
								<?php /*?><div class="product_addtocart">
									<?php 
									if ($show_addtocart) {
										echo mod_virtuemart_product_override::addtocart ($product);
									} else { ?>
										<button class="btn btn-primary"><?php echo JHTML::_ ('link', JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name)); ?></button>
									<?php }
									$i++; ?>
								</div><?php */?>
                                <?php /*?><div class="item_name product_desc">
									<?php $url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
										$product->virtuemart_category_id); ?>

									<a href="<?php echo $url ?>"><?php echo JText::_('TM_DETAILS'); ?></a> 
								</div>  <?php */?>
							</div>
						</div>
						
						<?php
						if ($col == $products_per_row && $products_per_row && $last) {
							echo "</div><div class='rows'>";
							$col= 1 ;
						} else {
							$col++;
						}
						$last--;
						endforeach; ?>
				</div>
			</div>

		<?php }?>


	<!-- Footer text -->
	<?php if ($footerText) : ?>
		<div class="vm-products_footer">
			<?php echo $footerText ?>
		</div>
	<?php endif; ?>

</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#slider .<?php echo 'owl-demo-'.$params->get ('moduleclass_sfx'); ?>").owlCarousel({
	autoPlay : 122000,
	stopOnHover : true,
	navigation:true,
	pagination:false,
	paginationSpeed : 1000,
	goToFirstSpeed : 2000,
	singleItem : false,
	items : 4,
	itemsDesktop : [1000,3], //5 items between 1000px and 901px
	itemsDesktopSmall : [900,3], // betweem 900px and 601px
	itemsTablet: [600,2], //2 items between 600 and 0
	itemsMobile : [480,1], // itemsMobile disabled - inherit from itemsTablet option
	autoHeight : true,
	transitionStyle:"fade",
	navigationText: 	["",""]
	});
	jQuery('.mod_virtuemart_product .vm-products__<?php echo $params->get ('moduleclass_sfx'); ?> .rows').addClass('wow zoomIn');

});
</script>
