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
<div id="template-manager-copy" class="container-fluid">
	<div class="row-fluid">
		<div class="form-horizontal">
			<div class="control-group">
				<div class="control-label">
					<label for="new_name" class="modalTooltip" title="<?php echo JHtml::_('tooltipText', 'COM_TEMPLATES_TEMPLATE_NEW_NAME_LABEL', 'COM_TEMPLATES_TEMPLATE_NEW_NAME_DESC'); ?>">
						<?php echo JText::_('COM_TEMPLATES_TEMPLATE_NEW_NAME_LABEL'); ?>
					</label>
				</div>
				<div class="controls">
					<input class="input-xlarge" type="text" id="new_name" name="new_name" />
				</div>
			</div>
		</div>
	</div>
</div>
