<?php
/**
 * @package		jFlickr
 * @subpackage	jFlickr
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.4.1
 */

defined( '_JEXEC' ) or die( 'Restricted access' );?>
<?php
	if($scripts && !$zgf && $cache) {?>
	<style type="text/css">
.gallery-flickr ul li {list-style-type:none;float:left;background: none;margin-left:0}.gallery-flickr ul {margin: 0} #right .gallery-flickr ul li a,#left .gallery-flickr ul li a,.gallery-flickr ul li a {float:left;margin:0 4px 4px 0;padding: 0;background:none;border: 0;} .gallery-flickr ul li a:hover {background: #ddd} #gallery-flickr {padding: 0;line-height: 0;margin: 0} .clearfix {clear:both}
	</style>
	<?php if (($fancyBoxScript == "yes") && ($fancyBox == "yes")){?>
		<link rel="stylesheet" href="<?php echo $modbase ?>js/jquery.fancybox/jquery.fancybox-1.3.4.css" type="text/css" />
		<?php $zoomClass .= $moduleID;
	}
}
	if($scripts && !$zgf && $cache) {?>
	<script type="text/javascript" src="<?php echo $modbase?>js/JFlickr.js"></script>
	<?php if (($fancyBoxScript == "yes") && ($fancyBox == "yes")){?>
		<script type="text/javascript" src="<?php echo $modbase?>js/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>

		<?php if ($fancyEasing == "yes"){?>
			<script type="text/javascript" src="<?php echo $modbase?>js/jquery.fancybox/jquery.easing-1.3.pack.js"></script>
		<?php }
	}?>
<?php }?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".gallery-flickr-<?php echo $moduleID ?>").flickr({
			api_key: "<?php echo $apiKey ?>",
			thumb_size: '<?php echo $tsize ?>',
			size: '<?php echo $size ?>',
			per_page: <?php echo $number ?>,
			randomise: '<?php echo $random ?>',
			<?php if ($type == "photoset") : ?>
			type: '<?php echo $type ?>',
			sort: '<?php echo $sort ?>',
			photoset_id: '<?php echo $photoset_id ?>',
			<?php endif; ?>
			<?php if ($type == "search") : ?>
			type: '<?php echo $type ?>',
			sort: '<?php echo $sort ?>',
			tags: '<?php echo $tags ?>',
			text: '<?php echo $text ?>',
			<?php endif; ?>
			<?php if ($type == "user") : ?>
			type: '<?php echo $type1 ?>',
			sort: '<?php echo $sort ?>',
			tags: '<?php echo $tags ?>',
			user_id: '<?php echo $user_id ?>',
			<?php endif; ?>
			<?php if ($type == "group") : ?>
			type: '<?php echo $type1 ?>',
			sort: '<?php echo $sort ?>',
			tags: '<?php echo $tags ?>',
			group_id: '<?php echo $group_id ?>',
			<?php endif; ?>
			module_id: '<?php echo $moduleID ?>',
			zoom_class: '<?php echo $zoomClass ?>',
			callback: swipeboxCallback
					});
	function fancyboxCallback(){
	<?php if ($fancyBox == "yes") : ?>
	jQuery("a[rel='<?php echo $zoomClass?>']").fancybox({
	zoomOpacity: false,
		padding: <?php echo $fancyPadding ?>,
		overlayOpacity: <?php echo $fancyOverlay ?>,
		'overlayShow'			: <?php echo $fancyOverlayShow ?>,
		'zoomSpeedIn'			: 600,
		'zoomSpeedOut'			: 500,
		<?php if ($fancyEasing == "yes") : ?>
		'easingIn'				: 'easeOutBack',
		'easingOut'				: 'easeInBack',
		<?php endif; ?>
		'hideOnContentClick'	: false

	});
	<?php endif; ?>
	}

	function swipeboxCallback(){
		jQuery("a[rel='<?php echo $zoomClass?>']").swipebox({
			useCSS : true,
			hideBarsDelay : 30000,
			hideBarsOnMobile : false
		});
	}
});
</script>
<!-- Start Joomla Bamboo jFlickr -->
<div class="gallery-flickr gallery-flickr-<?php echo $moduleID ?>">&nbsp;</div>
<div class="clearfix"></div>
<?php if ($flickrLink == "1") : ?>
	<a href="#" target="_blank" class="flickr_direct_link"><?php echo $linkTitle; ?></a>
<?php endif; ?>
<!-- End Joomla Bamboo jFlickr -->
