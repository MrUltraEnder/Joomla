<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

?>
<div class="page blog-view blog-view__<?php echo $this->pageclass_sfx;?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page_heading article_heading">	

		<h1 class="page_title blog_title">
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>

		<?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
		<h4 class="page_subtitle blog_category_title">
			<?php echo $this->escape($this->params->get('page_subheading')); ?>
			<?php if ($this->params->get('show_category_title')) : ?>
				<span class="subheading-category"><?php echo $this->category->title;?></span>
			<?php endif; ?>
		</h4>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
		<div class="category_desc">
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
		<?php endif; ?>
		<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
		<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
		<?php if ($this->params->get('show_no_articles', 1)) : ?>
			<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php $leadingcount=0 ; ?>
	<?php if (!empty($this->lead_items)) : ?>
	<div class="articles-listing articles-listing__leading">
		<?php foreach ($this->lead_items as &$item) : ?>
			<div class="article listing-item leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
				<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
			</div>
			<?php $leadingcount++; ?>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<?php
		$introcount=(count($this->intro_items));
		$counter=0;
	?>

	<?php if (!empty($this->intro_items)) : ?>
		<div class="articles-listing articles-listing__intro">
		<?php foreach ($this->intro_items as $key => &$item) :
			$key= ($key-$leadingcount)+1;
			$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
			$row = $counter / $this->columns ;

			if ($rowcount==1) : ?>
				<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row ; ?>">
			<?php endif; ?>

				<div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
					<?php
						$this->item = &$item;
						echo $this->loadTemplate('item');
					?>
				</div>

			<?php $counter++; ?>
			<?php if (($rowcount == $this->columns) or ($counter ==$introcount)): ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<?php if (!empty($this->link_items)) : ?>

		<?php echo $this->loadTemplate('links'); ?>

	<?php endif; ?>


	<?php /*if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
		<div class="category_children">
			<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
			<h3>
				<?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?>
			</h3>
			<?php endif; ?>
			<?php echo $this->loadTemplate('children'); ?>
		</div>
	<?php endif;*/ ?>

	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
			<div class="articles-pagination">
				<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pagination-counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>

				<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
	<?php  endif; ?>

</div>