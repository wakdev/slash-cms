 <?php
/**
* @package		SLASH-CMS
* @subpackage	sla_country
* @internal     Admin country module
* @version		sla_country.php - Version 11.1.14
* @author		Julien Veuillet [http://www.wakdev.com]
* @copyright	Copyright(C) 2010 - Today. All rights reserved.
* @license		GNU/GPL
*/

/**
* @file
* @name sla_country
* @defgroup sla_country sla_country
* Administration modules country
* @{
*/


// Include Default Module View
include ("views/default/sla_country_view.php");

class sla_country extends sla_country_view implements iController{

	public $slash; //Core Reference
	public $params;
	public $module_name = "sla_country";
	public $module_id;
	
	//Private module params
	protected $mode;
	protected $message;
	protected $errors;
	protected $datas;
	
	/**
	* Contructor
	* @param core_class_ref Core class reference
	*/
	function __construct(&$core_class_ref,$module_id,$module_name,$params) {
       $this->slash = $core_class_ref;
       $this->module_id = $module_id;
       $this->module_name = $module_name;
       $this->params = $params;
	   
	   $this->load_params(); //Load params
	}
	
	
	/**
	* Initialize function 
	* Require function by slash core
	*/
	public function initialize() {
		//no global initialisation for this module
	}
	
	/**
	 * Load header function
	 */
	public function load_header(){
		$this->header(); //Global header
		sl_interface::listing_sessions($this->module_name,array('name','shortname','enabled')); // session for listing
		
		switch ($this->mode) {
			case "show":
				$this->l_header(); //List header
			break;
			case "add":
			case "edit":
				$this->f_header(); //Form header
			break;
			case "delete":
				//nothing
			break;
			default:
				//nothing
		}
	}
	
	
	/**
	 * Load footer function
	 */
	public function load_footer(){
		$this->footer();
	}
	
	/**
	* Load module function
	* Require function by slash core
	*/
	public function load() {
		switch ($this->mode) {
			case "add": //Add article
				$this->show_form();
			break;
			case "edit": //Edit article
				$this->show_form($this->datas["id"],$this->datas,$this->errors);	
			break;
			case "delete"://Delete article
				$this->show_delete($this->slash->sl_param($this->module_name."_checked","POST"));
			break;
			case "show": //List view
				$this->show_items($this->message);
			break;
			default: // default view
				$this->show_items($this->message);
		}
	}
	
	
	/**
	* Execute function
	* Require function by slash core
	*/
	public function execute() {
		
	}
	
	
	
	/* ---------------- */
	/* MODULE FUNCTIONS */
	/* ---------------- */
	private function load_params() {
	
		switch ($this->slash->sl_param($this->module_name."_act","POST")) {
			case "add": //Add
				$this->mode = "add";
			break;
			case "edit": //Edit
				$values = $this->slash->sl_param($this->module_name."_checked","POST");
				if (isset ($values) && count($values) > 0) {
					reset($values);
					$this->datas = $this->load_item(current($values));
					$this->mode = "edit";
				}else{
					$this->mode = "show";
					$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
				}
			break;
			case "save": //Save
			
				$this->datas = $this->recovery_fields();
				$this->errors = $this->check_fields($this->datas);
				
				if ($this->errors != null) {
					$this->mode = "edit";
				}else{
					$this->save_item($this->datas["id"],$this->datas);
					$this->message = $this->slash->trad_word("SAVE_SUCCESS");
					$this->mode = "show";
				}
			
			break;
			case "delete"://Delete
				if ($this->slash->sl_param($this->module_name."_valid","POST")) {
					$this->delete_items($this->slash->sl_param($this->module_name."_checked","POST"));
					$this->mode = "show";
					$this->message = $this->slash->trad_word("DELETE_SUCCESS"); 
				}else {
					$values = $this->slash->sl_param($this->module_name."_checked","POST");
					if (isset ($values) && count($values) > 0) {
						$this->mode = "delete";
					}else{
						$this->mode = "show";
						$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
					}
				}		
			break;
			case "set_enabled": //Set enabled
				$values = $this->slash->sl_param($this->module_name."_checked","POST");
				if (isset ($values) && count($values) > 0) {
					$this->set_items_enabled($this->slash->sl_param($this->module_name."_checked","POST"),1);
					$this->mode = "show";
					$this->message = $this->slash->trad_word("ITEM_ENABLE_SUCCESS");
				}else{
					$this->mode = "show";
					$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
				}
			break;
			case "set_disabled": //Set disabled
				$values = $this->slash->sl_param($this->module_name."_checked","POST");
				if (isset ($values) && count($values) > 0) {
					$this->set_items_enabled($this->slash->sl_param($this->module_name."_checked","POST"),0);
					$this->mode = "show";
					$this->message = $this->slash->trad_word("ITEM_DISABLE_SUCCESS");
				}else{
					$this->mode = "show";
					$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
				}
			break;
			default: // default view
				$this->mode = "show";
		}
	
	}
	
	
	protected function load_items() {
		
		$search = "";
			
		if ($_SESSION[$this->module_name."_search"] != "#") {
			$search = "WHERE title LIKE '%".$this->slash->database->escape($_SESSION[$this->module_name."_search"])."%' OR shortname LIKE '%".$this->slash->database->escape($_SESSION[$this->module_name."_search"])."%' ";
		}
			
		
		$this->slash->database->setQuery("SELECT id,name,shortname,enabled FROM ".$this->slash->database_prefix."country ".$search."ORDER BY ".$_SESSION[$this->module_name."_orderby"]." ".$_SESSION[$this->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}

		
		$objects = array();
		
		$obj_ids = array("id","name","shortname","enabled");
		$obj_titles = array("ID",$this->slash->trad_word("NAME"),$this->slash->trad_word("COUNTRY_SHORTNAME"),$this->slash->trad_word("ENABLED"));
		$obj_sorts = array(false,true,true,false);
		$obj_sizes = array(5,40,40,5);
		$obj_actions = array(false,"single_edit","single_edit","set_state");

		$obj_controls = array("single_edit","single_delete");
		
		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			/* CONTENT */
			
			array_push($objects,$row);
		}
		
		sl_interface::create_listing($this->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,true,true,true);
		
	}
	
	
	
