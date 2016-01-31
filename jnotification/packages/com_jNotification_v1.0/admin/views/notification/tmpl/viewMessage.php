<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: edit.php 30 2016-01-15 22:00:00Z Konstantinos $
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
		if (task == 'notification.cancel') {
                    window.location = 'index.php?option=com_jnotification&view=inbox';
		}
	}
</script>

<form action="" method="post" name="adminForm" id="notification-form" class="form-validate">
	<div class="control-group">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JNOTIFICATION_NOTIFICATION'); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?></li>
			</ul>

			<div>
				<?php echo $this->form->getLabel('message'); ?>
				<div class="clr"></div>
				<?php echo $this->form->getInput('message'); ?>                           
			</div>
		</fieldset>
	</div>

	<div class="control-group">
        <?php echo JHtml::_('form.token'); ?>
	</div>
	<div class="clr"></div>
</form>

