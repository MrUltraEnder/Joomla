<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<div class="archive-view archive-view__<?php echo $this->pageclass_sfx;?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>

	<form id="adminForm" action="<?php echo JRoute::_('index.php')?>" method="post">

		<legend class="hidelabeltxt"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>

		<div class="filter-search">
			<div class="form-group">
				<?php if ($this->params->get('filter_field') != 'hide') : ?>
				<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
				<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->filter); ?>" class="inputbox" onchange="document.getElementById('adminForm').submit();" />
				<?php endif; ?>
			<div class="form-inline">
				<div class="form-group"><?php echo $this->form->monthField; ?></div>
				<div class="form-group"><?php echo $this->form->yearField; ?></div>
				<div class="form-group"><?php echo $this->form->limitField; ?></div>
				<button type="submit" class="btn btn-default button"><?php echo JText::_('JGLOBAL_FILTER_BUTTON'); ?></button>
			</div>
		</div>


		<input type="hidden" name="view" value="archive" />
		<input type="hidden" name="option" value="com_content" />
		<input type="hidden" name="limitstart" value="0" />


		<?php echo $this->loadTemplate('items'); ?>
	</form>
</div>
