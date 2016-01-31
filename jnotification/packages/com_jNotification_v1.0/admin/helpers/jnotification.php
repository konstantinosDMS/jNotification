<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: jnotification.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access
defined('_JEXEC') or die;


class JNotificationHelper
{

	public static function addSubmenu($vName = 'inbox')
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_JNOTIFICATION_SUBMENU_NOTIFICATIONS'),
			'index.php?option=com_jnotification&view=inbox',
			$vName == 'inbox'
		);
                
                $document = JFactory::getDocument();
               
                
	}

	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		if (empty($categoryId)) {
			$assetName = 'com_jnotification';
			$level = 'component';
		}
		$actions = JAccess::getActions('com_jnotification', $level);
		foreach ($actions as $action) {
			$result->set($action->name,	$user->authorise($action->name, $assetName));
		}
		return $result;
	}
}
