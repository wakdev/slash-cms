<?php
/**
* @package		SLASH-CMS
* @subpackage	CORE
* @internal     Slash core system
* @version		slash.php - Version 12.3.1
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
* @name slash
* @defgroup slash core
* Slash Core
* @{
*/

//Global includes
include ("common/constants/sl_constants.php"); //Defines
include ("config/sl_config.php"); // configuration file.
include ("languages/sl_lang.php"); // System Language

class Slash {

/**
* --------------------
* - CLASS PROPERTIES -
* --------------------
*/

	public $config = array(); //Configuration in database
	public $get_params = array(); //Get param
	public $post_params = array(); //Post param
	public $db_handle; //Slash Database Handle
	
	public $database; //Slash Database
	
	private $properties = array(); //Properties in file sl_config.php
	private $modules = array(); //module Array
	private $request_module; //Request module
	private $mode; //Mode (admin or site)
	public $mobile; //mobile dectection class (Platforms, etc..)
	
/**
* ---------------
* - OVERLOADING -
* ---------------
*/
	
	/**
	* Constructor
	*/
	function __construct(){
		
	}
	
	/**
	* Overloading for Slash properties (configuration.php)
	*/
	public function __get($name) {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
		
		//@todo : else ?
	}
	
	
/**
* -------------------
* - PRIVATE METHODS -
* -------------------
*/

	/**
	* Core front initialisation
	*/
	private function initialize() {
		
		session_start();
		
		$this->load_properties(); // load properties configuration
		$this->load_db_connector();
		$this->load_common(); // load interfaces and class
		
		//database connection
		$this->connect();
		$this->load_config(); // load configuration
		$this->load_language(); // load language (core/languages/LANGUAGE SELECTED/)
		$this->load_params(); // load get and post params (load all get and post params)
		$this->mobile_detection();// mobile platforms detection
		$this->load_template($this->config["site_template_url"]."template.php"); // load front template
		$this->disconnect();
	}

	/**
	* Core backoffice initialisation
	*/
	private function initialize_admin() {
		
		session_start();
		
		$this->load_properties(); // load properties configuration
		$this->load_db_connector();
		$this->load_common(); // load interfaces and class
		
		//database connection
		$this->connect();
		$this->load_config(); // load configuration
		$this->load_language(); // load language (core/languages/LANGUAGE SELECTED/)
		$this->load_params(); // load get and post params (load all get and post params)
		
		$this->load_template($this->config["admin_template_url"]."template.php"); // load admin template
		$this->disconnect();
	}

	/**
	* MySql Database connexion function
	* @param $host host server
	* @param $database database name
	* @param $user MySql user
	* @param $password MySql password
	*/
	private function connect () {

		$ret = $this->database->connect($this->db_host,$this->db_name,$this->db_user,$this->db_password,$this->db_prefix);
		if (!$ret) {
			$this->show_fatal_error("SELECT_DB_ERROR",$this->database->getError());
		}
		
		//retro-compatibility
		$this->db_handle = $this->database->getHandle();
	}

	/**
	* MySql Database disconnexion function
	*/
	private function disconnect () {
		$this->database->disconnect();
	}

	
	/**
	* Loading Slash properties in SLConfig class
	*/
	private function load_properties() {
		$sl_config = new SLConfig();
		$class_vars = get_class_vars(get_class($sl_config));
		
		foreach ($class_vars as $name => $value) {
			$this->properties[$name] = $value;
		}	
		
		//Retro-compatibility
		$this->properties["database_prefix"] = $sl_config->db_prefix;
	}
	
	/**
	* Loading Database connector
	*/
	private function load_db_connector() {
		if (isset($this->properties["db_type"]) && $this->properties["db_type"] != "") {
		
			// MySQL
			if ($this->properties["db_type"] == "MySQL") {
				include ("common/class/db/mysql/connector.php");
				$this->database = MySQLConnector::getInstance();
			}
			
			// PDO
			if ($this->properties["db_type"] == "PDO") {
				include ("common/class/db/pdo/connector.php");
				$this->database = PDOConnector::getInstance();
			}
			
		}
	}
	
	/**
	* Loading Slash configuration
	*/
	private function load_config() {
		
		$this->database->setQuery("SELECT * FROM ".$this->db_prefix."config");
		if (!$this->database->execute()) {
			$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
		}
	
		foreach ($this->database->fetchAll("ASSOC") as $value) {
			 $this->config[$value["config_name"]] = $value["config_value"];
		}
		
		unset($value);
	
	}
	
	


