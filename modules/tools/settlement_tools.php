<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    $department = $_SESSION['department'];
    switch ($department) {
    case '18':
        include "modules/tools/settlements.php";
        break;
    }

?>