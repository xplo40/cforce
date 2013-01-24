<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    include "../../include/common.php";
    
    //this is the agent id
    $id = sanitize($_GET['id']);
?>

<script type="text/javascript">
    var processing_graph;
    var boarded_graph;
    $(document).ready(function() {
        //selectors
        var spinner = $("#spinner");
        var agent_detail = $("#agent_detail .section_div");
        var top_merchants_list = $("#top_merchants_list .section_div");
        var processing_summary_list = $("#processing_summary_list .section_div");
        var merchant_detail_content_m = $("#merchant_detail_content_m");
        var merchant_detail_content_h = $("#merchant_detail_content_h");
        var merchant_detail_content_p = $("#merchant_detail_content_p");
        var merchant_detail_content_a = $("#merchant_detail_content_a");
        var boarded = $("#boarded_graph .section_div");
        var processing = $("#processing_graph .section_div");
        var add_case = $("#add_case");
        var edit_agent = $("#edit_agent");
        var print_agent = $("#print_agent");
        var back_to_agent_btn = $("#back_to_agent_btn");
        var merchant_header_a = $("#merchant_header_a");
        var agent_detail_layout = $("#agent_detail_layout");
        
        //selectors - add case modal
        var case_create = $("#case_create");
        var case_created = $("#case_created");
        var submit_new_case = $("#submit_new_case");
        var merchant_search = $("#merchant_search");
        var description = $("#description");
        var cc = $("#cc");
        var subject = $("#subject");
        var assign_to_list = $("#assign_to_list");
        spinner.hide();
        //vars
        var timeout = 60000;
        var retryLimit = 1;
        
        //initialize
        merchant_header_a.hide();
        
       case_create.dialog({
                autoOpen: false,
                height: 650,
                width: 700,
                modal: true
            });   
       add_case.button({
            icons: {
                primary: "ui-icon-plus"
            }
        }).click(function(){
            case_create.dialog("open");
            return false;
        });
        edit_agent.button({disabled:true})
            .click(function(){
                $("#edit_agent_modal").dialog("open");
                return false;
            });
        print_agent.button()
            .click(function(){
                return false;
            });
            
        //assign_to_list in case create dialog
        $.ajax({
            url: "modules/cases/assign_to_list.php",
            cache: false,
            timeout: timeout,
            tryCount: 0,
            retryLimit: retryLimit,
            success: function(data){
                assign_to_list.css("background", "#FFFFFF");
                assign_to_list.html(data);
            },
            error:  ajaxError
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
            
        //create case submit button
        submit_new_case.button()
           .click(function(){
               $.ajax({
                   url: "modules/cases/case_submit.php",
                   type: "GET",
                    timeout: timeout,
                    tryCount: 0,
                    retryLimit: retryLimit,
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
                   }
               });
               return false;
           });
           
        //load agent details
        $.ajax({
            url: "modules/agents/agent_detail_data.php",
            cache: false,
            type: "GET",
            data:{
                agent: '<?php echo $id; ?>'
            },
            timeout: timeout,
            tryCount: 0,
            retryLimit: retryLimit,
            success: function(data){
                edit_agent.button({disabled:false});
                agent_detail.css("background", "#FFFFFF");
                agent_detail.html(data);
                $("#edit_agent_modal").dialog({
                    modal: true,
                    height: 400,
                    buttons:{
                        "Submit Changes": function(){
                            $.ajax({
                                url: "modules/agents/edit_agent.php",
                                cache: false,
                                type: "GET",
                                data:{
                                    id: '<?php echo $id; ?>',
                                    name: $("#edit_agent_modal #name").val(),
                                    company: $("#edit_agent_modal #company").val(),
                                    address: $("#edit_agent_modal #address").val(),
                                    city: $("#edit_agent_modal #city").val(),
                                    state: $("#edit_agent_modal #state").val(),
                                    zip: $("#edit_agent_modal #zip").val(),
                                    email: $("#edit_agent_modal #email").val(),
                                    phone: $("#edit_agent_modal #phone").val(),
                                    access: $("#edit_agent_modal #access").val(),
                                    repcode_low: $("#edit_agent_modal #repcode_low").val(),
                                    repcode_high: $("#edit_agent_modal #repcode_high").val(),
                                    com_low: $("#edit_agent_modal #com_low").val(),
                                    com_high: $("#edit_agent_modal #com_high").val()
                                },
                                timeout: timeout,
                                tryCount: 0,
                                retryLimit: retryLimit,
                                success: function(data){
                                    alert(data);
                                    $("#edit_agent_modal input").val("");
                                    $("#edit_agent_modal").dialog("close");
                                },
                                error:  ajaxError
                            });
                        },
                        "Cancel": function(){
                            $("#edit_agent_modal").dialog("close");
                        }
                    },
                    width: 810,
                    autoOpen: false
                });
            },
            error:  ajaxError
        });
        
        //Top Merchants List
         $.ajax({
            url: "modules/charts/top_merchants_list.php",
            type: "GET",
            data:{
                agent: '<?php echo $id; ?>'
            },
            cache: false,
            timeout: timeout,
            tryCount: 0,
            retryLimit: retryLimit,
            success: function(data){
                top_merchants_list.css("background", "#FFFFFF");
                top_merchants_list.html(data);
                $("#top_merchants_list .data_table tr").click(function(){
                    merchant_header_a.show();
                    var mid = $(this).attr("id");
                    loadMerchantDetail(mid);
                    return false;
                });
            },
            error:  ajaxError
        });
        
        back_to_agent_btn.button({
                icons: {
                    primary: 'ui-icon-triangle-1-w'
                }
            })
            .click(function(){
                merchant_detail_content_a.hide();
                merchant_header_a.hide();
                agent_detail_layout.show();
                return false;
            });
        
        function loadMerchantDetail(mid){
            merchant_detail_content_m.html("");
            merchant_detail_content_p.html("");
            merchant_detail_content_h.html("");
            //clear previous merchant detail dialogs
            $(".ui-dialog").has("#case_create").remove();
            $(".ui-dialog").has("#add_note_modal").remove();
            $(".ui-dialog").has("#case_created").remove();
            $(".ui-dialog").has("#edit_merchant_modal").remove();
           spinner.show();
           // $(".detail_spinner").remove();
           // merchant_detail_content_a.before("<img class='detail_spinner' src='../../images/cms_loading.gif'/>");
            agent_detail_layout.hide();
            $.ajax({
                url: "modules/merchants/merchant_detail.php?mid=" + mid,
                cache: false,
                timeout: timeout,
                tryCount: 0,
                retryLimit: retryLimit,
                success: function(data){
                   spinner.hide();
                    merchant_detail_content_a.html(data).show();
                },
                error:  ajaxError
            });
        }
        
        //Merchant Status pie chart
        new Highcharts.Chart({
            chart: {
                renderTo: 'status_chart_container',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                width: 475,
                height: 243
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '<b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            credits: {
                enabled: false  
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        distance: 30,
                        formatter: function(){
                            return this.point.name +': '+ this.y;
                        }
                    },
                    showInLegend:false
                }
            },
            series: [{
                type: 'pie',
                data: [
                    ['Live',   253],
                    ['Boarded',       1],
                    {
                        name: 'Received',
                        y: 1,
                        sliced: true,
                        selected: true
                    },
                    ['Approved',    17],
                    ['Pending',     1],
                    ['Declined',   4],
                    ['Closed',   23]
                ]
            }]
        });
        
        //Boarded Graph
        boarded_graph = new Highcharts.Chart({
            chart: {
                renderTo: 'boarded_graph_container',
                events: {
                    load: requestBoardedData
                },
                width: 990,
                height: 243,
                backgroundColor:'rgba(255, 255, 255, 0.1)'
            },
             title: {
                text: ''
            },
           
            credits: {
                enabled: false  
            },
            legend: {
                enabled: false
            },
            xAxis: [{
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            }],
            yAxis: { // Primary yAxis
                title: {
                    text: 'Amount',
                    style: {
                        color: '#89A54E'
                    }
                }
            }, 
            series: [{
                name: 'Boarded This Month',
                color: '#4572A7',
                type: 'column',
                data: []
            }]
        });
        
        //Processing Summary List
        $.ajax({
            url: "modules/charts/processing_summary_list.php",
            type: "GET",
            data:{
                agent: '<?php echo $id; ?>'
            },
            cache: false,
            timeout: timeout,
            tryCount: 0,
            retryLimit: retryLimit,
            success: function(data){
                processing_summary_list.css("background", "#FFFFFF");
                processing_summary_list.html(data);
            },
            error:  ajaxError
        });
        
        //Processing Graph
        processing_graph = new Highcharts.Chart({
            chart: {
                renderTo: 'processing_graph_container_a',
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
            legend: {
                enabled: false
            },
            xAxis: [{
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            }],
            yAxis: {
            labels: {
                formatter: function() {
                    return '$' + Highcharts.numberFormat(this.value, 0, '', ',');
                }},// Primary yAxis
                title: {
                    text: 'Amount',
                    style: {
                        color: '#89A54E'
                    }
                }
            }, 
            series: [{
                name: 'Monthly Amount',
                color: '#4572A7',
                type: 'column',
                data: []
            }]
        });
        
        function requestBoardedData() {
            $.ajax({
                url: 'modules/charts/boarded_graph.php',
                type: "GET",
                data:{
                    agent: '<?php echo $id; ?>'
                },
                datatype: "json",
                cache: false,
                timeout: timeout,
                tryCount: 0,
                retryLimit: retryLimit,
                success: function(data) {
                    boarded.css("background", "#FFFFFF");
                    var dataArr = data.split(",").map(parseFloat);
                    boarded_graph.series[0].setData(dataArr);
                },
                error:  ajaxError
            });
        }
        
        function requestProcessingData() {
            $.ajax({
                url: 'modules/charts/processing_graph.php',
                type: "GET",
                data:{
                    agent: '<?php echo $id; ?>'
                },
                datatype: "json",
                cache: false,
                timeout: timeout,
                tryCount: 0,
                retryLimit: retryLimit,
                success: function(data) {
                    processing.css("background", "#FFFFFF");
                    var dataArr = data.split(",").map(parseFloat);
                    processing_graph.series[0].setData(dataArr);
                },
                error:  ajaxError
            });
        }
        
        function ajaxError(xhr, textStatus, errorThrown) {
            if (textStatus == 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    //try again
                    $.ajax(this);
                    return;
                }
               // alert('We have tried ' + this.retryLimit + ' times and it is still not working. We give in. Sorry.');
                return;
            }
            if (xhr.status == 500) {
                //alert('Oops! There seems to be a server problem, please try again later.');
            } 
            else {
                //alert('Oops! There was a problem, sorry.');
            }
        }
    });

