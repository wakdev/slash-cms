<?php
/**
* @package		SLASH-CMS
* @subpackage	FORM_FUNCTIONS
* @internal     form functions
* @version		form.php - Version 10.4.2
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

* @addtogroup sl_functions
* @{

*/

class sl_form {

	/**
	 * Start form
	 * @param string $module_name Module name
	 * @param int $obj_id Obj ID
	 * @param string $act Form action
	 * @param string $get Add get params
	 */
	public static function start($module_name,$obj_id=0,$act="save",$get="") {
		
		echo "	<form name='".$module_name."_add_form' method='post' action='index.php?mod=".$module_name.$get."' enctype='multipart/form-data'>
				<input type='hidden' id='".$module_name."_act' name='".$module_name."_act' value='".$act."'>
				<input type='hidden' id='".$module_name."_id_obj' name='".$module_name."_id_obj' value='".$obj_id."'>";
	
	}

	/**
	 * Show title
	 * @param string $title Title
	 */
	public static function title($title) {
		echo "<span class='sl_form_title'>".$title."</span>";
	}
	
	/**
	 * Show error
	 * @param string $text Error text
	 */
	public static function error($text) {
		echo "<span class='sl_form_error'>&nbsp;&nbsp;<img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$text."</span>";
	}
	
	/**
	 * New line
	 * @param int $nb br number
	 */
	public static function br($nb) {
		for ($i=0;$i<$nb;$i++){
			echo "<br/>";
		}
	}
	
	/**
	 * Show input field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function input($module_name,$field_id,$options=null) {
	
		/* default options */
		if (!isset($options["value"])) {$options["value"]= "";} 
		if (!isset($options["size"])) {$options["size"]= "80";}
		if (!isset($options["js"])) {$options["js"]= "";} 		
		
