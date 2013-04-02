<?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_ARTICLES
* @internal     Admin articles module
* @version		categories.php - Version 12.7.12
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


class categories extends slaModel implements iModel{

	
	/**
	 * Load categorie from database
	 */
	public function load_categories() {
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->db_prefix."categories ORDER BY title");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}

		return $this->slash->database->fetchAll("ASSOC");
	}
	
	/**
	* Get categories titles
	*/
	public function get_categories_titles($id) {
		
		$id_mod1 = $this->controller->module_id;
		$id_mod2 = $this->slash->sl_module_id("sla_categories");
		$id1 = $id;
		
		$this->slash->database->setQuery("SELECT id,title FROM sl_categories JOIN sl_joins
				WHERE id_mod1='".$id_mod1."'
				AND id_mod2='".$id_mod2."'
				AND id1='".$id."'
				AND id2=id");
		
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			return false;
		}
		
		$ret = "";
		
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			$ret .= $row["title"].",";
		}
		
		if ($ret == "") { 
			$ret = $this->slash->trad_word("NONE");
		}else{
			$ret = substr($ret, 0, -1);
		}
		
		return $ret;
	}
	
}


/** 
* @} 
*/

?>