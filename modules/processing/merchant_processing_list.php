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
        $query = "Select MID
		,MerchantName
		,Prior_Month as Prior_Month_Deposit_Amount
		,Prior_Day as Prior_Day_Deposit_Amount
From CMC.CMC_Merchant_Processing_Monthly
where Agent_ID='$agent'
Order by Prior_Month DESC"; 
        
      /*  "SELECT 
		mids.MID
		,mids.DBA_Name AS MerchantName
		,IFNULL(Prior_Month_Deposit_Amount, 0) AS Prior_Month_Deposit_Amount
		,IFNULL(Prior_Day_Deposit_Amount,0) As Prior_Day_Deposit_Amount
		FROM (
               SELECT AgentId1
					 ,AgentId2
                       FROM CMC.CMC_Agents
                       WHERE ID = '$agent'
			) AS agent
       LEFT JOIN(
               SELECT MID
					, DBA_Name
					,AgentID
					FROM CMC.CMC_Merchant
			) AS mids
			  ON agent.AgentId1 = mids.AgentID
			  OR agent.AgentId2 = mids.AgentID
	  LEFT JOIN (
			 SELECT MID
					,SUM(Deposit_Amount) AS Prior_Month_Deposit_Amount
                     FROM cmv.CMV_Batch_Data
                       WHERE ReportDate BETWEEN DATE_SUB(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), INTERVAL DAY(CURDATE())-1 DAY) AND DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)
					GROUP BY MID
    ) As priormonth
               ON priormonth.MID = mids.MID
	LEFT JOIN (		SELECT MID
								,SUM(Deposit_Amount) AS Prior_Day_Deposit_Amount
								FROM cmv.CMV_Batch_Data
								WHERE ReportDate = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
								GROUP BY MID
                ) AS Prior_Day
                                ON Prior_Day.MID = mids.MID"; */
    }
    else{
        $query =  "Select MID
		,MerchantName
		,Prior_Month as Prior_Month_Deposit_Amount
		,Prior_Day as Prior_Day_Deposit_Amount
From CMC.CMC_Merchant_Processing_Monthly
Order by Prior_Month DESC LIMIT 100";
        /*
        "SELECT mi.MID
		,mi.DBA_Name AS MerchantName
		,IFNULL(Prior_Month_Deposit_Amount, 0) AS Prior_Month_Deposit_Amount
		,IFNULL(Prior_Day_Deposit_Amount, 0) As Prior_Day_Deposit_Amount
		FROM (
			 SELECT MID
					,SUM(Deposit_Amount) AS Prior_Month_Deposit_Amount
				FROM cmv.CMV_Batch_Data
				WHERE ReportDate BETWEEN DATE_SUB(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), INTERVAL DAY(CURDATE())-1 DAY) AND DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)
				GROUP BY MID
		) As prior_month
		LEFT JOIN (		
			SELECT MID
					,SUM(Deposit_Amount) AS Prior_Day_Deposit_Amount
				FROM cmv.CMV_Batch_Data
				WHERE ReportDate = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
				GROUP BY MID
		) AS prior_day
			ON prior_day.MID = prior_month.MID
		LEFT JOIN (		
			SELECT MID
					,DBA_Name
				FROM CMC.CMC_Merchant
		) AS mi
			ON mi.MID = prior_month.MID
		ORDER BY Prior_Month_Deposit_Amount DESC
		LIMIT 100"; */
    }
    
    $result = mysql_query($query); 
    $output = "<table class='data_table'><tbody>";
    $output .="<tr><th>MID</th><th>Merchant Name</th><th>Prior Month</th><th>Prior Day</th></tr>";
    while($row = mysql_fetch_assoc($result)){
        $monthamount = "$".number_format($row['Prior_Month_Deposit_Amount']);
        $dayamount = "$".number_format($row['Prior_Day_Deposit_Amount']);
        $output .= "<tr id=".$row['MID']."><td>".$row['MID']."</td><td>".$row['MerchantName']."</td><td>$monthamount</td><td>$dayamount</td></tr>";
    }
    $output .= "</tbody></table>";
    echo $output;

