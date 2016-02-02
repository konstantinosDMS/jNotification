<?php 

/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default_body.php 30  2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

include_once '../../../../../../configuration.php';

@$mySwitch = $_POST['switch'];
$mySwitch = @ereg_replace("[[:punct:][:space:][:alpha:]]+", ' ',@$mySwitch);
$mySwitch = @ereg_replace("[αβγδεζηθικλμνξοπρστυφχψωάέήίόύώϊϋ]+", ' ', @$mySwitch);
$mySwitch = @ereg_replace("[ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆΈΉΊΌΎΏΪΫ]+", ' ', @$mySwitch);
$mySwitch = @ereg_replace("[abcdefghijklmnopqrstuvwxyz]+", ' ', @$mySwitch);
$mySwitch = @ereg_replace("[ABCDEFGHIJKLMNOPQRSTUVWXYZ]+", ' ', @$mySwitch);

$jconfig = new JConfig();
$host = $jconfig->host;
$user = $jconfig->user;
$password = $jconfig->password;
$database = $jconfig->db;
$prefix = $jconfig->dbprefix;
$flag=false;

$db = new mysqli($host,$user,$password,$database);

if (!@$mySwitch){
    
    $query = "SELECT * FROM ".$prefix."notification_domains";

    $result = $db->query($query);   

    for ($i=0;$i<$result->num_rows;$i++){ 
            $row = $result->fetch_assoc();
            $domains[] = 'http://'.$row['domains'];
            $domains[] = 'https://'.$row['domains']; 
    }

    
    for ($i=0;$i<count($domains);$i++){

        switch ($_SERVER['HTTP_ORIGIN']) {

            case $domains[$i]:
            header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            header('Access-Control-Max-Age: 1000');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
            header('Access-Control-Allow-Credentials: true');

            $flag=true;
            $message = strip_tags($_POST['message']);
            $message = mysql_escape_string($message);
            $message = preg_replace('/([\x00-\x20\xff])/se', '', $message);

            $title = strip_tags($_POST['title']);
            $title = mysql_escape_string($title);
            $title = preg_replace('/([\x00-\x20\xff])/se', '', $title);

            $domainReceiver = strip_tags($_POST['domainReceiver']);
            $domainReceiver = mysql_escape_string($domainReceiver);
            $domainReceiver = preg_replace('/([\x00-\x20\xff])/se', '', $domainReceiver);

            $domainSender = strip_tags($_POST['domainSender']);
            $domainSender = mysql_escape_string($domainSender);
            $domainSender = preg_replace('/([\x00-\x20\xff])/se', '', $domainSender);

            if (mysqli_connect_errno()){
                header('Content-Type: application/json');
                echo json_encode(array('false'));
            }

             
            if (($message!='')&&($title!='')&&($domainReceiver!='')&&($domainSender!='')){
                $query4 = "SELECT id,message,title FROM ".$prefix."notification_header WHERE
                           message LIKE '%".$message."%' AND title LIKE '%".$title."%'";

            $result4 = $db->query($query4);   

            if ($result4->num_rows>0) {
                $row = $result4->fetch_assoc();
                $lastInsert = $row['id'];
            }
            else{
                $query1 = "INSERT INTO ".$prefix."notification_header VALUES ('','"
                         .$message . "','". $title."', now(),0)";

                $result = $db->query($query1);
                $lastInsert = $db->insert_id;
            }   
                
            $query2 = "INSERT INTO ".$prefix."notification_user VALUES ('','"
                     .$domainSender."',".$lastInsert.",1,0,0,0)";

            $result1 = $db->query($query2);

            if ($result1) {
                $query3 = "UPDATE ".$prefix."notification_header SET STATUS=1 WHERE id = ".(int)$lastInsert; 
                    $result2 = $db->query($query3);
            }
                
            if ($result2) {   
                    
                    $db->close(); 
                    $xml = '<?xml version="1.0" encoding="UTF-8"?>'
                          .'<info>'
                          .'<domainReceiver>'.$domainReceiver.'</domainReceiver>'
                          .'<domainSender>'.$domainSender.'</domainSender>'
                          .'</info>';

                    $xml = new SimpleXMLElement($xml);
                    $xmll = $xml->asXML();

                    header("Content-Type: text/xml; charset=utf-8");
                    echo $xmll;
            }
            else {
                   header('Content-Type: application/json');
                   echo json_encode(false);
                }
            }
            break;
        }
        if ($flag) break;
    }
} else {
    
    $domainName = strip_tags($_POST['domainName']);
    $domainName = mysql_escape_string($domainName);
    $domainName =  preg_replace('~^https?://~i', NULL,  $domainName);
    $domainName = preg_replace('/([\x00-\x20\xff])/se', '', $domainName);
    
    $siteName = strip_tags($_POST['siteName']);    
    $siteName = mysql_escape_string($siteName);
    $siteName =  preg_replace('~^https?://~i', NULL,  $siteName);
    $siteName = preg_replace('/([\x00-\x20\xff])/se', '', $siteName);
    
    $query = "UPDATE ".$prefix."notification_user"." SET status=1 WHERE domain like '%".$domainName.'%'.$siteName."%' AND outbox=1";

    $db->query($query);  
    
    if ($db->affected_rows==1){
        header('Content-Type: application/json');
        echo json_encode(true);
    }
    else {
        header('Content-Type: application/json');
        echo json_encode(false);
    }
}

?>


    
 



