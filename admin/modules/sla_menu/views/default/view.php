<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_menu
* @internal     Admin menu module
* @version		view.php - Version 11.6.1
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

* @addtogroup sla_menu
* @{

*/


class sla_menu_view extends slaView implements iView{
	

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
		
		echo "
			<script type='text/javascript'>
						
				function show_level_select(id) {
					$('#".$this->controller->module_name."_level>div').hide();	
					$('#".$this->controller->module_name."_level'+id).show();	
				}
				
				
				function set_action_page(id) {
					$('#".$this->controller->module_name."_obj4').val('index.php?mod=sl_pages&id='+id);	
				}
				
				function set_action_article(id) {
					$('#".$this->controller->module_name."_obj4').val('index.php?mod=sl_articles&id='+id);	
				}
		
			</script>
		";
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
						 ".$this->slash->trad_word("SLA_MENU_TITLE");
						
		sl_interface::create_message_zone($message); //Message
						
		echo "</td><td class='sl_mod_control'>";
					
		//control buttons
		sl_interface::create_control_buttons($this->controller->module_name,array('add','edit','publish','unpublish','delete'));		
					
		echo "</td></tr></table>";
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='sl_mod_list'>";
				
		//create cat selection
		sl_interface::create_categorie_selection($this->controller->module_name,"MENU",$this->controller->menus->load_menus(),"title",1,false);
				
		$this->controller->menus->load_items(); // Load articles list
								
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
	public function show_form ($id=0, $values=null, $errors=null) {
	
		$mn = $this->controller->module_name;
		
		sl_form::start($mn,$id);
		
		if ($id != 0) { $utitle = $this->slash->trad_word("EDIT");
		} else { $utitle = $this->slash->trad_word("ADD");}
		
		
		echo "<div class='sl_adm_form_top'>";
			sl_interface::create_title($mn,$this->slash->trad_word("SLA_MENU_TITLE"),$utitle);
			sl_interface::create_buttons($mn,array("save","back"));
		echo "</div>";
		
		echo "<div class='sl_adm_form_main'>";
			
			sl_form::title($this->slash->trad_word("TITLE")." : ");
			sl_form::input($mn,1,array("value" => $values["title"]));
			if ($errors[1]["message"]) { sl_form::error($errors[1]["message"]); }
			sl_form::br(2);
			
			/* Load menus */
			$row_menus = $this->controller->menus->load_menus();
			$row_cat_ids = array();
			$row_cat_text = array();
			for ($i=0; $i<count($row_menus); $i++ ) {
				$row_cat_ids[$i] = $row_menus[$i]["id"];
				$row_cat_text[$i] = $row_menus[$i]["title"];
			}
						
			sl_form::title($this->slash->trad_word("SLA_MENU_SELECT_MENU")." : ");
			sl_form::select($mn,2,array("value" => $values["menu_id"], 
										"values" => $row_cat_ids, 
										"texts" => $row_cat_text, 
										"js" => "onchange=\"javascript:show_level_select(this.value);\""));
			sl_form::br(2);

			
			/* Load level */
			echo "<div id='".$this->controller->module_name."_level'>";
			
			for ($i=0; $i<count($row_menus); $i++ ) {
				$row_links = $this->controller->menus->load_links($row_menus[$i]["id"]);
				$row_cat_ids = array();
				$row_cat_text = array();
				
				$row_cat_ids[0] = 0;
				$row_cat_text[0] = " - ".$this->slash->trad_word("SLA_MENU_TOPLEVEL")." - ";
				
				for ($j=0; $j<count($row_links); $j++ ) {
					$row_cat_ids[$j+1] = $row_links[$j]["id"];
					$row_cat_text[$j+1] = $row_links[$j]["title"];
				}
				
				
				echo "<div id='".$this->controller->module_name."_level".$row_menus[$i]["id"]."'";
				if ($i>0) { echo " style='display:none;'>";}
				sl_form::title($this->slash->trad_word("SLA_MENU_LEVEL")." : ");
				sl_form::select($mn,3,array("value" => $values["parent"], "values" => $row_cat_ids, "texts" => $row_cat_text ));
				sl_form::br(2);
				echo "</div>";
			}
			
			echo "</div>"; 
			
			/* Actions */
			echo "<div class='sl_form_title adm_box well'>";

			echo "<b>".$this->slash->trad_word("SLA_MENU_MODULES")."</b>";
			
			
			sl_form::br(1);
			
			/* Pages */
			sl_form::title($this->slash->trad_word("SLA_MENU_ADD_PAGE")." : ");
			$row_pages = $this->controller->pages->load_pages();
			$row_pg_ids = array();
			$row_pg_text = array();
				
			$row_pg_ids[0] = 0;
			$row_pg_text[0] = " - ".$this->slash->trad_word("SLA_MENU_ADD_PAGE_DEFAULT")." - ";
			for ($j=0; $j<count($row_pages); $j++ ) {
				$row_pg_ids[$j+1] = $row_pages[$j]["id"];
				$row_pg_text[$j+1] = $row_pages[$j]["title"];
			}
			
			
			sl_form::select($mn,101,array("value" => 0, 
										"values" => $row_pg_ids, 
										"texts" => $row_pg_text, 
										"js" => "onchange=\"javascript:set_action_page(this.value);\""));
			

			/* end Pages */
			
			sl_form::br(1);
			
			/* Articles */
			
			sl_form::title($this->slash->trad_word("SLA_MENU_ADD_ARTICLE")." : ");
			$row_articles = $this->controller->articles->load_articles();
			$row_art_ids = array();
			$row_art_text = array();
				
			$row_art_ids[0] = 0;
			$row_art_text[0] = " - ".$this->slash->trad_word("SLA_MENU_ADD_ARTICLE_DEFAULT")." - ";
			for ($j=0; $j<count($row_articles); $j++ ) {
				$row_art_ids[$j+1] = $row_articles[$j]["id"];
				$row_art_text[$j+1] = $row_articles[$j]["title"];
			}
			
			
			sl_form::select($mn,102,array("value" => 0, 
										"values" => $row_art_ids, 
										"texts" => $row_art_text, 
										"js" => "onchange=\"javascript:set_action_article(this.value);\""));
			sl_form::br(1);
			
			/* end Articles */
			
			
			echo "</div>";
			
			sl_form::br(1);
			
			sl_form::title($this->slash->trad_word("SLA_MENU_ACTION")." : ");
			sl_form::input($mn,4,array("value" => $values["action"]));
			if ($errors[4]["message"]) { sl_form::error($errors[4]["message"]); }
			sl_form::br(2);
			
			sl_form::title($this->slash->trad_word("ACTIVE")." : ");
			sl_form::checkbox($mn,5,array("value" => $values["enabled"]));
										
							
			
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
								
								$item = $this->controller->menus->load_item($value);
								
								echo "<tr><td align='left' class='sl_mod_delete_text' width='50%'>
										<input type='checkbox' id='".$this->controller->module_name."_checked[".$count."]' name='".$this->controller->module_name."_checked[".$count."]' value='".$item["id"]."' checked style='display:none;'  />
										".$item["title"]."
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