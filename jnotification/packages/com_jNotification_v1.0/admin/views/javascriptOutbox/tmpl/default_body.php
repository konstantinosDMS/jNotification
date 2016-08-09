<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_jnotification
 * @version	$Id: default_body.php 30 2016-01-15 22:00:00Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted Access');
JSession::checkToken('get') or die('Invalid Token');

echo '<tbody>'; 
    // Initialise variables.
    $app = JFactory::getApplication('administrator'); 
    $myModel = $this->getModel();              
    $cid = $app ->getUserState('domains.edit.id');
    $app->setUserState('domains.edit.id', '');
    
    $id = $app->getUserState('message.id');
    $app->setUserState('message.id', '');
    
    $tmpMessage = $myModel -> getMessage($id);
    @$message = $tmpMessage[0]->message;
    @$title = $tmpMessage[0]->title;
    $url =  preg_replace('~^https?://~i', NULL,  JURI::root());

    for ($i=0;$i<count($cid);$i++){
        $result = $myModel->getInfo($cid[$i]);
        $domain[] = $result->domains;
        $siteName [] = $result->siteName;
    }
    
    echo 'Wait while senting the message(s)...';
    echo "<img src=".JURI::root()."/media/com_jnotification/images/wait30.gif />";
?>

<script type="text/javascript">

function sentMessage(array,siteName) {
   var myRequest;
   var myRequest1;
   var domainArray = array; 
   var siteName = siteName;
    
    myRequest = new Request({
        url: 'http://'+domainArray[0]+'/'+siteName[0]+'/administrator/components/com_jnotification/views/javascriptInbox/tmpl/default_body.php',
        //url: 'http://localhost/joomla_3.4.8/administrator/components/com_jnotification/views/javascriptInbox/tmpl/default_body.php',
        method: 'post',
        data: {
                message: <?php echo '"'.strip_tags($message).'"' ; ?>,
                title: <?php echo '"'.$title.'"' ; ?>,
                domainSender: <?php echo '"'.$url.'"' ; ?>,
                domainReceiver: "http://"+domainArray[0]
        }, 
        onRequest: function(){
            console.log('Loading...');
            //alert('Loading...');
        },
        onProgress: function(event, xhr){
            console.log('Progress ...');
            //alert('Progress...');
        },
        onComplete: function(){
            console.log('Complete...');
            //alert('Complete...');
        },
        onSuccess: function(responseText, responseXML){
           console.log('Success...');
           
            if (responseText==false){
               alert("Problem while saving the message");
               exit;
            }
                  
            myRequest1 = new Request({
                        url: 'http://'+responseXML.getElementsByTagName("domainSender")[0].firstChild.nodeValue+'/administrator/components/com_jnotification/views/updateRecords/tmpl/default_body.php',
                        method: 'post', 
                        user: 'konUser1',
                        password: 'konDms3#4%T',
                        data: {
                            domainReceiver: responseXML.getElementsByTagName("domainReceiver")[0].firstChild.nodeValue,
                            messageId: <?php echo $id; ?>
                        },
                        onRequest: function(){
                            console.log('Loading...');
                            //alert('Loading...');
                        },
                        onProgress: function(event, xhr){
                            console.log('Progress ...');
                            //alert('Progress...');
                        },
                        onComplete: function(){
                            console.log('Complete...');
                            //alert('Complete...');
                        },
                        onSuccess: function(responseText, responseXML){
                           console.log('Success...');
                           
                           if(responseText==false) alert("Problem while savong the status of the message"); 
                           if ( domainArray.length == 0 ) window.location = 'index.php?option=com_jnotification&view=outbox';
                        },
                        onFailure: function(xhr){
                            console.log('Fail...');
                            //alert('Fail...');
                            if ( domainArray.length == 0 ) window.location = 'index.php?option=com_jnotification&view=outbox';
                        },
                        onException: function(headerName, value){
                            console.log('Exception ...');
                            //alert('Exception...');
                            if ( domainArray.length == 0 ) window.location = 'index.php?option=com_jnotification&view=outbox';
                        }
                       
            });  
            
           myRequest1.send();
        
           domainArray.splice(0,1);
           siteName.splice(0,1);
           
           if (domainArray.length>0){
               sentMessage(domainArray,siteName);
           } 
        },
        onFailure: function(xhr){
            console.log('Fail...');
            //alert('Fail...');
            if ( domainArray.length == 0 ) window.location = 'index.php?option=com_jnotification&view=outbox';
        },
        onException: function(headerName, value){
            console.log('Exception ...');
            //alert('Exception...');
            if ( domainArray.length == 0 ) window.location = 'index.php?option=com_jnotification&view=outbox';
        }
    });
    
   myRequest.send(); 
} 

window.addEvent('domready', function() {
    sentMessage(<?php echo json_encode($domain); ?>,<?php echo json_encode($siteName); ?>);
});  

</script>

</tbody> 