		echo "<input id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' type='text' class='sl_form_input' size='".$options["size"]."' value=\"".htmlspecialchars($options["value"])."\" ".$options["js"].">";
	}
	
	/**
	 * Show hidden field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function hidden($module_name,$field_id,$options=null) {
	
		/* default options */
		if (!isset($options["value"])) {$options["value"]= "";}
		
		echo "<input id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' type='hidden' value=\"".$options["value"]."\">";
	}
	
	/**
	 * Show password field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function password($module_name,$field_id,$options=null) {
	
		/* default options */
		if (!isset($options["value"])) {$options["value"]= "";} 
		if (!isset($options["size"])) {$options["size"]= "80";}
		if (!isset($options["js"])) {$options["js"]= "";} 		
		
		echo "<input id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' type='password' class='sl_form_input' size='".$options["size"]."' value=\"".htmlspecialchars($options["value"])."\" ".$options["js"].">";
	}
	
	/**
	 * Show submit button
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function submit($module_name,$field_id=0,$options=null) {
	
		/* default options */
		
		if (!isset($options["value"])) {$options["value"]= "OK";} 
		/*
		if (!$options["size"]) {$options["size"]= "80";}
		if (!$options["js"]) {$options["js"]= "";} 		
		*/
		echo "<input type='submit' value='".$options["value"]."'>";
	}
	
	
	/**
	 * Show textarea field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function textarea($module_name,$field_id,$options=null) {
	
		/* default options */
		if (!isset($options["value"])) {$options["value"]= "";} 
		if (!isset($options["cols"])) {$options["cols"]= "50";}
		if (!isset($options["rows"])) {$options["rows"]= "5";}
		if (!isset($options["js"])) {$options["js"]= "";} 		
		
		echo "<textarea id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' cols='".$options["cols"]."' rows='".$options["rows"]."' ".$options["js"]." class='sl_form_textarea'>".$options["value"]."</textarea>";
		
	}
	
	/**
	 * Show select field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function select($module_name,$field_id,$options=null) {
		
		if (!isset($options["js"])) {$options["js"]= "";} 		
		if (!isset($options["value"])) {$options["value"] = "";} 
		if (!isset($options["values"])) {$options["values"] = "";} 
		if (!isset($options["texts"])) {$options["texts"]= "";} 			
		
		echo "<select id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' class='sl_form_select' ".$options["js"].">";
						
		for ($i=0; $i<count($options["values"]); $i++) {
			echo "<option value='".$options["values"][$i]."' ";
			if ($options["values"][$i] == $options["value"]) {
				echo "selected";
			}
			echo ">".$options["texts"][$i]."</option>";
		}
						
		echo "</select>";
	
	}
	
	/**
	 * Show select multiple field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function select_multiple($module_name,$field_id,$options=null) {
	
		if (!isset($options["js"])) {$options["js"]= "";}
		if (!isset($options["class"])) {$options["class"]= "sl_form_select";}
		
		echo "<select id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."[]' class='".$options["class"]."' multiple='multiple' ".$options["js"].">";
						
		for ($i=0; $i<count($options["values"]); $i++) {
			echo "<option value='".$options["values"][$i]."' ";
			
			if (in_array($options["values"][$i],$options["selected"])){
				echo "selected";
			}
			echo " >".$options["texts"][$i]."</option>";
		}
						
		echo "</select>"; 
	
	}
	
	/**
	 * Show TinyMCE
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function tinymce($module_name,$field_id,$options=null) {
	
		if (!isset($options["value"])) {$options["value"]= "";} 
		if (!isset($options["style"])) {$options["style"]= "";} 
		if (isset($options["css"])) { $css = $options["css"]; } else { $css = ""; }
		if (isset($options["script_url"])) { $script_url = $options["script_url"]; } else { $script_url = "../core/plugins/tiny_mce/tiny_mce.js"; }
		if (isset($options["theme"])) { $theme = $options["theme"]; } else { $theme = "advanced"; }
		
		echo "<textarea id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' style='".$options["style"]."'>".$options["value"]."</textarea>";
				

		echo '<script type="text/javascript">
					$(document).ready(function(){ 
						$("#'.$module_name.'_obj'.$field_id.'").tinymce({
							// Location of TinyMCE script
							script_url : "'.$script_url.'",
							
							// General options
							mode : "textareas",
							theme : "'.$theme.'",
							language : "fr",
							
							// Automatic <p>
							/*
							forced_root_block : false,
							force_br_newlines : true,
							force_p_newlines : false,
							*/
							
							relative_urls : true,
							file_browser_callback : "tinyBrowser",
							skin : "o2k7",
							plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

							// Theme options
							theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
							theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
							theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
							
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,

							// Example word content CSS (should be your site CSS) this one removes paragraph margins
							content_css : "'.$css.'",

							// Drop lists for link/image/media/template dialogs
							//template_external_list_url : "lists/template_list.js",
							//external_link_list_url : "lists/link_list.js",
							//external_image_list_url : "lists/image_list.js",
							//media_external_list_url : "lists/media_list.js",

							// Replace values for the template plugin
							//template_replace_values : {
							//	username : "Some User",
							//	staffid : "991234"
							//}			
						});
						
						
					});
				</script>';

	}
	
	
	/**
	 * Show ckeditor
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function ckeditor($module_name,$field_id,$options=null) {
	
		if (!isset($options["value"])) {$options["value"]= "";} 
		if (!isset($options["style"])) {$options["style"]= "";} 
		if (isset($options["css"])) { $css = $options["css"]; } else { $css = ""; }
		if (isset($options["theme"])) { $theme = $options["theme"]; } else { $theme = "advanced"; }
		if (isset($options["finder"])) { $finder = $options["finder"]; } else { $finder = "kcfinder"; }

		
		echo "<textarea id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' style='".$options["style"]."'>".$options["value"]."</textarea>";
		
		//default
		if ($finder == "kcfinder"){
			echo '<script type="text/javascript">
			
					CKEDITOR.replace( "'.$module_name.'_obj'.$field_id.'",
						{
							fullPage : false,
							skin : "slashcms",
							toolbar : "SlashCMSToolbar",
							filebrowserBrowseUrl : "../core/plugins/kcfinder/browse.php?type=files",
	 						filebrowserImageBrowseUrl : "../core/plugins/kcfinder/browse.php?type=images",
						    filebrowserFlashBrowseUrl : "../core/plugins/kcfinder/browse.php?type=flash",
						   	filebrowserUploadUrl : "../core/plugins/kcfinder/upload.php?type=files",
						   	filebrowserImageUploadUrl : "../core/plugins/kcfinder/upload.php?type=images",
						   	filebrowserFlashUploadUrl : "../core/plugins/kcfinder/upload.php?type=flash",
						});
				</script>';
		}
		
		
		if ($finder == "ckfinder"){
				
			echo '<script type="text/javascript">
				CKEDITOR.replace( "'.$module_name.'_obj'.$field_id.'",
					{
						fullPage : false,
						skin : "slashcms",
						toolbar : "SlashCMSToolbar",
						filebrowserBrowseUrl : "../core/plugins/ckfinder/ckfinder.html",
						filebrowserImageBrowseUrl : "../core/plugins/ckfinder/ckfinder.html?type=Images",
						filebrowserFlashBrowseUrl : "../core/plugins/ckfinder/ckfinder.html?type=Flash",
						filebrowserUploadUrl : "../core/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&currentFolder=/archive/",
						filebrowserImageUploadUrl : "../core/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=/cars/",
						filebrowserFlashUploadUrl : "../core/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
					});
			</script>';
		}
			
	}
	
	/**
	 * Show attachments
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function attachments ($module_name,$field_id,$options=null) {
		
		$slash = &$GLOBALS["slash"];
		
		$id_module = $slash->sl_module_id($module_name);
		
							
		if (!$id_module) { 
			$slash->show_fatal_error("UNKNOWN_MODULE_ERROR","MODULE ID NOT FOUND");
			exit();
		}
		/*
		if(!$options["lang"]){
			 $options["lang"] = '';
		}*/
		
		/***********************/
		/* CLEAR USER TMP DIR */
		/***********************/
		
		$slash->database->setQuery("SELECT * FROM sl_attachments WHERE id_user=".$_SESSION["id_user"]." and state=0");
		if (!$slash->database->execute()) {
			$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());
		}
		
		foreach ($slash->database->fetchAll("BOTH") as $row_tmp) {
				
			if (is_file("../tmp/".$row_tmp["filename"])) {
				unlink("../tmp/".$row_tmp["filename"]);
			}
			
		}
		
		$slash->database->setQuery("DELETE FROM sl_attachments WHERE id_user=".$_SESSION["id_user"]." and state=0");
		if (!$slash->database->execute()) {
			$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());
		}
		/***************************/
		
		
		

		if (!isset($options["max_upload"]) || $options["max_upload"] == 0) {$options["max_upload"] = 999;}
		if (!isset($options["min_upload"]) || $options["min_upload"] == 0) {$options["min_upload"] = 0;}
		if (!isset($options["img_resize"]) || $options["img_resize"] == 0) {$options["img_resize"] = false;}
		if (!isset($options["img_max_width"]) || $options["img_max_width"] == 0) {$options["img_max_width"] = "auto";}
		if (!isset($options["img_max_height"]) || $options["img_max_height"] == 0) {$options["img_max_height"] = "auto";}
		
		if ($options["item_id"] != ""){ 
		
			$slash->database->setQuery("SELECT * FROM sl_attachments WHERE  id_module='".$id_module."' and id_element='".$options["item_id"]."' and id_field='".$field_id."' and state=1");
			if (!$slash->database->execute()) {
				$slash->show_fatal_error("QUERY_ERROR",$slash->database->getError());
			}
			
			$start_count = $slash->database->rowCount();
		}else{
			$start_count = 0;
		}
		
		
		echo "<table width='400' cellspacing='0' cellpadding='0' border='0' >
				<tr>	<td align='left' width='70%'>";						
		echo "			<div id='sl_mod_upload_button_".$field_id."' class='upload_button'>".$slash->trad_word("UPLOAD")."</div>";
		echo "		</td></tr>
				<tr>
					<td align='left' width='70%'>";	
		echo "			<div id='sl_mod_upload_list_".$field_id."' class='sl_mod_upload_list'><div class='sl_mod_upload_loader'></div>&nbsp;&nbsp;Loading ...</div>";
		echo "	</td></tr></table>";
		
		echo "<script type='text/javascript'>
			var upload_count_".$field_id."=".$start_count .";
			
	
			$(document).ready(function(){
			
			var button_".$field_id." = $('#sl_mod_upload_button_".$field_id."'), interval;
			var ajaxupload_".$field_id." = new AjaxUpload(button_".$field_id.",{
				action: '../core/plugins/ajaxupload/ajaxupload.php',
				name: 'sl_userfile',
				data: {
					sl_mod_field_id : '".$field_id."',
					sl_mod_upload_id : '".$options["item_id"]."',
					sl_mod_upload_id_module: '".$id_module."',
					sl_mod_upload_files_dir: '".$options["files_dir"]."',
					sl_mod_img_resize: '".$options["img_resize"]."',
					sl_mod_img_max_width: '".$options["img_max_width"]."',
					sl_mod_img_max_height: '".$options["img_max_height"]."'
				},

				onSubmit : function(file, ext){
					
					
					
					// check extension
					if (! (ext && /^(".$options["accept"].")$/.test(ext))){
							
							alert('".$slash->trad_word("INVALID_EXT")." !');
							return false;
					}
					
					// check max upload
					if (upload_count_".$field_id." >= ".$options["max_upload"].") {
						alert('".$slash->trad_word("MAX_UPLOAD_LIMIT")." !');
						return false;
					}
					
					
					button_".$field_id.".text('".$slash->trad_word("UPLOAD")."');
					this.disable();
					
					// Uploding -> Uploading. -> Uploading...
					interval = window.setInterval(function(){
						var text = button_".$field_id.".text();
						if (text.length < 13){
							button_".$field_id.".text(text + '.');					
						} else {
							button_".$field_id.".text('".$slash->trad_word("UPLOAD")."');				
						}
					}, 200);
					
				},
				
				onComplete: function(file, response){
					
					button_".$field_id.".text('".$slash->trad_word("UPLOAD")."');	
					window.clearInterval(interval);
					this.enable();

					if (response.indexOf('success')!=-1){
						upload_count_".$field_id."++;
					}else{
						alert('".$slash->trad_word("FILE_TRANFERT_FAIL")." : '+response);
					}
					
					// add file to the list
					$('#sl_mod_upload_list_".$field_id."').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
						sl_mod_field_id: '".$field_id."',
						sl_mod_upload_id: '".$options["item_id"]."',
						sl_mod_upload_id_module: '".$id_module."',
						sl_mod_upload_min: '".$options["min_upload"]."',
						sl_mod_upload_files_dir: '".$options["files_dir"]."',
						sl_mod_img_resize: '".$options["img_resize"]."',
						sl_mod_img_max_width: '".$options["img_max_width"]."',
						sl_mod_img_max_height: '".$options["img_max_height"]."'
						});		
				}
			});
			
			$('#sl_mod_upload_list_".$field_id."').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
				sl_mod_field_id: '".$field_id."',
				sl_mod_upload_id: '".$options["item_id"]."',
				sl_mod_upload_id_module: '".$id_module."',
				sl_mod_upload_min: '".$options["min_upload"]."',
				sl_mod_upload_files_dir: '".$options["files_dir"]."',
				sl_mod_img_resize: '".$options["img_resize"]."',
				sl_mod_img_max_width: '".$options["img_max_width"]."',
				sl_mod_img_max_height: '".$options["img_max_height"]."'
				});
			
			});
			
			</script>";
	
	}
	
	/**
	 * Show date field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function date($module_name,$field_id,$options=null) {
	
	
	
		echo "<input id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' type='text' class='sl_form_date' readonly size='30' >";	
		echo "<div id='datepicker_".$field_id."' name='datepicker_".$field_id."' style='font-size:80%;'></div>";

		echo "	<script type='text/javascript'> 
					$(document).ready(function(){ 
						var current_date = '".$options["value"]."';
						
						$('#datepicker_".$field_id."').datepicker({ altField: '#".$module_name."_obj".$field_id."' }); ";
						
		if ($options["value"] != "" && $options["value"] != "0000-00-00") {
			
			$date = explode("-",$options["value"]);
			$day = $date[2];
			$month = $date[1] - 1;
			$year = $date[0];
			
			echo "$('#datepicker_".$field_id."').datepicker( 'setDate',  new Date(".$year.",".$month.",".$day.") );";
		}
		
		echo "	});</script>";
	
	}
	
	/**
	 * Show date bootstrap field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function dateBS($module_name,$field_id,$options=null) {
	
		echo "<div class='sl_date'>";
		echo "<div class='input-append date' id='".$module_name."_div".$field_id."' data-date='".$options["value"]."' data-date-format='dd-mm-yyyy'>";
		echo "<input class='span2' id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' size='16' type='text' value='".$options["value"]."'>";
		echo "<span class='add-on'><i class='icon-th'></i></span>";
		echo "</div>";
		
		echo "	<script type='text/javascript'> 
					$(document).ready(function(){ ";			
		echo "$('#".$module_name."_div".$field_id."').datepicker()";
		echo "	});</script>";
		
		echo "</div>";
	}
	
	/**
	 * Show datetime bootstrap field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function datetimeBS($module_name,$field_id,$options=null) {
	
		$created_date_timestamp = strtotime($options["value"]);
			
		if (!isset($options["value"]) || $created_date_timestamp == 0) {
			$created_date = date("d-m-Y");
			$created_time = date("H:i:s");
		}else{
			$created_date = date("d-m-Y",$created_date_timestamp);
			$created_time = date("H:i:s",$created_date_timestamp);
		}
		
		echo "<div class='sl_date'>
				<div class='input-append date' id='".$module_name."_div".$field_id."_date' data-date='".$created_date."' data-date-format='dd-mm-yyyy'>
						<input class='span2' id='".$module_name."_obj".$field_id."_date' name='".$module_name."_obj".$field_id."_date' size='16' type='text' value='".$created_date."'>
								<span class='add-on'><i class='icon-th'></i></span>
				</div>
			</div>";
		
		echo "<div class='sl_time'>";
			sl_form::input($module_name,$field_id."_time",array("value" => $created_time , "size"=>10));
		echo "</div>";
	
		echo "	<script type='text/javascript'>
					$(document).ready(function(){ 
						$('#".$module_name."_div".$field_id."_date').datepicker();
				});</script>";
	
	}
	
	    
	/**
	 * Show colorpicker field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function colorpicker($module_name,$field_id,$options=null) {
	
		/* default options */
		if (!isset($options["value"])) {$options["value"]= "FFFFFF";} 

		echo "<input id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' type='hidden' value=\"".$options["value"]."\">";
		
		echo "<div id='colorpicker_".$field_id."' class='colorSelector'><div style='background-color:#".$options["value"]."; '></div></div>";
		
		echo "	<script type='text/javascript'> 
					$(document).ready(function(){ ";
					
		echo "$('#colorpicker_".$field_id."').ColorPicker({
					color: '#".$options["value"]."',
					onShow: function (colpkr) {
						$(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						$(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						$('#colorpicker_".$field_id." div').css('backgroundColor', '#' + hex);
						$('#".$module_name."_obj".$field_id."').val(hex);
					}
			});";
					
		echo "	});</script>";
		
	}
	
	/**
	 * Show checkbox field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function checkbox($module_name,$field_id,$options=null) {
	
		/* default options */
		if (isset($options["value"]) && $options["value"] == 1) { $state = "checked"; } else { $state = "";}
		if (!isset($options["js"])) {$options["js"]= "";} 
		
		
		echo "<input id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' type='checkbox' ".$state." value='1' ".$options["js"].">";
		
	}
	
	/**
	 * Show radio field
	 * @param string $module_name Module name
	 * @param string/int $field_id Field ID
	 * @param array $options Field options
	 */
	public static function radio($module_name,$field_id,$options=null) {
		
		if (!isset($options["value"])) {$options["value"]= "";}
		if (!isset($options["js"])) {$options["js"]= "";}
		if (!isset($options["values"])) {$options["values"] = "";}
		
		for ($i=0; $i<count($options["values"]); $i++) {
			echo "<input value='".$options["values"][$i]."' ";
			
			echo "type='radio' id='".$module_name."_obj".$field_id."' name='".$module_name."_obj".$field_id."' ";
			
			if ($options["values"][$i] == $options["value"]) {
				echo "checked";
			}
			
			echo " ".$options["js"].">".$options["texts"][$i]."</input>";
		}
		
	}
	
	
	/**
	 * End Form
	 */
	public static function end() {
		echo "</form>";
	}
	
}

/** 
* @} 
*/

?>