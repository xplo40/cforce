<?php  
    session_start(); 

    include "include/common.php";
    
    //logout
    if(isset($_GET['lo']) && $_GET['lo'] == '1'){
        logout();
    }
    
    //errors
    if(isset($_GET['e'])){
        if($_GET['e'] == '0'){
            $error = "Please log in first";
        }
    }
    
    //grab and sanitize inputs
    if(isset($_POST['user']) && isset($_POST['password'])){
        $user = sanitize($_POST['user']);
        $password = sanitize($_POST['password']);

        //validate inputs
        $validate = validateUser($user, $password);
        session_write_close();
        if($validate == ""){
            header("Location: portal.php");
        }
        else{
            $error = $validate;
        }
    }
    session_write_close();
    
    initializePage();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Complete Portal</title>
        <script>
            $(document).ready(function(){
                //selectors
                var login_button = $("#login_button");
                var login_form = $("#login_form");
                var password_label = $("#password_label");
                var new_password1 = $("#new_password1");
                var new_password2 = $("#new_password2");
                var error = $("#error");
                var change_password = $("#change_password");
                var login_button_row = $("#login_button_row");
                var help_modal = $("#help_modal");
                var help_modal_btn = $("#help_modal_btn");
                var wrapper = $("#wrapper");
                var content = $("#content");
                
               /* _gaq.push(
                    ['_setAccount', 'UA-31861703-1']
                    ,['_trackPageview', 'Login']
                );*/
                
                var passwordRegex = /^.*(?=.{8,})(?=.*\d)(?=.*[a-zA-Z]).*$/;
                
                login_button.button()
                    .css({
                        padding:"0px",
                        textMozBorderRadius:"0px",
                        borderRadius:"0px"
                    }).click(function(){
                        //validate change password vars
                        if(new_password1.val() != '' || new_password2.val() != '' ){
                            if(new_password1.val() != new_password2.val()){
                               // _gaq.push(['_setAccount', 'UA-31861703-1']);
                               // _gaq.push(['_trackEvent', 'Button', 'Change Password', 'Passwords were not the same.']);
                                error.text("Both passwords must be the same.");
                                event.preventDefault();
                            }
                            else if(!new_password1.val().match(passwordRegex)){
                                //_gaq.push(['_setAccount', 'UA-31861703-1']);
                                //_gaq.push(['_trackEvent', 'Button', 'Change Password', 'Password did not meet regex requirements.']);
                                error.text("The password must contain at least one letter and one number and be at least 8 characters long.");
                                event.preventDefault();
                            }
                            else{
                              //  _gaq.push(['_setAccount', 'UA-31861703-1']);
                              //  _gaq.push(['_trackEvent', 'Button', 'Change Password', 'Success!']);
                                login_form.submit();
                            }
                        }
                        else{
                           // _gaq.push(['_setAccount', 'UA-31861703-1']);
                           // _gaq.push(['_trackEvent', 'Button', 'Login']);
                            login_form.submit();
                        }
                        return false;
                    });
                    
                change_password.button()
                    .toggle(function(){
                            password_label.text("Old Password");
                            content.css("height", "400px");
                            var insert = 
                                "<tr class='new_password_row'>" +
                                    "<th>New Password</th>" + 
                                "</tr><tr class='new_password_row'>" +
                                    "<td><input type='password' id='new_password1' name='new_password1'/></td>" + 
                                "</tr><tr class='new_password_row'>" +
                                    "<th>Confirm Password</th>" +
                                "</tr><tr class='new_password_row'>" +
                                    "<td><input type='password' id='new_password2' name='new_password2'/></td>" + 
                                "</tr>";
                            login_button_row.before(insert);
                        },
                        function(){
                            $(".new_password_row").remove();
                            content.css("height", "300px");
                            login_button_row.prev("tr").children("th").text("Password");
                        }
                    );
                
                //help modal
                help_modal.dialog({
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
                help_modal_btn.click(function(){
                   /* _gaq.push(['_setAccount', 'UA-31861703-1']);
                    _gaq.push(['_trackEvent', 'Button', 'Login Help']);
                    $("#help_modal").dialog( "open" );*/
                });
                
                //show content
                wrapper.css("display", "block");
            });
        </script>
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
                 <div id="header_container" cellpadding="0" cellspacing="0">
                     <div id="logo"></div>
                     <p>Complete Merchant Solutions</p>
                     <a id="help_modal_btn2" href="#">Help</a>
                 </div>
            </div> 
            <div id="bar1"></div>
            <div id="bar2"></div>
            <div id="content">
                <div id = "login">
                    <form id="login_form" action="index.php" method="POST">
                            <table>
                                <tr>
                                    <th>User ID</th>
                                </tr>
                                <tr>
                                    <td><input type="text" id="user" name="user"/></td>
                                </tr>
                                <tr>
                                    <th id="password_label">Password</th>
                                </tr>
                                <tr>
                                    <td><input type="password" id="password" name="password"/></td>
                                </tr>
                                <tr id="login_button_row">
                                    <td>  
                                        <button id="login_button" class="button2">Login</button>
                                        <button id="change_password" name="change_password">Change Password</button>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <p id="error" class="error"><?php echo $error;?></p>
                    </form>
                </div>
                <div id="intro_text">
                    <h1>Welcome to C Force!</h1>
                    <p>C Force provides agents with direct online access to their merchant records through an easily navigated system. Built in analytics provides you quick visibility to your merchants' processing trends.</p>
                </div>
                <div id="login_line"></div>
            </div>
            <div class="push"></div>
        </div>
        <div id="footer">
            <a id="cms_logo" href="http://www.cmsonline.com">
                <img src="images/CMSLogo_white.png"></img>
            </a>
            <div id="contact_info" >
                <div>
                    <span class="contact_2">Forgot Password? Need Help? Please call us!</span>
                    <span class="contact_1">CUSTOMER SERVICE</span>
                    <p class="contact_3">801.623.4000 / 877.267.4324</p>
                    <p class="contact_4">customerservice@cmsonline.com</p>
                </div>
            </div>
            <div id="copywrite">
                <p class="copywrite_1">&#169; 2013 Complete Merchant Solutions LLC, All Rights Reserved</p>
                <p class="copywrite_2">Complete Merchant Solutions is a Registered ISO and MSP of HSBC Bank USA, National Association,</p>
                <p class="copywrite_2">Buffalo, NY and NATIONAL BANK of California, Los Angeles, CA. American Express requires separate approval.</p>
                <p class="copywrite_2">All trademarks, service marks, and trade names that are referenced in this material are the property of their respective owners.</p>
            </div>
        </div>
        <div id="bar3"></div>
        <div id="bar4"></div>
        <div id='help_modal'>
            <h2>Login Help</h2>
            <br/>
            <p>During the initial login, using your User ID and temporary password you will be asked to provide a valid email address as well as validate your account by using the last four numbers of the bank account that is associated with your merchant account. If you are unsure of the bank account associated you may call customer support at (801-623-4000) and this can be provided to you. Next, you will receive an email to the address you provided and there will be a hyperlink that you will either click or copy and paste into a browser and this will allow you to reset your password. Please call if you need any assistance with the initial login or resetting a forgotten password.</p>
        </div>
    </body>
</html>