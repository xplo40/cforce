<?php 
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    require_once('../../include/AmazonSES/ses.php');
    $ses = new SimpleEmailService('AKIAIPMNPKDPNCI2HI2A', 'HFzqAQGCOQj6R6Y9ogJb27tMVHXSrJjQiCaAKBPs');

    include "../../include/common.php";
    connect_to_mysql_database(false);
        
    $subject = $_GET['subject'];
    $merchant = $_GET['merchant'];
    $merchant_name = $_GET['merchant_name'];
    $description = $_GET['description'];
    $cc = $_GET['cc'];
    $assigned_to_department = $_GET['assigned_to_department'];
    $assigned_to_id = $_GET['assigned_to_id'];
    $creator_email = $_SESSION['email'];
    $agent_email = $_GET['case_agent_email'];
    
    //set assigned_to_email
    if($assigned_to_id == ''){
        //department = agent => agent email associated with merchant
        if($assigned_to_department == '1' || $assigned_to_department == '2'){
            $query = "SELECT agent.Email
                    FROM(
                        SELECT AgentID
                                FROM CMC.CMC_Merchant
                                WHERE MID = '$merchant'
                    ) AS mi
                    LEFT JOIN CMC.CMC_Agents AS agent
                            ON mi.AgentID = agent.AgentId1
                                    OR mi.AgentID = agent.AgentId2";
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            $assigned_to_email = $row['Email'];
        }
        //department email
        else{
            $query = "SELECT DepartmentEmail 
                    FROM CMC.CMC_Department 
                    WHERE ID = '$assigned_to_department'";
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            $assigned_to_email = $row['DepartmentEmail'];
        }
    }
    //user email
    else {
        $query = "SELECT Email 
                FROM CMC.CMC_Users 
                WHERE ID = '$assigned_to_id'";
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        $assigned_to_email = $row['Email'];
    }

    $query = "INSERT INTO CMC.CMC_Cases 
            VALUES (
                DEFAULT
                ,'$description'
                ,'$cc'
                ,'$subject'
                ,'open'
                ,DEFAULT
                ,'$merchant'
                ,'$assigned_to_department'
                ,'$assigned_to_id'
                ,'$merchant_name'
                ,'$creator_email'
                ,'$assigned_to_email'
        )";
    $result = mysql_query($query);
    
    //echo "subject: $subject, merchant_name: $merchant_name, description: $description, assigned_to_email: $assigned_to_email, creator_email: $creator_email, cc_email: $cc, agent_email: $agent_email";die;
    $email_message = "Subject: $subject\n<br />Merchant: $merchant_name\n<br />\n<br />";
    $email_message .= (nl2br($description));
      
    $m = new SimpleEmailServiceMessage();
//$m->addTo($assigned_to_email);   
$m->addTo($creator_email);
$m->addTo($agent_email);
if ($cc != '   Optional to notify others' || $cc != ''){
       $m->addTo(array(split(",", $cc)));
        }
$m->setFrom('admin@cmsonline.com');
$m->setSubject($subject);
 $m->setMessageFromString($email_message);

$output = "<p>The case was created. <br/>";

$response = $ses->sendEmail($m);



if($response){
          $output .= "$email_message<br/>";
           }
       else {
                $output .= "<br/>There was an error sending the mail.<br/>  "; 
            }
    
  
  /*  
    $email_message = "Subject: $subject\n<br />Merchant: $merchant_name\n<br />\n<br />";
    $email_message .= (nl2br($description));
    
    //send the email
  
    $output = "<p>The case was created. <br/>";
    foreach($emails as $email){
        if($email != ''){
            if(mail($email, $subject, $message, $headers)) {
                $output .= "$email<br/>";
            }
            else {
                $output .= "<br/>There was an error sending the mail.<br/>  "; die;
            }
        }
    }
   * 
   */
   
    echo $output;
?> 