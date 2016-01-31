<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: domains.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

 class JNotificationControllerDomains extends  JControllerLegacy{ 
    protected $myModel;
    
    public function cancel($key = null){   
        $myModel = $this->getModel('Domains','JNotificationModel'); 
        $myModel->deleteOrphans();
        $this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=inbox', false));
    }
    
    public function save($key = null, $urlVar = null){
        
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
              
        $myModel = $this->getModel('Domains','JNotificationModel');
        $id = $myModel -> insertMoreRecords(JRequest::getVar('cid', array(), 'post', 'array'));
      
        if ($id){
            $app = JFactory::getApplication('administration');
            
            $app ->setUserState('domains.edit.id', JRequest::getVar('cid',array(),'post','array'));
            $app->setUserState('message.id', (int)$id);
            
            $this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=javascriptOutbox&id='.(int)$id.'&'.JSession::getFormToken().'=1',false)); 
        }
        else{
                // Check-out failed, display a notice but allow the user to see the record.
	        $this->setError(JText::sprintf('J_NOTIFICATION_ERROR', $myModel->getError()));
		$this->setMessage($this->getError(), 'error');
		$this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=inbox'));
        }
    }  
 }
