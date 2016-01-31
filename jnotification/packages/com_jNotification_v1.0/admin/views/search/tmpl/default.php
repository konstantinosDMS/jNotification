<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

$doc = JFactory::getDocument();
$doc->addstylesheet(JURI::root().'/media/com_jnotification/css/jnotification.css');

$user		= JFactory::getUser();
$userId		= $user->get('id');

?>

<form action="<?php echo JRoute::_('index.php?option=com_jnotification&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div id="filter-bar" class="btn-toolbar">
                    <div class="filter-search btn-group pull-left">	
                        <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_JNOTIFICATION_SEARCH_IN_TITLE'); ?>" />
                    </div>
                    <div class="filter-search btn-group pull-left">
                        <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                        <button type="button" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><span class="icon-remove"></span></button>
                    </div>
                </div>
	</fieldset>
	<div class="clearfix"> </div>
        <br /><br /> 
	<table class="adminlist">
		<thead>
			<tr>		
				<th width="5%">
					<?php echo JText::_('Id:'); ?>
                                </th>
				<th width="10%" class="title">
					<?php echo JText::_('Title:'); ?>
				</th>
                                <th width="10%">
					<?php echo JText::_('Message'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('Created At:'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
	
		?>
			<tr class="row<?php echo $i % 2; ?>">
				
                                <td class="center">
					<?php echo (int) $item->id; ?>
				</td>                              
        			<td class="center">
					<?php echo $this->escape($item->title); ?>
				</td>		
			        <td class="center">
					<?php echo $this->escape(strip_tags($item->message)); ?>
				</td>
                                <td class="center">
					<?php echo $this->escape($item->created); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
