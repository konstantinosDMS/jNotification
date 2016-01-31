<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default_body.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted Access');

echo '<tbody>';

$app = JFactory::getApplication('administrator');
 
foreach ($this->items as $i => $item) :
$app->setUserState('message_id_'.$item->id, $item->id);

?>

    <tr class="row<?php echo $i % 2; ?>">
    <td class="center">
    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
    </td>
    <td class="center"><?php echo (int)$item->id; ?></td>
    <td class="center"><a href="index.php?option=com_jnotification&view=notification&layout=viewMessage&id=<?php echo (int)$item->id; ?>" ><?php echo $this->escape(strip_tags($item->title)); ?></a></td>
    <td class="center"><?php echo $this->escape($item->domain); ?></td>
    <td class="center"><?php if ($this->escape($item->status)==1) echo 'Read'; else echo 'UnRead'; ?></td>
    <td class="center"><?php echo $this->escape($item->created); ?></td>
    </tr>

<?php 
  endforeach; 
  echo '</tbody>'; 
?>


