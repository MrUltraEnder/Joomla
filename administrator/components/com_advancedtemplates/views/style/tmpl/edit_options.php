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

// Load chosen.css
JHtml::_('formbehavior.chosen', 'select');

?>
<?php
echo JHtml::_('bootstrap.startAccordion', 'templatestyleOptions', ['active' => 'collapse0']);
$fieldSets = $this->form->getFieldsets('params');
$i         = 0;

foreach ($fieldSets as $name => $fieldSet) :
	$label = ! empty($fieldSet->label) ? $fieldSet->label : 'COM_TEMPLATES_' . $name . '_FIELDSET_LABEL';
	echo JHtml::_('bootstrap.addSlide', 'templatestyleOptions', JText::_($label), 'collapse' . ($i++));
	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<p class="tip">' . $this->escape(JText::_($fieldSet->description)) . '</p>';
	endif;
	?>
	<?php foreach ($this->form->getFieldset($name) as $field) : ?>
	<div class="control-group">
		<div class="control-label">
			<?php echo $field->label; ?>
		</div>
		<div class="controls">
			<?php echo $field->input; ?>
		</div>
	</div>
<?php endforeach;
	echo JHtml::_('bootstrap.endSlide');
endforeach;
echo JHtml::_('bootstrap.endAccordion');
