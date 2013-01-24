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
<script type="text/javascript" src="include/jquery.cookie.js"></script>
<script type="text/javascript" src="include/weather.js"></script>

<script type="text/javascript">
    var processing_chart;
    var boarded_chart;
   
    $(document).ready(function(){
       
        //selectors
        var news_content = $("#news .section_div");
        var merchant_table = $("#merchant_list .section_div");
        var processing_summary_list = $("#processing_summary_list .section_div");
        var boarded = $("#boarded .section_div");
        var processing = $("#processing .section_div");
        var merchant_detail_content_m = $("#merchant_detail_content_m");
        var merchant_detail_content_h = $("#merchant_detail_content_h");
        var merchant_detail_content_p = $("#merchant_detail_content_p");
        var merchant_detail_content_a = $("#merchant_detail_content_a");
        var home_layout = $("#home_layout");
        var timeout = 60000;
        var retryLimit = 1;
        //var add_case = $("#add_case");
        //var edit_merchant = $("#edit_merchant");
        //var print_merchant = $("#print_merchant");
        var back_to_home_btn = $("#back_to_home_btn");
        var merchant_header_h = $("#merchant_header_h");
               
        //News 
        $.ajax({
            url: "modules/home/news.php",
            cache: false,
            timeout: timeout,
            tryCount: 0,
            retryLimit: retryLimit,
            success: function(data){
                news_content.css("background", "#FFFFFF");
                news_content.html(data);
            },
            error:  ajaxError
        });
     
        //Top Merchants List
         $.ajax({
            url: "modules/charts/top_merchants_list.php",
            type: "GET",
            data:{
                agent: '<?php echo $agent; ?>'
            },
            cache: false,
            timeout: timeout,
            tryCount: 0,
            retryLimit: retryLimit,
            success: function(data){
                merchant_table.css("background", "#FFFFFF");
                merchant_table.html(data);
                $("#merchant_list .data_table tr").click(function(){
                    var mid = $(this).attr("id");
                    loadMerchantDetail(mid);
                    return false;
                });
            },
            error:  ajaxError
        });
        
        //back to home tab
        merchant_header_h.hide();
        back_to_home_btn.button()
            .click(function(){
                home_layout.show();
                merchant_header_h.hide();
                merchant_detail_content_h.hide();
            });
          
        function loadMerchantDetail(mid){
            merchant_detail_content_m.html("");
            merchant_detail_content_p.html("");
            merchant_detail_content_a.html("");
            //clear previous merchant detail dialogs
            $(".ui-dialog").has("#case_create").remove();
            $(".ui-dialog").has("#add_note_modal").remove();
            $(".ui-dialog").has("#case_created").remove();
            $(".ui-dialog").has("#edit_merchant_modal").remove();
            merchant_header_h.show();
            merchant_detail_content_h.before("<img class='detail_spinner' src='../../cforce/images/cms_loading.gif'/>");
            home_layout.hide();
            $.ajax({
                url: "modules/merchants/merchant_detail.php?mid=" + mid,
                cache: false,
                timeout: timeout,
                tryCount: 0,
                retryLimit: retryLimit,
                success: function(data){
                    $(".detail_spinner").remove();
                    merchant_detail_content_h.html(data).show();
                },
                error:  ajaxError
            });
        }
        
        //Processing Summary List
        $.ajax({
            url: "modules/charts/processing_summary_list.php",
            type: "GET",
            data:{
                agent: '<?php echo $agent; ?>'
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
        
        //Merchant Status pie chart
        new Highcharts.Chart({
            chart: {
                renderTo: 'merchant_status_chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                width: 475,
                height: 298
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
        
        //Processing Graph
        processing_chart = new Highcharts.Chart({
            chart: {
                renderTo: 'processing_chart',
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
        
        boarded_chart = new Highcharts.Chart({
            chart: {
                renderTo: 'boarded_chart',
                events: {
                    load: requestBoardedData
                },
                width: 475,
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
        
        function requestProcessingData() {
             $.ajax({
                url: 'modules/charts/processing_graph.php',
                type: "GET",
                data:{
                    agent: '<?php echo $agent; ?>'
                },
                datatype: "json",
                cache: false,
                timeout: timeout,
                tryCount: 0,
                retryLimit: retryLimit,
                success: function(data) {
                    processing.css("background", "#FFFFFF");
                    var dataArr = data.split(",").map(parseFloat);
                    processing_chart.series[0].setData(dataArr);
                },
                error:  ajaxError
            });
        }
        
        function requestBoardedData() {
             $.ajax({
                url: 'modules/charts/boarded_graph.php',
                type: "GET",
                data:{
                    agent: '<?php echo $agent; ?>'
                },
                datatype: "json",
                cache: false,
                timeout: timeout,
                tryCount: 0,
                retryLimit: retryLimit,
                success: function(data) {
                    boarded.css("background", "#FFFFFF");
                    var dataArr = data.split(",").map(parseFloat);
                    boarded_chart.series[0].setData(dataArr);
                },
                error:  ajaxError
            });
        };
        
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

 

<div id="merchant_header_h">
    <button id="back_to_home_btn"><- Back to Home Tab</button>
</div>
<table id="home_layout" class="detail_layout">
    <tbody>
        <tr>
            <td colspan="2">
                <div id="news">
                    <div class="section_header"><p>News</p></div>
                    <div class="section_div"></div>
                 
		
       

        </div>

            </td>
        </tr>
        <tr>
            <td>
                <div id="merchant_status">
                    <div class="section_header"><p>Merchant Status</p></div>
                    <div class="section_div">
                        <div id="merchant_status_chart"></div>
                    </div>
                </div>
            </td>
            <td>
                <div id="cases">
                    <div class="section_header"><p>Cases</p></div>
                    <div class="section_div">
                        <?php include "../cases/cases.php"; ?>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="merchant_list">
                    <div class="section_header"><p>Top Merchant List YTD</p></div>
                    <div class="section_div"></div>
                </div>
            </td>
            <td>
                <div id="boarded">
                    <div class="section_header"><p>Merchants Boarded</p></div>
                    <div class="section_div">
                        <div id="boarded_chart"></div>
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
                <div id="processing">
                    <div class="section_header"><p>Processing</p></div>
                    <div class="section_div">
                        <div id="processing_chart"></div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>   

<div id='merchant_detail_content_h'></div>