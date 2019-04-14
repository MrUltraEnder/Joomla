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

function printCategories($params, $virtuemart_categories, $class_sfx, $parentCategories, $ID) {

    echo '<ul class="list list__categories list__accordion menu' . $class_sfx . '">';
    foreach ($virtuemart_categories as $category) {
        $active_menu = '';
        $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$category->virtuemart_category_id);
        $images = $category->images;
        if (1 == $params->get('show_images')) {
            $image = $images[0]->file_url_thumb;
        } else if (2 == $params->get('show_images')) {
            $image = $images[0]->file_url;
        }
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
            <span class="VmArrow"><i class="fa fa-caret-down"></i></span>
            <?php 
            printCategories($params, $category->childs, $class_sfx, $parentCategories, $ID);
        }
        echo '</li>';
    }
    echo '</ul>';
}