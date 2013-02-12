<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_header
* @internal     Header module
* @version		sl_header.php - Version 9.6.2
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

/**
* @file
* @name sl_header
* @defgroup sl_header sl_header
* Front modules : Header
* @{
*/

include ("views/default/sl_header_view.php");

class sl_header extends sl_header_view implements iController{

	public $slash;
	public $params;
	public $module_id;
	
	/**
	* Contructor
	* @param core_class_ref Core class reference
	*/
	function __construct(&$core_class_ref,$module_id) {
       $this->slash = $core_class_ref;
       $this->module_id = $module_id;
	}
	
	
	
	/**
	* Initialize function
	*/
	public function initialize() {
		
		//echo $this->slash->sl_config("global_keywords");
		$this->scripts();
	
	}
	
	/**
	 * Load Header function 
	 * 
	 */
	public function load_header(){
		// Header is already sent
	}
	
	/**
	 * Load footer function
	 */
	public function load_footer(){
		// Footer is already sent
	}
	
	/**
	* Load function
	*/
	public function load() {
		
		// CHECK PAGE METAS OR SHOW GLOBAL METAS !!!
		
		$this->title($this->slash->config["site_name"]);
		$this->metas($this->slash->config["global_description"],$this->slash->config["global_keywords"]);
	}
	
	
	/**
	* Execute function
	*/
	public function execute() {
	
	
	
	}

}

/** 
* @} 
*/



?>