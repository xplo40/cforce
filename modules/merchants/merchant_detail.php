<?php 
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    include "../../include/common.php";

    $id = sanitize($_GET['id']);
    echo $id;
?>

<script type="text/javascript">
    var processing_graph;
    var id = '<?php echo $id; ?>';

    $(document).ready(function() {
        //selectors
        var merchant_detail = $("#merchant_detail .section_div");
        var print_merchant = $("#print_merchant");
        var processing = $("#processing_graph .section_div");
        var add_note = $("#add_note");
        var add_case = $("#add_case");
        var status = $("#status .section_div");
        var case_create = $("#case_create");
        var add_note_modal = $("#add_note_modal");
        var case_created = $("#case_created");
        var submit_new_case = $("#submit_new_case");
        var merchant_search = $("#merchant_search");
        var description = $("#description");
        var cc = $("#cc");
        var subject = $("#subject");
        var assign_to_list = $("#assign_to_list");
        var edit_merchant= $("#edit_merchant");
        var processing_summary_list = $("#processing_summary_list .section_div");
        var merchant_notes = $("#merchant_notes .section_div");
         
        status.css("background", "#FFFFFF");
        
        //HEADER BUTTONS
        add_note.button({
            icons: {
                primary: "ui-icon-plus"
            }
        }).click(function(){
            $("#add_note_modal").dialog("open");
            return false;
        });
        add_case.button({
            icons: {
                primary: "ui-icon-plus"
            }
        }).click(function(){
            $("#create_case").dialog("open");
            return false;
        });
        edit_merchant.button()
            .click(function(){
                $("#edit_merchant_modal").dialog("open");
                return false;
            })
            .attr("disabled", "disabled")
            .css("background", "#CCC");
        print_merchant.button()
            .click(function(){
                return false;
            });
            
///////////////////////////CASES
        //add case dialog
        case_create.dialog({
            autoOpen: false,
            height: 700,
            width: 840,
            modal: true,
            buttons:{
                "Create Case":  function(){
                    if(!cc.hasClass("default")){
                        cc.val("");
                    }
                    if(merchant_search.hasClass("default")){
                        merchant_search.val("");
                    }
                    $.ajax({
                        url: "modules/cases/case_submit.php",
                        type: "GET",
                        cache: false,
                        data:{
                            merchant: merchant_search.val(),
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
                },
                "Cancel": function(){
                    case_create.dialog("close");
                    return false;
                }
            }
        });   
        $("#case_create .section_div").css("background", "FFFFFF");
           
        //case created dialog
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
           
        //load assign_to_list in case create dialog
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
        
        //add case modal submit button
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
                        merchant: $("#case_mid_selected").text(),
                        merchant_name: $("#case_merchant_selected").text(),
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

///////////////////////////ADD NOTE MODAL
        add_note_modal.dialog({
            autoOpen: false,
            height: 240,
            width: 340,
            modal: true,
            buttons:{
                "Add":  function(){
                    $.ajax({
                        url: "modules/merchants/add_note.php",
                        type: "GET",
                        cache: false,
                        data:{
                            id: id,
                            type: 5,
                            note: $("#add_note_modal #note_input").val()
                        },
                        success: function(data){
                            add_note_modal.dialog("close");
                            loadMerchantNotes();
                            alert(data);
                        }
                    });
                    return false;
                },
                "Cancel": function(){
                    add_note_modal.dialog("close");
                    return false;
                }
            }
        });   
          
///////////////////////////MERCHANT DETAILS
        loadMerchantDetailSection();
        function loadMerchantDetailSection(){
            merchant_detail.css("background", "none");
            merchant_detail.html("");
            console.time("load merchant detail section");
            $.ajax({
                url: "modules/merchants/merchant_detail_data.php",
                cache: false,
                type: "GET",
                data:{
                    id: id
                },
                success: function(data){
                    merchant_detail.css("background", "#FFFFFF");
                    merchant_detail.html(data);
                    console.timeEnd("load merchant detail section");
                    loadEditMerchant();
                    $("#case_create #case_merchant_selected").text($("#merchant_detail_table #detail_dba"));
                    $("#case_create #case_mid_selected").text($("#merchant_detail_table #detail_mid"));

                    edit_merchant.removeAttr("disabled")
                        .css({
                            background: "#fed99a url(css/custom-theme/images/ui-bg_inset-soft_100_fed99a_1x100.png) 50% 50% repeat-x",
                            border: "1px solid #999 !important"
                        });
                    $("#edit_merchant:hover").css({
                        background: "#fdb35d url(images/ui-bg_flat_33_fdb35d_40x100.png) 50% 50% repeat-x",
                        border: "1px solid #fdb35d"
                    });
                    $("#edit_merchant_modal #agent_search_field").autocomplete({
                        minLength: 2,
                        source: 'modules/agents/agent_listing_cases.php',
                        focus: function(event, ui) {
                            $("#edit_merchant_modal #agent_search_field").val(ui.item.label);
                            return false;
                        },
                        select: function(event, ui) {
                            $("#edit_merchant_modal #agent_display").text(ui.item.label + " (" + ui.item.value + ")");
                            $("#edit_merchant_modal #agent_selected").val(ui.item.label);
                            $("#edit_merchant_modal #agent_ID_selected").val(ui.item.value);
                            return false;
                        }
                    })
                    .data("autocomplete")._renderItem = function(ul, item) {
                        return $("<li></li>")
                            .data("item.autocomplete", item)
                            .append("<a><p>" + item.label + " (" + item.value + ")</p></a>")
                            .appendTo(ul);
                    };
                }
            });      
        }
 
///////////////////////////MERCHANT NOTES
    loadMerchantNotes();
    function loadMerchantNotes(){
        merchant_notes.css("background", "none");
        merchant_notes.html("");
        $.ajax({
            url: "modules/merchants/get_merchant_notes.php",
            type: "GET",
            data:{
                id: id
            },
            cache: false,
            tryCount: 0,
            success: function(data){
                merchant_notes.css("background", "#FFFFFF");
                merchant_notes.html(data);
            }
        });
    }
    
///////////////////////////MERCHANT CHARTS
        $.ajax({
            url: "modules/charts/processing_summary_list.php",
            type: "GET",
            data:{
                mid: id
            },
            cache: false,
            tryCount: 0,
            success: function(data){
                processing_summary_list.css("background", "#FFFFFF");
                processing_summary_list.html(data);
            }
        });
        
        //Processing Graph
        processing_graph = new Highcharts.Chart({
            chart: {
                renderTo: 'processing_graph_container_m',
                events: {
                    load: requestProcessingData
                },
                width: 990,
                height: 300,
                backgroundColor:'rgba(255, 255, 255, 0.1)'
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false  
            },
            xAxis:[{
                    categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                }
            ],
            yAxis: [{ // Primary yAxis
                title: {
                    text: 'Daily Amount',
                    style: {
                        color: '#89A54E'
                    }
                }
            }], 
            series: [{
                name: 'DailyAmount',
                color: '#4572A7',
                type: 'column',
                data: []
    
            }]
        });
        
///////////////////////////FUNCTIONS
        function loadEditMerchant() {
            var dba_name = $("#edit_merchant_modal #dba_name");
            var legal_name = $("#edit_merchant_modal #legal_name");
            var address = $("#edit_merchant_modal #address");
            var city = $("#edit_merchant_modal #city");
            var state = $("#edit_merchant_modal #state");
            var zip = $("#edit_merchant_modal #zip");
            var account_status = $("#edit_merchant_modal #account_status");
            var email = $("#edit_merchant_modal #email");
            var phone = $("#edit_merchant_modal #phone");
            var agent_name =  $("#edit_merchant_modal #agent_selected");
            var agent_id = $("#edit_merchant_modal #agent_ID_selected");
            var merchant_id = $("#edit_merchant_modal #merchant_id");
            var monthly_volume = $("#edit_merchant_modal #monthly_volume");
            var average_ticket = $("#edit_merchant_modal #average_ticket");
            var prefund = $("#edit_merchant_modal #prefund");
            var pricing_type = $("#edit_merchant_modal #pricing_type");
            var percentage = $("#edit_merchant_modal #percentage");
            var transaction_fee = $("#edit_merchant_modal #transaction_fee");
            var debit = $("#edit_merchant_modal #debit");
            var qual = $("#edit_merchant_modal #qual");
            var midqual = $("#edit_merchant_modal #midqual");
            var nonqual = $("#edit_merchant_modal #nonqual");
            var amex = $("#edit_merchant_modal #amex");
            var discover = $("#edit_merchant_modal #discover");
            var pin_debit = $("#edit_merchant_modal #pin_debit");
            var pin_debit_fee = $("#edit_merchant_modal #pin_debit_fee");
            var monthly_min = $("#edit_merchant_modal #monthly_min");
            var statement_fee = $("#edit_merchant_modal #statement_fee");
            var hardware = $("#edit_merchant_modal #hardware");
            var process_platform = $("#edit_merchant_modal #process_platform");
            var notes = $("#edit_merchant_modal #notes");
           
           $("#edit_merchant_modal").dialog({
                autoOpen: false,
                height: 700,
                width: 700,
                modal: true,
                buttons: {
                    "Save Changes": function() {
                         $.ajax({
                            url: "modules/merchants/edit_merchant.php",
                            type: "GET",
                            cache: false,
                            data:{
                                id: id,
                                dba_name: escape(dba_name.val()),
                                legal_name: escape(legal_name.val()),
                                address: escape(address.val()),
                                city: escape(city.val()),
                                state: state.val(),
                                zip: zip.val(),
                                account_status: account_status.val(),
                                email: email.val(),
                                phone: escape(phone.val()),
                                agent_name:  agent_name.val(),
                                agent_id: agent_id.val(),
                                merchant_id: (merchant_id.val() == "(none)" ? "" : merchant_id.val()),
                                monthly_volume: monthly_volume.val().replace(",",""),
                                average_ticket: average_ticket.val().replace(",",""),
                                prefund: prefund.val().replace(",",""),
                                pricing_type: pricing_type.val(),
                                percentage: percentage.val(),
                                transaction_fee: transaction_fee.val(),
                                debit: debit.val(),
                                qual: qual.val(),
                                midqual: midqual.val(),
                                nonqual: nonqual.val(),
                                amex: amex.is(":checked"),
                                discover: discover.is(":checked"),
                                pin_debit: pin_debit.is(":checked"),
                                pin_debit_fee: pin_debit_fee.val(),
                                monthly_min: monthly_min.val().replace(",",""),
                                statement_fee: statement_fee.val().replace(",",""),
                                hardware: hardware.val(),
                                process_platform: process_platform.val(),
                                notes: notes.val()
                            },
                            success: function(data){
                                alert(data);
                                $("#edit_merchant_modal").dialog("close");
                                loadMerchantNotes();
                                loadMerchantDetailSection();
                            }
                         });
                    },
                    "Cancel": function(){
                        $("#edit_merchant_modal").dialog("close");
                    }
                }
            });
            
            setPricingRows(pricing_type.val())
            pricing_type.change(function(){
                  setPricingRows(pricing_type.val())
            });
            
            function setPricingRows(type){
                var tiered_row = $("#edit_merchant_modal .tiered_row");
                var interchange_row = $("#edit_merchant_modal .interchange_row");
                if(type == '1'){
                    tiered_row.hide();
                    interchange_row.show();
                }
                else if(type == '2'){
                    tiered_row.show();
                    interchange_row.hide();
                }
                else{
                    tiered_row.hide();
                    interchange_row.hide();
                }
            }
       }
       
       function requestProcessingData() {
             $.ajax({
                url: 'modules/merchants/data.php',
                type: "GET",
                data: {
                    id: id
                },
                datatype: "json",
                cache: false,
                success: function(data) {
                    processing.css("background", "#FFFFFF");
                    if(data){
                        var dataArr = data.split(",").map(parseFloat);
                        processing_graph.series[0].setData(dataArr);
                    }
                }
            });
       }
    });
</script>

<table id="merchant_detail_layout">
    <tbody>
        <tr>
            <td colspan="2">
                <div id='header_button_row'>
                    <button id='add_note'>Add Note</button>
                    <button id='add_case'>Add Case</button>
                <?php if($_SESSION['department'] == '10' || $_SESSION['department'] == '13'): ?>
                    <button id='edit_merchant'>Edit</button>
                <?php endif; ?>
                    <button id='print_merchant'>Print</button>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
               <div id="merchant_detail">
                    <div class="section_header"><p>Merchant Detail</p></div>
                    <div class="section_div"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
               <div id="merchant_notes">
                    <div class="section_header"><p>Merchant Notes</p></div>
                    <div class="section_div"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="processing_summary_list">
                    <div class="section_header"><p>Volume Summary</p></div>
                    <div class=" section_div"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="processing_graph">
                    <div class="section_header"><p>Processing</p></div>
                    <div class="section_div">
                        <div id="processing_graph_container_m"></div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<div id='case_create' title="Create Case">
    <div class="section_header"><p>Merchant</p></div>
    <div class="section_div">
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

<div id='add_note_modal' title="Add Note">
    <textarea rows="7" cols="41" id="note_input"></textarea>
</div>

<div id='case_created'></div>


