<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_users
* @internal     Admin users module
* @version		users.php - Version 11.5.30
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

* @addtogroup sla_users
* @{

*/


class users extends slaModel implements iModel {

	
	
	
	public function load_items() {
		
		/* Order */
		$filter = "";
		if ($_SESSION[$this->controller->module_name."_categorie1"] != -1) {
			$filter = "WHERE grade=".$_SESSION[$this->controller->module_name."_categorie1"]." ";
		}		
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			if ($filter == ""){
				$filter = "WHERE name LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' OR mail LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}else{
				$filter .= "AND name LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' OR mail LIKE '%".$_SESSION[$this->controller->module_name."_search"]."%' ";
			}
		}
			
		
		$this->slash->database->setQuery("SELECT id,name,login,mail,language,grade,enabled 
								FROM ".$this->slash->database_prefix."users ".$filter."
								ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		
		$objects = array();
		$obj_ids = array("id","name","login","mail","language","grade","enabled" );
		$obj_titles = array("ID",
						$this->slash->trad_word("NAME"),
						$this->slash->trad_word("LOGIN"),
						$this->slash->trad_word("MAIL"),
						$this->slash->trad_word("USERS_LANGUAGE"),
						$this->slash->trad_word("GROUP"),
						$this->slash->trad_word("ACTIVE"));
		$obj_sorts = array(false,true,true,true,false,false,false);
		$obj_sizes = array(5,20,20,25,10,15,5);
		$obj_actions = array(false,"single_edit","single_edit","single_edit","single_edit","single_edit","set_state");
		$obj_controls = array("single_edit","single_delete");

		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			$row[5] = $this->get_grade_title($row[5]);
			array_push($objects,$row);
		}
		
		//Load listing
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
	}
	
	/**
	* Get Grade Title
	* @param $id Grade ID
	* @return string:Grade traduction
	*/
	public function get_grade_title($id) {
		switch ($id){
			case 0: //ADMIN [ALL ACCESS]
				return $this->slash->trad_word("USERS_GR_ADMINISTRATOR");
			break;
			case 1: //GESTION [REDACTION + CONFIG]
				return $this->slash->trad_word("USERS_GR_MANAGEMENT");
			break;
			case 2: //REDACTION [UNIQUEMENT REDACTION]
				return $this->slash->trad_word("USERS_GR_REDACTION");
			break;
			default:
				return $this->slash->trad_word("USERS_NO_GROUP");
		}
	}
	
	/**
	* Get Grade ID
	* @param $id User ID
	* @return int:Grade ID
	*/
	public function get_grade_id($id) {
		$this->slash->database->setQuery("SELECT grade FROM ".$this->slash->database_prefix."users WHERE id=".$id);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$row = $this->slash->database->fetch("ASSOC");
		return $row["grade"];
	}
	
	/**
	* Get Grade ID current User
	* @param -
	* @return boolean
	*/
	public function get_grade_id_current_user() {
	
		$current_user = $this->slash->get_admin_infos();
	
		return $current_user["grade"];
	}
	
	/**
	* Get Grade Acces
	* @param $grade User Grade
	* @return boolean
	*/
	public function get_grade_acces($grade) {
	
		$current_user = $this->slash->get_admin_infos();
	
		if($grade >= $current_user["grade"])
			return true;
		else	
			return false;
	}
	
	/**
	* Get Id Acces For lvl 2
	* @param $id User Id
	* @return boolean
	*/
	public function get_id_acces($id) {
		
		$current_user = $this->slash->get_admin_infos();
	
		if($id != $current_user["id"] && $current_user["grade"] == 2)
			return false;
		else
			return true;
	}
	
	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	public function load_item($id) {
		
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."users WHERE id=".$id);
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
			$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."users WHERE id=".$value);
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		}
	}
	
	
	/**
	 * Save user
	 * @param $id user id
	 */
	public function save_item($id,$values){

		if ($id != 0) {

			$values=$this->slash->database->escapeArray($values);			
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."users set 
					name='".$values["name"]."',
					login='".$values["login"]."',
					password='".sha1($values["_password"])."', 
					mail='".$values["mail"]."',
					grade='".$values["grade"]."',
					language='".$values["lang"]."',
					enabled='".$values["enabled"]."'
					WHERE id='".$values["id"]."'
					");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}	
					
		} else {
								
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("INSERT INTO ".$this->slash->database_prefix."users
					(id,name,login,password,mail,language,grade,enabled) value
					('','".$values["name"]."','".$values["login"]."','".sha1($values["_password"])."','".$values["mail"]."','".$values["lang"]."','".$values["grade"]."','".$values["enabled"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}		
		}

	}
	
	/**
	 * Set is enabled
	 * @param $id users ID
	 */
	public function set_items_enabled($id_array,$enabled) {	
		foreach ($id_array as $value) {
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."users set enabled=".$enabled." WHERE id='".$value."'");
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
		$obj["name"] = $this->slash->sl_param($this->controller->module_name."_obj1","POST");
		$obj["login"] = $this->slash->sl_param($this->controller->module_name."_obj2","POST");
		$obj["_password"] = $this->slash->sl_param($this->controller->module_name."_obj3","POST");
		$obj["_password2"] = $this->slash->sl_param($this->controller->module_name."_obj4","POST");
		$obj["mail"] = $this->slash->sl_param($this->controller->module_name."_obj5","POST");
		$obj["lang"] = $this->slash->sl_param($this->controller->module_name."_obj8","POST");
		$obj["grade"] = $this->slash->sl_param($this->controller->module_name."_obj6","POST");
		$obj["enabled"] = $this->slash->sl_param($this->controller->module_name."_obj7","POST");
	
		return $obj;
		
	}
	
	
	/**
	* Check add/edit values
	* @param $values:Array Object Values
	*/
	public function check_fields($values) {
		
		$mess = array();
		
		//Login verification
		$values=$this->slash->database->escapeArray($values);
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."users WHERE login='".$values["login"]."' AND id !='".$values["id"]."'");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		if ($this->slash->database->rowCount()>0) {
			$mess[1]["message"] = $this->slash->trad_word("USERS_ERROR_LOGIN_EXIST");
		}
		
		if ($values["login"] == "") {
			$mess[2]["message"] = $this->slash->trad_word("ERROR_FIELD_EMPTY");
		}
		
		//Password verification
		if ($values["_password"] != $values["_password2"]) {
			$mess[3]["message"] = $this->slash->trad_word("USERS_ERROR_PWD_NOT_SIMIL");
			$mess[4]["message"] = $this->slash->trad_word("USERS_ERROR_PWD_NOT_SIMIL");
		}
		if ($values["_password"] == "") {
			$mess[3]["message"] = $this->slash->trad_word("ERROR_FIELD_EMPTY");
		}
		if ($values["_password2"] == "") {
			$mess[4]["message"] = $this->slash->trad_word("ERROR_FIELD_EMPTY");
		}
		if (strlen($values["_password"]) < 3) {
			$mess[3]["message"] = $this->slash->trad_word("ERROR_PWD_SHORT");
		}
		if (strlen($values["_password2"]) < 3) {
			$mess[4]["message"] = $this->slash->trad_word("ERROR_PWD_SHORT");
		}
		
		if (count($mess) > 0){ return $mess; } else { return null; }
	
	}
	
	
	/**
	* Check values
	* @param $values:Array Values
	*/
	public function check_permission($values) {
		
		$mess = null;
		$current_user = $this->slash->get_admin_infos();
		
		if ($current_user != null && $current_user["grade"] == 0) { //Administrator
			foreach ($values as $value) {
				if ($value == $current_user["id"]) {
					$mess = $this->slash->trad_word("USERS_ERROR_DEL_CURRENT_ACCOUNT");
				}
			}
		}else if ($current_user != null && $current_user["grade"] == 1) { //Gestion
			foreach ($values as $value) {
				if ($value == $current_user["id"]) {
					$mess = $this->slash->trad_word("USERS_ERROR_DEL_CURRENT_ACCOUNT");
				}
				if(!$this->get_grade_acces($this->get_grade_id($value))){
					$mess = $this->slash->trad_word("USERS_ERROR_NO_PERMIT");
				}
			}
		}else{ //Others
			$mess = $this->slash->trad_word("USERS_ERROR_NO_PERMIT");
		}
		
		return $mess;
	}
	
}

/** 
* @} 
*/

?>