var home_tab = 0;
var agent_tab = 1;
var mrch_tab = 2;
var proc_tab = 3;
var doc_tab = 4;
var tool_tab = 5;

$(document).ready(function(){         
    /****************************GENERAL DEFINITIONS*******************************/

    //INITIAL DYNAMIC CSS
    $(".data_table tr").filter(":even").has("td")
        .css("background-color","#F6F6F6")
        .hover(function(){
            $(this).css("background-color","#EB6300");
        },function(){
            $(this).css("background-color","#F6F6F6");
    });
    $(".data_table tr").filter(":odd").has("td")
        .css("background-color","#E6E6E6")
        .hover(function(){
            $(this).css("background-color","#EB6300");
        },function(){
            $(this).css("background-color","#E6E6E6");
    });
    
    
    //ROW HOVER
    $(".data_table tr").filter(":even").has("td").hover(function(){
            $(this).css("background-color","#EB6300");
        },function(){
            $(this).filter(":even").css("background-color","#F6F6F6");
            $(this).filter(":odd").css("background-color","#E6E6E6");
    });
    //POINTER
    $(".data_table tr:not(:first-child)").css("cursor","pointer");

    //EXPORT EXCEL BUTTON
    $(".export").button({
        icons: {
            primary: "custom-excel"
        }
    }).css({
        margin:"20px 20px 10px 89%",
        padding:"0px",
        textMozBorderRadius:"0px",
        borderRadius:"0px"
    }).click(function(){
        $(this).parent().submit();
    });
    $(".export").addClass("button2");
});