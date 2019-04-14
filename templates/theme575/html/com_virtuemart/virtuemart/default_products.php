<?php defined('_JEXEC') or die('Restricted access'); 

	$app = JFactory::getApplication();
	$templateparams	= $app->getTemplate(true)->params;
	$tabs = $templateparams->get('home_tabs');

	if ($tabs == '1') { 
		$listing_class = 'tab-pane fade in';
		$link_params = 'role="tab" data-toggle="tab"';
	} else {
		$listing_class = 'product_listing-section';
		$link_params = '';
	} ?>

	<?php if ($tabs == '1') { ?>
		<ul class="nav nav-tabs" role="tablist">
		<?php foreach ($this->products as $type => $productList ) { ?>	
			<li class="<?php echo $type == 'featured' ? 'active' : '' ?>">
				<h2><a href="#<?php echo $type ?>" <?php echo $link_params; ?>><?php echo JText::_('COM_VIRTUEMART_'.$type.'_PRODUCT') ?></a></h2>
			</li>
		<?php } ?>
		</ul>
	<?php } ?>

	<?php if ($tabs == '1') { ?>
		<div class="tab-content">
	<?php } ?>
	
		<?php foreach ($this->products as $type => $productList ) {
			// Calculating Products Per Row
			$products_per_row = VmConfig::get ( 'homepage_products_per_row', 3 ) ;
			$cellwidth = 'width:'.floor ( 100 / $products_per_row  ) . '%';

			// Category and Columns Counter
			$col = 1;
			$nb = 1; ?>			

			<div class="<?php echo $listing_class; ?> <?php echo $type ?>-view <?php echo $type == 'featured' ? 'active' : '' ?>" id="<?php echo $type ?>">

				<?php if ($tabs == '0') { ?>
					<h2 class="product_listing-heading"><?php echo JText::_('COM_VIRTUEMART_'.$type.'_PRODUCT') ?></h2>
				<?php } ?>

				<div class="products-listing">

					<?php // Start the Output
					foreach ( $productList as $product ) {
						// this is an indicator wether a row needs to be opened or not
						if ($col == 1) { ?>
							<div class="row listing__grid">
						<?php } ?>

							<div class="item item__product product"  <?php if (abs($product->prices['discountAmount']) > 0 && ($product->prices['salesPrice'] < $product->prices['salesPriceWithDiscount'] ) ){ echo 'sale';} ?> style="<?php echo $cellwidth; ?>">
								<div class="product_wrap">

									<?php
									 if (abs($product->prices['discountAmount']) > 0 && ($product->prices['salesPrice'] < $product->prices['salesPriceWithDiscount'] ) ): ?>							
										<span class="product_sale-label label label-success"><?php echo JText::_('TM_VMTHEME_SALE') ?></span>
									<?php endif; ?>
									<div class="item_image product_image">
										<?php // Product Image
										if ($product->images) {
											// echo JHTML::_ ( 'link', JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id, FALSE ), $product->images[0]->displayMediaThumb( 'class="featuredProductImage"',true,'class="modal"' ) );

											// echo   $product->images[0]->displayMediaThumb( 'class="featuredProductImage"',true,'class="swipebox"' ) ; ?>

											<a title="<?php echo $product->product_name ?>"  href="<?php echo $product->link; ?>">
												<?php echo $product->images[0]->displayMediaThumb('class="browseProductImage"', false); ?>
											</a>
										<?php } ?>
									</div>

									<h3 class="item_name product_title">
										<?php // Product Name
										echo JHTML::link ( JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id, FALSE ), $product->product_name, array ('title' => $product->product_name ) ); ?>
									</h3>

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

									<div class="product_details-link">
										<?php // Product Details Button
										echo JHTML::link ( JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id , FALSE), JText::_ ( 'COM_VIRTUEMART_PRODUCT_DETAILS' ), array ('title' => $product->product_name, 'class' => 'product-details btn btn-primary' ) );
										?>
									</div>
								</div>
							</div>

						<?php						

						// Do we need to close the current row now?
						if ($col == $products_per_row) { ?>
							</div>
							<?php
							$col = 1;
						} elseif ($nb == count($productList) && $col != $products_per_row) {
							echo '</div>';
						} else {
							$col ++;
						}						
						$nb ++;			
					} ?>
				</div>
			</div>

		<?php } ?>

	<?php if ($tabs == '1') { ?>
		</div>
	<?php }