<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_menu
* @internal     Menu module
* @version		sl_menu_view.php - Version 9.6.2
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

* @addtogroup sl_menu
* @{

*/


class sl_menu_view extends slView implements iView{


	public function header () {
		
		//<link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen">
		//echo "<link rel='stylesheet' type='text/css' href='core/javascript/jquery_plugins/superfish/css/superfish.css' media='screen'>";
		
		//echo "<link rel='stylesheet' type='text/css' href='admin/templates/system/css/sla_menu.css' media='screen'>";
		// A CHANGER -> POINTER LE STYLE DIRECTEMENT DANS LE TEMPLATE
		
		
		/*
		echo "<script type='text/javascript' src='core/plugins/jquery_plugins/superfish/js/hoverintent.js'></script> \n";
		echo "<script type='text/javascript' src='core/plugins/jquery_plugins/superfish/js/superfish.js'></script> \n";
		*/
	}
	
	
	public function start_main_menu() {
		echo "<ul id='sl_menu'> \n";
	}
	
	public function end_main_menu() {
		echo "</ul> \n";
	}
	
	public function start_menu($data) {
		
		$title = $data["title"];
		$type = $data["sec_type"];
		$action = $data["action"];
		$home = $data["home"];
		
		$class = ""; 
		
		$is_current = $this->controller->menus->is_current($action);
		
		if ($is_current || ($this->controller->is_home==true && $home == 1) ) {
			$class =  "class='current'";
			
		}
		
		if ($home == 1) {$action = "/";}
		
		switch ($type) {
			case "url_self" :
				echo "<li ".$class."><a href='".$action."'>".$title."</a> \n";
			break;
			case "url_blank" :
				echo "<li><a href='".$action."' target='_blank'>".$title."</a> \n";
			break;
			case "none":
				echo "<li ".$class."><a href='#'>".$title."</a> \n";
			break;
			
			default:
				echo "<li ".$class."><a href='#'>".$title."</a> \n";
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
		
		/*echo "
		<script type='text/javascript'> 
 
			$(document).ready(function(){ 
			$('#sl_menu').superfish({ 
            delay:       1000,                            // one second delay on mouseout 
            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
            speed:       300,                          // faster animation speed 
            autoArrows:  true,                           // disable generation of arrow mark-up 
            dropShadows: false                            // disable drop shadows 
			}); 
		}); 
 
		</script>";*/
	
	}
	
}


/** 
* @} 
*/


?>