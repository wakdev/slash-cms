<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_medias
* @internal     Admin Panel module
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
* @name sla_medias
* @defgroup sla_medias sla_medias
* Administration modules : Panel
* @{
*/

include ("views/default/view.php");

class sla_medias_controller extends slaController implements iController{
	
	public $view;
	
	/**
	* Contructor
	
	*/
	function sla_construct() {
		$this->view = new sla_medias_view($this);
	}
	

	
	
	/**
	 * Load header function # require by slash-cms #
	 */
	public function load_header(){
		$this->view->header();
	}
	
	/**
	 * Load footer function # require by slash-cms #
	 */
	public function load_footer(){
		$this->view->footer();
	}
	
	/**
	 * User admin name
	 */
	public function get_admin_username (){
		$row_user = $this->slash->get_admin_infos();
		return $row_user["name"];
	}
	
	/**
	* Load function # require by slash-cms #
	*/
	public function load() {
		$this->view->show();
	}

	


}

/** 
* @} 
*/

?>
