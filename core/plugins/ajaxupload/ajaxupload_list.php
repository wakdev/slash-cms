<?php
/**
* @package		SLASH-CMS
* @subpackage	AJAXUPLOAD_PLUGIN
* @internal     Upload interface in ajax
* @version		ajaxupload_list.php
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

//Includes
include ("../../slash.php");

$slash = new Slash();
$slash->standalone_init();
$filestools = new sl_files();

//Check user
if (isset($_SESSION["id_user"]) && $_SESSION["id_user"] != null) {
	
	$slash->database->setQuery("SELECT * FROM ".$slash->database_prefix."users WHERE id=".$_SESSION["id_user"]);
	if (!$slash->database->execute()) {
		$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());
	}
	
	if($slash->database->rowCount()==0) { exit; }
	
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


/**************************************************/
/******** 			   ACTIONS 	  		   ********/
/**************************************************/

switch ($sl_mod_upload_action) {	

	
	case "delete":
		
		$ret = unlink($sl_mod_upload_file);
		if (!$ret){ echo $slash->trad_word("DELETE_FILE_FAIL")."<br />"; }
		
		$slash->database->setQuery("DELETE FROM ".$slash->database_prefix."attachments WHERE id_module=".$sl_mod_upload_id_module." AND id='".$sl_mod_upload_id_attachment."'");
		if (!$slash->database->execute()) {
			$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());
		}
		
			
		if ($sl_mod_upload_id!=0) {
		
			$slash->database->setQuery("SELECT * FROM ".$slash->database_prefix."attachments WHERE id_module='".$sl_mod_upload_id_module."' AND id_element='".$sl_mod_upload_id."' AND state='1' and id_field='".$sl_mod_field_id."' ORDER BY position ASC");
			if (!$slash->database->execute()) {
				$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());
			}
			
			if ($slash->database->rowCount()==0) {
			
				$path = "../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id."/";
			
				if(@ ! rmdir($path)) {
					echo $slash->trad_word("DELETE_DIR_FAIL")."<br />";
				}
				
			
			}else{ //New position
				$p = 1;
				
				foreach ($slash->database->fetchAll("BOTH") as $row) {
					$slash->database->setQuery("UPDATE ".$slash->database_prefix."attachments set position=".$p." WHERE id='".$row["id"]."'");
					if (!$slash->database->execute()) {
						$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());
					}
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
			
				$slash->database->setQuery("SELECT * FROM ".$slash->database_prefix."attachments WHERE id_module='".$sl_mod_upload_id_module."' AND id_user='".$_SESSION["id_user"]."' AND position='".$new_position."'");
				if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}
				
				$num_rows = $slash->database->rowCount();
				
				if ($num_rows != 0) {
					$row = $slash->database->fetch("ASSOC");
					$slash->database->setQuery("UPDATE ".$slash->database_prefix."attachments set position=".$sl_mod_upload_position_attachment." WHERE id_module='".$sl_mod_upload_id_module."' AND id_user='".$_SESSION["id_user"]."' AND position=".$new_position);
					if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}
					$slash->database->setQuery("UPDATE ".$slash->database_prefix."attachments set position=".$new_position." WHERE id=".$sl_mod_upload_id_attachment);
					if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}
				}
				
			}else{
			
				$slash->database->setQuery("SELECT * FROM ".$slash->database_prefix."attachments WHERE id_module='".$sl_mod_upload_id_module."' and id_element='".$sl_mod_upload_id."' and position='".$new_position."'");
				if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}
				
				$num_rows = $slash->database->rowCount();
				
				if ($num_rows != 0) {
					$row = $slash->database->fetch("ASSOC");
					$slash->database->setQuery("UPDATE ".$slash->database_prefix."attachments set position=".$sl_mod_upload_position_attachment." WHERE id_module='".$sl_mod_upload_id_module."' AND id_element='".$sl_mod_upload_id."' AND position=".$new_position);
					if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}
					$slash->database->setQuery("UPDATE ".$slash->database_prefix."attachments set position=".$new_position." WHERE id=".$sl_mod_upload_id_attachment);
					if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}
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
	$slash->database->setQuery("SELECT * FROM ".$slash->database_prefix."attachments WHERE id_user='".$_SESSION["id_user"]."' and id_module='".$sl_mod_upload_id_module."' and state='0' and id_field='".$sl_mod_field_id."' ORDER BY position"); 
	if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}	
	$numfields = $slash->database->rowCount();
	
	if ($numfields > 0) {
	
			
		foreach ($slash->database->fetchAll("BOTH") as $row) {
		
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
			<a href="#" class="upload_list_del_button" onclick="if(confirm('<?php echo $slash->trad_word("DELETE"); ?> : <?php echo $row["filename"]; ?> ?')){ 
			
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
		echo $slash->trad_word("NO_FILES");
	}
					
					
}else{
	/*--- EDIT MODE ---*/
	$slash->database->setQuery("SELECT * FROM ".$slash->database_prefix."attachments WHERE id_module='".$sl_mod_upload_id_module."' and id_element='".$sl_mod_upload_id."' and state='1' and id_field='".$sl_mod_field_id."' ORDER BY position"); 
	if (!$slash->database->execute()) {$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());}
	$numfields = $slash->database->rowCount();
	
	echo "<script type='text/javascript'>upload_count=".$numfields.";</script>";
	
	if ($numfields > 0) {
			
		foreach ($slash->database->fetchAll("BOTH") as $row) {
		
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
			
			<a href="#" class="upload_list_del_button" onclick="if(confirm('<?php echo $slash->trad_word("DELETE"); ?> : <?php echo $row["filename"]; ?> ?')){
			
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
		echo $slash->trad_word("NO_FILES");
	}

}

$slash->standalone_close();

?>



