<?php
/**
* sublayout products
*
* @package	VirtueMart
* @author Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2014 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
* @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
*/

defined('_JEXEC') or die('Restricted access');

$product = $viewData['product'];
$position = $viewData['position'];
$customTitle = isset($viewData['customTitle'])? $viewData['customTitle']: false;;
if(isset($viewData['class'])){
	$class = $viewData['class'];
} else {
	$class = 'product-fields';
}

if (!empty($product->customfieldsSorted[$position])) {
	?>
	<div class="<?php echo $class?> related-product">
    <div class="product_related-products product-section">
	<h4 class="product-section_title"><?php echo JText::_('COM_VIRTUEMART_RELATED_PRODUCTS'); ?></h4>
	<div class="products-listing">
	<div class="listing__grid" id="owl-demo">	

		<?php 
		$custom_title = null;
		foreach ($product->customfieldsSorted[$position] as $field) {
			if ( $field->is_hidden ) //OSP http://forum.virtuemart.net/index.php?topic=99320.0
			continue;
			?><div class="item item__product product-field-type-<?php echo $field->field_type ?>">
				<?php if (!empty($field->display)){
					?><span class="product-field-display"><?php echo $field->display ?></span><?php
				}
				?>
			</div>
            <?php }?>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#owl-demo").owlCarousel({
            autoPlay : 10200,
            stopOnHover : true,
            navigation:true,
			rewindNav: false,
            pagination:false,
            paginationSpeed : 1000,
            goToFirstSpeed : 2000,
            singleItem : false,
            items : 4,
            itemsDesktop : [1000,3], //5 items between 1000px and 901px
            itemsDesktopSmall : [900,3], // betweem 900px and 601px
            itemsTablet: [600,2], //2 items between 600 and 0
            itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
            autoHeight : true,
            transitionStyle:"fade",
            navigationText: 	["",""]
            });
        });
        </script>
	</div>
<?php
} ?>