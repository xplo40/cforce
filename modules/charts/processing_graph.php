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
        
      
        $query = "Select ReportMonth_Name, 
                            Deposit_Amount 
                            from CMC.CMC_Agent_Processing_Monthly
                            where AgentID= '$agent'"; 
                            
        /* "
           
SELECT ReportMonth_Name
                 ,ReportMonthID
               , SUM(Deposit_Amount) AS Deposit_Amount
               ,agent.ID
                FROM (
                        SELECT AgentId1
                                        ,AgentId2
                                FROM CMC.CMC_Agents
                                WHERE ID = '$agent'
                ) AS agent
                LEFT JOIN(
                        SELECT MID
                                        ,AgentID
                                FROM CMC.CMC_Merchant
                ) AS mids
            ON agent.AgentId1 = mids.AgentID
                                OR agent.AgentId2 = mids.AgentID
                LEFT JOIN (
                        SELECT MID
                                        ,MONTH(ReportDate) AS ReportMonth_ID
          ,MONTHNAME(ReportDate) AS ReportMonth_Name
                                        ,SUM(Deposit_Amount) AS Deposit_Amount

                                FROM cmv.CMV_Batch_Data
                                WHERE ReportDate >= ADDDATE(CURDATE(), INTERVAL -1 YEAR)
                                GROUP BY MID, ReportMonth_ID, ReportMonth_Name
             ) As midlisting
                        ON midlisting.MID = mids.MID
                WHERE ReportMonth_Name IS NOT NULL
                GROUP BY ReportMonth_Name, ReportMonth_ID
                ORDER BY ReportMonth_ID"; */
    }
    else{
            $query = "Select ReportMonth_Name, 
                           SUM(Deposit_Amount) as Deposit_Amount 
                            from CMC.CMC_Agent_Processing_Monthly
			group by ReportMonth_Name";
      /*  $query = "SELECT MONTHNAME(ReportDate) AS ReportMonth_Name
                    ,SUM(Deposit_Amount) AS Deposit_Amount
                 FROM cmv.CMV_Batch_Data
             WHERE ReportDate >= ADDDATE(CURDATE(), INTERVAL -1 YEAR)
                         AND MONTHNAME(ReportDate) IS NOT NULL
                 GROUP BY ReportMonth_Name
            ORDER BY MONTH(ReportDate)"; */
    }
    
    
    $result = mysql_query($query); 

    // get data and store in a json array
    $data = "";
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data .= $row['Deposit_Amount'].",";
    }
    $data = substr($data, 0, -1);
    $data .= "";

   echo $data;

   ?>