<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_modules
* @internal     Admin module
* @version		module.php - Version 16.6.11
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

* @addtogroup sla_modules
* @{

*/


class modules extends slaModel implements iModel{

	
	/* ---------------- */
	/* MODULE FUNCTIONS */
	/* ---------------- */
	
	public function load_items() {
		
		
		/* Order */
		$filter = "";
		if ($_SESSION[$this->controller->module_name."_categorie1"] != -1) {
			$filter = "WHERE type='".$_SESSION[$this->module_name."_categorie1"]."' ";
		}	
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			if ($filter == ""){
				$filter = "WHERE name LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}else{
				$filter .= "AND name LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}
		}

		
		$this->slash->database->setQuery("SELECT id,type,name,url,enabled, initialize_order 
								FROM ".$this->slash->database_prefix."modules ".$filter."ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$objects = array();
		
		$obj_ids = array("id","type","name","url","enabled");
		$obj_titles = array("ID",$this->slash->trad_word("TYPE"),$this->slash->trad_word("NAME"),$this->slash->trad_word("ADDRESS"),$this->slash->trad_word("ACTIVE"),$this->slash->trad_word("GLOBAL"));
		$obj_sorts = array(false,true,true,true,false,false);
		$obj_sizes = array(5,10,30,30,5,5);
		$obj_actions = array(false,"single_edit","single_edit","single_edit","set_state",false);

		$obj_controls = array("single_edit","single_delete");
		

		
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {

			
			/* CONTENT */
			/*$sl_txt = new sl_text();
			$h2t_content =& new html2text($row[2]);
			$row[2] = $sl_txt->substring_word($h2t_content->get_text(),80,true);
			*/
			
			
			if ($row[5] > 0) {
				$pos = $row[5];
				$row[5] = $this->slash->trad_word("YES")." (".$pos.")";
			}else{
				$row[5] = $this->slash->trad_word("NO")." (0)";
			}
			
			array_push($objects,$row);
		}
		
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
	}
	
	
	
	
	
	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	public function load_item($id) {
			
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."modules WHERE id=".$id);
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
				$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."modules WHERE id=".$value);
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
			}
	}
	
	
	/**
	 * Save categorie
	 */
	public function save_module($id,$values){
		
			
		if ($id != 0) {
			/*$result = mysql_query("UPDATE ".$this->slash->database_prefix."modules set 
					type='".$values["type"]."',
					name='".$values["name"]."',
					url='".$values["url"]."', 
					initialize_order='".$values["initialize_order"]."', 
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'
					",$this->slash->db_handle) 
					or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());*/
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."modules set 
					type='".$values["type"]."',
					name='".$values["name"]."',
					url='".$values["url"]."', 
					initialize_order='".$values["initialize_order"]."', 
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'
					");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}	
			
		} else {
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("INSERT INTO ".$this->slash->database_prefix."modules
					(id,type,name,url,initialize_order,enabled) value
					('','".$values["type"]."','".$values["name"]."','".$values["url"]."','".$values["initialize_order"]."','".$values["enabled"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		}
					
		
		
	}
	
	/**
	 * Set is enabled
	 * @param $id article ID
	 */
	public function set_items_enabled($id_array,$enabled) {
			
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."modules set enabled=".$enabled." WHERE id='".$value."'");
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
		$obj["type"] = $this->slash->sl_param($this->controller->module_name."_obj0","POST");
		$obj["name"] = $this->slash->sl_param($this->controller->module_name."_obj1","POST");
		$obj["url"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		$obj["initialize_order"] = $this->slash->sl_param($this->controller->module_name."_obj3","POST");
		$obj["enabled"] = $this->slash->sl_param($this->controller->module_name."_obj4","POST");
		
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
		}*/
		
		if (count($mess) > 0){ return $mess; } else { return null; }
	
	}

}

/** 
* @} 
*/

?>