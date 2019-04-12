<?php // no direct access
defined('_JEXEC') or die('Restricted access');
//JHTML::stylesheet ( 'menucss.css', 'modules/mod_virtuemart_category/css/', false );

/* ID for jQuery dropdown */
$ID = str_replace('.', '_', substr(microtime(true), -8, 8));
$js="
//<![CDATA[
jQuery(document).ready(function() {
		jQuery('#VMmenu".$ID." li.VmClose ul').hide();
		jQuery('#VMmenu".$ID." li .VmArrowdown').click(
		function() {

			if (jQuery(this).parent().next('ul').is(':hidden')) {
				jQuery('#VMmenu".$ID." ul:visible').delay(500).slideUp(500,'linear').parents('li').addClass('VmClose').removeClass('VmOpen');
				jQuery(this).parent().next('ul').slideDown(500,'linear');
				jQuery(this).parents('li').addClass('VmOpen').removeClass('VmClose');
			}
		});
	});
//]]>
" ;

$document = JFactory::getDocument();
//$document->addScriptDeclaration($js);
?>

<ul class="VMmenu <?php echo $class_sfx ?> nav nav-pills nav-stacked" id="<?php echo "VMmenu" . $ID ?>" >
	<?php foreach ($categories as $category) {
		$active_menu = 'VmClose';
		$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
		$cattext = $category->category_name;
		if (in_array( $category->virtuemart_category_id, $parentCategories)) $active_menu = 'VmOpen active';
	?>
  <li class="<?php echo $active_menu; ?>">
		<?php
			if ($category->childs) {
	    	$span = '<button class="vm-plus btn btn-xs btn-default" type="button"><span class="glyphicon glyphicon-plus"></span></button>';
			} else {
	      $span = '';
			}
			echo JHTML::link($caturl, $cattext);
			echo $span ;
    ?>

		<?php if (!empty($category->childs)) { ?>
		<ul class="vm-child-menu <?php echo $class_sfx; ?> nav small">
			<?php
				foreach ($category->childs as $child) {

				$active_child_menu = 'VmClose';
				$caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$child->virtuemart_category_id);
				$cattext = vmText::_($child->category_name);

				if ($child->virtuemart_category_id == $active_category_id) {
					$active_child_menu = 'VmOpen active';
        }
			?>
				<li class="<?php echo $active_child_menu; ?>">
					<?php echo JHTML::link($caturl, $cattext); ?>
				</li>
			<?php	} ?>
		</ul>
		<?php } ?>
  </li>
	<?php } ?>
</ul>
<script>
jQuery('#VMmenu<?php echo $ID ?>').find('li.active').children('ul.vm-child-menu').show().siblings('button').children('span').toggleClass('glyphicon-plus glyphicon-minus');
jQuery('#VMmenu<?php echo $ID ?>').find('button').click(function(event){
	jQuery(this).children('span').toggleClass('glyphicon-plus glyphicon-minus');
	jQuery(this).siblings('ul').slideToggle();
	event.stopPropagation();
});
</script>