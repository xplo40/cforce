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
    
    //filter results if given an agent id
    $agent = sanitize($_GET['agent']);
    if($agent){
        $query = "SELECT MonthNameAdded
		,COUNT(MID) as Boarded_Merchants
	FROM(
		SELECT AgentID1
				,AgentID2
			FROM CMC.CMC_Agents
			WHERE ID = '$agent'
	) AS agent
	LEFT JOIN(
		SELECT MID
				,AgentID
				,MONTH(DateAdded) AS MonthAdded
				,MONTHNAME(DateAdded) AS MonthNameAdded
			FROM CMC.CMC_Merchant
			WHERE DateAdded >= ADDDATE(CURDATE(), INTERVAL -3 YEAR)
	) AS mids
		ON agent.AgentID1 = mids.AgentID
			OR agent.AgentID2 = mids.AgentID
        WHERE MonthNameAdded IS NOT NULL
	GROUP BY MonthAdded, MonthNameAdded
	ORDER BY MonthAdded";
    }
    else{
        $query = "SELECT MONTHNAME(DateAdded) AS MonthNameAdded
		,COUNT(MID) as Boarded_Merchants
	FROM CMC.CMC_Merchant
	WHERE DateAdded >= ADDDATE(CURDATE(), INTERVAL -3 YEAR)
        AND MONTHNAME(DateAdded) IS NOT NULL
	GROUP BY MonthNameAdded
	ORDER BY MONTH(DateAdded)";
    }
    
    $result = mysql_query($query); 

    // get data and store in a json array
    $data = "";
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
       
        $data .= $row['Boarded_Merchants'].",";
    }
    $data = substr($data, 0, -1);

   echo $data;

   ?>