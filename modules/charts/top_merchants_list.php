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
        $query="Select MerchantID
                        ,MID
                        ,MerchantName
                        ,Deposit_Amount
from CMC.CMC_Top_Ten_Processing_YTD
                        where Agent_ID='$agent'
ORDER BY Deposit_Amount desc
	LIMIT 10";        
        /*"SELECT mids.MID 
		,DBA_Name AS MerchantName
		,Deposit_Amount
	FROM (
		SELECT AgentId1
				,AgentId2
			FROM CMC.CMC_Agents
			WHERE ID = '$agent'
	) AS agent
	LEFT JOIN(
		SELECT MID
                            ,DBA_Name
                            ,AgentID
			FROM CMC.CMC_Merchant
	) AS mids
		ON agent.AgentId1 = mids.AgentID
			OR agent.AgentId2 = mids.AgentID
	LEFT JOIN(
		SELECT SUM(Deposit_Amount) AS Deposit_Amount 
				,MID
			FROM cmv.CMV_Batch_Data 
			WHERE YEAR(ReportDate) = YEAR(CURDATE())
			GROUP BY MID
	) AS processing
		ON processing.MID = mids.MID
	ORDER BY Deposit_Amount desc
	LIMIT 10";*/
    }
    else{
        $query= "Select MerchantID
                        ,MID
                        ,MerchantName
                        ,Deposit_Amount
from CMC.CMC_Top_Ten_Processing_YTD
ORDER BY Deposit_Amount desc
	LIMIT 10";
       /* "SELECT p.MID 
		,DBA_Name AS MerchantName
		,Deposit_Amount
	FROM (
		SELECT MID
				,SUM(Deposit_Amount) AS Deposit_Amount
			FROM cmv.CMV_Batch_Data 
			WHERE YEAR(ReportDate) = YEAR(CURDATE())
			GROUP BY MID
	) AS p
	LEFT JOIN (
		SELECT MID
				,DBA_Name
			FROM CMC.CMC_Merchant
	) AS mi
		ON mi.MID = p.MID
	ORDER BY Deposit_Amount desc
	LIMIT 10"; */
    }
    
    
   
    $result = mysql_query($query);
    
    $output = "<table class='data_table'><tbody>";
    $output .="<tr><th>MID</th><th>Merchant Name</th><th>Amount</th></tr>";
    while($row = mysql_fetch_assoc($result)){
        $amount = "$".number_format($row['Deposit_Amount']);
        $output .= "<tr id='".$row['MerchantID']."'><td>".$row['MID']."</td><td>".$row['MerchantName']."</td><td>$amount</td></tr>";
    }
    $output .= "</tbody></table>";
    echo $output;
?>


