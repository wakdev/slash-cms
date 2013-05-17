<?php
/**
* @package		SLASH-CMS
* @subpackage	SLSETUP
* @internal     Slash core system
* @version		SlSetup.php - Version 13.05.03
* @author		Loic Bajard
* @copyright	Copyright(C) 2013 - Today. All rights reserved.
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

require_once("views/default.php");

class SlSetup{
	private $state, $view;

	public function __construct(){
		if(file_exists("../core/config/sl_config.php")) die ("FATAL ERROR : sl_config.php already present");
		$this->view = new DefaultView();
		call_user_func(array($this,$this->getState()));
	}
	/**
	* Simply display welcome message and bdd form
	*/
	private function init(){
		// Check uploaded form
		$datas = array();
		$errors = array();
		$fatals = array();
		if(isset($_POST['init_state'])){
			if(!empty($_POST['bdd_host'])){
				$datas['bdd_host'] = filter_var($_POST['bdd_host'],FILTER_SANITIZE_STRING);
			}else{
				$errors['bdd_host'] = "Ne peut pas &ecirc;tre vide!";
			}
			if(!empty($_POST['bdd_port'])){
				$datas['bdd_port'] = filter_var($_POST['bdd_port'],FILTER_SANITIZE_NUMBER_INT);
				if(!filter_var($datas['bdd_port'],FILTER_VALIDATE_INT)) $errors['bdd_port'] = "Valeur invalide";
			}else{
				$errors['bdd_port'] = "Ne peut pas &ecirc;tre vide!";
			}
			if(!empty($_POST['bdd_user'])){
				$datas['bdd_user'] = filter_var($_POST['bdd_user'],FILTER_SANITIZE_STRING);
			}else{
				$errors['bdd_user'] = "Ne peut pas &ecirc;tre vide!";
			}

			$datas['bdd_pwd'] = filter_var($_POST['bdd_pwd'],FILTER_SANITIZE_STRING);

			if(!empty($_POST['bdd_name'])){
				$datas['bdd_name'] = filter_var($_POST['bdd_name'],FILTER_SANITIZE_STRING);
			}else{
				$errors['bdd_name'] = "Ne peut pas &ecirc;tre vide!";
			}
			$datas['bdd_prefix'] = filter_var($_POST['bdd_prefix'],FILTER_SANITIZE_STRING);

			if(!empty($_POST['site_url'])){
				$datas['site_url'] = filter_var($_POST['site_url'],FILTER_SANITIZE_URL);
				if(!filter_var($datas['site_url'],FILTER_VALIDATE_URL)) $errors['site_url'] = "Adresse invalide";
			}else{
				$errors['site_url'] = "Ne peut pas &ecirc;tre vide!";
			}

			if(!empty($_POST['admin_name'])){
				$datas['admin_name'] = filter_var($_POST['admin_name'],FILTER_SANITIZE_STRING);
			}else{
				$errors['admin_name'] = "Ne peut pas &ecirc;tre vide!";
			}

			if(!empty($_POST['admin_user'])){
				$datas['admin_user'] = filter_var($_POST['admin_user'],FILTER_SANITIZE_STRING);
			}else{
				$errors['admin_user'] = "Ne peut pas &ecirc;tre vide!";
			}

			if(!empty($_POST['admin_pwd'])){
				$datas['admin_pwd'] = filter_var($_POST['admin_pwd'],FILTER_SANITIZE_STRING);
				if(strlen($datas['admin_pwd']) < 6) $errors['admin_pwd'] = "Mot de passe trop court";
			}else{
				$errors['admin_pwd'] = "Ne peut pas &ecirc;tre vide!";
			}

			if(!empty($_POST['admin_pwd_confirm'])){
				$datas['admin_pwd_confirm'] = filter_var($_POST['admin_pwd_confirm'],FILTER_SANITIZE_STRING);
				if($datas['admin_pwd_confirm'] != $datas['admin_pwd']) $errors['admin_pwd_confirm'] = "Les mots de passe ne correspondent pas.";
			}else{
				$errors['admin_pwd_confirm'] = "Ne peut pas &ecirc;tre vide!";
			}

			if(!empty($_POST['admin_mail'])){
				$datas['admin_mail'] = filter_var($_POST['admin_mail'],FILTER_SANITIZE_EMAIL);
				if(!filter_var($datas['admin_mail'],FILTER_VALIDATE_EMAIL)) $errors['admin_mail'] = "Adresse invalide";
			}else{
				$errors['admin_mail'] = "Ne peut pas &ecirc;tre vide!";
			}
			if(!empty($_POST['bdd_type'])){
				$datas['bdd_type'] = filter_var($_POST['bdd_type'],FILTER_SANITIZE_STRING);
			}else{
				$errors['bdd_type'] = "Ne peut pas &ecirc;tre vide!";
			}

			$datas['demo_datas'] = $_POST['demo_datas'];
			$datas['site_path'] = $_POST['site_path'];
			$datas['cache_path'] = $_POST['cache_path'];
			$datas['site_name'] = $_POST['site_name'];

			$datas['init_state'] = 2;
		}else{
			$datas['bdd_host'] = "localhost";
			$datas['bdd_port'] = "3386";
			$datas['bdd_user'] = "root";
			$datas['bdd_pwd'] = "";
			$datas['bdd_name'] = "db_slashcms";
			$datas['bdd_prefix'] = "sl_";
			$datas['bdd_type'] = "";
			$datas['site_path'] = "/".str_replace(str_replace("/",DIRECTORY_SEPARATOR,$_SERVER['DOCUMENT_ROOT']), "", dirname(__DIR__));
			$datas['cache_path'] = $datas['site_path']."/cache";
			$datas['site_name'] = "My very cool website.";
			$datas['site_url'] = "http://".$_SERVER['HTTP_HOST'];
			$datas['admin_name'] = "";
			$datas['admin_user'] = "";
			$datas['admin_pwd'] = "";
			$datas['admin_pwd_confirm'] = "";
			$datas['admin_mail'] = "";
			$datas['demo_datas'] = "on";
			$datas['init_state'] = 1;
		}
		if(version_compare(phpversion(), "5.2.0") == -1 ) $fatals[] = "Version PHP minimum : 5.2.0";
		if(!extension_loaded('gd')) $fatals[] = "Php GD requis";

		// No errors, redirect to bdd test
		if(empty($errors) && empty($fatals) && $datas['init_state'] == 2){
			// Save configuration into session before redirection
			session_start();
			foreach ($datas as $key => $value) {
				$_SESSION[$key] = $value;
			}
			header("Location:?state=checkSettings");
		}
		$datas['bdd_host'] = isset($_POST['bdd_host']) ? $_POST['bdd_host'] : "localhost";
		$this->view->loadInit($datas,$errors,$fatals);
	}
	/**
	* Do initials checks
	*/
	private function checkSettings(){
		$ret = array();
		$fatals = array();
		$warnings = array();
		session_start();
		$error_reporting = error_reporting();
		
		// Check database connector
		if(!extension_loaded($_SESSION['bdd_type'])) $fatals[] = "Extension ".$_SESSION['bdd_type']. " requise.";
		if(!$fatals){
			$db = $this->getConnector();
			// temporary disable error reporting
			error_reporting(0);
			if(!$db->connect($_SESSION['bdd_host'],$_SESSION['bdd_name'],$_SESSION['bdd_user'],$_SESSION['bdd_pwd'],$_SESSION['bdd_prefix'])) $fatals[] = "Erreur de connexion à la base de donn&eacute;es<br>". $db->getError();
			$db->setQuery("SHOW TABLES");
			if(!$db->setQuery("SHOW TABLES")->execute()) $fatals[] = "Erreur lors de l'ex&eacute;cution de la requ&ecirc;te<br>". $db->getError();
			if($db->fetchAll() != null ) $warnings[] = "La base <i>".$_SESSION['bdd_name']."</i> n'est pas vide.";
			if(!is_writable("../cache")) $fatals[] = "Le r&eacute;pertoire cache n'est pas accessible en &eacute;criture";
			if(!is_writable("../tmp")) $fatals[] = "Le r&eacute;pertoire cache n'est pas accessible en &eacute;criture";
			if(!is_writable("../medias")) $fatals[] = "Le r&eacute;pertoire cache n'est pas accessible en &eacute;criture";
			error_reporting($error_reporting);
		}
		$this->view->loadCheckSettings($fatals,$warnings);
	}

	private function install(){
		$fatals = array();
		set_time_limit(0);
		session_start();
		// Load sql file
		$queries = $this->SplitSQL("sql/slashcms.sql",$_SESSION['bdd_prefix']);
		$db = $this->getConnector();
		if(!$db->connect($_SESSION['bdd_host'],$_SESSION['bdd_name'],$_SESSION['bdd_user'],$_SESSION['bdd_pwd'],$_SESSION['bdd_prefix'])) $fatals[] = "Erreur de connexion à la base de donn&eacute;es<br>". $db->getError();
		// $db->autocommit(FALSE);
		$error_reporting = error_reporting();
		// temporary disable error reporting
		error_reporting(0);
		// Create tables
		foreach ($queries as $query) {
			if(!$db->setQuery($query)->execute()) $fatals[] = "Erreur lors de l'ex&eacute;cution de la requ&ecirc;te<br>". $db->getError();
		}
		// Create initial admin
		$sql_admin = "INSERT INTO `".$_SESSION['bdd_prefix']."users` (`id`, `name`, `login`, `password`, `mail`, `language`, `grade`, `allowed_module`, `enabled`) 
					VALUES (1, ".$db->quote($_SESSION['admin_name']).", ".$db->quote($_SESSION['admin_user']).", ".$db->quote(sha1($_SESSION['admin_pwd'])).", ".$db->quote($_SESSION['admin_mail']).", 'fr', 0, '', 1)";
		$db->setQuery($sql_admin)->execute();

		// Install demo datas
		if($_SESSION['demo_datas'] == "on"){
			$queries = $this->SplitSQL("sql/demodatas.sql",$_SESSION['bdd_prefix']);
			$db = $this->getConnector();
			if(!$db->connect($_SESSION['bdd_host'],$_SESSION['bdd_name'],$_SESSION['bdd_user'],$_SESSION['bdd_pwd'],$_SESSION['bdd_prefix'])) $fatals[] = "Erreur de connexion à la base de donn&eacute;es<br>". $db->getError();
			// $db->autocommit(FALSE);

			// Create tables
			foreach ($queries as $query) {
				if(!$db->setQuery($query)->execute()) $fatals[] = "Erreur lors de l'ex&eacute;cution de la requ&ecirc;te<br>". $db->getError();
			}

		}
		error_reporting($error_reporting);
		// if(empty($fatals)) if(!$db->commit()) $fatals[] = "Erreur lors de la cr&eacute;ation des tables<br>".$db_error;
		// else $db->rollback();

		//Create config file
		$source = "../core/config/sl_config.default.php";
		$dest = "../core/config/sl_config.php";
		

		if(!$fatals){
			// Create config file
			if(!copy($source,$dest)) $fatals[] = "Erreur lors de la cr&eacute;ation du fichier de configuration";
			$config = file_get_contents($dest);
			$config = str_replace("_bdd_host",$_SESSION['bdd_host'],$config);
			$config = str_replace("_bdd_user",$_SESSION['bdd_user'],$config);
			$config = str_replace("_bdd_pwd",$_SESSION['bdd_pwd'],$config);
			$config = str_replace("_bdd_name",$_SESSION['bdd_name'],$config);
			$config = str_replace("_bdd_prefix",$_SESSION['bdd_prefix'],$config);
			$config = str_replace("_bdd_port",$_SESSION['bdd_port'],$config);
			if($_SESSION['bdd_type'] == "mysql") $bdd_type = "MySQL";
			if($_SESSION['bdd_type'] == "mysqli") $bdd_type = "MySQLi";
			if($_SESSION['bdd_type'] == "pdo_mysql") $bdd_type = "PDO";
			$config = str_replace("_bdd_type",$bdd_type,$config);
			$config = str_replace("_site_path",($_SESSION['site_path'] == "/"?"/":$_SESSION['site_path']."/"),$config);
			$config = str_replace("_cache_path",$_SESSION['cache_path']."/",$config);
			$config = str_replace("_site_name",$_SESSION['site_name'],$config);
			$config = str_replace("_site_url",$_SESSION['site_url']."/",$config);
			file_put_contents($dest, $config);

			// Replace RewriteBase line in htaccess
			$htaccess = file_get_contents("../.htaccess");
			$htaccess = str_replace("RewriteBase /", "RewriteBase ".$_SESSION['site_path'], $htaccess);
			file_put_contents("../.htaccess", $htaccess);
;		}

		$this->view->loadInstall($fatals);
	}

	private function getConnector(){
		
		require_once("../core/common/implements/db/iconnector.php");
		
		switch ($_SESSION['bdd_type']) {
			case 'mysql':
				require_once("../core/common/class/db/mysql/connector.php");
				return MySQLConnector::getInstance();
				break;
			case 'mysqli':
				require_once("../core/common/class/db/mysqli/connector.php");
				return MySQLiConnector::getInstance();
				break;
			case 'pdo_mysql':
				require_once("../core/common/class/db/pdo/connector.php");
				return PDOConnector::getInstance();
				break;
		}

	}

	private function SplitSQL($file,$table_prefix = "sl_", $delimiter = ';'){
	    $ret = array();

	    if (is_file($file) === true){
	        $file = fopen($file, 'r');

	        if (is_resource($file) === true){
	            $query = array();

	            while (feof($file) === false){
	                $query[] = fgets($file);
	                if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1){
	                	$query = str_replace("bdd_prefix_", $table_prefix, $query);
	                    $query = trim(implode('', $query));
	                    $ret[] = $query;
	                }

	                if (is_string($query) === true){
	                    $query = array();
	                }
	            }
	            fclose ($file);
	            return $ret;
	        }
	    }

	    return false;
	}

	/**
	* Get current setup state
	*/
	private function getState(){
		if(isset($_GET['state'])){
			switch ($_GET['state']) {
				case 'init':
					return 'init';
					break;
				case 'checkSettings':
					return 'checkSettings';
					break;
				case 'install':
					return 'install';
					break;
				default:
					return 'init';
					break;
			}
		}else{
			return 'init';
		}
	}

}
?>