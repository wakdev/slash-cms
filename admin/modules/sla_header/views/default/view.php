<?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_HEADER
* @internal     Admin header module
* @version		sla_header_view.php - Version 11.5.31
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

* @addtogroup sla_header
* @{

*/


class sla_header_view extends slaView implements iView{

	/**
	* Show title
	*/
	public function title () {
		echo "<title>Admin</title> \n";
	}
	
	/**
	* Show meta data
	*/
	public function metas () {
	
		echo "	<meta http-equiv='Content-Type'  content='text/html; charset=utf-8' />";
	}
	
	
	/**
	* Show script
	*/
	public function scripts () {
		
		echo "<noscript>Slash use JavaScript. If you see this message, you should enable JavaScript on the preferences web browser ! </noscript> \n";	
		echo "<script type='text/javascript' src='../core/plugins/jquery/jquery.js'></script> \n";
		echo "<script type='text/javascript' src='../core/plugins/jquery/jquery-migrate.js'></script>";
		//echo "<script type='text/javascript' src='../core/plugins/jquery/jquery.bgiframe.js'></script> \n";
		//echo "<script type='text/javascript' src='../core/plugins/jquery/jquery.dimensions.js'></script> \n";
		//echo "<script type='text/javascript' src='../core/plugins/jquery/jquery.delegate.js'></script> \n";
		echo "<script type='text/javascript' src='../core/common/javascript/sl_javascript.js'></script> \n";
	}


}


/** 
* @} 
*/


?>