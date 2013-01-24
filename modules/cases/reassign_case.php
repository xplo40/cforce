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
     
    $assigned_user = sanitize($_POST['user']);
    $department = sanitize($_POST['department']);
    $id = sanitize($_POST['id']);
    
    if (empty($assigned_user)){
        $status = "open";
    }
    else{
        $status = "in_progress";
    }
    
    $query1 = "Update CMC.CMC_Cases SET Status = ?, AssignedToDepartment = ?, AssignedToID = ? WHERE ID = ?";
    $stmt = mysqli_stmt_init($mysqli);
    if(mysqli_stmt_prepare($stmt, $query1)){
       mysqli_stmt_bind_param($stmt, 'ssss', $status, $department, $assigned_user, $id);
       mysqli_stmt_execute($stmt);
    }
      // mysqli_stmt_bind_result($stmt, $id, $subject, $description, $recipients, $status, $dateadded, $merchant, $department, $assignedto);
      /* while(mysqli_stmt_fetch($stmt)){
           //echo out table
           echo "Case Assigned To You";
       }*/
 ?>