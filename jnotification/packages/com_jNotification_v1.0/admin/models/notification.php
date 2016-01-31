<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: notification.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class JNotificationModelNotification extends JModelAdmin
{

	protected $text_prefix = 'COM_JNOTIFICATION';


	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return ;
			}
			$user = JFactory::getUser();

			if ($record->catid) {
				return $user->authorise('core.delete', 'com_jnotification.category.'.(int) $record->catid);
			}
			else {
				return parent::canDelete($record);
			}
		}
	}


	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_jnotification.category.'.(int) $record->catid);
		}
		else {
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'Notification', $prefix = 'JNotificationTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_jnotification.notification', 'notification', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('notification.id')) {
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		return $form;
	}


	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_jnotification.edit.notification.data', array());

		if (empty($data)) {
                        $app = JFactory::getApplication('administrator');
                        
                        $value = (int)$app->getUserState('message_id_'.(int)JRequest::getInt('id'));
                        if ($value == (int)JRequest::getInt('id') || $value='') $data = $this->getItem($value);

			// Prime some default values.
			if ($this->getState('notification.id') == 0) {
                           
				$app = JFactory::getApplication();
				$data->set('catid', JRequest::getInt('catid', $app->getUserState('com_jnotification.notification.filter.category_id')));
			}
		}

		return $data;
	}


	public function getItem($pk = null)
	{
	        $item = parent::getItem($pk); 
		
                //var_dump($item);
		return $item;
	}


	protected function prepareTable(&$table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
		
	}


	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		return $condition;
	}  

}
