<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos Dimos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addstylesheet(JURI::root().'/media/com_jnotification/css/jnotification.css');


JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

?>
    
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{         
                  if (task == 'domains.save'){
                        if ($$("input[type=checkbox]:checked").length<1) {
                          alert('<?php echo $this->escape(JText::_('JGLOBAL_CHECKBOXES_SELECTION'));?>'); 
                          exit();
                        }
                       Joomla.submitform(task, document.getElementById('adminForm'));
                       
                       <?php  
                            $app = JFactory::getApplication('administration');
                            $id =(int) $app->getUserState('message.id');
                       ?>          
                  }
                  else if (task=='domains.cancel') {
                      Joomla.submitform(task,document.getElementById('adminForm'));                 
                  }
	}       
</script>

<form action=" <?php echo JRoute::_('index.php?option=com_jnotification&view=javascriptOutbox&id='.(int)$id); ?> " method="post" name="adminForm" id="adminForm">

	<div class="clr"> </div>

	<table class="adminlist">
		<?php echo $this->loadTemplate('head');?>
		<?php echo $this->loadTemplate('foot');?>
		<?php echo $this->loadTemplate('body');?>
                
	</table>

	<div>  
                <input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
        
</form>



