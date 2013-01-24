<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
?>
<script type="text/javascript">
    $(document).ready(function(){
        //selectors
        var merchant_detail_content_m = $("#merchant_detail_content_m");
        var merchant_detail_content_h = $("#merchant_detail_content_h");
        var merchant_detail_content_p = $("#merchant_detail_content_p");
        var merchant_detail_content_a = $("#merchant_detail_content_a");
        var merchant_search = $("#merchant_header_m #merchant_search");
        var back_to_merchants_btn = $("#back_to_merchants_btn");
        var merchant_content = $("#merchant_content");
        var add_case = $("#add_case");
        var edit_merchant = $("#edit_merchant");
        var print_merchant = $("#print_merchant");
        var add_merchant_btn = $("#add_merchant_btn");
        var add_merchant_modal = $("#add_merchant_modal");
        var allFields = $("#add_merchant_modal input");
        
        var merchant_listing_box = $("#merchant_listing_box");
        var pricing_type= $("#pricing_type");
        var dba = $("#dba");
        var legal_name= $("#legal_name");
         var address= $("#address");
        var state= $("#state");
        var city = $("#city");
         var zip= $("#zip");
         var email = $("#email");
        var phone = $("#phone");
        var account_status= $("#account_status");
         var rep_name= $("#rep_name");
        var sales_rep_code= $("#sales_rep_code");
         var merchant_id= $("#merchant_id");
        var monthly_volume= $("#monthly_volume");
        var avg_ticket = $("#avg_ticket");
         var surcharge= $("#surcharge");
         var percent = $("#percent");
         var transaction_fee= $("#transaction_fee");
         var debit= $("#debit");
        var qual= $("#qual");
        var midqual = $("#midqual");
         var nonqual= $("#nonqual");
         var amex = $("#amex");
        var discover= $("#discover");
         var pin_debit= $("#pin_debit");
        var pin_debit_fee= $("#pin_debit_fee");
         var monthly_min= $("#monthly_min");
        var monthly_volume= $("#monthly_volume");
        var statement_fee= $("#statement_fee");
          var hardware= $("#hardware");
        var process_platform= $("#process_platform");
        
         var term_id = 0;
        var term = "";
        var count = 100;
        var index = 0;
       
       //hide certain rows
        hideAllrows = function () {
            $(".interchange_row, .transfee_row, .tiered_row1, .tiered_row2").hide();
        };

        
        handleNewSelection = function () {
            hideAllrows();

            switch ($(this).val()) {
                case '1':
                    $(".interchange_row").show();
                    $(".transfee_row").show();
                break;
                case '2':
                    $(".transfee_row").show();
                    $(".tiered_row1").show();
                    $(".tiered_row2").show();
                break;
            }
        };



    $("#pricing_type").change(handleNewSelection);

    // Run the event handler once now to ensure everything is as it should be
    handleNewSelection.apply($("#pricing_type"));
    
    hidemidrow = function () {
            $(".midrow").hide();
    };
    
    midSelection= function(){
        hidemidrow();
         switch ($(this).val()) {
             case 'Received':
              $(".midrow").hide();
             break;
              case 'Approved':
              $(".midrow").hide();
             break;
              case 'Pending':
              $(".midrow").hide();
             break;
             case 'Boarded':
                    $(".midrow").show();
                  
                break;
              case 'Live':
              $(".midrow").hide();
             break;
               case 'Declined':
              $(".midrow").hide();
             break;
             case 'Closed':
              $(".midrow").hide();
             break;
             
            }
        
    };
    
    $("#account_status").change(midSelection);

    // Run the event handler once now to ensure everything is as it should be
    midSelection.apply($("#account_status"));
    
    $('.pindebit_row').hide();
    
   $('#pin_debit').change(function() {
    if ($(this).is(":checked")) {
        $('.pindebit_row').show('fast');
    } else {
        $('.pindebit_row').hide('fast');
    }
});
        //initialize
        console.time("load merchant listing");
        merchant_detail_content_m.hide();
        loadMerchantListing(term, index, term_id);
        
        //merchant search
        merchant_search.keyup( function() {
            term = $(this).val();
            index = 0;
            term_id++;
            loadMerchantListing(term, index, term_id);
        });
        merchant_search.on('paste', function(event) { 
            term = $(this).val();
            index = 0;
            term_id++;
            loadMerchantListing(term, index, term_id);
        });
        merchant_search.click(function(){
            if(merchant_search.hasClass("default")){
                merchant_search.val("")
                    .removeClass("default");
            }
            return false;
        });
        merchant_search.blur(function(){
            if(merchant_search.val() == ""){
                merchant_search.addClass("default")
                    .val("   Search for a merchant here...");
            }
            return false;
        });
        
        //add merchant
        add_merchant_modal.dialog({
            modal: true,
            autoOpen: false,
            width: 700,
            buttons: {
                "Add Merchant": function() {
                     $.ajax({
                        url: "modules/merchants/add_merchant.php",
                        type: "GET",
                        cache: false,
                        data:{
                            dba: dba.val(),
                            legal_name: legal_name.val(),
                            address: address.val(),
                            city: city.val(),
                            state:state.val(),
                            zip: zip.val(),
                            account_status: account_status.val(),
                            email: email.val(),
                            phone: phone.val(),
                            rep_name: rep_name.val(),
                            sales_rep_code: sales_rep_code.val(),
                            merchant_id: merchant_id.val(),
                            monthly_volume: monthly_volume.val(),
                            avg_ticket: avg_ticket.val(),
                            pricing_type: pricing_type.val(),
                            surcharge: surcharge.val(),
                            percent: percent.val(),
                            transaction_fee: transaction_fee.val(),
                            debit: debit.val(),
                            qual: qual.val(),
                            midqual: midqual.val(),
                            nonqual: nonqual.val(),
                            amex: amex.val(),
                            discover: discover.val(),
                            pin_debit: pin_debit.val(),
                            pin_debit_fee: pin_debit_fee.val(),
                            monthly_min: monthly_min.val(),
                            statement_fee: statement_fee.val(),
                            hardware: hardware.val(),
                            process_platform: process_platform.val()
                        },
                        success: function(data){
                            add_merchant_modal.dialog("close");
                        }
                     });
                },
                "Cancel": function(){
                    allFields.val("");
                    add_merchant_modal.dialog("close");
                }
            }
        });
        
        add_merchant_btn.button()
            .click(function(){
                add_merchant_modal.dialog("open");
                return false;
            });
        
        //back to merchant listing
        back_to_merchants_btn.button({
                icons: {
                    primary: 'ui-icon-triangle-1-w'
                }
            })
            .hide()
            .click(function(){
                merchant_content.show();
                merchant_search.show();
                add_merchant_btn.show();
                back_to_merchants_btn.hide();
                merchant_detail_content_m.hide();
            });
      
        function loadMerchantListing(term, index, term_id_t){
            if(index == 0){
                $(".listing_spinner").remove();
                merchant_listing_box.append("<img class='listing_spinner' src='../../images/cms_loading.gif'/>");
            }
            $.ajax({
                url: "modules/merchants/merchant_listing.php",
                type: "GET",
                cache: false,
                data:{
                    mode: "table",
                    term: term,
                    index: index,
                    count: count
                },
                success: function(data){
                    $(".listing_spinner").remove();
                    if(term_id == term_id_t){
                        if(index == 0){
                            $("#merchant_listing tr:has(td)").remove();
                        }
                        $("#merchant_listing tr:last-child").after(data);
                        
                        //keep loading until no data is returned
                        if(data.length > 0){
                            loadMerchantListing(term, index + 100, term_id_t);
                        }
                        else{
                            term_id = 0;
                        }
                        //add click listener to rows
                        $(".data_table tr").click(function(){
                            var mid = $(this).attr("id").substr(6);
                            loadMerchantDetail(mid);
                            return false;
                        }); 
                    }
                    console.timeEnd("load merchant listing");
               }
            });
        }
        
        function loadMerchantDetail(mid){
            merchant_detail_content_h.html("");
            merchant_detail_content_p.html("");
            merchant_detail_content_a.html("");
            merchant_detail_content_m.before("<img class='detail_spinner' src='../../images/cms_loading.gif'/>");
            merchant_content.hide();
            merchant_search.hide();
            add_merchant_btn.hide();
            back_to_merchants_btn.show();
            $.ajax({
                url: "modules/merchants/merchant_detail.php?mid=" + mid,
                cache: false,
                success: function(data){
                    $(".detail_spinner").remove();
                    merchant_detail_content_m.html(data).show();
                    
                    add_case.button();
                    edit_merchant.button();
                    print_merchant.button();
                }
            });
        }
         $("#add_merchant_modal #agent_search_field").autocomplete({
            minLength: 2,
            source: 'modules/agents/agent_listing_cases.php',
            focus: function(event, ui) {
                $("#agent_search_field").val(ui.item.label);
                return false;
            },
            select: function(event, ui) {
                $("#agent_selected").text(ui.item.label);
                $("#agent_ID_selected").text(ui.item.value);
                //$("#merchant").val(ui.item.value);
                //$("#merchantname").val(ui.item.label);
                return false;
            }
        })
        .data("autocomplete")._renderItem = function(ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a><p>" + item.label + "</p><p>" + item.value + "</p></a>")
                .appendTo(ul);
           };
    });
   
