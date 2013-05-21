 <?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_ARTICLES
* @internal     Admin articles module
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

* @defgroup sla_articles sla_articles
* Administration modules articles
* @{


*/

// Include Default Module View
include ("views/default/view.php");

// Include models
include ("models/articles.php");
include ("models/categories.php");

class sla_articles_controller extends slaController implements iController{

	
	public $articles;
	public $categories;
	public $view;
	
	
	
	/**
	* Contructor
	*/
	function sla_construct() {
       
	   $this->articles = new articles($this);
	   $this->categories = new categories($this);
	   $this->view = new sla_articles_view($this);
	   
	   $this->load_params(); //Load params
	}

	
	/**
	 * Load header function
	 */
	public function load_header(){

		$this->view->header(); //Global header
		sl_interface::listing_sessions($this->module_name,array('title','content','date')); // session for listing
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
					$this->datas = $this->articles->load_item(current($values));
					$this->mode = "edit";
				}else{
					$this->mode = "show";
					$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
				}
			break;
			case "save": //Save
			
				$this->datas = $this->articles->recovery_fields();
				$this->errors = $this->articles->check_fields($this->datas);
				
				if ($this->errors != null) {
					$this->message = $this->slash->trad_word("ERROR_FIELD_CHECK");
					$this->mode = "edit";
				}else{
					$this->message = $this->articles->save_item($this->datas["id"],$this->datas);
					
					//Log action
					$log_info = "";
					if ($this->datas["id"] == 0) {$log_info .= "add article";}else{$log_info .= "edit article [id=".$this->datas["id"]."].";}
					$this->slash->log($log_info,$this->module_name);
					
					$this->mode = "show";
				}
			
			break;
						
			case "delete"://Delete
				if ($this->slash->sl_param($this->module_name."_valid","POST")) {
					
					$id_array = $this->slash->sl_param($this->module_name."_checked","POST");
					$this->articles->delete_items($id_array);

					//Log action
					$ids = "";
					foreach ($id_array as $value) {$ids .= $value.",";}
					$ids = substr($ids,0,-1);
					$log_info = "delete article [id=".$ids."].";
					$this->slash->log($log_info,$this->module_name);
					
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
					$id_array = $this->slash->sl_param($this->module_name."_checked","POST");
					$this->articles->set_items_enabled($id_array,1);
					
					//Log action
					$ids = "";
					foreach ($id_array as $value) {$ids .= $value.",";}
					$ids = substr($ids,0,-1);
					$log_info = "enable article [id=".$ids."].";
					$this->slash->log($log_info,$this->module_name);
					
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
					$id_array = $this->slash->sl_param($this->module_name."_checked","POST");
					$this->articles->set_items_enabled($id_array,0);
					
					//Log action
					$ids = "";
					foreach ($id_array as $value) {$ids .= $value.",";}
					$ids = substr($ids,0,-1);
					$log_info = "disable article [id=".$ids."].";
					$this->slash->log($log_info,$this->module_name);
					
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
	
	

}

/** 
* @} 
*/

?>
