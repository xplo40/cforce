<?
    //check for login
    session_start();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    $user = $_SESSION['user'];
    ?>

<?php
    include "../../include/common.php";
    $mysqli = connect_to_mysqli_database(false);
    initializePage();
    
    // USER CREDENTIALS VALIDATION 
    $user = sanitize($_GET['user']);
    $password = sanitize($_GET['password']);

    if(!validateUser($user, $password)){
        die;
    }
?>
    <script type="text/javascript">
        $(document).ready(function(){ 
           updatecases('open', $('#Unassigned_Cases'));

           function updatecases(type, element){
               $.ajax({
                    url: 'modules/cases/updatecases.php?<? echo "user=$user&password=$password";?>',
                    type: 'GET',
                    data: {
                        type: type
                    },
                    success: function(data){
                        element.html(data);
                        var id;
                        $("tr.case_listing").css("cursor","pointer");
                        $("tr.case_listing").click(function(){
                           id = $(this).attr("id").substr(5);
                           loadCaseDetail(id);
                        });   
                        
                        //style data_table
                       // $.getScript("include/data_table.js");
                }

               });  
           };

           function loadCaseDetail(id){
                $.ajax({
                   url: 'modules/cases/case_detail.php?<? echo "user=$user&password=$password";?>',
                   type: 'POST',
                   data: {
                        id: id
                   },
                   success: function(data){
                       $("#case_detail").dialog( "open" );
                       $("#case_detail").html(data);
                       

                       $("#add_note_btn").button()
                            .click(function(){
                                $.ajax({
                                    type: 'POST',
                                    url: 'modules/cases/add_note.php?<? echo "user=$user&password=$password";?>',
                                    data: {
                                        id:  $("#case_id").text(),
                                        note: $("#note_text").val(),
                                        merchantname: $("#case_merchant_name").text(),
                                        cc: $("#case_recipients").text(),
                                        assignedtoemail:  $("#assignedto").text(),
                                        creator: $("#creator").text()
                                    },
                                    success: function(data){
                                        $("#case_detail").append(data);
                                        loadCaseDetail(id);
                                    }
                                });
                            });
                             $("#take_case_btn").button()
                             .click(function(){
                         $.ajax({
                            type: 'POST',
                            url: 'modules/cases/take_case.php?<? echo "user=$user&password=$password";?>',
                            data: {
                                id:  $("#case_id").text()
                            },
                            success: function(data){
                                $("#case_detail").dialog("close");
                                updatecases("open", $("#Unassigned_Cases"));
                                updatecases("you", $("#Open_Cases"));
                                updatecases("department", $("#Open_Cases_By_Department"));
                             }
                        });
                    });

                        //reassign radio buttons click listener
                       $("input:radio[name=department]").click(function(){
                            $.ajax({
                                type: 'POST',
                                url: 'modules/cases/reassign_case.php?<? echo "user=$user&password=$password";?>',
                                data: {
                                    id:  $("#case_id").text(),
                                    user: $(this).attr("user"),
                                    department: $(this).attr("department")
                                 },
                                 success: function(data){
                                     $("#case_detail").append(data);
                                     updatecases(all, element);
                                      $("#case_detail").dialog( "close" );
                                 }
                            });
                        });
                    }
                });
           }





            $( "#case_detail" ).dialog({
                autoOpen: false,
                height: 600,
                width: 700,
                modal: true,
                buttons: {
                   
                    "Close Case": function(){
                         $.ajax({
                            type: 'POST',
                            url: 'modules/cases/close_case.php?<? echo "user=$user&password=$password";?>',
                            data: {
                             id:  $("#case_id").text()
                            },
                            success: function(data){
                                $("#case_detail").dialog("close");
                                updatecases("open", $("#Unassigned_Cases"));
                                updatecases("you", $("#Open_Cases"));
                                updatecases("department", $("#Open_Cases_By_Department"));
                                updatecases("closed", $("#Closed_Cases"));
                             }
                        });
                    },


                    OK: function() {
                        $("#case_detail").dialog("close");
                    } 

               // close: function() {
                   // allFields.val( "" ).removeClass( "ui-state-error" );
                }
            });



           $("#radio").buttonset();

            $(".case_tabs").click( function(){
                var selector = "#" + $(this).attr("id").substring(4);
                $(".active_tab").toggle();
                $(".active_tab").removeClass("active_tab");
                $(selector).toggle();
                $(selector).addClass("active_tab");
                updatecases($(selector).attr("type"),$(selector));
            });

            $(".case_panel:not(.active_tab)").hide();
        });
    </script>
  
    <?// if($usertype=='admin'): ?>
    <!--  <div id ="Unassigned_Cases" type="open" class='active_tab case_panel'></div>
     <div id ="Open_Cases" type="you" class='case_panel'></div>
     <div id ="Open_Cases_By_Department" type="department" class='case_panel'></div>
     <div id ="Closed_Cases" type="closed" class='case_panel'></div>
     <div id="case_detail"></div>

    <? //elseif($usertype=='employee'): ?>
     <div id ="Unassigned_Cases" type="open" class='active_tab case_panel'></div>
      <div id ="Open_Cases" type="you" class='case_panel'></div>
      <div id ="Open_Cases_By_Department" type="department" class='case_panel'></div>
      <div id ="Closed_Cases" type="closed" class='case_panel'></div>
      <div id="case_detail"></div>

    <?// elseif($usertype=='agent'): ?>
      <div id ="Open_Cases" type="you" class='case_panel'></div>
      <div id ="Closed_Cases" type="closed" class='case_panel'></div>
      <div id="case_detail"></div>
    <?// elseif($usertype=='basic_agent'): ?>

    -->
    <?// endif;?>        
    <div id="radio">
      <form>
        <input type="radio" id="btn_Unassigned_Cases" class="case_tabs" name="radio" checked="checked"><label for="btn_Unassigned_Cases">Unassigned Cases</label></input>
        <input type="radio" id="btn_Open_Cases" class="case_tabs" name="radio"><label for="btn_Open_Cases">Open Cases</label></input>
        <input type="radio" id="btn_Open_Cases_By_Department" class="case_tabs" name="radio"><label for="btn_Open_Cases_By_Department">Open Cases by Department</label></input>
        <input type="radio" id="btn_Closed_Cases" class="case_tabs" name="radio"><label for="btn_Closed_Cases">Closed Cases</label></input>
     </form>  
    </div> 
        <div id ="Unassigned_Cases" type="open" class='active_tab case_panel'></div>
        <div id ="Open_Cases" type="you" class='case_panel'></div>
        <div id ="Open_Cases_By_Department" type="department" class='case_panel'></div>
        <div id ="Closed_Cases" type="closed" class='case_panel'></div>
        <div id="case_detail" title="case $id, $subject" </div>
 
<?php mysqli_close($mysqli); ?>

    
    
    
    
    
