<?php
/**
* @package		SLASH-CMS
* @subpackage	AJAXUPLOAD_PLUGIN
* @internal     Upload interface in ajax
* @version		ajaxupload_list.php - Version 9.12.16
* @author		Julien Veuillet [http://www.wakdev.com]
* @copyright	Copyright(C) 2009 - Today. All rights reserved.
* @license		GNU/GPL

This program is free software : you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

/**************************************************/
/******** 		CONFIGURATION		********/
/**************************************************/
session_start();
include ("../../common/constants/sl_constants.php");
include ("../../config/sl_config.php");
include ("../../common/class/functions/includes/sl_files.php");
$config = new SLConfig();
$filestools = new sl_files();
$host = $config->db_host; 
$login = $config->db_user; 
$password = $config->db_password; 
$database_name = $config->db_name; 
$database = mysql_connect($host, $login, $password) or die ("CONNEXION ERROR");	
mysql_select_db($database_name, $database) or die ("DATABASE CONNEXION ERROR");

//Check user
if (isset($_SESSION["id_user"]) && $_SESSION["id_user"] != null) {
	$result = mysql_query("SELECT * FROM ".$config->db_prefix."users WHERE id=".$_SESSION["id_user"],$database);
	if (mysql_num_rows($result)==0) { exit; }
}else{
	exit;
}
		

// Get post values
$sl_mod_field_id;
$sl_mod_upload_id;
$sl_mod_upload_id_module;
$sl_mod_upload_files_dir;
$sl_mod_upload_action;
$sl_mod_upload_file;
$sl_mod_upload_id_attachment;
$sl_mod_upload_position_attachment;
$sl_mod_upload_min;

$sl_mod_img_resize;
$sl_mod_img_max_width;
$sl_mod_img_max_height;

if (isset($_POST["sl_mod_field_id"])) { $sl_mod_field_id = $_POST["sl_mod_field_id"]; }
if (isset($_POST["sl_mod_upload_id"])) { $sl_mod_upload_id = $_POST["sl_mod_upload_id"]; }
if (isset($_POST["sl_mod_upload_id_module"])) { $sl_mod_upload_id_module = $_POST["sl_mod_upload_id_module"]; }
if (isset($_POST["sl_mod_upload_files_dir"])) { $sl_mod_upload_files_dir = $_POST["sl_mod_upload_files_dir"]; }
if (isset($_POST["sl_mod_upload_action"])) { $sl_mod_upload_action = $_POST["sl_mod_upload_action"]; } else { $sl_mod_upload_action = Null; }
if (isset($_POST["sl_mod_upload_file"])) { $sl_mod_upload_file = $_POST["sl_mod_upload_file"]; }
if (isset($_POST["sl_mod_upload_id_attachment"])) { $sl_mod_upload_id_attachment = $_POST["sl_mod_upload_id_attachment"]; }
if (isset($_POST["sl_mod_upload_position_attachment"])) { $sl_mod_upload_position_attachment = $_POST["sl_mod_upload_position_attachment"]; }
if (isset($_POST["sl_mod_upload_min"])) { $sl_mod_upload_min = $_POST["sl_mod_upload_min"]; } else { $sl_mod_upload_min = Null; }

if (isset($_POST["sl_mod_img_resize"])) { $sl_mod_img_resize = $_POST["sl_mod_img_resize"]; } else { $sl_mod_img_resize = false; }
if (isset($_POST["sl_mod_img_max_width"])) { $sl_mod_img_max_width = $_POST["sl_mod_img_max_width"]; } else { $sl_mod_img_max_width = "auto"; }
if (isset($_POST["sl_mod_img_max_height"])) { $sl_mod_img_max_height = $_POST["sl_mod_img_max_height"]; } else {$sl_mod_img_max_height = "auto"; }



