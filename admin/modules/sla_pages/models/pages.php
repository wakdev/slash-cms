<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_pages
* @internal     Admin page module
* @version		pages.php - Version 11.5.30
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

	
	public function load_items() {
		
		/* Order */
		$filter = "";
		
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			if ($filter == ""){
				$filter = "WHERE title LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' OR content LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}else{
				$filter .= "AND title LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' OR content LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}
		}
			
		
		$this->slash->database->setQuery("SELECT id,title,content,enabled 
								FROM ".$this->slash->database_prefix."pages ".$filter."
								ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$objects = array();
		$obj_ids = array("id","title","content","enabled");
		$obj_titles = array("ID",
						$this->slash->trad_word("TITLE"),
						$this->slash->trad_word("CONTENT"),
						$this->slash->trad_word("ACTIVE"));
		$obj_sorts = array(false,true,true,false);
		$obj_sizes = array(5,40,40,5);
		$obj_actions = array(false,"single_edit","single_edit","set_state");
		$obj_controls = array("single_edit","single_delete");

		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			/* CONTENT */
			$sl_txt = new sl_text();
			$h2t_content = new html2text($row[2]);
			$row[2] = $sl_txt->substring_word(utf8_encode($h2t_content->get_text()),30,true);
		
			array_push($objects,$row);
		}
		
		//Load listing
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
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
	
	
	/**
	 * Delete categorie
	 * @param $id Categorie ID
	 */
	public function delete_items($id_array) {
		foreach ($id_array as $value) {
			$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."pages WHERE id=".$value);
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		}
	}
	
	
	/**
	 * Save categorie
	 */
	public function save_item($id,$values){
		
		if ($id != 0) {
			
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."pages set 
					id_user='".$_SESSION["id_user"]."',
					title='".$values["title"]."',
					content='".$values["content"]."',
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}		
					
			return $this->slash->trad_word("EDIT_SUCCESS");	
			
		} else {
					
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("INSERT INTO ".$this->slash->database_prefix."pages
					(id,id_user,title,content,date,enabled) value
					('','".$_SESSION["id_user"]."','".$values["title"]."','".$values["content"]."','".date ("Y-m-d H:i:s", time())."','".$values["enabled"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}

				
			$insert_id = $this->slash->database->lastInsertId();
			return $this->slash->trad_word("SAVE_SUCCESS");
						
			
		}
		
	}
	
	/**
	 * Set is enabled
	 * @param $id article ID
	 */
	public function set_items_enabled($id_array,$enabled) {
			
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."pages set enabled=".$enabled." WHERE id='".$value."'");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
			}
	}
		
	
	/**
	* Recovery fields value
	*/
	public function recovery_fields() {
	
		$obj = array();
		$obj["id"] = $this->slash->sl_param($this->controller->module_name."_id_obj","POST");
		$obj["title"] = $this->slash->sl_param($this->controller->module_name."_obj1","POST");
		$obj["content"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		$obj["enabled"] = $this->slash->sl_param($this->controller->module_name."_obj3","POST");
		
		return $obj;
		
	}
	
	
	/**
	* Check add/edit values
	* @param $values:Array Object Values
	*/
	public function check_fields($values) {
		
		$mess = array();
		
		/*
		$result = mysql_query("SELECT * FROM ".$this->slash->database_prefix."categories WHERE title='".$values["title"]."' AND id !='".$values["id"]."'",$this->slash->db_handle) or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
		if (mysql_num_rows($result)>0) {
			$mess[0]["message"] = $this->slash->trad_word("CATEGORIES_ERROR_EXIST");
		}
		*/
		//$mess[1]["message"] =  $this->slash->trad_word("ERROR_TITLE_FIELD_EMPTY");
		
		if (count($mess) > 0){ return $mess; } else { return null; }
	
	}
	
}

/** 
* @} 
*/

?>