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
                
                if ($view != 'domain'){
		        // Load the submenu.
	        	JNotificationHelper::addSubmenu(JRequest::getCmd('view', 'inbox'));
                }
               
		// Check for edit form.
		if ($view == 'notification' && $layout == 'edit' && !$this->checkEditId('com_jnotification.edit.notification', $id)) {
			
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
