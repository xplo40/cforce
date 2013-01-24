<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        header('Location: index.php?e=0');
        die();
    }

    include "include/common.php";
    
    initializePage();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Complete Portal</title>
        <script src="include/jQueryCookie/jquery.cookie.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                //initialze tabs
                $("#tabs").tabs({ 
                    cookie: { expires: 30 },
                    cache: true,
                    activate: function(event, ui){
                       
                    }
                });
                
                //loading icon
                $("#tabs .ui-tabs-panel:not(#tabs-processing)").append("<img src='images/cms_loading.gif' class='tab_spinner'/>");
                
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
                $("#help_modal_btn2").click(function(){
                   /* _gaq.push(['_setAccount', 'UA-31861703-1']);
                    _gaq.push(['_trackEvent', 'Button', 'CMV Help']);*/
                    $("#help_modal").dialog( "open" );
                });
                
                //clear feedback default text on click
                $("#feedback_ti").click(function(){
                   if($("#feedback_ti").text() == "Enter your feedback here..."){
                       $("#feedback_ti").text("").css("color", "#000");
                   }
                });
               
                //initialize feedback submit button
                $("#submit_feedback").button()
                    .css({
                        padding:"0px",
                        marginTop: "10px",
                        textMozBorderRadius:"0px",
                        borderRadius:"0px"
                    })
                    .click(function(){
                       // _gaq.push(['_setAccount', 'UA-31861703-1']);
                       // _gaq.push(['_trackEvent', 'Button', 'Feedback']);
                        $.ajax({
                            url: "include/submit_feedback.php",
                            type: "POST",
                            data: {
                                feedback : $("#feedback_ti").val()
                            },
                            success: function(data) {
                                $("#feedback_ti").val("");
                                $("#feedback_message").text(data);
                            }
                        });
                    })
                    .addClass("button2");
                
                //remove title tooltips
                $('[title]').each(function() {
                    $.data(this, 'title', $(this).attr('title'));
                    $(this).removeAttr('title');
                });
                
                //show content
                $("#wrapper").css("display", "block");
                
                $('#to_top').click(function() {
                    $("body").scrollTop(0);
                });
            });
        </script>
    </head>
    <body>
        <div id="wrapper" class="with-footer">
            <div id="header">
                 <div id="header_container" cellpadding="0" cellspacing="0">
                     <div id="logo"></div>
                     <p>Welcome, <?php echo $_SESSION['name']; ?></p>
                     <a id="help_modal_btn2" href="#">Help</a>
                     <a id="logout_btn" href="index.php?lo=1">Logout</a>
                 </div>
            </div> 
            <div id="container">
                <div id="bar1"></div>
                <div id="bar2"></div>
                <div id="tabs">
                    <ul>
                        <li style="z-index:8">
                            <a id="tab0" class="tab_title" title="0" href="modules/home/home.php"><p>Home</p></a>
                        </li>
    <?php if((int)$_SESSION['department'] >= 10): ?>
                        <li style="z-index:7">
                            <a id="tab1" class="tab_title" title="1" href="modules/agents/agents.php"><p>Agents</p></a>
                        </li>
    <?php endif; if($_SESSION['department'] == "100"): ?>
                        <li style="z-index:7">
                            <a id="tab1" class="tab_title" title="1" href="modules/users/users.php"><p>Users</p></a>
                        </li>
    <?php endif; ?>
                        <li style="z-index:6">
                            <a id="tab2" class="tab_title" title="2" href="modules/merchants/merchants.php"><p>Merchants</p></a>
                        </li>
                        <li style="z-index:5">
                            <a id="tab3" class="tab_title" title="3" href="modules/processing/processing.php"><p>Processing</p></a>
                        </li>
                        <li style="z-index:3">
                            <a id="tab4" class="tab_title" title="4" href="modules/documents/documents.php"><p>Documents</p></a>
                        </li>
                        <li style="z-index:2">
                            <a id="tab5" class="tab_title" title="5" href="modules/tools/tools.php"><p>Tools</p></a>
                        </li>
                    </ul>
                  
                    </div>
                </div>

                <div id="feedback">
                  <h3>FEEDBACK</h3>
                  <p id="feedback_subtitle">Since this is a Beta version, we would love to hear your suggestions to make C Force better:</p>
                  <textarea id="feedback_ti" cols="40" rows="5">Enter your feedback here...</textarea>
                  <p id="feedback_message" class="error"></p>
                  <button id="submit_feedback">Submit</button>  
                </div>
            </div>
            <div class="push with-footer"></div>
            <div id="background_tl" class="background_img_top"></div>
            <div id="background_tr" class="background_img_top"></div>
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
        <!--<div id="background_bl" class="background_img_bottom"></div>
        <div id="background_br" class="background_img_bottom"></div>-->
        <div id="bar3"></div>
        <div id="bar4"></div>
        <div id='help_modal'></div>
    </body>
</html>