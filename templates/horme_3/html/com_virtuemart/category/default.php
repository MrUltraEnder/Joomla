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
 * @version $Id: default.php 8811 2015-03-30 23:11:08Z Milbo $
 */

defined ('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$category_id  = vRequest::getInt ('virtuemart_category_id', 0);
$manufacturer_id  = vRequest::getInt ('virtuemart_manufacturer_id', 0);
$sort_dir = vRequest::getInt ('dir');
if (vRequest::getInt('dynamic',false) and vRequest::getInt('virtuemart_product_id',false)) {
	if (!empty($this->products)) {
		if($this->fallback){
			$p = $this->products;
			$this->products = array();
			$this->products[0] = $p;
			vmdebug('Refallback');
		}

		echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));

	}

	return ;
}
?>
<div class="category-view">
<?php
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";
//vmJsApi::addJScript('vm.hover',$js);
?>
	<h1 class="page-header"><?php echo vmText::_($this->category->category_name); ?></h1>
	<?php if ($this->show_store_desc and !empty($this->vendor->vendor_store_desc)) : ?>
 	<div class="vendor-store-desc">
 		<?php echo $this->vendor->vendor_store_desc; ?>
 	</div>
  <?php endif; ?>

	<?php if (!empty($this->showcategory_desc) and empty($this->keyword)) : ?>
		<?php if (!empty($this->category->category_description)) : ?>
		<div class="category_description">
			<?php echo $this->category->category_description; ?>
		</div>
		<hr>
		<?php endif; ?>
	<?php endif; ?>

	<?php	if(!empty($this->manu_descr)) :	?>
	<div class="manufacturer-description">
		<?php echo $this->manu_descr; ?>
	</div>
	<?php endif;?>

	<?php	// Show child categories
	if ($this->showcategory and empty($this->keyword) and $manufacturer_id == 0) {
		if (!empty($this->category->haschildren)) {
			echo ShopFunctionsF::renderVmSubLayout('categories',array('categories'=>$this->category->children, 'categories_per_row'=>$this->categories_per_row));
	    echo '<hr>';
		}
	}

	if (!empty($this->products) or ($this->showsearch or $this->keyword !== false)) {
	?>
	<div class="browse-view">
	<?php

	if ($this->showsearch or $this->keyword !== false) {
		//id taken in the view.html.php could be modified
		$category_id  = vRequest::getInt ('virtuemart_category_id', 0); ?>
  	<!--BEGIN Search Box -->
  	<div class="virtuemart_search">
  		<form action="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=category&limitstart=0', FALSE); ?>" method="get">
  			<?php if(!empty($this->searchCustomList)) { ?>
  			<div class="vm-search-custom-list">
  				<?php echo $this->searchCustomList ?>
  			</div>
  			<?php } ?>

  			<?php if(!empty($this->searchCustomValues)) { ?>
  			<div class="vm-search-custom-values">
  				<?php echo $this->searchCustomValues ?>
  			</div>
  			<?php } ?>
  			<div class="vm-search-custom-search-input input-group form-group">
  				<input name="keyword" class="inputbox" type="text" size="40" value="<?php echo $this->keyword ?>"/>
          <span class="input-group-btn">
  				  <button type="submit" class="button btn-primary"><?php echo vmText::_ ('COM_VIRTUEMART_SEARCH') ?></button>
          </span>
  				<?php //echo VmHtml::checkbox ('searchAllCats', (int)$this->searchAllCats, 1, 0, 'class="changeSendForm"'); ?>
  			</div>
        <div class="vm-search-descr text-warning">
          <p><?php echo vmText::_('COM_VM_SEARCH_DESC') ?></p>
        </div>
  			<!-- input type="hidden" name="showsearch" value="true"/ -->
  			<input type="hidden" name="view" value="category"/>
  			<input type="hidden" name="option" value="com_virtuemart"/>
  			<input type="hidden" name="virtuemart_category_id" value="<?php echo $category_id; ?>"/>
  			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>"/>
  		</form>
  	</div>
  	<!-- End Search Box -->
  	<?php  } ?>

  <?php
  	$j = 'jQuery(document).ready(function() {

    jQuery(".changeSendForm")
    	.off("change",Virtuemart.sendCurrForm)
        .on("change",Virtuemart.sendCurrForm);
    })';

  	vmJsApi::addJScript('sendFormChange',$j);
  ?>

  	<?php
  	  if ( VmConfig::get ('show_manufacturers',1) ) {
  	    $col = "col-sm-4";
  	  } else {
  	    $col = "col-sm-6";
  	  }
  	?>
    <?php if (!empty($this->products) && $this->showproducts) : ?>
  	<div class="orderby-displaynumber well well-sm">
  		<div class="vm-order-list small row">
  	    <div class="orderby-product <?php echo $col; ?>">
  				<div style="display: none">
  				<?php
  		      $search  = array('+/-', '-/+');
  		      $replace = array('', '');
  		      $orderby = $this->orderByList['orderby'];
  		      echo str_replace($search, $replace, $orderby);
            //var_dump($this->orderByList);
  		    ?>
  				</div>
          <label>
            <?php echo vmText::_('COM_VIRTUEMART_ORDERBY') ?>:
            <div class="input-group">
            <select id="product-orderby" onChange="window.location=this.value"></select>
            <div class="input-group-btn">&nbsp;
            <a id="sorting" class="btn<?php echo $sort_dir != "0" ? " desc" : ""; ?>" title="<?php echo $sort_dir != "0" ? "ASC" : "DESC"; ?>"></a>
            </div>
            </div>
          </label>
  	    </div>
        <?php if (VmConfig::get ('show_manufacturers',1)) : ?>
  	    <div class="orderby-manufacturer <?php echo $col; ?> text-center">
  	      <?php if (!empty($this->orderByList['manufacturer'])) : ?>
  	  		<div style="display: none"><?php echo $this->orderByList['manufacturer']; ?></div>
          <label>
          <?php echo vmText::_('COM_VIRTUEMART_PRODUCT_DETAILS_MANUFACTURER_LBL') ?>
          <select id="manuf-orderby" onChange="window.location=this.value"></select>
          </label>
          <?php endif; ?>
  	    </div>
        <?php endif; ?>
  	  	<div class="display-number <?php echo $col; ?> text-right">
	        <label>
	        <?php echo $this->vmPagination->getResultsCounter ();?>
	        <?php echo $this->vmPagination->getLimitBox ($this->category->limit_list_step); ?>
          </label>
  	    </div>
  	  </div>
  	</div> <!-- end of orderby-displaynumber -->
    <?php endif; ?>

    <?php	if (!empty($this->products))
    {
  		//revert of the fallback in the view.html.php, will be removed vm3.2
  		if($this->fallback){
  			$p = $this->products;
  			$this->products = array();
  			$this->products[0] = $p;
  			vmdebug('Refallback');
  		}

  	  echo shopFunctionsF::renderVmSubLayout($this->productsLayout,array('products'=>$this->products,'currency'=>$this->currency,'products_per_row'=>$this->perRow,'showRating'=>$this->showRating));

    }
    ?>

    <?php if (!empty($this->orderByList)) { ?>
    <hr>
  	<div class="vm-pagination vm-pagination-bottom text-center row">
  		<div class="vm-page-counter col-sm-3 small text-muted"><?php echo $this->vmPagination->getPagesCounter (); ?></div>
  		<div class="col-sm-9 text-right">
  	  <?php echo $this->vmPagination->getPagesLinks (); ?>
  		</div>
  	</div>
    <hr>
    <?php } ?>

  	<?php	if ($this->keyword !== false && empty($this->products)) { ?>
    <hr>
    <div class="alert alert-info">
    <?php echo vmText::_ ('COM_VIRTUEMART_NO_RESULT') . ($this->keyword ? ' : (' . $this->keyword . ')' : ''); ?>
    </div>
  	<?php } ?>

	</div> <!-- browse view end -->
  <?php } ?>
