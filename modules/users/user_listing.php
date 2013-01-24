<?php
    /* merchant_listing.php
     * author: William Atkinson
     * date-created: 9/27/12 
     * 
     * Accepts the following GET variables:
     *  user (required): Used to validate user
     *  password (required): Used to validate user
     *  mode (required): Used to format output
     *      = dropdown: Outputs a json array with 'label'(MerchantName) and 'value'(MID) keys
     *      = table: Outputs the rows of a table with MID, Name, Contact, Email, and Phone fields
     *  term (optional): String that filters results by searching for that string in MID, MerchantName, ContactName, Email, and ContactPhone
     *  count (optional, for table mode): The number of results to return (default = 100).
     *  index (optional, for table mode): The index to start returning results (default = 0).
     */

    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    $user = $_SESSION['user'];
    
    include "../../include/common.php";
    $dbconnect = connect_to_mysql_database(false);

    $mode = sanitize($_GET['mode']);
    $term = sanitize($_GET['term']);
    $count = sanitize($_GET['count']);
    $index = sanitize($_GET['index']);
 
    //Set default values
    if(!$term){
        $term = "";
    }
    $term = "%$term%"; 
    if(!$count){
        $count = 100;
    }
    $count = (int)$count;
    if(!$index){
        $index = 0;
    }
    $index = (int)$index;
    $query = "Select ID
                    ,Profile
                    ,Name
                    ,DDA
                    ,Email
                    ,Department
                    FROM CMC.CMC_Users
                    WHERE (ID LIKE '$term'
                        OR Profile LIKE '$term'
                        OR DDA LIKE '$term'
                        OR Email LIKE '$term'
                        OR Department LIKE '$term'
                )
                ORDER BY ID
                LIMIT $index,$count";
    
    $result = mysql_query($query);
    if($mode == "dropdown"){
        $json = "[";
        while($row = mysql_fetch_assoc($result)){
            if(strlen($json) > 1){
                $json .= ",";
            }
            $json .= "{\"label\":\"".$row['Name']."\",\"value\":\"".$row['ID']."\"}";
        }
        $json .= "]";
        echo $json;
    }
    elseif($mode == "table"){
        if(mysql_num_rows($result) == 0){
            die;
        }
        $table = "";
        $cnt = $index;
        while($row = mysql_fetch_assoc($result)){
            $table .= "<tr id=merch_".$row['ID']."><td>".$row['ID']."</td><td>".$row['Name']."</td><td>".$row['DDA']."</td><td>".$row['Email']."</td><td>".$row['Department']."</td></tr>";
        $cnt++;
            
        }
        echo $table;
    }
    
     /*MYSQLI DISABLED FOR SPEED
    //merchant search query
    $query = "SELECT mi.MID
                ,MerchantName
                ,ContactName
                ,Email
                ,ContactPhone
            FROM (
                SELECT Profile
                    FROM CMC.CMC_Users 
                    WHERE User = ?
            ) AS users
            LEFT JOIN(
                SELECT MID
                        ,Profile
                    FROM CMC.CMC_Profile_Mids
            ) AS mids
                ON users.Profile = mids.Profile
            INNER JOIN(
                SELECT MID
                        ,MerchantName
                        ,ContactName
                        ,Email
                        ,ContactPhone
                    FROM CMS.MerchantInfo
                    WHERE (MID LIKE ?
                        OR MerchantName LIKE ?
                        OR ContactName LIKE ?
                        OR Email LIKE ?
                        OR ContactPhone LIKE ?
                )
                ORDER BY MID
                LIMIT ?,?
            ) AS mi
                ON mids.MID = mi.MID
            ORDER BY mi.MID";
    
    $stmt = mysqli_stmt_init($mysqli);
    if(mysqli_stmt_prepare($stmt, $query)){
        mysqli_stmt_bind_param($stmt, 'ssssssii', $user, $term, $term, $term, $term, $term, $index, $count);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $mid, $name, $contact, $email, $phone);
        if($mode == "dropdown"){
            $json = "[";
            while (mysqli_stmt_fetch($stmt)){
                if(strlen($json) > 1){
                    $json .= ",";
                }
                $json .= "{\"label\":\"$name\",\"value\":\"$mid\"}";
            }
            $json .= "]";
            echo $json;
        }
        elseif($mode == "table"){
            if(mysqli_stmt_num_rows($stmt) == 0){
                die;
            }
            while (mysqli_stmt_fetch($stmt)){
                $table .= "<tr id=merch_$mid><td>$mid</td><td>$name</td><td>$contact</td><td>$email</td><td>$phone</td></tr>";
            }
            echo $table;
            
        }
        mysqli_stmt_close($stmt);
    }*/
 ?>
   
