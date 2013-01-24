<?php
    /* INITIALIZE PAGE
     * 
     * includes sha encryption
     * forces https
     * connect to MySQL database
     * 
     */
    function initializePage(){
        //force https
        if ($_SERVER['HTTPS'] != "on") { 
            $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; 
            header("Location: $url"); 
            exit; 
        }

        //javascript
        $root = $_SERVER['HTTP_HOST']."/cforce";
        echo "<link type='text/css' href='https://$root/css/custom-theme/jquery-ui-1.9.2.custom.css' rel='stylesheet' />
            <link rel='stylesheet' type='text/css' href='https://$root/css/styles.css'/>
            <link rel='shortcut icon' href='images/favicon.ico' type='image/x-icon' />
            <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'></script>
            <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js'></script>
            <script type='text/javascript' src='https://$root/include/HighCharts/highcharts.js'></script>
            <script type='text/javascript' src='https://$root/include/HighCharts/modules/exporting.js'></script>
            <script type='text/javascript'>
                /*var _gaq = _gaq || [];
                (function() {
                    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                })();*/
            </script>";
    }
        
    
    
    /* CONNECT TO MYSQL DATABASE
     * 
     * Requires debug mode and database name
     * Returns mysqli link object
     * 
     */
    function connect_to_mysqli_database($debug){
        $server = 'db.cmsonline.com';
        $username = 'cforce';
        $password = 'XVyNcSPa6x9LWf9y';

        //connect to the database
        $mysqli = new mysqli($server, $username, $password);
        return $mysqli;
    }
    
    
    
    function connect_to_mysql_database($debug){
        global $mysqldb;
        //This is the live MYSQL database
        $server = 'db.cmsonline.com';
        $username = 'cforce';
        $password = 'XVyNcSPa6x9LWf9y';
        if($debug) {
            $mysqldb = 'CMC';
        }
        else{ 
            $mysqldb = 'CMC';
        }

        //connect to the database
        $dblink = mysql_pconnect($server, $username, $password)or die(mysql_error());
        mysql_select_db($mysqldb)or die(mysql_error());
        return $dblink;
    }
    
    
    
    /* SANITIZE INPUTS
     * 
     * Returns false on large or empty strings
     * Returns the given string after stripping tags and running mysqli_real_escape_string
     * 
     */
    function sanitize($string){
        global $mysqli, $dbconnect;
        
        if (strlen($string) > 5000){
            return false;
        }
        else if(empty($string)){
            return false;
        }
        else{
            $string = strip_tags($string);
            if(true){
                $string = mysql_real_escape_string($string);
            }
            else{
                $string = mysqli_real_escape_string($mysqli, $string);
            }
            return $string;
        }
    }
    
    
    /* CREATE SALT
     * 
     * Returns a 10 character random salt 
     * 
     */
    function createSalt(){
        $string = md5(uniqid(rand(), true));
        return substr($string, 0, 10);
    }
    
    
    
    /* VALIDATE USER
     * 
     * Requires sanitized username and password
     * Returns true or error
     * 
     */
    function validateUser($user, $input_password){
        include "include/SHA2/sha2.php";
        
        if($user && $input_password){
            //connect to database and check connection 
            $mysqli = connect_to_mysqli_database(false);
            if(mysqli_connect_errno()){
                //bad connection
                $return = "Website databases are down";
            }
            else{
                //query user table
                $query = "SELECT ID
                        ,Name
                        ,Password
                        ,Salt
                        ,Department
                        ,Email
                        ,AgentID
                    FROM CMC.CMC_Users
                    WHERE User = ?";
                $stmt = mysqli_stmt_init($mysqli);
                if(mysqli_stmt_prepare($stmt, $query)){
                    mysqli_stmt_bind_param($stmt, 's', $user);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $name, $stored_password, $salt, $department, $email, $agent);
                    if(mysqli_stmt_fetch($stmt)){
                        //check for bad credentials
                        $input_hash = sha256(sha256($input_password).$salt);
                        if($input_hash != $stored_password){
                            //bad password
                            $return = "That password is incorrect";
                        }
                        else{
                            session_regenerate_id();
                            $_SESSION['valid'] = 1;
                            $_SESSION['user'] = $id;
                            $_SESSION['agent'] = $agent;
                            $_SESSION['name'] = $name;
                            $_SESSION['department'] = $department;
                            $_SESSION['email'] = $email;
                            $return = "";
                        }
                    }
                    else{
                        //user doesn't exist
                        $return = "That user does not exist";
                    }
                }
                else{
                    //invalid inputs - breaks query
                    $return = "Invalid credentials";
                }
                mysqli_stmt_close($stmt);
            }
        }
        else{
            //user and/or password not valid
            $return = "Please enter a valid username and password";
        }
        
        return $return;
    }
    
    
    
    /* LOGOUT
     * 
     * clears the $_SESSION array variables and destroys the php session
     * 
     */
    function logout(){
        $_SESSION = array(); //destroy all of the session variables
        session_destroy();
    }
    
    
    
    /* LEGACY VALIDATE USER 
     * 
     * Requires username and sha256 encrypted password
     * Use sanitize inputs on both username and password before calling this method
     * Returns true or false
     * 
     */
    function validateUser1($user, $password){
        global $dbconnect, $user_department_id;
        $dbconnect = connect_to_mysql_database(false);
        
        //check for empty credentials
        if(!$user || !$password){ 
            return false;
        }
         
        //use mysql or mysqli
        if($dbconnect){
            //query user table
            $query = "SELECT Password
                        ,Department
                    FROM CMC.CMC_Users 
                    WHERE User = '$user'"; 

            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            $password2 = $row['Password'];
            $user_department_id = $row['Department'];

            //check for bad credentials
            if($password != $password2){
                 //echo "BAD CREDENTIALS<br/>";
                return false;
            }else{
                return true;
            }
        }
        else{
        /*MYSQLI DISABLED FOR SPEED
         *
        //query user table
        $query = "SELECT Password
                    ,Department
                FROM CMC.CMC_Users 
                WHERE User = ?"; 

         $stmt = mysqli_stmt_init($mysqli);
        if(mysqli_stmt_prepare($stmt, $query)){
            mysqli_stmt_bind_param($stmt, 's', $user);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $password2, $user_department_id);
            mysqli_stmt_fetch($stmt);

            //check for bad credentials
            if($password != $password2){
                 //echo "BAD CREDENTIALS<br/>";
                return false;
            }else{
                return true;
            }
            mysqli_stmt_close($stmt);
        }
        //echo "NO CONNECTION<br/>";
        return false;*/
        }
    }
    
    ########################################
    ########## Create XFDF Form
    ########################################
    // Takes values passed via associative array and generates XFDF file format
    // with that data for the pdf address suplied.

    // $file: The pdf file - url or file path accepted
    // $info: array of data to use in key/value pairs no more than 2 dimensions
    // $enc: default UTF-8, match server output: default_charset in php.ini
    // return: string The XFDF data for acrobat reader to use in the pdf form file

    function createXFDF($file,$info,$enc='UTF-8'){
        $data='<?xml version="1.0" encoding="'.$enc.'"?>'."\n".
            '<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">'."\n".
            '<fields>'."\n";
        foreach($info as $field => $val){
            $data.='<field name="'.$field.'">'."\n";
            if(is_array($val)){
                foreach($val as $opt)
                    $data.='<value>'.htmlentities($opt).'</value>'."\n";
            }else{
                $data.='<value>'.htmlentities($val).'</value>'."\n";
            }
            $data.='</field>'."\n";
        }
        $data.='</fields>'."\n".
            '<ids original="'.md5($file).'" modified="'.time().'" />'."\n".
            '<f href="'.$file.'" />'."\n".
            '</xfdf>'."\n";
        return $data;
    }
    
?>
