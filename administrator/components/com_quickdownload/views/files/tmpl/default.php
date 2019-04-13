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

JHtml::_('behavior.tooltip');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_quickdownload');
$canChange	= $user->authorise('core.edit.state','com_quickdownload');
$state		= array ('0'=>JText::_('COM_QUICKDOWNLOAD_UNPUBLISHED'),'1'=>JText::_('COM_QUICKDOWNLOAD_PUBLISHED'));


?>

<form action="<?php echo JRoute::_('index.php?option=com_quickdownload&view=files'); ?>" method="post" name="adminForm" id="adminForm">
	
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>

	<div id="j-main-container" class="span10">

		<div id="filter-bar" class="btn-toolbar">
			
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="" />
			</div>

			<div class="btn-group pull-left">
				<button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			
			<div class="btn-group pull-right">
				<select name="filter_published" class="inputbox" onchange="this.form.submit()">
					<option value=""> - <?php echo JText::_('COM_QUICKDOWNLOAD_FILES_SELECT_STATE');?> - </option>
					<?php echo JHtml::_('select.options', $state  , 'value', 'text', $this->state->get('filter.published'));?>
				</select>
			</div>

			<div class="btn-group pull-right">
				<select name="filter_category" class="inputbox" onchange="this.form.submit()">
					<option value=""> - <?php echo JText::_('COM_QUICKDOWNLOAD_FILES_SELECT_CATEGORY');?> - </option>
					<?php echo JHtml::_('select.options', $this->categories  , 'value', 'text', $this->state->get('filter.category'));?>
				</select>
			</div>
			
		</div>

		<div class="clr"> </div>
	
		<table class="table table-striped">
		<thead>
			<tr>
			
				<th width="1%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>

				<th width="45%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_FILES_NAME', 'a.title', $listDirn, $listOrder); ?>
				</th>
				
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_FILES_STATUS', 'a.published', $listDirn, $listOrder); ?>
				</th>
				
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_FILES_TYPE', 'a.type', $listDirn, $listOrder); ?>
				</th>
				
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_FILES_SIZE', 'a.size', $listDirn, $listOrder); ?>
				</th>
				
				<th width="14%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_FILES_CREATED', 'a.created', $listDirn, $listOrder); ?>
				</th>
				
				<th width="10%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_FILES_HITS', 'a.hits', $listDirn, $listOrder); ?>
				</th>
				
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				
			</tr>
		</thead>

			<tbody>
			<?php foreach ($this->items as $i => $item) {
			?>
			<tr class="row<?php echo $i % 2; ?>">

				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				
				<td>
					<a href="index.php?option=com_quickdownload&task=file.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->title);?></a>
				</td>
				
				<td>
					<?php echo JHtml::_('jgrid.published',  $item->published, $i , 'files.', $canChange , 'cb'); ?>
				</td>
				
				<td>
					<?php echo $this->escape($item->type);  ?>
				</td>
				
				<td>
					<?php echo QuickDownloadHelper::parseSize($item->size);  ?>
				</td>
				
				<td>
					<?php echo $item->created;  ?>
				</td>
				
				<td>
					<?php echo $item->hits;  ?>
				</td>
				
				<td>
					<?php echo $item->id; ?>
				</td>
				
			</tr>

			<?php } ?>
			</tbody>
					
			<tfoot>
				<tr>
					<td colspan="8">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</form>
