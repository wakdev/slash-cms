<?php
/**
* @package		SLASH-CMS
* @subpackage	TABS
* @internal     Tabs
* @version		sl_tabs.php
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

* @addtogroup sl_interfaces
* @{

*/

class sl_tabs {
	
	
	private $tabs;
	
	/**
	 * Construct
	 */
	function __construct(){
		$this->tabs = array();
	}
	
	/**
	 * Add a tab
	 * @param unknown $id
	 * @param unknown $title
	 * @param string $custom_icon
	 */
	public function addTab($id,$title,$custom_icon="",$custom_class=""){
		$this->tabs[$id]["id"] = $id;
		$this->tabs[$id]["title"] = $title;
		$this->tabs[$id]["custom_icon"] = $custom_icon;
		$this->tabs[$id]["custom_class"] = $custom_class;
		$this->tabs[$id]["current"] = false;
		
	}
	
	/**
	 * Set current tab
	 * @param unknown $id
	 */
	public function setCurrent($id){
		if (isset($this->tabs[$id])){
			foreach(array_keys($this->tabs) as $key){ 
				$this->tabs[$key]["current"] = false;
			}
			$this->tabs[$id]["current"] = true;
		}
	}
	
	
	/**
	 * Render
	 */
	public function render(){
		echo "<div id='slash-tabs' class='sl_adm_tabs'>";
		foreach($this->tabs as $tab){
			if ($tab["current"]==true) {$class = "sl_adm_tabs-active";} else {$class = "sl_adm_tabs-inactive";}
			if (!empty($tab["custom_class"])){$class .= " ".$tab["custom_class"];}
			if (isset($tab["custom_icon"])){$tab["title"] =  $tab["custom_icon"]."&nbsp;".$tab["title"];}
			echo "<a href='#' onclick=\"javascript:show_tab('".$tab["id"]."',$(this)); return false;\" class='".$class."'>".$tab["title"]."</a>";
		}
		echo "</div>";
	}
	
	/**
	 * Start Tab
	 * @param string $id
	 */
	public function startTab($id){
		
		if (isset($this->tabs[$id])){
			if (isset($this->tabs[$id]["current"]) && $this->tabs[$id]["current"] == true){
				$class = "sl_adm_tabs-div-active";
			}else{
				$class = "sl_adm_tabs-div-inactive";
			}
			
			echo "<div id='".$this->tabs[$id]["id"]."' class='sl_adm_form_main ".$class."' >";
		}
		
	}
	
	/**
	 * End Tab
	 */
	public function endTab(){
		echo "</div>";
	}
	
}




?>