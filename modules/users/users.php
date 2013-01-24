<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
?>
<script type="text/javascript" src="../include/HighCharts/highcharts.js"></script>
<script type="text/javascript" src="../include/HighCharts/modules/exporting.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var term_id = 0;
        var term = "";
        var index = 0;
        // var thereIsMore = true;
        // var loading = false;
        $("#user_detail_content").hide();
        
<?php if($_SESSION['type'] == 'Agent'): ?>
        loadUserListing(term, index, term_id);
<?php endif; ?>
        $("#user_search").keyup( function() {
            term = $(this).val();
            if(term.length > 2){
                index = 0;
                term_id++;
                loadUserListing(term, index, term_id);
            }
        });
        $("#user_search").on('paste', function(event) { 
            term = $(this).val();
            if(term.length > 2){
               index = 0;
               term_id++;
               loadUserListing(term, index, term_id);
            }
        });
        
        $("#back_to_users_btn").button()
            .hide()
            .click(function(){
                $("#users_listing_content").show();
                $("#back_to_users_btn").hide();
                $("#user_detail_content").hide();
            });
            
        //load more after scrolling to the bottom
       /* var scroll = 0;
        $(window).scroll(function(){
            scroll = $(window).scrollTop();
            $("#to_top").html($(document).height() + ", " + (scroll + window.innerHeight));
            if($(document).height() <= (scroll + window.innerHeight + 1000) && thereIsMore && !loading){
                index = index + 100;
                loaduserListing(term, index, term_id);
            }
        });*/
        
        function loadUserListing(term, index, term_id_t){
            // loading = true;
            $("#to_top").html("loading...");
            //$("#user_listing").after("<img id='loading_user_listing_spinner' src='../../images/cms_loading.gif' class='spinner'/>");
            // alert("term: '" + term + "', index: " + index + ", term id: " + term_id + ", term id temp: " + term_id_t + ", thereIsMore: " + thereIsMore);
            $.ajax({
                url: "modules/users/user_listing.php",
                type: "GET",
                data:{
                    mode: "table",
                    term: term,
                    index: index
                },
                success: function(data){
                    //$("#loading_user_listing_spinner").remove();
                    $("#to_top").html(term_id + ", "+term_id_t);
                    // loading = false;
                    if(term_id == term_id_t){
                        if(index == 0){
                            $("#user_listing tr:has(td)").remove();
                        }
                        $("#user_listing tr:last-child").after(data);
                        
                        //style data_table
                        $.getScript("include/data_table.js");
                        $(".data_table td").css("fontSize", "10pt");
                        
                        //keep loading until no data is returned
                        if(data.length > 0){
                           // thereIsMore = true;
                            loadUserListing(term, index + 100, term_id_t);
                        }
                        else{
                            // thereIsMore = false;
                            term_id = 0;
                            
                            //add click listener to rows
                            $(".data_table tr").click(function(){
                                var id = $(this).attr("id").substr(6);
                                loadUserDetail(id);
                            });
                        }
                    }
               }
            });
        }
        
        function loadUserDetail(id){
            $("#user_detail_content").before("<img id='loading_user_detail_spinner' src='../../images/cms_loading.gif' class='spinner'/>");
            $("#user_listing_content").hide();
            $("#back_to_users_btn").show();
            $.ajax({
                url: "modules/users/user_detail.php?id=" + id,
                success: function(data){
                    $("#loading_user_detail_spinner").remove();
                    $("#user_detail_content").html(data).show();
                    
                    $("#add_case").button();
                    $("#edit_user").button();
                    $("#print_user").button();
                }
            });
        }
    });
</script>
<button id="back_to_users_btn"><- Back to user Tab</button>
<div id="user_listing_content">
    <h1>users</h1>
    <input id="user_search" type="text"></input>
    <table id="user_listing" class="data_table" width="923px">
        <tr>
            <th width="115px">ID</th>
            <th>Name</th>
            <th width="100px">Profile</th>
            <th width="120px">DDA</th>
            <th width="120px">Email</th>
        </tr>
    </table>        
</div>
<div id='user_detail_content'></div>
<a href="#" id="to_top">
    Back to top
</a>
                
                