<?php
/**
* @package		SLASH-CMS
* @subpackage	implements
* @internal     interface connector
* @version		iConnector.php
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

interface iConnector{
	
	public static function getInstance(); //Singleton
	
	public function connect($db_host,$db_name,$db_user,$db_password,$db_prefix); //Database connection
	public function disconnect(); //Disconnect
	public function setQuery($sql); //Load SQL Query
	public function execute(); //Execute query
	public function show_fatal_error ($message,$code); //Show fatal Error
	public function lastInsertId(); //Last insert ID
	public function rowCount(); //Row count
	public function fetch($mode = "ASSOC"); //Fetch query
	public function fetchAll($mode = "ASSOC"); //Fetch all
	public function getError(); //Get sql error
	public function getHandle(); //Get database handle
	public function quote($value,$real_escape=false); //Quote value
	public function escape($value,$real_escape=false); //Escape value
	public function escapeArray($values,$real_escape=false); //Escape array
	
}

?>