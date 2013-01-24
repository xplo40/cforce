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
    
    $query = "SELECT Name
		,note_types.Type
		,Notes
		,notes.DateAdded 
	FROM(
		SELECT Creator
				,Type
				,Notes
				,DateAdded
			FROM CMC.CMC_Merchant_Notes 
			WHERE MerchantID = '$id'
	) AS notes
	LEFT JOIN CMC.CMC_Users AS users
		ON notes.Creator = users.ID
	LEFT JOIN CMC.CMC_Merchant_Note_Types AS note_types
		ON notes.Type = note_types.ID
	ORDER BY DateAdded DESC";
    $result = mysql_query($query);

    $output = "<table id='merchant_notes_table' class='data_summary'>
            <tbody>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Creator</th>
                    <th>Notes</th>
                </tr>";
                
    while($row = mysql_fetch_assoc($result)){
        $date = date('n/d/y g:i:s a', strtotime($row['DateAdded']));
         $output .= "<tr><td>$date</td><td>".$row['Type']."</td><td>".$row['Name']."</td><td>".$row['Notes']."</td></tr>";
    }
    
    $output .= "</tbody></table>";
    echo $output;
?>
               