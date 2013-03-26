<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_pages
* @internal     pages module
* @version		controller.php - Version 11.5.30
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
* @name sl_pages
* @defgroup sl_pages sl_pages
* Front modules : Pages
* @{
*/

class sl_pages_controller extends slController implements iController{

	
	public $module_name = "sl_pages";
	
	
	public $pages; //model
	public $view; //view
	
	public $data;
	
	public function sl_construct() {
	   
	   $this->load_models();
	   $this->load_views();
	  
	   $this->pages = new pages($this);
	   $this->view = new sl_pages_view($this);
	   
	   $this->data = $this->pages->load_page(intval($this->slash->sl_param("id","GET")));
		
	   
	}
	
	/**
	* Load Views
	*/
	private function load_views(){
		if ($this->slash->mobile == true && $this->slash->mobile->isMobile() == true) {
			include ("views/mobile/view.php");
		}else{
			include ("views/default/view.php");
		}
	}
	
	/**
	* Load Models
	*/
	private function load_models() {
		include ("models/pages.php");
	}
	
	/**
	 * Load header function
	 */
	public function load_header(){
		
		if (isset($this->data["title"])){
			$this->view->header($this->data["title"]);
		}else{
			$this->view->header($this->slash->config["site_name"]);
		}
	
		
	}
	
	
	/**
	 * Load footer function
	 */
	public function load_footer(){
		$this->view->footer_page();
	}
	
	/**
	* Load module function
	* Require function by slash core
	*/
	public function load() {
		
		
		if (isset($this->data["title"])){
			$this->view->show_page($this->data);
		}else{
			$this->view->show_404();
		}
	}
	
	
	
}

/** 
* @} 
*/


?>
