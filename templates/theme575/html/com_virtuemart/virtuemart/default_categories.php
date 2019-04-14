<?php
// Access
defined('_JEXEC') or die('Restricted access');

// Category and Columns Counter
$iCol = 1;
$iCategory = 1;

// Calculating Categories Per Row
$categories_per_row = VmConfig::get('homepage_categories_per_row', 3);
$category_cellwidth = 'width:' . floor(100 / $categories_per_row) . '%'; ?>

<div class="category-view">

    <!-- <h4><?php /*echo JText::_('COM_VIRTUEMART_CATEGORIES')*/ ?></h4> -->

    <div class="categories-listing">

	    <?php
	    // Start the Output
	    foreach ($this->categories as $category) {

			// this is an indicator wether a row needs to be opened or not
			if ($iCol == 1) { ?>
			    <div class="row listing__grid">
			<?php }

		    // Category Link
		    $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $category->virtuemart_category_id, FALSE);

		    // Show Category
		    ?>
	    	<div class="item item__category" style="<?php echo $category_cellwidth; ?>">
	    	    <div class="category_wrap">

	    	    	<div class="item_image category_image">
	    	    		<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>">

						    <?php
						    if (!empty($category->images)) {
								echo $category->images[0]->displayMediaThumb("", false);
						    }
						    ?>
				    	</a>
	    	    	</div>

	    	    	<h5 class="item_name category_title">
	    	    		<a href="<?php echo $caturl ?>" title="<?php echo $category->category_name ?>"><?php echo $category->category_name ?></a>
	    	    	</h5>
	    		    
	    	    </div>
	    	</div>
			<?php
			$iCategory++;

			// Do we need to close the current row now?
			if ($iCol == $categories_per_row) { ?>
			    </div>
				<?php $iCol = 1;
		    } else {
				$iCol++;
		    }
		}
		// Do we need a final closing row tag?
		if ($iCol != 1) { ?>
		    </div>
		<?php }	?>
	</div>
</div>