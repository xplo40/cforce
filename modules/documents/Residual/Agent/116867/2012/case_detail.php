
<script type="text/javascript">
    $("#case_recipients").focusout(function(){
        $.ajax({
                            type: 'POST',
                            url: 'modules/cases/add_recipient.php?<? echo "user=$user&password=$password";?>',
                            data: {
                             id:  $("#case_id").text(),
                             cc:  $("case_recipients").val()
                            },
                             success: function(data){
                                
                             }
          })

    });
  
</script>
<style type="text/css">
#center{
    /*basic styles*/
	width: 100px;  height: 20px;  color: white; background-color: #ffaa56;
	text-align: center;  font-size: 12px;  line-height: 30px;
 
 
	/*gradient styles*/
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#99CF00), to(#6DB700));
	background: -moz-linear-gradient(19% 75% 90deg,#6DB700, #99CF00);
 
	/*border styles*/
	border-left: solid 1px #c3f83a;
	border-top: solid 1px #c3f83a;
	border-right: solid 1px #82a528;
	border-bottom: solid 1px #58701b;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	-webkit-gradient(linear, 0% 0%, 0% 100%, from(#99CF00), to(#6DB700))
}
tr.spaceUnder > td
{
  padding-bottom: 1em;
}

h1 {
	text-align: center;
	padding: 20px 0 12px 0;
	margin: 0;
}
h2 {
	font-size: 16px;
	text-align: center;
	padding: 0 0 12px 0; 
}



table {
	background-color: #F3F3F3;
	border-collapse: collapse;
	width: 100%;
	margin: 15px 0;
}

th {
	background-color: #FE4902;
	color: #FFF;
	cursor: pointer;
	padding: 5px 10px;
}

th small {
	font-size: 9px; 
}

td, th {
	text-align: left;
}

a {
	text-decoration: none;
}

td a {
	color: #663300;
	display: block;
	padding: 5px 10px;
}
th a {
	padding-left: 0
}

tr:nth-of-type(odd) {
	background-color: #E6E6E6;
}

tr:hover td {
	background-color:#CACACA;
}

tr:hover td a {
	color: #000;
}


</style>
<script src=".sorttable.js"></script>


