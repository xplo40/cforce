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
    
    require_once('../../include/AmazonSES/ses.php');
    $ses = new SimpleEmailService('AKIAIPMNPKDPNCI2HI2A', 'HFzqAQGCOQj6R6Y9ogJb27tMVHXSrJjQiCaAKBPs');
    

    $legal_name = sanitize($_GET['legal_name']);
    $dba = sanitize($_GET['dba']);
    $address = sanitize($_GET['address']);
    $city = sanitize($_GET['city']);
    $state = sanitize($_GET['state']);
    $zip = sanitize($_GET['zip']);
    $email = sanitize($_GET['email']);
    $phone = sanitize($_GET['phone']);
    $account_status = sanitize($_GET['account_status']);
    $agent = sanitize($_GET['agent']);
    $agent_ID = sanitize($_GET['agent_ID']);
    $merchant_id = sanitize($_GET['merchant_id']);
    $monthly_volume = sanitize($_GET['monthly_volume'])*1;
    $average_ticket = sanitize($_GET['average_ticket'])*1;
    $prefund = $_GET['prefund']*1;
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
    
    $isTest = ($dba == "TEST" ? true : false);
    
    $query = "INSERT INTO CMC.CMC_Merchant
        (`Address`,
        `AgentID`,
        `Amex`,
        `Average_Ticket`,
        `City`,
        `DateAdded`,
        `DBA_Name`,
        `Debit`,
        `Discover`,
        `Email`,
        `Hardware`,
        `Legal_Name`,
        `MID`,
        `Mid_Qual`,
        `Monthly_Min`,
        `Monthly_Volume`,
        `Non_Qual`,
        `Percentage`,
        `Phone`,
        `Pin_Debit`,
        `Pin_Transfer_Fee`,
        `Platform`,
        `Pricing_Type`,
        `Qual`,
        `Rep_Name`,
        `State`,
        `Statement_Fee`,
        `Status`,
        `TransFee`,
        `Zip`,
        `Prefund`)
        VALUES( 
            '$address',
            '$agent_ID',
            $amex,
            $average_ticket,
            '$city',
            NOW(),
            '$dba',
            $debit,
            $discover,
            '$email',
            '$hardware',
            '$legal_name',
            '$merchant_id',
            $midqual,
            $monthly_min,
            $monthly_volume,
            $nonqual,
            $percentage,
            '$phone',
            $pin_debit,
            $pin_debit_fee,
            '$process_platform',
            '$pricing_type',
            $qual,
            '$agent',
            '$state',
            $statement_fee,
            '$account_status',
            $transaction_fee,
            '$zip',
            $prefund)";
    //if(!$isTest){
        $result = mysql_query($query);
   // }
    
    $legal_name = stripslashes($legal_name);
    $dba = stripslashes($dba);
    $address = stripslashes($address);
    $city = stripslashes($city);
    $state = stripslashes($state);
    $zip = stripslashes($zip);
    $email = stripslashes($email);
    $phone = stripslashes($phone);
    $agent = stripslashes($agent);
    $hardware = stripslashes($hardware);
    
    if(mysql_affected_rows() > 0){
        echo "$legal_name was added successfully.";
    }
    else{
        echo "Add merchant failed - please try again later.";
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
    if(empty($merchant_id)){
        $merchant_id = "(no mid)";
    }
    $m->setSubject("New Merchant Added: $dba - $merchant_id");
    $plain_message = "New Merchant Added by ".$_SESSION['name'].":
        MID: $merchant_id 
        Legal Name: $legal_name 
        DBA: $dba 
        Address: $address 
        City: $city 
        State: $state 
        Zip: $zip 
        Email: $email 
        Phone: $phone 
        Status: $account_status 
        Agent: $agent 
        Agent ID: $agent_ID 
        Monthly Volume: $".number_format($monthly_volume,2)."
        Average Ticket: $".number_format($average_ticket,2)."
        Pre-fund: $".number_format($prefund,2)."
        Transaction Fee: $".number_format($transaction_fee,2)."
        Pricing Type: ".($pricing_type == 0 ? "(not set)" : ($pricing_type == 1 ? "Interchange Plus" : "Tiered"))
            .($pricing_type == 1 ? "
        Percentage: ".number_format($percentage,2)."%"
            : ($pricing_type == 2 ? "
        Debit: ".number_format($debit,2)."%
        Qual: ".number_format($qual,2)."%
        MidQual: ".number_format($midqual,2)."%
        NonQual: ".number_format($nonqual,2)."%" : ""))."
        Amex: ".($amex == "true" ? "YES" : "NO")."
        Discover: ".($discover == "true" ? "YES" : "NO")." 
        Pin Debit: ".($pin_debit == "true" ? "YES
        Pin Debit Fee: $".number_format($pin_debit_fee,2)
            : "NO")."
        Monthly Minimum: $".number_format($monthly_min,2)."
        Statement Fee: $".number_format($statement_fee,2)."
        Hardware: $hardware
        Platform: ".($process_platform == 0 ? "Omaha" : ($process_platform == 1 ? "Nashville" : ($process_platform == 2 ? "CardNet (North)" : ($process_platform == 3 ? "Bypass" : "Global"))));
    
    $html_message = "<h3>New Merchant Added by ".$_SESSION['name'].":</h3>
        <p><strong>MID:</strong> $merchant_id<br/>
        <strong>Legal Name:</strong> $legal_name<br/>
        <strong>DBA:</strong> $dba<br/>
        <strong>Address:</strong> $address<br/>
        <strong>City:</strong> $city<br/>
        <strong>State:</strong> $state<br/>
        <strong>Zip:</strong> $zip<br/>
        <strong>Email:</strong> $email<br/>
        <strong>Phone:</strong> $phone<br/>
        <strong style='color:blue'>Status: $account_status </strong><br/>
        <strong>Agent:</strong> $agent<br/>
        <strong>Agent ID:</strong> $agent_ID<br/>
        <strong>Monthly Volume:</strong> $".number_format($monthly_volume,2)."<br/>
        <strong>Average Ticket:</strong> $".number_format($average_ticket,2)."<br/>
        <strong>Pre-fund:</strong> $".number_format($prefund,2)."<br/>
        <strong>Transaction Fee:</strong> $".number_format($transaction_fee,2)."<br/>
        <strong>Pricing Type:</strong> ".($pricing_type == 0 ? "(not set)" : ($pricing_type == 1 ? "Interchange Plus" : "Tiered"))."<br/>"
            .($pricing_type == 1 ? 
    "   <strong>Percentage:</strong> ".number_format($percentage,2)."%<br/>"
            : ($pricing_type == 2 ? 
    "   <strong>Debit:</strong> ".number_format($debit,2)."%<br/>
        <strong>Qual:</strong> ".number_format($qual,2)."%<br/>
        <strong>MidQual:</strong> ".number_format($midqual,2)."%<br/>
        <strong>NonQual:</strong> ".number_format($nonqual,2)."%<br/>" : "")).
    "   <strong>Amex:</strong> ".($amex == "true" ? "YES" : "NO")."<br/>
        <strong>Discover:</strong> ".($discover == "true" ? "YES" : "NO")."<br/>
        <strong>Pin Debit:</strong> ".($pin_debit == "true" ? "YES" : "NO")."<br/>"
            .($pin_debit == "1" ? 
    "   <strong>Pin Debit Fee:</strong> $".number_format($pin_debit_fee,2)."<br/>"
            : "").
    "   <strong>Monthly Minimum:</strong> $".number_format($monthly_min,2)."<br/>
        <strong>Statement Fee:</strong> $".number_format($statement_fee,2)."<br/>
        <strong>Hardware:</strong> $hardware<br/>
        <strong>Platform:</strong> ".($process_platform == 0 ? "Omaha" : ($process_platform == 1 ? "Nashville" : ($process_platform == 2 ? "CardNet (North)" : ($process_platform == 3 ? "Bypass" : "Global"))))."<br/>";
    $m->setMessageFromString($plain_message, $html_message);

    $response = $ses->sendEmail($m);
    if($response){
        //echo "Thank you, your feedback has been submitted.";
    }
    else{
        //echo "There was an error sending your feedback. Please try again.";
    } 
?> 