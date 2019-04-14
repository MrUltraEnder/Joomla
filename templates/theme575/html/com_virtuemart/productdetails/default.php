<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz
 * @author RolandD,
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6530 2012-10-12 09:40:36Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.module.helper' );
$product_page_modules = JModuleHelper::getModules( 'product-page' );

/* Let's see if we found the product */
if (empty($this->product)) {
	echo JText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
	echo $this->continue_link_html;
	return;
}
echo shopFunctionsF::renderVmSubLayout('askrecomjs',array('product'=>$this->product));

if(JRequest::getInt('print',false)) { ?>
<body onLoad="javascript:print();">
<?php }

// addon for joomla modal Box
JHTML::_('behavior.modal');

$MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component';

?>
<div class="page productdetails-view productdetails">
	<div class="page_heading product_heading">	   

	    <?php // afterDisplayTitle Event
	    echo $this->product->event->afterDisplayTitle ?>

	    <?php
	    // Product Edit Link
	   // echo $this->edit_link;
	    ?>

	    <?php
	    // PDF - Print - Email Icon
	    if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_icon')) {
		?>
	        <div class="product_icons icons">
			    <?php
			    //$link = (JVM_VERSION===1) ? 'index2.php' : 'index.php';
			    $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;

				echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_icon', false);
			    echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
			    echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend', false,true,false,'class="recommened-to-friend"');
			    ?>
	        </div>
	    <?php }
	    ?>
	</div>

	<?php 
	// Back To Category Button
	if ($this->product->virtuemart_category_id) {
		$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
		$categoryName = $this->product->category_name ;
	} else {
		$catURL =  JRoute::_('index.php?option=com_virtuemart');
		$categoryName = jText::_('COM_VIRTUEMART_SHOP_HOME') ;
	}
	?>
	<div class="product_back-to-category">
    	<a href="<?php echo $catURL ?>" class="product-details btn btn-default" title="<?php echo $categoryName ?>"><i class="fa fa-reply"></i><?php echo JText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO',$categoryName) ?></a>
	</div>

	<div class="row product_columns">

		<div class="col-md-4">
			<div class="product_images">
				<?php echo $this->loadTemplate('images'); ?>
			</div>
		</div>

		<div class="col-md-8">
			<div class="row product_wrap-top-right">
				<div class="col-md-7">

					<!-- Product title -->
				    <h1 class="page_title product_title"><?php echo $this->product->product_name ?></h1>

				    <div class="product_rating ratingbox">
						<?php // Output: Average Product Rating
							$ratingModel = VmModel::getModel('ratings');
							$rating = $ratingModel->getRatingByProduct($this->product->virtuemart_product_id);
						 ?>
						
                        <?php                    
						if ($this->showRating) {
							$maxrating = VmConfig::get('vm_maximum_rating_scale', 5);

							if (empty( $rating)) { ?>
								<div class="vote">
									<span class="rating-text"><?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . JText::_('COM_VIRTUEMART_UNRATED') ?></span>
								</div>
							<?php
							} else {
								?>
								<div class="vote">
									<span class="pull-left rating-icons">
		                            <?php 
		                            for ($i = 1; $i <= 5 ; $i ++ ) { 		                            	
		                            	if ($i <= $rating->rating) {
		                            		echo '<i class="fa fa-star"></i> ';
		                            	} else {
		                            		echo '<i class="fa fa-star-o"></i> ';
		                            	}                     	
		                            } ?>
		                            </span>
		                            <span class="rating-text pull-right">
		                            	<?php echo JText::_('COM_VIRTUEMART_RATING') . ' ' . round( $rating->rating) . '/' . $maxrating; ?>
		                            </span>
		                            <div class="clearfix"></div>
		                        </div>
		                <?php
							}
						} ?>
					</div>
               		 
				    <?php
				    // Product Short Description
				    if (!empty($this->product->product_s_desc)) { ?>
				       <div class="product_short-description">
						    <?php
						    /** @todo Test if content plugins modify the product description */
						    echo nl2br($this->product->product_s_desc); ?>
				        </div>
					<?php
				    } 

				    if (!empty($this->product->customfieldsSorted['ontop'])) {
						$this->position = 'ontop';
						echo $this->loadTemplate('customfields');
				    } ?>
					
					<?php 
					if (is_array($this->productDisplayShipments)) { ?>
					<div class="product_shipments">
					    <?php 
					    	foreach ($this->productDisplayShipments as $productDisplayShipment) {
								echo $productDisplayShipment;
					    	} ?>
					</div>
					<?php } ?>


					<?php if (is_array($this->productDisplayPayments)) { ?>
					<div class="product_payments">
					    <?php 
					    	foreach ($this->productDisplayPayments as $productDisplayPayment) {
								echo $productDisplayPayment;
					    	} ?>
					</div>
					<?php } 
					if ($this->product->product_availability) {
					?>			

					<div class="product_availability">
						<?php
						// Availability
						$stockhandle = VmConfig::get('stockhandle', 'none');
						$product_available_date = substr($this->product->product_available_date,0,10);
						$current_date = date("Y-m-d");
						if (($this->product->product_in_stock - $this->product->product_ordered) < 1) {
							if ($product_available_date != '0000-00-00' and $current_date < $product_available_date) {
							?>	<div class="availability">
									<?php echo JText::_('COM_VIRTUEMART_PRODUCT_AVAILABLE_DATE') .': '. JHTML::_('date', $this->product->product_available_date, JText::_('DATE_FORMAT_LC4')); ?>
								</div>
						    <?php
							} else if ($stockhandle == 'risetime' and VmConfig::get('rised_availability') and empty($this->product->product_availability)) {
							?>	<div class="availability">
							    <?php echo (file_exists(JPATH_BASE . DS . VmConfig::get('assets_general_path') . 'images/availability/' . VmConfig::get('rised_availability'))) ? JHTML::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . VmConfig::get('rised_availability', '7d.gif'), VmConfig::get('rised_availability', '7d.gif'), array('class' => 'availability')) : JText::_(VmConfig::get('rised_availability')); ?>
							</div>
						    <?php
							} else if (!empty($this->product->product_availability)) {
							?>
							<div class="availability">
							<?php echo (file_exists(JPATH_BASE . DS . VmConfig::get('assets_general_path') . 'images/availability/' . $this->product->product_availability)) ? JHTML::image(JURI::root() . VmConfig::get('assets_general_path') . 'images/availability/' . $this->product->product_availability, $this->product->product_availability, array('class' => 'availability')) : JText::_($this->product->product_availability); ?>
							</div>
							<?php
							}
						}
						else if ($product_available_date != '0000-00-00' and $current_date < $product_available_date) {
						?>	<div class="availability">
								<?php echo JText::_('COM_VIRTUEMART_PRODUCT_AVAILABLE_DATE') .': '. JHTML::_('date', $this->product->product_available_date, JText::_('DATE_FORMAT_LC4')); ?>
							</div>
						<?php
						}
						?>					
					</div>
					<?php } ?>
					<ul class="list product_details-list">

						<?php if (!empty($this->product->product_box)): ?>
							<li>							
								<strong><?php echo JText::_('COM_VIRTUEMART_PRODUCT_UNITS_IN_BOX'); ?></strong>
								<span><?php echo $this->product->product_box; ?></span>
							</li>
				    	<?php endif; ?>

						<?php
						// Manufacturer of the Product
						if (VmConfig::get('show_manufacturers', 1) && !empty($this->product->virtuemart_manufacturer_id)) {
							echo $this->loadTemplate('manufacturer');
						}
						?>
					</ul>
				</div>
				<div class="col-md-5">
					<div class="product_price-wrap">
						<div class="product_prices">
							<?php  echo $this->loadTemplate('showprices'); ?>
						</div>

						<div class="product_addtocart">
                        	<?php 
								  echo shopFunctionsF::renderVmSubLayout('addtocartproduct',array('product'=>$this->product)); 
							?>
						</div>

                        
                      <?php   // Ask a question about this product
						if (VmConfig::get('ask_question', 0) == 1) {
							$askquestion_url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component', FALSE);
							?>
							<div class="product_question ask-a-question">
								<a class="ask-a-question" href="<?php echo $askquestion_url ?>" rel="nofollow" ><i class="fa fa-question-circle"></i><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_ENQUIRY_LBL') ?></a>
							</div>
						<?php
						}
					?>
					</div>					
				</div>
				<div class="clearfix"></div>
			</div>
			<?php // event onContentBeforeDisplay
			echo $this->product->event->beforeDisplayContent; ?>

			<?php
			// Product Description
			if (!empty($this->product->product_desc)) { ?>
		        <div class="product_description product-section">
		    		<h4 class="product-section_title"><?php echo JText::_('COM_VIRTUEMART_PRODUCT_DESC_TITLE') ?></h4>
					<?php echo $this->product->product_desc; ?>
		        </div>
			<?php
		    }
				echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'normal'));

			?>
		    <?php
		    // Product Files
		    // foreach ($this->product->images as $fkey => $file) {
		    // Todo add downloadable files again
		    // if( $file->filesize > 0.5) $filesize_display = ' ('. number_format($file->filesize, 2,',','.')." MB)";
		    // else $filesize_display = ' ('. number_format($file->filesize*1024, 2,',','.')." KB)";

		    /* Show pdf in a new Window, other file types will be offered as download */
		    // $target = stristr($file->file_mimetype, "pdf") ? "_blank" : "_self";
		    // $link = JRoute::_('index.php?view=productdetails&task=getfile&virtuemart_media_id='.$file->virtuemart_media_id.'&virtuemart_product_id='.$this->product->virtuemart_product_id);
		    // echo JHTMl::_('link', $link, $file->file_title.$filesize_display, array('target' => $target));
		    // }
		    //if (!empty($this->product->customfieldsRelatedProducts)) {
				//echo $this->loadTemplate('relatedproducts');
		   // } // Product customfieldsRelatedProducts END
			
			echo shopFunctionsF::renderVmSubLayout('customfieldsrelatedprod',array('product'=>$this->product,'position'=>'related_products','class'=> 'product-related-products' ));
		   echo shopFunctionsF::renderVmSubLayout('customfieldsrelatedcategories',array('product'=>$this->product,'position'=>'related_categories','class'=> 'product-related-categories'));

		    
		    // Show child categories
		   
		   	echo shopFunctionsF::renderVmSubLayout('customfields',array('product'=>$this->product,'position'=>'onbot'));

		    ?>


            <?php ?><div class="product-jc product-section">
				<?php 
				defined('_JEXEC') or die('Restricted access'); 
				$comments = JPATH_SITE.'/components/com_jcomments/jcomments.php';
				if (file_exists($comments)){
					require_once($comments);
					echo JComments::show($this->product->virtuemart_product_id,'com_virtuemart', $this->product->product_name);
				}
                ?> 
			</div><?php ?>
            <?php // onContentAfterDisplay event
				echo $this->product->event->afterDisplayContent; ?>
			<?php
				echo $this->loadTemplate('reviews');
			?>
			<!-- Product-page position modules -->
            <div class="product_page-modules">
            <?php  $link2 = JURI::base() .'index.php?view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id; 
				   $img_pint2 = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id;
				   //print_r($this->product->images[0]->file_url); 
			?>

				<?php 
                foreach ($product_page_modules as $product_page_module) {
					//var_dump($product_page_module->params);
					$social=$product_page_module->params;
					$socialstyle = explode(",", $social);
					$social2=$socialstyle[1];
					$socialstyle2 = explode(":", $social2);
					if ($socialstyle2[1]=='"custom-button"'){
					?>
                    
                    <meta property="og:title" content="" />
                    <meta property="og:type" content="product" />
                    <meta property="og:site_name" content="" />
                    <meta property="og:description" content="" />
                    <meta property="og:email" content="" />
                    <meta property="og:phone_number" content="" />
                    <meta property="og:street-address" content="" />
                    <meta property="og:locality" content="" />
                    <meta property="og:country-name" content="" />
                    <meta property="og:postal-code" content="" />
                    <meta property="og:image" content="<?php echo JURI::base() . $this->product->images[0]->file_url; ?>" />

               <script type='text/javascript'>
				jQuery(document).ready(function(){
					jQuery('.socialsharing_product .social-sharing').on('click', function(){
						type = jQuery(this).attr('data-type');
						if (type.length)
						{
							switch(type)
							{
								case 'twitter':
									window.open('https://twitter.com/intent/tweet?text=' + '<?php echo $this->product->product_name ?>' + ' ' + encodeURIComponent('<?php echo $link2 ?>'), 'sharertwt', 'toolbar=0,status=0,width=640,height=445');
									break;
								case 'facebook':
									window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent('<?php echo $link2 ?>'), 'sharer', 'toolbar=0,status=0,width=660,height=445');
									break;
								case 'google-plus':
									window.open('https://plus.google.com/share?url=' + encodeURIComponent('<?php echo $link2 ?>'), 'sharer', 'toolbar=0,status=0,width=660,height=445');
									break;
								case 'pinterest':
									window.open('http://www.pinterest.com/pin/create/button/?media=' + '<?php echo JURI::base() . $this->product->images[0]->file_url; ?>' + '&url=' + encodeURIComponent('<?php echo $link2 ?>'), 'sharerpinterest', 'toolbar=0,status=0,width=660,height=445');
									break;
							}
						}
					});
				});				
				</script>
				<?php } ?>
                    <div class="<?php echo $product_page_module->name; ?>">
                   
                        <?php echo JModuleHelper::renderModule($product_page_module); ?>
                    </div>
                <?php } ?>
            </div>
			<?php
		    // Product Navigation
		    if (VmConfig::get('product_navigation', 1)) {
			?>
		        <div class="product_neighbours">
				    <?php
				    if (!empty($this->product->neighbours ['previous'][0])) {
						$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
						echo JHTML::_('link', $prev_link, '<i class="fa fa-caret-left"></i> ' . $this->product->neighbours ['previous'][0] ['product_name'], array('rel'=>'prev', 'class' => 'previous-page'));
				    }
				    if (!empty($this->product->neighbours ['next'][0])) {
						$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
						echo JHTML::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'] . ' <i class="fa fa-caret-right"></i>', array('rel'=>'next','class' => 'next-page'));
				    }
				    ?>
		        </div>
		    <?php } ?>
		</div>
	</div>
</div>