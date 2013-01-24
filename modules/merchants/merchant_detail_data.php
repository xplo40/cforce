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
    
    $query = "SELECT * from CMC.CMC_Merchant where ID = '$id'";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    
    $legal_name = $row['Legal_Name'];
    $dba_name = $row['DBA_Name'];
    $address = $row['Address'];
    $city = $row['City'];
    $state = $row['State'];
    $zip = $row['Zip'];
    $email = $row['Email'];
    $phone = $row['Phone'];
    $account_status = $row['Status'];
    $date_added = $row['DateAdded'];
    $rep_name = $row['Rep_Name'];
    $agentID = $row['AgentID'];
    $merchant_id = $row['MID'];
    $monthly_volume = number_format($row['Monthly_Volume'],2);
    $avg_ticket = number_format($row['Average_Ticket'],2);
    $prefund = number_format($row['Prefund'],2);
    $pricing_type = $row['Pricing_Type'];
    $percent = number_format($row['Percentage'],2);
    $transaction_fee = number_format($row['TransFee'],2);
    $debit = number_format($row['Debit'],2);
    $qual = number_format($row['Qual'],2);
    $midqual = number_format($row['Mid_Qual'],2);
    $nonqual = number_format($row['Non_Qual'],2);
    $amex = $row['Amex'];
    $discover = $row['Discover'];
    $pin_debit = $row['Pin_Debit'];
    $pin_debit_fee = number_format($row['Pin_Transfer_Fee'],2);
    $monthly_min = number_format($row['Monthly_Min'],2);
    $statement_fee = number_format($row['Statement_Fee'],2);
    $hardware= $row['Hardware'];
    $process_platform = $row['Platform'];
    
    $date_added = date('n/d/y g:i a', strtotime($date_added));
    
    echo "<table id='merchant_detail_table'>
            <tbody>
                <tr>
                    <th style='width:150px'>Legal Name</th>
                    <td style='width:340px'>$legal_name</td>
                    <th style='width:150px'>DBA Name</th>
                    <td style='width:340px' id='detail_dba'>$dba_name</td>
                </tr>
                <tr>
                    <th>MID</th>
                    <td id='detail_mid'>".(empty($merchant_id) ? "(none)" : $merchant_id)."</td>
                    <th>Account Status</th>
                    <td>$account_status</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>$address</td>
                    <th>Email</th>
                    <td>$email</td>
                </tr>
                 <tr>
                    <th></th>
                    <td>".(empty($city) ? $state : (empty($state) ? $city : "$city, $state"))." $zip</td>
                    <th>Phone</th>
                    <td>$phone</td>
                </tr>
                <tr>
                    <th>Agent Name</th>
                    <td>$rep_name</td>
                    <th>Agent ID</th>
                    <td>$agentID</td>
                </tr>
                <tr>
                    <th>Monthly Volume</th>
                    <td>$$monthly_volume</td>
                    <th>Average Ticket</th>
                    <td>$$avg_ticket</td>
                </tr>
                <tr>
                    <th>Pre-fund</th>
                    <td>$$prefund</td>
                    <th></th>
                    <td></td>
                </tr>
                <tr>
                    <th>Pricing Type</th>
                    <td>".($pricing_type == 0 ? "(not set)" : ($pricing_type == 1 ? "Interchange Plus" : "Tiered"))."</td>
                    <th>Platform</th>
                    <td>".($process_platform == 0 ? "Omaha" : ($process_platform == 1 ? "Nashville" : ($process_platform == 2 ? "CardNet (North)" : ($process_platform == 3 ? "Bypass" : "Global"))))."</td>
                </tr>";
    if($pricing_type == 1){
        echo "<tr>
                    <th>Percentage</th>
                    <td>$percent%</td>
                    <th>Tran Fee</th>
                    <td>$$transaction_fee</td>
                </tr>";
    }
    if($pricing_type == 2){
        echo "<tr>
                    <th>Tran Fee</th>
                    <td>$$transaction_fee</td>
                    <th></th>
                    <td></td>
                </tr>
                <tr>
                    <th>Debit</th>
                    <td>$debit%</td>
                    <th>Qual</th>
                    <td>$qual%</td>
                </tr>
                 <tr>
                    <th>Mid Qual</th>
                    <td>$midqual%</td>
                    <th>Non Qual</th>
                    <td>$nonqual%</td>
                </tr>";
    }
    echo "      <tr>
                    <th>Amex</th>
                    <td>".($amex == '1' ? "Yes" : "No")."</td>
                    <th>Discover</th>
                    <td>".($discover == '1' ? "Yes" : "No")."</td>
                </tr>
                <tr>
                    <th>Pin Debit</th>
                    <td>".($pin_debit == '1' ? "Yes" : "No")."</td>
                    <th>Pin Debit Fee</th>
                    <td>$$pin_debit_fee</td>
                </tr>
                <tr>
                    <th>Monthly Minimum</th>
                    <td>$$monthly_min</td>
                    <th>Statement Fee</th>
                    <td>$$statement_fee</td>
                </tr>
                <tr>
                    <th>Hardware</th>
                    <td>$hardware</td>
                    <th>Date Added</th>
                    <td>$date_added</td>
                </tr>
            </tbody>
        </table>";

   echo "<div id='edit_merchant_modal' title='Edit Merchant'> 
        <table>
            <tr>
                <th style='width:80px !important'>DBA</th>
                <td style='width:210px !important'>
                    <input type='text' id='dba_name' value=\"$dba_name\"v></input>
                </td>
                <th>Legal Name</th>
                <td colspan='3'>
                    <input type='text' id='legal_name' value=\"$legal_name\"></input>
                </td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan='5'>
                    <input type='text' id='address' value=\"$address\"></input>
                </td>
            </tr>
            <tr>
                <th>City</th>
                <td>
                    <input type='text' id='city' value=\"$city\"></input>
                </td>
                <th style='width:100px !important'>State</th>
                <td style='width:100px !important'>
                    <input type='text' id='state' style='width:90px !important' value=\"$state\"></input>
                </td>
                <th style='width:100px !important'>Zip</th>
                <td style='width:100px !important'>
                    <input type='text' id='zip' style='width:90px !important' value=\"$zip\"></input>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <input type='text' id='email' value=\"$email\"></input>
                </td>
                <th>Phone</th>
                <td colspan='3'>
                    <input type='text' id='phone' value=\"$phone\"></input>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <select id='account_status'>
                        <option value='Received'".($account_status == 'Received' ? " selected='selected'" : "").">Received</option>
                        <option value='Approved'".($account_status == 'Approved' ? " selected='selected'" : "").">Approved</option>
                        <option value='Pending'".($account_status == 'Pending' ? " selected='selected'" : "").">Pending</option>
                        <option value='Boarded'".($account_status == 'Boarded' ? " selected='selected'" : "").">Boarded</option>
                        <option value='Live'".($account_status == 'Live' ? " selected='selected'" : "").">Live</option>
                        <option value='Declined'".($account_status == 'Declined' ? " selected='selected'" : "").">Declined</option>
                        <option value='Closed'".($account_status == 'Closed' ? " selected='selected'" : "").">Closed</option>
                    </select>
                </td>
                <th class='midrow'>Merchant ID</th>
                <td class='midrow' colspan='3'>
                    <input type='text' id='merchant_id' value='$merchant_id'></input>
                </td>
            </tr>
            <tr>
                <th>Agent</th>
                <td>
                    <span>
                        <p id='agent_display'>".(empty($agentID) ? "(none)" : "$rep_name ($agentID)")."</p>
                        <input type='hidden' id='agent_selected' value=\"$rep_name\"></input>
                        <input type='hidden' id='agent_ID_selected' value='$agentID'></input>
                    </span>
                </td>
                <th>Agent Search</th>
                <td colspan='3'>
                    <input type='text' id='agent_search_field'></input>
                </td>
             </tr>
            </tr>
            <tr>          
                <th>Monthly Volume</th>
                <td>
                    <input type='text' id='monthly_volume' value='$monthly_volume'></input>
                </td>
                <th>Average Ticket</th>
                <td>
                    <input type='text' id='average_ticket' value='$avg_ticket'></input>
                </td>
                <th>Pre-fund</th>
                <td>
                    <input type='text' id='prefund' value='$prefund'></input>
                </td>
            </tr>
            <tr>
                <th>Pricing Type</th>
                <td>
                    <select id='pricing_type'>
                        <option value='0'".($pricing_type == '0' ? " selected='selected'" : "")."> Please Select</option>
                        <option value='1'".($pricing_type == '1' ? " selected='selected'" : "").">Interchange Plus</option>
                        <option value='2'".($pricing_type == '2' ? " selected='selected'" : "").">Tiered</option>
                    </select>
                </td>
                <th>Trans Fee</th>
                <td colspan='3'>
                    <input type='text' id='transaction_fee' value='$transaction_fee'></input>
                </td>
            </tr>
            <tr class='interchange_row'>
                <th>Percentage</th>
                <td>
                    <input type='text' id='percentage' value='$percent'></input>
                </td>
                <th></th>
                <td colspan='3'></td>
            </tr>
            <tr class='tiered_row'>
                <th>Debit</th>
                <td>
                    <input type='text' id='debit' value='$debit'></input>
                </td>
                <th>Qual</th>
                <td colspan='3'>
                    <input type='text' id='qual' value='$qual'></input>
                </td>
            </tr>
             <tr class='tiered_row'>
                <th>Mid Qual</th>
                <td>
                    <input type='text' id='midqual' value='$midqual'></input>
                </td>
                <th>Non Qual</th>
                <td>
                    <input type='text' id='nonqual' value='$nonqual'></input>
                </td>
            </tr>
            <tr>
                <th>Amex</th>
                <td>
                    <input type='checkbox' name='cardtype' id='amex'".($amex == '1' ? " checked='checked'" : "")."></input>
                </td>
                <th>Discover</th>
                <td colspan='3'>
                    <input type='checkbox' name='cardtype' id='discover'".($discover == '1' ? " checked='checked'" : "")."></input>
                </td>
           </tr>
           <tr>
               <th>Pin Debit</th>
               <td>
                    <input type='checkbox' name='cardtype' id='pin_debit'".($pin_debit == '1' ? " checked='checked'" : "")."></input>
               </td>
               <th class='pindebit_row'>Pin Trans Fee</th>
               <td class='pindebit_row' colspan='3'>
                  <input type='text' id='pin_debit_fee' value='$pin_debit_fee'></input>
               </td>
           </tr>
           <tr>
               <th>Monthly Min</th>
               <td>
                    <input type='text' id='monthly_min' value='$monthly_min'></input>
               </td>
               <th>Statement Fee</th>
               <td colspan='3'>
                   <input type='text' id='statement_fee' value='$statement_fee'></input>
               </td>
            </tr>
            <tr>
               <th>Hardware</th>
               <td>
                    <input type='text' id='hardware' value=\"$hardware\"></input>
               </td>
               <th>Platform</th>
               <td colspan='3'>
                    <select id='process_platform'>
                        <option value='0'".($process_platform == '0' ? " selected='selected'" : "").">Omaha</option>
                        <option value='1'".($process_platform == '1' ? " selected='selected'" : "").">Nashville</option>
                        <option value='2'".($process_platform == '2' ? " selected='selected'" : "").">CardNet (North)</option>
                        <option value='3'".($process_platform == '3' ? " selected='selected'" : "").">Buypass</option>
                        <option value='4'".($process_platform == '4' ? " selected='selected'" : "").">Global</option>
                    </select>
                </td>
           </tr>
            <tr>
               <th>Notes</th>
               <td colspan='5'>
                    <textarea id='notes' rows='4' cols='50'></textarea>
               </td>
            </tr>
        </table>
    </div>";
?>
               