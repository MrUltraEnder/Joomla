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

if (!function_exists('printCategoriesNav')) {
   function printCategoriesNav($params, $virtuemart_categories, $class_sfx, $parentCategories, $ID) {

    echo '<ul class="menu' . $class_sfx . '">';
    foreach ($virtuemart_categories as $category) {
        $active_menu = '';
        $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
        $cattext = (isset($image) ? '<img src="'.$image.'" alt="'.$category->category_name.'" />' : '').'<span>'.$category->category_name.'</span>';

        if ($category->childs) {
             $parent_class = 'parent';
        } else {
            $parent_class = '';
        }

        $active_menu = "";
        if (is_array($parentCategories)) {// Need this check because $parentCategories will be null if we're at category 0
            if (in_array( $category->virtuemart_category_id, $parentCategories)) {
                $active_menu = 'active';
            }
        }
        echo '<li class="'.$active_menu.' '. $parent_class .' list-item"">'.JHTML::link($caturl, $cattext);
        if ($category->childs) {?>
            <?php 
            printCategoriesNav($params, $category->childs, $class_sfx, $parentCategories, $ID);
        }
        echo '</li>';
    }
    echo '</ul>';
}
}


printCategoriesNav($params, $categories, $class_sfx, $parentCategories, $ID);
?>