	/**
	 * Load categorie
	 * @param $id Categorie ID
	 */
	protected function load_item($id) {
			
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."country WHERE id=".$id);
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
	protected function delete_items($id_array) {
			
			foreach ($id_array as $value) {
				$this->slash->database->setQuery("DELETE FROM ".$this->slash->database_prefix."country WHERE id=".$value);
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
			}
	}
	
	
	/**
	 * Save categorie
	 */
	protected function save_item($id,$values){
		
		if ($id != 0) {
			/*$result = mysql_query("UPDATE ".$this->slash->database_prefix."country set 
					name='".$values["name"]."',
					shortname='".$values["shortname"]."', 
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'
					",$this->slash->db_handle) 
					or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());*/
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."country set 
					name='".$values["name"]."',
					shortname='".$values["shortname"]."', 
					enabled='".$values["enabled"]."' 
					WHERE id='".$values["id"]."'
					");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}	
					
		} else {
		
			/*$result = mysql_query("INSERT INTO ".$this->slash->database_prefix."country
					(id,name,shortname,enabled) value
					('','".$values["name"]."','".$values["shortname"]."','".$values["enabled"]."')",$this->slash->db_handle) 
					or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());*/
			$values=$this->slash->database->escapeArray($values);
			$this->slash->database->setQuery("INSERT INTO ".$this->slash->database_prefix."country
					(id,name,shortname,enabled) value
					('','".$values["name"]."','".$values["shortname"]."','".$values["enabled"]."')");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
		}
					
	}
	
	/**
	 * Set is enabled
	 * @param $id article ID
	 */
	private function set_items_enabled($id_array,$enabled) {
			
			foreach ($id_array as $value) {
				//$result = mysql_query("UPDATE ".$this->slash->database_prefix."country set enabled='".$enabled."' WHERE id='".$value."'",$this->slash->db_handle) or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
				$this->slash->database->setQuery("UPDATE ".$this->slash->database_prefix."country set enabled='".$enabled."' WHERE id='".$value."'");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
			}
	}
	
	
	
	/**
	* Recovery fields value
	*/
	private function recovery_fields() {
	
		$obj = array();
		$obj["id"] = $this->slash->sl_param($this->module_name."_id_obj","POST");
		$obj["name"] = $this->slash->sl_param($this->module_name."_obj0","POST");
		$obj["shortname"] = $this->slash->sl_param($this->module_name."_obj1","POST");
		$obj["enabled"] = $this->slash->sl_param($this->module_name."_obj2","POST");
		
		return $obj;
		
	}
	
	
	/**
	* Check add/edit values
	* @param $values:Array Object Values
	*/
	private function check_fields($values) {
		
		$mess = array();
		//Country verification
		$values=$this->slash->database->escapeArray($values);
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."country WHERE name='".$values["name"]."' AND id !='".$values["id"]."'");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		if ($this->slash->database->rowCount()>0) {
			$mess[0]["message"] = $this->slash->trad_word("COUNTRY_ERROR_EXIST");
		}
		
		if ($values["name"] == "") {
			$mess[0]["message"] = $this->slash->trad_word("ERROR_FIELD_EMPTY");
		}
		if ($values["shortname"] == "") {
			$mess[1]["message"] = $this->slash->trad_word("ERROR_FIELD_EMPTY");
		}
		
		if (count($mess) > 0){ return $mess; } else { return null; }
	
	}
	

}

/** 
* @} 
*/

?>
