<?
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
?>
    <script type="text/javascript">
        //assign case tab types
        var case_panel_types = new Array("New","Open","Closed");

        $(document).ready(function(){
            //selectors
            var cases = $("#cases .section_div");
            var case_detail = $("#case_detail");
            var case_id = $("#case_id");
            var take_case_btn = $("#take_case_btn");
            var case_panel = $(".case_panel");
            var case_panel_0 = $("#case_panel_0");
            var case_panel_1 = $("#case_panel_1");
            var case_panel_2 = $("#case_panel_2");
            var case_panel_0_btn_lbl = $("#case_panel_0_btn_lbl");
            var case_panel_1_btn_lbl = $("#case_panel_1_btn_lbl");
            var case_panel_2_btn_lbl = $("#case_panel_2_btn_lbl");
            var case_radio_set = $("#case_radio_set");
            var case_tabs = $(".case_tabs");
            var case_panel_not_active = $(".case_panel:not(.active_tab)");
            var case_create = $("#case_create");
            var case_created = $("#case_created");
            var case_create_btn = $("#case_create_btn");
           var agent_selected_row = $("#agent_selected_row");
           var agent_selected_id_row = $("#agent_selected_id_row");
            //modal selectors
            var submit_new_case = $("#submit_new_case");
            var merchant_search = $("#merchant_search");
            var description = $("#description");
            var cc = $("#cc");
            var subject = $("#subject");
            var assign_to_list = $("#assign_to_list");
            var department_listing = $("#department_listing");
            var member_listing = $("#member_listing");
            var case_merchant_selected = $("#case_merchant_selected");
            var case_mid_selected = $("#case_mid_selected");
            
            /** CASE TABS **/
            case_radio_set.buttonset();

            case_tabs.click( function(){
                var index = $(this).attr("id").substring(9);
                selector = case_panel.eq(index);
                $(".active_tab").toggle();
                $(".active_tab").removeClass("active_tab");
                $(selector).toggle();
                $(selector).addClass("active_tab");
                return false;
            });
            
            //add case labels
            case_panel_0_btn_lbl.text(case_panel_types[0]);
            case_panel_1_btn_lbl.text(case_panel_types[1]);
            case_panel_2_btn_lbl.text(case_panel_types[2]);

            /** CASE PANELS **/
            case_panel_not_active.hide();
            
            updatecases(0);
            updatecases(1);
            updatecases(2);
            
            case_panel_0.attr("type", case_panel_types[0]);
            case_panel_1.attr("type", case_panel_types[1]);
            case_panel_2.attr("type", case_panel_types[2]);

            /** CREATE CASE **/
            //create case button
            case_create_btn.button({
                icons: {
                    primary: "ui-icon-plus"
                }
            }).click(function(){
                    case_create.dialog("open");
                    return false;
                });
                
            //create case dialog
            case_create.dialog({
                autoOpen: false,
                height: 650,
                width: 700,
                modal: true
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
            
            //assign_to_list in case create dialog
            $.ajax({
                url: "modules/cases/assign_to_list.php",
                cache: false,
                success: function(data){
                    //display returned html
                    assign_to_list.html(data);
                    //element vars
                    var agent_listing_results = $("#agent_listing_results");
                    var member_listing = $("#member_listing");
                    agent_listing_results.hide();
                    member_listing.hide();
                    //erase spinners
                    $("#department_listing .section_div").css("background", "#FFFFFF");
                    $("#member_listing .section_div").css("background", "#FFFFFF");
                    $("#agent_listing_results .section_div").css("background", "#FFFFFF");
                    //on change department
                    $("input:radio[name=department]").on("change", function(){
                        if ($("input[name=department]:checked").val() == '2'){
                             agent_listing_results.show();
                             member_listing.hide();
                        }
                        else if($("input[name=department]:checked").val() == '<?php echo $_SESSION['department']?>'){
                            agent_listing_results.hide();
                            member_listing.show();
                        }
                        else{
                            agent_listing_results.hide();
                            member_listing.hide();
                        }
                    });
                    
                     $("#agent_listing_case_list").autocomplete({
                        minLength: 2,
                        source: 'modules/agents/agent_listing_cases.php',
                        focus: function(event, ui) {
                            $("#agent_listing_case_list").val(ui.item.label);
                            return false;
                        },
                        select: function(event, ui) {
                            $("#case_agent_selected").text(ui.item.label);
                            $("#case_agent_ID_selected").text(ui.item.value);
                            $("case_agent_email").text(ui.item.agentemail);
                            return false;
                        }
                    })
                    .data("autocomplete")._renderItem = function(ul, item) {
                        return $("<li></li>")
                            .data("item.autocomplete", item)
                            .append("<a><p>" + item.label + "</p><p>" + item.value + "</p></a>")
                            .appendTo(ul);
                    };
                }
            });
            
            //cc default message
            cc.click(function(){
                if(cc.hasClass("default")){
                    cc.val("")
                        .removeClass("default");
                }
                return false;
            });
            cc.blur(function(){
                if(cc.val() == ""){
                    cc.addClass("default")
                        .val("   Optional to notify others");
                }
                return false;
            });
            
             //create case submit button
            submit_new_case.button()
                .click(function(){
                    if(cc.val() == "   Optional to notify others"){
                        cc.val("");
                    }
                    $.ajax({
                        url: "modules/cases/case_submit.php",
                        type: "GET",
                        cache: false,
                        data:{
                            merchant: case_mid_selected.text(),
                            merchant_name: case_merchant_selected.text(),
                            description: description.val(),
                            cc: cc.val(),
                            assigned_to_department: $("input[type=radio][name=department]:checked").val(),
                            assigned_to_id: $("input[type=radio][name=coworker]:checked").val(),
                            subject: subject.val()
                        },
                        success: function(data){
                            case_create.dialog("close");
                            case_created.html(data);
                            case_created.dialog("open");
                            updatecases(0);
                            updatecases(1);
                            updatecases(2);
                        }
                    });
                    return false;
                });
                
            /** CASE CREATED **/
            case_created.dialog({
                autoOpen: false,
                height: 500,
                width: 400,
                modal: true,
                buttons: {
                    OK: function() {
                        case_created.dialog("close");
                        return false;    
                    } 
                }
            });
            
            /** CASE DETAIL **/
            case_detail.dialog({
                autoOpen: false,
                height: 725,
                width: 800,
                modal: true,
                buttons: {
                    "Close Case": function(){
                         $.ajax({
                            type: 'GET',
                            url: 'modules/cases/close_case.php',
                            data: {
                                id:  $("#case_id").text()
                            },
                            success: function(data){
                                $("#case_detail").dialog("close");
                                updatecases(0);
                                updatecases(1);
                                updatecases(2);
                             }
                        });
                        return false;
                    },
                    OK: function() {
                        case_detail.dialog("close");
                        return false;    
                    } 
                    // close: function() {
                   // allFields.val( "" ).removeClass( "ui-state-error" );
                }
            });
            
            function updatecases(type_index){
                type = case_panel_types[type_index];
                var element = case_panel.eq(type_index);
                $.ajax({
                    url: 'modules/cases/updatecases.php',
                    type: 'GET',
                    cache: false,
                    data: {
                        type: type
                    },
                    success: function(data){
                        cases.css("background", "#FFFFFF");
                        element.html(data);
                        label = case_tabs.eq(type_index).next();
                        x = label.offset().left + label.width() - 10;
                        y = label.offset().top - 10;
                        notification = element.children(".notification");
                        case_radio_set.append(notification);
                        notification.offset({top:y, left:x});
                        $("tr.case_listing").css("cursor","pointer");
                        $("tr.case_listing").click(function(){
                           id = $(this).attr("id").substr(5);
                           loadCaseDetail(id);
                           return false;
                        });   
                    }
               }); 
               return false;
           };

           function loadCaseDetail(id){
                console.time("load case detail");
                $.ajax({
                    url: 'modules/cases/case_detail.php',
                    type: 'GET',
                    cache: false,
                    data: {
                        id: id
                    },
                    success: function(data){
                       console.timeEnd("load case detail");
                       case_detail.dialog("open");
                       case_detail.html(data);
                       $("#case_detail .section_div").css("background", "#FFFFFF");
                      
                       $("input:radio[name=department]").on("change", function(){
                            if($("input[name=department]:checked").val() == '<?php echo $_SESSION['department']?>'){
                                $("#department_member_info").show();
                             }
                             else{
                                 $("#department_member_info").hide();
                             }
                         });
                           
                     
                       $("#add_note_btn").button()
                            .click(function(){
                                $.ajax({
                                    type: 'GET',
                                    cache: false,
                                    url: 'modules/cases/add_note.php',
                                    data: {
                                        id:  $("#case_id").text(),
                                        note: $("#note_text").val(),
                                        merchantname: $("#case_merchant_name").text(),
                                        cc: $("#case_recipients").text(),
                                        assignedtoemail:  $("#assignedtoemail").val(),
                                        creatoremail:  $("#creatoremail").val()
                                    },
                                    success: function(data){
                                        case_detail.append(data);
                                        loadCaseDetail(id);
                                        $("#add_note .section_div").css("background", "#FFFFFF");
                       
                                    }
                                });
                                return false;
                            });
                            
                        take_case_btn.button()
                             .click(function(){
                                $.ajax({
                                   type: 'GET',
                                   url: 'modules/cases/take_case.php',
                                   data: {
                                       id:  case_id.text()
                                   },
                                   success: function(data){
                                       case_detail.dialog("close");
                                       updatecases(0);
                                       updatecases(1);
                                       updatecases(2);
                                    }
                               });
                               return false;
                           });
                           
                          /* $("#case_recipients").focusout(function(){
        $.ajax({
            type: 'GET',
            url: 'modules/cases/add_recipient.php',
            data: {
             id:  $("#case_id").text(),
             cc:  $("case_recipients").val()
            },
             success: function(data){

             }
          })
            return false;
    });*/
                        //reassign radio buttons click listener
                       /*department_radio.click(function(){
                            $.ajax({
                                type: 'GET',
                                url: 'modules/cases/reassign_case.php',
                                data: {
                                    id:  case_id.text(),
                                    user: $(this).attr("user"),
                                    department: $(this).attr("department")
                                 },
                                 success: function(data){
                                     case_detail.append(data);
                                     updatecases(0);
                                     updatecases(1);
                                     updatecases(2);
                                     case_detail.dialog("close");
                                 }
                            });
                            return false;
                        });*/
                    }
                });
           }
        });
    </script>
    
    <div id="case_radio_set">
      <form>
        <input type="radio" id="case_btn_0" class="case_tabs" name="radio" checked="checked">
            <label id="case_panel_0_btn_lbl" for="case_btn_0"></label>
        </input>
        <input type="radio" id="case_btn_1" class="case_tabs" name="radio">
            <label id="case_panel_1_btn_lbl" for="case_btn_1"></label>
        </input>
        <input type="radio" id="case_btn_2" class="case_tabs" name="radio">
            <label id="case_panel_2_btn_lbl" for="case_btn_2"></label>
        </input>
     </form>  
    </div> 
    <div id="case_panel_0" class='active_tab case_panel'></div>
    <div id="case_panel_1" class='case_panel'></div>
    <div id="case_panel_2" class='case_panel'></div>
    <button id='case_create_btn'>Add Case</button>
    
    
    <div id='case_create' title="Create Case">
        <div class="info">
                Please fill out the form below.  Click "Create Case" once you are finished.
        </div>
        <div class="section_header"><p>Merchant</p></div>
        <div class="section_div">
            <input type="text" size="40px" id="case_merchant_search" />
            <h3 id="case_merchant_selected"></h3>
            <p id="case_mid_selected"></p>
        </div>
        <div class="section_header"><p>Details</p></div>
        <div class="section_div">
            <p>Subject</p>
            <input type="text" name="subject" size="40px" id="subject" class="k-textbox full" />
            <p>Description</p>
            <textarea class="textbox" rows="7" cols="41" name="description" id="description"></textarea>
        </div>
        <div id="assign_to_list"></div>
                 
        <div class="section_header"><p>Additional CC</p></div>
        <div class="section_div">
            <input type="text" size="40px" name="cc" id ="cc" value="   Optional to notify others" class="default"/>
        </div>
        <button id="submit_new_case">Create Case</button>
    </div>
    
    <div id='case_detail'></div>
    <div id='case_created'></div>
    
   
    