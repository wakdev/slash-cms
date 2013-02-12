<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_modules
* @internal     Admin modules module
* @version		sla_modules_view.php - Version 10.1.5
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

* @addtogroup sla_modules
* @{

*/


class sla_modules_view extends slaView implements iView{


	/**
	 * Show HTML Header
	 */
	public function header () {
		
		//<link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen">
		//echo "<link rel='stylesheet' type='text/css' href='templates/system/css/sla_menu.css' media='screen'>";
		//echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/superfish/js/hoverintent.js'></script> \n";
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/interface/js/interface.js'></script> \n";
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/pager/js/pager.js'></script> \n";
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/preload/js/preloadCssImages.js'></script> \n";


		echo "<script type='text/javascript' src='../core/functions/sl_javascript.js'></script> \n";

	}
	
	
	/**
	 * Show modules list
	 * @param $message message
	 */
	public function show_items($message="") {
		
		echo "<form name='".$this->controller->module_name."_nav_form' method=post action='index.php?mod=".$this->controller->module_name."'>
			  <input type='hidden' id='".$this->controller->module_name."_act' name='".$this->controller->module_name."_act' value=''>
			  <input type='hidden' id='".$this->controller->module_name."_valid' name='".$this->controller->module_name."_valid' value=''>";

			sl_interface::listing_hidden_fields($this->controller->module_name);	
		
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'>
					<tr><td class='sl_mod_title' align='left' width='50%'>
					<img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> 
					".$this->slash->trad_word("MODULES_TITLE");
						
		sl_interface::create_message_zone($message); //Message
						
		echo "</td><td class='sl_mod_control'>";
							
		sl_interface::create_control_buttons($this->controller->module_name,array('add','edit','delete'));				
		
		echo "</td></tr></table><table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='sl_mod_list'>";
			
		$row_type = array(
						array("id"=> "admin", "type" => "Admin"), 
						array("id"=> "site","type" => "Site"));
			
		//create cat selection
		sl_interface::create_categorie_selection($this->controller->module_name,$this->slash->trad_word("MODULES_TYPE"),$row_type,"type",1);
			
		$this->controller->modules->load_items(); // Load modules list
				
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
	 * Show module form
	 */
	public function show_form ($id=0,$values=null,$errors=null) {
		
		$title = "<img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> ";
		
		if ($id != 0) {
			$title .= $this->slash->trad_word("MODULES_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("EDIT")."</span>";
		} else {
			$title .= $this->slash->trad_word("MODULES_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("ADD")."</span>";
		}

		$obj_titles = array($this->slash->trad_word("MODULES_TYPE"),$this->slash->trad_word("NAME"),$this->slash->trad_word("ADDRESS"),"GLOBAL <br/> 0 = disabled <br /> x = initialize position",$this->slash->trad_word("ACTIVE"));
		$obj_fieds = array("type","name","url","initialize_order","enabled");
		$obj_styles = array(
			array("type" => "select","values" => array("admin","site"),"texts" => array("admin","site")),
			array("type" => "input", "mandatory" => "1" ),
			array("type" => "input", "mandatory" => "1" ),
			array("type" => "input", "mandatory" => "1"),
			array("type" => "checkbox")
			);
		
		sl_interface::create_show_form($this->controller->module_name,$title,$id,$obj_fieds,$obj_titles,$obj_styles,$values,"");
		
	}
	
	
	
	
	
	/**
	 * Show module form
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
						<td class='sl_mod_title'><img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> ".$this->slash->trad_word("MODULES_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("DELETE")."</span></td>
					
						<td class='sl_mod_control'>
							<table align='right' width='200'>
							<tr>
								<td align='center' width='50%'>
										
									<a href='javascript:void(0);' class='del_button' onClick=\"javascript:submitForm('".$this->controller->module_name."','del_apply');\"></a>
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
								<td align='left' class='sl_mod_field_title' width='50%'>".$this->slash->trad_word("MODULES_DELETE_CONFIRM")." 
								</td>
							</tr>";
							
					if (count ($id_array) != 0) {
						$count=0;		
						foreach ($id_array as $value) {
								
								$module = $this->controller->modules->load_item($value);
								
								echo "<tr><td align='left' class='sl_mod_delete_text' width='50%'>
										<input type='checkbox' id='".$this->controller->module_name."_checked[".$count."]' name='".$this->controller->module_name."_checked[".$count."]' value='".$module["id"]."' checked style='display:none;'  />
										".$module["name"]."
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