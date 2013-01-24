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
    
    //populate assign_to list
    $query1 = "SELECT 
            ID
            ,DepartmentName 
        FROM CMC.CMC_Department
        WHERE DepartmentName != 'empty' 
            AND DepartmentName != 'Basic'
            AND DepartmentName != 'Admin'";
    $result1 = mysql_query($query1);
    $query2 = "SELECT ID
            ,Name 
        FROM CMC.CMC_Users 
        WHERE Department = '".$_SESSION['department']."'
            AND Department >= 10";
    $result2 = mysql_query($query2);   

    $assign_to = "<div id='department_listing'>";
                    $assign_to .= "<div class='section_header'><p>Department</p></div>";
                        $assign_to .= "<div class='section_div'>";
                         $assign_to .= "<table>";
        while ($row1 = mysql_fetch_assoc($result1)){
            $department_id = $row1['ID'];
            $department_name = $row1['DepartmentName'];
            $assign_to .= "<tr>";
                $assign_to .= "<td><input type='radio' class ='radio' name='department' value='$department_id'></input><label for='$department_id'>$department_name</label></td>";
                               
        
         if ($row1=mysql_fetch_assoc($result1)){
            $department_id = $row1['ID'];
            $department_name = $row1['DepartmentName'];
            $assign_to .= "<td><input type='radio' class ='radio' name='department' value='$department_id'></input><label for='$department_id'>$department_name</label></td>";
        }
         if($row1=mysql_fetch_assoc($result1)){
            $department_id = $row1['ID'];
            $department_name = $row1['DepartmentName'];
            $assign_to .= "<td><input type='radio' class ='radio' name='department' value='$department_id'></input><label for='$department_id'>$department_name</label></td>";
        }
        $assign_to .= "</tr>";
    }     
                        $assign_to .= "</table>";
                    $assign_to .= "</div>";
                $assign_to .= "</div>";
    
    if((int)$_SESSION['department'] >= 10){
                 $assign_to .= "<div id='member_listing'>";
                         $assign_to .= "<div class='section_header'><p>Department Members</p></div>";
                             $assign_to .= "<div class='section_div'>";
                              $assign_to .= "<table>";
         while ($row2 = mysql_fetch_assoc($result2)){
             $department_user_id = $row2['ID'];
             $department_user_name = $row2['Name'];

             $assign_to .= "<tr>";
             $assign_to .= "<td><input type='radio' class ='radio' name='coworker' value='$department_user_id'></input><label for='$department_user_id'>$department_user_name</label></td>";

             if ($row2 = mysql_fetch_assoc($result2)){
                 $department_user_id = $row2['ID'];
                 $department_user_name = $row2['Name'];
                 $assign_to .= "<td><input type='radio' class ='radio' name='coworker' value='$department_user_id'></input><label for='$department_user_id'>$department_user_name</label></td>";
             }
             if ($row2 = mysql_fetch_assoc($result2)){
                 $department_user_id = $row2['ID'];
                 $department_user_name = $row2['Name'];
                 $assign_to .= "<td><input type='radio' class ='radio' name='coworker' value='$department_user_id'></input><label for='$department_user_id'>$department_user_name</label></td>";
             }
             $assign_to .= "</tr>";
         }     
                             $assign_to .= "</table>";
                         $assign_to .= "</div>";
                     $assign_to .= "</div>";
    }  
   

    $assign_to .= "<div id='agent_listing_results'>";
                         $assign_to .= "<div class='section_header'><p>Agent Search</p></div>";
                             $assign_to .= "<div class='section_div'>";
                               $assign_to .= "<table>";
                                    $assign_to .= "<tr>";
                                        $assign_to .="<td id='agent_case_search'><input type='text' id='agent_listing_case_list' class='agent_listing_case_list'></td>";
                                        $assign_to .="<td colspan='2'>";
                                                $assign_to .="<h3 id='case_agent_selected'></h3>";
                                                $assign_to .= "<p id='case_agent_ID_selected'></p>";
                                                $assign_to .="<input type='hidden' id='case_agent_email'>";
                                        $assign_to .="</td>";
                                    $assign_to .="</tr>";
                               $assign_to .= "</table>";
                         $assign_to .= "</div>";
                     $assign_to .= "</div>";

    echo $assign_to;
   
   ?>


