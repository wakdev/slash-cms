<?php
/**
* @package		SLASH-CMS
* @subpackage	MODEL ABSTRACT CLASS
* @internal     Module Model
* @version		sl_model.php - Version 12.3.02
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

*/

abstract class slModel{


	public $slash; //Core Reference
	public $controller; //Control Reference
	
	/**
	* Contructeur
	*/
	function __construct(&$controller_class_ref) {
		$this->slash = &$GLOBALS["slash"];
		$this->controller = $controller_class_ref;
		
		$this->sl_construct();
	}
	
	public function sl_construct() {
	
	}
	
}

?>