<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_article
* @internal     articles module
* @version		controller.php - Version 12.5.30
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
* @name sl_articles
* @defgroup sl_articles sl_articles
* Front modules : Articles
* @{
*/


class sl_articles_controller extends slController implements iController{

	
	public $module_name = "sl_articles";
	
	
	public $articles; //model
	public $view; //view
	
	
	public function sl_construct() {
	   
	   $this->load_models();
	   $this->load_views();
	  
	   $this->articles = new articles($this);
	   $this->view = new sl_articles_view($this);
	   
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
		include ("models/articles.php");
	}
	
	/**
	 * Load header function
	 */
	public function load_header(){
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
		
		$row_article = $this->articles->load_article(intval($this->slash->sl_param("id","GET")));
		if (isset($row_article["title"])){
			$this->view->show_article($row_article);
		}else{
			$this->view->show_404();
		}
	}
	
	
	
}

/** 
* @} 
*/

?>
