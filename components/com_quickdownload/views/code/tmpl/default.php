<?php
/*
 * @component QuickDownload
 * @version 3.1 'QD-03'
 * @website : http://www.ionutlupu.me
 * @copyright Ionut Lupu. All rights reserved.
 * @license : http://www.gnu.org/copyleft/gpl.html GNU/GPL , see license.txt
 */
 
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

?>
<div id="quickdownload">
	<form action="<?php echo JRoute::_('index.php?option=com_quickdownload&view=code', false); ?>" method="post" name="adminForm" id="adminForm">
	
		<?php if ($this->category !== '') {?>
			
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?php echo JText::_('COM_QUICKDOWNLOAD_ITEMS_IN_CATEGORY');?>
			</div>
			<div class="panel-body">
				<?php if ( $this->items ) { ?>
					<?php foreach ($this->items  as $item) { ?>
					<div class="col-md-12">
						<div class="col-md-8">
							<i class="file"></i><a href="<?php echo JRoute::_('index.php?option=com_quickdownload&task=file.download&category='. $this->category .'&id=' . $item->id, false );?>">
								<?php echo $item->title;?>
							</a>
						</div>
						<?php if ($this->options->get('show_file_type',0)) { ?>
						<div class="col-md-2">
							<?php echo $item->type; ?>
						</div>
						<?php } ?>
						
						<?php if ($this->options->get('show_file_size',0)) { ?>
						<div class="col-md-2">
							<?php echo $this->model->parseSize($item->size); ?>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
				<?php } ?>
			</div>
			<div class="panel-footer">
				<div class="row">
					<a href="<?php echo JRoute::_('index.php?option=com_quickdownload&view=code&category=', false );?>"><?php echo JText::_('COM_QUICKDOWNLOAD_BACK_LIST_CATEGORIES');?></a>
				</div>
			</div>
		</div>
		
		<?php } else { ?>
			
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?php echo JText::_('COM_QUICKDOWNLOAD_FILE_CATEGORIES');?>
			</div>
			<div class="panel-body">
				<?php if ( $this->categories ) { ?>
					<?php foreach ($this->categories as $category) { ?>
					<div class="col-md-12">
						<div class="col-md-6">
							<i class="folder"></i>
							<a href="<?php echo JRoute::_('index.php?option=com_quickdownload&view=code&category=' . $category->id, false );?>"><?php echo $category->name;?></a>
						</div>
					</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		
		<?php } ?>


	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>

	</form>
</div>
