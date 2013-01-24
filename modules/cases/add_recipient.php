<?php 
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }

    include "../../include/common.php";
    $mysqldb = connect_to_mysql_database(false);
     

    $id = sanitize($_GET['id']);
    $cc = ($_GET['cc']);
    
    
    $query1 = "UPDATE CMC.CMC_Cases SET AdditionalRecipients='$cc' WHERE ID='$id'";
    $result= mysql_query($query1);
    
   
     
?>