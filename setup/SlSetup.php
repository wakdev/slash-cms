<?php
/**
* @package		SLASH-CMS
* @subpackage	SLSETUP
* @internal     Slash core system
* @version		SlSetup.php - Version 13.04.24
* @author		Loïc Bajard
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

			$datas['init_state'] = 2;
		}else{
			$datas['bdd_host'] = "localhost";
			$datas['bdd_port'] = "3386";
			$datas['bdd_user'] = "root";
			$datas['bdd_pwd'] = "";
			$datas['bdd_name'] = "slash-cms";
			$datas['bdd_prefix'] = "sla_";
			$datas['admin_name'] = "";
			$datas['admin_user'] = "";
			$datas['admin_pwd'] = "";
			$datas['admin_pwd_confirm'] = "";
			$datas['admin_mail'] = "";
			$datas['init_state'] = 1;
		}
		if(version_compare(phpversion(), "5.2.0") == -1 ) $fatals[] = "Version PHP minimum : 5.2.0";
		if(!extension_loaded('gd')) $fatals[] = "Php GD requis";
		if(!extension_loaded('mysqli')) $fatals[] = "Php MySQLi requis";
		if(!extension_loaded('pdo')) $fatals [] = "Php PDO requis";

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
		$db = mysqli_init();
		$error_reporting = error_reporting();
		// temporary disable error reporting
		error_reporting(0);
		if(!$db->real_connect($_SESSION['bdd_host'],$_SESSION['bdd_user'],$_SESSION['bdd_pwd'],$_SESSION['bdd_name'])) $fatals[] = "Erreur de connexion à la base de donn&eacute;es<br>". mysqli_connect_error();
		if($db->query("SHOW TABLES")->num_rows != 0) $warnings[] = "La base <i>".$_SESSION['bdd_name']."</i> n'est pas vide.";
		if(!is_writable("../cache")) $fatals[] = "Le r&eacute;pertoire cache n'est pas accessible en &eacute;criture";
		if(!is_writable("../tmp")) $fatals[] = "Le r&eacute;pertoire cache n'est pas accessible en &eacute;criture";
		if(!is_writable("../medias")) $fatals[] = "Le r&eacute;pertoire cache n'est pas accessible en &eacute;criture";
		$db->close();
		error_reporting($error_reporting);
		$this->view->loadCheckSettings($fatals,$warnings);
	}

	private function install(){
		$fatals = array();
		set_time_limit(0);
		session_start();
		// Load sql file
		$queries = $this->SplitSQL("db_slashcms.sql",$_SESSION['bdd_prefix']);
		$db = mysqli_init();
		if(!$db->real_connect($_SESSION['bdd_host'],$_SESSION['bdd_user'],$_SESSION['bdd_pwd'],$_SESSION['bdd_name'])) $fatals[] = "Erreur de connexion à la base de donn&eacute;es<br>". mysqli_connect_error();
		$db->autocommit(FALSE);

		// Create tables
		foreach ($queries as $query) {
			$db->query($query);
		}
		// Create initial admin
		$sql_admin = "INSERT INTO `".$_SESSION['bdd_prefix']."users` (`id`, `name`, `login`, `password`, `mail`, `language`, `grade`, `allowed_module`, `enabled`) 
					VALUES (1, '".$db->real_escape_string($_SESSION['admin_name'])."', '".$db->real_escape_string($_SESSION['admin_user'])."', '".$db->real_escape_string(sha1($_SESSION['admin_pwd']))."', '".$db->real_escape_string($_SESSION['admin_mail'])."', 'fr', 0, '', 1)";
		if(!$db->query($sql_admin)) $fatals[] = $db->error;
		if(empty($fatals)) if(!$db->commit()) $fatals[] = "Erreur lors de la cr&eacute;ation des tables<br>".$db_error;
		else $db->rollback();
		$db->close();

		//Create config file
		$source = "../core/config/sl_config.default.php";
		$dest = "../core/config/sl_config.php";
		

		if(!$fatals){
			if(!copy($source,$dest)) $fatals[] = "Erreur lors de la création du fichier de configuration";
			$config = file_get_contents($dest);
			$config = str_replace("bdd_host",$_SESSION['bdd_host'],$config);
			$config = str_replace("bdd_user",$_SESSION['bdd_user'],$config);
			$config = str_replace("bdd_pwd",$_SESSION['bdd_pwd'],$config);
			$config = str_replace("bdd_name",$_SESSION['bdd_name'],$config);
			$config = str_replace("bdd_prefix",$_SESSION['bdd_prefix'],$config);
			$config = str_replace("bdd_port",$_SESSION['bdd_port'],$config);
			file_put_contents($dest, $config);
		}

		$this->view->loadInstall($fatals);
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
	                    $query = preg_replace("/(CREATE TABLE IF NOT EXISTS|INSERT INTO) `(.*)`/", "$1 `".$table_prefix."$2`", $query);
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