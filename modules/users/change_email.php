<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }

$email = $_POST['email'];
    $profile = $_POST['profile'];
    $user = $_GET['user'];
    $password = $_GET['password'];
    
    if(!empty($user) && !empty($password)){
        include "database.php";
        connect_to_mysql_database(false);
        
        //check user/password
        $query = "SELECT Password
                FROM CMC.CMC_Users AS profiles
                WHERE profiles.Profile = '$user'"; 
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        
        if($password == $row['Password']){
            $query = "UPDATE CMC.CMC_Users
                    SET Email = '$email',
                        DateUpdated = CURDATE()
                    WHERE Profile = '$profile'";
            mysql_query($query);

            echo "Email has been changed to '$email'.";
    }
    else{
            echo "Incorrect login credentials!";
        }
    }
    else{
        echo "Incorrect login credentials!";
    }
?>
