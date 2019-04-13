<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Layout\LayoutHelper as JLayoutHelper;

$app  = JFactory::getApplication();
$form = $displayData->getForm();

$fieldSets = $form->getFieldsets('params');
if (empty($fieldSets))
{
	$fieldSets = $form->getFieldsets('attribs');
}

if (empty($fieldSets))
{
	return;
}

$ignoreFieldsets = $displayData->get('ignore_fieldsets') ?: [];
$ignoreFields    = $displayData->get('ignore_fields') ?: [];
$extraFields     = $displayData->get('extra_fields') ?: [];

if ( ! empty($displayData->hiddenFieldsets))
{
	// These are required to preserve data on save when fields are not displayed.
	$hiddenFieldsets = $displayData->hiddenFieldsets ?: [];
}

if ( ! empty($displayData->configFieldsets))
{
	// These are required to configure showing and hiding fields in the editor.
	$configFieldsets = $displayData->configFieldsets ?: [];
}

if ($displayData->get('show_options', 1))
{
	foreach ($fieldSets as $name => $fieldSet)
	{
		if (in_array($name, $ignoreFieldsets) || ( ! empty($configFieldsets) && in_array($name, $configFieldsets))
			|| ! empty($hiddenFieldsets) && in_array($name, $hiddenFieldsets)
		)
		{
			continue;
		}

		if ( ! empty($fieldSet->label))
		{
			$label = JText::_($fieldSet->label);
		}
		else
		{
			$label = strtoupper('JGLOBAL_FIELDSET_' . $name);
			if (JText::_($label) == $label)
			{
				$label = strtoupper('COM_TEMPLATES_' . $name . '_FIELDSET_LABEL');
			}
			$label = JText::_($label);
		}

		echo JHtml::_('bootstrap.addTab', 'myTab', 'attrib-' . $name, $label);

		$displayData->fieldset = $name;
		echo JLayoutHelper::render('joomla.edit.fieldset', $displayData);

		echo JHtml::_('bootstrap.endTab');
	}
}
else
{
	$html   = [];
	$html[] = '<div style="display:none;">';
	foreach ($fieldSets as $name => $fieldSet)
	{
		if (in_array($name, $ignoreFieldsets))
		{
			continue;
		}

		if (in_array($name, $hiddenFieldsets))
		{
			foreach ($form->getFieldset($name) as $field)
			{
				echo $field->input;
			}
		}
	}
	$html[] = '</div>';

	echo implode('', $html);
}
