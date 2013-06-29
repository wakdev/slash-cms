<?php
/**
* @package		SLASH-CMS
* @subpackage	SL_PAGES
* @internal     Front page module
* @version		pages.php - Version 13.5.2
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

* @addtogroup sl_pages
* @{

*/

class pages extends slModel implements iModel{
	

	/**
	* Load page
	*/
	public function load_page($id){
	
		if ($id) {
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."pages WHERE enabled=1 AND id=".intval($id));
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			$row = $this->slash->database->fetch("ASSOC");
			if($row['responsive_images']) $row['content'] = sl_images::set_responsive($row['content']);
			return $row;
		} else {
			return NULL;
		}
		
	}
	
	
	/*
	public function load_attachments($id,$id_module,$where){
		$attachments = array();
		
		$i=0;
		$result = mysql_query("select * from ".$this->slash->database_prefix."attachments where id_module = '".$id_module."' and id_element = '".$id."' and state = 1 ".$where." order by position asc ");
		while($row = mysql_fetch_array($result))
		{
			$attachments[$i]["filename"] = $row["filename"];
			$attachments[$i]["id_field"] = $row["id_field"];
			$i++;
		}
		
		return $attachments;
	}
	*/
	
}

/** 
* @} 
*/

?>