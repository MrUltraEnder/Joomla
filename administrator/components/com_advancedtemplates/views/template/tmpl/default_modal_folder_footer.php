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
<form id="deleteFolder" method="post" action="<?php echo JRoute::_('index.php?option=com_advancedtemplates&task=template.deleteFolder&id=' . $input->getInt('id') . '&file=' . $this->file); ?>">
	<fieldset>
		<button type="button" class="btn" data-dismiss="modal"><?php echo JText::_('COM_TEMPLATES_TEMPLATE_CLOSE'); ?></button>
		<input type="hidden" class="address" name="address" />
		<?php echo JHtml::_('form.token'); ?>
		<input type="submit" value="<?php echo JText::_('COM_TEMPLATES_BUTTON_DELETE'); ?>" class="btn btn-danger" />
	</fieldset>
</form>
