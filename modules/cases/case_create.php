<?php

    //check for login
    session_start();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }

 
    include "../../include/common.php";
    initializePage();
    
    // USER CREDENTIALS VALIDATION 
    $user = sanitize($_GET['user']);
 
   // $department_id = "3";
    $department_id= $_SESSION['department'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>CForce Case View</title>
        <script type="text/javascript">
            $(document).ready(function(){
                 var term_id = 0;
                var term = "";
                 var index = 0;
                $.ajax({
                    url: '/modules/cases/assign_to_list.php',
                     success: function(data){
                         $("#assign_to_list").after(data);
                     }
                });
               
                    
             
               $("#case_merchant_search").autocomplete({
                    minLength: 2,
                    source: 'modules/cases/case_merchant_select.php',
                    focus: function(event, ui) {
                        $("#case_merchant_search").val(ui.item.label);
                        return false;
                    },
                    select: function(event, ui) {
                        $("#case_merchant_selected").text(ui.item.label);
                        $("#case_mid_selected").text(ui.item.value);
                      
                        return false;
                    }
		})
		.data("autocomplete")._renderItem = function(ul, item) {
                    return $("<li></li>")
                        .data("item.autocomplete", item)
                        .append("<a><p>" + item.label + "</p><p>" + item.value + "</p></a>")
                        .appendTo(ul);
		};
/*
 $("#merchant_search").keyup( function() {
            term = $(this).val();
            if(term.length > 2){
                index = 0;
                term_id++;
                loadMerchantListing(term, index, term_id);
            }
        });
        $("#merchant_search").on('paste', function(event) { 
            term = $(this).val();
            if(term.length > 2){
               index = 0;
               term_id++;
               loadMerchantListing(term, index, term_id);
            }
        });
        
            
        //load more after scrolling to the bottom
        var scroll = 0;
        $(window).scroll(function(){
            scroll = $(window).scrollTop();
            $("#to_top").html($(document).height() + ", " + (scroll + window.innerHeight));
            if($(document).height() <= (scroll + window.innerHeight + 1000) && thereIsMore && !loading){
                index = index + 100;
                loadMerchantListing(term, index, term_id);
            }
        });
      
        function loadMerchantListing(term, index, term_id_t){
            console.time("load merchant listing");
            // loading = true;
            $("#to_top").html("loading...");
            //$("#merchant_listing").after("<img id='loading_merchant_listing_spinner' src='../../images/cms_loading.gif' class='spinner'/>");
            // alert("term: '" + term + "', index: " + index + ", term id: " + term_id + ", term id temp: " + term_id_t + ", thereIsMore: " + thereIsMore);
            $.ajax({
                url: "modules/merchants/merchant_listing.php",
                type: "GET",
                data:{
                    mode: "dropdown",
                    term: term,
                    index: index
                },
                success: function(data){
                    //$("#loading_merchant_listing_spinner").remove();
                    $("#to_top").html(term_id + ", "+term_id_t);
                    // loading = false;
                    if(term_id == term_id_t){
                        if(index == 0){
                            $("#merchant_listing tr:has(td)").remove();
                        }
                        $("#merchant_listing tr:last-child").after(data);
                        
                        //style data_table
                        $.getScript("include/data_table.js");
                        $(".data_table td").css("fontSize", "10pt");
                        
                        //keep loading until no data is returned
                        if(data.length > 0){
                           // thereIsMore = true;
                            loadMerchantListing(term, index + 100, term_id_t);
                        }
                        else{
                            // thereIsMore = false;
                            term_id = 0;
                            
                            //add click listener to rows
                            $(".data_table tr").click(function(){
                                var mid = $(this).attr("id").substr(6);
                                loadMerchantDetail(mid);
                            });
                        }
                    }
                    console.timeEnd("load merchant listing");
               }
            });
  */      
                 $('#form1').submit(function() {
                   if($('input:radio', this).is(':checked')) {
                        // everything's fine...
                    } 
                    else {
                        alert('Please select something!');
                        return false;
                    }
                });
                
                $("#cc").val("Optional, to notify others of case");
                $("#cc").focus(function(){
                    if($(this).val() == "Optional, to notify others of case"){ 
                        $(this).val("");
                    }
                });
            });
      </script>
        <style>
            
tr.spaceUnder > td
{
  padding-bottom: 1em;
}
            
        </style>
  </head>
<body>

    <div class="info">
        <h3>Please fill out the form below.  Click "Create Case" once you are finished. </h3>
    </div>
    <br></br>
    <h3 id="case_merchant_selected"></h3>
    <p id="case_mid_selected"></p>
    <form name="create_ticket" id="create_ticket" method="post" action="modules\cases\case_submit.php">
        <table class="full">
            <tr class='spaceUnder'><th align="left">Merchant</th><td colspan="5"><input type="text" size="40px" id="case_merchant_search" /></td></tr>
            <tr></tr>
            <tr class="spaceUnder"><th align="left">Subject</th><td colspan="5"><input type="text" name="subject" size="40px" id="subject" class="k-textbox full" /></td></tr>
            <tr></tr>
            <tr class="spaceUnder"><th align="left">Description</th><td colspan="3"><textarea class="textbox" rows="7" cols="41" name="description" id="description"></textarea></td></tr>
            <tr class=" spaceUnder"><th align="left"> Department</th><td colspan="3" id="assign_to_list"></td></tr>
            <tr><td colspan="2"><input type="hidden" name="merchant" id="merchant" /></td></tr>
            <tr><td colspan="2"><input type="hidden" name="merchantname" id="merchantname" /></td></tr>
            <tr class="spaceUnder"><th align="left">Additional CC</th><td colspan="3"><input type="text" size="40px" name="cc" id ="cc" /></td></tr>
            <tr class="spaceUnder"><td align="center" colspan="2"><input type="submit" name="submit" value="Create Case"/></td></tr>
            </table>
    </form>
</body>
</html>