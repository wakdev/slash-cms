<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_admmenu
* @internal     Admin menu module
* @version		sla_admmenu_view.php - Version 9.12.16
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

* @addtogroup sla_admmenu
* @{

*/


class sla_admmenu_view extends slaView implements iView{
	
	public function header () {
		
		echo "<link rel='stylesheet' type='text/css' href='modules/sla_admmenu/views/default/css/sla_admmenu.css' media='screen'>";
		//echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/superfish/js/hoverintent.js'></script> \n";
		//echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/superfish/js/superfish.js'></script> \n";
	
	}
	
	
	public function start_main_menu() {
		echo "<table cellspacing='0' cellpadding='0' class='menu'><tr><td><ul id='sla_admmenu' class='sf-menu'> \n";
	}
	public function end_main_menu() {
		echo "</ul></td>
		<td align='right' class='menu_username'><img src='".$this->slash->config["admin_template_url"]."/images/assets/user.png' width='16' height='16' align='absmiddle'>&nbsp;".$this->slash->trad_word("ADMMENU_CONNEXION_USERNAME")." : ".$this->controller->get_admin_username()."&nbsp;&nbsp;</td><td width='30' align='center'><img src='".$this->slash->config["admin_template_url"]."/images/assets/logout.png' width='16' height='16' align='absmiddle'></td>
		<td width='90' align='left'><a href='index.php?mod=sla_secure&act=logout' class='disconnect_link'>".$this->slash->trad_word("ADMMENU_DECONNEXION_TEXT")."</a></td>
		</tr></table> \n";
	}
	
	public function start_menu($mData) {
		
		$type = $mData["type"];
		$action = $mData["action"];
		$icon = $mData["icon"];
		$title = $mData["title_".$_SESSION["user_language"]];
		
		switch ($type) {
			case "url_self" :
				echo "<li class='current'><a href='".$action."'><img src='templates/system/images/menu/".$icon."' width='16' height='16' align='absmiddle' border='0'>&nbsp;".$title."</a> \n";
			break;
			case "url_blank" :
				echo "<li class='current'><a href='".$action."' target='_blank'><img src='templates/system/images/menu/".$icon."' width='16' height='16' align='absmiddle' border='0'>&nbsp;".$title."</a> \n";
			break;
			case "none":
				echo "<li class='current'><a href='#'><img src='templates/system/images/menu/".$icon."' width='16' height='16' align='absmiddle' border='0'>&nbsp;".$title."</a> \n";
			break;
			
			default:
				echo "<li class='current'><a href='#'><img src='templates/system/images/menu/".$icon."' width='16' height='16' align='absmiddle' border='0'>&nbsp;".$title."</a> \n";
		}
		
	}
	
	public function end_menu () {
		echo "</li> \n";
	}
	
	public function start_under_menu () {
		echo "<ul> \n";
	}
	
	public function end_under_menu() {
		echo "</ul></li> \n";
	}
	
	
	
	public function execute_menu() {
	
		
		
		echo "
		<script type='text/javascript'> 
 
			$(document).ready(function(){ 
			$('#sla_admmenu').superfish({ 
            delay:       1000,                            // one second delay on mouseout 
            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
            speed:       300,                          // faster animation speed 
            autoArrows:  true,                           // disable generation of arrow mark-up 
            dropShadows: false                            // disable drop shadows 
			}); 
		}); 
 
		</script>";
	
	}
	
}


/** 
* @} 
*/


?>