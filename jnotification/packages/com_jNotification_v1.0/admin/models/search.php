<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: search.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class JNotificationModelSearch extends JModelList
{

	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'posted', 'a.posted',
                                'name','uc.name',
                                'title','c.title',
                                'status','b.status',
                                'catid','a.catid'
			);
		}

		parent::__construct($config);
	}



	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_jnotification');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.title', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}


	protected function getListQuery()
	{
		// Create a new query object.
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();
                $userId=$user->get('id');
   
		// Select the required fields from the table.
		$query->select('a.id, a.message as message, a.title as title,a.created');
		$query->from($db->quoteName('#__notification_header').' AS a');
                
                $query->where('a.status=1');
   
                // Filter by search in title
		$search = $this->getState('filter.search');
                
                if (!empty($search)) {
                    $search = $db->Quote('%'.$db->escape($search, true).'%');
                    $query->where('(a.title LIKE '.$search.') OR MATCH(a.message) AGAINST ('.$search.' IN BOOLEAN MODE)');                
	        }

                $query->order($db->escape('a.created asc'));
                //echo $query.'<br />';
		return $query;   
	}
}
