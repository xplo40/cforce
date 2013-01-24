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
    
    $user = $_SESSION['user'];

    $id = $_GET['id'];

    $query = "Select Subject
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
  
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    $subject = $row['Subject']; 
    $description = $row['Description']; 
    $additionalrecipients = $row['AdditionalRecipients']; 
    $status = $row['Status']; 
    $dateadded = $row['DateAdded']; 
    $merchant = $row['Merchant']; 
    $merchantname = $row['MerchantName']; 
    $assignedtodepartment = $row['AssignedToDepartment']; 
    $assigntoID = $row['AssignedToID']; 
    $assigntoemail = $row['AssignedToEmail']; 
    $creatoremail = $row['CreatorEmail']; 
       
    //echo out table
    $output  ="<div id=case_detail_info>";
    $output .= "<div class='section_header'><p>Case Details</p></div>";
            $output .= "<div class='section_div'>";
                        $output .= "<table id='caseinfo'>
                                    <tr><th>Case ID:</th><td id='case_id'>$id</td><th>Date Added:</th><td id='case_dateadded'>$dateadded</td></tr>
                                    <tr><th id ='center'>Merchant ID:</th><td id='case_merchant'>$merchant</td><th>Merchant Name:</th><td id='case_merchant_name'>$merchantname</td></tr>
                                    <tr><th>CC :</th><td><input type ='text' size='30' id='case_recipients' value='$additionalrecipients'/></td><th>Status:</th><td id='case_status'>$status</td></tr>
                                    <tr><th>Subject:</th><td id='case_subject'colspan='3'>$subject</td></tr>
                                    <tr><th>Description:</th><td id='case_description' colspan='3'>$description</td></tr>
                                    <tr><td><input type='hidden' id='assigntoemail' value='$assigntoemail'></input></td><td><input type='hidden' id='creatoremail' value='$creatoremail'></input></td></tr>
                                    </table>";
          $output .= "</div>";
   $output .= "</div>";        
     
    $output.="<br><button id='take_case_btn'>Take Case</button>";
    if($assigntoID == $user){
        $output .= "<script>$('#take_case_btn').hide();</script>";
    };
      $output  .="<div id=case_department_info>";
            $output .= "<div class='section_header'><p>Department Details</p></div>";
                $output .= "<div class='section_div'>";
                    $output .= "<table>";
                        
                        
    
    $query = "Select ID, DepartmentName from CMC.CMC_Department WHERE ID >= 10 ORDER BY DepartmentName";
    $result1 = mysql_query($query);
    $query = "Select ID, Name, Department From CMC.CMC_Users where Department = '".$_SESSION['department']."' ORDER BY Name";
    $result2 = mysql_query($query);

        while ($row1 = mysql_fetch_assoc($result1)){
            $department_id = $row1['ID'];
            $department_name = $row1['DepartmentName'];
            $output .="<tr>";
            $output .= "<td><input type='radio' class ='radio' name='department' department='$department_id' value='$department_id'".($department_id == $assignedtodepartment ? " checked='checked'" : "")."></input><label for='$department_id'>$department_name</label><br/></td>";
        
                if ($row1=mysql_fetch_assoc($result1)){
                    $department_id = $row1['ID'];
                   $department_name = $row1['DepartmentName'];
                   $output .= "<td><input type='radio' class ='radio' name='department' department='$department_id' value='$department_id'".($department_id == $assignedtodepartment ? " checked='checked'" : "")."></input><label for='$department_id'>$department_name</label><br/></td>";
                }

                  if ($row1=mysql_fetch_assoc($result1)){
                    $department_id = $row1['ID'];
                   $department_name = $row1['DepartmentName'];
                   $output .= "<td><input type='radio' class ='radio' name='department' department='$department_id' value='$department_id'".($department_id == $assignedtodepartment ? " checked='checked'" : "")."></input><label for='$department_id'>$department_name</label><br/></td>";
                }
                 $output .="</tr>";
          
            }
                $output .= "</table>";
             $output .= "</div>";
          $output .= "</div>";
        
          
         if((int)$_SESSION['department'] >= 10 && $department_id= $_SESSION['department']){
                 $output .= "<div id='department_member_info'>";
                         $output .= "<div class='section_header'><p>Department Members</p></div>";
                             $output .= "<div class='section_div'>";
                              $output .= "<table>";
         while ($row2 = mysql_fetch_assoc($result2)){
            $coworker_id = $row2['ID'];
            $coworker_name = $row2['Name'];
            $coworker_department = $row2['Department'];
            $output .="<tr>";
            $output .="<td><input type='radio' class ='radio' name='department' department='$coworker_department' user='$coworker_id'  value='$coworker_id'></input><label for='$coworker_id'>$coworker_name</label><br/></td>";
        
                if ($row2 = mysql_fetch_assoc($result2)){
                     $coworker_id = $row2['ID'];
                $coworker_name = $row2['Name'];
                $coworker_department = $row2['Department'];
                $output .="<td><input type='radio' class ='radio' name='department' department='$coworker_department' user='$coworker_id'  value='$coworker_id'></input><label for='$coworker_id'>$coworker_name</label><br/></td>";
                }
       
                 if ($row2 = mysql_fetch_assoc($result2)){
                     $coworker_id = $row2['ID'];
                $coworker_name = $row2['Name'];
                $coworker_department = $row2['Department'];
                $output .="<td><input type='radio' class ='radio' name='department' department='$coworker_department' user='$coworker_id'  value='$coworker_id'></input><label for='$coworker_id'>$coworker_name</label><br/></td>";
                }
        
                $output .="</tr>";
        }    
    }
    
    $output .="</table>";
  $output .= "</div>";
 $output .= "</div>";
    echo $output;
    
    $query = "SELECT NoteDate
                        ,Name AS Author
                        ,Note
                FROM CMC.CMC_Case_Notes notes
                INNER JOIN(
                        SELECT ID 
                                FROM CMC.CMC_Cases
                                WHERE ID = '$id'
                ) AS cases
                        ON cases.ID = notes.CaseID
                LEFT JOIN(
                        SELECT ID
                                        ,Name
                                FROM CMC.CMC_Users
                ) AS user
                        ON user.ID = notes.Author
                ORDER BY NoteDate DESC";
    $result = mysql_query($query);
    
      
    
    $str .= " <div id='add_note'>";
        $str .= "<div class='section_header'><p>Case Notes</p></div>";
            $str .= "<div class='section_div'>";  
           $str .=   "<table width=700px>
            <tbody>
                <tr>
                    <th align ='left'>Author</th>
                    <th align ='left'>Date</th>
                    <th align ='center'>Note</th>
                </tr>";
      
        while($row = mysql_fetch_array($result)){
            $author = $row['Author'];
            $date = $row['NoteDate']; 
            $note = $row['Note'];
            $str .= "<tr class='spaceUnder'><td align='left'>$author</td><td align='left'>$date</td><td align ='center'>$note</td></tr>";
        }
       $str .= "</tbody></table>";
       $str .= "<table>
               <tr>
                    <th align='left' colspan='2'>Add Note</th>
                </tr>
               <tr>
                    <td colspan='4'><textarea id='note_text' rows='4' cols='70'></textarea></td>
                </tr>
               <tr>
                    <td></td>
                    <td><button id='add_note_btn'>Save Note</button></td>
                </tr>
            </table>";
       $str .= "</div>";
 $str .= "</div>";
       echo $str;
   ?>














