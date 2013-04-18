<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_secure
* @internal     Admin Login module
* @version		controller.php - Version 9.12.16
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
* @name sla_secure
* @defgroup sla_secure sla_secure
* Administration modules : Secure
* @{
*/


// Include Module View
include ("views/default/view.php");

class sla_secure_controller extends slaController implements iController{

	public $view;
	
	/**
	* Contructor
	*/
	function sla_construct() {
       
	   $this->view = new sla_secure_view($this);
	}
	
	
	/**
	* Initialize function # Require by slash-cms #
	*/
	public function initialize() {
		
		$this->error = "no_error";
		
		if (isset($this->slash->get_params["mod"]) 
		&& $this->slash->get_params["mod"] == "sla_secure" ) {
			
			// Login script
			if ($this->slash->get_params["act"] == "login") {
				
				$this->error = "no_user";
				
				$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."users");
				if (!$this->slash->database->execute()) {
					$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
				}
				
				foreach ($this->slash->database->fetchAll("BOTH") as $row) {
					
					if ($row["login"] == $this->slash->post_params["sla_secure_login"]  && $row["password"] == sha1($this->slash->post_params["sla_secure_password"]) ) {
						if ($row["enabled"]!= 0) {
							$_SESSION["id_user"] = $row["id"];
							
							//Finder Configuration
							$_SESSION['user_finder']['disabled'] = false;
							$_SESSION['user_finder']['_sessionPath'] = $_SERVER['HTTP_HOST'].$this->slash->site_path."medias";
							$_SESSION['user_finder']['uploadURL'] = $this->slash->site_path."medias";
							
							$_SESSION["user_language"] = $row["language"];
							$this->slash->get_params["mod"] = "sla_panel";
							
							//Log connexion
							$log_info = "user [".$row["login"]."] [".$_SERVER["REMOTE_ADDR"]."] connected.";
							$this->slash->log($log_info,$this->module_name);
							
						}else{
							$this->error = "inactive_user";
						}
					}
				}
			
			}
			
			
			// Logout script
			if ($this->slash->get_params["act"] == "logout") {
				
				//Log connexion
				$log_info = "[".$_SERVER["REMOTE_ADDR"]."] disconnected.";
				$this->slash->log($log_info,$this->module_name);

				$_SESSION = array();
				/*
				if (isset($_COOKIE[session_name()])) {
				    setcookie(session_name(), '', time()-42000, '/');
				}*/
		
				session_destroy();

				$this->slash->get_params["mod"] = "sla_secure";
				
			}
			
		} 
		
		if (isset($_SESSION["id_user"]) && $_SESSION["id_user"] != null) {
		
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."users WHERE id=".$_SESSION["id_user"]);
			
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			
			if($this->slash->database->rowCount()  == 0 ) {
				$this->slash->get_params["mod"] = "sla_secure";
			}else{
				$row = $this->slash->database->fetch("ASSOC");
				$_SESSION["user_language"] = $row["language"];
			}
		
		} else {
			$this->slash->get_params["mod"] = "sla_secure";			
		}
		
	
	}
	
	/**
	 * Load Header function # Require by slash-cms #
	 */
	public function load_header(){
		$this->view->header();
	}
	
	/**
	* Load function # Require by slash-cms #
	*/
	public function load() {
	
		
		switch ($this->error) {
			
			case "no_error" :
				$this->view->login_form();
			break;
			
			case "no_user" :
				$this->view->show_error("SECURE_CONNEXION_NO_USER");
				$this->view->login_form();
			break;
			
			case "inactive_user" :
				$this->view->show_error("SECURE_CONNEXION_INACTIVE_USER");
				$this->view->login_form();
			break;
			
			default:
				$this->view->show_error("SECURE_CONNEXION_ERROR");
				$this->view->login_form();
			
		}
		
	}
	
	/**
	 * Load footer function # Require by slash-cms #
	 */
	public function load_footer(){
		$this->view->footer();
	}
	
	

}

/** 
* @} 
*/

?>