<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
?>
<tr>
    <td>
        <div id="dda_action_request">
            <script type="text/javascript">
                $(document).ready(function(){
                    //selectors
                    var dda_merchant_search = $("#dda_merchant_search");
                    var dda_merchant_name = $("#dda_merchant_name");
                    var dda_mid = $("#dda_mid");
                    var dda_merchant_name_val = $("#dda_merchant_name_val");
                    var dda_mid_val = $("#dda_mid_val");
                    var dda_reject_date = $("#dda_reject_date");
                    var dda_write_off_date = $("#dda_write_off_date");
                    var dda_transfer_from = $("#dda_transfer_from");
                    var dda_amount = $("#dda_amount");
                    var dda_from_account = $("#dda_from_account");
                    var dda_to_account = $("#dda_to_account");
                    var dda_type = $("#dda_type");
                    var dda_reason = $("#dda_reason");
                    var dda_descriptor = $("#dda_descriptor");
                    var dda_save_and_export = $("#dda_save_and_export");
                    
                    
                    dda_reject_date.datepicker();
                    dda_write_off_date.datepicker();
                    dda_save_and_export.button()
                        .click(function(){
                            dda_merchant_name_val.val() = dda_merchant_name.val();
                            dda_mid_val.val() = dda_mid.val();
                            /*$.ajax({
                                url: "modules/tools/dda_action_save_and_export.php",
                                cache: false,
                                data: {
                                    merchant_name: dda_merchant_name.text(),
                                    mid: dda_mid.text(),
                                    reject_date: dda_reject_date.val(),
                                    write_off_date: dda_write_off_date.val(),
                                    transfer_from: dda_transfer_from.val(),
                                    amount: dda_amount.val(),
                                    from_account: dda_from_account.val(),
                                    to_account: dda_to_account.val(),
                                    type: dda_type.val(),
                                    reason: dda_reason.val(),
                                    descriptor: dda_descriptor.val()
                                },
                                success: function(data){
                                    if(data){
                                        alert(data);
                                    }
                                    //window.open("modules/tools/download_pdf.php", "_blank");
                                }
                            });*/
                            $(this).parent().submit();
                            return false;
                        });
                        
                    //merchant search autocomplete
                    dda_merchant_search.autocomplete({
                        minLength: 2,
                        source: 'modules/cases/case_merchant_select.php',
                        focus: function(event, ui) {
                            dda_merchant_search.val(ui.item.label);
                            return false;
                        },
                        select: function(event, ui) {
                            dda_merchant_name.text(ui.item.label);
                            dda_mid.text(ui.item.value);

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
            <div class="section_header"><p>DDA Action Request</p></div>
            <div class="section_div">
                <form id='dda_action_request_form' method='GET' action='modules/tools/dda_action_save_and_export.php' target="_blank">
                    <table>
                        <tbody>
                            <tr>
                                <th>Reject Date</th>
                                <td><input id="dda_reject_date" name="dda_reject_date"type="text"></input></td>
                                <th>Write Off Date</th>
                                <td><input id="dda_write_off_date" name="dda_write_off_date"type="text"></input></td>
                            </tr>
                            <tr>
                                <th>Merchant Search</th>
                                <td><input id="dda_merchant_search" type="text"></input></td>
                                <th>Merchant</th>
                                <td>
                                    <h3 id="dda_merchant_name"></h3>
                                    <h5 id="dda_mid"></h5>
                                    <input type="hidden" id="dda_merchant_name_val" name="dda_merchant_name_val"></input>
                                    <input type="hidden" id="dda_mid_val" name="dda_mid_val"></input>
                                </td>
                            </tr>
                            <tr>
                                <th>Transfer from</th>
                                <td>
                                    <select id="dda_transfer_from" name="dda_transfer_from">
                                        <option value="0">Reserve to DDA</option>
                                        <option value="1">DDA to Reserve</option>
                                    </select>
                                </td>
                                <th>Amount</th>
                                <td><input id="dda_amount" name="dda_amount" type="text"></input></td>
                            </tr>
                            <tr>
                                <th>From Account #</th>
                                <td><input id="dda_from_account" name="dda_from_account" type="text"></input></td>
                                <th>To Account #</th>
                                <td><input id="dda_to_account" name="dda_to_account" type="text"></input></td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>
                                    <select id="dda_type" name="dda_type">
                                        <option value="0">3rd ACCT REJECT</option>
                                        <option value="1">ACH REJECT FEE</option>
                                        <option value="2">FEE RECOVERY</option>
                                        <option value="3">RECOVERY</option>
                                        <option value="4">REJECT</option>
                                    </select>
                                </td>
                                <th>Reason</th>
                                <td>
                                    <select id="dda_reason" name="dda_reason">
                                        <option value="0"></option>
                                        <option value="1">Insufficient Funds</option>
                                        <option value="2">Account Closed</option>
                                        <option value="3">No Account/Unable to Locate</option>
                                        <option value="4">Account Frozen</option>
                                        <option value="5">Payment Stopped</option>
                                        <option value="6">Unpaid Monthly Fees</option>
                                        <option value="7">Corp. Not Auth.</option>
                                        <option value="8">Invalid Account Number</option>
                                        <option value="9">Acct. Sold to Other DFI</option>
                                        <option value="10">Uncollected Funds</option>
                                        <option value="11">Unauth. DB using Corp. SEC</option>
                                        <option value="12">Auth. Revoked</option>
                                        <option value="13">Non-transaction acct.</option>
                                        <option value="14">Reject from Closed Acct.</option>
                                        <option value="15">RDFI Not Qualified</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Descriptor</th>
                                <td><input id="dda_descriptor" name="dda_descriptor" type="text"></input></td>
                                <th></th>
                                <td><button id="dda_save_and_export">Save and Export</button></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </td>
</tr>