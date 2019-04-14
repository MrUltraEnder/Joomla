<?php
/**
* @package mod_vm_ajax_search
*
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL, see LICENSE.php
* VM Live Product Search is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/
/**
*  Modified by RuposTel.com 25.6.2011
*  and renamed to mod_vm_ajax_search
*/
/**
 * VM Live Product Search
 *
 * Used to process Ajax searches on a Virtuemart 1.1.2 Products.
 * Based on the excellent mod_pixsearch live search module designed by Henrik Hussfelt (henrik@pixpro.net - http://pixpro.net)
 * @author		John Connolly <webmaster@GJCWebdesign.com>
 * @package		mod_vm_live_product
 * @since		1.5
 * @version     0.5
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
/* Load the virtuemart main parse code */

		if (file_exists(JPATH_BASE.DS.'components'.DS.'com_virtuemart'.DS.'fetchscript.php'))
		{
		define('VM1', true); 
		define('VM2', false); 
		}
		else
		{
		define('VM1', false); 
		define('VM2', true); 
		}
		
if (VM1)
{
if( !isset( $mosConfig_absolute_path ) ) {
	$mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path']	= JPATH_SITE;
}
if (file_exists($mosConfig_absolute_path.'/components/com_virtuemart/virtuemart_parser.php'))
{
require_once( $mosConfig_absolute_path.'/components/com_virtuemart/virtuemart_parser.php' );
global $mosConfig_absolute_path, $VM_LANG, $page, $sess;
if (!empty($jmshortcode) && is_object($jmshortcode))
{
 $_SESSION['jmactivelang']=$jmshortcode->id;
 $_SESSION['JMsetmosConfig_lang'] = $mosConfig_lang;
}
}
else
{
  	$VM_LANG = new op_bcompatibility(); 
	$GLOBALS['VM_LANG'] = $VM_LANG;
	$sess = $VM_LANG; 
}

$lang_code = JRequest::getVar('lang', '');
}
else
{
 // load virtuemart language files
 $jlang =JFactory::getLanguage();
 $jlang->load('com_virtuemart', JPATH_SITE, $jlang->getDefault(), true);
 $jlang->load('com_virtuemart', JPATH_SITE, null, true);
}



$lang =& JFactory::getLanguage();
$extension = 'com_search';
$base_dir = JPATH_SITE;
if (VM1)
$language_tag = $lang->_lang;
else $language_tag = $lang->getTag(); 

$lang->load($extension, $base_dir, $language_tag, true);
$myid = $module->id;



$clang = JRequest::getVar('lang', ''); 
//var_dump($_SESSION);die();
$conf = JFactory::getConfig();
$l = $conf->getValue('config.language');
$a = explode('-', $l); 

if (!empty($a[0]))
$clang = $a[0];
else $clang = '';

$q = 'select params from #__modules where id = "'.$myid.'" '; 
$db =& JFactory::getDBO();
$db->setQuery($q); 
$res = $db->loadResult(); 

if (!empty($res))
$params = new JParameter( $res );

$min_height = $params->get('min_height');
$results_width = $params->get('results_width');

if (empty($min_height)) $min_height = '40'; 
if (empty($results_width)) $results_width = '200px';
else $results_width .= 'px'; 

$prods = $params->get('number_of_products'); 
// we start with zero
$prods--; 
if (empty($prods)) $prods = 4; 
$url = JURI::base().'modules/mod_vm_ajax_search/ajax/index.php';