</script>

<div id="merchant_header_m">
    <button id="back_to_merchants_btn">Back to Merchant Tab</button>
    <input id="merchant_search" type="text" value="   Search for an merchant here..." class="default"></input>
<?php if($_SESSION['department'] >= 10): ?>
    <button id="add_merchant_btn">Add Merchant</button>
<?php endif; ?>
</div>




<div id='quotinator' title="Quotinator"> 
    <div class="section_header"><p>Cost Comparison and Rate Quote Analysis</p></div>
    <table>
        <tr>
            <th>DBA</th>
            <td>
                <input type="text" id="dba"/>
            </td>
           <td></td>
                <th>Address</th>
            <td>
                <input type="text" id="address">
            </td>
        </tr>
        <tr>
            <th>Owner</th>
            <td>
                <input type="text" id="owner"/>
            </td>
            <td></td>
            <th>City</th>
            <td>
                <input type="text" id="City"/>
            </td>
        </tr>
         <tr>
            <th>Office Phone</th>
            <td>
                <input type="text" id="phone"/>
            </td>
            <td></td>
            <th>State</th>
            <td>
                <input type="text" id="state"/>
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td>
                <input type="text" id="email"/>
            </td>
            <td></td>
            <th>Zip</th>
            <td>
                <input type="text" id="zip"/>
            </td>
        </tr>
        </table>
    </div>
        
    <div id=createdby> 
    <div class="section_header"><p>Rate Quote Creation Information</p></div>
         <table>
        <tr>
            <th>Quote Created By</th>
            <td>
                <input type="text" id="Created By"/>
            </td>
           <td></td>
                <th>Created For</th>
            <td>
                <input type="text" id="address">
            </td>
        </tr>
        </table>
    </div>


