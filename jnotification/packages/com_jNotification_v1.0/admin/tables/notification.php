<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: notification.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access
defined('_JEXEC') or die;


class JNotificationTableNotification extends JTable
{

	public function __construct(&$db)
	{
		parent::__construct('#__notification_header', 'id', $db);
	}


	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		
                $this->created	= $date->toSql();
                
        	// Attempt to store the user data.
		return parent::store($updateNulls);
	}


	public function check()
	{
		// check for valid name
		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_JNOTIFICATION_ERR_TABLES_TITLE'));
			return false;
		}

		// check for existing name
		$query = 'SELECT id FROM #__notification_header WHERE title = '.$this->_db->Quote($this->title);
		$this->_db->setQuery($query);

           
		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::_('COM_JNOTIFICATION_ERR_TABLES_NAME'));
			return false;
		}
                
		return true;
	}
        
      

	
}
