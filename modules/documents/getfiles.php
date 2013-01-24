<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }
    $user = $_SESSION['user'];
    $year=$_GET['year'];
  ?>

    <table class="sortable data_table">
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
      $directory2="Residual/Agent/$user/$year/";
      $myDirectory2=opendir($directory2);
        
        // Gets each entry
        while($entryName2=readdir($myDirectory2)) {
          $dirArray2[]=$entryName2;
        }
        
        // Finds extensions of files
        function findexts2 ($filename2) {
          $filename2=strtolower($filename2);
          $exts2=split("[/\\.]", $filename2);
          $n2=count($exts2)-1;
          $exts2=$exts2[$n2];
          return $exts2;
        }
        
        // Closes directory
        closedir($myDirectory2);
        
        // Counts elements in array
        $indexCount2=count($dirArray2);
        
        // Sorts files
        sort($dirArray2);
        
        // Loops through the array of files
        for($index2=0; $index2 < $indexCount2; $index2++) {
        
          // Allows ./?hidden to show hidden files
          if($_SERVER['QUERY_STRING']=="hidden")
          {$hide2="";
          $ahref2="./";
          $atext2="Hide";}
          else
          {$hide2=".";
          $ahref2="./?hidden";
          $atext2="Show";}
          if(substr("$dirArray2[$index2]", 0, 1) != $hide2) {
          
          // Gets File Names
          $name2=$dirArray2[$index2];
          $namehref=$dirArray2[$index2];
          
          // Gets Extensions 
          $extn2=findexts2($dirArray2[$index2]); 
          
          // Gets file size 
          $size2=number_format(filesize($directory2.$dirArray2[$index2]));
          
          // Gets Date Modified Data
          $modtime2=date("M j Y g:i A", filemtime($directory2.$dirArray2[$index2]));
          $timekey2=date("YmdHis", filemtime($directory2.$dirArray2[$index2]));
          
          // Prettifies File Types, add more to suit your needs.
          switch ($extn2){
            case "png": $extn2="PNG Image"; break;
            case "jpg": $extn2="JPEG Image"; break;
            case "svg": $extn2="SVG Image"; break;
            case "gif": $extn2="GIF Image"; break;
            case "ico": $extn2="Windows Icon"; break;
            
            case "txt": $extn2="Text File"; break;
            case "log": $extn2="Log File"; break;
            case "htm": $extn2="HTML File"; break;
            case "php": $extn2="PHP Script"; break;
            case "js": $extn2="Javascript"; break;
            case "css": $extn2="Stylesheet"; break;
            case "pdf": $extn2="PDF Document"; break;
            
            case "zip": $extn2="ZIP Archive"; break;
            case "bak": $extn2="Backup File"; break;
            
            default: $extn2=strtoupper($extn2)." File"; break;
          }
          
          // Separates directories
          if(is_dir($directory2.$dirArray2[$index2])) {
            $extn2="&lt;Directory&gt;"; 
            $size2="&lt;Directory&gt;"; 
            $class2="dir";
          } else {
            $class2="file";
          }
          
          // Cleans up . and .. directories 
          if($name2=="."){$name2=". (Current Directory)"; $extn2="&lt;System Dir&gt;";}
          if($name2==".."){$name2=".. (Parent Directory)"; $extn2="&lt;System Dir&gt;";}
          
          // Print 'em
          print("
          <tr class='$class2'>
            <td><a href='./$namehref2'>$name2</a></td>
            <td><a href='./$namehref2'>$extn2</a></td>
            <td><a href='./$namehref2'>$size2</a></td>
            <td sorttable_customkey='$timekey2'><a href='./$namehref2'>$modtime2</a></td>
          </tr>");
          }
        };
      ?>
      </tbody>
    </table>










 
