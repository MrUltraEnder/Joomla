<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 6188 2012-06-29 09:38:30Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument ();
$imageJS = '

jQuery(window).load(function() {

	console.log(jQuery(window).width());

	if (jQuery(window).width() < 1183) {
		jQuery("#product_image img").attr("height","352px");
		jQuery("#product_image img").attr("width","352px");
	};

	jQuery("#product_image").jqzoom({
		zoomType: 		"innerzoom",
		preloadText: 	"",
		lens: false,
		alwaysOn: false,
		title:			false
	});
});


jQuery(document).ready(function() {	

	jQuery(".swipebox").swipebox({
		useCSS : true,
		hideBarsDelay : 30000,
		hideBarsOnMobile : false
	});

});
';
$document->addScriptDeclaration ($imageJS);

$count_images = count ($this->product->images);
$start_image = VmConfig::get('add_img_main', 1) ? 0 : 1;

if (!empty($this->product->images)) {

	$image = $this->product->images[0];	?>
	<div class="product_image__main item_image main-image">
		<a href="<?php echo $image->file_url; ?>" class="swipebox product_image_zoom" rel="vm-additional-images__mobile"><i class="fa fa-search-plus"></i></a>
		
		<?php 
		for ($img = 1; $img < $count_images; $img++) { ?>
			<a href="<?php echo $this->product->images[$img]->file_url; ?>"  class="product-image swipebox image-<?php echo $img; ?>" title="<?php echo $this->product->images[$img]->file_meta; ?>" rel="vm-additional-images__mobile"></a>				
		<?php }	?>		

		<?php echo $image->displayMediaFull("width='100%' ", true,"id='product_image' class='jqzoom' rel='vm-additional-images'"); ?>
	</div>	

	<?php if ($count_images > 1) { ?>

	<div class="additional-images product_images__add" id="owl-demo1">
		<?php			
		for ($i = $start_image; $i < $count_images; $i++) {
			$image = $this->product->images[$i]; 
			if ($i == $start_image) {
				$activeClass = 'zoomThumbActive';
			} else {
				$activeClass = '';
			}
			?>
			<div class="item_image-wrap">					
				<?php
				if(VmConfig::get('add_img_main', 1)) {	?>						
					<a class="product-image item_image <?php echo $activeClass; ?>" href="javascript:void(0);" 
					rel="{gallery: 'vm-additional-images', smallimage: '<?php echo JURI::base() . $image->file_url; ?>',largeimage: '<?php echo JURI::base() . $image->file_url; ?>'}">  <img src="<?php echo $image->file_url_thumb; ?>"> 
					</a>
				<?php
				} else {
					echo $image->displayMediaThumb("",true,"rel='vm-additional-images'");
				} ?>
			</div>
		<?php } ?>
	</div>
            <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#owl-demo1.additional-images").owlCarousel({
            autoPlay : false,
            stopOnHover : true,
			rewindNav: false,
            navigation:true,
            pagination:false,
			scrollPerPage:true,
            paginationSpeed : 1000,
            goToFirstSpeed : 2000,
            singleItem : false,
            items : 3,
            itemsDesktop : [1000,3], //5 items between 1000px and 901px
            itemsDesktopSmall : [900,3], // betweem 900px and 601px
            itemsTablet: [600,3], //2 items between 600 and 0
            itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
            autoHeight : true,
            transitionStyle:"fade",
            navigationText: 	["",""]
            });
        });
        </script>

	<?php };
} ?>