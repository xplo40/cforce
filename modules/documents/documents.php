<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    $user = $_SESSION['user'];
    
 ?>

<script type="text/javascript">
    $(document).ready(function(){
        //selectors
        var years = $("#years");
        var file_list = $("#file_list");
        
        //load residuals
        loadResiduals();
        
        //load residuals for selected year
        years.change(function(){
            loadResiduals();
        });
        
        function loadResiduals(){
            $.ajax({
                url: "/modules/documents/getfiles.php",
                type: "GET",
                data:{
                    year: years.val()
                },
                cache: false,
                success: function(data){
                    file_list.html(data);
                }
            });
        }
    });
</script>

<div id="documents_layout">
    <h3 style="color:#000647">CMS Application Docs</h3>
    <br>
    <div id="Applications_Div">
        <table class="data_table document_table">
          <thead>
            <tr>
              <th>Document Name</th>
              <th>Type</th>
              <th>Size <small>(bytes)</small></th>
              <th>Date Added</th>
            </tr>
          </thead>
          <tbody>
          <?php
            // Opens directory
          $directory="Applications/";  
          $myDirectory=opendir($directory);

            // Gets each entry
            while($entryName=readdir($myDirectory)) {
              $dirArray[]=$entryName;
            }

            // Finds extensions of files
            function findexts ($filename) {
              $filename=strtolower($filename);
              $exts=split("[/\\.]", $filename);
              $n=count($exts)-1;
              $exts=$exts[$n];
              return $exts;
            }

            // Closes directory
            closedir($myDirectory);

            // Counts elements in array
            $indexCount=count($dirArray);

            // Sorts files
            sort($dirArray);

            // Loops through the array of files
            for($index=0; $index < $indexCount; $index++) {

              // Allows ./?hidden to show hidden files
              if($_SERVER['QUERY_STRING']=="hidden")
              {$hide="";
              $ahref="./";
              $atext="Hide";}
              else
              {$hide=".";
              $ahref="./?hidden";
              $atext="Show";}
              if(substr("$dirArray[$index]", 0, 1) != $hide) {

              // Gets File Names
              $name=$dirArray[$index];
              $namehref=$dirArray[$index];

              // Gets Extensions 
              $extn=findexts($dirArray[$index]); 

              // Gets file size 
              $size=number_format(filesize($directory.$dirArray[$index]));

              // Gets Date Modified Data
              $modtime=date("M j Y g:i A", filemtime($directory.$dirArray[$index]));
              $timekey=date("YmdHis", filemtime($directory.$dirArray[$index]));

              // Prettifies File Types, add more to suit your needs.
              switch ($extn){
                case "png": $extn="PNG Image"; break;
                case "jpg": $extn="JPEG Image"; break;
                case "gif": $extn="GIF Image"; break;
                case "txt": $extn="Text File"; break;
                case "htm": $extn="HTML File"; break;
                case "php": $extn="PHP Script"; break;
                case "pdf": $extn="PDF Document"; break;
                case "zip": $extn="ZIP Archive"; break;

                default: $extn=strtoupper($extn)." File"; break;
              }

              // Separates directories
              if(is_dir($dirArray[$index])) {
                $extn="&lt;Directory&gt;"; 
                $size="&lt;Directory&gt;"; 
                $class="dir";
              } else {
                $class="file";
              }

              // Cleans up . and .. directories 
              if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;";}
              if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}

              // Print 'em
              print("
              <tr class='$class'>
                <td><a href='modules/documents/Applications/$namehref'>$name</a></td>
                <td><a href='modules/documents/Applications/$namehref'>$extn</a></td>
                <td><a href='modules/documents/Applications/$namehref'>$size</a></td>
                <td sorttable_customkey='$timekey'><a href='modules/documents/Applications/$namehref'>$modtime</a></td>
              </tr>");
              }
            }
          ?>
          </tbody>
        </table>
    </div>
    <br></br>
    <h3 style="color:#000647">Industry Docs</h3>
    <br>
        <table class="data_table document_table">
          <thead>
            <tr>
              <th>Document Name</th>
              <th>Type</th>
              <th>Size <small>(bytes)</small></th>
              <th>Date Added</th>
            </tr>
          </thead>
          <tbody>
          <?php

    // Opens directory
            $directory1="Industry Docs/";
            $myDirectory1=opendir($directory1);

            // Gets each entry
            while($entryName1=readdir($myDirectory1)) {
              $dirArray1[]=$entryName1;
            }

            // Finds extensions of files
            function findexts1 ($filename1) {
              $filename1=strtolower($filename1);
              $exts1=split("[/\\.]", $filename1);
              $n1=count($exts1)-1;
              $exts1=$exts1[$n1];
              return $exts1;
            }

            // Closes directory
            closedir($myDirectory1);

            // Counts elements in array
            $indexCount1=count($dirArray1);

            // Sorts files
            sort($dirArray1);

            // Loops through the array of files
            for($index1=0; $index1 < $indexCount1; $index1++) {

              // Allows ./?hidden to show hidden files
              if($_SERVER['QUERY_STRING']=="hidden")
              {$hide1="";
              $ahref1="./";
              $atext1="Hide";}
              else
              {$hide1=".";
              $ahref1="./?hidden";
              $atext1="Show";}
              if(substr("$dirArray1[$index1]", 0, 1) != $hide1) {

              // Gets File Names
              $name1=$dirArray1[$index1];
              $namehref1=$dirArray1[$index1];

              // Gets Extensions 
              $extn1=findexts1($directory1.$dirArray1[$index1]); 

              // Gets file size 
              $size1=number_format(filesize($directory1.$dirArray1[$index1]));

              // Gets Date Modified Data
              $modtime1=date("M j Y g:i A", filemtime($directory1.$dirArray1[$index1]));
              $timekey1=date("YmdHis", filemtime($directory1.$dirArray1[$index1]));

              // Prettifies File Types, add more to suit your needs.
              switch ($extn1){
                case "png": $extn1="PNG Image"; break;
                case "jpg": $extn1="JPEG Image"; break;
                case "gif": $extn1="GIF Image"; break;
                case "txt": $extn1="Text File"; break;
                case "htm": $extn1="HTML File"; break;
                case "php": $extn1="PHP Script"; break;
                case "pdf": $extn1="PDF Document"; break;
                case "zip": $extn1="ZIP Archive"; break;
                 case "xlsx": $extn1="Excel Spreadsheet"; break;
                  case "doc": $extn1="Word Document"; break;
                   case "docx": $extn1="Word Document"; break;

                default: $extn1=strtoupper($extn1)." File"; break;
              }

              // Separates directories
              if(is_dir($dirArray1[$index1])) {
                $extn1="&lt;Directory&gt;"; 
                $size1="&lt;Directory&gt;"; 
                $class1="dir";
              } else {
                $class1="file";
              }

              // Cleans up . and .. directories 
              if($name1=="."){$name1=". (Current Directory)"; $extn1="&lt;System Dir&gt;";}
              if($name1==".."){$name1=".. (Parent Directory)"; $extn1="&lt;System Dir&gt;";}

              // Print 'em
              print("
              <tr class='$class1'>
                <td><a href='modules/documents/Industry Docs/$namehref1'>$name1</a></td>
                <td><a href='modules/documents/Industry Docs/$namehref1'>$extn1</a></td>
                <td><a href='modules/documents/Industry Docs/$namehref1'>$size1</a></td>
                <td sorttable_customkey='$timekey1'><a href='modules/documents/Industry Docs/$namehref1'>$modtime1</a></td>
              </tr>");
              }
            }
          ?>
          </tbody>
        </table>


    <br>
    <h3 style="color:#000647">Residual Reports</h3>
    <br>   

        <?php
        //loop through year folders
        $years = array();
        if($handle = opendir("Residual/Agent/$user")){
            while(false !== ($year = readdir($handle))) {
                if($year != '.' && $year != ".."){
                   $years[] = $year;
                }
            }
            closedir($handle);
        }


        //cancel if there are no files to display
        if(sizeof($years) == 0){
            echo "<select id='years' name='years' disabled='disabled'>
                <option>None available</option>
            </select>";
        }
        else{
            //reverse array
            sort($years);
            $years = array_reverse($years);

            //find the latest year
            $selected = $years[0];
            echo "<select id='years' name='years'>";
            foreach($years as $year){

                echo "<option value='$year'".($year == $selected ? " selected='selected'" : "")." >$year</option>";
            }
            echo "</select>";
        }
    ?>
    <br />
    <div id="file_list"><b>All Files will be listed here</b></div>
</div>

