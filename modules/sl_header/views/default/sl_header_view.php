<?php
/**
* @package		SLASH-CMS
* @subpackage	SL_HEADER
* @internal     Header module
* @version		sl_header_view.php - Version 13.5.2
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

* @addtogroup sl_header
* @{

*/


class sl_header_view implements iView{


	
	/**
	* Show title
	*/
	protected function title ($title) {
		
		//echo "<title>".$title."</title>";
	}
	
	/**
	* Show meta data
	*/
	protected function metas ($description,$keywords) {
	
		//<meta http-equiv='Content-Type'  content='text/html; charset=utf-8' />
		echo "	<meta http-equiv='Content-Type'  content='text/html; charset=utf-8' />";
			/*
				<meta name='description' content='".$description."' />
				<meta name='keywords' content='".$keywords."' />
				<meta name='expires' content='never' />
				<meta name='language' content='FR' />";*/

	
	}
	
	
	/**
	* Show script
	*/
	protected function scripts () {
		
		echo "<noscript>Slash use JavaScript. If you see this message, you should enable JavaScript on the preferences web browser ! </noscript> \n";	
		sl_interface::script("./core/plugins/picturefill/matchmedia.js");
		sl_interface::script("./core/plugins/picturefill/picturefill.js");
		sl_interface::script("./core/plugins/jquery/jquery.js");

		//echo "<script type='text/javascript' src='./core/plugins/jquery/jquery.bgiframe.js'></script> \n";
		//echo "<script type='text/javascript' src='./core/plugins/jquery/jquery.dimensions.js'></script> \n";
		//echo "<script type='text/javascript' src='./core/plugins/jquery/jquery.delegate.js'></script> \n";
		
	}


}


/** 
* @} 
*/


?>