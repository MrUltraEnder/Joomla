<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="mod-vm-search <?php echo $params->get('moduleclass_sfx'); ?>">
	<form action="<?php echo JRoute::_('index.php?option=com_virtuemart&view=category&search=true&limitstart=0&virtuemart_category_id='.$category_id ); ?>" method="get" class="form-inline">
		<div class="search-form">
			<?php 
				$output = '<div class="form-group"><input name="keyword" id="mod_virtuemart_search" maxlength="'.$maxlength.'" alt="'.$button_text.'" class="inputbox form-control '.$moduleclass_sfx.'" type="text" size="'.$width.'" value="'.$text.'"  onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" /></div>';

				$image = JURI::base().'components/com_virtuemart/assets/images/vmgeneral/search.png' ; 

			if ($button) :
			    if ($imagebutton) :
			        $button = '<input type="image" value="'.$button_text.'" class="btn btn-default'.$moduleclass_sfx.'" src="'.$image.'" onclick="this.form.keyword.focus();"/>';
			    else :
			        $button = '<button type="submit" class="btn btn-search'.$moduleclass_sfx.'" onclick="this.form.keyword.focus();"> <i class="fa fa-search"></i></button>';
			    endif;
		

				switch ($button_pos) :
				    case 'top' :
					    $button = $button.'<br />';
					    $output = $button.$output;
					    break;

				    case 'bottom' :
					    $button = '<br />'.$button;
					    $output = $output.$button;
					    break;

				    case 'right' :
					    $output = $output.$button;
					    break;

				    case 'left' :
				    default :
					    $output = $button.$output;
					    break;
				endswitch;
			endif;
			
			echo $output;
			?>
			

			<input type="hidden" name="limitstart" value="0" />
			<input type="hidden" name="option" value="com_virtuemart" />
			<input type="hidden" name="view" value="category" />
			<?php if(!empty($set_Itemid)){
				echo '<input type="hidden" name="Itemid" value="'.$set_Itemid.'" />';
			} ?>
		</div>
	</form>
</div>