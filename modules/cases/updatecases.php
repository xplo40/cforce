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

    $type = $_GET['type'];
    $user = $_SESSION['user'];
    $AssignedToDepartment = $_SESSION['department'];
    
    //for unassigned cases in your department
    if ($type == 'New'){
        $query = "SELECT ID
                ,DateAdded
                ,Subject
            FROM CMC.CMC_Cases
            WHERE AssignedToDepartment = '$AssignedToDepartment'
                    AND (AssignedToID = ''
                            OR AssignedToID IS NULL
                    )";
        $result = mysql_query($query);
        $total = mysql_num_rows($result);
        echo "<div style='background-color: #87bbc8' class='notification'>$total</div>";
        echo "<table id='new' class='case_table data_table'><tbody><tr><th style='width:40px'>Case ID</th><th style='width:100px'>Date</th><th style='width:200px'>Subject</th></tr>";
        while($row = mysql_fetch_assoc($result)){
            $date = date('m/d/y', strtotime($row['DateAdded']));
            echo "<tr id='case_".$row['ID']."' class='case_listing'><td>".$row['ID']."</td><td>$date</td><td>".$row['Subject']."</td></tr>";
        }
        echo "</tbody></table>";
    }
    
    //for open cases assigned to you
    if($type=='Open'){
        $query = "Select ID, Subject, DateAdded From CMC.CMC_Cases where Status != 'closed' AND AssignedToID = '$user'";
        $result = mysql_query($query);
        $total = mysql_num_rows($result);
        echo "<div style='background-color: #EE7853' class='notification'>$total</div>";
        echo "<table id='open' class='case_table data_table'><tbody><tr><th>Case ID</th><th>Date</th><th>Subject</th></tr>";
        while($row = mysql_fetch_assoc($result)){
            $date = date('m/d/y', strtotime($row['DateAdded']));
            echo "<tr id='case_".$row['ID']."' class='case_listing'><td>".$row['ID']."</td><td>$date</td><td>".$row['Subject']."</td></tr>";
        }
        echo "</tbody></table>";
    }
    
    //for cases assigned to your department
   /* if($type=='department'){
        $query = "Select ID, Subject, DateAdded 
                from CMC.CMC_Cases cases
				where AssignedToDepartment = '$AssignedToDepartment'
				and status='in_progress'";
        $result = mysql_query($query);
        $total = mysql_num_rows($result);
        echo "<table id='department' class='data_table' total='$total'><tbody><tr><th>Case ID</th><th>Date</th><th>Subject</th></tr>";
        while($row = mysql_fetch_assoc($result)){
            echo "<tr id='case_".$row['ID']."' class='case_listing'><td>".$row['ID']."</td><td>".$row['DateAdded']."</td><td>".$row['Subject']."</td></tr>";
        }
        echo "</tbody></table>";
    }*/
   
    //for your closed cases
    if($type=='Closed'){
        $query = "Select ID, Subject, DateAdded From CMC.CMC_Cases where Status = 'closed' AND AssignedToID = '$user'";
        $result = mysql_query($query);
        $total = mysql_num_rows($result);
        echo "<div style='background-color: #9ec568' class='notification'>$total</div>";
        echo "<table  id='closed' class='case_table data_table'><tbody><tr><th>Case ID</th><th>Date Added</th><th>Subject</th></tr>";
        while($row = mysql_fetch_assoc($result)){
            $date = date('m/d/y', strtotime($row['DateAdded']));
            echo "<tr id='case_".$row['ID']."' class='case_listing'><td>".$row['ID']."</td><td>$date</td><td>".$row['Subject']."</td></tr>";
        }
        echo "</tbody></table>";
    }
?>