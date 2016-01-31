<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default_body.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

header('Access-Control-Allow-Credentials: true');

include_once '../../../../../../configuration.php';

$jconfig = new JConfig();
$host = $jconfig->host;
$user = $jconfig->user;
$password = $jconfig->password;
$database = $jconfig->db;
$prefix = $jconfig->dbprefix;
$table = $prefix.'notification_user';

$db = new mysqli($host,$user,$password,$database);

if (mysqli_connect_errno()){
     header('Content-Type: application/json');
     echo json_encode(array('false'));
}

$domain = strip_tags($_POST['domainReceiver']);
$domain = preg_replace('/([\x00-\x20\xff])/se', '', $domain);
$domain =  preg_replace('~^https?://~i', NULL,  $domain);
$messageId = $_POST['messageId'];
$messageId = @ereg_replace("[[:punct:][:space:][:alpha:]]+", ' ',$messageId );
$messageId = @ereg_replace("[αβγδεζηθικλμνξοπρστυφχψωάέήίόύώϊϋ]+", ' ', $messageId);
$messageId = @ereg_replace("[ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆΈΉΊΌΎΏΪΫ]+", ' ', $messageId);
$messageId = @ereg_replace("[abcdefghijklmnopqrstuvwxyz]+", ' ', $messageId);
$messageId = @ereg_replace("[ABCDEFGHIJKLMNOPQRSTUVWXYZ]+", ' ', $messageId);
$var = trim($messageId);

if ($messageId != ''){
    $query = "UPDATE $table SET SUCCESS=1 WHERE domain like '%".$domain."%'".
             " AND message_id= ".
             (int) $messageId ;

    $result = $db->query($query);
    $db->close();
    
    if ($result) {
        header('Content-Type: application/json');
        echo json_encode(true); 
    }
    else {
        header('Content-Type: application/json');
        echo json_encode(false);
    }

}