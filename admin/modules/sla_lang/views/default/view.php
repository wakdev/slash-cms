<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_lang
* @internal     Admin LANG module
* @version		sla_lang_view.php - Version 11.1.14
* @author		Julien Veuillet [http://www.wakdev.com]
* @copyright	Copyright(C) 2011 - Today. All rights reserved.
* @license		GNU/GPL

* @addtogroup sla_lang
* @{

*/


class sla_lang_view extends slaView implements iView{


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
		sl_interface::script("../core/plugins/tiny_mce/jquery.tinymce.js");
		sl_interface::script("../core/plugins/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php");
	}
	
	
	/**
	 * Show LANG list
	 * @param $message message
	 */
	public function show_items($message="") {
		
		echo "<form name='".$this->controller->module_name."_nav_form' method=post action='index.php?mod=".$this->controller->module_name."'>
			  <input type='hidden' id='".$this->controller->module_name."_act' name='".$this->controller->module_name."_act' value=''>
			  <input type='hidden' id='".$this->controller->module_name."_valid' name='".$this->controller->module_name."_valid' value=''>";

		sl_interface::listing_hidden_fields($this->controller->module_name);	
		
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>
						<td class='sl_mod_title' align='left' width='50%'>
						<img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> 
						".$this->slash->trad_word("LANG_TITLE");
						
		sl_interface::create_message_zone($message); //Message
						
		echo "</td><td class='sl_mod_control'>";

		sl_interface::create_control_buttons($this->controller->module_name,array('add','delete'));				
				
		echo "</td></tr></table>";
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='sl_mod_list'>";
								
		$this->controller->lang->load_items(); // Load LANG list
							
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
	 * Show categorie form
	 */
	public function show_form ($id=0, $values=null, $error=null) {
	
		$mn = $this->controller->module_name;
		
		sl_form::start($mn,$id);
		
		$utitle = $this->slash->trad_word("ADD");
		
		echo "<div class='sl_adm_form_top'>";
			sl_interface::create_title($mn,$this->slash->trad_word("LANG_TITLE"),$utitle);
			sl_interface::create_buttons($mn,array("save","back"));
		echo "</div>";
		
		echo "<div class='sl_adm_form_main'>";
		
			sl_form::title("S&eacute;lectionner la langue que vous souhaitez ajouter");
			
			sl_form::br(2);
			
			sl_form::title($this->slash->trad_word("NAME")." * : ");
			
			$value_id = array();
			$value_name = array();
			
			for($i=0;$i<count($values);$i++){
				$value_id[$i]= $values[$i]["id"];
				$value_name[$i]= $values[$i]["name"];
			}
			
			sl_form::select($mn,0,array("values" =>  $value_id,
									"texts" => $value_name
									)
						);
			
			sl_form::br(2);
			
			sl_form::hidden($mn,2,array("value" => "1"));
			
		echo "</div>";
		
		sl_form::end();
	
	}
	
	/**
	 * Show categorie form
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
						<td class='sl_mod_title'><img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> ".$this->slash->trad_word("LANG_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("DELETE")."</span></td>
					
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
								<td align='left' class='sl_mod_field_title' width='50%'>".$this->slash->trad_word("LANG_DELETE_CONFIRM")." 
								</td>
							</tr>";
							
					if (count ($id_array) != 0) {
						$count=0;		
						foreach ($id_array as $value) {
								
								$lang = $this->controller->lang->load_item($value);
								
								echo "<tr><td align='left' class='sl_mod_delete_text' width='50%'>
										<input type='checkbox' id='".$this->controller->module_name."_checked[".$count."]' name='".$this->controller->module_name."_checked[".$count."]' value='".$value."' checked style='display:none;'  />
										".$lang["name"]."
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