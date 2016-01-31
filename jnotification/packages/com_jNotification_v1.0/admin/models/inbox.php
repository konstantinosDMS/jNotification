<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: inbox.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class JNotificationModelInbox extends JModelList
{
    	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id','a.id','b.id',
                                'message','a.message',
                             	'title', 'a.title',
				'status', 'a.status','b.status',
				'message_id', 'b.message_id',
				'created', 'a.created',
                                'domain','b.domain',
                                'inbox','b.inbox',
                                'outbox','b.outbox'
			);
		}
                $this->session = JFactory::getSession();
		parent::__construct($config);
	}


	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');               
                             
                // Load the parameters.
		$params = JComponentHelper::getParams('com_jnotification');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
	}

	protected function getStoreId($id = '')
	{
		return parent::getStoreId($id);
	}


	protected function getListQuery()
	{
		// Create a new query object.
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);
		
                $url = parse_url(JURI::root());                          
                
		// Select the required fields from the table.
		$query->select('DISTINCT a.id as id,a.title as title, a.created as created');
		$query->from($db->quoteName('#__notification_header').' AS a');
         
                //Join over ergo3_correspondence_users	
                $query->select('b.domain as domain, b.status as status');
		$query->join('INNER',$db->quoteName('#__notification_user').' AS b on b.message_id=a.id');
                
                $query->where('b.inbox=1');
                $query->where('a.status=1');
                
                $query->order($db->escape('a.created desc'));
                $query->order($db->escape('b.status desc'));
                
                //echo $query.'<br />';
		return $query;             
	}
                
 
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getListQuery();
		$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}
        
  
	protected function _getListQuery()
	{
		// Capture the last store id used.
		static $lastStoreId;

		// Compute the current store id.
		$currentStoreId = $this->getStoreId();

		// If the last store id is different from the current, refresh the query.
		if ($lastStoreId != $currentStoreId || empty($this->query))
		{
			$lastStoreId = $currentStoreId;
			$this->query = $this->getListQuery();
		}
		return $this->query;
	}            
}