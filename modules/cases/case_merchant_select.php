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
    connect_to_mysql_database(false);

    $term = sanitize($_GET['term']);
    $term = "%$term%"; 
    
    $query = "SELECT MID
                ,Legal_Name
                ,DBA_Name
                ,Email
                ,Phone
                FROM CMC.CMC_Merchant
                    WHERE (MID LIKE '$term'
                        OR Legal_Name LIKE '$term'
                        OR DBA_Name LIKE '$term'
                        OR Email LIKE '$term'
                        OR Phone LIKE '$term'
                )
                ORDER BY MID";

    $result = mysql_query($query);
    
    while($row = mysql_fetch_assoc($result)){
        $return[] = array("label"=>$row['Legal_Name'], "value"=>$row['MID']);
    }
    echo json_encode($return);
            
      
 ?>
   
