<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_country
* @internal     Admin country module
* @version		sla_country_view.php - Version 11.1.14
* @author		Julien Veuillet [http://www.wakdev.com]
* @copyright	Copyright(C) 2011 - Today. All rights reserved.
* @license		GNU/GPL

* @addtogroup sla_country
* @{

*/


class sla_country_view implements iView{


	/**
	 * Show global HTML Header
	 */
	protected function header () {
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/interface/js/interface.js'></script> \n";
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/preload/js/preloadCssImages.js'></script> \n";
	} 
	  
	protected function l_header () {
		echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/pager/js/pager.js'></script> \n";
	}
	
	protected function f_header () {
		echo "<script type='text/javascript' src='../core/plugins/tiny_mce/jquery.tinymce.js'></script> \n";
		echo "<script type='text/javascript' src='../core/plugins/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php'></script> \n";
	}
	
	
	/**
	 * Show country list
	 * @param $message message
	 */
	protected function show_items($message="") {
		
		echo "<form name='".$this->module_name."_nav_form' method=post action='index.php?mod=".$this->module_name."'>
			  <input type='hidden' id='".$this->module_name."_act' name='".$this->module_name."_act' value=''>
			  <input type='hidden' id='".$this->module_name."_valid' name='".$this->module_name."_valid' value=''>";

		sl_interface::listing_hidden_fields($this->module_name);	
		
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>
						<td class='sl_mod_title' align='left' width='50%'>
						<img src='modules/".$this->module_name."/views/default/images/".$this->module_name.".png' align='absmiddle' /> 
						".$this->slash->trad_word("COUNTRY_TITLE");
						
		sl_interface::create_message_zone($message); //Message
						
		echo "</td><td class='sl_mod_control'>";

		sl_interface::create_control_buttons($this->module_name,array('add','edit','delete'));				
				
		echo "</td></tr></table>";
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td class='sl_mod_list'>";
								
		$this->load_items(); // Load country list
							
		echo "</td></tr></table></td></tr></table></form>";
		
	}


	
	/**
	 * HTML footer
	 */
	protected function footer() {
	
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
	protected function show_form ($id=0, $values=null, $error=null) {
	
		$mn = $this->module_name;
		
		sl_form::start($mn,$id);
		
		if ($id != 0) { $utitle = $this->slash->trad_word("EDIT");
		} else { $utitle = $this->slash->trad_word("ADD");}
		
		
		echo "<div class='sl_adm_form_top'>";
			sl_interface::create_title($mn,$this->slash->trad_word("COUNTRY_TITLE"),$utitle);
			sl_interface::create_buttons($mn,array("save","back"));
		echo "</div>";
		
		echo "<div class='sl_adm_form_main'>";
			
			sl_form::title($this->slash->trad_word("NAME")." * : ");
			sl_form::input($mn,0,array("value" => $values["name"]));
			if ($error[0]["message"]) { sl_form::error($error[0]["message"]); }
			sl_form::br(2);
			
			sl_form::title($this->slash->trad_word("COUNTRY_SHORTNAME")." * : ");
			sl_form::input($mn,1,array("value" => $values["shortname"]));
			if ($error[1]["message"]) { sl_form::error($error[1]["message"]); }
			sl_form::br(2);

			sl_form::title($this->slash->trad_word("ENABLED")." : ");
			sl_form::checkbox($mn,2,array("value" => $values["enabled"]));
			
		echo "</div>";
		
		sl_form::end();
	
	}
	/*
	protected function show_form ($id=0,$values=null,$errors=null) {
		
		$title = "<img src='modules/".$this->module_name."/views/default/images/".$this->module_name.".png' align='absmiddle' /> ";
		
		if ($id != 0 ) {
			$title .= $this->slash->trad_word("COUNTRY_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("EDIT")."</span>";
		} else {
			$title .= $this->slash->trad_word("COUNTRY_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("ADD")."</span>";
		}
		
		$obj_titles = array($this->slash->trad_word("NAME"),$this->slash->trad_word("COUNTRY_SHORTNAME"),$this->slash->trad_word("ENABLED"));
		$obj_fieds = array("name","shortname","enabled");
		$obj_styles = array(
						array("type" => "input", "mandatory" => "1" ),
						array("type" => "input", "mandatory" => "1"),
						array("type" => "checkbox")
					);
		
		sl_interface::create_show_form($this->module_name,$title,$id,$obj_fieds,$obj_titles,$obj_styles,$values,"",$errors);
		
	}
	*/
	
	
	/**
	 * Show categorie form
	 */
	protected function show_delete ($id_array) {
		
		echo "	<form name='".$this->module_name."_del_form' method=post action='index.php?mod=".$this->module_name."'>
				<input type='hidden' id='".$this->module_name."_act' name='".$this->module_name."_act' value='delete'>
				<input type='hidden' id='".$this->module_name."_valid' name='".$this->module_name."_valid' value='1'>
				
				<table width='100%' cellspacing='0' cellpadding='0' border='0'>
				<tr>
				<td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'>
					<tr>
						<td class='sl_mod_title'><img src='modules/".$this->module_name."/views/default/images/".$this->module_name.".png' align='absmiddle' /> ".$this->slash->trad_word("COUNTRY_TITLE")." >>> <span class='sl_mod_undertitle'>".$this->slash->trad_word("DELETE")."</span></td>
					
						<td class='sl_mod_control'>
							<table align='right' width='200'>
							<tr>
								<td align='center' width='50%'>
										
									<a href='javascript:void(0);' class='del_button' onClick=\"javascript:submitForm('".$this->module_name."','del_apply');\"></a>
									".$this->slash->trad_word("DELETE")."</td>
									
								<td align='center' width='50%'>			
									<a href='index.php?mod=".$this->module_name."' class='undo_button'></a>
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
								<td align='left' class='sl_mod_field_title' width='50%'>".$this->slash->trad_word("COUNTRY_DELETE_CONFIRM")." 
								</td>
							</tr>";
							
					if (count ($id_array) != 0) {
						$count=0;		
						foreach ($id_array as $value) {
								
								$categorie = $this->load_item($value);
								
								echo "<tr><td align='left' class='sl_mod_delete_text' width='50%'>
										<input type='checkbox' id='".$this->module_name."_checked[".$count."]' name='".$this->module_name."_checked[".$count."]' value='".$categorie["id"]."' checked style='display:none;'  />
										".$categorie["title"]."
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