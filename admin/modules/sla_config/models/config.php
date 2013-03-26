<?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_CONFIG
* @internal     Admin config module
* @version		config.php - Version 12.7.12
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

 
* @addtogroup sla_config
* @{

*/


class config extends slaModel implements iModel{

	
	/**
	* Load items
	*/
	public function load_items() {
		
		/* Order */
		$filter = "";
		
		
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			if ($filter == ""){
				$filter = "WHERE config_name LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}else{
				$filter .= "AND config_name LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}
		}
			
		$this->slash->database->setQuery("
			SELECT id,config_name, config_value 
			FROM ".$this->slash->db_prefix."config ".$filter."
			ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$objects = array();
		$obj_ids = array("id","config_name","config_value",);
		$obj_titles = array("ID",
						$this->slash->trad_word("TITLE"),
						$this->slash->trad_word("CONTENT"));
		$obj_sorts = array(false,true,true);
		$obj_sizes = array(5,40,40);
		$obj_actions = array(false,"single_edit","single_edit");
		$obj_controls = array("single_edit","single_delete");

		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			
			
			array_push($objects,$row);
		}

		unset($row);
		
		//Load listing
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
	}
	
	

	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	public function load_item($id) {
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->db_prefix."config WHERE id=".$id);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}

		return $this->slash->database->fetch("ASSOC");
	}
	
	
	/**
	 * Delete categorie
	 * @param $id Categorie ID
	 */
	public function delete_items($id_array) {
		foreach ($id_array as $value) {
						
			$this->slash->database->setQuery("DELETE FROM ".$this->slash->db_prefix."config WHERE id=".$value);
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
			
			$this->slash->database->setQuery("
					UPDATE ".$this->slash->db_prefix."config set 
					config_name='".$values["config_name"]."', 
					config_value='".$values["config_value"]."'
					WHERE id='".$values["id"]."'");
					
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
			return $this->slash->trad_word("EDIT_SUCCESS");	
			
		} else {
		
			$this->slash->database->setQuery("
					INSERT INTO ".$this->slash->db_prefix."config
					(id,config_name,config_value) value
					('','".$values["config_name"]."','".$values["config_value"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
					
			$insert_id = $this->slash->database->lastInsertId();
			
			return $this->slash->trad_word("SAVE_SUCCESS");
							
			
		}
		
	}
	
	
	/**
	* Recovery fields value
	*/
	public function recovery_fields() {
	
		$obj = array();
		$obj["id"] = $this->slash->sl_param($this->controller->module_name."_id_obj","POST");
		$obj["config_name"] = $this->slash->sl_param($this->controller->module_name."_obj1","POST");
		$obj["config_value"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		
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