<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: view.html.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class JNotificationViewSearch extends JViewLegacy
{
    
	protected $items;
	protected $pagination;
	protected $state;
         

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

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
		require_once JPATH_COMPONENT.'/helpers/jnotification.php';

		$state	= $this->get('State');
		$canDo	= JNotificationHelper::getActions($state->get('filter.category_id'));
		$user	= JFactory::getUser();
                
                JToolBarHelper::title(JText::_('COM_JNOTIFICATION_MANAGER_SEARCH_NOTIFICATIONS'), 'jnotification');
              		
             	if ($canDo->get('core.edit')) {
			JToolBarHelper::cancel('search.cancel');
                }
                
		JToolBarHelper::help('JHELP_COMPONENTS_JNOTIFICATION_LINKS_EDIT',true);
	}
}
