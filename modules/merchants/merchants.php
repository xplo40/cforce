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
        var spinner = $("#spinner");
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
        var pricing_type = $("#pricing_type");
        var dba = $("#dba");
        var legal_name = $("#legal_name");
        var address = $("#address");
        var state = $("#state");
        var city = $("#city");
        var zip = $("#zip");
        var email = $("#email");
        var phone = $("#phone");
        var account_status = $("#account_status");
        var merchant_id = $("#merchant_id");
        var monthly_volume = $("#monthly_volume");
        var average_ticket = $("#average_ticket");
        var prefund = $("#prefund");
        var percentage = $("#percentage");
        var transaction_fee = $("#transaction_fee");
        var debit = $("#debit");
        var qual = $("#qual");
        var midqual = $("#midqual");
        var nonqual = $("#nonqual");
        var amex = $("#amex");
        var discover = $("#discover");
        var pin_debit = $("#pin_debit");
        var pin_debit_fee = $("#pin_debit_fee");
        var monthly_min = $("#monthly_min");
        var statement_fee = $("#statement_fee");
        var hardware = $("#hardware");
        var process_platform = $("#process_platform");
        var agent_search_field = $("#add_merchant_modal #agent_search_field");
        var agent_ID_selected = $("#agent_ID_selected");
        var agent_selected = $("#agent_selected");
        var merchant_filter = $("#merchant_filter");
        
        spinner.hide();
        
        var term_id = 0;
        var term = "";
        var count = 100;
        var index = 0;
       
       //override
       var override = false;
       var override_div = $("#override")
            .hide()
            .css({
                display: "none",
                position: "absolute",
                background: "red",
                top: "15px",
                left: "20px",
                cursor: "pointer"
            })
            .click(function(){
                if(override){
                    override = false;
                    override_div.css({
                            background: "red"
                        });
                }
                else{
                    override = true;
                    override_div.css({
                            background: "#00FF00"
                        });
                }
            });
      $("#override_hover").hover(function(){
                override_div.show();
            },function(){
                override_div.hide();
            });
            
       
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
        merchant_filter.change(function(){
            term = merchant_search.val();
            if(term == "   Search for a merchant here..."){
                term = "";
            }
            index = 0;
            term_id++;
            loadMerchantListing(term, index, term_id);
         });
        
        //add merchant
        add_merchant_modal.dialog({
            modal: true,
            autoOpen: false,
            width: 800,
            buttons: {
                "Add Merchant": function() {
                    if(override || addMerchantValidated()){
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
                               agent: agent_selected.text(),
                               agent_ID: agent_ID_selected.text(),
                               merchant_id: merchant_id.val(),
                               monthly_volume: monthly_volume.val(),
                               average_ticket: average_ticket.val(),
                               prefund: prefund.val(),
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
                               monthly_min: monthly_min.val(),
                               statement_fee: statement_fee.val(),
                               hardware: hardware.val(),
                               process_platform: process_platform.val()
                           },
                           success: function(data){
                               $("#add_merchant_modal input").val("");
                               add_merchant_modal.dialog("close");
                               alert(data);
                               loadMerchantListing("", 0, term_id);
                           }
                        });
                    }
                },
                "Cancel": function(){
                    allFields.val("");
                    $("#add_merchant_modal input[type=checkbox]").attr("checked", false),
                    agent_selected.text("");
                    agent_ID_selected.text("");
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
                merchant_listing_box.append("<img class='listing_spinner' src='../../cforce/images/cms_loading.gif'/>");
            }
            $.ajax({
                url: "modules/merchants/merchant_listing.php",
                type: "GET",
                cache: false,
                data:{
                    mode: "table",
                    term: term,
                    index: index,
                    count: count,
                    filter: merchant_filter.val(),
                    agent: '<?php echo $agent; ?>'
           
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
                        $(".data_table").off("click", "tr", clickRows);
                        $(".data_table").on("click", "tr", clickRows);
                    }
                    console.timeEnd("load merchant listing");
               }
            });
        }
        
        function clickRows(){
            var id = $(this).attr("id").substr(6);
            loadMerchantDetail(id);
            return false;
        }
        
        function loadMerchantDetail(id){
            //clear previous merchant details
            merchant_detail_content_h.html("");
            merchant_detail_content_p.html("");
            merchant_detail_content_a.html("");
            //clear previous merchant detail dialogs
            $(".ui-dialog").has("#case_create").remove();
            $(".ui-dialog").has("#add_note_modal").remove();
            $(".ui-dialog").has("#case_created").remove();
            $(".ui-dialog").has("#edit_merchant_modal").remove();
            spinner.show();
            //merchant_detail_content_m.before("<img class='detail_spinner' src='../../cforce/images/cms_loading.gif'/>");
            merchant_content.hide();
            merchant_search.hide();
            add_merchant_btn.hide();
            back_to_merchants_btn.show();
            console.time("load merchant detail");
            $.ajax({
                url: "modules/merchants/merchant_detail.php?id=" + id,
                cache: false,
                success: function(data){
                    spinner.hide();
                    merchant_detail_content_m.html(data).show();
                    console.timeEnd("load merchant detail");
                    add_case.button();
                    edit_merchant.button();
                    print_merchant.button();
                }
            });
        }
        
        agent_search_field.autocomplete({
            minLength: 2,
            source: 'modules/agents/agent_listing_cases.php',
            focus: function(event, ui) {
                agent_search_field.val(ui.item.label);
                return false;
            },
            select: function(event, ui) {
                agent_selected.text(ui.item.label);
                agent_ID_selected.text(ui.item.value);
                return false;
            }
        })
        .data("autocomplete")._renderItem = function(ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a><p>" + item.label + "</p><p>" + item.value + "</p></a>")
                .appendTo(ul);
           };
           
        function addMerchantValidated(){
            var isValid = true;
            $(".validation_error").remove();
            $("#add_merchant_modal input").css("border", "#EEE 2px solid");
            $("#add_merchant_modal p").css("border", "none");
            
            //grab values
            var dba_val = dba.val();
            var legal_name_val = legal_name.val();
            var address_val = address.val();
            var city_val = city.val();
            var state_val =state.val();
            var zip_val = zip.val();
            var account_status_val = account_status.val();
            var email_val = email.val();
            var phone_val = phone.val();
            var agent_val = agent_selected.text();
            var merchant_id_val = merchant_id.val();
            var monthly_volume_val = monthly_volume.val();
            var average_ticket_val = average_ticket.val();
            var prefund_val = prefund.val();
            var pricing_type_val = pricing_type.val();
            var percentage_val = percentage.val();
            var transaction_fee_val = transaction_fee.val();
            var debit_val = debit.val();
            var qual_val = qual.val();
            var midqual_val = midqual.val();
            var nonqual_val = nonqual.val();
            var amex_val = amex.is(":checked");
            var discover_val = discover.is(":checked");
            var pin_debit_val = pin_debit.is(":checked");
            var pin_debit_fee_val = pin_debit_fee.val();
            var monthly_min_val = monthly_min.val();
            var statement_fee_val = statement_fee.val();
            var hardware_val = hardware.val();
            var process_platform_val = process_platform.val();
                               
            //required inputs
            if(!legal_name_val){
                isValid = false;
                showValidationError(legal_name, "missing legal name");
            }
            if(!address_val){
                isValid = false;
                showValidationError(address, "missing address");
            }
            if(!city_val){
                isValid = false;
                showValidationError(city, "missing city");
            }
            if(!state_val){
                isValid = false;
                showValidationError(state, "missing state");
            }
            if(!zip_val){
                isValid = false;
                showValidationError(zip, "missing zip");
            }
            if(!account_status_val){
                isValid = false;
                showValidationError(account_status, "missing account status");
            }
            if(!phone_val){
                isValid = false;
                showValidationError(phone, "missing phone");
            }
            if(!agent_val){
                isValid = false;
                showValidationError(agent_selected, "missing agent");
            }
            if(!monthly_volume_val){
                isValid = false;
                showValidationError(monthly_volume, "missing monthly volume");
            }
            if(!average_ticket_val){
                isValid = false;
                showValidationError(average_ticket, "missing average ticket");
            }
            if(!pricing_type_val){
                isValid = false;
                showValidationError(pricing_type, "missing pricing type");
            }
            if(!transaction_fee_val){
                isValid = false;
                showValidationError(transaction_fee, "missing transaction fee");
            }
            if(!monthly_min_val){
                isValid = false;
                showValidationError(monthly_min, "missing monthly min");
            }
            if(!statement_fee_val){
                isValid = false;
                showValidationError(statement_fee, "missing statement fee");
            }
            if(!process_platform_val){
                isValid = false;
                showValidationError(process_platform, "missing platform");
            }
            
            //conditionally required inputs
            if(account_status_val == 'Boarded' && !merchant_id_val){
                isValid = false;
                showValidationError(merchant_id, "missing merchant id");
            }
            if(pricing_type_val == '1' && !percentage_val){
                isValid = false;
                showValidationError(percentage, "missing percentage");
            }
            if(pricing_type_val == '2'){
                if(!debit_val){
                    isValid = false;
                    showValidationError(debit, "missing debit");
                }
                if(!qual_val){
                    isValid = false;
                    showValidationError(qual, "missing qual");
                }
                if(!midqual_val){
                    isValid = false;
                    showValidationError(midqual, "missing midqual");
                }
                if(!nonqual_val){
                    isValid = false;
                    showValidationError(nonqual, "missing nonqual");
                }
            }
            if(pin_debit_val && !pin_debit_fee_val){
                isValid = false;
                showValidationError(pin_debit_fee, "missing pin debit fee");
            }
            
            //length checks
            if(dba_val.length > 60){
                isValid = false;
                showValidationError(dba, "too long");
            }
            if(legal_name_val.length > 60){
                isValid = false;
                showValidationError(legal_name, "too long");
            }
            if(address_val.length > 60){
                isValid = false;
                showValidationError(address, "too long");
            }
            if(city_val.length > 25){
                isValid = false;
                showValidationError(city, "too long");
            }
            if(state_val.length > 15){
                isValid = false;
                showValidationError(state, "too long");
            }
            if(zip_val.length > 10){
                isValid = false;
                showValidationError(zip, "too long");
            }
            if(email_val.length > 70){
                isValid = false;
                showValidationError(email, "too long");
            }
            if(phone_val.length > 20){
                isValid = false;
                showValidationError(phone, "too long");
            }
            if(monthly_volume_val.length > 19){
                isValid = false;
                showValidationError(monthly_volume, "too long");
            }
            if(average_ticket_val.length > 19){
                isValid = false;
                showValidationError(average_ticket, "too long");
            }
            if(prefund_val.length > 19){
                isValid = false;
                showValidationError(prefund, "too long");
            }
            if(transaction_fee_val.length > 19){
                isValid = false;
                showValidationError(transaction_fee, "too long");
            }
            if(percentage_val.length > 6){
                isValid = false;
                showValidationError(percentage, "too long");
            }
            if(debit_val.length > 6){
                isValid = false;
                showValidationError(debit, "too long");
            }
            if(qual_val.length > 6){
                isValid = false;
                showValidationError(qual, "too long");
            }
            if(midqual_val.length > 6){
                isValid = false;
                showValidationError(midqual, "too long");
            }
            if(nonqual_val.length > 6){
                isValid = false;
                showValidationError(nonqual, "too long");
            }
            if(monthly_min_val.length > 19){
                isValid = false;
                showValidationError(monthly_min, "too long");
            }
            if(statement_fee_val.length > 19){
                isValid = false;
                showValidationError(statement_fee, "too long");
            }
            if(hardware_val.length > 20){
                isValid = false;
                showValidationError(hardware, "too long");
            }

            
            //format validation
            //$.isNumeric("-10");
            ////dba: dba.val(),
            //email: email.val(),
            //amex: amex.is(":checked"),
            //discover: discover.is(":checked"),
            //pin_debit: pin_debit.is(":checked"),
            //hardware: hardware.val(),
            /*if(dba.val() == 'TEST'){
                isValid = false;
            }*/
            return isValid;
        }
        
        function showValidationError(element, error){
            $("<div class='validation_error'><p>" + error + "</p></div>")
                .insertAfter(element)
                .fadeIn()
                .delay(2000)
                .fadeOut();
            if(element == agent_selected){
                agent_search_field.css("border", "1px solid red");
            }
            else{
                element.css("border", "1px solid red");
            }
            
        }
    });
   
