<?php

//check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    
  if ($_SERVER['HTTPS'] != "on") { 
        $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; 
        header("Location: $url"); 
    }  
    
    //import database connection utility
    include "../include/database.php";
    connect_to_mysql_database(false);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>CForce Case View</title>
        
        <link rel="shortcut icon" href="images/favicon.ico" type='image/x-icon' />
        <link type="text/css" href="css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        
        <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="js/highcharts.js" type="text/javascript"></script>
        <script src="js/jquery.cookie.js"></script>
        <script src="js/jquery.address-1.4.min.js" type="text/javascript"></script>
        <script type="text/javascript">
            
       $("#merchant-search-field").ready(function(){
                    var searchString = $("#merchant-search").val();
                    //$("#agent-search-results").html("<img src='images/cms_loading.gif'></img>");
                    
                    if(searchString.length >=2)
                        $.ajax({
                            url: "merchant_listing.php?user=<?php echo $user; ?>&password=<?php echo $password; ?>",
                            type: "POST",
                            data: {
                                id: searchString
                            },
                            success: function(data) {
                             $("#merchant_search")   
                            }
  });                       

 
      </script>
        
  </head>
    <body>
        <div id="wrapper" class="with-footer">
            <div id="header">
                <div id="cmv-logo">               
                    <img src="images/cmvlogo.png"></img>
                </div>
                <div id ="case_search"><?
                  $query = "SELECT mi.ID AS ID
	,Sendee
        ,Subject
	,Description
	,Recipients
	,`Status`
	,DateAdded
	,DateUpdated
	FROM CMC.CMC_Cases AS p 
         INNER JOIN ( 
		SELECT ID
		FROM CMC.`CMC_Users`) AS mi
		ON mi.ID = p.ID
                        WHERE (ID LIKE '%%'
                                    OR Sendee LIKE '%%'
                                    OR Subject LIKE '%%'
                                    OR Description LIKE '%%'
                                    OR Priority LIKE '%%'
                                    OR Recipients LIKE '%%'
                                    OR Status LIKE '%%'
                                    OR DateAdded LIKE '%%'
                                    OR DateUpdated LIKE '%%'
                        )
                        ORDER BY MerchantName";
                $result = mysql_query($query);

              $str = "<table border ='2'><tbody><tr><th>ID</th><th>Sendee</th><th>Profile</th><th>Status</th><th>Description</th></tr>";         
    
   while ($row = mysql_fetch_assoc($result)){
        $str.= "<tr id = '".$row['ID']."'>"; 
        $str.=  "<td>".$row['Sendee']."</td>";
        $str.= "<td>".$row['Description']."</td>";
        $str.= "<td>".$row['Recipients']."</td>";
        $str.= "<td>".$row['Status']."</td>";
        $str.= "<td>".$row['DateAdded']."</td>";
        $str.= "<td>".$row['DateUpdated']."</td>";
        $str.= "<td><button class='add_case_button' data-id='4'>Reserve</button></td>"
     
    $str.= "</tr>";    
     
    $str.="</tbody></table>";
                //create output


    echo $str;
                ?>
                </div>


	
                        
               <form action="case_submit.php" method="post">
               <table width="450px">
               <tr>
                    <td valign="top">
                        <label for="subject">Subject</label>
                    </td>
                    <td valign="top">
                        <input  type="type" name="Subject" id="subject" maxlength="255" size="30">
                    </td>
               </tr>
               <tr>
                    <td valign="top">
                        <label for="Merchant">Merchant</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="Merchant" id ="merchant search" maxlength="50" size="30">
                    </td>
              </tr>
               <tr>
                    <td valign="top">
                        <label for="Description">Description *</label>
                    </td>
                    <td valign="top">
                        <textarea  name="Description" id ="description" maxlength="1000" cols="25" rows="6"></textarea>
                    </td>
              </tr>
             
              <tr>
               <td colspan="2" style="text-align:center">
              <input type="Submit" value="Submit Case">   
             </td>
             </tr>
            </table>
            </form>






                 
                
 </body>
</html>