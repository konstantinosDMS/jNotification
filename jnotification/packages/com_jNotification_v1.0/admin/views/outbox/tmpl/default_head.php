<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default_head.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted Access');

?>

<thead>
			<tr>
				<th width="3%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="5%">
					<?php echo JText::_('Id:'); ?>
				</th>
                                <th width="5%">
					<?php echo JText::_('Title:'); ?>
				</th>
                                <th width="5%">
					<?php echo JText::_('Message:'); ?>
				</th>
                                <th width="5%">
					<?php echo JText::_('Send To:'); ?>
				</th>
                                <th width="5%">
					<?php echo JText::_('Status:'); ?>
				</th>
                                <th width="5%">
					<?php echo JText::_('Created At:'); ?>
				</th>   
                                <th width="5%">
					<?php echo JText::_('Succeed:'); ?>
				</th>   
			</tr>
</thead>
