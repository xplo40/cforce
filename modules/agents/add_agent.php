<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    $department = $_SESSION['department'];
    
    if($department == 10){
        include "../../include/common.php";
        connect_to_mysql_database(false);

        $name = sanitize($_GET['name']);
        $company = sanitize($_GET['company']);
        $address = sanitize($_GET['address']);
        $city = sanitize($_GET['city']);
        $state = sanitize($_GET['state']);
        $zip = sanitize($_GET['zip']);
        $email = sanitize($_GET['email']);
        $phone = sanitize($_GET['phone']);
        $access = $_GET['access'];
        $repcode_low = sanitize($_GET['repcode_low']);
        $repcode_high = sanitize($_GET['repcode_high']);
        $com_low = sanitize($_GET['com_low']);
        $com_high = sanitize($_GET['com_high']);
        
        $query = "INSERT INTO CMC.CMC_Agents VALUES (DEFAULT, '$name', '$company', '$address', '$state', '$city', '$zip', '$phone', '$email', '$repcode_low', '$repcode_high', '$access', NULL, NULL, '$com_low', '$com_high')";
        $result = mysql_query($query);
        if(mysql_affected_rows() > -1){
            echo "$name has been added.";
        }
        else{
            echo "There was a problem. Try again later.";
        }
    }
     
 ?>
   