</div>
<?php
if(VmConfig::get ('ajax_category', false)){
	$j = "Virtuemart.container = jQuery('.category-view');
	Virtuemart.containerSelector = '.category-view';";

	vmJsApi::addJScript('ajax_category',$j);
	vmJsApi::jDynUpdate();
}
?>
<!-- end browse-view -->
<script>
	// Create the sorting layout. See vm-ltr-site.css also
	var prodActiveOrder = jQuery('.orderby-product div.activeOrder').text();
	jQuery('#product-orderby').append('<option>'+ prodActiveOrder +'</option>');
	jQuery('.orderby-product .orderlist a').each(function(){
	  href = jQuery(this).attr('href');
	  name = jQuery(this).text();
	  jQuery('#product-orderby').append('<option value="'+ href +'">'+ name +'</option>');
	});

	var manActiveOrder = jQuery('.orderby-manufacturer div.activeOrder').text();
	if (manActiveOrder.length) {
		jQuery('#manuf-orderby').append('<option>'+ manActiveOrder +'</option>');
	}
	jQuery('.orderby-manufacturer a, div.Order').each(function(){
		if (!jQuery(this).hasClass('Order')) {
		  href = jQuery(this).attr('href');
		  name = jQuery(this).text();
		  jQuery('#manuf-orderby').append('<option value="'+ href +'">'+ name +'</option>');
		} else {
			jQuery('#manuf-orderby').append('<option>'+ jQuery(this).text() +'</option>');
		}
	});

	var sort_href = jQuery('.orderby-product .activeOrder a').attr('href');
	jQuery('#sorting').attr('href', sort_href);
</script>