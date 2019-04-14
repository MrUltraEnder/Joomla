<?php // no direct access
defined('_JEXEC') or die('Restricted access');
vmJsApi::jQuery();
vmJsApi::chosenDropDowns();
?>

<div class="mod-currency-selector <?php echo $params->get('moduleclass_sfx'); ?>">	
	<form action="<?php echo vmURI::getCleanUrl() ?>" method="post" class="form-inline">
		<?php echo $text_before ? '<label>' . $text_before . '</label>' : '' ?>
		<?php echo JHTML::_('select.genericlist', $currencies, 'virtuemart_currency_id', 'class="inputbox vm-chzn-select form-control"', 'virtuemart_currency_id', 'currency_txt', $virtuemart_currency_id) ; ?>
	    <!-- <input class="button btn btn-default" type="submit" name="submit" value="<?php /*echo JText::_('MOD_VIRTUEMART_CURRENCIES_CHANGE_CURRENCIES')*/ ?>" /> -->
	</form>
</div>

<script>
	jQuery('#virtuemart_currency_id').change(function(){
		jQuery(this).parent('form').submit();
	})
</script>