	/**
	* Loading Slash language
	*/
	private function load_language() {
		if (isset($_SESSION["user_language"])) {
			$url_language = "languages/".$_SESSION["user_language"];
		}else{
			$url_language = "languages/".$this->config["slash_language"];
		}
		
		if ($this->mode == "admin" && file_exists("../core/".$url_language."/interface.php") ) { 
			include ($url_language."/interface.php"); //include interface traduction
		}
		
		if ($this->mode == "site" && file_exists("core/".$url_language."/interface.php") ) { 
			include ($url_language."/interface.php"); //include interface traduction

		}
	}

	/**
	* Is a mobile platforms ?
	*/
	private function mobile_detection() {
		if ($this->config["mobile_detection"] == "true" && $this->sl_param("desktop") != 1) {
			$this->mobile = new sl_mobile();
			if ($this->mobile->isMobile() == true || $this->sl_param("mobile") == 1) {
				$this->config["site_template_url"] = $this->config["mobile_template_url"];
			}
		}else{
			$this->mobile = false;
		}
	}
	
	/**
	* Load template
	* @param $url url_template
	*/
	private function load_template($url=null) {
		if ($url!==null && file_exists($url)){
		    include ($url);
		}else{
		    $this->show_fatal_error("UNKNOWN_TEMPLATE_ERROR","No such template '$url'");
		}
	}



	/**
	* Load GET and POST params
	*/
	private function load_params() {
		foreach ($_GET as $get => $val){$this->get_params[$get] = $val;}   
		foreach ($_POST as $post => $val){$this->post_params[$post] = $val;}   
	}
	
	
	/**
	* Load module traduction
	*/
	private function load_module_language($module_url=null) {
	
		if ($module_url!== null && $this->mode == "admin") {
		
			if (isset($_SESSION["user_language"])) {
				$url = $module_url."languages/".$_SESSION["user_language"].".php";
			}else{
				$url = $module_url."languages/".$this->config["slash_language"].".php";
			}
			
			if (file_exists($url)){
				include ($url);
			}
		}
		
		/* @todo : Front mutli-language */
		if ($module_url!== null && $this->mode == "site") { 
		
			if (isset($_SESSION["user_language"])) {
				$url = $module_url."languages/".$_SESSION["user_language"].".php";
			}else{
				$url = $module_url."languages/".$this->config["slash_language"].".php";
			}
			
			if (file_exists($url)){
				include ($url);
			}
			
		}
	}
	
	/**
	* Load Module interface
	*/
	private function load_common(){
		$this->load_implements();
		$this->load_class();
	}
	
	/**
	* Load Module interface
	*/
	private function load_implements(){
	
		include ("common/implements/modules/imodel.php");
		include ("common/implements/modules/iview.php");
		include ("common/implements/modules/icontroller.php");
	
		if ($this->mode == "site") { 
			//nothing
		}
		
		if ($this->mode == "admin") { 
			//nothing
		}
	}
	
	/**
	* Load Module 
	*/
	private function load_class(){
		
		include ("common/class/functions/sl_functions.php"); // load functions
		include ("common/class/interfaces/sl_interfaces.php"); // load interfaces
		
		if ($this->mode == "site") { 
			include ("common/class/modules/sl_model.php"); // load abstract class
			include ("common/class/modules/sl_view.php"); // load abstract class
			include ("common/class/modules/sl_controller.php"); // load abstract class
		}
		
		if ($this->mode == "admin") { 
			include ("common/class/modules/sla_model.php"); // load abstract class
			include ("common/class/modules/sla_view.php"); // load abstract class
			include ("common/class/modules/sla_controller.php"); // load abstract class
			
		}
		
		
	}

/*
* ------------------
* - PUBLIC METHODS -
* ------------------
*/
	
	/**
	* Show Front-Office
	*/
	public function show() {
		$this->mode = "site";
		$this->initialize();
	}

	/**
	* Show Admin - Back-Office
	*/
	public function show_admin () {
		$this->mode = "admin";
		$this->initialize_admin ();
	}
	
	
	/**
	* Show fatal errors and quit
	* @param $message error message
	* @param $code technical message error
	*/
	public function show_fatal_error ($message=null,$code=null) {
		
		if ($this->error_level > 0) {
			echo "<br /><table style='border: 1px solid #FF0000;' align='center'><tr><td>";
			echo "<font color='#FF0000' size='2'>".constant($message)." - ERROR CODE : ".$code."</font>";
			echo "</td></tr></table>";
		}
		
		exit;

	}
	
