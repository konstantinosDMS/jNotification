<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: view.html.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class JNotificationViewNotification extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item	= $this->get('Item');
		$this->form	= $this->get('Form');
                
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
 
      		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
                JToolBarHelper::title(JText::_('COM_JNOTIFICATION_VIEW_JNOTIFICATION'), 'jnotification');
         	if (JRequest::getCmd('layout') != 'viewMessage') JToolBarHelper::apply('notification.apply');
		JToolBarHelper::cancel('notification.cancel');
		JToolBarHelper::help('JHELP_COMPONENTS_JNOTIFICATION_LINKS_EDIT',true);
	}
}
