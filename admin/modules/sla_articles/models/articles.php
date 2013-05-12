<?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_ARTICLES
* @internal     Admin articles module
* @version		articles.php - Version 12.7.12
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
	public function load_items() {
		
		/* Order */
		$filter = "";
		if ($_SESSION[$this->controller->module_name."_categorie1"] != -1) {
			
			$id_mod1 = $this->controller->module_id;
			$id_mod2 = $this->slash->sl_module_id("sla_categories");
			
			$filter = "JOIN ".$this->slash->db_prefix."joins 
						WHERE id_mod1='".$id_mod1."' 
								AND id_mod2='".$id_mod2."' 
								AND id2='".$_SESSION[$this->controller->module_name."_categorie1"]."' 
								AND id=id1 ";
			
		}
		
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			if ($filter == ""){
				$filter = "WHERE title LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' 
							OR content LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' ";
			}else{
				$filter .= "AND title LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' 
							OR content LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' ";
			}
		}
			
		$this->slash->database->setQuery("
			SELECT id,title,content,null,id_user,created_date,enabled 
			FROM ".$this->slash->db_prefix."articles ".$filter."
			ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$objects = array();
		$obj_ids = array("id","title","content","null","id_user","created_date","enabled");
		$obj_titles = array("ID",
						$this->slash->trad_word("TITLE"),
						$this->slash->trad_word("CONTENT"),
						$this->slash->trad_word("CATEGORIE"),
						$this->slash->trad_word("AUTHOR"),
						$this->slash->trad_word("CREATION_DATE"),
						$this->slash->trad_word("ACTIVE"));
		$obj_sorts = array(false,true,true,false,false,true,false);
		$obj_sizes = array(5,20,20,20,10,15,5);
		$obj_actions = array(false,"single_edit","single_edit","single_edit","single_edit","single_edit","set_state");
		$obj_controls = array("single_edit","single_delete");

		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			/* CONTENT */
			$sl_txt = new sl_text();
			$h2t_content = new html2text($row[2]);
			$row[2] = $sl_txt->substring_word(utf8_encode($h2t_content->get_text()),50,true);
			
			/* USER */
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->db_prefix."users WHERE id='".$row[4]."'");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			$row_user = $this->slash->database->fetch("ASSOC");
			$row[4] = $row_user["name"];
			
			/* CATEGORIE */
			$row[3] = $this->controller->categories->get_categories_titles($row["id"]);
			
			array_push($objects,$row);
		}

		unset($row);
		
		//Load listing
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
	}
	
	

	/**
	 * Load item
	 * @param $id item ID
	 */
	public function load_item($id) {
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->db_prefix."articles WHERE id=".$id);
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
			if (file_exists("../medias/attachments/sl_articles/".$value)) {
				$this->delete_attachment("../medias/attachments/sl_articles",$value);
			}
			
			$this->unlink_categories($value);
			
			$this->slash->database->setQuery("DELETE FROM ".$this->slash->db_prefix."articles WHERE id=".$value);
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
			$this->slash->database->setQuery("
					UPDATE ".$this->slash->db_prefix."articles set 
					id_user='".$_SESSION["id_user"]."',
					title='".$values["title"]."',
					content='".$values["content"]."',
					responsive_images='".$values["responsive_images"]."',
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'");
					
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
			$this->set_categories($id,$values["categories"]);
		
			return $this->slash->trad_word("EDIT_SUCCESS");	
			
		} else {
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("
					INSERT INTO ".$this->slash->db_prefix."articles
					(id,id_user,title,content,responsive_images,created_date,enabled) value
					('','".$_SESSION["id_user"]."','".$values["title"]."','".$values["content"]."','".$values["responsive_images"]."','".date ("Y-m-d H:i:s", time())."','".$values["enabled"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
					
			$insert_id = $this->slash->database->lastInsertId();

			$this->set_categories($insert_id,$values["categories"]);
			
			$ret = $this->save_attachment("../medias/attachments/sl_articles",$insert_id);
			
			if (!$ret){
				return $this->slash->trad_word("FILE_TRANFERT_FAIL");
			}else{
				return $this->slash->trad_word("SAVE_SUCCESS");
			}					
			
		}
		
	}
	

	/**
	 * Set is enabled
	 * @param $id article ID
	 */
	public function set_items_enabled($id_array,$enabled) {
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("UPDATE ".$this->slash->db_prefix."articles set enabled=".$enabled." WHERE id='".$value."'");
					
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
		$obj["categories"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		$obj["content"] = $this->slash->sl_param($this->controller->module_name."_obj3","POST");
		$obj["enabled"] = $this->slash->sl_param($this->controller->module_name."_obj5","POST");
		$obj["responsive_images"] = $this->slash->sl_param($this->controller->module_name."_obj6","POST");
		if (!is_array($obj["categories"])) {$obj["categories"] = null;}

		return $obj;
		
	}
	
	
	/**
	* Check add/edit values
	* @param $values:Array Object Values
	*/
	public function check_fields($values) {
		
		$mess = array();
		$values=$this->slash->database->escapeArray($values);
		/*
		$result = mysql_query("SELECT * FROM ".$this->slash->database_prefix."categories WHERE title='".$values["title"]."' AND id !='".$values["id"]."'",$this->slash->db_handle) or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
		if (mysql_num_rows($result)>0) {
			$mess[0]["message"] = $this->slash->trad_word("CATEGORIES_ERROR_EXIST");
		}
		*/
		//$mess[1]["message"] =  $this->slash->trad_word("ERROR_TITLE_FIELD_EMPTY");
		
		if (count($mess) > 0){ return $mess; } else { return null; }
	
	}
	
	/**
	 * Get categories
	 * @param int $id Article ID
	 */
	public function linked_categories($id=null){
		
		$id_mod1 = $this->controller->module_id;
		$id_mod2 = $this->slash->sl_module_id("sla_categories");
		$id1 = $id;

		$this->slash->database->setQuery("
				SELECT id FROM sl_categories 
				JOIN sl_joins 
				WHERE id_mod1='".$id_mod1."' 
				AND id_mod2='".$id_mod2."'  
				AND id1='".$id."' 
				AND id2=id");
		
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			return false;
		}
		
		$arr = array();
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			$arr[] = $row["id"];
		}
		return $arr;
	}
	
	/**
	 * Set articles categories
	 * @param int $id ID articles
	 * @param array $ids_categories Categories IDS 
	 */
	private function set_categories($id,$ids_categories=null,$delete=false){
		
		$id_mod1 = $this->controller->module_id;
		$id_mod2 = $this->slash->sl_module_id("sla_categories");
		$id1 = $id;
		
		$this->unlink_categories($id1);
		
		//Add categories
		if (count($ids_categories)>0 && $ids_categories!==null) {
			foreach ($ids_categories as $id2){
				$this->slash->database->setQuery("
						INSERT INTO ".$this->slash->db_prefix."joins
						(id_mod1,id_mod2,id1,id2) VALUES
						('".$id_mod1."','".$id_mod2."','".$id1."','".$id2."')");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
					return false;
				}
			}
		}
		return true;
	}
	
	
	/**
	 * Unlink categories
	 * @param int $id
	 * @return boolean success ?
	 */
	private function unlink_categories($id){
		
		$id_mod1 = $this->controller->module_id;
		$id_mod2 = $this->slash->sl_module_id("sla_categories");
		$id1 = $id;
		
		//Delete current categorie
		$this->slash->database->setQuery("DELETE FROM ".$this->slash->db_prefix."joins WHERE id_mod1='".$id_mod1."' AND id_mod2='".$id_mod2."' AND id1 = '".$id1."'");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			return false;
		}
		
		return true;
	}
	
}

/** 
* @} 
*/

?>