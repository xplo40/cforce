<?php
    require_once('helpers/ses.php');
    $ses = new SimpleEmailService('AKIAIPMNPKDPNCI2HI2A', 'HFzqAQGCOQj6R6Y9ogJb27tMVHXSrJjQiCaAKBPs');
    
    include "helpers/database.php";
    connect_to_mssql_database(false);
    
    $profile = $_POST['profile'];
    $tab = $_POST['tab'];
    $mid = $_POST['mid'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $ach = $_POST['ach'];
    $batch = $_POST['batch'];
    $feedback = $_POST['feedback'];
        
    //get name and email from database
    $query = "SELECT * FROM CRM.dbo.CMV_profiles WHERE Profile = '$profile'";
    $result = mssql_query($query);
    $row = mssql_fetch_assoc($result);
    $name = $row['Name'];
    $email = $row['Email'];
    $last_updated = $row['DateUpdated'];
        
    //send email
    $email_message = "Name: $name\r\n
        Profile: $profile\r\n
        Email: $email\r\n
        Started CMV: $last_updated\r\n
        Active Tab: $tab\r\n
        Active MID: $mid\r\n
        Start Date: $start_date\r\n
        End Date: $end_date\r\n
        ACH Filter: $ach\r\n
        Batch Filter: $batch\r\n
        Feedback: $feedback\r\n
        Timestamp: ".date('n/d/y g:i:s a');
    if($profile == '112358'){
        $agentip=$_SERVER['REMOTE_ADDR'];
        $agents = array (
            array("Stephen Aston","10.1.10.4"),
            array("Chase Morgan","10.1.10.226"),
            array("Chase Morgan","10.1.10.108"),
            array("Chase Morgan","10.1.10.104"),
            array("Michelle Atkinson","97.75.175.218"),
            array("Michelle Atkinson","10.1.10.103"),
            array("Adam Christian","10.1.10.42"),
            array("Jerrie Lundberg","10.1.10.57"),
            array("Doug Hansen","10.1.10."),
            array("Jeremy Ady","10.1.10."),
            array("Amanda Hill","10.1.10.102"),
            array("Patrick Lynch","10.1.10.")
            );
        $email_message .= "\r\n$agentip";
        foreach($agents as $agent){
            if($agent[1] == $agentip){
                $email_message .= "\r\nUser: ".$agent[0];
            }
        }
        
    }

    $m = new SimpleEmailServiceMessage();
    $m->addTo('watkinson@cmsonline.com');
    $m->setFrom('admin@cmsonline.com');
    $m->setSubject('CMV Feedback');
    $m->setMessageFromString($email_message);

    $response = $ses->sendEmail($m);
    if($response){
        echo "Thank you, your feedback has been submitted.";
    }
    else{
        echo "There was an error sending your feedback. Please try again.";
    } 
?>
