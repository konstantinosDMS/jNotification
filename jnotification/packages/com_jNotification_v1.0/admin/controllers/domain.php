<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: domain.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */


// No direct access
defined('_JEXEC') or die;

class JNotificationControllerDomain extends JControllerForm
{
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		$allow = null;

		if ($categoryId)
		{
			// If the category has been passed in the URL check it.
			$allow = $user->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$categoryId = 0;

		if ($recordId)
		{
			//$categoryId = (int) $this->getModel()->getItem($recordId)->catid;
		}

		if ($categoryId)
		{
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);
		}
		else
		{
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit($data, $key);
		}
	}
        
        public function cancel($key = null){
            $this->view_list = 'inbox';
            parent::cancel($key);
        }
        
        public function save($key = null, $urlVar = null){
           parent::save($key,$urlVar);
           $this->setRedirect(
           JRoute::_('index.php?option=' . $this->option . '&view=inbox'. $this->getRedirectToListAppend(), false));
        }
}
