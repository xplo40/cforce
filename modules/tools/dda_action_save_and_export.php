<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    
    include "../../include/common.php";
    connect_to_mysql_database(false);
    
    $merchant_name = sanitize($_GET['dda_merchant_name_val']);
    $mid = sanitize($_GET['dda_mid_val']);
    $reject_date = sanitize($_GET['dda_reject_date']);
    $write_off_date = sanitize($_GET['dda_write_off_date']);
    $transfer_from = sanitize($_GET['dda_transfer_from'])*1;
    $amount = sanitize($_GET['dda_amount'])*1;
    $from_account = sanitize($_GET['dda_from_account']);
    $to_account = sanitize($_GET['dda_to_account']);
    $type = sanitize($_GET['dda_type'])*1;
    $reason = sanitize($_GET['dda_reason'])*1;
    $descriptor = sanitize($_GET['dda_descriptor']);

    //convert formats
    $write_off_date = date('Y-m-d h:i:s', strtotime($write_off_date));
    $reject_date = date('Y-m-d h:i:s', strtotime($reject_date));
    
    $query = "INSERT INTO CMS.DDA_Action_Request 
            (`MerchantName`
            ,`MID`
            ,`RejectDate`
            ,`WriteOffDate`
            ,`Amount`
            ,`AccountNumber`
            ,`Type`
            ,`ReturnReason`
            ,`Descriptor`)
        VALUES ('$merchant_name','$mid','$reject_date','$write_off_date','$amount','$to_account','$type','$reason','$descriptor')";
    $result = mysql_query($query);
    
    if(!$result){
       // echo "There was an error storing the DDA action request, please try again later.";
    }
    
    /*$info = array(
        'Date1' => date('m/d/y'),
        'NBCal' => 'NBCal',
        'User' => $_SESSION['name'],
        'MerchantName' => $merchant_name,
        'MID' => $mid,
        'Action_9' => 'true',
        
        '9_FromAccount' => $from_account,
        '9_ToAccount' => $to_account,
        '9_Amount' => $amount,
        '9_Reason' => $reason
    );

    $formdata = createXFDF('DDA Action Request Form.pdf', $info);
    $filename = "DDA Action Request Form.fdf";
    if (file_exists($filename)){
        unlink($filename);
    }
    $handler = fopen($filename, 'w');
    fwrite($handler, $formdata);
    fclose($handler);*/
  /*  
    include("vcard.php");
    include("fdf.php");

    //The pdf file that the fdf file points to:
    $pdf_doc="https://beta.cmsonline.com/cforce/modules/tools/DDA%20Action%20Request%20Form.pdf";

    $data = array(
        'Date1' => date('m/d/y'),
        'NBCal' => 'NBCal',
        'User' => $_SESSION['name'],
        'MerchantName' => $merchant_name,
        'MID' => $mid,
        'Action_9' => 'true',
        
        '9_FromAccount' => $from_account,
        '9_ToAccount' => $to_account,
        '9_Amount' => $amount,
        '9_Reason' => $reason
    );
    
    $fdf_data=createFDF($pdf_doc,$data);
    $filename = "DDA Action Request Form.fdf";
    if (file_exists($filename)){
        unlink($filename);
    }
    $handler = fopen($filename, 'w');
    fwrite($handler, $fdf_data);
    fclose($handler);
    */
    
   /* $v = new vCard();

    $v->setPhoneNumber($_POST["mobilePhone"], "PREF;CELL;VOICE");
    $v->setPhoneNumber($_POST["homePhone"], "PREF;HOME;VOICE");
    $v->setName($_POST["lastName"], $_POST["firstName"], "", "");
    $v->setBirthday($_POST["dobYear"]."-".$_POST["dobMonth"]."-".$_POST["dobDat"]);
    $v->setAddress("", "", $_POST["address"], $_POST["city"], $_POST["state"], $_POST["zip"], "US");
    $v->setEmail($_POST["email"]);
    //$v->setNote("You can take some notes here.\r\nMultiple lines are supported via \\r\\n.");
    //$v->setURL("http://www.thomas-mustermann.de", "WORK");
    
    $output = $v->getVCard();
    $filename = $v->getFileName();

    Header("Content-Disposition: attachment; filename=$filename");
    Header("Content-Length: ".strlen($output));
    Header("Connection: close");
    Header("Content-Type: text/x-vCard; name=$filename");

    echo $output;*/

    /*$outfdf = fdf_create();
    fdf_set_value($outfdf, "volume", $volume, 0);

    fdf_set_file($outfdf, "http:/testfdf/resultlabel.pdf");
    fdf_save($outfdf, "outtest.fdf");
    fdf_close($outfdf);
    Header("Content-type: application/vnd.fdf");
    $fp = fopen("outtest.fdf", "r");
    fpassthru($fp);
    unlink("outtest.fdf");*/

   /* foreach($_POST as $name => $val) {
     $data[$name] = $val;
     //echo $name."<br/>";
    }


    // generate the file content
    $fdf_data=createFDF($pdf_doc,$data);
    $attachments[0]['data']=$v->getVCard();
    $attachments[0]['filename']=$_POST['firstName'].$_POST['lastName'].".vcf";
    $attachments[0]['mime']="text/x-vcard";

    $attachments[1]['data']=$fdf_data;
    $attachments[1]['filename']=$_POST['firstName'].$_POST['lastName'].".fdf";
    $attachments[1]['mime']="application/vnd.fdf";

    $from=$_POST['firstName']." ".$_POST['lastName']." <".$_POST['email'].">";

    mail_attachment($from,$emailto,"Rental Application", "Lease: from ".$_POST['leaseStart']." to ".$_POST['leaseEnd'], $attachments);
    Header("Content-type: application/vnd.fdf");
    echo $fdf_data;*/
    
    $data = array(
        'Date1' => date('m/d/y'),
        'NBCal' => 'NBCal',
        'User' => $_SESSION['name'],
        'MerchantName' => $merchant_name,
        'MID' => $mid,
        'Action_9' => 'true',
        
        '9_FromAccount' => $from_account,
        '9_ToAccount' => $to_account,
        '9_Amount' => $amount,
        '9_Reason' => $reason
    );
    
    $fdfdata = "%FDF-1.2\n%\n";
    $fdfdata .= "1 0 obj << /FDF ";
    $fdfdata .= "<< /Fields ["; 
    foreach($data as $name=>$value){
      $fdfdata .= "<< /V ($value) /T ($name) >> ";
    }  
    $fdfdata .= "]\n";
    $fdfdata .= "/F (https://beta.cmsonline.com/cforce/modules/tools/DDA%20Action%20Request%20Form.pdf) >>";
    $fdfdata .= ">>\nendobj\ntrailer\n<<\n/Root 1 0 R\n>>\n";
    $fdfdata .= "%%EOF";

    /*** Now we display the FDF data which causes Acrobat to start  ***/
    header ("Content-Type: application/vnd.fdf");
    print($fdfdata);
   /* $filename = "DDA Action Request Form.fdf";
    if (file_exists($filename)){
        unlink($filename);
    }
    $handler = fopen($filename, 'w');
    fwrite($handler, $fdfdata);
    fclose($handler);*/
?>