</script>

<div id="merchant_header_m">
    <button id="back_to_merchants_btn">Back to Merchant Tab</button>
    <input id="merchant_search" type="text" value="   Search for a merchant here..." class="default"></input>
    <!--<select id="merchant_filter">
        <option value="" selected="selected">Choose a status</option>
        <option value="Received">Received</option>
        <option value="Approved">Approved</option>
        <option value="Pending">Pending</option>
        <option value="Boarded">Boarded</option>
        <option value="Live">Live</option>
        <option value="Declined">Declined</option>
        <option value="Closed">Closed</option>
    </select>-->
<?php if($_SESSION['department'] >= 10): ?>
    <button id="add_merchant_btn">Add Merchant</button>
<?php endif; ?>
</div>
<div id="merchant_content">
    <div id="infobox">  
        <div class="section_header"><p>Stats</p></div>
        <img id="fire1" src="../../cforce/images/fire1.png">
        <img id="fire2" src="../../cforce/images/fire2.png">
        <img id="fire3" src="../../cforce/images/fire3.png">
    </div>
    <div id="merchant_listing_box">  
        <div class="section_header"><p>Merchant List</p></div>
        <table id="merchant_listing" class="data_table">
            <tr>
                <th style="width:110px">MID</th>
                <th style="width:210px">Name</th>
               <!-- <th style="width:130px">Contact</th> -->
                <th style="width:180px">Email</th>
                <th style="width:85px">Phone</th>
            </tr>
        </table>   
    </div>
