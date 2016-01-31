<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default_body.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted Access');

echo '<tbody>';

$myModel = $this->getModel();
$app = JFactory::getApplication('administrator');

foreach ($this->items as $i => $item) :
$app->setUserState('message_id_'.$item->id, $item->id);
?>

<tr class="row<?php echo $i % 2; ?>">
<td class="center">
<?php echo JHtml::_('grid.id', $i, $item->id); ?>
</td>
<td class="center"><?php echo (int)$item->id; ?></td>
<td class="center"><?php echo $this->escape(strip_tags($item->title)); ?></td>
<td class="center"><?php echo $this->escape(strip_tags($item->message)); ?></td>
<td class="center"> <?php echo $this->escape(strip_tags($item->domain)); ?></td>
<td class="center"><?php if ($item->status==0) echo 'Receiver have not Read this message yet'; else echo 'Receiver have Read this message'; ?></td>
<td class="center"><?php echo $this->escape($item->created); ?></td>
<td class="center"><?php if ($item->success==0) echo 'The message has not been sent to the receiver'; else echo 'The message has been sent to the receiver';  ?></td>
</tr>

<?php 
endforeach; 
echo '</tbody>'; 
?>