<?php 
    include "../../include/common.php";
    $mysqli = connect_to_mysqli_database(false);
    $mysqldb = connect_to_mysql_database(false);
    $user = sanitize($_GET['user']);
    $password = sanitize($_GET['password']);
   // $user ='cms';
    if(!validateUser($user, $password)){
        echo "<p class='error'>Invalid credentials.</p>";
    }
   
     $id = $_POST['id'];
     $note = sanitize($_POST['note']);
     $user= '114630';
    

    $query1 = "Select ID
                ,Subject
                ,Description
                ,AdditionalRecipients
                ,Status
                ,DateAdded
                ,Merchant
                ,MerchantName
                ,AssignedToDepartment
                ,AssignedToID
                ,CreatorEmail
                ,AssignedToEmail 
            From CMC.CMC_Cases 
            WHERE ID = '$id'";
   /*$stmt = mysqli_stmt_init($mysqli);
   if(mysqli_stmt_prepare($stmt, $query1)){
       mysqli_stmt_bind_param($stmt, 's', $id);
       mysqli_stmt_execute($stmt);
*/      $result=mysql_query($query1);
         //mysqli_stmt_bind_result($stmt, $id, $subject, $description, $additionalrecipients, $status, $dateadded, $merchant, $merchantname, $assignedtodepartment, $assigntoID, $creatoremail, $AssignedToEmail);
        $row=mysql_fetch_array($result);
                $id=$row['ID'];
                $subject=$row['Subject']; 
                $description=$row['Description']; 
                $additionalrecipients=$row['AdditionalRecipients']; 
                $status=$row['Status']; 
                $dateadded=$row['DateAdded']; 
                $merchant=$row['Merchant']; 
                $merchantname=$row['MerchantName']; 
                $assignedtodepartment=$row['AssignedToDepartment']; 
                $assigntoID=$row['AssignedToID']; 
                $creatoremail=$row['CreatorEmail']; 
                $AssignedToEmail=$row['AssignedToEmail'];
       //mysqli_stmt_fetch($stmt);

       ////echo out table
          $output= "<div class=CSSTableGenerator>";
          $output .=  "<table style='color: white !important'>
            <tr class='d0'><td id='center'>Case ID:</td><td id='case_id'>$id</td><td>Date Added:</td><td text-align: center id='case_dateadded'>$dateadded</td></tr>
            
            <tr  class='d0'><td id ='center'>Merchant ID:</td><td id='case_merchant'>$merchant</td><td id='center'>Merchant Name:</td><td id='case_merchant_name'>$merchantname</td></tr>
            <tr class='d0'><td id='center'>CC :</td><td><input type ='text' size='30' id='case_recipients' value='$additionalrecipients'/></td><td id='center'>Status:</td><td id='case_status'>$status</td></tr>
            <tr  class='d0'><td id='center'>Subject:</td><td id='case_subject'colspan='3'>$subject</td></tr>
            <tr  class='d0'><td id='center'>Description:</td><td id='case_description' colspan='3'>$description</td></tr>
           
               </table></div>
               <input id='creator' type='hidden' value='$creatoremail'></input><input id='assignedto' type='hidden' value='$AssignedToEmail'></input>"; 
   //}
      //mysqli_stmt_reset($stmt);
   
    $output .="<br>";
    $output.="<button id='take_case_btn'>Take Case</button>";
    if($assigntoID == $user){
        $output .= "<script>$('#take_case_btn').hide();</script>";
    };
    
        $output .="<fieldset><legend>Assigned To:</legend>";
        $output .= "<table>";
    
    $output .=  "<tr>
                    <td></td><td></td>
                 </tr>";
    
    
    //$output .="<div id='DepartmentContainer'>";
    $output .= "<tr><td id='left_column' span style='font-weight:bold'>Department</td><td id='right_column' span style='font-weight:bold'>Department Members</td></tr>";
    $output .="<tr>";
    $query1 = "Select ID, DepartmentName from CMC.CMC_Department";
    $stmt1 = mysqli_stmt_init($mysqli);
    if(mysqli_stmt_prepare($stmt1, $query1)){
       mysqli_stmt_execute($stmt1);
        mysqli_stmt_bind_result($stmt1, $department_id, $department_name);
    }
    $department_arr = array(); 
    while(mysqli_stmt_fetch($stmt1)){
        $department_arr[] = "<td><input type='radio' class ='radio' name='department' department='$department_id' value='$department_id'".($department_id == $assignedtodepartment ? " checked='checked'" : "")."></input><label for='$department_id'>$department_name</label><br/></td>";
    }
    mysqli_stmt_close($stmt1);
  // while(mysqli_stmt_fetch($stmt)){
    //    $output .= "</tr><td><input type='radio' id='left_column' class ='radio' name='department' department='$department_id' value='$department_id'".($department_id == $assignedtodepartment ? " checked='checked'" : "")."></input><label for='$department_id'>$department_name</label><br/></td>";
        
    
   //  $output .="<td id='right_column' span style='font-weight:bold'>Department Members</td></tr>";
    $query2 = "Select ID, Name From CMC.CMC_Users where Department = ?";
    $stmt2 = mysqli_stmt_init($mysqli);
    if(mysqli_stmt_prepare($stmt2, $query2)){
         mysqli_stmt_bind_param($stmt2, 's', $user_department_id);
         mysqli_stmt_execute($stmt2);
         mysqli_stmt_bind_result($stmt2, $department_user_id, $department_user_name);
    }
    // $output .= "<hr noshade size='8'>";
    $department_row = current($department_arr);
    $employee_exists=mysqli_stmt_fetch($stmt2);
    while($department_row || $employee_exists){
        $output .="<tr>";
        if($department_row){
           // $output .="<td id='left_column' span style='font-weight:bold'>Assigned To</td>";
            $output .= $department_row;
        }
        else{
            $output .="<td></td>";
        }
        if($employee_exists){
           // $output .="<td id='right_column' span style='font-weight:bold'>Department Members</td></tr>";
            $output .="<td><input type='radio' class ='radio' name='department' department='$user_department_id' user='$department_user_id' value='$department_user_id'></input><label for='$department_user_id'>$department_user_name</label><br/></td>";
            }
        else{
            $output .="<td></td>";
        }
        $output .="</tr>";
        $department_row = next($department_arr);
        $employee_exists=mysqli_stmt_fetch($stmt2);
    }
    $output .="</table>";
    $output .="</fieldset>";
    //$output .="</div>";
     echo $output;
//WILLIAM: query for department list            
//WILLIAM: if statement between user_types (agents will only see departments and CMS will see departments and the users in their department
//WILLIAM: query for user list using department, left join to find user's department   
            
//WILLIAM: loop through query results to populate "reassign to" table
       
    mysqli_stmt_reset($stmt);
   
    $query2 = "Select NoteDate, Author, Note
                from CMC.CMC_Case_Notes notes
                LEFT JOIN(
                        Select ID 
			From CMC.CMC_Cases
                )dep
                        on dep.ID=notes.CaseID
                where dep.ID = ?
                order by NoteDate DESC";
    $stmt = mysqli_stmt_init($mysqli);
    if(mysqli_stmt_prepare($stmt, $query2)){
        mysqli_stmt_bind_param($stmt, 's', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $date, $author, $note);
        $str = "<br></br><hr noshade size='16px'>
            <div id='add_note'>
            <table width=700px style='color: white !important'>
                <tbody>
                    <tr>
                        <th align ='center'>Author</th>
                        <th align ='center'>Date</th>
                        <th align ='center'>Note</th>
                    </tr>";
        //echo "<h1>".mysqli_stmt_num_rows()."</h1>";
        while(mysqli_stmt_fetch($stmt)){
            $str .= "<tr class='spaceUnder'><td align=center>$author</td><td align ='center'>$date</td><td align ='left'>$note</td></tr>";
        }
       $str .= "</tbody></table>";
       
       $str .= "<table>
               <tr>
                    <th align='left' colspan='2'>Add Note</th>
                </tr>
               <tr>
                    <td colspan='4'><textarea id='note_text'></textarea></td>
                </tr>
               <tr>
                    <td></td><td><button id='add_note_btn'>Save Note</button></td>
                </tr>
            </table>";
       echo $str;
    }
   
    //echo "<script>alert('hi');</script>";
    
    mysqli_stmt_close($stmt);
   ?>














