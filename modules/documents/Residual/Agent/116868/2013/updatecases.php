<?php
    include "../../include/database.php";
    $mysqli = connect_to_mysqli_database(false);

    // USER CREDENTIALS VALIDATION 
    $user = $_GET['user'];
    $password = $_GET['password'];
    
    //check for empty credentials
    if(!$user || !$password){
        return false;
    }
    
    //query user table
    $query = "SELECT Password, ID
            FROM CMC.CMC_Users 
            WHERE User = ?"; 

    $stmt = mysqli_stmt_init($mysqli);
    if(mysqli_stmt_prepare($stmt, $query)){
        mysqli_stmt_bind_param($stmt, 's', $user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $password2, $userid);
        mysqli_stmt_fetch($stmt);

        //check for bad credentials
        if($password != $password2){
            die;
        }
        mysqli_stmt_close($stmt);
    }
    //REMOVE LATER
    $user = '114630';
    $AssignedToDepartment='3';
    $type = $_GET['type'];
   
    if ($type == 'open'|| $type == 'all'){
        $query1 = "Select ID, Subject, DateAdded From CMC.CMC_Cases cases
               
                LEFT JOIN (
                        Select Department from CMC.CMC_Users
                                where ID = ?
                )d 
                   on d.department=cases.AssignedToDepartment  
                where cases.Status = 'open'
                    AND (cases.AssignedToID IS NULL
                        OR cases.AssignedToID = '')
                        AND cases.AssignedToDepartment = d.Department";
        $stmt = mysqli_stmt_init($mysqli);
        if(mysqli_stmt_prepare($stmt, $query1)){
             mysqli_stmt_bind_param($stmt, 's', $user);
             mysqli_stmt_execute($stmt);
             mysqli_stmt_bind_result($stmt, $id1, $subject1, $dateadded1);
             echo "<table id='open' class='data_table'><tbody><tr><th>Case ID</th><th>Date</th><th>Subject</th></tr>";
            while(mysqli_stmt_fetch($stmt)){
            //echo out table
                echo "<tr id='case_$id1' class='case_listing'><td>$id1</td><td>$dateadded1</td><td>$subject1</td></tr>";
            }
            echo "</tbody></table>";
        }
        mysqli_stmt_close($stmt);
    }
    
    if($type=='you'|| $type == 'all'){
         $query2 = "Select ID, Subject, DateAdded From CMC.CMC_Cases where Status = 'in_progress' AND AssignedToID = ?";
            $stmt = mysqli_stmt_init($mysqli);
            if(mysqli_stmt_prepare($stmt, $query2)){
                mysqli_stmt_bind_param($stmt, 's', $userid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $id, $subject, $dateadded);
                echo "<table id='you' class='data_table'><tbody><tr><th>Case ID</th><th>Date</th><th>Subject</th></tr>";
               // echo "<h1>".mysqli_stmt_num_rows()."</h1>";
                while(mysqli_stmt_fetch($stmt)){
                   echo "<tr id='case_$id' class='case_listing'><td>$id</td><td>$dateadded</td><td>$subject</td></tr>";
                 }
                 echo "</tbody></table>";
            }
            mysqli_stmt_close($stmt);
    }
    
    if($type=='department'|| $type == 'all'){
        $query3 = "Select ID, Subject, DateAdded 
                from CMC.CMC_Cases cases
				where AssignedToDepartment = ?
				and status='in_progress'";
        $stmt = mysqli_stmt_init($mysqli);
        if(mysqli_stmt_prepare($stmt, $query3)){
            mysqli_stmt_bind_param($stmt, 's', $AssignedToDepartment);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $subject, $dateadded);
             echo "<table id='department' class='data_table'><tbody><tr><th>Case ID</th><th>Date</th><th>Subject</th></tr>";
            //echo "<h1>".mysqli_stmt_num_rows()."</h1>";
            while(mysqli_stmt_fetch($stmt)){
                echo "<tr id='case_$id' class='case_listing'><td>$id</td><td>$dateadded</td><td>$subject</td></tr>";
            }
            echo "</tbody></table>";
        }
        mysqli_stmt_close($stmt);
        
    }
   
    if($type=='closed'|| $type == 'all'){
        $query4 = "Select ID, Subject, DateAdded From CMC.CMC_Cases where Status = 'closed'";
            $stmt = mysqli_stmt_init($mysqli);
          if(mysqli_stmt_prepare($stmt, $query4)){
              mysqli_stmt_execute($stmt);
              mysqli_stmt_bind_result($stmt, $id, $subject, $dateadded);
              echo "<table  id='closed' class='data_table'><tbody><tr><th>Case ID</th><th>Date</th><th>Subject</th></tr>";

              //echo "<h1>".mysqli_stmt_num_rows()."</h1>";
              while(mysqli_stmt_fetch($stmt)){
                  echo "<tr id='case_$id' class='case_listing'><td>$id</td><td>$dateadded</td><td>$subject</td></tr>";
              }
              echo "</tbody></table>";
        }
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($mysqli);
?>