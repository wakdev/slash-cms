 <?php
/**
* @package		SLASH-CMS
* @subpackage	sl_error
* @internal     Error module
* @version		sl_error.php - Version 9.6.2
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
* @name sl_error
* @defgroup sl_error sl_error
* Front modules : Error
* @{
*/


include ("views/default/sl_error_view.php");

class sl_error extends sl_error_view implements iController{

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
		//no global initialisation for this module
	}
	
	
	/**
	 * Load header function
	 */
	public function load_header(){
		$this->header();
	}
	
	/**
	 * Load footer function
	 */
	public function load_footer(){
		$this->footer();
	}
	
	
	/**
	* Load function
	*/
	public function load() {
		
		$this->show_error($this->slash->sl_param("id","GET"));
		
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
