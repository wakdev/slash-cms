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


class admmenu extends slaModel implements iModel {

	
	
	public function have_dropdown($id){
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."admmenu WHERE parent=".$id." AND enabled=1");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		if( $this->slash->database->rowCount()  > 0 ) {
			return true;
		}else{
			return false;
		}
	}

	/**
	* Loading menu function
	* @param $parent parent id
	*/
	public function load_menu($parent) {
	
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."admmenu WHERE parent=".$parent." AND enabled=1 ORDER by position");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}

		
		if( $this->slash->database->rowCount()  > 0 ) {
			
			if ($parent != 0 ) { $this->controller->view->start_under_menu();}

			foreach ($this->slash->database->fetchAll("ASSOC") as $row) {
				$this->controller->view->start_menu($row);
				$this->load_menu($row["id"]);
			}
			
			if ($parent != 0 ) { $this->controller->view->end_under_menu();}
			
		} else {
			$this->controller->view->end_menu();
		}
		
		
	}
	
}

/** 
* @} 
*/

?>