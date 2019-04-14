<?php // no direct access
defined('_JEXEC') or die('Restricted access');
/**
 * Category menu module
 *
 * @package VirtueMart
 * @subpackage Modules
 * @copyright Copyright (C) OpenGlobal E-commerce. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL V3, see LICENSE.php
 * @author OpenGlobal E-commerce
 *
 */
$ID = str_replace('.', '_', substr(microtime(true), -8, 8));

require_once('printcategories.php');

echo '<div class="virtuemartcategories'.$params->get('moduleclass_sfx').'" id="VMenu' . $ID . '">';
printCategories($params, $categories, $class_sfx, $parentCategories, $ID);
echo '</div>'; 
?>

<script>

function categories_accordion(container){
	var arrow = jQuery(container).find('.VmArrow');

	jQuery(arrow).click(function(){
        var menu_item = jQuery(this).parent('.list-item');
        var menu_sub = menu_item.children('ul');
        var icon = jQuery(this).find('.fa')

        icon.stop().toggleClass('fa-caret-down').toggleClass('fa-caret-up');
        menu_sub.stop().slideToggle({
            duration: 200,
            easing: 'easeOutQuad'
        });
    })
}

jQuery(document).ready(function(){
    categories_accordion('#VMenu<?php echo $ID ;?>'); 
});

</script>