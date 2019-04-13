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

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\MVC\Controller\AdminController as JControllerAdmin;
use Joomla\CMS\MVC\Model\BaseDatabaseModel as JModel;
use Joomla\CMS\Session\Session as JSession;

/**
 * Template styles list controller class.
 */
class AdvancedTemplatesControllerStyles extends JControllerAdmin
{
	/**
	 * @var  string  The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_TEMPLATES';

	/**
	 * Method to clone and existing template style.
	 *
	 * @return  void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$pks = $this->input->post->get('cid', [], 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_TEMPLATES_NO_TEMPLATE_SELECTED'));
			}

			JArrayHelper::toInteger($pks);

			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(JText::_('COM_TEMPLATES_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage(), 500);
		}

		$this->setRedirect('index.php?option=com_advancedtemplates&view=styles');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string $name   The model name. Optional.
	 * @param   string $prefix The class prefix. Optional.
	 * @param   array  $config Configuration array for model. Optional.
	 *
	 * @return  JModel
	 */
	public function getModel($name = 'Style', $prefix = 'AdvancedTemplatesModel', $config = [])
	{
		$model = parent::getModel($name, $prefix, ['ignore_request' => true]);

		return $model;
	}

	/**
	 * Method to set the home template for a client.
	 *
	 * @return  void
	 */
	public function setDefault()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$pks = $this->input->post->get('cid', [], 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_TEMPLATES_NO_TEMPLATE_SELECTED'));
			}

			JArrayHelper::toInteger($pks);

			// Pop off the first element.
			$id    = array_shift($pks);
			$model = $this->getModel();
			$model->setHome($id);
			$this->setMessage(JText::_('COM_TEMPLATES_SUCCESS_HOME_SET'));
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage(), 500);
		}

		$this->setRedirect('index.php?option=com_advancedtemplates&view=styles');
	}

	/**
	 * Method to unset the default template for a client and for a language
	 *
	 * @return  void
	 */
	public function unsetDefault()
	{
		// Check for request forgeries
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		$pks = $this->input->get->get('cid', [], 'array');
		JArrayHelper::toInteger($pks);

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_TEMPLATES_NO_TEMPLATE_SELECTED'));
			}

			// Pop off the first element.
			$id    = array_shift($pks);
			$model = $this->getModel();
			$model->unsetHome($id);
			$this->setMessage(JText::_('COM_TEMPLATES_SUCCESS_HOME_UNSET'));
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage(), 500);
		}

		$this->setRedirect('index.php?option=com_advancedtemplates&view=styles');
	}

	/**
	 * Method to set the color of items
	 *
	 * @return  void
	 */
	public function setcolor()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$pks = $this->input->post->get('cid', [], 'array');
		JArrayHelper::toInteger($pks);

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('ATP_NO_TEMPLATE_STYLES_SELECTED'));
			}
			$color = $this->input->post->get('setcolor', '', 'string');
			$model = $this->getModel();
			$model->setcolor($pks, $color);
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage(), 500);
		}

		$this->setRedirect('index.php?option=com_advancedtemplates&view=styles');
	}
}
