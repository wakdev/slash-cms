<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_medias
* @internal     Admin medias module
* @version		view.php
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

* @addtogroup sla_medias
* @{

*/


class sla_medias_view extends slaView implements iView {

	

	public function header () {
		
		sl_interface::script("../core/plugins/jquery_plugins/preload/js/preloadCssImages.js");
		sl_interface::script("modules/sla_medias/views/default/js/sla_medias.js");
		sl_interface::stylesheet("modules/sla_medias/views/default/css/assets.css");
	}
	
	
	public function show() {
		
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>
					<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr>
						<td class='sl_mod_title' align='left' width='50%'>
						<img src='modules/".$this->controller->module_name."/views/default/images/".$this->controller->module_name.".png' align='absmiddle' /> 
						".$this->slash->trad_word("MEDIAS_TITLE");
						
		
						
		echo "</td><td class='sl_mod_control'>";
		
		echo "<table align='right'><tr>";
		
		echo "<td align='center' width='30%'><a href='javascript:void(0);' id='sla_medias_files' class='sla_medias_myfiles_button sla_medias_current'
			onClick=\"javascript:setFinder('files','".$_SESSION["user_language"]."');\"></a>".$this->slash->trad_word("MEDIAS_MYFILES")."</td>";
		
		echo "<td align='center' width='30%'><a href='javascript:void(0);' id='sla_medias_images' class='sla_medias_myimages_button'
			onClick=\"javascript:setFinder('images','".$_SESSION["user_language"]."');\"></a>".$this->slash->trad_word("MEDIAS_MYIMAGES")."</td>";
		
		echo "<td align='center' width='30%'><a href='javascript:void(0);' id='sla_medias_media' class='sla_medias_mymedia_button'
			onClick=\"javascript:setFinder('media','".$_SESSION["user_language"]."');\"></a>".$this->slash->trad_word("MEDIAS_MYMEDIAS")."</td>";
		echo "</tr></table>";
				
		echo "</td></tr></table>";
		
		
		echo "<iframe id='sla_medias_frame' name='sla_medias_frame' src='../core/plugins/kcfinder/browse.php?type=files&lang=".$_SESSION["user_language"]."' width='100%' height='450'></iframe>";
					
		
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