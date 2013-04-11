<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_news
* @internal     Admin news module
* @version		news.php - Version 11.5.31
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

* @addtogroup sla_news
* @{

*/


class news extends slaModel implements iModel{

	

	public function load_items() {
		
		/* Order */
		$filter = "";
		if ($_SESSION[$this->controller->module_name."_categorie1"] != -1) {
			
			if ($_SESSION[$this->controller->module_name."_categorie1"] == 0) { // en cours
				$filter = "WHERE dateout > '".date("Y-m-d H:i:s")."' ";
			}else{ // passée
				$filter = "WHERE dateout < '".date("Y-m-d H:i:s")."' ";
			}
			
		}		
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			if ($filter == ""){
				$filter = "WHERE title LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}else{
				$filter .= "AND title LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}
		}
		
		
		
		
		$this->slash->database->setQuery("SELECT id,datein,dateout,title,content,id_user,date,enabled 
								FROM ".$this->slash->database_prefix."news ".$filter."
								ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$objects = array();
		$obj_ids = array("id","datein","dateout","title","content","id_user","date","enabled");
		$obj_titles = array("ID",
						$this->slash->trad_word("NEWS_PUBLISH_DATE"),
						$this->slash->trad_word("NEWS_UNPUBLISH_DATE"),
						$this->slash->trad_word("TITLE"),
						$this->slash->trad_word("CONTENT"),
						$this->slash->trad_word("AUTHOR"),
						$this->slash->trad_word("CREATION_DATE"),
						$this->slash->trad_word("ACTIVE"));
		$obj_sorts = array(false,true,true,true,false,false,true,false);
		$obj_sizes = array(5,15,15,20,15,5,15,5);
		$obj_actions = array(false,"single_edit","single_edit","single_edit","single_edit",false,false,"set_state");
		$obj_controls = array("single_edit","single_delete");

		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			
			/* DATE */
			if ($row[2] == "0000-00-00 00:00:00") {
				$row[2] = "----";
			}
			
			
			/* CONTENT */
			$sl_txt = new sl_text();
			$h2t_content = new html2text($row[4]);
			$row[4] = $sl_txt->substring_word($h2t_content->get_text(),20,true);
			
			/* USER */
			
			$this->slash->database->setQuery("SELECT id,name FROM ".$this->slash->database_prefix."users WHERE id='".$row[5]."'");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			$row_user = $this->slash->database->fetch("ASSOC");
			
			$row[5] = $row_user["name"];
			
			
			array_push($objects,$row);
		}
		
		//Load listing
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
	}
	
	

	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	public function load_item($id) {
	
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."news WHERE id=".$id);
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
			if (file_exists("../medias/attachments/sl_news/".$value)) {
				$this->delete_attachment("../medias/attachments/sl_news",$value);
			}
			$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."news WHERE id=".$value);
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
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."news set 
					id_user='".$_SESSION["id_user"]."',
					datein='".$values["datein"]." ".$values["timein"]."',
					dateout='".$values["dateout"]." ".$values["timeout"]."',
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
			$this->slash->database->setQuery("INSERT INTO ".$this->slash->database_prefix."news
						(id,id_user,datein,dateout,title,content,date,enabled) value
						('','".$_SESSION["id_user"]."','".$values["datein"]." ".$values["timein"]."','".$values["dateout"]." ".$values["timeout"]."','".$values["title"]."','".$values["content"]."','".date ("Y-m-d H:i:s", time())."','".$values["enabled"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}			
					
			$insert_id = $this->slash->database->lastInsertId();
					
			$ret = $this->save_attachment("../medias/attachments/sl_news",$insert_id);
			
			if (!$ret){
				return $this->slash->trad_word("FILE_TRANFERT_FAIL");
			}else{
				return $this->slash->trad_word("SAVE_SUCCESS");
			}					
			
		}
		
	}
	
	/**
	 * Save attachment 
	 */
	public function save_attachment($destination,$id_element){
		
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
	 * @param $id article ID
	 */
	public function set_items_enabled($id_array,$enabled) {
			
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."news set enabled=".$enabled." WHERE id='".$value."'");
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
		$obj["title"] = $this->slash->sl_param($this->controller->module_name."_obj0","POST");
		$obj["content"] = $this->slash->sl_param($this->controller->module_name."_obj1","POST");
		$obj["_datein"] = $this->slash->sl_param($this->controller->module_name."_obj3","POST");
		$obj["timein"] = $this->slash->sl_param($this->controller->module_name."_obj4","POST");
		$obj["permanent"] = $this->slash->sl_param($this->controller->module_name."_obj5","POST");
		$obj["_dateout"] = $this->slash->sl_param($this->controller->module_name."_obj6","POST");
		$obj["timeout"] = $this->slash->sl_param($this->controller->module_name."_obj7","POST");
		$obj["enabled"] = $this->slash->sl_param($this->controller->module_name."_obj8","POST");
		
		
		$datein = explode("/",$obj["_datein"]);
		$obj["datein"] = $datein[2]."-".$datein[1]."-".$datein[0];
		
		
		if ($obj["permanent"] == 1) {
			$obj["dateout"] = "0000-00-00 00:00:00";
		}else{
			$dateout = explode("/",$obj["_dateout"]);
			$obj["dateout"] = $dateout[2]."-".$dateout[1]."-".$dateout[0];
		}
		
		return $obj;
		
	}
	
	
	/**
	* Check add/edit values
	* @param $values:Array Object Values
	*/
	public function check_fields($values) {
		
		$mess = array();
		
		$sl_filters = new sl_filters();
		
		if (!$sl_filters->is_time($values["timein"])) {
			$mess[4]["message"] = $this->slash->trad_word("INVALID_FIELD_FORMAT")." ( ex : 09:05 )";
		}
		
		if ($values["permanent"] != 1 ) {
		
			if (!$sl_filters->is_time($values["timeout"])) {
				$mess[7]["message"] = $this->slash->trad_word("INVALID_FIELD_FORMAT")." ( ex : 09:05 )";
			}
			
			if ( !$sl_filters->date_compare($values["datein"],$values["dateout"])) {
				$mess[6]["message"] = $this->slash->trad_word("INVALID_DATE_INTERVAL");
			}
		}
		
		
		
		if (count($mess) > 0){ return $mess; } else { return null; }
	
	}
	
}

/** 
* @} 
*/

?>