/*
$sl_mod_field_id = $_POST["sl_mod_field_id"];
$sl_mod_upload_id = $_POST["sl_mod_upload_id"];
$sl_mod_upload_id_module = $_POST["sl_mod_upload_id_module"];
$sl_mod_upload_files_dir = $_POST["sl_mod_upload_files_dir"];
$sl_mod_upload_action = $_POST["sl_mod_upload_action"];

$sl_mod_upload_file = $_POST["sl_mod_upload_file"];
$sl_mod_upload_id_attachment = $_POST["sl_mod_upload_id_attachment"];
$sl_mod_upload_position_attachment = $_POST["sl_mod_upload_position_attachment"];
*/

/**************************************************/
/******** 			   ACTIONS 	  		   ********/
/**************************************************/

//echo "ID MODULE : ".$sl_mod_upload_id_module." ET ID ELEMENT : ".$sl_mod_upload_id." ET ID FIELD : ".$sl_mod_field_id;

switch ($sl_mod_upload_action) {	

	
	case "delete":
		
		$ret = unlink($sl_mod_upload_file);
		if (!$ret){
			echo "DELETE ERROR <br />";
		}
		
		$result = mysql_query("DELETE FROM sl_attachments WHERE id_module=".$sl_mod_upload_id_module." AND id='".$sl_mod_upload_id_attachment."'",$database) 
					or die ("QUERY ERROR : ".mysql_error());
		
		if ($sl_mod_upload_id!=0) {
		
			$result = mysql_query("SELECT * FROM sl_attachments WHERE id_module='".$sl_mod_upload_id_module."' AND id_element='".$sl_mod_upload_id."' AND state='1' and id_field='".$sl_mod_field_id."' ORDER BY position ASC",$database) or die ("QUERY ERROR : ".mysql_error());
			if (mysql_num_rows($result)==0) {
			
				$path = "../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id."/";
			
				if(@ ! rmdir($path)) {
					echo "DELETE ERROR <br />";
				}
				
			
			}else{ //New position
				$p = 1;
				while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
					mysql_query("UPDATE sl_attachments set position=".$p." WHERE id='".$row["id"]."'",$database) or die ("QUERY ERROR : ".mysql_error());
					$p++;
				}
			}
		}
	
	break;
		
	/* set Position */
	case "up":
	case "down":

		if ($sl_mod_upload_action == "up") { 
			$new_position = $sl_mod_upload_position_attachment-1;
		}else{
			$new_position = $sl_mod_upload_position_attachment+1;
		}
		
		if ($new_position >=1) {
		
			if ($sl_mod_upload_id==0) {
			
				$result = mysql_query("SELECT * FROM sl_attachments WHERE id_module='".$sl_mod_upload_id_module."' AND id_user='".$_SESSION["id_user"]."' AND position='".$new_position."'",$database) or die ("QUERY ERROR : ".mysql_error());
				
				$num_rows = mysql_num_rows($result);
				
				if ($num_rows != 0) {
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					$result = mysql_query("UPDATE sl_attachments set position=".$sl_mod_upload_position_attachment." WHERE id_module='".$sl_mod_upload_id_module."' AND id_user='".$_SESSION["id_user"]."' AND position=".$new_position,$database) or die ("QUERY ERROR : ".mysql_error());
					$result = mysql_query("UPDATE sl_attachments set position=".$new_position." WHERE id=".$sl_mod_upload_id_attachment,$database) or die ("QUERY ERROR : ".mysql_error());
				}
				
			}else{
			
				$result = mysql_query("SELECT * FROM sl_attachments WHERE id_module='".$sl_mod_upload_id_module."' and id_element='".$sl_mod_upload_id."' and position='".$new_position."'",$database) or die ("QUERY ERROR : ".mysql_error());

				$num_rows = mysql_num_rows($result);
				
				if ($num_rows != 0) {
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					$result = mysql_query("UPDATE sl_attachments set position=".$sl_mod_upload_position_attachment." WHERE id_module='".$sl_mod_upload_id_module."' AND id_element='".$sl_mod_upload_id."' AND position=".$new_position,$database) or die ("QUERY ERROR : ".mysql_error());
					$result = mysql_query("UPDATE sl_attachments set position=".$new_position." WHERE id=".$sl_mod_upload_id_attachment,$database) or die ("QUERY ERROR : ".mysql_error());
				}
			
			}
		
		}
		
	break;
	
	default:
	
	
}





