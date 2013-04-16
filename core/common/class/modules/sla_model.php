<?php
/**
* @package		SLASH-CMS
* @subpackage	MODEL ABSTRACT CLASS
* @internal     Module Model
* @version		sla_model.php - Version 12.3.02
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

abstract class slaModel{


	public $slash; //Core Reference
	public $controller; //Control Reference
	
	/**
	* Contructor
	*/
	function __construct(&$controller_class_ref) {
		$this->slash = &$GLOBALS["slash"];
		$this->controller = $controller_class_ref;
		
		$this->sla_construct();
	}
	
	/**
	 * Module constructor
	 */
	public function sla_construct() {}
	
	
	/**
	 * Save attachment
	 * @param string $destination Destination directory
	 * @param int $id_element ID element
	 * @param string/int $id_field ID field
	 * @return boolean success ?
	 */
	public function save_attachment($destination,$id_element,$id_field=""){
	
		if ($id_field != ""){$id_field = "AND id_field='".$id_field."'";}
	
		$this->slash->database->setQuery("
				SELECT * FROM ".$this->slash->db_prefix."attachments
				WHERE id_user='".$_SESSION["id_user"]."' AND id_module='".$this->controller->module_id."' ".$id_field." AND state='0'
				ORDER BY position");
	
		if (!$this->slash->database->execute()) {
			$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
		}
	
	
		if ($this->slash->database->rowCount() > 0) {
	
			$sl_files_sv = new sl_files();
	
			if (!$sl_files_sv->make_dir($destination."/".$id_element)){
				return false;
			}
	
	
			foreach ($this->slash->database->fetchAll("BOTH") as $row) {
				if (!$sl_files_sv->move_files("../tmp/".$row["filename"],$destination."/".$id_element."/".$row["filename"])){
					return false;
				}
			}
	
			unset($row);
	
	
			$this->slash->database->setQuery("
					UPDATE ".$this->slash->db_prefix."attachments set
					id_element='".$id_element."',
					state='1'
					WHERE id_user='".$_SESSION["id_user"]."' AND id_module='".$this->controller->module_id."' ".$id_field." AND state='0'");
	
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
	
			return true;
		}else{
			return true;
		}
	}
	
	
	/**
	 * Delete attachement
	 * @param string $destination Destination directory
	 * @param int $id_element ID element
	 * @param string/int $id_field ID field
	 * @return boolean success ?
	 */
	public function delete_attachment($destination,$id_element,$id_field=""){
	
		if ($id_field != ""){$id_field = "AND id_field='".$id_field."'";}
	
		$sl_files_dl = new sl_files();
	
		if ($sl_files_dl->remove_dir($destination."/".$id_element)) {
	
			$this->slash->database->setQuery("
					DELETE FROM ".$this->slash->db_prefix."attachments
					WHERE id_module=".$this->controller->module_id." AND id_element='".$id_element."' ".$id_field." AND state=1");
	
			if (!$this->slash->database->execute()) {
				$this->slash->show_fatal_error("QUERY_ERROR",$this->slash->database->getError());
			}
	
			return true;
		}else{
			return false;
		}
	
	}
	
}

?>