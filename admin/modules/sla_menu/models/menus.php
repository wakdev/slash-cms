<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_menu
* @internal     Admin menu module
* @version		menus.php - Version 11.6.1
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

* @addtogroup sla_menu
* @{

*/


class menus extends slaModel implements iModel{

	
	
	/**
	 * Load links from database
	 */
	public function load_items() {
						
		$filter = "";
		if ($_SESSION[$this->controller->module_name."_categorie1"] != -1) {
			$filter_menu = $_SESSION[$this->controller->module_name."_categorie1"];
		}

		$objects = array();
		$obj_ids = array("id","title","action","home","enabled");
		$obj_titles = array("ID",
							$this->slash->trad_word("TITLE"),
							$this->slash->trad_word("SLA_MENU_ACTION"),
							$this->slash->trad_word("SLA_MENU_HOME"),
							$this->slash->trad_word("ACTIVE"));
		$obj_sorts = array(false,false,false,false,false);
		$obj_sizes = array(5,30,40,10,5);
		$obj_actions = array(false,"single_edit","single_edit","set_home","set_state");
		$obj_controls = array("single_up","single_down","single_edit","single_delete");
		
		$this->load_child_links($objects,$filter_menu,0,0);

		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,false);

	}
	
	/**
	 * Set position
	 * @param $id item ID
	 */
	public function set_position($id,$order) {
			
			$link = $this->load_item($id);
			
			if ($order=="up") { $next_position = $link["position"]-1; } 
			if ($order=="down") { $next_position = $link["position"]+1; } 
			
			if ($order=="up" || $order=="down") {
				
				
				$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."menu WHERE pri_type='2' AND parent='".$link["parent"]."' AND position='".$next_position."'");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
				
				$num_rows = $this->slash->database->rowCount();
				
				if ($num_rows != 0) {
					$row = $this->slash->database->fetch("ASSOC");
					$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."menu set position='".($link["position"])."' WHERE parent='".$link["parent"]."' AND position='".$next_position."'");
					if (!$this->slash->database->execute()) {
						$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
					}
					
					$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."menu set position='".$next_position."' WHERE id='".$link["id"]."'");
					if (!$this->slash->database->execute()) {
						$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
					}
				
				}
			}
	}
	
	
	/**
	 * Load item
	 * @param $id item ID
	 */
	public function load_item($id) {
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."menu WHERE id=".$id);
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
			
			/*if (file_exists("../medias/attachments/sl_articles/".$value)) {
				$this->delete_attachment("../medias/attachments/sl_articles",$value);
			}*/
			$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."menu WHERE id=".$value);
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		}
	}
	
	/**
	 * Load menus from database
	 */
	public function load_menus() {
		
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."menu WHERE pri_type=1 AND enabled=1 ORDER BY position");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$menus = array();
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			array_push($menus,$row); 
		}
		return $menus;
		
	}
	
	/**
	 * Load links
	 * @param $id item ID
	 */
	public function load_links($menu_id) {
		
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."menu WHERE pri_type=2 AND menu_id='".$menu_id."' ORDER BY parent,position");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$links = array();
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			array_push($links,$row); 
		}
		return $links;
	}
	
	
	/**
	 * Load child links
	 */
	public function load_child_links(&$objects,$menu_id,$parent,$level) {
	
		$count=0;
		
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."menu WHERE pri_type=2 AND menu_id=".$menu_id." AND parent=".$parent." ORDER BY position");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		foreach ($this->slash->database->fetchAll("BOTH") as $row_link) {
		    
		    $row = array();
		    $row[0] = $row_link["id"];
			$row["id"] = $row_link["id"];
		    $row[1] = " ".$row_link["title"];
			
			 
		    for ($i=0; $i<$level; $i++){
		    $row[1] = "--".$row[1];
		    }
		   
		    $row[2] = $row_link["action"];
			$row[3] = $row_link["home"];
			$row[4] = $row_link["enabled"];
			
		    array_push($objects,$row); 

		    $count++;
		    
		    
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."menu WHERE pri_type=2 AND menu_id=".$menu_id." AND parent=".$row_link["id"]);
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
		    $child_count = $this->slash->database->rowCount();
		    
		    if ($child_count > 0 ) {
			    $this->load_child_links($objects,$menu_id,$row_link["id"],$level+1);
		    }
					
		}
		
	
	}
	
	
	
	
	/**
	 * Load link menu
	 * @param $id link menu ID
	 */
	 /*
	protected function load_link($id) {
			$result = mysql_query("SELECT * FROM ".$this->slash->database_prefix."menu WHERE type='image' AND id=".$id,$this->slash->db_handle) or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $row;
	}
	*/
	
	/**
	 * Save categorie
	 */
	public function save_item($id,$values){
		
		if ($id != 0) {
			
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."menu set 
					menu_id='".$values["menu_id"]."',
					title='".$values["title"]."',
					action='".$values["action"]."',
					parent='".$values["parent"]."',
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}		
					
			return $this->slash->trad_word("EDIT_SUCCESS");	
			
		} else {
		
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("INSERT INTO ".$this->slash->database_prefix."menu
					(id,menu_id,pri_type,sec_type,parent,position,title,action,enabled) value
					('','".$values["menu_id"]."','2','url_self','".$values["parent"]."','0','".$values["title"]."','".$values["action"]."','".$values["enabled"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}	
					
			$insert_id = $this->slash->database->lastInsertId();
			/*		
			$ret = $this->save_attachment("../medias/attachments/sl_articles",$insert_id);
			
			if (!$ret){
				return $this->slash->trad_word("FILE_TRANFERT_FAIL");
			}else{
				return $this->slash->trad_word("SAVE_SUCCESS");
			}	
			*/
			return $this->slash->trad_word("SAVE_SUCCESS");
		}
		
	}
	
	/**
	 * Save attachment 
	 */
	public function save_attachment($destination,$id_element){
		
		/*$result_files = mysql_query("SELECT * FROM ".$this->slash->database_prefix."attachments WHERE id_user='".$_SESSION["id_user"]."' and id_module='".$this->controller->module_id."' and state='0' ORDER BY position",$this->slash->db_handle) 
		or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());	*/
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."attachments WHERE id_user='".$_SESSION["id_user"]."' and id_module='".$this->controller->module_id."' and state='0' ORDER BY position");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		if ($this->slash->database->rowCount() > 0) {
		
			$sl_files_sv = new sl_files();
			
			if (!$sl_files_sv->make_dir($destination."/".$id_element)){
				return false;
			}
			
			
			
			
			foreach ($this->slash->database->fetchAll("BOTH") as $row) {
				if (!$sl_files_sv->move_files("../tmp/".$row["filename"],$destination."/".$id_element."/".$row["filename"])){
					return false;
				}
			}
			
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."attachments set 
					id_element='".$id_element."',
					state='1'
					WHERE id_user='".$_SESSION["id_user"]."' and id_module='".$this->controller->module_id."' and state='0'
					");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		
			return true;
		}else{
			return true;
		}
	}
	
	/**
	* Delete attachment
	*/
	public function delete_attachment($destination,$id_element){
	
		$sl_files_dl = new sl_files();
		
		if ($sl_files_dl->remove_dir($destination."/".$id_element)) {
			
			$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."attachments WHERE id_module=".$this->controller->module_id." AND id_element='".$id_element."' and state=1");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}		
			
			return true;
		}else{
			return false;
		}
				
	}
	
	/**
	 * Set is enabled
	 * @param $id menu ID
	 */
	public function set_items_enabled($id_array,$enabled) {
			
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."menu set enabled=".$enabled." WHERE id='".$value."'");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
			}
	}
	
	/**
	 * Set home
	 * @param $id menu ID
	 */
	public function set_home($id) {
			
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."menu set home=0 WHERE 1");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."menu set home=1 WHERE id='".$id."'");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		
	}
	
	
	/**
	 * Load categorie from database
	 */
	public function load_categories() {
		
		
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."categories ORDER BY title");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$categories = array();
		$i = 0;
		foreach ($this->slash->database->fetchAll("ASSOC") as $row) {
			$categories[$i] = $row;
			$i++;
			
		}
		return $categories;
		
	}
	
	
	/**
	* Recovery fields value
	*/
	public function recovery_fields() {
	
		$obj = array();
		$obj["id"] = $this->slash->sl_param($this->controller->module_name."_id_obj","POST");
		$obj["title"] = $this->slash->sl_param($this->controller->module_name."_obj1","POST");
		$obj["menu_id"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		$obj["parent"] = $this->slash->sl_param($this->controller->module_name."_obj3","POST");
		$obj["action"] = $this->slash->sl_param($this->controller->module_name."_obj4","POST");
		$obj["enabled"] = $this->slash->sl_param($this->controller->module_name."_obj5","POST");
		
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