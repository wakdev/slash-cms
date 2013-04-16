<?php
/**
* @package		SLASH-CMS
* @subpackage	DB PDO
* @internal     PDO Connector
* @version		connector.php - Version 12.12.19
* @author		Benjamin Retel [http://www.rbcreation.fr]
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

Notes :
2013-03-15 : Add singleton

*/

class PDOConnector
{
	/*----------------------------------------------------------------------------------------------------
	 > ATTRIBUTES
	----------------------------------------------------------------------------------------------------*/
	private $_db_host;
	private $_db_name;
	private $_db_user;
	private $_db_pass;
	private $_db_prefix;
	
	private static $instance;
	
	private $_db_handle;
	//private $_db_selected;
	
	private $_db_error;
	private $_db_query;
	private $_db_query_prepare;
	private $_db_query_execute;
	private $_db_result;
	private $_db_fetch;
	
	
	/*----------------------------------------------------------------------------------------------------
	 > OVERLOADING
	----------------------------------------------------------------------------------------------------*/
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
	
	
	/*----------------------------------------------------------------------------------------------------
	 > METHODS
	----------------------------------------------------------------------------------------------------*/
	/* DATABASE CONNECTION */
	public function connect($db_host, $db_name, $db_user, $db_pass, $db_prefix) {
		$this->_db_host = $db_host;
		$this->_db_name = $db_name;
		$this->_db_user = $db_user;
		$this->_db_pass = $db_pass;
		$this->_db_prefix = $db_prefix;
		
		try{
			$this->_db_handle = new PDO('mysql:host='.$this->_db_host.';dbname='.$this->_db_name, $this->_db_user, $this->_db_pass);
		}catch (Exception $e){
			return false;
		}
		
		return true;
	}
	
	/* DATABASE DISCONNECTION */
	public function disconnect() {
		$this->_db_handle = null;
	}
	
	/* SQL QUERY */
	public function setQuery($sql) {
		$this->_db_query = $sql;
	}
	
	/* SQL QUERY PREPARE */
	public function setQueryPrepare($sql_prepare) {
		$this->_db_query_prepare = $sql_prepare;
	}
	
	/* SQL QUERY EXECUTE */
	public function setQueryExecute($sql_execute) {
		$this->_db_query_execute = $sql_execute;
	}
	
	/* QUERY EXECUTE */
	public function execute() {
		if (!$this->_db_query && !$this->_db_query_prepare) {
			$this->_db_error = "No query";
			return false;
		}else {
		
			if ($this->_db_query){
				$this->_db_result = $this->_db_handle->query($this->_db_query) or $this->show_fatal_error("Query Error", $this->_db_result->errorCode());
			}
			
			if ($this->_db_query_prepare){
				$this->_db_result = $this->_db_handle->prepare($this->_db_query_prepare);
				$this->_db_result->execute($this->_db_query_execute) or $this->show_fatal_error("Query Error", $this->_db_result->errorCode());
				$this->closeResult();
			}
			
			if (!$this->_db_result){
				$this->_db_error = "Query Error";
				return false;
			}else{
				return true;
			}
		}
	}
	
	/**
	* Show fatal errors and quit
	* @param $message error message
	* @param $code technical message error
	*/
	public function show_fatal_error($message, $code){	
		echo "<br /><table style='border: 1px solid #FF0000;' align='center'><tr><td>";
		echo "<font color='#FF0000' size='2'>".constant($message)." - ERROR CODE : ".$code."</font>";
		echo "</td></tr></table>";
		exit;
	}
	
	/* LAST INSERT */
	public function lastInsertId(){
		return $this->_db_handle->lastInsertId();
	}
	
	/* ROW COUNT */
	public function rowCount(){
		return $this->_db_result->rowCount();
	}
	
	/* FETCH */
	//MODES : PDO::FETCH_ASSOC | PDO::FETCH_BOTH | PDO::FETCH_BOUND | PDO::FETCH_CLASS | PDO::FETCH_INTO | PDO::FETCH_LAZY | PDO::FETCH_NUM | PDO::FETCH_OBJ
	public function fetch($mode = "ASSOC"){
		return $this->_db_result->fetch($this->getFetchConstant($mode));
	}
	
	/* FETCH ALL */
	public function fetchAll($mode = "ASSOC"){
		$this->execute();
		return $this->_db_result->fetchAll($this->getFetchConstant($mode));
	}
	
	/* RESULT CLOSE */
	public function closeResult(){
		return $this->_db_result->closeCursor();
	}
	
	/* GET ERROR */
	public function getError(){
		return $this->_db_error;
	}
	
	/* GET HANDLE */
	public function getHandle(){
		return $this->_db_handle;
	}
	
	private function getFetchConstant($mode){
		switch ($mode) {
			case "ASSOC":
			case "MYSQL_ASSOC":
				return PDO::FETCH_ASSOC;
			case "NUM":
			case "MYSQL_NUM":
				return PDO::FETCH_NUM;
			case "BOTH":
			case "MYSQL_BOTH":
				return PDO::FETCH_BOTH;
			default:
				return PDO::FETCH_ASSOC;
		} 
	}
	/**
	 * Alias for $this->_db_handle->quote()
	 * @param $value the value to quote
	 * @return the quoted value
	 * @todo add the $parameter_type to conform the PDO Connector at all.
	 */
	public function quote($value){
		return $this->_db_handle->quote($value);
	}
	/**
	 * Escapes strings in order to be included in SQL queries
	 * @param string $value the value to escape
	 * @return string the escaped string
	 */
	public function escape($value){
		//quote the value with PDO::quote() function then
		//remove enclosing quotes to conform "escape" protocol
		return substr($this->_db_handle->quote($value),1,-1);
	}
	
	/**
	 * Escapes array of strings in order to be included in SQL queries
	 * @uses PDOConnector::escape()
	 * @param string $values the values to escape
	 * @return string the escaped string
	 */
	public function escapeArray($values){
		$return = array();
		foreach($values as $key=>$value){
			if (is_string($value)){			
				$return[$key]=$this->escape($value);
			}else{
				$return[$key] = $value;
			}
		}
		return $return;
	}
}
?>
