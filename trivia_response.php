 <?php

$host="db.cmsonline.com"; // Host name
$username="trivia"; // Mysql username`
$password="1qaz!QAZ"; // Mysql password
$db_name="Trivia"; // Database name
$tbl_name="Marquee_Responses"; // Table name

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect");
mysql_select_db("$db_name")or die("cannot select DB");

// Get values from form
$first_name=mysql_real_escape_string($_POST['first_name']);
$last_name=mysql_real_escape_string($_POST['last_name']);
$email=mysql_real_escape_string($_POST['email']);
$phone=mysql_real_escape_string($_POST['phone']);
$response=mysql_real_escape_string($_POST['response']);


// Insert data into mysql

$query="INSERT INTO Marquee_Responses (First_Name, Last_Name, Phone_Number, Email, Question_Response) VALUES('$first_name', '$last_name',  '$phone', '$email', '$response')";
$result=mysql_query($query) or die ('error Updating database');

// if successfully insert data into database, displays message "Successful".
if($result){
echo "Successful";

}

else {
echo "ERROR, please try again";
}

// close connection
mysql_close();
?>