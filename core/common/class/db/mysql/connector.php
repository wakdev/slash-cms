<?php
/**
* @package		SLASH-CMS
* @subpackage	DB MYSQL
* @internal     MySQL Connector
* @version		connector.php - Version 12.7.12
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

class MySQLConnector {
	
	private $db_host; //Database host
	private $db_name; //Database name
	private $db_user; //Database user
	private $db_password; //Database password
	private $db_prefix; //Database prefix
	
	private $db_handle;
	private $db_selected;
	
	private $db_error;
	private $db_query;
	private $db_result;
	private $db_fetch;
	
	function __construct() {
	
	}
	
	public function connect($_db_host,$_db_name,$_db_user,$_db_password,$_db_prefix) {
		
		$this->db_host = $_db_host;
		$this->db_name = $_db_name;
		$this->db_user = $_db_user;
		$this->db_password = $_db_password;
		$this->db_prefix = $_db_prefix;
		
		
		
		$this->db_handle = mysql_connect($this->db_host, $this->db_user, $this->db_password) or $this->show_fatal_error("CONNEXION_ERROR",mysql_error());
		$this->db_selected = mysql_select_db($this->db_name, $this->db_handle);
		
		if (!$this->db_selected) {
			$this->db_error = mysql_error();
			return false;
		}else{
			return true;
		}
		
	}
	
	public function disconnect() {
		mysql_close($this->db_handle);
	}
	
	public function setQuery($sql){
		$this->db_query = $sql;
	}
	
	public function execute(){
		if (!$this->db_query){ 
			$this->db_error = "No Query";
			return false;
		}
		
		$this->db_result = mysql_query($this->db_query,$this->db_handle) or $this->show_fatal_error("QUERY_ERROR",mysql_error());
		
		if (!$this->db_result) {
			$this->db_error = mysql_error();
			return false;
		}else{
			return true;
		}
	}
	
	/**
	* Show fatal errors and quit
	* @param $message error message
	* @param $code technical message error
	*/
	public function show_fatal_error ($message,$code) {	
		echo "<br /><table style='border: 1px solid #FF0000;' align='center'><tr><td>";
		echo "<font color='#FF0000' size='2'>".constant($message)." - ERROR CODE : ".$code."</font>";
		echo "</td></tr></table>";
		exit;
	}
	
	public function lastInsertId() {
		return mysql_insert_id();
	}
	
	public function rowCount(){
		return mysql_num_rows($this->db_result);
	}
	
	public function fetch($mode="MYSQL_BOTH") {
		
		return mysql_fetch_array($this->db_result, $this->getFetchConstant($mode));
	}
	
	public function fetchAll($mode="MYSQL_BOTH") {
		
		$arr = array();
		while ($row = mysql_fetch_array($this->db_result,$this->getFetchConstant($mode))) {
		    array_push($arr,$row);
		}
		return $arr;
	}
	
	
	public function getError() {
		return $this->db_error;
	}
	
	
	// Retro
	public function getHandle() {
		return $this->db_handle;
	}
	
	private function getFetchConstant($mode){
		switch ($mode) {
			case "ASSOC":
			case "MYSQL_ASSOC":
				return 1;
			break;
			case "NUM":
			case "MYSQL_NUM":
				return 2;
			break;
			case "BOTH":
			case "MYSQL_BOTH":
				return 3;
			break;
			default:
				return 3;
		} 
	}
}
?>