<div id='creditcardactivity' title="cc_activity"> 
    <div class="section_header"><p>All Credit Card Activity</p></div>
    <table>
        <tr>
            <th>Month1</th><<td></td><th>Month2</th><td></td><th>Month3</th>
        </tr>
        <tr>
            <th>Total Monthly Volume</th>
            <td>
                <input type="text" id="total_monthly_volume_month_1"/>
            </td>
           <td></td>
                <th>Total Monthly Volume</th>
            <td>
                <input type="text" id="total_monthly_volume_month_2">
            </td>
            <td></td>
                <th>Total Monthly Volume</th>
            <td>
                <input type="text" id="total_monthly_volume_month_3">
            </td>
        </tr>
         <tr>
            <th>Total Transactions</th>
            <td>
                <input type="text" id="total_transactions_month_1"/>
            </td>
           <td></td>
                <th>Total Transactions</th>
            <td>
                <input type="text" id="total_transactions_month_2">
            </td>
            <td></td>
                <th>Total Transactions</th>
            <td>
                <input type="text" id="total_transactions_month_3">
            </td>
        </tr>
        <tr>
            <th>Average Ticket</th>
            <td>
                <input type="text" id="average_ticket_month_1"/>
            </td>
            <td></td>
                <th>Average Ticket</th>
            <td>
                <input type="text" id="average_ticket_month_2">
            </td>
            <td></td>
                <th>Average Ticket</th>
            <td>
                <input type="text" id="average_ticket_month_3">
            </td>
        </tr>
        <?php
         $avgvolume = (($total_monthly_volume_month_1 + $total_monthly_volume_month_1 + $total_monthly_volume_month_1) / 3);
         $avgtransactions= (($total_transactions_month_1 + $total_transactions_month_2 + $total_transactions_month_3) / 3);
        ?>
         <tr>
            <th>Average Monthly Volume</th>
            <td>
                <input type="text" value="<?php echo $avgvolume ?>"/>
            </td>
            <td></td>
                <th>Average Ticket</th>
            <td>
                <input type="text" value="<?php echo $avgtransactions ?>">
            </td>
    </table>
</div>
    

<a href="#" class="to_top">
    Back to top
</a>
                
                