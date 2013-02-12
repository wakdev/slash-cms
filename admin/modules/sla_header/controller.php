<?php
/**
* @package		SLASH-CMS
* @subpackage	SLA_HEADER
* @internal     Admin header module
* @version		sla_header.php - Version 11.5.31
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
* @name sla_header
* @defgroup sla_header sla_header
* Administration header
* @{
*/


include ("views/default/view.php");

class sla_header_controller extends slaController implements iController{

	
	public $view;
	
	/**
	* Contructor
	* @param core_class_ref Core class reference
	*/
	function sla_construct() {
      
	   $this->view = new sla_header_view($this);
	}
	
	
	
	/**
	* Initialize function # require by slash-cms #
	*/
	public function initialize() {
		
		//echo $this->slash->sl_config("global_keywords");
		$this->view->scripts();
	
	}
	

	
	/**
	* Load function # require by slash-cms #
	*/
	public function load() {
		$this->view->title();
		$this->view->metas();
	}
	
	

}

/** 
* @} 
*/



?>