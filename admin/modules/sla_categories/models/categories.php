<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_categories
* @internal     sla_categories
* @version		categories.php - Version 12.3.25
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

* @addtogroup sla_categories
* @{

*/


class categories extends slaModel implements iModel{


	public function load_items() {
		
		$search = "";
			
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			$search = "WHERE title LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' OR description LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' ";
		}
			
		/*$result = mysql_query("SELECT id,title,description FROM ".$this->slash->database_prefix."categories ".$search."ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"],
		$this->slash->db_handle) or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
		*/
		$this->slash->database->setQuery("SELECT id,title,description FROM ".$this->slash->database_prefix."categories ".$search."ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$objects = array();
		
		$obj_ids = array("id","title","description");
		$obj_titles = array("ID",$this->slash->trad_word("TITLE"),$this->slash->trad_word("DESCRIPTION"));
		$obj_sorts = array(false,true,true);
		$obj_sizes = array(5,40,40);
		$obj_actions = array(false,"single_edit","single_edit");

		$obj_controls = array("single_edit","single_delete");
		

				foreach ($this->slash->database->fetchAll("BOTH") as $row) {
		
			/* CONTENT */
			$sl_txt = new sl_text();
			$h2t_content = new html2text($row[2]);
			$row[2] = $sl_txt->substring_word($h2t_content->get_text(),80,true);
			$row[2] = utf8_encode($row[2]);
			
			
			array_push($objects,$row);
		}
		
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
	}
	
	
	
	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	public function load_item($id) {
			
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."categories WHERE id=".$id);
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
				$id_mod1 = $this->slash->sl_module_id("sla_articles");
		$id_mod2 = $this->controller->module_id;			
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."categories WHERE id=".$value);
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
				
				//Delete linked categories
				$this->slash->database->setQuery("DELETE FROM ".$this->slash->db_prefix."joins WHERE id_mod1='".$id_mod1."' AND id_mod2='".$id_mod2."' AND id2 = '".$value."'");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
					return false;
				}
			}
	}
	
	
	/**
	 * Save categorie
	 */
	public function save_item($id,$values){
		
		if ($id != 0) {
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."categories set 
					id_user='".$_SESSION["id_user"]."',
					title='".$values["title"]."',
					description='".$values["description"]."' 
					WHERE id='".$values["id"]."'
					");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
					
					
		} else {
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("INSERT INTO ".$this->slash->database_prefix."categories
					(id,id_user,title,description) value
					('','".$_SESSION["id_user"]."','".$values["title"]."','".$values["description"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
		}
		
		return $this->slash->trad_word("SAVE_SUCCESS");
					
	}
	
	
	
	/**
	* Recovery fields value
	*/
	public function recovery_fields() {
	
		$obj = array();
		$obj["id"] = $this->slash->sl_param($this->controller->module_name."_id_obj","POST");
		$obj["title"] = $this->slash->sl_param($this->controller->module_name."_obj1","POST");
		$obj["description"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		
		return $obj;
		
	}
	
	
	/**
	* Check add/edit values
	* @param $values:Array Object Values
	*/
	public function check_fields($values) {
		
		$mess = array();
		
		//Categorie verification
		
		$values=$this->slash->database->escapeArray($values);
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."categories WHERE title='".$values["title"]."' AND id !='".$values["id"]."'");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}

		
		if ($this->slash->database->rowCount()>0) {
			$mess[0]["message"] = $this->slash->trad_word("CATEGORIES_ERROR_EXIST");
		}
		
		
		if (count($mess) > 0){ return $mess; } else { return null; }
	
	}
	
}


/** 
* @} 
*/

?>