</script>

<div id="merchant_header_a">
    <button id="back_to_agent_btn">Back to Agent Detail</button>
</div>
<table id="agent_detail_layout">
    <tbody>
        <tr>
            <td colspan="2">
                <div id='header_button_row'>
                    <button id='add_case'>Add Case</button>
                <?php if($_SESSION['department'] == '10' || $_SESSION['department'] == '13'): ?>
                    <button id='edit_agent'>Edit</button>
                <?php endif; ?>
                    <button id='print_agent'>Print</button>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
               <div id="agent_detail">
                    <div class="section_header"><p>Agent Detail</p></div>
                    <div class="section_div"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="top_merchants_list">
                    <div class="section_header"><p>Top Processing Merchants YTD</p></div>
                    <div class="section_div"></div>
                </div>
            </td>
            <td>
                <div id="status_chart">
                    <div class="section_header"><p>Merchant Status</p></div>
                    <div class="section_div">
                        <div id="status_chart_container"></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="boarded_graph">
                    <div class="section_header"><p>Merchants Boarded</p></div>
                    <div class="section_div">
                        <div id="boarded_graph_container"></div>
                    </div>
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
            <td colspan="2">
                <div id="processing_graph">
                    <div class="section_header"><p>Processing</p></div>
                    <div class="section_div">
                        <div id="processing_graph_container_a"></div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
    
