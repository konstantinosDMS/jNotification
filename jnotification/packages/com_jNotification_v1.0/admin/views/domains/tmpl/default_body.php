<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default_body.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted Access');
JSession::checkToken('get') or die('Invalid Token');

echo '<tbody>';

foreach ($this->items as $i => $item) :
?>

    <tr class="row<?php echo $i % 2; ?>">
    <td class="center">
       <?php echo JHtml::_('grid.id', $i, $item->id); ?>
    </td>
    <td class="center"><?php echo (int)$item->id; ?></td>
    <td class="center"><?php echo $this->escape(strip_tags($item->domains)); ?></td>
    </tr>

<?php 
endforeach;
echo '</tbody>';
?>  

   
    
    
       

    
   


