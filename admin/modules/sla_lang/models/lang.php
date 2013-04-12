<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_lang
* @internal     Admin lang module
* @version		lang.php - Version 11.3.25
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

* @addtogroup sla_lang
* @{

*/


class lang extends slaModel implements iModel{



	public function load_items() {
		
		$search = "";
			
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			$search = "WHERE title LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' OR shortname LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
		}
			
		
		$this->slash->database->setQuery("SELECT id,name,shortname,enabled FROM ".$this->slash->database_prefix."lang WHERE enabled='1' ".$search."ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}

		
		
		$objects = array();
		
		$obj_ids = array("id","name","shortname","flag");
		$obj_titles = array("ID",$this->slash->trad_word("NAME"),$this->slash->trad_word("LANG_SHORTNAME"),$this->slash->trad_word("LANG_FLAGS"));
		$obj_sorts = array(false,true,true,false);
		$obj_sizes = array(5,40,25,10);
		$obj_actions = array(false,false,false,false);

		$obj_controls = array("single_delete");
		

		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			
			/* CONTENT */
			$row[3] = "<img src='templates/system/images/flags/".$row[2].".png' height='20' width='20' />";
			
			array_push($objects,$row);
		}
		
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,false);
		
	}
	
	/**
	 * Get the language shortname
	 * @param none
	 * @return shortname languages available array
	 */
	public function load_disabled_items() {
			
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."lang WHERE enabled='0'");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
			$return_array = array();
			$id = 0;
			
			foreach ($this->slash->database->fetchAll("ASSOC") as $row) {
				$return_array[$id]["id"] = $row["id"];
				$return_array[$id]["shortname"] = $row["shortname"];
				$return_array[$id]["name"] = $row["name"];
				$id++;
			}
			
			return $return_array;
	}
	
	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	public function load_item($id) {
			
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."lang WHERE id='".$id."'");
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
			
			$id_array=$this->slash->database->escapeArray($id_array);
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."lang set 
					enabled='0' 
					WHERE id='".$value."'");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}	
				
			}
	}
	
	
	/**
	 * Save categorie
	 */
	public function save_item($id,$values){
		
		/*if ($id != 0) {*/
		$values=$this->slash->database->escapeArray($values);
		$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."lang set  
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'
					");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}		
					
					
		/*} else {
			$result = mysql_query("INSERT INTO ".$this->slash->database_prefix."lang
					(id,name,shortname,enabled) value
					('','".$values["name"]."','".$values["shortname"]."','".$values["enabled"]."')",$this->slash->db_handle) 
					or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
		}*/
					
	}
	
	/**
	 * Set is enabled
	 * @param $id article ID
	 *//*
	private function set_items_enabled($id_array,$enabled) {
			
			foreach ($id_array as $value) {
				$result = mysql_query("UPDATE ".$this->slash->database_prefix."lang set enabled='".$enabled."' WHERE id='".$value."'",$this->slash->db_handle) or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
			}
	}*/
	
	
	
	/**
	* Recovery fields value
	*/
	public function recovery_fields() {
	
		$obj = array();
		
		$obj["id"] = $this->slash->sl_param($this->controller->module_name."_obj0","POST");
		$obj["name"] = $this->slash->sl_param($this->controller->module_name."_obj0","POST");
		$obj["shortname"] = $this->slash->sl_param($this->controller->module_name."_obj0","POST");
		$obj["enabled"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		
		return $obj;
		
	}
	
	
	/**
	* Check add/edit values
	* @param $values:Array Object Values
	*/
	public function check_fields($values) {
		/* TODO */
	}
	

}

/** 
* @} 
*/


?>