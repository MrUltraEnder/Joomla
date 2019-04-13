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

use Joomla\CMS\Application\ApplicationHelper as JApplicationHelper;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Uri\Uri as JUri;

/**
 * JHtml helper class.
 */
class JHtmlTemplates
{
	/**
	 * Display the thumb for the template.
	 *
	 * @param   string  $template The name of the template.
	 * @param   integer $clientId The application client ID the template applies to
	 *
	 * @return  string  The html string
	 */
	public static function thumb($template, $clientId = 0)
	{
		$client   = JApplicationHelper::getClientInfo($clientId);
		$basePath = $client->path . '/templates/' . $template;
		$thumb    = $basePath . '/template_thumbnail.png';
		$preview  = $basePath . '/template_preview.png';
		$html     = '';

		if (file_exists($thumb))
		{
			JHtml::_('bootstrap.tooltip');

			$clientPath = ($clientId == 0) ? '' : 'administrator/';
			$thumb      = $clientPath . 'templates/' . $template . '/template_thumbnail.png';
			$html       = JHtml::_('image', $thumb, JText::_('COM_TEMPLATES_PREVIEW'));

			if (file_exists($preview))
			{
				$html = '<a href="#' . $template . '-Modal" role="button" class="thumbnail pull-left hasTooltip" data-toggle="modal" title="' .
					JHtml::tooltipText('COM_TEMPLATES_CLICK_TO_ENLARGE') . '">' . $html . '</a>';
			}
		}

		return $html;
	}

	/**
	 * Renders the html for the modal linked to thumb.
	 *
	 * @param   string  $template The name of the template.
	 * @param   integer $clientId The application client ID the template applies to
	 *
	 * @return  string  The html string
	 */
	public static function thumbModal($template, $clientId = 0)
	{
		$client   = JApplicationHelper::getClientInfo($clientId);
		$basePath = $client->path . '/templates/' . $template;
		$baseUrl  = ($clientId == 0) ? JUri::root(true) : JUri::root(true) . '/administrator';
		$thumb    = $basePath . '/template_thumbnail.png';
		$preview  = $basePath . '/template_preview.png';
		$html     = '';

		if (file_exists($thumb))
		{
			if (file_exists($preview))
			{
				$preview = $baseUrl . '/templates/' . $template . '/template_preview.png';
				$footer  = '<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">'
					. JText::_('JTOOLBAR_CLOSE') . '</a>';

				$html .= JHtml::_(
					'bootstrap.renderModal',
					$template . '-Modal',
					[
						'title'  => JText::_('COM_TEMPLATES_BUTTON_PREVIEW'),
						'height' => '500px',
						'width'  => '800px',
						'footer' => $footer,
					],
					$body = '<div><img src="' . $preview . '" style="max-width:100%"></div>'
				);
			}
		}

		return $html;
	}
}
