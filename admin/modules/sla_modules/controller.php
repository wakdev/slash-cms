 <?php
/**
* @package		SLASH-CMS
* @subpackage	sla_modules
* @internal     	Admin categories module
* @version		sla_modules.php - Version 10.1.5
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

/**
* @file
* @name sla_modules
* @defgroup sla_modules sla_modules
* Administration : Modules
* @{
*/




// Include Default Module View
include ("views/default/view.php");

// Include Models
include ("models/modules.php");

class sla_modules_controller extends slaController implements iController{

	public $modules;
	public $view;
	
	
	/**
	* Contructor
	* @param core_class_ref Core class reference
	*/
	function sla_construct() {
		$this->modules = new modules($this);
		$this->view = new sla_modules_view($this);
		
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
		//SESSION
		sl_interface::listing_sessions($this->module_name,array('name','type','url'));
		$this->view->header(); //show script header
	
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
				$this->view->show_form();
			break;
			case "edit": //Edit article
				$this->view->show_form($this->datas["id"],$this->datas,$this->errors);	
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
	
	private function load_params() {
	
		switch ($this->slash->sl_param($this->module_name."_act","POST")) {
			case "add": //Add 
				$this->mode = "add";
			break;
			case "edit": //Edit
				$values = $this->slash->sl_param($this->module_name."_checked","POST");
				if (isset ($values) && count($values) > 0) {
					reset($values);
					$this->datas = $this->modules->load_item(current($values));
					$this->mode = "edit";
				}else{
					$this->mode = "show";
					$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
				}
			break;
			case "save": //Save
			
				$this->datas = $this->modules->recovery_fields();
				$this->errors = $this->modules->check_fields($this->datas);
				
				if ($this->errors != null) {
					$this->mode = "edit";
				}else{
					$this->message = $this->modules->save_item($this->datas["id"],$this->datas);
					$this->mode = "show";
				}
			
			break;
						
			case "delete"://Delete
				if ($this->slash->sl_param($this->module_name."_valid","POST")) {
					$this->modules->delete_items($this->slash->sl_param($this->module_name."_checked","POST"));
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
					$this->modules->set_items_enabled($this->slash->sl_param($this->module_name."_checked","POST"),1);
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
					$this->modules->set_items_enabled($this->slash->sl_param($this->module_name."_checked","POST"),0);
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
	
	
	/**
	* Execute function
	* Require function by slash core
	*/
	public function execute() {
		
	}
	
	
	
	

}

/** 
* @} 
*/

?>
