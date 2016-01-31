<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: domain.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access.
defined('_JEXEC') or die;

class JFormRuleDomain extends JFormRule
{
    public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
    {
       $url = preg_replace('~^https?://~i', NULL, $value);
       if (checkdnsrr($url)) return true; else return false;
    }
    
}