	/**
	 * Log action
	 * @param string $log_info information
	 * @param string $log_title title
	 * @param string $log_type type
	 * @param boolean $clear Clear old logs ?
	 */
	public function log($log_info,$log_title=null,$log_type=SL_LOG_TYPE_INFO,$clear=true){
		
		if (isset($this->properties["logs"]) && $this->properties["logs"] == true) {
		
			$id_user = 0;
			$log_url = $_SERVER["REQUEST_URI"];
			$log_referer = $_SERVER["HTTP_REFERER"];
			$log_date = date("Y-m-d H:i:s",time());
			
			if (isset($_SESSION["id_user"])) {$id_user=$_SESSION["id_user"];}
			if (!isset($log_info) || $log_info=="") {$log_info="none";}
			if ($log_title!== null){$log_info = $log_title." : ".$log_info; }
			
			if ($clear) {$this->clear_log();}
			
			$this->database->setQuery("
						INSERT INTO ".$this->db_prefix."logs
						(id,log_type,log_url,log_referer,log_info,id_user,log_date) value
						('','".$log_type."','".$log_url."','".$log_referer."',\"".$log_info."\",'".$id_user."','".$log_date."')");
			if (!$this->database->execute()) {
				$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
			}
		
		}
	
	}
	
	/**
	 * Clear Log : see config $logs_rotation
	 */
	public function clear_log(){
		
		if (isset($this->properties["logs_rotation"])) {
			$log_rot = "-1 week";
			switch ($this->properties["logs_rotation"]){
				case "month":
					$log_rot = "-1 month";
					break;
				case "week":
					$log_rot = "-1 week";
					break;
				case "day":
					$log_rot = "-1 day";
					break;
				case "hour":
					$log_rot = "-1 hour";
					break;
			}
			
			$expired_date = date('Y-m-d H:i:s', strtotime($log_rot));
			
			$this->database->setQuery("SELECT * FROM ".$this->database_prefix."logs WHERE log_date<'".$expired_date."'");
			if (!$this->database->execute()) {
				$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
			}
			
			$n_expired = $this->database->rowCount();
			
			if( $n_expired  > 0 ) {
				$this->database->setQuery("DELETE FROM ".$this->db_prefix."logs WHERE log_date<'".$expired_date."'");
				if (!$this->database->execute()) {
					$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
				}else{
					$this->log($n_expired." logs clear","SYSTEM",SL_LOG_TYPE_SYSTEM,false);
				}
			}
			
		}
	}
	
	/**
	 * User admin infos
	 * @return $row_user:Array Return User datas
	 */
	public function get_admin_infos (){
	    if ($this->mode == "admin" && $_SESSION["id_user"] != null) {   
			
			$this->database->setQuery("SELECT * FROM ".$this->db_prefix."users WHERE id=".$_SESSION["id_user"]);
			if (!$this->database->execute()) {
				$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
			}
			
			return $this->database->fetch("ASSOC");
			
	    } else { return null; }
	}
	
	/**
	 * Active lang infos
	 * @return $row_lang:Array Return lang datas
	 */
	public function get_active_lang (){ 
		
		$this->database->setQuery("SELECT * FROM ".$this->db_prefix."lang WHERE enabled=1 ORDER BY id");
		if (!$this->database->execute()) {
			$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
		}
	
		return $this->database->fetchAll("ASSOC");
	
	}
	
	
	/**
	* Word traduction
	* @return $word:string Word traduction
	*/
	public function trad_word($message=null) {
		if ($message!== null && defined($message)) {
			return constant($message); 
		}else{
			return "- NO TRANSLATE -";
		}
	}
	
	
	
	/**
	* Return GET or POST param
	* @param $name nome of param
	* @param $method POST, GET, or both if not defined
	*/
	public function sl_param($name,$method=null) {
		$value=null;
		if ($method) {
			if ($method == "POST" && array_key_exists($name, $this->post_params)) {$value = $this->post_params[$name];}
			if ($method == "GET" && array_key_exists($name, $this->get_params)) {$value = $this->get_params[$name];}
		} else {
			if (array_key_exists($name, $this->get_params)) {$value = $this->get_params[$name];}
			if (array_key_exists($name, $this->post_params)) {$value = $this->post_params[$name];}
		}
		return $value;
	}
	
	
	/**
	* Return slash config value of a param
	* @return value of configuration
	*/
	public function sl_config($name) {
		return $this->config[$name];
	}
	
	
	/**
	* Return id module
	*/
	public function sl_module_id($name,$type=0) {
	
		if ($type == 0) { 
			
			$this->database->setQuery("SELECT * FROM ".$this->db_prefix."modules WHERE name='".$name."'");
			if (!$this->database->execute()) {
				$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
			}
			
		}else{
			
			$this->database->setQuery("SELECT * FROM ".$this->db_prefix."modules WHERE name='".$name."' AND type='".$type."'");
			if (!$this->database->execute()) {
				$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
			}
			
		}
		
		if ($this->database->rowCount() > 0) {
			$row_module = $this->database->fetch("ASSOC");	
			return $row_module["id"];
		}else{
			return false;
		}
	}
	
	/**
	* Modules initialize function
	* @todo Reste le chargement puis l'intégration des paramètres des modules
	*/
	public function initialize_modules() {
		
		$this->database->setQuery("SELECT *, IF(initialize_order = 0, '1', '0') AS prio FROM ".$this->db_prefix."modules WHERE enabled=1 AND type='".$this->mode."' ORDER by prio ASC, initialize_order ASC");
		if (!$this->database->execute()) {
			$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
		}
		
		foreach ($this->database->fetchAll("ASSOC") as $row) {
			if ($row["initialize_order"] != "0") { //Global module
				$this->load_module_language($row["url"]);
				
				if (file_exists($row["url"].$row["name"].".php")){
					$module_url = $row["url"].$row["name"].".php";
					$module_class = $row["name"];
				}elseif(file_exists($row["url"]."controller.php")){
					$module_url = $row["url"]."controller.php";
					$module_class = $row["name"]."_controller";
				}else{
					$this->show_fatal_error("UNKNOWN_MODULE_ERROR","No such module '".$row["name"]."'");
				}
				
				include ($module_url);
				$this->modules[$row["name"]] = new $module_class($this,$row["id"],$row["params"]);
				$this->modules[$row["id"]] = $this->modules[$row["name"]];
				$this->modules[$row["name"]]->initialize();

			}else{ //Current module
				if ($this->sl_param("mod") && $this->sl_param("mod")==$row["name"] ) {
					$this->load_module_language($row["url"]);
					
					if (file_exists($row["url"].$row["name"].".php")){
						$module_url = $row["url"].$row["name"].".php";
						$module_class = $row["name"];
					}elseif(file_exists($row["url"]."controller.php")){
						$module_url = $row["url"]."controller.php";
						$module_class = $row["name"]."_controller";
					}else{
						$this->show_fatal_error("UNKNOWN_MODULE_ERROR","No such module '".$row["name"]."'");
					}
					
					include ($module_url);
					$this->modules[$row["name"]] = new $module_class($this,$row["id"],$row["params"]);
					$this->modules[$row["id"]] = $this->modules[$row["name"]];

				}
			}
		}
		
		if ($this->sl_param("mod")) {
			$name = $this->sl_param("mod");
			if (array_key_exists($name, $this->modules)) {
				$this->modules[$name]->load_header();	
			} else {
				$this->show_fatal_error("UNKNOWN_MODULE_ERROR","No such module '".$name."'");
			}
		}
		
	}
	
	/**
	* Module execution
	*/
	public function execute_modules() {
		
		if ($this->sl_param("mod")) {
			$name = $this->sl_param("mod");
			if (array_key_exists($name, $this->modules)) {
				$this->modules[$name]->load_footer();
			} else {
				$this->show_fatal_error("UNKNOWN_MODULE_ERROR","No such module '".$name."'");
			}
		}
		
		//Global module
		$this->database->setQuery("SELECT * FROM ".$this->db_prefix."modules WHERE enabled=1 AND type='".$this->mode."' AND initialize_order > 0 ORDER by initialize_order ASC");
		if (!$this->database->execute()) {
			$this->show_fatal_error("QUERY_ERROR",$this->database->getError());
		}

		foreach ($this->database->fetchAll("ASSOC") as $value) {
			 $this->modules[$value["name"]]->execute();
		}

		unset($value);
		
	}
	
	/**
	* Include module
	* @param $name module name
	* @param $params params
	*/
	public function load_module($name,$params=null) {
		
		if (array_key_exists($name, $this->modules)) {
			if ($this->mode == "admin") {
				if (isset ($_SESSION["id_user"]) && $_SESSION["id_user"] != null || $name == "sla_secure") {
					$this->modules[$name]->params = $params;
					$this->modules[$name]->load();
				}
			} else {
				$this->modules[$name]->params = $params;
				$this->modules[$name]->load();
			}
		} else {
			$this->show_fatal_error("UNKNOWN_MODULE_ERROR","No such module '".$name."'");
		}
		
	}


}

/** 
* @} 
*/

?>
