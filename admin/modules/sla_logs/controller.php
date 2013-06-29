 <?php
/**
* @package		SLASH-CMS
* @subpackage	sla_logs
* @internal     Admin logs module
* @version		controller.php
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

*/

/**
* @file
* @name sla_logs
* @defgroup sla_logs sla_logs
* Administration modules : Logs
* @{
*/


// Include Models and Views
include ("models/logs.php");
include ("views/default/view.php");

class sla_logs_controller extends slaController implements iController{

	public $logs;
	public $view;
	
	/**
	* Custom Constructor
	*/
	public function sla_construct () {
	
	   $this->logs = new logs($this);
	   $this->view = new sla_logs_view($this);
	   
	   $this->load_params(); //Load params
	}
	
	/**
	 * Load header function
	 */
	public function load_header(){
		$this->view->header(); //Global header
		sl_interface::listing_sessions($this->module_name,array('log_date'),"desc"); // session for listing
		
		switch ($this->mode) {
			case "show":	
				$this->view->l_header(); //List header
			break;
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
			case "edit": //Edit
				$this->view->show_form($this->datas["id"],$this->datas,$this->errors);	
			break;
			case "delete"://Delete
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
			case "edit": //Edit
				$values = $this->slash->sl_param($this->module_name."_checked","POST");
				if (isset ($values) && count($values) > 0) {
					reset($values);
					$this->datas = $this->logs->load_item(current($values));
					$this->mode = "edit";
				}else{
					$this->mode = "show";
					$this->message = $this->slash->trad_word("SELECTION_REQUIRE");
				}
			break;			
			case "delete"://Delete
				if ($this->slash->sl_param($this->module_name."_valid","POST")) {
					$this->logs->delete_items();
					$this->mode = "show";
					$this->message = $this->slash->trad_word("DELETE_SUCCESS"); 
				}else {
					$this->mode = "delete";
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
