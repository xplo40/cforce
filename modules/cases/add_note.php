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
     
    $user = $_SESSION['user'];
    
    $id = sanitize($_GET['id']);
    $note = sanitize($_GET['note']);
    $MerchantName = sanitize($_GET['merchantname']);
    $cc = sanitize($_GET['cc']);
    $assignedtoemail = sanitize($_GET['assignedtoemail']);
    $creatoremail = sanitize($_GET['creatoremail']);
   
    $query = "INSERT INTO CMC.CMC_Case_Notes VALUES (DEFAULT, DEFAULT, '$note', '$user', '$id')";
    mysql_query($query);
    
    $emails = array($assignedtoemail, $creatoremail);
    $emails = array_merge($emails, split($cc, ','));
    $subject = "case # $id";
    $salutation = "Note added to: " . $subject . "\n<br />Merchant: " . $MerchantName . "\n<br />\n<br />";
    $message = $salutation . (nl2br($note));
    $from = $_SESSION['email'];
    $headers = "From: $from" . "\n" .
    'Reply-To: admin@cmsonline.com' . "\n" .
    'Content-Type: text/html; charset="utf-8"' . "\n" .
    'X-Mailer: PHP/' . phpversion();

    foreach($emails as $email){
        if(mail($email, $subject, $message, $headers)) {
            echo "$email was notified.";
        }
        else {
            echo "Note Submission Failed, Please try again";
        }
    }
?>