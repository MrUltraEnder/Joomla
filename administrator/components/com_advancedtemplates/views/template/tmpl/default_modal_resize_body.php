<?php
/**
 * @package         Advanced Template Manager
 * @version         3.7.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2019 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

/**
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;

?>
<div id="template-manager-resize" class="container-fluid">
	<div class="row-fluid">
		<div class="control-group">
			<div class="control-label">
				<label for="height" class="modalTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_TEMPLATES_IMAGE_HEIGHT'); ?>">
					<?php echo JText::_('COM_TEMPLATES_IMAGE_HEIGHT') ?>
				</label>
			</div>
			<div class="controls">
				<input class="input-xlarge" type="number" name="height" placeholder="<?php echo $this->image['height']; ?> px" required />
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<label for="width" class="modalTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_TEMPLATES_IMAGE_WIDTH'); ?>">
					<?php echo JText::_('COM_TEMPLATES_IMAGE_WIDTH') ?>
				</label>
			</div>
			<div class="controls">
				<input class="input-xlarge" type="number" name="width" placeholder="<?php echo $this->image['width']; ?> px" required />
			</div>
		</div>
	</div>
</div>
