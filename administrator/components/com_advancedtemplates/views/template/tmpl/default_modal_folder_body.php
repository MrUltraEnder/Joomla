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

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;

$input = JFactory::getApplication()->input;
?>
<div id="template-manager-folder" class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="span6 column-right">
				<form method="post" action="<?php echo JRoute::_('index.php?option=com_advancedtemplates&task=template.createFolder&id=' . $input->getInt('id') . '&file=' . $this->file); ?>" class="well">
					<fieldset class="form-inline">
						<label><?php echo JText::_('COM_TEMPLATES_FOLDER_NAME'); ?></label>
						<input type="text" name="name" required />
						<input type="hidden" class="address" name="address" />
						<?php echo JHtml::_('form.token'); ?>
						<input type="submit" value="<?php echo JText::_('COM_TEMPLATES_BUTTON_CREATE'); ?>" class="btn btn-primary" />
					</fieldset>
				</form>
			</div>
			<div class="span6 column-left">
				<?php echo $this->loadTemplate('folders'); ?>
				<hr class="hr-condensed" />
			</div>
		</div>
	</div>
</div>
