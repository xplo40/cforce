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
        var agent_detail_content = $("#agent_detail_content");
        var add_agent_btn = $("#add_agent_btn");
        var add_agent_modal = $("#add_agent_modal");
        var agent_search = $("#agent_search");
        var back_to_agents_btn = $("#back_to_agents_btn");
        var agent_content = $("#agent_content");
        var add_case = $("#add_case")
        var edit_agent = $("#edit_agent");
        var print_agent = $("#print_agent");
        
        var name = $("#name");
        var company = $("#company");
        var address = $("#address");
        var city = $("#city");
        var state = $("#state");
        var zip = $("#zip");
        var email = $("#email");
        var phone = $("#phone");
        var access = $("#access");
        var repcode_low = $("#repcode_low");
        var repcode_high = $("#repcode_high");
        var com_low = $("#com_low");
        var com_high = $("#com_high");
        var allFields = $("#add_agent_modal input");
        var agent_listing_box = $("#agent_listing_box");
        
        //initial vars
        var term_id = 0;
        var term = "";
        var index = 0;
        
        //initialize
        loadAgentListing(term, index, term_id)
        agent_detail_content.hide();
        
        //agent search
        agent_search.keyup( function() {
            term = $(this).val();
            index = 0;
            term_id++;
            loadAgentListing(term, index, term_id);
        });
        agent_search.on('paste', function(event) { 
            term = $(this).val();
            index = 0;
            term_id++;
            loadAgentListing(term, index, term_id);
        });
        agent_search.click(function(){
            if(agent_search.hasClass("default")){
                agent_search.val("")
                    .removeClass("default");
            }
            return false;
        });
        agent_search.blur(function(){
            if(agent_search.val() == ""){
                agent_search.addClass("default")
                    .val("   Search for an agent here...");
            }
            return false;
        });
       
        //add agent
        add_agent_modal.dialog({
            modal: true,
            autoOpen: false,
            width: 700,
            buttons: {
                "Add Agent": function() {
                     $.ajax({
                        url: "modules/agents/add_agent.php",
                        type: "GET",
                        cache: false,
                        data:{
                            name: name.val(),
                            company: company.val(),
                            address: address.val(),
                            city: city.val(),
                            state: state.val(),
                            zip: zip.val(),
                            email: email.val(),
                            phone: phone.val(),
                            access: access.val(),
                            repcode_low: repcode_low.val(),
                            repcode_high: repcode_high.val(),
                            com_low: com_low.val(),
                            com_high: com_high.val()
                        },
                        success: function(data){
                            alert(data);
                            add_agent_modal.dialog("close");
                        }
                     });
                },
                "Cancel": function(){
                    allFields.val("");
                    add_agent_modal.dialog("close");
                }
            }
        });
        
        add_agent_btn.button()
            .click(function(){
                add_agent_modal.dialog("open");
                return false;
            });
            
        //back to agent listing
        back_to_agents_btn.button({
                icons: {
                    primary: 'ui-icon-triangle-1-w'
                }
            })
            .hide()
            .click(function(){
                agent_content.show();
                agent_search.show();
                add_agent_btn.show();
                back_to_agents_btn.hide();
                agent_detail_content.hide();
            });
            
        function loadAgentListing(term, index, term_id_t){
            if(index == 0){
                $("#agent_listing_box .listing_spinner").remove();
                agent_listing_box.append("<img class='listing_spinner' src='../../cforce/images/cms_loading.gif'/>");
            }
            $.ajax({
                url: "modules/agents/agent_listing.php",
                type: "GET",
                cache: false,
                data:{
                    mode: "table",
                    term: term,
                    index: index
                },
                success: function(data){
                    $("#agent_listing_box .listing_spinner").remove();
                    if(term_id == term_id_t){
                        if(index == 0){
                            $("#agent_listing tr:has(td)").remove();
                        }
                        $("#agent_listing tr:last-child").after(data);
                        
                        //keep loading until no data is returned
                        if(data.length > 0){
                            loadAgentListing(term, index + 100, term_id_t);
                        }
                        else{
                            term_id = 0;
                        }
                        //add click listener to rows
                        $("#agent_listing tr").click(function(){
                            var id = $(this).attr("id").substr(6);
                            loadAgentDetail(id);
                            return false;
                        });
                    }
               }
            });
        }
        
        function loadAgentDetail(id){
            agent_detail_content.show();
            agent_detail_content.html("<img class='detail_spinner' src='../../cforce/images/cms_loading.gif'/>");
            agent_content.hide();
            agent_search.hide();
            add_agent_btn.hide();
            back_to_agents_btn.show();
            $.ajax({
                url: "modules/agents/agent_detail.php",
                type: "GET",
                data:{
                    id: id
                },
                cache: false,
                success: function(data){
                    agent_detail_content.html(data).show();
                    
                    add_case.button();
                    edit_agent.button();
                    print_agent.button();
                }
            });
        }  
    });
</script>
<div id="agent_header">
    <button id="back_to_agents_btn">Back to Agent List</button>
    <input id="agent_search" type="text" value="   Search for an agent here..." class="default"></input>
<?php if($_SESSION['department'] == '10'): ?>
    <button id="add_agent_btn">Add Agent</button>
<?php endif; ?>
</div>
<div id="agent_content">
    <div id="infobox">  
        <div class="section_header"><p>Stats</p></div>
        <img id="fire1" src="../../cforce/images/fire1.png">
        <img id="fire2" src="../../cforce/images/fire2.png">
        <img id="fire3" src="../../cforce/images/fire3.png">
    </div>
    <div id="agent_listing_box">  
        <div class="section_header"><p>Agent List</p></div>
        <table id="agent_listing" class="data_table">
            <tr>
                <th style="width:30px">Low</th>
                <th style="width:30px">High</th>
                <th style="width:175px">Name</th>
                <th style="width:120px">Company</th>
                <th style="width:150px">Email</th>
                <th style="width:75px">Phone</th>
            </tr>
        </table>   
    </div>
    
    <div id='add_agent_modal' title="Add Agent"> 
        <table>
            <tr>
                <th>Name</th>
                <td colspan="5">
                    <input type="text" id="name"/>
                </td>
            </tr>
            <tr>
                <th>Company</th>
                <td colspan="5">
                    <input type="text" id="company"/>
                </td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="5">
                    <input type="text" id="address"/>
                </td>
            </tr>
            <tr>
                <th style="width: 120px">City</th>
                <td style="width: 175px">
                    <input type="text" id="city"/>
                </td>
                <th style="width: 120px">State</th>
                <td style="width: 40px">
                    <input type="text" id="state"/>
                </td>
                <th style="width: 120px">Zip</th>
                <td style="width: 100px">
                    <input type="text" id="zip"/>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td colspan="5">
                    <input type="text" id="email"/>
                </td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>
                    <input type="text" id="phone"/>
                </td>
                <th>Access</th>
                <td colspan="3">
                    <select id="access">
                        <option value="0" selected="selected">Basic Agent</option>
                        <option value="1">Full Access Agent</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Rep Code Low</th>
                <td>
                    <input type="text" id="repcode_low"/>
                </td>
                <th>Rep Code High</th>
                <td colspan="3">
                    <input type="text" id="repcode_high"/>
                </td>
            </tr>
            <tr>
                <th>Com % Low</th>
                <td>
                    <input type="text" id="com_low"/>
                </td>
                <th>Com % High</th>
                <td colspan="3">
                    <input type="text" id="com_high"/>
                </td>
            </tr>
        </table>
    </div>
    
    <a href="#" class="to_top">
        Back to top
    </a>
</div>

<div id='agent_detail_content'></div>




                
                