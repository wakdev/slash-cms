<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_secure
* @internal     Admin Login module
* @version		view.php - Version 9.6.2
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

* @addtogroup sla_secure
* @{

*/


class sla_secure_view extends slaView implements iView {


	/**
	 * HTML Header 
	 */
	public function header () {
		
		sl_interface::stylesheet("modules/sla_secure/views/default/css/sla_secure.css","screen");
		sl_interface::script("../core/plugins/jquery_plugins/preload/js/preloadCssImages.js");
		echo "<meta http-equiv='Content-Type'  content='text/html; charset=utf-8' />\n";
	}
	
	/**
	 * Login form
	 */
	public function login_form () {
				
		echo "<br />
		<form name='sla_secure_form' method=post action='index.php?mod=sla_secure&act=login'>
			<div class='sla_secure_container'>
				<div class='sla_secure_box'>
					<div class='sla_secure_zn_title connexion_title pull-left'>
						<img src='".$this->slash->config["admin_template_url"]."/images/assets/lock.png' width='64' height='64' align='absmiddle'>&nbsp;&nbsp;".$this->slash->trad_word('SECURE_CONNEXION_TEXT')."
					</div>
					
					<div class='sla_secure_zn_input login_text'>
						
						<input id='sla_secure_login' name='sla_secure_login' type='text' class='login_text' placeholder='".$this->slash->trad_word('SECURE_CONNEXION_LOGIN')."' value='".$this->controller->slash->sl_param("sla_secure_login","POST")."'>
					</div>
					<div class='sla_secure_zn_input login_text'>
						<input id='sla_secure_password' name='sla_secure_password' type='password' class='login_text'  placeholder='".$this->slash->trad_word('SECURE_CONNEXION_PASSWORD')."'>
					</div>
					
					<div class='sla_secure_zn_submit'>
						<input id='sla_secure_submit' name='sla_secure_submit' type='image' src='".$this->slash->config["admin_template_url"]."/images/assets/connexion.png' 
						onMouseOver=\"javascript:this.src='".$this->slash->config["admin_template_url"]."/images/assets/connexion_over.png'\" 
						onMouseOut=\"javascript:this.src='".$this->slash->config["admin_template_url"]."/images/assets/connexion.png'\">
					</div>
					
				</div>
			</div>
		</form>";
		
	}
	
	/**
	* Show Error
	*/
	public function show_error($message) {
		echo "<br />
		<div class='sla_secure_error error_login_text'>
			<img src='".$this->slash->config["admin_template_url"]."/images/assets/error.png' width='16' height='16' align='absmiddle'>&nbsp;".$this->slash->trad_word($message)."
		</div>";
	}
	
	
	/**
	 * HTML footer
	 */
	public function footer() {

		echo "<script type='text/javascript'> 
 
			$(document).ready(function(){ 
			
			
				$.preloadCssImages();
				
		}); 			
		</script>";
	
	}
}



/** 
* @} 
*/

?>