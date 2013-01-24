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
    
    //get merchant details
    $id = sanitize($_GET['id']);

    //  header("Content-type: text/json");
    $query = "SELECT MONTHNAME(ReportDate) AS ReportMonth
		,SUM(Deposit_Amount) AS Deposit_Amount 
	FROM (
		SELECT MID
			FROM CMC.CMC_Merchant
			WHERE ID = '$id'
	) AS mid
	LEFT JOIN(
		SELECT * 
			FROM cmv.CMV_Batch_Data 
			WHERE ReportDate >= ADDDATE(CURDATE(), INTERVAL -1 YEAR)
	)AS data
		ON data.MID = mid.MID
	GROUP BY MONTH(ReportDate) 
	ORDER BY MONTH(ReportDate)  
	LIMIT 0, 100";
    $result = mysql_query($query); 

    // get data and store in a json array
    $data = "";
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data .= $row['Deposit_Amount'].",";
    }
    $data = substr($data, 0, -1);
    
   echo $data;
?>