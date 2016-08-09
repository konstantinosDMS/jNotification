<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: domains.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class JNotificationModelDomains extends JModelList
{
    	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id','a.id',
                                'domains','a.domains',
                             	'title', 'a.title'
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
		$query->select('a.*');
		$query->from($db->quoteName('#__notification_domains').' AS a');
         
                $query->order($db->escape('a.domains asc'));
                                
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
        
        public function deleteOrphans(){
           $query = $this->getDbo()->getQuery(true);
           $query->delete();
           $query->from('#__notification_header');
           $query->where('id NOT IN (SELECT DISTINCT message_id FROM #__notification_user)');
        
            $this->getDbo()->setQuery($query);
         
            if (!$this->getDbo()->query()){
                     $this->setError(JText::_('COM_JNOTIFICATION_UPDATE_HEADER'));
                     return false;
            } 
        
            $query->clear();
                  
            return true;
        }
       
        public function updateRecords($id=0){
            $query = $this->getDbo()->getQuery(true);
            $query->update('#__notification_header');
            $query->set('status=1');
            $query->where('id='.(int)$id);
           
            $this->getDbo()->setQuery($query);
            
            if (!$this->getDbo()->query()) {
                $this->setError(JText::_('COM_JNOTIFICATION_UPDATE_HEADER'));
                return false;
            }
            
            $query->clear();
            return true;
        }
        
        public function getId(){
            $query = $this->getDbo()->getQuery(true);
            $query->select('id');
            $query->from('#__notification_header');
            $query->where('id NOT IN (SELECT DISTINCT message_id FROM #__notification_user)');
                    
            //var_dump($query);                       
            $this->getDbo()->setQuery($query);
            $record = $this->getDbo()->loadResult();
            //echo $query;
            $query->clear();
            return $record;
        }
        
        public function insertMoreRecords($array=array()){
            $app = JFactory::getApplication('administration');
            $id = (int)$app->getUserState('message.id');
     
            if (empty($id)) $id = (int)$this->getId();
             
            for ($i=0;$i<count($array);$i++){
                $query = $this->getDbo()->getQuery(true);
                $query->select('domains as domains,siteName as siteName');
                $query->from('#__notification_domains');
                $query->where('id='.(int)$array[$i]);
                    
                //var_dump($query);                       
                $this->getDbo()->setQuery($query);
                $domain = $this->getDbo()->loadObject();
            
                $query->clear();   
                           
                // Initialize the query object
                $query = $this->getDbo()->getQuery(true);
                $query->insert('#__notification_user');
                $query->set('domain='. $this->getDbo()->quote((($domain->domains.'/'. $domain->siteName))) );
                $query->set('message_id='.$id);
                $query->set('inbox=0');
                $query->set('outbox=1');
                $query->set('status=0');
            
                $this->getDbo()->setQuery($query); 
           
                if (!$this->getDbo()->query()){
                    
                    $query1 = $this->getDbo()->getQuery(true);
                    $query1->delete();
                    $query1->from('#__notification_header');
                    $query1->where('id='.(int)$id);
        
                    $this->getDbo()->setQuery($query1);
         
                    if (!$this->getDbo()->query()){
                     $this->setError(JText::_('COM_JNOTIFICATION_INSERT_USERS'));
                     return false;
                    } 
        
                    $query1->clear();

                    $query1 = $this->getDbo()->getQuery(true);
                    $query1->delete();
                    $query1->from('#__notification_user');
                    $query1->where('message_id='.(int)$id);
        
                    $this->getDbo()->setQuery($query1);
         
                    if (!$this->getDbo()->query()){
                     $this->setError(JText::_('COM_JNOTIFICATION_INSERT_USERS'));
                     return false;
                    } 
        
                    $query1->clear();
                                        
                    $this->setError(JText::_('COM_JNOTIFICATION_INSERT_USERS'));
                    return false;
                }
            }
          
            if ($this->updateRecords($id)) return $id;
            else {
                $query1 = $this->getDbo()->getQuery(true);
                $query1->delete();
                $query1->from('#__notification_header');
                $query1->where('id='.(int)$id);
                $this->getDbo()->setQuery($query1);

                if (!$this->getDbo()->query()){
                     $this->setError(JText::_('COM_JNOTIFICATION_INSERT_USERS'));
                     return false;
                } 
                $query1->clear();
                $query1->delete();
                $query1->from('#__notification_user');
                $query1->where('message_id='.(int)$id);

                $this->getDbo()->setQuery($query1);

                if (!$this->getDbo()->query()){
                    $this->setError(JText::_('COM_JNOTIFICATION_INSERT_USERS'));
                    return false;
                } 

                $query1->clear();                    
                return false;
            }     
        }
}