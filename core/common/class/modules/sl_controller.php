<?php
/**
* @package		SLASH-CMS
* @subpackage	CONTROLLER ABSTRACT CLASS
* @internal     Module Controller
* @version		sl_controller.php - Version 12.3.2
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

abstract class slController{

	public $slash; //Core Reference
	public $params;
	public $module_id;
	public $module_name;
	public $message;
	
	protected $mode;
	protected $errors;
	protected $datas;

	/**
	* Contructor
	* @param core_class_ref Core class reference
	*/
	function __construct(&$core_class_ref,$module_id,$module_name,$module_params=null) {
       $this->slash = $core_class_ref;
       $this->module_id = $module_id;
       $this->module_name = $module_name;
       $this->setParams($module_params);
	   $this->sl_construct();
	}
	
	public function sl_construct() {}
	
	public function initialize(){} //Initialize function 
	public function load_header(){} //Load header function
	public function load_footer(){} //Load footer function
	public function load(){} //Load module function
	public function execute(){} //Execute function
	
	/**
	 * Set module params
	 * @param array $params Parameter Array
	 */
	public function setParams($params=null){
		if ($params!==null){
			$my_params = array();
			foreach (explode('&', $params) as $param) {
				if ($param && strlen($param) > 0){
					$item = explode('=', $param);
					if(count($item)==2 && isset($item[0]) && isset($item[1])){
						$my_params[$item[0]] = $item[1];
					}
				}
			}
			if (count($my_params) > 0) {
				$this->params = array();
				$this->params = $my_params;
			}else{
				$this->params = null;
			}
		}
	}
	
	/**
	 * Get module params
	 * @param string $name Name
	 */
	public function getParam($name=null){
		if ($name!==null && is_array($this->params) && array_key_exists($name, $this->params)){
			return $this->params[$name];
		}else{
			return null;
		}
	}
	
}

?>