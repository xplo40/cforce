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
    $id = sanitize($_GET['mid']);
  
    
    if($agent){
        $query = "SELECT SUM(YTD_Deposit_Amount) AS YTD_Deposit_Amount
                                ,SUM(Prior_Month_Deposit_Amount) AS Prior_Month_Deposit_Amount
								,SUM(MTD_Deposit_Amount) AS MTD_Deposit_Amount
								,SUM(Prior_Day_Deposit_Amount) AS Prior_Day_Deposit_Amount
								,SUM(All_Time_Deposit_Amount) AS All_Time_Deposit_Amount
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
                                Select Prior_Day AS Prior_Day_Deposit_Amount, 
								MTD AS MTD_Deposit_Amount, 
								Prior_Month AS Prior_Month_Deposit_Amount, 
								YTD AS YTD_Deposit_Amount, 
								All_Time AS All_Time_Deposit_Amount,
								MID
								from CMC.CMC_Merchant_Processing_Home
								GROUP BY MID
								) as summary
								on mids.MID = summary.MID";
        /*    SELECT SUM(YTD_Deposit_Amount) AS YTD_Deposit_Amount
                                ,SUM(Prior_Month_Deposit_Amount) AS Prior_Month_Deposit_Amount
								,SUM(MTD_Deposit_Amount) AS MTD_Deposit_Amount
								,SUM(Prior_Day_Deposit_Amount) AS Prior_Day_Deposit_Amount
								,SUM(All_Time_Deposit_Amount) AS All_Time_Deposit_Amount
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
				Select MID, Prior_Day AS Prior_Day_Deposit_Amount, 
                MTD AS MTD_Deposit_Amount, 
                Prior_Month AS Prior_Month_Deposit_Amount, 
                YTD AS YTD_Deposit_Amount, 
                All_Time AS All_Time_Deposit_Amount
                from CMC.CMC_Merchant_Processing
				GROUP BY MID
                ) AS merchprocess
				ON mids.MID= merchprocess.MID */   
    }
    else if($id){
        $query = "SELECT Prior_Day AS Prior_Day_Deposit_Amount, 
                        MTD AS MTD_Deposit_Amount, 
                        Prior_Month AS Prior_Month_Deposit_Amount, 
                        YTD AS YTD_Deposit_Amount, 
                        All_Time AS All_Time_Deposit_Amount
                FROM( 
                        SELECT MID FROM CMC.CMC_Merchant where ID = '$id'
                ) AS mid
                LEFT JOIN CMC.CMC_Merchant_Processing_Home AS data	
                        ON data.MID = mid.MID";
    }
    else{
        $query = "Select SUM(Prior_Day) As Prior_Day_Deposit_Amount
                    ,SUM(MTD) AS MTD_Deposit_Amount
                    ,SUM(Prior_Month) AS Prior_Month_Deposit_Amount
                    ,SUM(YTD) AS YTD_Deposit_Amount
                    ,SUM(All_Time) AS All_Time_Deposit_Amount
                   from CMC.CMC_Merchant_Processing_Home";  
        
        
        /*"
            
            SELECT YTD_Deposit_Amount
                                ,Prior_Month_Deposit_Amount
								,MTD_Deposit_Amount
								,Prior_Day_Deposit_Amount
								,All_Time_Deposit_Amount
                FROM (
                                SELECT SUM(Deposit_Amount) AS YTD_Deposit_Amount
								FROM cmv.CMV_Batch_Data
								WHERE ReportDate BETWEEN STR_TO_DATE(CONCAT( YEAR(CURDATE()), '0101' ),'%Y%m%d') AND CURDATE()
                ) As YTD
                LEFT JOIN (
                                SELECT SUM(Deposit_Amount) AS Prior_Month_Deposit_Amount
								FROM cmv.CMV_Batch_Data
								WHERE ReportDate BETWEEN DATE_SUB(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), INTERVAL DAY(CURDATE())-1 DAY) AND DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY)
                ) AS prior_month
                                ON 1=1
				LEFT JOIN (
								SELECT SUM(Deposit_Amount) AS MTD_Deposit_Amount
								FROM cmv.CMV_Batch_Data
								WHERE Month(ReportDate) = Month(CURDATE()) 
									AND Year(ReportDate) = Year(CURDATE()) 
                ) AS MTD
                                ON 1=1
				LEFT JOIN (		SELECT SUM(Deposit_Amount) AS Prior_Day_Deposit_Amount
								FROM cmv.CMV_Batch_Data
								WHERE ReportDate = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
                ) AS Prior_Day
                                ON 1=1
				
				LEFT JOIN (		SELECT SUM(Deposit_Amount) AS All_Time_Deposit_Amount
								FROM cmv.CMV_Batch_Data
						) AS All_Time
								ON 1=1" ;
    */
         }
         
    
    $result = mysql_query($query); 
    
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    
    $ytd = "$".number_format($row['YTD_Deposit_Amount']);
    $prior_month = "$".number_format($row['Prior_Month_Deposit_Amount']);
    $mtd = "$".number_format($row['MTD_Deposit_Amount']);
    $prior_day = "$".number_format($row['Prior_Day_Deposit_Amount']);
    $all_time = "$".number_format($row['All_Time_Deposit_Amount']);

    echo "<table class='data_summary'>
               <tbody>
                   <tr>
                       <th>Prior Day</th>
                       <th>MTD</th>
                       <th>Prior Month</th>
                       <th>YTD</th>
                       <th>All Time</th>
                   </tr>
                   <tr>
                       <td>$prior_day</td>
                       <td>$mtd</td>
                       <td>$prior_month</td>
                       <td>$ytd</td>
                       <td>$all_time</td>
                   </tr>
               </tbody>
           </table>";

    ?>