</div>

<div id='merchant_detail_content_m'></div>

<div id='add_merchant_modal' title="Add Merchant"> 
    <table>
        <tr>
            <th id="override_hover" style="width:80px !important">DBA<div id="override"><span class="ui-icon ui-icon-key"></span></div></th>
            <td style="width:210px !important">
                <input type="text" id="dba"/>
            </td>
            <th>Legal Name</th>
            <td colspan="3">
                <input type="text" id="legal_name">
            </td>
        </tr>
        <tr>
            <th>Address</th>
            <td colspan="5">
                <input type="text" id="address"/>
            </td>
        </tr>
        <tr>
            <th>City</th>
            <td>
                <input type="text" id="city"/>
            </td>
            <th style="width:100px !important">State</th>
            <td style="width:100px !important">
                <input type="text" id="state" style="width:90px !important"/>
            </td>
            <th style="width:100px !important">Zip</th>
            <td style="width:100px !important">
                <input type="text" id="zip" style="width:90px !important"/>
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td>
                <input type="text" id="email"/>
            </td>
            <th>Phone</th>
            <td colspan="3">
                <input type="text" id="phone"/>
            </td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <select id="account_status">
                    <option value="Received" selected="selected">Received</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                    <option value="Boarded">Boarded</option>
                    <option value="Live">Live</option>
                    <option value="Declined">Declined</option>
                    <option value="Closed">Closed</option>
                </select>
            </td>
            <th class="midrow">Merchant ID</th>
            <td class="midrow" colspan="3">
                <input type="text" id="merchant_id"/>
            </td>
        </tr>
        <tr>
            <th>Rep Name</th>
            <td>
                <input type="text" id="agent_search_field"/>
            </td>
            <td></td>
            <td colspan="3">
                <p id='agent_selected'></p>
                <p id='agent_ID_selected'></p>
            </td>
         </tr>
        </tr>
        <tr>          
            <th>Monthly Volume</th>
            <td>
                <input type="text" id="monthly_volume"/>
            </td>
            <th>Average Ticket</th>
            <td>
                <input type="text" id="average_ticket"/>
            </td>
            <th>Pre-fund</th>
            <td>
                <input type="text" id="prefund"/>
            </td>
        </tr>
        <tr>
            <th>Pricing Type</th>
            <td>
                <select id="pricing_type">
                    <option value="0" selected="selected"> Please Select</option>
                    <option value="1" >Interchange Plus</option>
                    <option value="2">Tiered</option>
                </select>
            </td>
            <th>Transaction Fee</th>
            <td colspan="3">
                <input type="text" id="transaction_fee"/>
            </td>
        </tr>
        <tr class="interchange_row">
            <th>Percentage</th>
            <td>
                <input type="text" id="percentage"/>
            </td>
            <th></th>
            <td colspan="3"></td>
        </tr>
        <tr class="tiered_row1">
            <th>Debit</th>
            <td>
                <input type="text" id="debit"/>
            </td>
            <th>Qual</th>
            <td colspan="3">
                <input type="text" id="qual"/>
            </td>
        </tr>
         <tr class="tiered_row2">
            <th>Mid Qual</th>
            <td>
                <input type="text" id="midqual"/>
            </td>
            <th>Non Qual</th>
            <td>
                <input type="text" id="nonqual"/>
            </td>
        </tr>
        <tr>
            <th>Amex</th>
            <td>
                <input type="checkbox" name="cardtype" id="amex" value="amex"></input>
            </td>
            <th>Discover</th>
            <td colspan="3">
                <input type="checkbox" name="cardtype" id="discover" value="discover"></input>
            </td>
       </tr>
       <tr>
           <th>Pin Debit</th>
           <td>
                <input type="checkbox" name="cardtype" id="pin_debit" value="pin_debit"></input>
           </td>
           <th class="pindebit_row">Pin Trans Fee</th>
           <td class="pindebit_row" colspan="3">
              <input type="text" id="pin_debit_fee"/>
           </td>
       </tr>
       <tr>
           <th>Monthly Min</th>
           <td>
                <input type="text" id="monthly_min"/>
           </td>
           <th>Statement Fee</th>
           <td colspan="3">
               <input type="text" id="statement_fee"/>
           </td>
        </tr>
        <tr>
           <th>Hardware</th>
           <td>
                <input type="text" id="hardware"/>
           </td>
           <th>Platform</th>
           <td colspan="3">
                <select id="process_platform">
                    <option value="0" selected="selected">Omaha</option>
                    <option value="1">Nashville</option>
                    <option value="2">CardNet (North)</option>
                    <option value="3">Buypass</option>
                    <option value="4">Global</option>
                </select>
            </td>
       </tr>
    </table>
</div>
<a href="#" class="to_top">
    Back to top
</a>
<div id="spinner">
    <img class='detail_spinner' src='../../cforce/images/cms_loading.gif'/>"
</div>
                
                