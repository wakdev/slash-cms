<?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_ARTICLES
* @internal     Admin articles module
* @version		view.php - Version 11.3.25
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

* @addtogroup sla_articles
* @{

*/


class sla_articles_view extends slaView implements iView{

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
	
		sl_interface::script("../core/plugins/jquery_plugins/ui/js/ui.custom.js");
		sl_interface::script("../core/plugins/jquery/jquery.localisation.js");
		sl_interface::script("../core/plugins/jquery_plugins/ajaxupload/js/ajaxupload.js");
		sl_interface::script("../core/plugins/ckeditor/ckeditor.js");
		sl_interface::script("../core/plugins/bootstrap_plugins/multiselect-master/js/bootstrap-multiselect.js");
		sl_interface::script("../core/plugins/bootstrap_plugins/datepicker/js/bootstrap-datepicker.js"); //date picker fr

		sl_interface::script("../core/plugins/tabs/js/slash-tabs.js");
	
		sl_interface::stylesheet("../core/plugins/bootstrap_plugins/multiselect-master/css/bootstrap-multiselect.css");
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
						".$this->slash->trad_word("ARTICLES_TITLE");
						
		sl_interface::create_message_zone($message); //Message
						
		echo "</td><td class='sl_mod_control'>";
					
		//control buttons
		sl_interface::create_control_buttons($this->controller->module_name,array('add','edit','publish','unpublish','delete'));		
					
		echo "</td></tr></table>";
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='sl_mod_list'>";
				
		//create cat selection
		sl_interface::create_categorie_selection($this->controller->module_name,$this->slash->trad_word("CATEGORIE"),$this->controller->categories->load_categories(),"title",1);
				
		$this->controller->articles->load_items(); // Load articles list
								
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
	
	public function f_footer() {
		echo "
		<script type='text/javascript'> 
			$(document).ready(function(){ 
				$('select.multiselect').multiselect({
				  buttonText: function(options) {
					if (options.length == 0) {
					  return \"".$this->slash->trad_word("ARTICLES_BT_CATEGORIES_SELECTED_NONE")." <b class='caret'></b>\";
					}
					else if (options.length > 3) {
					  return options.length + \" ".$this->slash->trad_word("ARTICLES_BT_CATEGORIES_SELECTED")." <b class='caret'></b>\";
					}
					else {
					  var selected = \"\";
					  options.each(function() {
						selected += $(this).text() + \", \";
					  });
					  return selected.substr(0, selected.length -2) + \" <b class='caret'></b>\";
					}
				  }
				});
		}); 			
		</script>";
	}
	
	/**
	 * Show article form
	 */
	public function show_form ($id=0, $values=null, $errors=null) {
		
		sl_interface::create_message_zone($this->controller->message); //Message
	
		$mn = $this->controller->module_name;
		$code = "";
		$code_perm = "<script type='text/javascript'>
							$(document).ready(function(){
								document.".$this->controller->module_name."_add_form.".$mn."_obj9.checked = true;
								$('#".$mn."_divP').hide();
							});
						</script>";
		
		sl_form::start($mn,$id);
		
		if ($id != 0) { 
			$utitle = $this->slash->trad_word("EDIT");
			if (strtotime($values["unpublish_date"]) == 0) {
				$values["permanent"] = 1;
				$code = $code_perm;
			}else{ $values["permanent"] = 0; }
		} else { 
			$utitle = $this->slash->trad_word("ADD");
			$values["permanent"] = 1;	
			$code = $code_perm;
		}
		
		echo "<div class='sl_adm_form_top'>";
			sl_interface::create_title($mn,$this->slash->trad_word("ARTICLES_TITLE"),$utitle);
			sl_interface::create_buttons($mn,array("save","back"));
		echo "</div>";
		
		//tabs
		$myTabs = new sl_tabs();
		
		$err_class = "sl_adm_tabs-on-error";
		$err_main_general = null;
		$err_main_config = null;
		$err_main_ref = null;
		if (!empty($errors[1]["message"])) { $err_main_general = $err_class;} 
		if (!empty($errors[8]["message"]) || !empty($errors[10]["message"])) {$err_main_config = $err_class;}
		
		$myTabs->addTab("main_general",$this->slash->trad_word("ARTICLES_TAB_GENERAL"),"<i class='icon-th-large'></i>",$err_main_general);
		$myTabs->addTab("main_config",$this->slash->trad_word("ARTICLES_TAB_CONFIG"),"<i class='icon-wrench'></i>",$err_main_config);
		$myTabs->addTab("main_ref",$this->slash->trad_word("ARTICLES_TAB_REF"),"<i class='icon-search'></i>",$err_main_ref);
		
		$myTabs->setCurrent("main_general");
		
		//have an error
		if ($err_main_general !== null){ $myTabs->setCurrent("main_general"); }
		if ($err_main_config !== null){ $myTabs->setCurrent("main_config"); }
		if ($err_main_ref !== null){ $myTabs->setCurrent("main_ref"); }

		$myTabs->render();
		
		//General
		$myTabs->startTab("main_general");

			sl_form::title($this->slash->trad_word("TITLE")." : ");
			if (!isset($values["title"])){$values["title"] = null;}
			sl_form::input($mn,1,array("value" => $values["title"]));
			if (!empty($errors[1]["message"])) { sl_form::br(1); sl_form::error($errors[1]["message"]); }
			sl_form::br(2);
			
			/* Load categories */
			$row_categories = $this->controller->categories->load_categories();
			$row_cat_ids = array();
			$row_cat_text = array();
			for ($i=0; $i<count($row_categories); $i++ ) {
				$row_cat_ids[$i] = $row_categories[$i]["id"];
				$row_cat_text[$i] = $row_categories[$i]["title"];
			}
		
			$categories_selected = array();
			$categories_selected = $this->controller->articles->linked_categories($id);
						
			sl_form::title($this->slash->trad_word("CATEGORIES")." : ");
			sl_form::br(2);
			sl_form::select_multiple($mn,2,array("selected" => $categories_selected, 
													"values" => $row_cat_ids, 
													"texts" => $row_cat_text, 
													"class" => "multiselect" ));
			sl_form::br(2);
			
			sl_form::title($this->slash->trad_word("DESCRIPTION")." : ");
			sl_form::br(2);
			if (!isset($values["content"])){$values["content"] = null;}
			sl_form::ckeditor($mn,3,array("value"=>$values["content"]));
			sl_form::br(2);

			sl_form::title($this->slash->trad_word("ARTICLES_RESPONSIVE_IMAGES")." : ");
			if (!isset($values["responsive_images"])){$values["responsive_images"] = 0;}
			sl_form::checkbox($mn,6,array("value" => $values["responsive_images"]));
			sl_form::br(2);

			
			sl_form::title($this->slash->trad_word("FILES_ATTACHMENTS")." : ");
			sl_form::br(2);
			sl_form::attachments($mn,4,array(
										"item_id"=> $id,
										"files_dir" => "medias/attachments/sl_articles",
										"accept" => "gif|jpg|txt|pdf"));
			sl_form::br(2);
			
			sl_form::title($this->slash->trad_word("ACTIVE")." : ");
			if (!isset($values["enabled"])){$values["enabled"] = 1;}
			sl_form::checkbox($mn,5,array("value" => $values["enabled"]));
										

		$myTabs->endTab();
		
		//Configuration
		$myTabs->startTab("main_config");
			
			$js_perm = "onclick=\"javascript:$('#".$mn."_divP').toggle();\"";
			
			if (!isset($values["username"])){
				$row_user = $this->slash->get_admin_infos();
				$values["username"] = $row_user["name"];
			}
			sl_form::title($this->slash->trad_word("AUTHOR")." : ".$values["username"]);
			
			sl_form::br(2);
			if (!isset($values["created_date"])){$values["created_date"] = date("Y-m-d H:i:s");}
			sl_form::title($this->slash->trad_word("ARTICLES_CREATED_DATE")." : ".date("d/m/Y H:i:s",strtotime($values["created_date"])));
			
			sl_form::br(2);
			
			sl_form::title($this->slash->trad_word("ARTICLES_PUBLISH_DATE")." : ");
			if (!isset($values["publish_date"])){$values["publish_date"] = date("Y-m-d H:i:s");}
			sl_form::datetimeBS($mn,8,array("value" => $values["publish_date"]));
			if (!empty($errors[8]["message"])) { sl_form::br(1); sl_form::error($errors[8]["message"]); sl_form::br(1);}
			
			sl_form::br(1);
			
			sl_form::title($this->slash->trad_word("ARTICLES_PERMANENT")." : ");
			if (!isset($values["permanent"])){$values["permanent"] = 1;}
			sl_form::checkbox($mn,9,array("value" => $values["permanent"],"js"=>$js_perm));
			
			sl_form::br(2);
			
			echo "<div id='".$mn."_divP' style='padding:10px;border:1px #bbb solid; background-color:#DDD'>";
				
				sl_form::title($this->slash->trad_word("ARTICLES_UNPUBLISH_DATE")." : ");
				if (!isset($values["unpublish_date"])){$values["unpublish_date"] = date("Y-m-d H:i:s",strtotime("+1 week"));}
				sl_form::datetimeBS($mn,10,array("value" => $values["unpublish_date"]));
				if (!empty($errors[10]["message"])) { sl_form::br(1); sl_form::error($errors[10]["message"]); sl_form::br(1); }
				
				sl_form::br(1);
			
			echo "</div>";

		$myTabs->endTab();
		
		//Search optimization
		$myTabs->startTab("main_ref");
		
		
			echo "
				    alias url (ré-écriture URL)<br/>
				    méta description<br/>
				    méta mots-clés<br/>
				    autoriser l'indexation du fichier ou interdire l'indexation du fichier<br/>
				    autoriser le robot à suivre les liens ou interdire de suivre les liens
					";
		
			
		$myTabs->endTab();
		
		echo $code;
		
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
						".$this->slash->trad_word("ARTICLES_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("DELETE")."</span></td>
					
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
								<td align='left' class='sl_mod_field_title' width='50%'>".$this->slash->trad_word("ARTICLES_DELETE_CONFIRM")." 
								</td>
							</tr>";
							
					if (count ($id_array) != 0) {
						$count=0;		
						foreach ($id_array as $value) {
								
								$article = $this->controller->articles->load_item($value);
								
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