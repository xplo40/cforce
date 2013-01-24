<?php
    $mid = $_GET['mid'];
    $selected = $_GET['selected'];
    $file = "../../Statements/$selected/$mid.pdf";
    $filename = "$selected.$mid.pdf"; 

    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));
    header('Accept-Ranges: bytes');

    @readfile($file);
?>
