 <?php
/**
* @package		SLASH-CMS
* @subpackage	sl_menu
* @internal     Menu module
* @version		sl_menu.php - Version 9.6.2
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
* @name sl_menu
* @defgroup sl_menu sl_menu
* Front modules : Menu
* @{
*/

// Include views
include ("views/default/view.php");
// Include models
include ("models/menus.php");

class sl_menu_controller extends slController implements iController{

	
	public $menus;
	public $view;
	
	public $is_home = false;
	
	/**
	* Contructor
	* @param core_class_ref Core class reference
	*/
	function sl_construct() {

		$this->menus = new menus($this);
		$this->view = new sl_menu_view($this);
	   
	}
	
	
	
	/**
	* Initialize function
	*/
	public function initialize() {
		
		$this->menus->check_homepage();
		
		$this->view->header(); //show script header
		
		
	}
	
	/**
	* Load function
	*/
	public function load() {
		
		$this->view->start_main_menu();
		$this->menus->load_menu(0);
		$this->view->end_main_menu();
		
	}
	
	
	
	
	/**
	* Execute function
	*/
	public function execute() {
		$this->view->execute_menu();
	}

	

}

/** 
* @} 
*/

?>
