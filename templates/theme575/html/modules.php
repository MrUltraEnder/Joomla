<?php defined('_JEXEC') or die;


function modChrome_vmBasic($module, &$params, &$attribs) { 

	$module_class_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	?>
	<div class="moduletable <?php echo strlen($module_class_sfx) > 0 ? 'moduletable__' . $module_class_sfx : '' ?> <?php echo $module->module; ?>">
		<?php if ($module->showtitle): ?>
		<div class="module_header">
			<h3 class="module_title"><?php echo JText::_( $module->title ); ?></h3>			
		</div>
		<?php endif; ?>
		<div class="module_content">
			<?php echo $module->content; ?>
		</div>
	</div><?php
}

function modChrome_vmNotitle($module, &$params, &$attribs) { 
	$module_class_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	?>
	<div class="moduletable <?php echo strlen($module_class_sfx) > 0 ? 'moduletable__' . $module_class_sfx : '' ?>  <?php echo $module->module; ?>">				
		<div class="module_content">
			<?php /*echo JText::_( $module->title );*/ ?>
			<?php echo $module->content; ?>
		</div>
	</div><?php
}

?>