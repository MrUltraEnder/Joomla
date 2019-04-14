<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$col= 1 ;
$current_manufacturer_id = JRequest::getInt('virtuemart_manufacturer_id',0);

if ($manufacturers_per_row) {
	$mwidth = ' width:' . floor (100 / $manufacturers_per_row) . '%';

	if ($manufacturers_per_row > 1) {
		$row_class = 'row listing';
	} else {
		$row_class = ' ';
	}

}



?>
<div class="vm-manufacturers <?php echo $params->get ('moduleclass_sfx') ? 'vm-manufacturers__' . $params->get ('moduleclass_sfx') : '' ?>">

	<?php if ($headerText) : ?>
		<div class="header-text">
			<?php echo $headerText ?>
		</div>
	<?php endif;

	if ($display_style =="div") { ?>
		<div class="vm-manufacturers_listing">

			<div class="<?php echo $row_class; ?>">
			<?php foreach ($manufacturers as $manufacturer) {
				$i = 0;
				$link = JROUTE::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id);
				?>
				<div class="item item__manufacturer">
						
					<?php
					if ($manufacturer->images && ($show == 'image' or $show == 'all' )) { ?>
						<div class="item_image">
							<a href="<?php echo $link; ?>"><?php echo $manufacturer->images[0]->displayMediaThumb('',false);?></a>
						</div>
					<?php
					}

					if ($show == 'text' or $show == 'all' ) { ?>
					 <div class="item_name">
					 	<a href="<?php echo $link; ?>"><?php echo $manufacturer->mf_name; ?></a>
					 </div>
					<?php
					} ?>

				</div>

				<?php	
				$i++;	

				if ($products_per_row == $i && $products_per_row > 1): ?>
				 	</div>
				 	<div class="<?php echo $row_class; ?>">
				<?php endif; 
			}	?>
			<div class="clearfix"></div>
			</div>

		</div>

	<?php
	} else {
		$last = count($manufacturers)-1;
	?>

		<ul class="vm-manufacturers_listing">
			<?php
			foreach ($manufacturers as $manufacturer) {
				$link = JROUTE::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id=' . $manufacturer->virtuemart_manufacturer_id);
					if ($manufacturer->virtuemart_manufacturer_id==$current_manufacturer_id) {
						$active_class = 'active';
					} else {
						$active_class = '';
					}

				?>
				<li class="item item__manufacturer <?php echo $active_class; ?>">
					<?php
					if ($manufacturer->images && ($show == 'image' or $show == 'all' )) { ?>
						<div class="item_image">
							<a href="<?php echo $link; ?>"><?php echo $manufacturer->images[0]->displayMediaThumb('',false);?></a>
						</div>
					<?php
					}

					if ($show == 'text' or $show == 'all' ) { ?>
					 	<a href="<?php echo $link; ?>"><?php echo $manufacturer->mf_name; ?></a>
					<?php
					} ?>
					<div class="clearfix"></div>
				</li>			
			<?php } ?>
		</ul>

	<?php }
		if ( $footerText ) { ?>
		<div class="footer-text">
			 <?php echo $footerText ?>
		</div>
	<?php } ?>
</div>