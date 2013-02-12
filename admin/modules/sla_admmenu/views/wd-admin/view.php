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
	
	private $tmpMenu;
	
	public function header () {
		echo "<link rel='stylesheet' type='text/css' href='modules/sla_admmenu/views/wd-admin/css/sla_admmenu.css' media='screen'>";
	}
	
	
	public function start_main_menu() {

		echo "<div class='menu'>";
		echo "<div class='pull-left'>";
		echo "<div class='navbar'>";
		echo "<div class='nav-collapse collapse'>";
		echo "<ul class='nav' role='navigation'> \n";
	}
	
	public function end_main_menu() {

		echo "</ul></div></div></div>";		
		echo "<div class='menu_username pull-right'>";
		echo "<img src='".$this->slash->config["admin_template_url"]."/images/assets/user.png' width='16' height='16' align='absmiddle'>";
		echo "&nbsp;".$this->slash->trad_word("ADMMENU_CONNEXION_USERNAME")." : ".$this->controller->get_admin_username()."&nbsp;&nbsp;";
		echo "<img src='".$this->slash->config["admin_template_url"]."/images/assets/logout.png' width='16' height='16' align='absmiddle'>&nbsp;&nbsp;";
		echo "<a href='index.php?mod=sla_secure&act=logout' class='disconnect_link'>".$this->slash->trad_word("ADMMENU_DECONNEXION_TEXT")."</a></div> \n";
		echo "</div>";
		echo "<div class='clear'></div>";
	}
	
	public function start_menu($mData) {
		
		
			$this->tmpMenu = $mData;
			$have_dropdown = $this->controller->admmenu->have_dropdown($mData["id"]);
			
			if (!$have_dropdown){	
		
				switch ($mData["type"]) {
					case "url_self" :
						echo "<li ><a href='".$mData["action"]."'><img src='templates/system/images/menu/".$mData["icon"]."' width='16' height='16' align='absmiddle' border='0'>&nbsp;<span class='dropLink'>".$mData["title_".$_SESSION["user_language"]]."</span></a> \n";
					break;
					case "url_blank" :
						echo "<li ><a href='".$mData["action"]."' target='_blank'><img src='templates/system/images/menu/".$mData["icon"]."' width='16' height='16' align='absmiddle' border='0'>&nbsp;<span class='dropLink'>".$mData["title_".$_SESSION["user_language"]]."</span></a> \n";
					break;
					case "none":
						echo "<li ><a href='#'><img src='templates/system/images/menu/".$mData["icon"]."' width='16' height='16' align='absmiddle' border='0'>&nbsp;<span class='dropLink'>".$mData["title_".$_SESSION["user_language"]]."</span></a> \n";
					break;
					
					default:
						echo "<li ><a href='#'><img src='templates/system/images/menu/".$mData["icon"]."' width='16' height='16' align='absmiddle' border='0'>&nbsp;<span class='dropLink'>".$mData["title_".$_SESSION["user_language"]]."</span></a> \n";
				}
			
			}
		
	
	}
	
	public function end_menu () {
		echo "</li> \n";
	}
	
	public function start_under_menu () {
		
		
		echo "<li class='dropdown'>";
                  
		echo "<a class='dropdown-toggle' data-toggle='dropdown' role='button' href='#' id='drop".$this->tmpMenu["id"]."'>
		<img src='templates/system/images/menu/".$this->tmpMenu["icon"]."' width='16' height='16' align='absmiddle' border='0'>&nbsp;<span class='dropLink'>".$this->tmpMenu["title_".$_SESSION["user_language"]]."</span></a>
		<ul aria-labelledby='drop".$this->tmpMenu["id"]."' class='dropdown-menu' role='menu'>";
		
	}
	
	public function end_under_menu() {
		echo "</ul></li> \n";
	}
	
	
	
	public function execute_menu() {
	
	}
	
}


/** 
* @} 
*/


?>