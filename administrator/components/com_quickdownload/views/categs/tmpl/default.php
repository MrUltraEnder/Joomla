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
$saveOrder	= $listOrder=='a.ordering';

?>

<form action="<?php echo JRoute::_('index.php?option=com_quickdownload&view=categs'); ?>" method="post" name="adminForm" id="adminForm">
	
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
		
		</div>

	<div class="clr"> </div>
	
		<table class="table table-striped">
		<thead>
			<tr>
			
				<th width="1%">
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				
				<th width="44%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_CATEGORIES_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				
				<th width="25%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_CATEGORIES_STATUS', 'a.published', $listDirn, $listOrder); ?>
				</th>
				
				<th width="25%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
				</th>
				
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'COM_QUICKDOWNLOAD_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				
			</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) {
			$ordering	= ($listOrder == 'a.ordering');
		?>
		<tr class="row<?php echo $i % 2; ?>">

			<td>
				<?php echo JHtml::_('grid.id', $i, $item->id); ?>
			</td>
			
			<td>
				<a href="index.php?option=com_quickdownload&task=categ.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->name);?></a>
			</td>
			
			<td>
				<?php echo JHtml::_('jgrid.published',  $item->published, $i , 'categs.', $canChange , 'cb'); ?>
			</td>
			
			<td class="order">
				<?php if ($canChange) : ?>
					<?php if ($saveOrder) : ?>
						<?php if ($listDirn == 'asc') : ?>
							<span><?php echo $this->pagination->orderUpIcon($i, true, 'categs.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'categs.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php elseif ($listDirn == 'desc') : ?>
							<span><?php echo $this->pagination->orderUpIcon($i, true, 'categs.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
							<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, true, 'categs.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php endif; ?>
					<?php endif; ?>
					<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled; ?> class="text-area-order" />
				<?php else : ?>
					<?php echo $item->ordering; ?>
				<?php endif; ?>
			</td>

			<td class="center">
				<?php echo $item->id; ?>
			</td>
			
		</tr>

		<?php } ?>
		</tbody>
					
		<tfoot>
			<tr>
				<td colspan="5">
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
