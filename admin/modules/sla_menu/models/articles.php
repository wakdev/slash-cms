<?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_ARTICLES
* @internal     Admin articles module
* @version		articles.php - Version 13.1.29
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

 
* @addtogroup sla_articles
* @{

*/


class articles extends slaModel implements iModel{

	
	/**
	* Load items
	*/
	public function load_articles() {
		
		$this->slash->database->setQuery("
			SELECT id,title,content,id_user,created_date,enabled 
			FROM ".$this->slash->db_prefix."articles 
			ORDER BY title");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		
		$articles = array();
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			array_push($articles,$row); 
		}
		
		return $articles;

	}
	
	

	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	public function load_item($id) {
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->db_prefix."articles WHERE id=".$id);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}

		return $this->slash->database->fetch("ASSOC");
	}
	
	

	
	

	
	

	
	
	
	
	

	
}

/** 
* @} 
*/

?>