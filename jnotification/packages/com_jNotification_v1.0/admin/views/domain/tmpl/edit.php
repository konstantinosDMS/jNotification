<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: edit.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addstylesheet(JURI::root().'/media/com_jnotification/css/jnotification.css');

$user	= JFactory::getUser();
$userId=$user->get('id');

 
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'domain.cancel' || document.formvalidator.isValid(document.id('domain-form'))) {
                    
		
			Joomla.submitform(task, document.getElementById('domain-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jnotification&layout=edit&id='.(int)$this->item->id); ?>" method="post" name="adminForm" id="domain-form" class="form-validate">
	<div class="control-group">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_JNOTIFICATION_NEW_NOTIFICATION') : JText::sprintf('COM_JNOTIFICATION_EDIT_NOTIFICATION', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<?php echo $this->form->getLabel('domains'); ?>
				
				<?php echo $this->form->getInput('domains'); ?>
			</ul>
                        <ul class="adminformlist">
                             <?php echo $this->form->getLabel('siteName'); ?>
                             <?php echo $this->form->getInput('siteName'); ?>
                        </ul>
		</fieldset>
	</div>

	<div class="control-group">
		<input type="hidden" name="task" value="" />                
                <?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>
