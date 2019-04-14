<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

?>
<div class="category-view category-view__contact category-view__<?php echo $this->pageclass_sfx;?>">
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>

<?php if($this->params->get('show_category_title', 1)) : ?>
	<h2>
		<?php echo JHtml::_('content.prepare', $this->category->title, '', 'com_contact.category'); ?>
	</h2>
<?php endif; ?>

<?php if ($this->params->def('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
	<div class="category_image">
		<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
	</div>
	<?php endif; ?>	
	
	<?php if ($this->params->get('show_description') && $this->category->description) : ?>
	<div class="category_desc">	
		<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_contact.category'); ?>
	</div>
	<?php endif; ?>
	
<?php endif; ?>

<?php echo $this->loadTemplate('items'); ?>

<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
<div class="category_children">
	<h3><?php echo JText::_('JGLOBAL_SUBCATEGORIES') ; ?></h3>
	<?php echo $this->loadTemplate('children'); ?>
</div>
<?php endif; ?>
</div>
