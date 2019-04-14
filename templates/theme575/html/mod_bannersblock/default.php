<?php 
defined('_JEXEC') or die;
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/modules/mod_bannersblock/css/bannersblock.css');
?>
<div class="banner_block <?php echo $params->get('moduleclass_sfx')?>">
	<ul class="listing__banners">		
		<?php		
			$j=$params->get('mod_total_image');	
			$k=0;
			for ($i=1; $i<=$j; $i++)
				{ ?>
				<li class="item banner item-<?php echo ++$k; ?>" style="width:<?php echo $params->get('bannerwidth'.$i); ?>; height:<?php echo $params->get('bannerheight'.$i); ?>;">
                <?php if ($params->get( 'bannerlink'.$i.'')!=null) {?>
					<a href="<?php echo JRoute::_( $params->get( 'bannerlink'.$i.'')); ?>" target="_self" title="<?php echo $params->get( 'bannertitle'.$i.'' ) ?>">
                    <?php if($params->get('mod_'.$i.'_image')!=null){ ?>
					<div class="banner_img">
						<img src="<?php if($params->get('mod_'.$i.'_image')!=null){echo JURI::root(true).'/'.$params->get('mod_'.$i.'_image');}?>" />
					</div>
                    <?php }?>
					<span class="caption"><?php echo $params->get('mod_image_'.$i.'_para'); ?></span>
					</a>
                    <?php }else { ?>
						 <?php if($params->get('mod_'.$i.'_image')!=null){ ?>
                        <div class="banner_img">
                            <img src="<?php if($params->get('mod_'.$i.'_image')!=null){echo JURI::root(true).'/'.$params->get('mod_'.$i.'_image');}?>" />
                        </div>
                        <?php }?>
                        <span class="caption"><?php echo $params->get('mod_image_'.$i.'_para'); ?></span>
					<?php } ?>
                    
				</li>
		<?php } ?>
	</ul>
</div>
