<?php
/**
* @package		SLASH-CMS
* @subpackage	sla_logs
* @internal     Admin logs module
* @version		logs.php
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

* @addtogroup sla_logs
* @{

*/


class logs extends slaModel implements iModel{

	
	public function load_items() {
		
		/* Order */
		$filter = "";
		
		if ($_SESSION[$this->controller->module_name."_search"] != "#") {
			if ($filter == ""){
				$filter = "WHERE log_info LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' ";
			}else{
				$filter .= "AND log_info LIKE '%".$this->slash->database->escape($_SESSION[$this->controller->module_name."_search"])."%' ";
			}
		}
			
		$this->slash->database->setQuery("SELECT id,log_type,log_info,id_user,log_date  
								FROM ".$this->slash->database_prefix."logs ".$filter."
								ORDER BY ".$_SESSION[$this->controller->module_name."_orderby"]." ".$_SESSION[$this->controller->module_name."_sort"]);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		
		$objects = array();
		$obj_ids = array("id","log_type","log_info","id_user","log_date");
		$obj_titles = array("ID",
						$this->slash->trad_word("SLA_LOGS_TYPE"),
						$this->slash->trad_word("SLA_LOGS_INFO"),
						$this->slash->trad_word("USER"),
						$this->slash->trad_word("SLA_LOGS_DATE"));
		$obj_sorts = array(false,false,false,false,true);
		$obj_sizes = array(5,20,50,10,10);
		$obj_actions = array(false,false,false,false,false);
		$obj_controls = null;

		foreach ($this->slash->database->fetchAll("BOTH") as $row) {
			
			/* USER */
			$this->slash->database->setQuery("SELECT * FROM ".$this->slash->db_prefix."users WHERE id='".$row[3]."'");
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
			$row_user = $this->slash->database->fetch("ASSOC");
			$row[3] = $row_user["name"];
			
			array_push($objects,$row);
		}
		
		//Load listing
		sl_interface::create_listing($this->controller->module_name,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$obj_controls,true,false,true,true);
		
	}
	
	
	/**
	 * Load items
	 * @param $id item ID
	 */
	public function load_item($id) {
		
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."logs WHERE id=".$id);
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
		$row = $this->slash->database->fetch("ASSOC");
		
		return $row;
	}
	
	
	/**
	 * Delete logs
	 */
	public function delete_items() {
		
		$this->slash->database->setQuery("SELECT * FROM ".$this->slash->database_prefix."logs");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
			
		$n_expired = $this->slash->database->rowCount();
		
		$this->slash->database->setQuery("TRUNCATE TABLE ".$this->slash->database_prefix."logs");
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}else{
			//Log action
			$log_info = $n_expired." logs clear";
			$this->slash->log($log_info,$this->controller->module_name);
		}
		
	}
	
	
	
	
	
	
	
}

/** 
* @} 
*/

?>