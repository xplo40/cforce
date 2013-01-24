 <?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
?>
        
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head>
        <title>Complete Merchant View</title>
        <link type="text/css" href="css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="css/styles.css"/>
        <link rel="shortcut icon" href="images/favicon.ico" type='image/x-icon' />
        
        <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script> 
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-31861703-1']);
            _gaq.push(['_trackPageview', 'Change_Password']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
        <script>
            $(document).ready(function(){
                var passwordRegex = /^.*(?=.{8,})(?=.*\d)(?=.*[a-zA-Z]).*$/;
                
                $("#submit_email_btn").button()
                    .css({
                        marginLeft: "80px",
                        padding:"0px",
                        textMozBorderRadius:"0px",
                        borderRadius:"0px"
                    }).click(function(){
                        _gaq.push(['_setAccount', 'UA-31861703-1']);
                        _gaq.push(['_trackEvent', 'Button', 'Submit Email']);
                        $("#form").submit();
                    });
                    
                $("#submit_password_btn").button()
                    .css({
                        marginLeft: "80px",
                        padding:"0px",
                        textMozBorderRadius:"0px",
                        borderRadius:"0px"
                    }).click(function(event){
                        _gaq.push(['_setAccount', 'UA-31861703-1']);
                        _gaq.push(['_trackEvent', 'Button', 'Submit Password']);
                        if($("#password1").val() != $("#password2").val()){
                            $("#error-msg").text("Both passwords must be the same.");
                            event.preventDefault();
                        }
                        else if(!$("#password1").val().match(passwordRegex)){
                            $("#error-msg").text("The password must contain at least one letter and one number and be at least 8 characters long.");
                            event.preventDefault();
                        }
                        else{
                            $("#form").submit();
                        }
                    });
                    
                    
                //help modal
                $("#help_modal").dialog({
                    autoOpen: false,
                    height: 'auto',
                    width: 800,
                    modal: true,
                    buttons: {
                        Close: function() {
                            $(this).dialog("close");
                        }
                    }
                });
                
                //listen for help modal clicks
                $("#help_modal_btn").click(function(){
                    $("#help_modal").dialog( "open" );
                    _gaq.push(['_setAccount', 'UA-31861703-1']);
                    _gaq.push(['_trackEvent', 'Button', 'Change Password Help']);
                });
                    
                //show content
                $("#wrapper").css("display", "block");
            });
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
                <div id="cmv-logo">               
                    <img src="images/cmvlogo.png"></img>
                </div>
                <div id="contact-info">
                    <strong style="font-size:larger;">Please call for assistance</strong>
                    <div>
                        <strong>Local Phone 1-801-623-4000</strong>
                        <br/><strong>Toll Free Phone 1-877-267-4324</strong>
                        <br/><strong>CustomerService@cmsonline.com</strong>
                    </div>
                </div>
                <div id="top-bar">
                    <img id="fade-bar" src="images/header_fade.png"></img>
                    <div id="solid-bar-container">
                        <div id="solid-bar">
                            <a id="help_modal_btn" href="#">Help</a>
                            <p id="product">Complete Merchant Solutions presents <strong style="color:white">Complete Merchant View</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="content">
                <div id="intro-text">
                    <h2>Welcome to Complete Merchant View!</h2>
                    <p>CMV provides you direct online access to your processing history through an easily navigated system. Built in analytics provides you quick visibility to your processing trends. You will be able to access every transactional record, retrieval, chargeback, and all other activity that generates your daily ACH to your bank account. This reporting makes daily, weekly, and monthly reconciling a more efficient process. </p>
                    <br/>
                    <p></p>
                </div>
                <div id="login-line"></div>
            <div id = "login">
                <form id="form" action="change_password.php" method="POST">
                        <table cellpadding="5" cellspacing="0">

<?php
    
    //email URL clicked -> create new password
    if(!empty($_GET['cpc'])):
        $cpc = $_GET['cpc'];
        $query = "SELECT ChangePasswordCode
                ,CPCExpiration
            FROM CRM.dbo.CMV_profiles
            WHERE Profile = '$user'"; 
        $result = mssql_query($query);
        $row = mssql_fetch_assoc($result);
        if($row['ChangePasswordCode'] != $cpc || strtotime($row['CPCExpiration']) < strtotime(date('n/d/y g:i:s a'))){
            setcookie("user", "", time()-3600);
            setcookie("password", "", time()-3600);
            header("Location: index.php");
        }
?>
                            
                            <tr>
                                <td colspan="2">
                                    <h2>New Password</h2>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3>Please create a new password with at least:</h3>
                                    <ul>
                                        <li>8 characters</li>
                                        <li>1 letter</li>
                                        <li>1 number</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td><input type="password" id="password1" name="password1"/></td>
                            </tr>
                            <tr>
                                <th>Confirm Password</th>
                                <td><input type="password" id="password2" name="password2"/></td>
                            </tr>
                            <tr>
                                <th><input type="hidden" id="cpc" name="cpc" value="<?php echo $cpc;?>"/></th>
                                <td>
                                    <button id="submit_password_btn" class="button2">Submit</button>
                                </td>
                            </tr>   
                            
<?php

    
    //entered email -> send email
    elseif(!empty($_POST["email"])):
        require_once("helpers/ses.php");
        $ses = new SimpleEmailService('AKIAIPMNPKDPNCI2HI2A', 'HFzqAQGCOQj6R6Y9ogJb27tMVHXSrJjQiCaAKBPs');
        
        $email = $_POST["email"];
        $dda = $_POST["dda"];
        
        //verify identity with dda
        $query = "SELECT DDA
                FROM CRM.dbo.CMV_profiles
                WHERE Profile = '$user'"; 
        $result = mssql_query($query);
        $row = mssql_fetch_assoc($result);

        if($dda != $row['DDA']){
            echo "
            <tr>
                <td colspan='2'>
                    <h3 class='error'>Sorry, that bank account number is incorrect. Please try again.</h3>
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <h3>Please enter a valid email address. You will receive an email with a link to change your password.</h3>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type='text' id='email' name='email'/></td>
            </tr>
            <tr>
                <th>Last four digits of your bank account number</th>
                <td><input type='text' id='dda' name='dda'/></td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <button id='submit_email_btn' class='button2'>Submit</button>
                </td>
            </tr>";
        }
        else{
            $cpc = md5("change password code $user $password");
            $expiration = date('n/d/y g:i:s a', strtotime(date('n/d/y g:i:s a')." +2 minutes"));
            $query = "UPDATE CRM.dbo.CMV_profiles
                    SET ChangePasswordCode = '$cpc', CPCExpiration = '$expiration', Email = '$email', DateUpdated = GETDATE()
                    WHERE Profile = '$user'"; 
            $result = mssql_query($query);
         
            //send email
            $message = "<h3>Welcome to Complete Merchant View</h3>
                <p>Please click the link below to set your password:</p>
                <br/>
                <a href='https://cmv.cmsonline.com/change_password.php?cpc=$cpc'>SET PASSWORD</a>
                <br/>
                <p>This url will expire in 24 hours.</p>";

            $m = new SimpleEmailServiceMessage();
            $m->addTo($email);
            $m->setFrom('admin@cmsonline.com');
            $m->setSubject('Complete Merchant View Login');
            $m->setMessageFromString(null, $message);

            $response = $ses->sendEmail($m);
            if($response){
                echo "<tr>
                        <td>
                            <h3>An email has been sent to $email with instructions to log in.</h3>
                        </td>
                    </tr> ";
            }
            else{
                "<tr>
                        <td>
                            <h3>There was an error sending your email. Please try again later.</h3>
                        </td>
                    </tr> ";
            } 
        }
?>
     
                            
                            
<?php
    //enter email
    else:
?>
                            <tr>
                                <td colspan="2">
                                    <h2>First Login</h2>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3>Please enter a valid email address. You will receive an email with a link to change your password.</h3>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><input type="text" id="email" name="email"/></td>
                            </tr>
                            <tr>
                                <th>Last four digits of your bank account number</th>
                                <td><input type="text" id="dda" name="dda"/></td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <button id="submit_email_btn" class="button2">Submit</button>
                                </td>
                            </tr>
     
<?php   
    endif; 
?>

                        </table>
                    <br/>
                    <p id="error-msg" class="error"><?php echo $error;?></p>
                </form>
            </div>
            </div>
            <div class="push"></div>
        </div>
        <div id="copywrite">
            <div id="cms-logo">               
                <a href="http://www.cmsonline.com">
                    <img src="images/cmslogo.png"></img>
                </a>
            </div>
            <p>&#169; 2012 Complete Merchant Solutions LLC, All Rights Reserved</p>
            <p>Complete Merchant Solutions is a Registered ISO and MSP of HSBC Bank USA, National Association, Buffalo, NY and NATIONAL BANK of California, Los Angeles, CA.</p>
            <p>American Express requires separate approval. All trademarks, service marks, and trade names that are referenced in this material are the property of their respective owners.</p>
        </div>
    </body>
</html>