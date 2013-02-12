<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_pages
* @internal     Admin page module
* @version		pages.php - Version 13.1.29
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

* @addtogroup sla_pages
* @{

*/


class pages extends slaModel implements iModel{

	
	public function load_pages() {
		
		
		$this->slash->database->setQuery("SELECT id,title,content,enabled 
								FROM ".$this->slash->database_prefix."pages 
								ORDER BY title");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$pages = array();
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			array_push($pages,$row); 
		}
		
		return $pages;

	}
	
	

	/**
	 * Load items
	 * @param $id item ID
	 */
	public function load_item($id) {
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."pages WHERE id=".$id);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		$row = $this->slash->database->fetch("ASSOC");
		
		return $row;
	}
	
	
	
	
}

/** 
* @} 
*/

?>