<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_articles
* @internal     Front page module
* @version		articles.php - Version 12.02.13
* @author		Julien veuillet
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

* @addtogroup sl_articles
* @{

*/

class articles extends slModel implements iModel{
	

	/**
	* Load page
	*/
	public function load_article($id){
	
		if ($id) {
			
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."articles WHERE enabled=1 AND id=".$id);
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			$row = $this->slash->database->fetch("ASSOC");
			if($row['responsive_images']) $row['content'] = sl_images::set_responsive($row['content']);
			$row["attachments"] = $this->load_attachments($id);
			var_dump($row);
			return $row;
		} else {
			return NULL;
		}
		
	}
	
	
	
	
	public function load_attachments($id,$where=""){
		
		$id_module = $this->slash->sl_module_id("sla_articles","admin");
		
		$attachments = array();
		
		$i=0;
		$this->slash->database->setQuery("select * from ".$this->slash->database_prefix."attachments where id_module = '".$id_module."' and id_element = '".$id."' and state = 1 ".$where." order by position asc ");
		if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		$attachments = $this->slash->database->fetchAll("ASSOC");
		
		return $attachments;
	}
	
	
}


/** 
* @} 
*/

?>