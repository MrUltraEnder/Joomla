<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$col= 1 ;
$pwidth= ' width'.floor ( 100 / $products_per_row );
if ($products_per_row > 1) { $float= "floatleft";}
else {$float="center";}
?>
 <script>
jQuery(document).ready(function() {
  // The slider being synced must be initialized first
  jQuery('#carousel').flexslider({
    animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
    itemWidth: 200,
    itemMargin: 0,
	prevText: "",     
    nextText: "",   
    asNavFor: '#slider'
  });
   
  jQuery('#slider').flexslider({
    animation: "fade",
    controlNav: false,
    animationLoop: false,
    slideshow: true,    
    slideshowSpeed: 12000,  
	animationSpeed: 800, 
    sync: "#carousel"
  });
});
</script>
<div id="products_example">	
<div id="slider" class="flexslider">
<ul id="products" class="slides">
<?php 
if ($display_style =="div") { 
JHTML::script( 'modules/mod_virtuemart_product/js/jquery.flexslider-min.js' );
?>


<?php 
$last = count($products)-1;
?>

 <li>
  <?php foreach ($products as $product) : ?>
			<?php
			if (!empty($product->images[0]) )
					$image = $product->images[0]->displayMediaFull('class="featuredProductImage" border="0"',false) ;
				else $image = '';
					echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,'class="img2"');
			?>
		<div class="caption">
		<h4 class="item_name product_title">
				<?php $url = JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .
                    $product->virtuemart_category_id); ?>

                <a href="<?php echo $url ?>"><?php echo shopFunctionsF::limitStringByWord($product->product_name,'30', '...'); ?></a> 
            </h4>  
			<div class="description">
				<?php  echo shopFunctionsF::limitStringByWord($product->product_s_desc, 150, '...') ?>
			</div>
			<div class="product_price <?php if (abs($product->prices['discountAmount']) > 0 && ($product->prices['salesPrice'] < $product->prices['salesPriceWithDiscount'] ) ){ echo 'sale';} ?>">     

				<?php 
                if ($show_price) {
                      if (!empty($product->prices['salesPriceWithDiscount'])) {
                        echo $currency->createPriceDiv ('salesPriceWithDiscount', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                    }
                    // echo $currency->priceDisplay($product->prices['salesPrice']);
                    if (!empty($product->prices['salesPrice'])) {
                        echo $currency->createPriceDiv ('salesPrice', '', $product->prices, FALSE, FALSE, 1.0, TRUE);
                    }
                  
                    // if ($product->prices['salesPriceWithDiscount']>0) echo $currency->priceDisplay($product->prices['salesPriceWithDiscount']);
                    
                } ?>
                </div>
			<div class="product_rating ratingbox">
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
					</div>
			<div class="product_addtocart">
				<?php 
                if ($show_addtocart) {
                    echo mod_virtuemart_product_override::addtocart ($product);
                } else { ?>
                    <button class="btn btn-primary"><?php echo JHTML::_ ('link', JRoute::_ ('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id), $image, array('title' => $product->product_name)); ?></button>
                <?php }
                $i++; ?>
            </div>
			
    	</div>
        
		<?php
            if ($col == $products_per_row && $products_per_row && $last) {
                echo "</li><li class='slide'>";
                $col= 1 ;
            } else {
                $col++;
            }
			$last--;
            endforeach; ?>
          </li>  

<?php
} ?>
</ul>
</div>

<?php 
$last = count($products)-1;
?>
<div id="carousel" class="flexslider">
        <ul class="slides">
 <?php foreach ($products as $product) : ?>
 <li>
 
			<?php
			if (!empty($product->images[0]) )
					$image = $product->images[0]->displayMediaThumb('class="browseProductImage featuredProductImage" border="0"',false) ;
				else $image = '';
					echo JHTML::_('link', JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->virtuemart_product_id.'&virtuemart_category_id='.$product->virtuemart_category_id),$image,'class="img2"');
			?>
		
		<?php
            if ($col == $products_per_row && $products_per_row && $last) {
                echo "</li><li>";
                $col= 1 ;
            } else {
                $col++;
            }
			$last--;
            endforeach; ?>
	</li>
</ul>	
</div>
</div>
<?php if ($footerText) : ?>
    <div class="vm-products_footer button-click">
        <a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category_id); ?>"><?php echo $footerText ?></a>
    </div>
<?php endif; ?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#products_example .pagination").owlCarousel({
	 	autoPlay : 36000,
		stopOnHover : true,
		navigation:true,
		pagination:false,
		scrollPerPage:true,
		paginationSpeed : 1000,
		goToFirstSpeed : 2000,
		singleItem : false,
		items : 4,
		itemsDesktop : [1000,3], //5 items between 1000px and 901px
		itemsDesktopSmall : [900,3], // betweem 900px and 601px
		itemsTablet: [600,3], //2 items between 600 and 0
		itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
		autoHeight : true,
		transitionStyle:"fade",
		navigationText: 	["",""]
	});
	jQuery('#products_example').addClass('wow zoomIn');

});
</script>
