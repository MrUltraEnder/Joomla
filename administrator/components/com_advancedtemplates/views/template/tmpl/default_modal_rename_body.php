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
<div id="template-manager-rename" class="form-horizontal">
	<div class="control-group">
		<div class="control-label">
			<label for="new_name" class="modalTooltip" title="<?php echo JHtml::tooltipText(JText::_('COM_TEMPLATES_NEW_FILE_NAME')); ?>">
				<?php echo JText::_('COM_TEMPLATES_NEW_FILE_NAME'); ?>
			</label>
		</div>
		<div class="controls">
			<div class="input-append">
				<input class="input-xlarge" type="text" name="new_name" required />
				<span class="add-on">.<?php echo JFile::getExt($this->fileName); ?></span>
			</div>
		</div>
	</div>
</div>
