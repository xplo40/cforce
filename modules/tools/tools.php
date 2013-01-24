<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
 ?>

<table id="tools_layout" class="detail_layout">
    <tbody>
<?php
    $department = $_SESSION['department'];
    switch ($department) {
    case "10":
        include "dda_action_request.php";
        break;
    case '16':
        include "dda_action_request.php";
        break;
    case '18':
        include "settlement_tools.php";
        break;
    }
?>
    </tbody>
</table>