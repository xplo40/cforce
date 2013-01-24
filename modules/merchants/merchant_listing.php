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

    $mode = sanitize($_GET['mode']);
    $term = sanitize($_GET['term']);
    $count = sanitize($_GET['count']);
    $index = sanitize($_GET['index']);
    $agent = sanitize($_GET['agent']);
    $filter = sanitize($_GET['filter']);

    //Set default values
   if(!$term){
        $term = "";
    }
    $term = "%$term%"; 
    if(!$count){
        $count = 20;
    }
    $count = (int)$count;
    if(!$index){
        $index = 0;
    }
    $index = (int)$index;
    
    if($agent){
        if($filter){
            $filter = "WHERE Status = '$filter'";
        }
        $query =" SELECT mids.ID
                ,MID
                ,Legal_Name
                ,DBA_Name
                ,Email
                ,Phone
                ,Status
                FROM (
                                SELECT AgentId1
				,AgentId2
                                FROM CMC.CMC_Agents
                                WHERE ID = '$agent'
                ) AS agent
                LEFT JOIN(
                                SELECT ID
                                    ,MID
                                    ,AgentID					 
                                    ,Legal_Name
                                    ,DBA_Name
                                    ,Email
                                    ,Phone
                                    ,Status
                                FROM CMC.CMC_Merchant
                                $filter
                ) AS mids
                                ON agent.AgentId1 = mids.AgentID
				OR agent.AgentId2 = mids.AgentID
                                WHERE (MID LIKE '$term'
                                OR Legal_Name LIKE '$term'
                                OR DBA_Name LIKE '$term'
                                OR Email LIKE '$term'
                                OR Phone LIKE '$term'
                    )
                            ORDER BY MID
                            LIMIT $index,$count";
        
    }
    else{
        if($filter){
            $filter = "AND Status = '$filter'";
        }
        $query = "SELECT ID
                ,MID
                ,Legal_Name
                ,DBA_Name
                ,Email
                ,Phone
                ,Status
                FROM CMC.CMC_Merchant
                    WHERE (MID LIKE '$term'
                        OR Legal_Name LIKE '$term'
                        OR DBA_Name LIKE '$term'
                        OR Email LIKE '$term'
                        OR Phone LIKE '$term'
                )
                $filter
                ORDER BY MID
                LIMIT $index,$count";
    };
    //echo $query;
    $result = mysql_query($query);
    if($mode == "dropdown"){
        $json = "[";
        while($row = mysql_fetch_assoc($result)){
            if(strlen($json) > 1){
                $json .= ",";
            }
            $json .= "{\"label\":\"".$row['Legal_Name']."\",\"value\":\"".$row['MID']."\"}";
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
            $id = $row['ID'];
            $mid = $row['MID'];
            if($row['Status'] == 'Declined'){
                $color = "style='background: #FF8F86'";
            }
            else{
                $color = "";
            }
            
            if(empty($mid)){
                $mid = "(none)";
            }
            $name = $row['DBA_Name'];
            if(empty($name)){
                $name = $row['Legal_Name'];
            }
            $table .= "<tr id=merch_$id $color><td><div style='width: 110px'>$mid</div></td><td><div style='width: 210px'>$name</div></td><td><div style='width: 180px'>".$row['Email']."</div></td><td><div style='width: 85px'>".$row['Phone']."</div></td></tr>";
        $cnt++;
            
        }
        echo $table;
    }
 ?>
   

   
