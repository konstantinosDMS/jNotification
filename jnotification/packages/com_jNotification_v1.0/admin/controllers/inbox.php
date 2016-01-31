<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: inbox.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

 class JNotificationControllerInbox extends  JControllerLegacy{ 
      public function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect(JRoute::_('index.php?option=com_jnotification&view=inbox', false));
    }
 }
