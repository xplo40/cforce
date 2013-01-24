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
    $agent = sanitize($_GET['agent']);

    $query = "SELECT *
            From CMC.CMC_Agents
            WHERE ID = '$agent'";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    
    $name = $row['Name'];
    $company = $row['Company'];
    $repcodelow = $row['AgentId1'];
    $repcodehigh = $row['AgentId2'];
    $commlow = $row['CommissionLow'];
    $commhigh = $row['CommissionHigh'];
    $phone = $row['PhoneNumber'];
    $email = $row['Email'];
    $address=$row['Address'];
    $city=$row['City'];
    $state=$row['State'];
    $zip=$row['ZipCode'];
    $fullaccess=$row['IsFullAccess'];
    $commissionlow=$row['CommissionLow'];
    $commissionhigh=$row['CommissionHigh'];
    

    echo "<table id='agent_detail_table'>
            <tbody>
                <tr>
                    <th style='width:100px'>Agent Name</th>
                    <td style='width:350px'>$name</td>
                    <th style='width:100px'>Commission</th>
                    <td style='width:350px'>$commlow</td>
                </tr>
                <tr>
                    <th>ID</th>
                    <td>$agent</td>
                    <th></th>
                    <td>$commhigh</td>
                </tr>
                <tr>
                    <th>Rep Codes</th>
                    <td>$repcodelow</td>
                    <th>Email</th>
                    <td>$email</td>
                </tr>
                <tr>
                    <th></th>
                    <td>$repcodehigh</td>
                    <th>Phone</th>
                    <td>$phone</td>
                </tr>
            </tbody>
        </table>";
    
    echo "<div id='edit_agent_modal' title='Edit Agent'> 
    <table>
        <tr>
            <th>Name</th>
            <td colspan='5'>
                <input type='text' id='name' value='$name'/>
            </td>
        </tr>
        <tr>
            <th>Company</th>
            <td colspan='5'>
                <input type='text' id='company' value='$company'/>
            </td>
        </tr>
        <tr>
            <th>Address</th>
            <td colspan='5'>
                <input type='text' id='address' value='$address'/>
            </td>
        </tr>
        <tr>
            <th style='width: 120px'>City</th>
            <td style='width: 175px'>
                <input type='text' id='city' value='$city'/>
            </td>
            <th style='width: 120px'>State</th>
            <td style='width: 40px'>
                <input type='text' id='state' value='$state'/>
            </td>
            <th style='width: 120px'>Zip</th>
            <td style='width: 100px'>
                <input type='text' id='zip' value='$zip'/>
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td colspan='5'>
                <input type='text' id='email' value='$email'/>
            </td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>
                <input type='text' id='phone' value='$phone'/>
            </td>
            <th>Access</th>
            <td colspan='3'>
                <select id='access'>
                    <option value='0'".($fullaccess == '0' ? "selected='selected'" : "" ).">Basic Agent</option>
                    <option value='1'".($fullaccess == '1' ? "selected='selected'" : "" ).">Full Access Agent</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>Rep Code Low</th>
            <td>
                <input type='text' id='repcode_low' value='$repcodelow'/>
            </td>
            <th>Rep Code High</th>
            <td colspan='3'>
                <input type='text' id='repcode_high' value='$repcodehigh'/>
            </td>
        </tr>
        <tr>
            <th>Com % Low</th>
            <td>
                <input type='text' id='com_low' value='$commissionlow'/>
            </td>
            <th>Com % High</th>
            <td colspan='3'>
                <input type='text' id='com_high' value='$commissionhigh'/>
            </td>
        </tr>
    </table>
</div>";
?>