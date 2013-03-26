<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_panel
* @internal     Admin Panel module
* @version		view.php - Version 9.10.20
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

* @addtogroup sla_panel
* @{

*/


class sla_panel_view extends slaView implements iView {

	

	public function header () {
		
		sl_interface::script("../core/plugins/jquery_plugins/preload/js/preloadCssImages.js");
	
	}
	
	
	public function show_panel() {
		
		
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>
				<tr>
				<td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'>
					<tr>
						<td class='sl_mod_title' align='left' width='50%'><img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> ".$this->slash->trad_word("PANEL_TITLE")."</td>		
					</tr>
					</table>
					
					
					<table width='100%' cellspacing='0' cellpadding='0' border='0'>
					<tr>
						<td class='mod_panel'><br />".$this->slash->trad_word("PANEL_WELCOME")." : <u>".$this->controller->get_admin_username()."</u> <br /> <br />";
						
						echo "<table width='400' cellspacing='20' cellpadding='0' border='0'><tr>";
						
							/* ------- PANEL ITEMS -------  */
							
							/* ARTICLES MODULE */
							echo "<td align='center'><a href='index.php?mod=sla_articles' class='panel_sla_articles'></a>".$this->slash->trad_word("PANEL_SLA_ARTICLE")."</td>";	
							
							/* CATEGORIES MODULE */
							echo "<td align='center'><a href='index.php?mod=sla_categories' class='panel_sla_categories'></a>".$this->slash->trad_word("PANEL_SLA_CATEGORIES")."</td>";	
							
							/* MENU MODULE */
							echo "<td align='center'><a href='index.php?mod=sla_menu' class='panel_sla_menu'></a>".$this->slash->trad_word("PANEL_SLA_MENU")."</td>";
							
							/* NEWS MODULE */
							echo "<td align='center'><a href='index.php?mod=sla_news' class='panel_sla_news'></a>".$this->slash->trad_word("PANEL_SLA_NEWS")."</td>";
							
							/* PAGES MODULE */
							echo "<td align='center'><a href='index.php?mod=sla_pages' class='panel_sla_pages'></a>".$this->slash->trad_word("PANEL_SLA_PAGES")."</td>";

							/* USERS MODULE */
							echo "<td align='center'><a href='index.php?mod=sla_users' class='panel_sla_users'></a>".$this->slash->trad_word("PANEL_SLA_USERS")."</td>";								
							
						
							/* --------------------------  */
							
							echo "</tr></table>";	

						
			echo "			</td>
					</tr>
					</table>	
								
			</td>
			</tr></table>
			
					";
					

		
	}
	
	
	
	
	/**
	 * HTML footer
	 */
	public function footer() {
	
		
		echo "
		<script type='text/javascript'> 
 
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