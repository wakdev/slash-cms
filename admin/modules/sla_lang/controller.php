 <?php
/**
* @package		SLASH-CMS
* @subpackage	sla_lang
* @internal     Admin lang module
* @version		sla_lang.php - Version 11.1.14
* @author		Julien Veuillet [http://www.wakdev.com]
* @copyright	Copyright(C) 2010 - Today. All rights reserved.
* @license		GNU/GPL
*/

/**
* @file
* @name sla_lang
* @defgroup sla_lang sla_lang
* Administration modules language
* @{
*/

// Include Default Module View
include ("views/default/view.php");

// Include models
include ("models/lang.php");


class sla_lang_controller extends slaController implements iController {

	
	public $module_name = "sla_lang";
	
	
	public $view;
	public $lang;
		
	
	/**
	* Contructor
	
	*/
	function sla_construct() {
      
	   $this->lang = new lang($this);
	   $this->view = new sla_lang_view($this);
	   
	   $this->load_params(); //Load params
	}
	
	
	
	/**
	 * Load header function
	 */
	public function load_header(){
		$this->view->header(); //Global header
		sl_interface::listing_sessions($this->module_name,array('name','shortname','enabled')); // session for listing
		switch ($this->mode) {
			case "show":
				$this->view->l_header(); //List header
			break;
			case "add":
			case "edit":
				$this->view->f_header(); //Form header
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
		$this->view->footer();
	}
	
	/**
	* Load module function
	* Require function by slash core
	*/
	public function load() {
		switch ($this->mode) {
			case "add": //Add article
				$this->view->show_form(0,$this->datas,0);
			break;
			case "delete"://Delete article
				$this->view->show_delete($this->slash->sl_param($this->module_name."_checked","POST"));
			break;
			case "show": //List view
				$this->view->show_items($this->message);
			break;
			default: // default view
				$this->view->show_items($this->message);
		}
	}
	
	
	
	
	
	/* ---------------- */
	/* MODULE FUNCTIONS */
	/* ---------------- */
	public function load_params() {
	
		switch ($this->slash->sl_param($this->module_name."_act","POST")) {
			case "add": //Add
				$this->datas = $this->lang->load_disabled_items();
				$this->mode = "add";
				
			break;
			case "save": //Save
			
				$this->datas = $this->lang->recovery_fields();
				$this->errors = $this->lang->check_fields($this->datas);
				
				if ($this->errors != null) {
					$this->mode = "edit";
				}else{
					$this->lang->save_item($this->datas["id"],$this->datas);
					$this->message = $this->slash->trad_word("SAVE_SUCCESS");
					$this->mode = "show";
				}
			
			break;
			case "delete"://Delete
				if ($this->slash->sl_param($this->module_name."_valid","POST")) {
					$this->lang->delete_items($this->slash->sl_param($this->module_name."_checked","POST"));
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
			default: // default view
				$this->mode = "show";
		}
	}
}

/** 
* @} 
*/
	
?>