$document =& JFactory::getDocument();
if (!defined('search_timer')) {
	// init only once per all modules
	$document->addStyleSheet(JURI::base().'modules/mod_vm_ajax_search/css/mod_vm_ajax_search.css'); 
	$document->addScript(JURI::Base().'modules/mod_vm_ajax_search/js/vmajaxsearch.js'); 

	$js1 = ' 
	      var search_timer = new Array(); 
		  var search_has_focus = new Array(); 
		  var op_active_el = null;
		  var op_active_row = null;
	      var op_active_row_n = parseInt("0");
		  var op_last_request = ""; 
	      var op_process_cmd = "href"; 
		  var op_controller = ""; 
		  var op_lastquery = "";
		  var op_maxrows = '.$prods.'; 
		  var op_lastinputid = "vm_ajax_search_search_str2'.$myid.'";
		  var op_currentlang = "'.$clang.'";
		  var op_lastmyid = "'.$myid.'"; 
		  var op_ajaxurl = "'.$url.'";
		  var op_savedtext = new Array(); 

	'; 
	}
	else $js1 = ''; 

	$js = $js1.'
	/* <![CDATA[ */
	// global variable for js


	search_timer['.$myid.'] = null; 
	search_has_focus['.$myid.'] = false; 
	//document.addEvent(\'onkeypress\', function(e) { handleArrowKeys(e); });

	window.addEvent(\'domready\', function() {
	 document.onkeydown = function(event) { return handleArrowKeys(event); };
	 //jQuery(document).keydown(function(event) { handleArrowKeys(event); }); 
	 // document.onkeypress = function(e) { handleArrowKeys(e); };
	 if (document.body != null)
	 {
	   var div = document.createElement(\'div\'); 
	   div.setAttribute(\'id\', "vm_ajax_search_results2'.$myid.'"); 
	   div.setAttribute(\'class\', "res_a_s"); 
	   div.setAttribute(\'style\', "width:'.$results_width.'");
	   document.body.appendChild(div);
	 }
	 //document.body.innerHTML += \'<div class="res_a_s" id="vm_ajax_search_results2'.$myid.'" style="z-index: 999; width: '.$results_width.';">&nbsp;</div>\';
	});
	/* ]]> */


	'; 
	$document->addScriptDeclaration($js); 
	$style = '
	#vm_ajax_search_results2'.$myid.' {margin-left:'.$params->get('offset_left_search_result').'px;margin-top:'.$params->get('offset_top_search_result').'px;}
	';
	$document->addStyleDeclaration($style); 
?>

<div class="mod-vm-search" >
	<form name="pp_search<?php echo $myid ?>" id="pp_search2.<?php echo $myid ?>" action="<?php echo JRoute::_('index.php'); ?>" method="get" class="form-inline">
		<div class="vmlpsearch<?php echo $params->get('moduleclass_sfx'); ?>">
				
			
			<?php 
			$prett = $params->get('pretext');	
		    if (!empty($prett)) { ?>
				<p class="vm_ajax_search_pretext"><?php echo $params->get('pretext'); ?></p>
			<?php } ?>

			<?php
				if (VM1)
				$search = $VM_LANG->_('PHPSHOP_PRODUCT_SEARCH_LBL');
				else 
				$search = JText::_('TM_VIRTUEMART_SEARCH');
				// can set this also to: JText::_('SEARCH');
				
				$search = addslashes($search);
				$include_but = $params->get('include_but');
				$tw = $params->get('text_box_width');		
			?>

			<div class="aj_label_wrapper">
				<div class="form-group">
					<label for="vm_ajax_search_search_str2<?php echo $myid ?>" id="label_vm_ajax_search_search_str2<?php echo $myid ?>" class="sr-only label_vm_ajax"><?php echo $search_lbl = JText::_('TM_VIRTUEMART_SEARCH_LABEL');  ?></label>
					<input class="form-control inputbox" id="vm_ajax_search_search_str2<?php echo $myid ?>" name="keyword" type="text" value="" placeholder="<?php echo $search_lbl = JText::_('TM_VIRTUEMART_SEARCH_LABEL');  ?>" autocomplete="off" onblur="javascript: return search_setText('', this, '<?php echo $myid ?>');" onfocus="javascript: aj_inputclear(this, '<?php echo $params->get('number_of_products'); ?>', '<?php echo $clang; ?>', '<?php echo $myid; ?>', '<?php echo $url ?>');" onkeyup="javascript:search_vm_ajax_live(this, '<?php echo $params->get('number_of_products'); ?>', '<?php echo $clang; ?>', '<?php echo $myid; ?>', '<?php echo $url ?>'); "/>
				</div>

				<input type="hidden" id="saved_vm_ajax_search_search_str2<?php echo $myid ?>" value="<?php echo $search; ?>" />
				<?php if (VM1) { ?>
				  <input type="hidden" name="Itemid" value="<?php echo $sess->getShopItemid(); ?>" />
				<?php } ?>	
				<input type="hidden" name="option" value="com_virtuemart" />
				<input type="hidden" name="page" value="shop.browse" />
				<input type="hidden" name="search" value="true" />
				<input type="hidden" name="view" value="category" />
				<input type="hidden" name="limitstart" value="0" />
			
		
				<?php 
				if (!empty($include_but)) {
					echo '<button class="btn btn-search button" type="submit" name="Search"><i class="fa fa-search"></i></button>';
				} 
					
					if (($params->get('include_advsearch')== 1) && (VM1)) {
						if (VM1)
							$search_page = JRoute::_('index.php?option=com_virtuemart&page=shop.search'); 
						else
							$search_page = JRoute::_('index.php?option_com_virtuemart&view=category');		
					echo '<a style="clear: both; float: left;" href="'.$search_page.'">'.$search.' </a>';}?>							
		    </div>

		    <?php 
		    $postt = $params->get('posttext');	
		    if (!empty($postt)) { ?>
					<p class="vm_ajax_search_posttext" style="clear: both;"><?php echo $postt; ?></p>
			<?php } ?>
		</div>
	</form>
	<?php 
	if (false) { ?>
		<div class="res_a_s" id="vm_ajax_search_results2<?php echo $myid ?>" style="position: <?php echo $params->get('css_position'); ?>; z-index: 999; width: <?php echo $results_width ?>;">&nbsp; 
		</div>
	<?php } ?>
</div>