/**************************************************/
/******** 			LIST 			********/
/**************************************************/
if ($sl_mod_upload_id == 0) {
	/*--- NEW MODE ---*/
	$result = mysql_query("SELECT * FROM sl_attachments WHERE id_user='".$_SESSION["id_user"]."' and id_module='".$sl_mod_upload_id_module."' and state='0' and id_field='".$sl_mod_field_id."' ORDER BY position",$database) 
				or die ("QUERY ERROR : ".mysql_error());
	$numfields = mysql_num_rows($result);
	
	if ($numfields > 0) {
	
			
		while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
		
			$path_admin = "../tmp/".$row["filename"];
			$path = "../../../tmp/".$row["filename"];
			
			?>
			<table width="100%" cellspacing='0' cellpadding='0' border='0'>
			<tr>
			<td align="left">
			<?php
			// Thumb Display
			$ext_file = strtolower($filestools->get_file_extension($row["filename"]));
			if( $ext_file == ".jpg" || $ext_file == ".gif" || $ext_file == ".png"){
				echo "<a href='".$path_admin."' target='_blank' ><img src='".$path_admin."' class='sl_mod_upload_img'/></a>";
			}else{
				
				if (file_exists("../../../admin/templates/system/images/extensions/16x16/".substr($ext_file, 1).".png")) {
					echo "<img src='templates/system/images/extensions/16x16/".substr($ext_file, 1).".png' align='absmiddle'/>&nbsp;";
				}else{
					echo "<img src='templates/system/images/extensions/16x16/none.png' align='absmiddle'/>&nbsp;";
				}
				
			}
			?>
			<a href="<?php echo $path_admin; ?>" target="_blank"><?php echo $row["filename"]; ?></a>
			</td><td width="20" align="right">
			<!-- UP BUTTON -->
			<?php if ($numfields > 1) { ?>
			<a href="#" class="upload_list_up_button" onclick="
			$('#sl_mod_upload_list_<?php echo $sl_mod_field_id; ?>').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
				sl_mod_field_id: '<?php echo $sl_mod_field_id; ?>',
				sl_mod_upload_id: '0',
				sl_mod_upload_id_module: '<?php echo $sl_mod_upload_id_module; ?>',
				sl_mod_upload_files_dir: '<?php echo $sl_mod_upload_files_dir; ?>', 
				sl_mod_upload_id_attachment: '<?php echo $row["id"]; ?>',
				sl_mod_upload_position_attachment: '<?php echo $row["position"]; ?>',
				sl_mod_upload_file: '<?php echo $path; ?>',
				sl_mod_img_resize: '<?php echo $sl_mod_img_resize; ?>',
				sl_mod_img_max_width: '<?php echo $sl_mod_img_max_width; ?>',
				sl_mod_img_max_height: '<?php echo $sl_mod_img_max_height; ?>',				
				sl_mod_upload_action: 'up'
			}); return false;"></a>
			<?php } ?>
			</td><td width="20" align="right">
			<!-- DOWN BUTTON -->
			<?php if ($numfields > 1) { ?>
			<a href="#" class="upload_list_down_button" onclick="
			$('#sl_mod_upload_list_<?php echo $sl_mod_field_id; ?>').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
				sl_mod_field_id: '<?php echo $sl_mod_field_id; ?>',
				sl_mod_upload_id: '0',
				sl_mod_upload_id_module: '<?php echo $sl_mod_upload_id_module; ?>',
				sl_mod_upload_files_dir: '<?php echo $sl_mod_upload_files_dir; ?>', 
				sl_mod_upload_id_attachment: '<?php echo $row["id"]; ?>',
				sl_mod_upload_position_attachment: '<?php echo $row["position"]; ?>',
				sl_mod_upload_file: '<?php echo $path; ?>', 
				sl_mod_img_resize: '<?php echo $sl_mod_img_resize; ?>',
				sl_mod_img_max_width: '<?php echo $sl_mod_img_max_width; ?>',
				sl_mod_img_max_height: '<?php echo $sl_mod_img_max_height; ?>',
				sl_mod_upload_action: 'down'
			}); return false;"></a>
			<?php } ?>
			</td>
			<td width="20" align="right">
			<!-- DELETE BUTTON -->
			<a href="#" class="upload_list_del_button" onclick="if(confirm('Delete <?php echo $row["filename"]; ?> ?')){ 
			
			upload_count_<?php echo $sl_mod_field_id; ?>--;
			
			$('#sl_mod_upload_list_<?php echo $sl_mod_field_id; ?>').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
				sl_mod_field_id: '<?php echo $sl_mod_field_id; ?>',
				sl_mod_upload_id: '0',
				sl_mod_upload_id_module: '<?php echo $sl_mod_upload_id_module; ?>',
				sl_mod_upload_files_dir: '<?php echo $sl_mod_upload_files_dir; ?>', 
				sl_mod_upload_id_attachment: '<?php echo $row["id"]; ?>',
				sl_mod_upload_file: '<?php echo $path; ?>',
				sl_mod_img_resize: '<?php echo $sl_mod_img_resize; ?>',
				sl_mod_img_max_width: '<?php echo $sl_mod_img_max_width; ?>',
				sl_mod_img_max_height: '<?php echo $sl_mod_img_max_height; ?>',
				sl_mod_upload_action: 'delete'
			});
			} return false;
			"></a>
			</td>
			</tr></table>
		
			<?php	
		}
	
	}else{
		echo "NO FILES";
	}
					
					
}else{
	/*--- EDIT MODE ---*/
	$result = mysql_query("SELECT * FROM sl_attachments WHERE id_module='".$sl_mod_upload_id_module."' and id_element='".$sl_mod_upload_id."' and state='1' and id_field='".$sl_mod_field_id."' ORDER BY position",$database) 
				or die ("QUERY ERROR : ".mysql_error());
	$numfields = mysql_num_rows($result);
	
	echo "<script type='text/javascript'>upload_count=".$numfields.";</script>";
	
	if ($numfields > 0) {
			
		while ($row = mysql_fetch_array($result, MYSQL_BOTH)) {
		
			$path_admin = "../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id."/".$row["filename"];
			$path = "../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id."/".$row["filename"];
			
			?>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td align="left">
			
			<?php
			// Thumb Display
			$ext_file = strtolower($filestools->get_file_extension($row["filename"]));
			if( $ext_file == ".jpg" || $ext_file == ".gif" || $ext_file == ".png"){
				echo "<a href='".$path_admin."' target='_blank' ><img src='".$path_admin."' class='sl_mod_upload_img'/></a>";
			}else{
				
				if (file_exists("../../../admin/templates/system/images/extensions/16x16/".substr($ext_file, 1).".png")) {
					echo "<img src='templates/system/images/extensions/16x16/".substr($ext_file, 1).".png' align='absmiddle'/>&nbsp;";
				}else{
					echo "<img src='templates/system/images/extensions/16x16/none.png' align='absmiddle'/>&nbsp;";
				}
				
			}

			?>
			
			<a href="<?php echo $path_admin; ?>" target="_blank" ><?php echo $row["filename"]; ?></a>
			</td><td width="20" align="right">
			<!-- UP BUTTON -->
			<?php if ($numfields > 1) { ?>
			<a href="#" class="upload_list_up_button" onclick="
			$('#sl_mod_upload_list_<?php echo $sl_mod_field_id; ?>').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
				sl_mod_field_id: '<?php echo $sl_mod_field_id; ?>',
				sl_mod_upload_id: '<?php echo $sl_mod_upload_id; ?>',
				sl_mod_upload_id_module: '<?php echo $sl_mod_upload_id_module; ?>',
				sl_mod_upload_files_dir: '<?php echo $sl_mod_upload_files_dir; ?>', 
				sl_mod_upload_id_attachment: '<?php echo $row["id"]; ?>',
				sl_mod_upload_position_attachment: '<?php echo $row["position"]; ?>',
				sl_mod_upload_file: '<?php echo $path; ?>', 
				sl_mod_img_resize: '<?php echo $sl_mod_img_resize; ?>',
				sl_mod_img_max_width: '<?php echo $sl_mod_img_max_width; ?>',
				sl_mod_img_max_height: '<?php echo $sl_mod_img_max_height; ?>',
				sl_mod_upload_min: '<?php echo $sl_mod_upload_min; ?>',
				sl_mod_upload_action: 'up'
			}); return false;"></a>
			<?php } ?>
			
			</td><td width="20" align="right">
			<!-- DOWN BUTTON -->
			<?php if ($numfields > 1) { ?>
			<a href="#;" class="upload_list_down_button" onclick="
			$('#sl_mod_upload_list_<?php echo $sl_mod_field_id; ?>').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
				sl_mod_field_id: '<?php echo $sl_mod_field_id; ?>',
				sl_mod_upload_id: '<?php echo $sl_mod_upload_id; ?>',
				sl_mod_upload_id_module: '<?php echo $sl_mod_upload_id_module; ?>',
				sl_mod_upload_files_dir: '<?php echo $sl_mod_upload_files_dir; ?>', 
				sl_mod_upload_id_attachment: '<?php echo $row["id"]; ?>',
				sl_mod_upload_position_attachment: '<?php echo $row["position"]; ?>',
				sl_mod_upload_file: '<?php echo $path; ?>',
				sl_mod_img_resize: '<?php echo $sl_mod_img_resize; ?>',
				sl_mod_img_max_width: '<?php echo $sl_mod_img_max_width; ?>',
				sl_mod_img_max_height: '<?php echo $sl_mod_img_max_height; ?>',
				sl_mod_upload_min: '<?php echo $sl_mod_upload_min; ?>',				
				sl_mod_upload_action: 'down'
			}); return false;"></a>
			<?php } ?>
			
			</td>
			<td width="20" align="right">
			<!-- DELETE BUTTON -->
			
			<?php 
			if ($sl_mod_upload_min < $numfields) { // Minimum upload
			?>
			
			<a href="#" class="upload_list_del_button" onclick="if(confirm('Delete <?php echo $row["filename"]; ?> ?')){
			
			upload_count_<?php echo $sl_mod_field_id; ?>--;
			
			$('#sl_mod_upload_list_<?php echo $sl_mod_field_id; ?>').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
				sl_mod_field_id: '<?php echo $sl_mod_field_id; ?>',
				sl_mod_upload_id: '<?php echo $sl_mod_upload_id; ?>',
				sl_mod_upload_id_module: '<?php echo $sl_mod_upload_id_module; ?>',
				sl_mod_upload_files_dir: '<?php echo $sl_mod_upload_files_dir; ?>', 
				sl_mod_upload_id_attachment: '<?php echo $row["id"]; ?>',
				sl_mod_upload_file: '<?php echo $path; ?>', 
				sl_mod_img_resize: '<?php echo $sl_mod_img_resize; ?>',
				sl_mod_img_max_width: '<?php echo $sl_mod_img_max_width; ?>',
				sl_mod_img_max_height: '<?php echo $sl_mod_img_max_height; ?>',
				sl_mod_upload_min: '<?php echo $sl_mod_upload_min; ?>',
				sl_mod_upload_action: 'delete'
			});
			} return false;
			"></a>
			
			<?php 
			} 
			?>
			
			</td>
			
			</tr></table>
		
			<?php

		}
	}else{
		echo "NO FILES";
	}

}
	

?>



