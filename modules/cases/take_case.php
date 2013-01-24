<?php 
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }

    include "../../include/common.php";
    $mysqli = connect_to_mysqli_database(false);
     
    $id = sanitize($_POST['id']);
     $user='114630';
    $query1 = "Update CMC.CMC_Cases SET Status='in_progress', AssignedToID = ? WHERE ID = ?";
    $stmt = mysqli_stmt_init($mysqli);
    if(mysqli_stmt_prepare($stmt, $query1)){
       mysqli_stmt_bind_param($stmt, 'ss', $user, $id);
       mysqli_stmt_execute($stmt);
       
      // mysqli_stmt_bind_result($stmt, $id, $subject, $description, $recipients, $status, $dateadded, $merchant, $department, $assignedto);
      /* while(mysqli_stmt_fetch($stmt)){
           //echo out table
           echo "Case Assigned To You";
       }*/
   };
 ?>