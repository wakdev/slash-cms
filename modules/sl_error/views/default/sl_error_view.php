<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_error
* @internal     Error module
* @version		sl_error_view.php - Version 9.6.2
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

* @addtogroup sl_error
* @{

*/


class sl_error_view implements iView{


	protected function header () {
		
		//<link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen">
		//echo "<link rel='stylesheet' type='text/css' href='templates/system/css/sla_menu.css' media='screen'>";
		//echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/superfish/js/hoverintent.js'></script> \n";
		//echo "<script type='text/javascript' src='../core/plugins/fckeditor/pick.js'></script> \n";
		//echo "<script type='text/javascript' src='../core/plugins/jquery_plugins/preload/js/preloadCssImages.js'></script> \n";
	
	}
	
	
	protected function show_error($id) {
		
		echo "<table width='200' cellspacing='0' cellpadding='5' border='0' align='center' style='border:2px #DD0000 dashed;'>
				<tr><td align='center' style='
						font-family: Arial;
						font-size:16px;
						font-weight:bold;
						color:#222222;
						text-decoration:none;
						'>";
				
		switch ($id) {
			
			case 403:
				echo $this->slash->trad_word("ERROR_403");
			break;
			case 404:
				echo $this->slash->trad_word("ERROR_404");
			break;
			default :
				echo $this->slash->trad_word("ERROR_X");
		}		
					
		echo "			
			</td>
			</tr></table>";
				
	}
	
	
	
	
	/**
	 * HTML footer
	 */
	protected function footer() {
	
		/*
		echo "
		<script type='text/javascript'> 
 
			$(document).ready(function(){ 
			
			
				$.preloadCssImages();
				
		}); 

 		 			
		</script>";
	*/
	}
	
	
	
}

/** 
* @} 
*/



?>