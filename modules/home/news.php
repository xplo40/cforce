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
    
    //TODO: eventually we will use department to filter news results
    $department = $_SESSION['department'];
    $query = "SELECT News
            ,DateAdded
            ,Audience
        FROM CMC.CMC_News";
    $result = mysql_query($query);
    $output = "<table><tbody>";
    while($row = mysql_fetch_assoc($result)){
        $date = date('n/d/y', strtotime($row['DateAdded']));
        $output .= "<tr><th>$date</th><td>".$row['News']."</td></tr>";
    }
    $output .= "</tbody></table>";
    echo $output;
?>