<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: controller.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;


class JNotificationController extends JControllerLegacy
{    
        protected $default_view = 'Inbox';
          
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/jnotification.php';
       
		$view		= JRequest::getCmd('view', 'inbox');
		$layout 	= JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');
                $model = $this->getModel('Domains','JNotificationModel');
                $numberofdomains = (int)count($model->getItems());
               
                $app = JFactory::getApplication();
                $flagNotification = $app->getUserState('notification.flag');
                $flagDomains = $app->getUserState('domains.flag');
                
                if ($view != 'domain'){
		        // Load the submenu.
	        	JNotificationHelper::addSubmenu(JRequest::getCmd('view', 'inbox'));
                }
               
		// Check for edit form.
		if (($view == 'notification' && $layout == 'edit' && !$this->checkEditId('com_jnotification.edit.notification', $id)) || ($view == 'notification' && $layout == 'edit' && $numberofdomains==0)) {
			
                        // Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=inbox', false));

			return false;
		}
                   
                // Check for edit form.
		if ($view == 'notification' && $layout == 'viewMessage') {
			$model1 = $model = $this->getModel('Inbox','JNotificationModel');
                        $items = $model1 -> getItems();
                        
                        for ($i=0;$i<count($items);$i++){
                            if ((int)$items[$i]->id == (int)JRequest::getInt('id')){
                              $app->setUserState('com_jnotification.edit.notification.id', (int)JRequest::getInt('id'));                                
                            }
                        }
                        // Somehow the person just went to the form - we don't allow that.
			if (!$this->checkEditId('com_jnotification.edit.notification', $id)){
                            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
                            $this->setMessage($this->getError(), 'error');
                            $this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=inbox', false));

                            return false;
                        }
		}
 
                if ($view == 'notification' && $layout == 'edit' && ($flagNotification)) {
                   $flagNotification = $app->setUserState('notification.flag',false); 
                   //$this->releaseEditId('com_jnotification.edit.notification', $id);
                   $this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=domains&layout=default&id='.(int)$postId. '&'.JSession::getFormToken().'=1', false));
		}

                 // Check for edit form.
		if ($view == 'domains' && $layout == 'default' && ($flagDomains)){
                        $app->setUserState('domains.flag', false);
                }else if ($view == 'domains' && $layout == 'default' && (!$flagDomains)){
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=inbox', false));

			return false;
		}             
              	parent::display();
		return $this;
	}
}
