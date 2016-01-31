<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jcorrespondence
 * @version	$Id: correspondence.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2010- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access
defined('_JEXEC') or die;


class JCorrespondenceTableCorrespondence extends JTable
{

	public function __construct(&$db)
	{
		parent::__construct('#__correspondence_header', 'postid', $db);
	}


	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		
                $this->posted	= $date->toSql();
		

		// Attempt to store the user data.
		return parent::store($updateNulls);
	}


	public function check()
	{
		if (JFilterInput::checkAttribute(array ('href', $this->url))) {
			$this->setError(JText::_('COM_JCORRESPONDENCE_ERR_TABLES_PROVIDE_URL'));
			return false;
		}

		// check for valid name
		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_CORRESPONDENCE_ERR_TABLES_TITLE'));
			return false;
		}

		// check for existing name
		$query = 'SELECT postid FROM #__correspondence_header WHERE title = '.$this->_db->Quote($this->title).' AND catid = '.(int) $this->catid;
		$this->_db->setQuery($query);

           
		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->postid)) {
			$this->setError(JText::_('COM_JCORRESPONDENCE_ERR_TABLES_NAME'));
			return false;
		}
                
		return true;
	}
        
      

	
}
