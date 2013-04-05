<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_news
* @internal     Admin news module
* @version		sla_news_view.php - Version 11.5.31
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

* @addtogroup sla_news
* @{

*/


class sla_news_view extends slaView implements iView{

	
	/**
	 * Show global HTML Header
	 */
	public function header () {
		sl_interface::script("../core/plugins/jquery_plugins/interface/js/interface.js");
		sl_interface::script("../core/plugins/jquery_plugins/preload/js/preloadCssImages.js");
	} 
	  
	public function l_header () {
		sl_interface::script("../core/plugins/jquery_plugins/pager/js/pager.js");
	}
	
	public function f_header () {
		sl_interface::script("../core/plugins/jquery_plugins/ajaxupload/js/ajaxupload.js");
		sl_interface::script("../core/plugins/bootstrap_plugins/datepicker/js/bootstrap-datepicker.js"); //date picker fr
		sl_interface::script("../core/plugins/ckeditor/ckeditor.js");
		
		/*
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/datepicker/js/datepicker.js'></script> \n"; //date picker
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/datepicker/js/datepicker-fr.js'></script> \n"; //date picker fr
		echo "<link rel='stylesheet' type='text/css' media='screen' href='../core/plugins/jquery_plugins/ui/themes/roller/css/roller.css'>"; //Theme roller
		*/

		sl_interface::stylesheet("../core/plugins/bootstrap_plugins/datepicker/css/datepicker.css","screen"); //Theme roller
	}
	
	
	/**
	 * Show categories list
	 * @param $message message
	 */
	public function show_items($message="") {
		
		echo "<form name='".$this->controller->module_name."_nav_form' method=post action='index.php?mod=".$this->controller->module_name."'>
			  <input type='hidden' id='".$this->controller->module_name."_act' name='".$this->controller->module_name."_act' value=''>
			  <input type='hidden' id='".$this->controller->module_name."_valid' name='".$this->controller->module_name."_valid' value=''>";
		
		//listing hidden fields
		sl_interface::listing_hidden_fields($this->controller->module_name);
		
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>
				<tr><td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>
						<td class='sl_mod_title' align='left' width='50%'>
						<img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> 
						".$this->slash->trad_word("NEWS_TITLE");
						
		sl_interface::create_message_zone($message); //Message
						
		echo "</td><td class='sl_mod_control'>";
					
		//control buttons
		sl_interface::create_control_buttons($this->controller->module_name,array('add','edit','publish','unpublish','delete'));		
					
		echo "</td></tr></table>";
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='sl_mod_list'>";
				
		//create states selection
		
		
		$row_states = array(
						array("id"=> "0", "state" => $this->slash->trad_word("NEWS_IN_PROGRESS")), 
						array("id"=> "1","state" => $this->slash->trad_word("NEWS_PAST")));
		
		
		sl_interface::create_categorie_selection($this->controller->module_name,$this->slash->trad_word("CATEGORIE"),$row_states,"state",1);
				
		$this->controller->news->load_items(); // Load articles list
								
		echo "</td></tr></table></td></tr></table></form>";
					
	}


	
	/**
	 * HTML footer
	 */
	public function footer() {
	
		
		echo "
		<script type='text/javascript'> 
 
			$(document).ready(function(){ 
			
			$('#message').css('opacity',1);
			$('#message').stopAll().pause(5000).fadeTo(400,0, function() { $(this).hide(); } );
			
			$.preloadCssImages();
				
		}); 

 		 			
		</script>";
	
	}
	
	
	
	/**
	 * Show article form
	 */
	public function show_form ($id=0,$values=null,$errors=null) {
		
		$mn = $this->controller->module_name;
		$code = "";
		sl_form::start($mn,$id);
		
		if ($id != 0) { 
			
			$utitle = $this->slash->trad_word("EDIT");
			
			$values["timein"] = substr($values["datein"], 11, 5);
			$values["timeout"] = substr($values["dateout"], 11, 5);
			$values["datein"] = substr($values["datein"], 0, 10);
			$values["dateout"] = substr($values["dateout"], 0, 10);
			
			if ($values["dateout"] == "0000-00-00") {
				
				$values["permanent"] = 1;
				
				$code = "<script type='text/javascript'>
							$(document).ready(function(){
								document.".$this->controller->module_name."_add_form.".$mn."_obj5.checked = true;
								$('#".$mn."_divP').hide();
							});
						</script>";
			}else{
				
				$values["permanent"] = 0;
			}
			
			
		} else { 
			$utitle = $this->slash->trad_word("ADD");
			$values["permanent"] = 0;
			
		}
		
		
		echo "<div class='sl_adm_form_top'>";
			sl_interface::create_title($mn,$this->slash->trad_word("NEWS_TITLE"),$utitle);
			sl_interface::create_buttons($mn,array("save","back"));
		echo "</div>";
		
		$js_perm = "onclick=\"javascript:
						$('#".$mn."_divP').toggle();
						\"";
		
		
		echo "<div class='sl_adm_form_main'>";
		
		
		if (!isset($values["title"])) {$values["title"] = "";};
		sl_form::title($this->slash->trad_word("TITLE")." : ");
		sl_form::input($mn,0,array("value" => $values["title"]));
		if (isset($errors[0]["message"])) {sl_form::error($errors[0]["message"]);}
		sl_form::br(2);
		
		if (!isset($values["content"])) {$values["content"] = "";};
		sl_form::title($this->slash->trad_word("DESCRIPTION")." : ");
		sl_form::br(2);
		sl_form::ckeditor($mn,1,array("value"=>$values["content"]));
		sl_form::br(2);
		
		
		sl_form::title($this->slash->trad_word("FILES_ATTACHMENTS")." : ");
		sl_form::br(2);
		sl_form::attachments($mn,2,array(
				"item_id"=> $id,
				"files_dir" => "medias/attachments/sl_news",
				"accept" => "gif|jpg|txt|pdf"));
		sl_form::br(2);
		
		if (!isset($values["datein"])) {$values["datein"] = "";};
		sl_form::title($this->slash->trad_word("NEWS_PUBLISH_DATE")." : ");
		sl_form::dateBS($mn,3,array("value" => $values["datein"]));
		if (isset($errors[3]["message"])) { sl_form::error($errors[3]["message"]); }
		sl_form::br(1);
		
		if (!isset($values["timein"])) {$values["timein"] = "";};
		sl_form::title($this->slash->trad_word("NEWS_PUBLISH_TIME")." ( HH:MM ) : ");
		sl_form::input($mn,4,array("value" => $values["timein"]));
		if (isset($errors[4]["message"])) {
			sl_form::error($errors[4]["message"]);
		}
		sl_form::br(2);
		
		sl_form::title($this->slash->trad_word("NEWS_PERMANENT")." : ");
		sl_form::checkbox($mn,5,array("value" => $values["permanent"],"js"=>$js_perm));
		
		sl_form::br(2);
		
		echo "<div id='".$mn."_divP' style='padding:10px;border:1px #bbb solid; background-color:#DDD'>";
		
			if (!isset($values["dateout"])) {$values["dateout"] = "";};
			sl_form::title($this->slash->trad_word("NEWS_UNPUBLISH_DATE")." : ");
			sl_form::dateBS($mn,6,array("value" => $values["dateout"],));
			if (isset($errors[6]["message"])) {
				sl_form::error($errors[6]["message"]);
			}
			sl_form::br(2);
			
			if (!isset($values["timeout"])) {$values["timeout"] = "";};
			sl_form::title($this->slash->trad_word("NEWS_UNPUBLISH_TIME")." ( HH:MM ) : ");
			sl_form::input($mn,7,array("value" => $values["timeout"]));
			if (isset($errors[7]["message"])) {
				sl_form::error($errors[7]["message"]);
			}
			sl_form::br(2);
		
		echo "</div>";
		
		sl_form::br(2);
		
		if (!isset($values["enabled"])) {$values["enabled"] = "";};
		sl_form::title($this->slash->trad_word("ACTIVE")." : ");
		sl_form::checkbox($mn,8,array("value" => $values["enabled"]));
		
		
		echo "</div>";
		
		sl_form::end();
		
		echo $code;
		
	}
	
	
	
	
	
	/**
	 * Show article form
	 */
	public function show_delete ($id_array) {
		
		echo "	<form name='".$this->controller->module_name."_del_form' method=post action='index.php?mod=".$this->controller->module_name."'>
				<input type='hidden' id='".$this->controller->module_name."_act' name='".$this->controller->module_name."_act' value='delete'>
				<input type='hidden' id='".$this->controller->module_name."_valid' name='".$this->controller->module_name."_valid' value='1'>
				
				<table width='100%' cellspacing='0' cellpadding='0' border='0'>
				<tr>
				<td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'>
					<tr>
						<td class='sl_mod_title'>
						<img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> 
						".$this->slash->trad_word("NEWS_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("DELETE")."</span></td>
					
						<td class='sl_mod_control'>
							<table align='right' width='200'>
							<tr>
								<td align='center' width='50%'>
										
									<a href='#' class='del_button' onClick=\"javascript:submitForm('".$this->controller->module_name."','del_apply');\"></a>
									".$this->slash->trad_word("DELETE")."</td>
									
								<td align='center' width='50%'>			
									<a href='index.php?mod=".$this->controller->module_name."' class='undo_button'></a>
									".$this->slash->trad_word("BACK")."				
								</td>

							</tr>
							</table>	
						</td>
					</tr>
					</table>
					
					<br />
					<table width='600' cellspacing='0' cellpadding='10' border='0' align='center' style='border:1px solid #333333;'>
					<tr>
						<td align='left'>		
							<table width='600' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='50%'>".$this->slash->trad_word("NEWS_DELETE_CONFIRM")." 
								</td>
							</tr>";
							
					if (count ($id_array) != 0) {
						$count=0;		
						foreach ($id_array as $value) {
								
								$article = $this->controller->news->load_item($value);
								
								echo "<tr><td align='left' class='sl_mod_delete_text' width='50%'>
										<input type='checkbox' id='".$this->controller->module_name."_checked[".$count."]' name='".$this->controller->module_name."_checked[".$count."]' value='".$article["id"]."' checked style='display:none;'  />
										".$article["title"]."
										</td>
									</tr>";
								$count++;
						}
						
					}
						echo"	</table>
						</td>
					</tr>
					
					</table>	
								
			</td>
			</tr></table></form>

				";
				
	}
	
	
	
}


/** 
* @} 
*/


?>