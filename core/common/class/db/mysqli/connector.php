<?php
/**
* @package		SLASH-CMS
* @subpackage	DB MYSQL
* @internal     MySQLi Connector
* @version		connector.php
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

class MySQLiConnector implements iConnector {
	
	private $db_host; //Database host
	private $db_name; //Database name
	private $db_user; //Database user
	private $db_password; //Database password
	private $db_prefix; //Database prefix
	
	private static $instance;
	
	private $db_error;
	private $db_query;
	private $db_result;
	private $mysqli;
	
	private function __construct() {}
	private function __clone() {}

	/**
	 * Singleton
	 */
	public static function getInstance() {
		if (!(self::$instance instanceof self)) {
		  self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Database connection
	 * @param string $_db_host
	 * @param string $_db_name
	 * @param string $_db_user
	 * @param string $_db_password
	 * @param string $_db_prefix
	 * @return boolean Success ?
	 */
	public function connect($_db_host,$_db_name,$_db_user,$_db_password,$_db_prefix) {
		
		$this->db_host = $_db_host;
		$this->db_name = $_db_name;
		$this->db_user = $_db_user;
		$this->db_password = $_db_password;
		$this->db_prefix = $_db_prefix;
		
		$this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
		
		// verify connexion 
		if (mysqli_connect_errno()) {
			$this->db_error = mysqli_connect_error();
			return false;
		}
		
		return $this;
	}
	
	/**
	 * Disconnect database
	 */
	public function disconnect() {
		$this->mysqli->close();
	}
	
	/**
	 * Set Query
	 * @param string $sql
	 */
	public function setQuery($sql){
		$this->db_query = $sql;
		return $this;
	}
	
	/**
	 * Execute Query
	 * @return boolean success ?
	 */
	public function execute(){
		if (!$this->db_query){ 
			$this->db_error = "No Query";
			return false;
		}
		
		$this->db_result = $this->mysqli->query($this->db_query);
		
		if (!$this->db_result) {
			$this->db_error = $this->mysqli->error;
			debug_print_backtrace();
			return false;
		}
			
		return $this;
	}
	
	/**
	* Show fatal errors and quit
	* @param $message error message
	* @param $code technical message error
	*/
	public function show_fatal_error ($message,$code) {	
		//Nothing
		exit;
	}
	
	/**
	 * Last insert ID
	 * @return number ID
	 */
	public function lastInsertId() {
		return $this->mysqli->insert_id;
	}
	
	/**
	 * Row count
	 * @return number NB row
	 */
	public function rowCount(){
		return $this->db_result->num_rows;
	}
	
	/**
	 * Fetch
	 * @param string $mode
	 * @return Array SQL Result
	 */
	public function fetch($mode="ASSOC") {
		return $this->db_result->fetch_array($this->getFetchConstant($mode));
	}
	
	/**
	 * Fetch ALL
	 * @param string $mode
	 * @return Array SQL Result
	 */
	public function fetchAll($mode="ASSOC") {
		
		$arr = array();
		while ($row = $this->db_result->fetch_array($this->getFetchConstant($mode))) {
		    array_push($arr,$row);
		}
		return $arr;
	}
	
	/**
	 * Get SQL Error
	 * @return string
	 */
	public function getError() {
		return $this->db_error;
	}
	
	
	// Retro
	public function getHandle() {
		return $this->mysqli;
	}
	
	/**
	 * Constants
	 * @param string $mode
	 * @return constant
	 */
	private function getFetchConstant($mode){
		switch ($mode) {
			case "ASSOC":
			case "MYSQL_ASSOC":
			case "MYSQLI_ASSOC":
				return MYSQLI_ASSOC;
			case "NUM":
			case "MYSQL_NUM":
			case "MYSQLI_NUM";
				return MYSQLI_NUM;
			case "BOTH":
			case "MYSQL_BOTH":
			case "MYSQLI_BOTH":
				return MYSQLI_BOTH;
			default:
				return MYSQLI_ASSOC;
		} 
	}
	/**
	 * Escape string and encloses it by simple quotes
	 * @param $value the value to quote
	 * @param bool $real_escape use the database escape method (safer, but may affect performances)
	 * @return the quoted value
	 */
	public function quote($value,$real_escape=false){
		return "'".$this->escape($value)."'";
	}
	/**
	 * Escapes strings in order to be included in SQL queries
	 * @param string $value the value to escape
	 * @param bool $real_escape use the database escape method (safer, but may affect performances)
	 * @return string the escaped string
	 */
	public function escape($value,$real_escape=false){
		if (!get_magic_quotes_gpc()) {
			return $this->mysqli->real_escape_string($value);
		}else{
			return $value;
		}
	}
	
	/**
	 * Escapes array of strings in order to be included in SQL queries
	 * @uses MySQLConnector::escape()
	 * @param string $values the values to escape
	 * @param bool $real_escape use the database escape method (safer, but may affect performances)
	 * @return string the escaped string
	 */
	public function escapeArray($values,$real_escape=false){
		$return = array();
		foreach($values as $key=>$value){
			if (is_string($value)){
				$return[$key]=$this->escape($value,$real_escape);
			}else{
				$return[$key] = $value;
			}
		}
		return $return;
	}
}
?>
