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

        $id = sanitize($_GET['id']);
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
     
        $query = "UPDATE CMC.CMC_Agents
            SET
                Address = '$address',
                AgentId1 = '$repcode_low',
                AgentId2 = '$repcode_high',
                City = '$city',
                CommissionHigh = '$com_high',
                CommissionLow = '$com_low',
                Company = '$company',
                Email = '$email',
                IsFullAccess = '$access',
                Name = '$name',
                PhoneNumber = '$phone',
                State = '$state',
                ZipCode = '$zip'
            WHERE ID = '$id'";
        
        mysql_query($query);
        if(mysql_affected_rows() > -1){
            echo "$name has been edited.";
        }
        else{
            echo "There was a problem. Try again later.";
        }
    }
     
 ?>
   
