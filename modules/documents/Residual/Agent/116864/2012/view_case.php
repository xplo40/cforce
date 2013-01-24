<html>
<style type="text/css">
<!--
.style2 {font-family: Arial, Helvetica, sans-serif}
.style1 {font-family: Arial, Helvetica, sans-serif;
	color: #FFFFFF;
	font-weight: bold;
}
.style5s {font-size: 12px}
.style6 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style7 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
-->
</style>


<script type="text/javascript">
<!--
null;

//-->
</script>
</head>

<body>

    <table border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#333333">
      <tr>
        <td bgcolor="#333333"><span class="style1">Case Manager</span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF"><table width="650" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#333333">
          <tr>
            <td colspan="3"  bgcolor="#CCCCCC" class="style1">Case Viewer</td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" class="style6">Case</td>
            <td bgcolor="#FFFFFF" class="style5s"><span class="style2"><?php echo "$case [id]"; ?></span></td>
            <td align="center" bgcolor="#CCCCCC" class="style2"><strong>Tech Notes</strong></td>
          </tr>
          <tr>
            <td class="style6" <?php if($row_tik[status] == "Open") { echo "bgcolor='#B3FFB3'"; } if($row_tik[status] == "Closed") { echo "bgcolor='#F2C2BD'"; } if($row_tik[status] == "Pending") { echo "bgcolor='#EEEEBB'"; }?>class="style4">Status</td>
            <td class="style5s" bgcolor="#FFFFFF" ><span class="style2">
              <select name="jumpMenu2" id="jumpMenu2" onChange="MM_jumpMenu('parent',this,0)">
                <option value="" selected><?php echo "$row_tik[status]"; ?></option>
                <option value="update.php?func=statusupdate&id=<?php echo "$row_tik[id]"; ?>&status=Open">Open</option>
                <option value="update.php?func=statusupdate&id=<?php echo "$row_tik[id]"; ?>&status=Closed">Closed</option>
                <option value="update.php?func=statusupdate&id=<?php echo "$row_tik[id]"; ?>&status=Pending">Pending</option>
              </select>
            </span> </td>
            <td width="39%" rowspan="7" align="center" bgcolor="#FFFFFF" class="style2"><p>
              <textarea name="notes" id="notes" cols="35" rows="15"><?php include('../funcs/get_ticket_notes.php'); ?>
              </textarea>
              <br>
              <a href="javascript:popUp('./update.php?id=<?php echo "$row_tik[id]"; ?>&func=notes')"><img src="../../images/add_notes.gif" width="103" height="23" border="0"></a>
              <?php if($user_type == "tech") 

{ ?>

              <a href="javascript:popUp('./update.php?id=<?php echo "$row_tik[id]"; ?>&func=owner')"><img src="../../images/take_ownership.gif" width="103" height="23" border="0"></a> 

              <?php } 
if($user_type == "admin")
 { ?>
           
            </p>
              <p>Change Ownership<br>
                <span class="style2">
              <?php include('../funcs/get_techs.php'); ?>
              </span>              <input type="submit" name="Change" id="Change" value="Change">
            </p>
              <?php } ?>
            
            
            </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" class="style6">Current Owner</td>
            <td valign="middle" bgcolor="#FFFFFF" class="style5s"><span class="style2"><?php echo "$techs_name"; ?></span></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC" class="style6">Timestamp</td>
            <td valign="middle" bgcolor="#FFFFFF" class="style7"><?php echo "$row_tik[date_start]"; ?> | <?php echo "$row_tik[date_stop]"; ?></td>
            </tr>
          <tr>
            <td width="23%" bgcolor="#CCCCCC" class="style6">Client</td>
            <td width="38%" bgcolor="#FFFFFF" class="style5s"><span class="style2"><?php echo "<a href='../view_client.php?id=$row_tik[client_id]'>$clients_name</a>"; ?></span></td>
            </tr>
          
          <tr>
            <td bgcolor="#CCCCCC" class="style6">Subject</td>
            <td bgcolor="#FFFFFF" class="style5s"><span class="style2"><?php echo "$row_tik[name]"; ?></span></td>
          </tr>
          <tr>
            <td colspan="2" bgcolor="#CCCCCC" class="style2"><strong>Client's Problem</strong></td>
            </tr>
          <tr>
            <td colspan="2" align="center" valign="middle" bgcolor="#FFFFFF" class="style2"><textarea name="description2" id="description2" cols="35" rows="7"><?php echo "$tk_notes";?></textarea></td>
            </tr>
        </table>
        </td>
      </tr>
</table>
    <p>&nbsp;</p>
	
</html>