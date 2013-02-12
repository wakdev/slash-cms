 <?php
/**
* @package		SLASH-CMS
* @subpackage	sla_admmenu
* @internal     Admin menu module
* @version		sla_admmenu.php - Version 9.12.16
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
* @name sla_admmenu
* @defgroup sla_admmenu sla_admmenu
* Display module admin menu
* @{
*/
include ("models/admmenu.php");
include ("views/wd-admin/view.php");

class sla_admmenu_controller extends slaController implements iController{

	public $view;
	public $admmenu;
	
	/**
	* Contructor
	*/
	function sla_construct() {
	   $this->admmenu = new admmenu($this);
	   $this->view = new sla_admmenu_view($this);
	}
	
	
	
	/**
	* Initialize function # require by slash-cms #
	*/
	public function initialize() {
		 $this->view->header();
	}
	
	/**
	* Load function # require by slash-cms #
	*/
	public function load() {
		
		$this->view->start_main_menu();
		$this->admmenu->load_menu(0);
		$this->view->end_main_menu();
		
	}
	
	
	/**
	 * User admin name
	 */
	public function get_admin_username (){
		$row_user = $this->slash->get_admin_infos();
		return $row_user["name"];
	}
	
	
	/**
	* Execute function # require by slash-cms #
	*/
	public function execute() {
		$this->view->execute_menu();
	}

	
	

}

/** 
* @} 
*/

?>
