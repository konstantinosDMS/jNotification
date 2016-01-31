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

class JNotificationControllerNotification extends JControllerForm
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
       
           $flag = parent::save($key,$urlVar);
           $model = $this->getModel();
	   $table = $model->getTable();
            // Determine the name of the primary key for the data.
	    if (empty($key))
	    {
	           $key = $table->getKeyName();
	    }

	  // To avoid data collisions the urlVar may be different from the primary key.
	    if (empty($urlVar))
	    {
		$urlVar = $key;
	    }

           $recordId = $this->input->getInt($urlVar);
           
           
        if ($flag){ 
            $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=domains&id='.(int)$recordId. $this->getRedirectToListAppend().'&'.JSession::getFormToken().'=1', false));
        }
        else{
            $this->setRedirect(JRoute::_('index.php?option='.$this->option . '&view=inbox'.$this->getRedirectToListAppend(), false));
        }
    }
    
    public function edit($key = null, $urlVar = null){
        $model = $this->getModel();
        $table = $model->getTable();
	$cid   = $this->input->post->get('cid', array(), 'array');
	$context = "$this->option.edit.$this->context";

        // Determine the name of the primary key for the data.
	if (empty($key))
	{
	    $key = $table->getKeyName();
	}

	// To avoid data collisions the urlVar may be different from the primary key.
	if (empty($urlVar))
	{
            $urlVar = $key;
	}

	// Get the previous record id (if any) and the current record id.
	$recordId = (int) (count($cid) ? $cid[0] : $this->input->getInt($urlVar));
        
        $app = JFactory::getApplication('administration');
        $app->setUserState('message.id', (int)$recordId); 
        parent::edit($key,$urlVar);
    }
}
