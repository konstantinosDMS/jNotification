<script type="text/javascript">

function sentMessage(domain,url) {
    var myRequest;    
    var t= url.indexOf('/');
    var size = url.length-1;
    var domainName = url.slice(0,t);  
    var siteName = url.slice(t+1,size);
       
    myRequest = new Request({
        url: 'http://'+domain+'/administrator/components/com_jnotification/views/javascriptInbox/tmpl/default_body.php',
        method: 'post',
        data: {
                domainName: domainName,
                siteName : siteName,
                switch: 1
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
            }
        },
        onFailure: function(xhr){
            console.log('Fail...');
            //alert('Fail...');
        },
        onException: function(headerName, value){
            console.log('Exception ...');
            //alert('Exception...');
        }
    });
   myRequest.send(); 
} 
</script>

<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  UpdateCorrespondenceStatus
 * @version	$Id: UpdateCorrespondenceStatus.php 30 2015-12-15 22:04:52Z Konstantinos $
 * @copyright   @copyright (C) 2016- Konstantinos
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('JPATH_BASE') or die;

/**
 * This is our custom registration plugin class.  It validates/verifies the email address a user 
 * entered into the email field of Joomla's registration form.
 *
 * @package     Joomla.Plugins
 * @subpackage  User.MyRegistration
 * @since       1.5.0
 */

class plgContentUpdateJNotificationStatus extends JPlugin
{		
	/**
	 * Method to handle the "onContentPrepareForm" event.
	 * 	 *
	 * @return  bool
	 * 
	 * @since   1.5.0
	 */
	public function  onContentPrepareForm($form, $data)
	{            
                $option	= JRequest::getCmd('option');              
                $view = JRequest::getCmd('view');
                $layout = JRequest::getCmd('layout');
                $id = JRequest::getInt('id');
                $url =  preg_replace('~^https?://~i', NULL,  JURI::root());
                        
                // Get the dbo
	        $db = JFactory::getDbo(); 
                
                 // Initialize the query object
                $query = $db->getQuery(true);
                
                if (($option == 'com_jnotification')&&($view=='notification')&&($layout=='viewMessage'))
	        {                   
                        $query->update($db->QuoteName('#__notification_user'));
                        $query->set('status = 1');
                        $query->where('message_id = '.(int)$id);
                        $query->where('inbox=1');
                                             
                        $db->setQuery($query);
                        
                        //echo $db->getAffectedRows();
                        
                        $db->execute();
                        
                        if ($db->getErrorNum()) {
                            echo $db->getErrorMsg();
                            return false;
                        }
                        
                        $query->clear(); 

                        $query->select('domain as domain');
                        $query->from('#__notification_user');
                        $query->where('message_id='.(int)$id);
                        $query->where('inbox=1');

                        //var_dump($query);                       
                        $db->setQuery($query);
                        $domain = $db->loadResult();
            
                        $query->clear();
                        $url =  preg_replace('~^https?://~i', NULL,  JURI::root());
                        
                        echo '<script type="text/javascript">';
                        echo 'sentMessage('. json_encode($domain) . ',' . json_encode($url). ')';            
                        echo '</script>';
                }             
		return true;
	}	
}