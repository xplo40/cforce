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
  
    $id = sanitize($_GET['id']);
    $note = sanitize($_GET['note']);
    $type = sanitize($_GET['type']);
    $creator = $_SESSION['user'];
    
    $query = "INSERT INTO CMC.CMC_Merchant_Notes
            (MerchantID,
            Creator,
            Type,
            Notes,
            DateAdded)
        VALUES( 
            $id,
            $creator,
            $type,
            '$note',
            NOW())";
    $result = mysql_query($query);
    
    if(mysql_affected_rows() > 0){
        echo "Your note was added successfully.";
    }
    else{
        echo "Add note failed - please try again later.";
    }
?> 