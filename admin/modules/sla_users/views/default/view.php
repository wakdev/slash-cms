<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_users
* @internal     Admin articles module
* @version		view.php - Version 11.5.30
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

* @addtogroup sla_users
* @{

*/


class sla_users_view extends slaView implements iView{
	

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
		sl_interface::script("../core/plugins/tiny_mce/jquery.tinymce.js");
		sl_interface::script("../core/plugins/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php");
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
						".$this->slash->trad_word("USERS_TITLE");
						
		sl_interface::create_message_zone($message); //Message
						
		echo "</td><td class='sl_mod_control'>";
					
		//control buttons
		sl_interface::create_control_buttons($this->controller->module_name,array('add','edit','publish','unpublish','delete'));		
					
		echo "</td></tr></table>";
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='sl_mod_list'>";
		
		$row_groupe = array(
						array("id"=> "0","title" => $this->slash->trad_word("USERS_GR_ADMINISTRATOR")), 
						array("id"=> "1","title" => $this->slash->trad_word("USERS_GR_MANAGEMENT")),
						array("id"=> "2","title" => $this->slash->trad_word("USERS_GR_REDACTION"))
						);
				
		//create cat selection
		sl_interface::create_categorie_selection($this->controller->module_name,$this->slash->trad_word("GROUP"),$row_groupe,"title",1);
				
		$this->controller->users->load_items(); // Load articles list
								
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
	* Form
	*/
	public function show_form($id=0, $values=null, $errors=null) {
	
		$mn = $this->controller->module_name;
		
		sl_form::start($mn,$id);
		
		if ($id != 0) { $utitle = $this->slash->trad_word("EDIT");
		} else { $utitle = $this->slash->trad_word("ADD");}
		
		echo "<div class='sl_adm_form_top'>";
			sl_interface::create_title($mn,$this->slash->trad_word("USERS_TITLE"),$utitle);
			sl_interface::create_buttons($mn,array("save","back"));
		echo "</div>";
				
		echo "<div class='sl_adm_form_main'>";
		
		/**Test pour interdir la modification depuis un niveau d'accès plus bas que celui qu'on veut modifier**/
		/**EDIT Attention dans le cas d'un ajout**/
		if ($this->controller->users->get_grade_acces($this->controller->users->get_grade_id($id)) || $id == 0 ){
			/**Test pour interdir la modification d'un autre compte au niveau 2 (redacteur)**/
			if($this->controller->users->get_id_acces($id)){

					sl_form::title($this->slash->trad_word("NAME")." : ");
					sl_form::input($mn,1,array("value" => $values["name"]));
					if (isset($errors[1]["message"])) { sl_form::error($errors[1]["message"]); }
					sl_form::br(2);
					
					sl_form::title($this->slash->trad_word("LOGIN")." * : ");
					sl_form::input($mn,2,array("value" => $values["login"]));
					if (isset($errors[2]["message"])) { sl_form::error($errors[2]["message"]); }
					sl_form::br(2);
					
					if (!isset($values["_password"])){$values["_password"]="";}
					sl_form::title($this->slash->trad_word("PASSWORD")." * : ");
					sl_form::password($mn,3,array("value" => $values["_password"]));
					if (isset($errors[3]["message"])) { sl_form::error($errors[3]["message"]); }
					sl_form::br(2);
					
					if (!isset($values["_password2"])){$values["_password2"]="";}
					sl_form::title($this->slash->trad_word("PASSWORD")." * : ");
					sl_form::password($mn,4,array("value" => $values["_password2"]));
					if (isset($errors[4]["message"])) { sl_form::error($errors[4]["message"]); }
					sl_form::br(2);
					
					if (!isset($values["mail"])){$values["mail"]="";}
					sl_form::title($this->slash->trad_word("MAIL")." : ");
					sl_form::input($mn,5,array("value" => $values["mail"]));
					if (isset($errors[5]["message"])) { sl_form::error($errors[5]["message"]); }
					sl_form::br(2);
					
					if (!isset($values["grade"])){$values["grade"]=0;}
					sl_form::title($this->slash->trad_word("GROUP")." : ");
					if ($this->controller->users->get_grade_id_current_user() == 0) {
						sl_form::select($mn,6,array("value" => $values["grade"], 
										"values" => array(0,1,2), 
										"texts" => array($this->slash->trad_word("USERS_GR_ADMINISTRATOR"),
													$this->slash->trad_word("USERS_GR_MANAGEMENT"),
													$this->slash->trad_word("USERS_GR_REDACTION"))));
					}
					else if ($this->controller->users->get_grade_id_current_user() == 1) {
						sl_form::select($mn,6,array("value" => $values["grade"], 
										"values" => array(1,2), 
										"texts" => array($this->slash->trad_word("USERS_GR_MANAGEMENT"),
													$this->slash->trad_word("USERS_GR_REDACTION"))));
					}
					else if ($this->controller->users->get_grade_id_current_user() == 2) {
						sl_form::select($mn,6,array("value" => $values["grade"], 
										"values" => array(2), 
										"texts" => array($this->slash->trad_word("USERS_GR_REDACTION"))));
					}
					sl_form::br(2);
					
					if (!isset($values["language"])) {$values["language"] = "fr";}
					sl_form::title($this->slash->trad_word("USERS_LANGUAGE")." : ");
					sl_form::radio($mn,8,array("values" => array("fr","en"),"value" => $values["language"],
									"texts" => array("<img src='templates/system/images/flags/fr.png' height='25' width='25' />&nbsp;&nbsp;",
												"<img src='templates/system/images/flags/en.png' height='25' width='25' />")));
					sl_form::br(2);
					
					if (!isset($values["enabled"])) {$values["enabled"] = 0;}
					sl_form::title($this->slash->trad_word("ACTIVE")." : ");
					sl_form::checkbox($mn,7,array("value" => $values["enabled"]));
				
			}
			else
			{
				sl_form::error($this->slash->trad_word("USERS_ERROR_ONLY_YOUR_SETTING"));
			}
		}
		else
		{
			sl_form::error($this->slash->trad_word("USERS_ERROR_NO_PERMIT"));
		}
		
		echo "</div>";
		
		sl_form::end();
	
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
						".$this->slash->trad_word("USERS_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("DELETE")."</span></td>
					
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
								<td align='left' class='sl_mod_field_title' width='50%'>".$this->slash->trad_word("USERS_DELETE_CONFIRM")." 
								</td>
							</tr>";
							
					if (count ($id_array) != 0) {
						$count=0;		
						foreach ($id_array as $value) {
								
								$item = $this->controller->users->load_item($value);
								
								echo "<tr><td align='left' class='sl_mod_delete_text' width='50%'>
										<input type='checkbox' id='".$this->controller->module_name."_checked[".$count."]' name='".$this->controller->module_name."_checked[".$count."]' value='".$item["id"]."' checked style='display:none;'  />
										".$item["login"]." [ ".$item["mail"]." ]
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