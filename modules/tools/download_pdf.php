<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    header('Content-Type: application/vnd.fdf');
    header('Content-Disposition: attachment;filename="DDA Action Request Form.fdf"');
    header('Cache-Control: max-age=0');


    exit;
?>
