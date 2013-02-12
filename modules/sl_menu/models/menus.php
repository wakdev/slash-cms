<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_menu
* @internal     FRONT Menu module
* @version		menus.php - Version 12.2.14
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

* @addtogroup sl_menu
* @{

*/


class menus extends slModel implements iModel {

	

	/**
	* Loading menu function
	* @param $parent parent id
	*/
	public function load_menu($parent) {
	
	
		$this->slash->database->setQuery("SELECT * FROM sl_menu WHERE parent=".$parent." AND pri_type = 2 AND enabled = 1 ORDER by position");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		if( $this->slash->database->rowCount()  > 0 ) {
			
			if ($parent != 0 ) { $this->controller->view->start_under_menu();}
			
			foreach ($this->slash->database->fetchAll("BOTH") as $row) {
				$this->controller->view->start_menu($row);
				$this->load_menu($row["id"]);
			}
			
			if ($parent != 0 ) { $this->controller->view->end_under_menu();}
			
		} else {
			$this->controller->view->end_menu();
		}
		
		
	}
	
	
	/**
	* Check Homepage
	*/
	public function check_homepage(){
		
		$mod = $this->slash->sl_param("mod","GET");
		
		if (!isset($mod) || $mod == ""){
			
			$this->slash->database->setQuery("SELECT * FROM sl_menu WHERE home=1 AND pri_type = 2 AND enabled = 1 LIMIT 1");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
			if( $this->slash->database->rowCount()  > 0 ) {
				$row = $this->slash->database->fetch("BOTH");
				
				$sfilters = new sl_filters();
				$arr = array();
				$arr = $sfilters->parse_url_query($row["action"]);
				
				foreach($arr as $k => $v) {
					$this->slash->get_params[$k] = $v;
				} 
				
				$this->controller->is_home = true;
			
			}
		}
	}
	
	/**
	*
	*/
	public function is_current($action){
					
		$sfilters = new sl_filters();
		$arr1 = $arr2 = array();
		$arr1 = $sfilters->parse_url_query($action);
		$arr2 = $sfilters->parse_url_query($_SERVER['REQUEST_URI']);
		
		if ((isset($arr1) && isset($arr2) && $arr1 == $arr2) || $action === substr($_SERVER['REQUEST_URI'], 1)) {
			
			return true;
		
		}else{
			return false;
		}
	}
	
}

/** 
* @} 
*/

?>