<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
?>

<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post" class="form-inline search-form">

	<div class="search-form_section form-inline">
		<div class="form-group">
			<label for="search_searchword">
					<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>
			</label>
			<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox form-control" />
		</div>			
		<button name="Search" onclick="this.form.submit()" class="btn btn-default button"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>
		<div class="form-group search_intro">
			<?php if (!empty($this->searchword)):?>
				<?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?>
			<?php endif;?>
		</div>
		<input type="hidden" name="task" value="search" />
	</div>

	<div class="search-form_section">
		<h4><?php echo JText::_('COM_SEARCH_FOR');?> </h4>
		<div class="phrases-box inline-inputs">
			<?php echo $this->lists['searchphrase']; ?>
		</div>
		<div class="ordering-box form-inline">
			<div class="form-group">
				<label for="ordering" class="ordering">
					<?php echo JText::_('COM_SEARCH_ORDERING');?>
				</label>
				<?php echo $this->lists['ordering'];?>
			</div>
		</div>
	</div>

	<?php if ($this->params->get('search_areas', 1)) : ?>
	<div class="search-form_section">
		<h4><?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?></h4>
		<div class="inline-inputs">
			<?php foreach ($this->searchareas['search'] as $val => $txt) :
				$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : ''; ?>
				<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> />
				<label for="area-<?php echo $val;?>">
					<?php echo JText::_($txt); ?>
				</label>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($this->total > 0) : ?>
		<div class="form-limit form-inline">
			<div class="form-group">
				<label for="limit">
					<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
				</label>
			</div>
			<div class="form-group">
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="counter form-group">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</div>
		</div>
	<?php endif; ?>

</form>
