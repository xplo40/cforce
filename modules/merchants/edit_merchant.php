<?php 
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    include "../../include/common.php";
    connect_to_mysql_database(false);

    include '../../include/AmazonSES/ses.php';
    $ses = new SimpleEmailService('AKIAIPMNPKDPNCI2HI2A', 'HFzqAQGCOQj6R6Y9ogJb27tMVHXSrJjQiCaAKBPs');

    //grab inputs
    $id = sanitize($_GET['id']);
    $legal_name = sanitize(urldecode($_GET['legal_name']));
    $dba = sanitize(urldecode($_GET['dba_name']));
    $address = sanitize(urldecode($_GET['address']));
    $city = sanitize(urldecode($_GET['city']));
    $state = sanitize($_GET['state']);
    $zip = sanitize($_GET['zip']);
    $email = sanitize($_GET['email']);
    $phone = sanitize(urldecode($_GET['phone']));
    $account_status = sanitize($_GET['account_status']);
    $agent = sanitize($_GET['agent_name']);
    $agent_ID = sanitize($_GET['agent_id']);
    $merchant_id = sanitize($_GET['merchant_id']);
    $monthly_volume = sanitize($_GET['monthly_volume'])*1;
    $average_ticket = sanitize($_GET['average_ticket'])*1;
    $prefund = sanitize($_GET['prefund'])*1;
    $pricing_type = sanitize($_GET['pricing_type']);
    $percentage = sanitize($_GET['percentage'])*1;
    $transaction_fee = sanitize($_GET['transaction_fee'])*1;
    $debit = sanitize($_GET['debit'])*1;
    $qual = sanitize($_GET['qual'])*1;
    $midqual = sanitize($_GET['midqual'])*1;
    $nonqual = sanitize($_GET['nonqual'])*1;
    $amex = sanitize($_GET['amex']);
    $discover = sanitize($_GET['discover']);
    $pin_debit = sanitize($_GET['pin_debit']);
    $pin_debit_fee = sanitize($_GET['pin_debit_fee'])*1;
    $monthly_min = $_GET['monthly_min']*1;
    $statement_fee = $_GET['statement_fee']*1;
    $hardware = sanitize($_GET['hardware']);
    $process_platform = $_GET['process_platform']*1;
    $notes = sanitize($_GET['notes']);

    $isTest = ($dba == "TEST" ? true : false);
    $merchantname = stripslashes(empty($dba) ? $legal_name : $dba);
    $subject = "Merchant Updated: $merchantname - ".(empty($merchant_id) ? "(no mid)" : $merchant_id);
    $note_type = "1";
    
    //grab original values
    $query = "SELECT *
            FROM CMC.CMC_Merchant
            WHERE ID = $id";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);

    //find changes
    $plain_message = $_SESSION['name']." made the following changes:\r";
    $html_message = "<h3>".$_SESSION['name']." made the following changes:</h3><p>";
    $set_query = "";
    appendChanges("Address", "Address", $address);
    appendChanges("Agent ID", "AgentID", $agent_ID);
    appendChanges("Amex", "Amex", ($amex == "true" ? "1" : "0"));
    appendChanges("Average Ticket", "Average_Ticket", $average_ticket);
    appendChanges("Pre-fund", "Prefund", $prefund);
    appendChanges("City", "City", $city);
    appendChanges("DBA Name", "DBA_Name", $dba);
    appendChanges("Debit", "Debit", $debit);
    appendChanges("Discover", "Discover", ($discover == "true" ? "1" : "0"));
    appendChanges("Email", "Email", $email);
    appendChanges("Hardware", "Hardware", $hardware);
    appendChanges("Legal Name", "Legal_Name", $legal_name);
    appendChanges("MID", "MID", $merchant_id);
    appendChanges("Mid Qual", "Mid_Qual", $midqual);
    appendChanges("Monthly Min", "Monthly_Min", $monthly_min);
    appendChanges("Monthly Volume", "Monthly_Volume", $monthly_volume);
    appendChanges("Non Qual", "Non_Qual", $nonqual);
    appendChanges("Percentage", "Percentage", $percentage);
    appendChanges("Phone", "Phone", $phone);
    appendChanges("Pin Debit", "Pin_Debit", ($pin_debit == "true" ? "1" : "0"));
    appendChanges("Pin Debit Fee", "Pin_Transfer_Fee", $pin_debit_fee);
    appendChanges("Platform", "Platform", $process_platform);
    appendChanges("Pricing Type", "Pricing_Type", $pricing_type);
    appendChanges("Qual", "Qual", $qual);
    appendChanges("Agent", "Rep_Name", $agent);
    appendChanges("State", "State", $state);
    appendChanges("Statement Fee", "Statement_Fee", $statement_fee);
    appendChanges("Status", "Status", $account_status);
    appendChanges("Transaction Fee", "TransFee", $transaction_fee);
    appendChanges("Zip", "Zip", $zip);

    function appendChanges($name, $dbname, $newvalue){
        global $row, $plain_message, $html_message, $set_query, $subject, $merchantname, $merchant_id, $note_type;
        $oldvalue = $row[$dbname];
        $newvalue_disp = stripslashes($newvalue);
        if($newvalue_disp != $oldvalue){
            $plain_message .= "\t$name was changed from ".($oldvalue == "" ? "(blank)" : $oldvalue)." to ".($newvalue_disp == "" ? "(blank)" : $newvalue_disp).".\r";
            $html_message .= "<strong style='margin-left: 20px'>$name</strong> was changed from ".($oldvalue == "" ? "(blank)" : $oldvalue)." to ".($newvalue_disp == "" ? "(blank)" : $newvalue_disp).".<br/>";
            $set_query .= "$dbname = '$newvalue', ";
            if($name == 'Status'){
                $subject = "Merchant $newvalue_disp: $merchantname - ".(empty($merchant_id) ? "(no mid)" : $merchant_id);
                switch ($newvalue_disp){
                    case "Received":
                        $note_type = "10";
                        break;
                    case "Approved":
                        $note_type = "11";
                        break;
                    case "Pending":
                        $note_type = "12";
                        break;
                    case "Boarded":
                        $note_type = "13";
                        break;
                    case "Live":
                        $note_type = "14";
                        break;
                    case "Denied":
                        $note_type = "15";
                        break;
                    case "Closed":
                        $note_type = "16";
                        break;
                }
            }
        }
    }
    
    $html_message .= "</p>";
    
    //update changes
    if(!empty($set_query)){
        $set_query = substr($set_query, 0, -2);
        $query = "UPDATE CMC.CMC_Merchant
            SET
            $set_query
            WHERE ID = '$id'";
    
        mysql_query($query);

        if(mysql_affected_rows() > 0){
            $output = mysql_affected_rows()." change(s) made.";
        }
        else{
            if($isTest){
                $output = $query;
            }
            else{
                $output = "Edit failed - try again later.";
            }
            echo $output;
            die;
        }
    
        if(!empty($notes)){
            $query = "INSERT INTO CMC.CMC_Merchant_Notes (MerchantID,Creator,Type,Notes,DateAdded)
                VALUES ($id,".$_SESSION['user'].",$note_type,'$notes',NOW())"; 
            mysql_query($query);
            $notes = str_replace("\\n", "<br/>", $notes);
            $notes = stripslashes($notes);
            $plain_message .= "\r\tNOTES: $notes\r";
            $html_message .= "<table><tr><td style='width: 80px'><strong>NOTES:</strong></td><td><p style='font-style:italic; font-size: 14px; background:#EEE; color: #444;'>$notes</p></td></tr></table>";
        }

        //send emails to all involved
        $m = new SimpleEmailServiceMessage();
        if(!empty($agent_email)){
            $m->addTo($agent_email);
        }
        $m->addTo("watkinson@cmsonline.com");
        if(!$isTest){
            $m->addTo("chenneman@cmsonline.com");
            $m->addTo("michatki@cmsonline.com");
            $m->addTo("jbriggs@cmsonline.com");
            $m->addTo("bdecker@cmsonline.com");
            $m->addTo("cdecker@cmsonline.com");
            $m->addTo("thansen@cmsonline.com");
        }
        $m->setFrom("admin@cmsonline.com");
        $m->setSubject($subject);
        $m->setMessageFromString($plain_message, $html_message);

        $response = $ses->sendEmail($m);
        if($response){
            //echo "Thank you, your feedback has been submitted.";
        }
        else{
            //echo "There was an error sending your feedback. Please try again.";
        } 
    }
    else{
        $output = "No changes were made.";
    }
    
    echo $output;
?>
    
