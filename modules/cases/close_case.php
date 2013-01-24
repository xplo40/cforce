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
    $query = "Update CMC.CMC_Cases set Status='closed' WHERE ID = '$id'";
    mysql_query($query);
    if(mysql_affected_rows()>0){
        echo "Case $id closed.";
    }
    else{
        echo "Error, please try again later.";
    }
 ?>