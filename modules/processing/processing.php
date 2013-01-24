<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    if($_SESSION['department'] < 10){
         $agent = $_SESSION['agent'];
    }
    else{
        $agent = "";
    }
?>

<script type="text/javascript">
    $(document).ready(function(){
        //selectors
        var cmv_button = $('#cmv_button');
        var  processing_summary_list = $("#processing_summary_list .section_div");
        var merchant_processing_list = $("#merchant_processing_list .section_div");
        var processing = $("#processing_graph .section_div");
        var merchant_detail_content_m = $("#merchant_detail_content_m");
        var merchant_detail_content_h = $("#merchant_detail_content_h");
        var merchant_detail_content_p = $("#merchant_detail_content_p");
        var merchant_detail_content_a = $("#merchant_detail_content_a");
        var timeout = 60000;
        var retryLimit = 1;
         var merchant_header_p = $("#merchant_header_p");
        var back_to_processing_btn = $("#back_to_processing_btn");
        var processing_layout = $("#processing_layout");
       
             cmv_button.button()
            .click(function(){
              window.open("http://cmv.cmsonline.com");
              return false;
        });
        
        //Processing Summary List
        $.ajax({
            url: "modules/charts/processing_summary_list.php",
            type: "GET",
            data:{
                agent: '<?php echo $agent; ?>'
            },
            cache: false,
            success: function(data){
                processing_summary_list.css("background", "#FFFFFF");
                processing_summary_list.html(data);
            }
        });
        
        //Merchant Processing List
        $.ajax({
            url: "modules/processing/merchant_processing_list.php",
            type: "GET",
            data:{
                agent: '<?php echo $agent; ?>'
            },
            cache: false,
            success: function(data){
                merchant_processing_list.css("background", "#FFFFFF");
                merchant_processing_list.html(data);
                $("#merchant_processing_list .data_table tr").click(function(){
                    var mid = $(this).attr("id");
                    processing_layout.hide();
                    loadMerchantDetail(mid);
                    return false;
                });
            }
        });
        
        //back to processing tab
        merchant_header_p.hide();
        back_to_processing_btn.button()
            .click(function(){
                processing_layout.show();
                merchant_header_p.hide();
                merchant_detail_content_p.hide();
            });
         
        //Processing Graph
        processing_chart = new Highcharts.Chart({
            chart: {
                renderTo: 'processing_chart_container',
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
         function loadMerchantDetail(mid){
            merchant_detail_content_m.html("");
            merchant_detail_content_h.html("");
            merchant_detail_content_a.html("");
            //clear previous merchant detail dialogs
            $(".ui-dialog").has("#case_create").remove();
            $(".ui-dialog").has("#add_note_modal").remove();
            $(".ui-dialog").has("#case_created").remove();
            $(".ui-dialog").has("#edit_merchant_modal").remove();
            merchant_header_p.show();
            merchant_detail_content_p.before("<img class='detail_spinner' src='../../cforce/images/cms_loading.gif'/>");
            
            $.ajax({
                url: "modules/merchants/merchant_detail.php?mid=" + mid,
                cache: false,
                timeout: timeout,
                tryCount: 0,
                retryLimit: retryLimit,
                success: function(data){
                    $(".detail_spinner").remove();
                    merchant_detail_content_p.html(data).show();
                }
               // error:  ajaxError
            });
        }
        
        function requestProcessingData() {
             $.ajax({
                url: 'modules/charts/processing_graph.php',
                type: "GET",
                data:{
                    agent: '<?php echo $agent; ?>'
                },
                datatype: "json",
                cache: false,
                success: function(data) {
                    processing.css("background", "#FFFFFF");
                    var dataArr = data.split(",").map(parseFloat);
                    processing_chart.series[0].setData(dataArr);
                }
            });
        };
});
</script> 
<div id="merchant_header_p">
    <button id="back_to_processing_btn"><- Back to Processing Tab</button>
</div>
<table id="processing_layout">
    <tbody>
        <tr>
            <td><button id="cmv_button">Click Here to Login to CMV</button></td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="processing_graph">
                    <div class="section_header"><p>Processing</p></div>
                    <div class="section_div">
                        <div id="processing_chart_container"></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="processing_summary_list">
                    <div class="section_header"><p>Volume Summary</p></div>
                    <div class="section_div"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                 <div id="merchant_processing_list">
                       <div class="section_header"><p>Monthly Volume</p></div>
                       <div class="section_div"></div>
                 </div>
            </td>
        </tr>
    </tbody>
</table>   
<div id ="merchant_detail_content_p"></div>
