 <?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_CONFIG
* @internal     Admin config module
* @version		controller.php - Version 11.3.25
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

* @defgroup sla_config sla_config
* Administration modules config
* @{


*/

// Include Default Module View
include ("views/default/view.php");

// Include models
include ("models/config.php");


class sla_config_controller extends slaController implements iController{

	
	public $module_name = "sla_config";
	
	public $config;
	public $view;
	
	
	
	/**
	* Contructor
	*/
	function sla_construct() {
       
	   $this->config = new config($this);
	   $this->view = new sla_config_view($this);
	   
	   $this->load_params(); //Load params
	}

	
	/**
	 * Load header function
	 */
	public function load_header(){
		$this->view->header(); //Global header
		sl_interface::listing_sessions($this->module_name,array('config_name')); // session for listing
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
		switch ($this->mode) {
			case "show":
				//nothing
			break;
			case "add":
			case "edit":
				$this->view->f_footer(); //Form header
			break;
			case "delete":
				//nothing
			break;
			default:
				//nothing
		}
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
					$this->datas = $this->config->load_item(current($values));
					$this->mode = "edit";
				}else{
					$this->mode = "show";
					$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
				}
			break;
			case "save": //Save
			
				$this->datas = $this->config->recovery_fields();
				$this->errors = $this->config->check_fields($this->datas);
				
				if ($this->errors != null) {
					$this->mode = "edit";
				}else{
					$this->message = $this->config->save_item($this->datas["id"],$this->datas);
					$this->mode = "show";
				}
			
			break;
						
			case "delete"://Delete
				if ($this->slash->sl_param($this->module_name."_valid","POST")) {
					$this->config->delete_items($this->slash->sl_param($this->module_name."_checked","POST"));
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
