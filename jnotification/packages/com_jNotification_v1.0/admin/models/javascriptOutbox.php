<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: javascriptOutbox.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class JNotificationModelJavascriptOutbox extends JModelList
{
    	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array();
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
	}
                
 
	public function getItems()
	{
	}
        
  
	protected function _getListQuery()
	{
	}
        
        public function getInfo($id=0){
              $query = $this->getDbo()->getQuery(true);
              $query->select('domains as domains , siteName as siteName');
              $query->from('#__notification_domains');
              $query->where('id='.(int)$id);
                    
              //var_dump($query);                       
              $this->getDbo()->setQuery($query);
              $domain = $this->getDbo()->loadObject();
          
              $query->clear();
              return $domain;
        }
        
        public function getMessage($id=0){
             $query = $this->getDbo()->getQuery(true);
             $query->select('message as message, title as title');
             $query->from('#__notification_header');
             $query->where('id='.(int)$id);
                    
             //var_dump($query);                       
             $this->getDbo()->setQuery($query);
             $message = $this->getDbo()->loadObjectList();
            
             $query->clear();
             return $message;
        }
        
        public function updateRecords($domain='',$messageId=''){
            
            $domainReceiver = strip_tags($_POST['domainReceiver']);
            $domainReceiver = mysql_escape_string($domainReceiver);
            $domainReceiver =  preg_replace('~^https?://~i', NULL,  $domainReceiver);
            $domainReceiver = preg_replace('/([\x00-\x20\xff])/se', '', $domainReceiver);
    
            $messageId = $_POST['messageId'];
            $messageId = @ereg_replace("[[:punct:][:space:][:alpha:]]+", ' ',$messageId );
            $messageId = @ereg_replace("[αβγδεζηθικλμνξοπρστυφχψωάέήίόύώϊϋ]+", ' ', $messageId);
            $messageId = @ereg_replace("[ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆΈΉΊΌΎΏΪΫ]+", ' ', $messageId);
            $messageId = @ereg_replace("[abcdefghijklmnopqrstuvwxyz]+", ' ', $messageId);
            $messageId = @ereg_replace("[ABCDEFGHIJKLMNOPQRSTUVWXYZ]+", ' ', $messageId);

            $query = $this->getDbo()->getQuery(true);
            $query->update('#__notification_user');
            $query->set('success=1');
            $query->where('domain like '.$this->getDbo()->quote($domainReceiver));
            $query->where('message_id='.(int)$messageId);
        
            $this->getDbo()->setQuery($query);
        
            if (!$this->getDbo()->query()) {
                 $this->setError(JText::_('COM_JNOTIFICATION_UPDATE_HEADER'));
                 return false;
            }
        
            $query->clear();
            return true;  
        }
}