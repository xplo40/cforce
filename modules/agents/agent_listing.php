<?php
    /* agent_listing.php
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
    $query = "SELECT ID
                        ,AgentId1
                        ,AgentId2
                        ,Name
                        ,Company
                        ,Email
                        ,PhoneNumber
                    FROM CMC.CMC_Agents
                    WHERE (AgentId1 LIKE '$term'
                        OR AgentId2 LIKE '$term'
                        OR Name LIKE '$term'
                        OR Company LIKE '$term'
                )
                ORDER BY ID  LIMIT $index,$count";
    
    $result = mysql_query($query);
    if($mode == "dropdown"){
        $json = "[";
        while($row = mysql_fetch_assoc($result)){
            if(strlen($json) > 1){
                $json .= ",";
            }
            $json .= "{\"label\":\"".$row['Name']."\",\"value\":\"".$row['RepCode_I']."\"}";
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
            $table .= "<tr id=agent_".$row['ID']."><td><div style='width:30px'>".$row['AgentId1']."</div></td><td><div style='width:30px'>".$row['AgentId2']."</div></td><td><div style='width:175px'>".$row['Name']."</div></td><td><div style='width:120px'>".$row['Company']."</div></td><td><div style='width:150px'>".$row['Email']."</div></td><td><div style='width:75px'>".$row['PhoneNumber']."</div></td></tr>";
        $cnt++;
        }
        echo $table;
    }
 ?>
   
