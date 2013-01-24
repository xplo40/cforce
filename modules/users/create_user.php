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
        <link type="text/css" href="../css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="../css/styles.css"/>
        <link rel="shortcut icon" href="../images/favicon.ico" type='image/x-icon' />
        
        <script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="../js/jquery-ui-1.8.16.custom.min.js"></script> 
        <script>
            $(function() {
                $("#manage-profiles-dialog").dialog({
			autoOpen: false,
			height: 500,
			width: 350,
			modal: true,
			buttons: {
                            Ok: function() {
                                $( this ).dialog( "close" );
                            }
			}
		});

		$("#manage-profiles").button().click(function() {
                    $("#manage-profiles-dialog").dialog("open");
                });
                
                $("#search_btn").css({
                    width: 16,
                    height: 16
                }).click(function(){
                    var searchString = $("#search").val();
                    //searching for a mid
                    if($(this).length == 15){
                        $.ajax({
                            url: "mid_search.php?user=<?php echo $user; ?>&password=<?php echo $password; ?>",
                            type: "POST",
                            data: {
                                mid: searchString
                            },
                            success: function(data) {
                                $("#search-results").html("<h2 style='color: blue'>SUCCESS MID</h2>");
                            }
                        });
                    }
                    //searching for a profile
                    else if($(this).length == 6){
                        $.ajax({
                            url: "profile_search.php?user=<?php echo $user; ?>&password=<?php echo $password; ?>",
                            type: "POST",
                            data: {
                                profile: searchString
                            },
                            success: function(data) {
                                $("#search-results").html("<h2 style='color: blue'>SUCCESS PROFILE</h2>");
                            }
                        });
                    }
                    //invalid search
                    else{
                        $("#search-results").html("<h2 style='color: red'>Please enter a Profile or MID</h2>");
                    }
                });
                
                $("#wrapper").css("display", "block");
            });
        </script>
    </head>
    <body>
      <div id="wrapper">
            <div id="header">
                <div id="cmv-logo">               
                    <img src="../images/cmvlogo.png"></img>
                </div>
                <div id="contact-info" >
                    <strong style="font-size:larger;">Forgot Password? Need Help? Please call us!</strong>
                    <div>
                        <strong>Local Phone 1-801-623-4000</strong>
                        <br/><strong>Toll Free Phone 1-877-267-4324</strong>
                        <br/><strong>CustomerService@cmsonline.com</strong>
                    </div>
                </div>
                <div id="top-bar">
                    <img id="fade-bar" src="../images/header_fade.png"></img>
                    <div id="solid-bar-container">
                        <div id="solid-bar">
                            <a id="help_modal_btn" href="#">Help</a>
                            <p id="product">Complete Merchant Solutions presents <strong style="color:white">Complete Merchant View</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="page-content">
                <div id="manage-profiles-dialog" title="Manage users">
                    <h2>
                        Enter a MID or Profile
                        <input id="search" type="text"/>
                        <span id='search_btn' class='ui-icon-circle-triangle-e'></span>
                    </h2>
                    <div id="search-results"></div>
                </div>
                <button id="manage-profiles">Create new user</button>
            <div class="push"></div>
        </div>
        <div id="copywrite">
            <div id="cms-logo">               
                <a href="http://www.cmsonline.com">
                    <img src="../images/cmslogo.png"></img>
                </a>
            </div>
            <p>&#169; 2012 Complete Merchant Solutions LLC, All Rights Reserved</p>
            <p>Complete Merchant Solutions is a Registered ISO and MSP of HSBC Bank USA, National Association, Buffalo, NY and NATIONAL BANK of California, Los Angeles, CA.</p>
            <p>American Express requires separate approval. All trademarks, service marks, and trade names that are referenced in this material are the property of their respective owners.</p>
        </div>
    </body>
</html>