<div id='case_create' title="Create Case">
    <div class="info">
            Please fill out the form below.  Click "Create Case" once you are finished.
    </div>
    <h3 id="merchant_selected"></h3>
    <p id="mid_selected"></p>
    <table class="full">
        <tr class='spaceUnder'>
            <th align="left">Merchant</th>
            <td colspan="5">
                <input type="text" size="40px" id="merchant_search" />
            </td>
        </tr>
        <tr></tr>
        <tr class="spaceUnder">
            <th align="left">Subject</th>
            <td colspan="5">
                <input type="text" name="subject" size="40px" id="subject" class="k-textbox full" />
            </td>
        </tr>
        <tr></tr>
        <tr class="spaceUnder">
            <th align="left">Description</th>
            <td colspan="3">
                <textarea class="textbox" rows="7" cols="41" name="description" id="description"></textarea>
            </td>
        </tr>
        <tr class=" spaceUnder">
            <td id="assign_to_list" colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="merchant" id="merchant" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="hidden" name="merchant_name" id="merchant_name" />
            </td>
        </tr>
        <tr class="spaceUnder">
            <th align="left">Additional CC</th>
            <td colspan="3">
                <input type="text" size="40px" name="cc" id ="cc" value="   Optional to notify others" class="default"/>
            </td>
        </tr>
        <tr class="spaceUnder">
            <td align="center" colspan="2">
                <button id="submit_new_case">Create Case</button>
            </td>
        </tr>
    </table>
</div>

<div id='case_created'></div>

<div id='merchant_detail_content_a'></div>
<div id="spinner">
    <img class='detail_spinner' src='../../cforce/images/cms_loading.gif'/>"
</div>
              