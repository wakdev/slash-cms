<?php
/**
* @package		SLASH-CMS
* @subpackage	AJAXUPLOAD_PLUGIN
* @internal     Upload interface in ajax
* @version		ajaxupload.php - Version 9.12.16
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


Notes : 
2013-03-21 - Julien Veuillet [http://www.wakdev.com] : Error when the file already exists

*/

/**************************************************/
/******** 		CONFIGURATION		********/
/**************************************************/
session_start();
include ("../../config/sl_config.php");
include ("../../common/class/functions/includes/sl_files.php");
include ("../../common/class/functions/includes/sl_images.php");
$config = new SLConfig();
$filestools = new sl_files();
$imagestools = new sl_images();
$host = $config->db_host; 
$login = $config->db_user; 
$password = $config->db_password; 
$database_name = $config->db_name; 

$database = mysql_connect($host, $login, $password) or die ("CONNEXION ERROR");	
mysql_select_db($database_name, $database) or die ("DATABASE CONNEXION ERROR");

// Get post values
$sl_mod_field_id = $_POST["sl_mod_field_id"];
$sl_mod_upload_id = $_POST["sl_mod_upload_id"];
$sl_mod_upload_id_module = $_POST["sl_mod_upload_id_module"];
$sl_mod_upload_files_dir = $_POST["sl_mod_upload_files_dir"];

//Images function
$sl_mod_img_resize;
$sl_mod_img_max_width;
$sl_mod_img_max_height;

if (isset($_POST["sl_mod_img_resize"])) { $sl_mod_img_resize = $_POST["sl_mod_img_resize"]; } else { $sl_mod_img_resize = false; }
if (isset($_POST["sl_mod_img_max_width"])) { $sl_mod_img_max_width = intval($_POST["sl_mod_img_max_width"]); } else { $sl_mod_img_max_width = "auto"; }
if (isset($_POST["sl_mod_img_max_height"])) { $sl_mod_img_max_height = intval($_POST["sl_mod_img_max_height"]); } else {$sl_mod_img_max_height = "auto"; }


/**************************************************/
/******** 		UPLOAD						********/
/**************************************************/
if ($sl_mod_upload_id==0){ /*--- NEW MODE ---*/

	$filename = $filestools->format_name($_FILES['sl_userfile']['name']);
	$uploadfile = "../../../tmp/".$filename;
	
	// File already exists
	if(file_exists($uploadfile)) {
		echo "The file already exists";
		exit();
	}
	
	//Upload
	if (move_uploaded_file($_FILES['sl_userfile']['tmp_name'], $uploadfile)) {
		if (!chmod ($uploadfile, 0777)) {
			echo "Error : CHMOD ".$uploadfile;
			exit();
		}
		
		//Image resize
		if ($sl_mod_img_resize == true){
			$ret = $imagestools->create_image($uploadfile,$uploadfile,$sl_mod_img_max_width,$sl_mod_img_max_height);
			if ($ret == 0) { 
				unlink ($uploadfile);
				echo "Error : IMG RESIZE ".$uploadfile; 
				exit(); 
			}
		}
		
	} else {
		echo "Error : MOVE UPLOAD FILE ".$uploadfile;
		exit();
	}
	
	/* Insert data */
	$result_pos = mysql_query("SELECT * FROM sl_attachments WHERE id_user='".$_SESSION["id_user"]."' AND id_module='".$sl_mod_upload_id_module."' AND id_element='0' AND state='0' and id_field='".$sl_mod_field_id."' ",$database) 
			or die ("QUERY ERROR : ".mysql_error());
	$next_position = mysql_num_rows($result_pos) + 1;
	
	$result = mysql_query("INSERT INTO sl_attachments
						(id,id_user,id_module,id_element,id_field,filename,position,state) value
						('','".$_SESSION["id_user"]."','".$sl_mod_upload_id_module."','0','".$sl_mod_field_id."','".$filename."','".$next_position."','0')",$database) 
						or die ("QUERY ERROR : ".mysql_error());
	echo "success";

}else{ /*--- EDIT MODE ---*/
	
	
	$filename = $filestools->format_name($_FILES['sl_userfile']['name']);
	$uploadfile = "../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id."/".$filename;
	
	// if directory not exist 
	if(!file_exists("../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id)) {
		
		$filestools->make_dir("../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id,true);
		/*
		if (!mkdir("../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id, 0777)){
			echo "Error : MKDIR "."../../../".$sl_mod_upload_files_dir."/".$sl_mod_upload_id;
		}*/
	}
	
	// File already exists
	if(file_exists($uploadfile)) {
		echo "The file already exists";
		exit();
	}
	
	//Upload
	if (move_uploaded_file($_FILES['sl_userfile']['tmp_name'], $uploadfile)) {
		if (!chmod ($uploadfile, 0777)) {
			echo "Error : CHMOD ".$uploadfile;
			exit();
		}
		
		//echo "Redim : MOVE UPLOAD FILE ".$sl_mod_img_max_width."".$sl_mod_img_max_height;
		//exit();
		
		//Image resize
		if ($sl_mod_img_resize == true){
			$ret = $imagestools->create_image($uploadfile,$uploadfile,$sl_mod_img_max_width,$sl_mod_img_max_height);
			if ($ret == 0) { 
				unlink ($uploadfile);
				echo "Error : IMG RESIZE ".$uploadfile; 
				exit(); 
			}
		}
		
	} else {
		echo "Error : MOVE UPLOAD FILE ".$uploadfile;
		exit();
	}
	
	/* Insert data */
	$result_pos = mysql_query("SELECT * FROM sl_attachments WHERE id_element='".$sl_mod_upload_id."' AND id_module='".$sl_mod_upload_id_module."' AND state='1' and id_field='".$sl_mod_field_id."' ",$database) 
			or die ("QUERY ERROR : ".mysql_error());
	$next_position = mysql_num_rows($result_pos) + 1;
	
	$result = mysql_query("INSERT INTO sl_attachments
						(id,id_user,id_module,id_element,id_field,filename,position,state) value
						('','".$_SESSION["id_user"]."','".$sl_mod_upload_id_module."','".$sl_mod_upload_id."','".$sl_mod_field_id."','".$filename."','".$next_position."','1')",$database) 
						or die ("QUERY ERROR : ".mysql_error());
	
	echo "success";
	
}

?>



