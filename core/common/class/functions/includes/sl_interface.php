<?php
/**
* @package		SLASH-CMS
* @subpackage	SL_INTERFACE
* @internal     interface functions
* @version		interface.php - Version 10.7.6
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

* @addtogroup sl_functions
* @{

*/

/*
* @TODO : Problème lors de l'appel de la fct JS du tri si autre qu'un title ou description
* @TODO : Problème de selection multiple, coche toutes les pages et non la page en cours
* @TODO : Faire la creation des controls en div et non en tableau
* @TODO : 
*/

class sl_interface {
	
	/**
	* create list
	* @param $core_ref Ref SLASH CMS core
	* @param $module_title Module title
	* @param $obj_titles Main titles
	* @param $obj_sorts Enabled or disabled sort
	* @param $objects Objetct list : [ID] [TEXT] [ACTION]
	* @param $controls Active controls list
	* @param $enable_checkbox Enable checkbox controls
	* @param $enable_actions Enable actions controls
	* @param $enable_paging Enable paging controls
	* @param $enable_search Enable search control
	*/
	public static function create_listing($module_title,$obj_ids,$obj_titles,$obj_sorts,$obj_sizes,$obj_actions,$objects,$controls,
							$enable_checkbox=true,$enable_actions=true,$enable_paging=true,$enable_search=false) {
		
		$slashcore = &$GLOBALS["slash"];
		
		//set obj / page 
		if ($enable_paging) {
			$nb_page = ceil(count($objects)/$_SESSION[$module_title."_nbbypage"]); // Total Page
		}else{
			$nb_page = count($objects); // Total Page
		}
		
		// ----------------- START PAGING ------------------ //
		if ($enable_paging) {
			
			if ($_SESSION[$module_title."_page"] > $nb_page) {
					$_SESSION[$module_title."_page"] = $nb_page;
			}
			
			echo "<div id='".$module_title."_list'>";
			echo "	<div class='sl_list_paging'>";
			echo "		<div style='position:absolute; left:0px;'>";
			echo "<font class='sl_mod_select_title'>".$slashcore->trad_word("VIEW")." : </font>";
			echo "		<select name='nbbypage'  heigth='20' onChange=\"javascript:submitForm('".$module_title."','nbbypage');\" class='sl_mod_select'>";
									
			if ($_SESSION[$module_title."_nbbypage"]==10) { echo "<OPTION VALUE='10' selected>10</OPTION>"; } else { echo "<OPTION VALUE='10'>10</OPTION>"; }
			if ($_SESSION[$module_title."_nbbypage"]==20) { echo "<OPTION VALUE='20' selected>20</OPTION>"; } else { echo "<OPTION VALUE='20'>20</OPTION>"; }
			if ($_SESSION[$module_title."_nbbypage"]==30) { echo "<OPTION VALUE='30' selected>30</OPTION>"; } else { echo "<OPTION VALUE='30'>30</OPTION>"; }
			if ($_SESSION[$module_title."_nbbypage"]==50) { echo "<OPTION VALUE='50' selected>50</OPTION>"; } else { echo "<OPTION VALUE='50'>50</OPTION>"; }
			if ($_SESSION[$module_title."_nbbypage"]==100){ echo "<OPTION VALUE='100' selected>100</OPTION>"; } else { echo "<OPTION VALUE='100'>100</OPTION>"; }
			if ($_SESSION[$module_title."_nbbypage"]==65537) { 
				echo "<OPTION VALUE='65537' selected>".$slashcore->trad_word("ALL")."</OPTION>"; 
			} else { 
				echo "<OPTION VALUE='65537'>".$slashcore->trad_word("ALL")."</OPTION>"; 
			}				
				
			echo "</select></div>";
			
			// ------------ SHOW SEARCH BOX  ------------ //
			if($enable_search) {
		
			echo "<div class='pull-right'>";
			echo "<font class='sl_searchbox_title'>".$slashcore->trad_word("SEARCH")." : </font>";
			echo "<input class='sl_searchbox' type='text' name='".$module_title."_search' id='".$module_title."_search' value='";
			
			if ($_SESSION[$module_title."_search"] != "#") { echo $_SESSION[$module_title."_search"]; }
				echo "' onchange=\"javascript:if(this.value.length == 0){this.value='#'}\"/>
					 
					<a href='javascript:void(0);' class='search_button' onClick=\"javascript:submitForm('".$module_title."','search');\"></a>
					<a href='javascript:void(0);' class='search_reset_button' onClick=\"javascript:submitForm('".$module_title."','reset');\"></a>";
					
					if ($_SESSION[$module_title."_search"] != "#") { 
						echo "<br /><font class='sl_searchbox_title'>".$slashcore->trad_word("YOUR_SEARCH")." : ".count($objects)." ".$slashcore->trad_word("RESULT")."</font>"; 
					} 
					
				echo "</div>";
			}
			
			
			echo "<div style='position:absolute; right:50%'>";
			echo "<p id='".$module_title."_nav_top' class='sl_mod_page_nav'>";
				echo "<a href='javascript:void(0);' rel='prev' class='back_list_button' onClick=\"unCheckAll('".$module_title."');\"></a>
					  <a href='javascript:void(0);' rel='1' class='highlight' onClick=\"unCheckAll('".$module_title."');\">&nbsp;1&nbsp;</a>";
							for ($i=2; $i<=$nb_page; $i++) {
								echo "<a href='javascript:void(0);' rel='".$i."' onClick=\"unCheckAll('".$module_title."');\">&nbsp;".$i."&nbsp;</a>";
							}
				echo "<a href='javascript:void(0);' rel='next' class='next_list_button' onClick=\"unCheckAll('".$module_title."');\"></a>";
			echo "</p>";
			
			
			
			echo "</div>";
			
			
			
		}
		
		
		echo "</div>";
		// ----------------- END PAGING ------------------ //
		
		
		
		// ----------------- START MAIN TITLE AND SORT ------------------ //
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0' class='sl_mod_page_title' ><tr>";
		
		if ($enable_checkbox) {
			echo "<td width='2%' align='left'><input id='checkControl' type='checkbox' onclick=\"if (this.checked == false) 
						{unCheckAll('".$module_title."');}else{checkAll('".$module_title."',".$_SESSION[$module_title."_nbbypage"].");}\" /></td>";
		}
				
		for ($i=0;$i<count($obj_titles);$i++) {
			
			echo "<td width='".$obj_sizes[$i]."%' align='left'>".$obj_titles[$i]."&nbsp;&nbsp;";
			
			if($obj_sorts[$i]) {
			
				$class_asc = "sort_asc";
				$class_desc = "sort_desc";
				
				if (isset ($_SESSION[$module_title."_orderby"]) && isset ($_SESSION[$module_title."_sort"]) && $_SESSION[$module_title."_orderby"] == $obj_ids[$i]) {
					if ($_SESSION[$module_title."_sort"] == "asc") {$class_asc = $class_asc."_on";}else{$class_desc = $class_desc."_on";} 	
				}
			
				echo "<a href='javascript:void(0);' class='".$class_asc."' onClick=\"javascript:submitForm('".$module_title."','order_".$obj_ids[$i]."_asc');\"></a>
					  <a href='javascript:void(0);' class='".$class_desc."' onClick=\"javascript:submitForm('".$module_title."','order_".$obj_ids[$i]."_desc');\"></a>";
			}
			
			echo "</td>";
		
		
		}	
		
		if ($enable_actions) {
			echo "<td width='10%' align='right'>";
			echo $slashcore->trad_word("CONTROLS");
			echo "</td>";
		}
						
		echo "</tr></table><br />";
		// ----------------- END MAIN TITLE AND SORT ------------------ //
		
		
		
		// ----------------- START OBJECT LIST ------------------ //
		$color = false;
	
		echo "<span id='sl_list' style='display: block; min-height: 154px; float:bottom; '>";
				echo "<table width='100%' cellspacing='0' cellpadding='0' border='0' class='sl_mod_page'>";
		
		if (count($objects) == 0) {
			echo "<tr><td align='center' width='100%' style='padding-right:70px;'>";
			echo $slashcore->trad_word("NO_RESULT");
			echo "</td>";
		
		}
		
		
		$i_pg = 0;
		
		for ($i=0;$i<count($objects);$i++) {
			
			if ($i_pg >= $_SESSION[$module_title."_nbbypage"] && $enable_paging) {
				
				echo "</tr>";
				echo "</table></span>";			
					
				echo "<span id='sl_list' style='display: block; min-height: 154px; float:bottom; '>
				<table width='100%' cellspacing='0' cellpadding='0' border='0' class='sl_mod_page'>";
					$i_pg=0;
			}
			
			
			
			if($color) {
				$style="sl_mod_page_ln1";
			} else {
				$style="sl_mod_page_ln2";
			}
			
			
			echo "<tr class='".$style."' >";
			
			if ($enable_checkbox) {
				echo "<td width='2%' ><input type='checkbox' id='".$module_title."_checked[".$i."]' name='".$module_title."_checked[".$i."]' value='".$objects[$i]["id"]."' /></td>";
			}
			
			for($j=0;$j<count($obj_titles);$j++) {
			
				if ($obj_actions[$j] != false) {
				
					switch ($obj_actions[$j]) {
						case "single_edit" :
							echo "<td width='".$obj_sizes[$j]."%' align='left' ><a href='javascript:void(0);' 
							onClick=\"javascript:submitForm('".$module_title."','single_edit','".$i."');\">".$objects[$i][$j]."</a></td>";
						break;
						
						case "single_show":
							echo "<td width='".$obj_sizes[$j]."%' align='left' ><a href='javascript:void(0);' 
							onClick=\"javascript:submitForm('".$module_title."','single_show','".$i."');\">".$objects[$i][$j]."</a></td>";
						break;
						
						case "set_state" :
							if ($objects[$i][$j] == 0 ){
								echo "<td width='".$obj_sizes[$j]."%' align='left' ><a href='javascript:void(0);' class='item_enabled_off'
									onClick=\"javascript:submitForm('".$module_title."','single_set_enabled','".$i."');\"></a></td>";
							}else {
								echo "<td width='".$obj_sizes[$j]."%' align='left' ><a href='javascript:void(0);' class='item_enabled_on'
									onClick=\"javascript:submitForm('".$module_title."','single_set_disabled','".$i."');\"></a></td>";	
							}
						break;
						
						case "set_home" :
							if ($objects[$i][$j] == 0 ){
								echo "<td width='".$obj_sizes[$j]."%' align='left' ><a href='javascript:void(0);' class='item_home_off'
									onClick=\"javascript:submitForm('".$module_title."','single_set_home','".$i."');\"></a></td>";
							}else {
								echo "<td width='".$obj_sizes[$j]."%' align='left' ><a href='javascript:void(0);' class='item_home_on'></a></td>";	
							}
						break;
						
						
						default:
						echo "<td width='".$obj_sizes[$j]."%' align='left'>".$objects[$i][$j]."</td>";
					
					}
				
				} else {
					echo "<td width='".$obj_sizes[$j]."%' align='left'>".$objects[$i][$j]."</td>";
				}
				
				
				if ($enable_actions && $j == (count($obj_titles)-1) ) {
				
					
					echo "<td width='10%' align='right'><table width='20%' cellspacing='0' cellpadding='0' border='0'><tr>";
								
					for($c=0;$c<count($controls);$c++) {
						
						switch ($controls[$c]) {
							case "single_edit":
								echo "<td>
										<a href='javascript:void(0);' class='list_edit_button' onClick=\"javascript:submitForm('".$module_title."','single_edit','".$i."');\"></a>
									  </td>";
							break;
							
							case "single_delete":
								echo "<td>
										<a href='javascript:void(0);' class='list_delete_button' onClick=\"javascript:submitForm('".$module_title."','single_delete','".$i."');\"></a>
									  </td>";
							break;
							
							case "single_up" :
							echo "<td>
									<a href='javascript:void(0);' class='item_up' onClick=\"javascript:submitForm('".$module_title."','single_set_up','".$i."');\"></a>
								  </td>";
							break;
							
							case "single_down" :
								echo "<td>
									<a href='javascript:void(0);' class='item_down' onClick=\"javascript:submitForm('".$module_title."','single_set_down','".$i."');\"></a>
								  </td>";
								
							break;
							
							case "single_print":
								echo "<td>
										<a href='javascript:void(0);' class='list_print_button' onClick=\"javascript:submitForm('".$module_title."','single_print','".$i."');\"></a>
									  </td>";
							break;
							
							case "single_show":
							echo "<td>
									<a href='javascript:void(0);' class='list_show_item_button' onClick=\"javascript:submitForm('".$module_title."','single_show','".$i."');\"></a>
								  </td>";
							break;
							
							default:
								echo "<td></td>";
						}
		
								
					}			
						
					echo "</tr></table></td>";
				
				}
				
			}
							
			$i_pg++;
			$color=!$color;
		}
		
		echo "</tr>";
			echo "</table></span>";
		// ----------------- END OBJECT LIST ------------------ //
		
		
		
		// ----------------- START BOTTOM PAGING ------------------ //
		if ($enable_paging) {
		
			echo "<div style='position:absolute; right:50%'>";
			echo "<p id='".$module_title."_nav_bottom' class='sl_mod_page_nav'>";
				echo "<a href='javascript:void(0);' rel='prev' class='back_list_button' ></a>
					  <a href='javascript:void(0);' rel='1' class='highlight'>&nbsp;1&nbsp;</a>";
							for ($i=2; $i<=$nb_page; $i++) {
								echo "<a href='javascript:void(0);' rel='".$i."'>&nbsp;".$i."&nbsp;</a>";
							}
				echo "<a href='javascript:void(0);' rel='next' class='next_list_button'></a>";
			echo "</p>";
			
			echo "</div>";
		}
		// ----------------- END BOTTOM PAGING ------------------ //
		
		
		
		echo "</div>";	
		
		if ($_SESSION[$module_title."_page"] == 0) {
			$_SESSION[$module_title."_page"] = 1;
		}
		
		echo "<script type='text/javascript'> 
				
			$(document).ready(function(){ 
				$('#".$module_title."_list').pager('span', {
						navIdTop:'".$module_title."_nav_top',
						navIdBottom:'".$module_title."_nav_bottom',
						navClass:'mod_page_nav',
						initPage:".$_SESSION[$module_title."_page"]."
				});
			});
						
			</script>";
	}
	
	/*
	* Load  sessions for listing
	* @param $module_name:string Module name
	* @param $order_ids:Array List of id orderby ($order_ids[0] = is a default orderby)
	* @param $first_sort:string First sort (asc or desc)
	*/
	public static function listing_sessions($module_name,$order_ids,$first_sort="asc") {
	
	$slashcore = &$GLOBALS["slash"];
	
		if (isset($_SESSION[$module_name."_nbbypage"]) == false) { $_SESSION[$module_name."_nbbypage"] = 10;}
		if (isset($_SESSION[$module_name."_orderby"]) == false) { $_SESSION[$module_name."_orderby"] = $order_ids[0];}
		if (isset($_SESSION[$module_name."_sort"]) == false) { $_SESSION[$module_name."_sort"] = $first_sort;}
		if (isset($_SESSION[$module_name."_page"]) == false) { $_SESSION[$module_name."_page"] = 1;}
		if (isset($_SESSION[$module_name."_lang"]) == false) { 
			$arr_lg = $slashcore->get_active_lang();
			$_SESSION[$module_name."_lang"] = $arr_lg[0]["id"];
		}
		if (isset($_SESSION[$module_name."_search"]) == false) { $_SESSION[$module_name."_search"] = "#";}
		
		/* Ascending compatibility */
		if (isset($_SESSION[$module_name."_categorie"]) == false) { $_SESSION[$module_name."_categorie"] = -1;}
		/* ------------------------- */
		
		if (isset($_SESSION[$module_name."_categorie1"]) == false) { $_SESSION[$module_name."_categorie1"] = -1;}
		if (isset($_SESSION[$module_name."_categorie2"]) == false) { $_SESSION[$module_name."_categorie2"] = -1;}
		if (isset($_SESSION[$module_name."_categorie3"]) == false) { $_SESSION[$module_name."_categorie3"] = -1;}
		if (isset($_SESSION[$module_name."_categorie4"]) == false) { $_SESSION[$module_name."_categorie4"] = -1;}
		
		if (isset($_POST[$module_name."_nbbypage"])) { $_SESSION[$module_name."_nbbypage"] = $_POST[$module_name."_nbbypage"];}
		if (isset($_POST[$module_name."_orderby"])) { $_SESSION[$module_name."_orderby"] = $_POST[$module_name."_orderby"];}
		if (isset($_POST[$module_name."_sort"])) { $_SESSION[$module_name."_sort"] = $_POST[$module_name."_sort"];}
		if (isset($_POST[$module_name."_page"])) { $_SESSION[$module_name."_page"] = $_POST[$module_name."_page"];}
		if (isset($_POST[$module_name."_lang"])) { $_SESSION[$module_name."_lang"] = $_POST[$module_name."_lang"];}
		if (isset($_GET[$module_name."_lang"])) { $_SESSION[$module_name."_lang"] = $_GET[$module_name."_lang"];}
		if (isset($_POST[$module_name."_search"]) && $_POST[$module_name."_search"]!= "" ) { 
			$_SESSION[$module_name."_search"] = $_POST[$module_name."_search"];
		}
		
		/* Ascending compatibility */
		if (isset($_POST[$module_name."_categorie"])) { $_SESSION[$module_name."_categorie"] = $_POST[$module_name."_categorie"];}
		/* ------------------------- */
		
		if (isset($_POST[$module_name."_categorie1"])) { $_SESSION[$module_name."_categorie1"] = $_POST[$module_name."_categorie1"];}
		if (isset($_POST[$module_name."_categorie2"])) { $_SESSION[$module_name."_categorie2"] = $_POST[$module_name."_categorie2"];}
		if (isset($_POST[$module_name."_categorie3"])) { $_SESSION[$module_name."_categorie3"] = $_POST[$module_name."_categorie3"];}
		if (isset($_POST[$module_name."_categorie4"])) { $_SESSION[$module_name."_categorie4"] = $_POST[$module_name."_categorie4"];}
		
		
		if (isset($_SESSION[$module_name."_orderby"])) { 
			$current_order = $order_ids[0];
			for ($i=0;$i<count($order_ids);$i++) {
				if ($_SESSION[$module_name."_orderby"] == $order_ids[$i]) {
					$current_order = $order_ids[$i];
				}
			}
			$_SESSION[$module_name."_orderby"] = $current_order;
		}
				
	}
	
	/*
	* Load  sessions for listing
	* @param $module_name:String Module name
	*/
	public static function listing_hidden_fields($module_name) {
	
		echo "<input type='hidden' id='".$module_name."_nbbypage' name='".$module_name."_nbbypage' value='".$_SESSION[$module_name."_nbbypage"]."'>
			  <input type='hidden' id='".$module_name."_orderby' name='".$module_name."_orderby' value='".$_SESSION[$module_name."_orderby"]."'>
			  <input type='hidden' id='".$module_name."_sort' name='".$module_name."_sort' value='".$_SESSION[$module_name."_sort"]."'>
			  <input type='hidden' id='".$module_name."_page' name='".$module_name."_page' value='".$_SESSION[$module_name."_page"]."'>
			  <input type='hidden' id='".$module_name."_lang' name='".$module_name."_lang' value='".$_SESSION[$module_name."_lang"]."'>
			  <input type='hidden' id='".$module_name."_categorie1' name='".$module_name."_categorie1' value='".$_SESSION[$module_name."_categorie1"]."'>
			  <input type='hidden' id='".$module_name."_categorie2' name='".$module_name."_categorie2' value='".$_SESSION[$module_name."_categorie2"]."'>
			  <input type='hidden' id='".$module_name."_categorie3' name='".$module_name."_categorie3' value='".$_SESSION[$module_name."_categorie3"]."'>
			  <input type='hidden' id='".$module_name."_categorie4' name='".$module_name."_categorie4' value='".$_SESSION[$module_name."_categorie4"]."'>";
		
		/* Ascending compatibility */		
		echo  "<input type='hidden' id='".$module_name."_categorie' name='".$module_name."_categorie' value='".$_SESSION[$module_name."_categorie"]."'>";
		/* ------------------------- */	 
				
	}
	
	
	/*
	* Create tab
	* @param $module_name:string Module name
	* @param $obj:array / string tabs title
	* @param $current:int current tab
	*/
	public static function create_tabs($module_name,$links,$titles,$current=0) {
		echo "<div id='".$module_name."_tab_container' class='tab_container'><ul>";
		for ($i=0;$i<count($links);$i++) {
			if ($i==$current) {
				echo "<li class='tab_container-selected'><a href='".$links[$i]."'><span>".$titles[$i]."</span></a></li>";
			}else{
				echo "<li><a href='".$links[$i]."'><span>".$titles[$i]."</span></a></li>";
			}
		}
		echo "</ul></div>";
	}
	
	
	/*
	* Create lang tab
	* @param $core_ref core reference
	* @param $module_ref:string Module reference
	*/
	public static function create_lang_tabs(&$module_ref) {

		$slashcore = &$GLOBALS["slash"];
		$module = $module_ref;
		$lg = $slashcore->get_active_lang();
		
		echo "<div id='".$module->module_name."_tab_container' class='tab_container'><ul>";
		
		for($i=0;$i<count($lg);$i++){
			
			if ($module->lang == $lg[$i]["id"]) { 
				echo "<li class='tab_container-selected'><a href='index.php?mod=".$module->module_name."&".$module->module_name."_lang=".$lg[$i]["id"]."'>
				<span><img src='templates/system/images/flags/".$lg[$i]["shortname"].".png' width='16' border='0'/>&nbsp;".$lg[$i]["name"]."</span></a></li>";
			}else{
				echo "<li><a href='index.php?mod=".$module->module_name."&".$module->module_name."_lang=".$lg[$i]["id"]."'>
				<span><img src='templates/system/images/flags/".$lg[$i]["shortname"].".png' width='16' border='0'/>&nbsp;".$lg[$i]["name"]."</span></a></li>";
			}
		}
		
		echo "</ul></div>";
	}
	
	
	/*
	* Create slash tab
	* @param $ids IDS tabs
	* @param $titles:Array Tabs titles
	* @param $current:int Current active tabs
	*/
	public static function create_slash_tabs($ids,$titles,$current=0) {
		
		echo "<div id='slash-tabs' class='sl_adm_tabs'>";
		
		for($i=0;$i<count($ids);$i++){
		
			if ($i==$current) { $class = "class='sl_adm_tabs-active'"; } else { $class = ""; }
			
			echo "<a href='javascript:void();' 
			onclick=\"
				show_tab(".$ids[$i]["id"].");
				$(this).addClass('sl_adm_tabs-active');
			\" ".$class.">".$titles[$i]."</a>";
		}
		
		echo "</div>";
	
	}
	
	/*
	* Create form lang tab
	* @param $lg:Array Lang
	* @param $current:int Current active tabs
	*/
	public static function create_slash_lang_tabs($lg,$current=0){
		
		echo "<div id='slash-tabs' class='sl_adm_tabs'>";
		
		for($i=0;$i<count($lg);$i++){
		
			if ($i==$current) { $class = "class='sl_adm_tabs-active'"; } else { $class = ""; }
			
			echo "<a href='javascript:void();' 
			onclick=\"
				show_tab(".$lg[$i]["id"].");
				$(this).addClass('sl_adm_tabs-active');
			\" ".$class.">
			<img src='templates/system/images/flags/".$lg[$i]["shortname"].".png' width='27' border='0' /></a>";
		}
		
		echo "</div>";
	
	}
	
	
	/*
	* Show top control
	* @param $module_name:string Module name
	* @param $controls:Array List of control
	*/
	public static function create_control_buttons($module_name,$controls) {
		
		$slash = &$GLOBALS["slash"];
	
		$size = floor(100 / count($controls));
	
		echo "<table align='right'><tr>";
		
		for ($i=0;$i<count($controls);$i++) {
			switch($controls[$i]) {
				case "add":
					echo "<td align='center' width='".$size."%'><a href='javascript:void(0);' class='add_button'
									onClick=\"javascript:submitForm('".$module_name."','add');\"></a>".$slash->trad_word("ADD")."</td>";
				break;
				case "edit":
					echo "<td align='center' width='".$size."%'><a href='javascript:void(0);' class='edit_button'
									onClick=\"javascript:submitForm('".$module_name."','edit');\"></a>".$slash->trad_word("EDIT")."</td>";	
				break;
				case "publish":
					echo "<td align='center' width='".$size."%'><a href='javascript:void(0);' class='publish_button'
									onClick=\"javascript:submitForm('".$module_name."','set_enabled');\"></a>".$slash->trad_word("ENABLED")."</td>";
				break;
				case "unpublish":
					echo "<td align='center' width='".$size."%'><a href='javascript:void(0);' class='unpublish_button'
									onClick=\"javascript:submitForm('".$module_name."','set_disabled');\"></a>".$slash->trad_word("DISABLED")."</td>";
				break;
				case "del":
				case "delete":
					echo "<td align='center' width='".$size."%'><a href='javascript:void(0);' class='delete_button'
									onClick=\"javascript:submitForm('".$module_name."','delete');\"></a>".$slash->trad_word("DELETE")."</td>";
				break;
				case "save":
					echo "<td align='center' width='".$size."%'><a href='javascript:void(0);' class='apply_button' onClick=\"
								if (check_fields()){
									submitForm('".$module_name."','add_apply');
								}else{
									alert('".$slash->trad_word("FIELD_NEED")." ! ');
								}\"></a>
								".$slash->trad_word("SAVE")."</td>";
				break;
				case "print":
					echo "<td align='center' width='".$size."%'><a href='javascript:void(0);' class='print_button'
									onClick=\"javascript:submitForm('".$module_name."','print');\"></a>".$slash->trad_word("PRINT")."</td>";
				break;
				case "back":
					echo "<td align='center' width='".$size."%'><a href='index.php?mod=".$module_name."' class='undo_button'></a>
								".$slash->trad_word("BACK")."	</td>";
				break;
				
				
				default:
					echo "<td align='center' width='".$size."%'>- NO CONTROL -</td>";
			
			}
		
		}
		
		echo "</tr></table>";					
								
	}
	
	/*
	* Show message
	* @param $message:string Message
	*/
	public static function create_message_zone($message) {
		if ($message != "") { 
			echo "<div id='message'>
					<table align='center'>
					<tr><td class='sl_mod_message' align='center'>".$message."</td></tr></table>
				  </div>";
		}
	}
	
	
	/*
	* Show categorie select
	* @param $module_name:string Module name
	* @pram $title:string Title of select
	* @param $row:array List of item
	* @param $cat_id:int Id of select (1,2,3 or 4)
	*/
	public static function create_categorie_selection($module_name,$title,$row,$row_title,$cat_id,$all=true) {
	
		$slashcore = &$GLOBALS["slash"];
	
		echo "<div style='padding-top:10px; padding-bottom:10px;'>";
		echo "<font class='sl_mod_select_title'>".$title." : </font>";
		echo "<select name='categorie".$cat_id."' class='sl_mod_select' 
			onChange=\"javascript:submitForm('".$module_name."','select_cat".$cat_id."');\">";
		
		if ($all==true) {		
			if ($_SESSION[$module_name."_categorie".$cat_id]==-1) { 
				echo "<OPTION VALUE='-1' selected>".$slashcore->trad_word("ALL")."</OPTION>"; 
			} else { 
				echo "<OPTION VALUE='-1'>".$slashcore->trad_word("ALL")."</OPTION>"; 
			}	
		}
	
		for ($i=0; $i<count($row); $i++ ) {
			
			if($all==false && $_SESSION[$module_name."_categorie".$cat_id]==-1 && $i==0){
				$_SESSION[$module_name."_categorie".$cat_id] = $row[$i]["id"];
			}
			
			if ($_SESSION[$module_name."_categorie".$cat_id]==$row[$i]["id"]) { 
				echo "<OPTION VALUE='".$row[$i]["id"]."' selected>".$row[$i][$row_title]."</OPTION>"; 
			} else { 
				echo "<OPTION VALUE='".$row[$i]["id"]."'>".$row[$i][$row_title]."</OPTION>";
			}
		}
				
		echo "</select></div>";
	}
	
	/**
	* Add / Edit interface form
	* @param $core_ref:class Slash reference
	* @param $module_tile:string Module title
	* @param $header_title:string Header title
	* @param $obj_id:int Object ID
	* @param $obj_fields:array Fields
	* @param $obj_titles:array Titles
	* @param $obj_styles:array Fields Styles
	* @param $objects:array Object Array
	* @param $controls:array Controls
	* @param $fields_val_mess:array Fields value and messages fields_val_mess[0][value] = value, fields_val_mess[0][message]
	*/
	public static function create_show_form($module_title,$header_title,$obj_id,$obj_fieds,$obj_titles,$obj_styles,$objects,$controls=null,$fields_val_mess=null,$code=null){
		
		$slashcore = &$GLOBALS["slash"];
		
		echo "	<form name='".$module_title."_add_form' method='post' action='index.php?mod=".$module_title."' enctype='multipart/form-data'>
				<input type='hidden' id='".$module_title."_act' name='".$module_title."_act' value='save'>
				<input type='hidden' id='".$module_title."_id_obj' name='".$module_title."_id_obj' value='".$obj_id."'>";
				
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>";
		
		/* ---- HEADER ---- */
		echo "<table width='100%' cellspacing='0' cellpadding='0' border='0'>
				<tr>
					<td class='sl_mod_title'>".$header_title."</td>
					<td class='sl_mod_control'>
						<table align='right' width='200'>
						<tr>
							<td align='center' width='50%'>	
								<a href='javascript:void(0);' class='apply_button' onClick=\"
							
								if (check_fields()){
									submitForm('".$module_title."','add_apply');
								}else{
									alert('".$slashcore->trad_word("FIELD_NEED")." ! ');
								}\"></a>
								".$slashcore->trad_word("SAVE")."</td>
								
							<td align='center' width='50%'>			
								<a href='index.php?mod=".$module_title."' class='undo_button'></a>
								".$slashcore->trad_word("BACK")."			
							</td>
						</tr>
						</table>	
					</td>
				</tr>
			</table>";
		
		/* ------------- */
		
		echo "<br />";
		
		echo "<table width='700' cellspacing='0' cellpadding='10' border='0' align='center' style='border:1px solid #333;'>";
		
		for ($i=0;$i<count($obj_styles);$i++) {
			
			

			
			echo "<tr><td align='center'>";	
			
			echo "<div id='".$module_title."_div".$i."' >";
			
			
			if ($i>0) {
				echo "<div class='sl_separation'></div><br />";
			}
			
			$field_title = $obj_titles[$i];
			if (isset ($obj_styles[$i]["mandatory"]) &&  $obj_styles[$i]["mandatory"] == "1") { 
				$field_title .= " <span class='sl_mandatory'>*</span>";
			}
			
			
			
			switch ($obj_styles[$i]["type"]){
			
				//Show input field
				case "input":
					
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='200'>".$field_title." : </td>
								<td align='left' >	
								<input id='".$module_title."_obj".$i."' name='".$module_title."_obj".$i."' type='text' class='sl_mod_field_input' size='80' value='".$objects[$obj_fieds[$i]]."'>
								</td>
							</tr>";
							
					if ($fields_val_mess[$i]["message"]) {
						echo "<tr><td align='left' width='200'></td><td align='left' class='sl_mod_field_error_msg'><img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$fields_val_mess[$i]["message"]."</td></tr>";	
					}		
					
					echo	"</table>";
				
				break;
				
				//Show password field
				case "password":
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='200'>".$field_title." : </td>
								<td align='left' >	
								<input id='".$module_title."_obj".$i."' name='".$module_title."_obj".$i."' type='password' class='sl_mod_field_input' size='80' value='".$objects[$obj_fieds[$i]]."'>
								</td>
							</tr>";
							
					if ($fields_val_mess[$i]["message"]) {
						echo "<tr><td align='left' width='180'></td><td align='left' class='sl_mod_field_error_msg'><img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$fields_val_mess[$i]["message"]."</td></tr>";	
					}		
					
					echo	"</table>";
				
				break;
				
				//Show input field
				case "textzone":
					
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='200'>".$field_title." : </td>
								<td align='left' >".$obj_styles[$i]["value"]."</td>
							</tr>";
					echo "</table>";
				
				break;
				
				//Show hidden field
				case "hidden":
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='200'>".$field_title." : </td>
								<td align='left' >	
								<input id='".$module_title."_obj".$i."' name='".$module_title."_obj".$i."' type='hidden' class='sl_mod_field_input' value='".$objects[$obj_fieds[$i]]."'>
								</td>
							</tr>
							</table>";
				
				break;
				
				//Show checkbox
				case "checkbox":
					
					if ($objects[$obj_fieds[$i]] == "1") { $check_val = "checked"; } else {  $check_val = ""; }
					if (isset($obj_styles[$i]["events"]) && $obj_styles[$i]["events"] != "") { $js = $obj_styles[$i]["events"]; } else { $js = ""; }
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left'  class='sl_mod_field_title' >".$field_title." : </td>
								<td align='left' width='70%'>	
								<input id='".$module_title."_obj".$i."' name='".$module_title."_obj".$i."' type='checkbox' ".$check_val." value='1' ".$js.">
								</td>
							</tr>";
							
					if ($fields_val_mess[$i]["message"]) {
						echo "<tr><td align='left' width='180'></td><td align='left' class='sl_mod_field_error_msg'><img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$fields_val_mess[$i]["message"]."</td></tr>";	
					}		
					
					echo	"</table>";
				
				break;
				
				//Show editor field
				case "editor":
				case "fckeditor":
					
					include("../core/plugins/fckeditor/fckeditor.php");
				
					
					echo "<table width='700' cellspacing='0' cellpadding='10' border='0' ><tr>
							<td align='left' class='sl_mod_field_title'>".$field_title." :</td>
							</tr>		
							<tr><td align='left'>";
							$oEditor = new FCKeditor($module_title."_obj".$i) ;
							$oEditor->BasePath	= "../core/plugins/fckeditor/" ;
							$oEditor->ToolbarSet	= 'Slash' ;	//style de la barre
							$oEditor->Width		= '600' ;		//largeur de la barre
							$oEditor->Height		= '300' ;		//hauteur de la barre
							$oEditor->Config['EnterMode'] = 'br';
							$oEditor->Value		= $objects[$obj_fieds[$i]];		//valeur par défaut
							$oEditor->Create() ;
					echo "</td></tr>";
					
					if ($fields_val_mess[$i]["message"]) {
						echo "<tr><td align='left' width='180'></td><td align='left' class='sl_mod_field_error_msg'><img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$fields_val_mess[$i]["message"]."</td></tr>";	
					}		
					
					echo	"</table>";
					
				break;
				
				//Show editor field
				case "tinymce":
					
					if ($obj_styles[$i]["css"]) { $css = $obj_styles[$i]["css"]; } else { $css = ""; }
					if ($obj_styles[$i]["script_url"]) { $script_url = $obj_styles[$i]["script_url"]; } else { $script_url = "../core/plugins/tiny_mce/tiny_mce.js"; }
					if ($obj_styles[$i]["theme"]) { $theme = $obj_styles[$i]["theme"]; } else { $theme = "advanced"; }
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='200'>".$field_title." : </td>
								</tr>		
								<tr><td align='left' ><br />	
								<textarea id='".$module_title."_obj".$i."' name='".$module_title."_obj".$i."' style='".$obj_styles[$i]["style"]."'>".$objects[$obj_fieds[$i]]."</textarea>
								
								</td>
							</tr>";
							
					
				
									
					if ($fields_val_mess[$i]["message"]) {
						echo "<tr><td align='left' width='180'></td><td align='left' class='sl_mod_field_error_msg'><img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$fields_val_mess[$i]["message"]."</td></tr>";	
					}		
					
					echo	"</table>";
					
					echo '<script type="text/javascript">
								$(document).ready(function(){ 
									$("#'.$module_title.'_obj'.$i.'").tinymce({
										// Location of TinyMCE script
										script_url : "'.$script_url.'",
										
										// General options
										mode : "textareas",
										theme : "'.$theme.'",
										language : "fr",
										forced_root_block : false,
										force_br_newlines : true,
										force_p_newlines : false,
										relative_urls : false,
										file_browser_callback : "tinyBrowser",
										skin : "o2k7",
										plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

										// Theme options
										theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
										theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
										theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
										
										theme_advanced_toolbar_location : "top",
										theme_advanced_toolbar_align : "left",
										theme_advanced_statusbar_location : "bottom",
										theme_advanced_resizing : true,

										// Example word content CSS (should be your site CSS) this one removes paragraph margins
										content_css : "'.$css.'",

										// Drop lists for link/image/media/template dialogs
										//template_external_list_url : "lists/template_list.js",
										//external_link_list_url : "lists/link_list.js",
										//external_image_list_url : "lists/image_list.js",
										//media_external_list_url : "lists/media_list.js",

										// Replace values for the template plugin
										//template_replace_values : {
										//	username : "Some User",
										//	staffid : "991234"
										//}			
									});
									
									
								});
							</script>';
					
					
				break;
				
				//Show select field
				case "select":
										
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='200'>".$field_title." : </td>
								<td align='left' >	
								<select id='".$module_title."_obj".$i."' name='".$module_title."_obj".$i."' class='sl_mod_field_select'>";
						
						for ($k=0; $k<count($obj_styles[$i]["values"]); $k++) {
							echo "<option value='".$obj_styles[$i]["values"][$k]."' ";
							if ($obj_styles[$i]["values"][$k] == $objects[$obj_fieds[$i]]) {
								echo "selected";
							}
							echo ">".$obj_styles[$i]["texts"][$k]."</option>";
						}
						
					echo "
								</select> 
								</td>
							</tr>";
							
					if ($fields_val_mess[$i]["message"]) {
						echo "<tr><td align='left' width='180'></td><td align='left' class='sl_mod_field_error_msg'><img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$fields_val_mess[$i]["message"]."</td></tr>";	
					}		
					
					echo	"</table>";
				break;
				
				//Show date field
				case "date":
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' class='sl_mod_field_title' width='200'>".$field_title." : </td>
								<td align='left' width='200'>	
									<div id='datepicker_".$i."' name='datepicker_".$i."' style='font-size:60%;'></div>
								</td>
								<td align='left' >
									<input id='".$module_title."_obj".$i."' name='".$module_title."_obj".$i."' type='text' class='sl_mod_field_date' readonly size='10' >	
								</td>
							</tr>";
						//value='".$objects[$obj_fieds[$i]]."'
					if ($fields_val_mess[$i]["message"]) {
						echo "<tr><td align='left' width='180'></td><td align='left' class='sl_mod_field_error_msg'><img src='templates/system/images/assets/error.png' align='absmiddle'/>&nbsp;&nbsp;".$fields_val_mess[$i]["message"]."</td></tr>";	
					}	
					
					echo	"</table>";
					
					echo "	<script type='text/javascript'> 
								$(document).ready(function(){ 
									var current_date = '".$objects[$obj_fieds[$i]]."';
									
									$('#datepicker_".$i."').datepicker({ altField: '#".$module_title."_obj".$i."' }); ";
									
					if ($objects[$obj_fieds[$i]] != "" && $objects[$obj_fieds[$i]] != "0000-00-00") {
						
						$date = explode("-",$objects[$obj_fieds[$i]]);
						$day = $date[2];
						$month = $date[1] - 1;
						$year = $date[0];
						
						echo "$('#datepicker_".$i."').datepicker( 'setDate',  new Date(".$year.",".$month.",".$day.") );";
					}
					echo "	});</script>";
					
				break;
				
				//Show PLUGIN Attachement (ajaxupload)
				case "attachments_list":
						
					$id_module = $slashcore->sl_module_id($module_title);
					
					if (!$id_module) { 
						$slashcore->show_fatal_error("UNKNOWN_MODULE_ERROR","MODULE ID NOT FOUND");
						exit();
					}
					
					/***********************/
					/* CLEAR USER TMP DIR */
					/***********************/
					$result_tmp = mysql_query("SELECT * FROM sl_attachments WHERE id_user=".$_SESSION["id_user"]." and state=0",$slashcore->db_handle) 
									or $slashcore->show_fatal_error("QUERY_ERROR",mysql_error());
					
					while ($row_tmp = mysql_fetch_array($result_tmp, MYSQL_BOTH)) {
						
						if (is_file("../tmp/".$row_tmp["filename"])) {
							unlink("../tmp/".$row_tmp["filename"]);
						}
						
					}
					
					$result = mysql_query("DELETE FROM sl_attachments WHERE id_user=".$_SESSION["id_user"]." and state=0",$slashcore->db_handle) 
									or $slashcore->show_fatal_error("QUERY_ERROR",mysql_error());
					
					/***************************/
					

					if ($obj_styles[$i]["max_upload"] == 0 || isset($obj_styles[$i]["max_upload"])==false) {
						$obj_styles[$i]["max_upload"] = 999;
					}
					
					if ($obj_id != 0){ 
						$result_nb = mysql_query("SELECT * FROM sl_attachments WHERE  id_module='".$id_module."' and id_element='".$obj_id ."' and id_field='".$i."' and state=1",$slashcore->db_handle) 
									or $slashcore->show_fatal_error("QUERY_ERROR",mysql_error());
						$start_count = mysql_num_rows($result_nb);
					}else{
						$start_count = 0;
					}
					
					
					echo "<table width='700' cellspacing='0' cellpadding='0' border='0' >
							<tr>
								<td align='left' valign='top' class='sl_mod_field_title' width='30%'>".$field_title." : </td>";
					echo "		<td align='left' width='70%'>";						
					echo "			<div id='sl_mod_upload_button_".$i."' class='upload_button'>".$slashcore->trad_word("UPLOAD")."</div>";
					echo "		</td></tr>
							<tr>
								<td align='left' width='30%'></td>
								<td align='left' width='70%'>";	
					echo "			<div id='sl_mod_upload_list_".$i."' class='sl_mod_upload_list'><div class='sl_mod_upload_loader'></div>&nbsp;&nbsp;Loading ...</div>";
					echo "	</td></tr></table>";
					
					echo "<script type='text/javascript'> 
				
						var upload_count_".$i."=".$start_count .";
				
						$(document).ready(function(){ 
						
						
						
						var button_".$i." = $('#sl_mod_upload_button_".$i."'), interval;
						var ajaxupload_".$i." = new AjaxUpload(button_".$i.",{
							action: '../core/plugins/ajaxupload/ajaxupload.php',
							name: 'sl_userfile',
							data: {
								sl_mod_field_id : ".$i.",
								sl_mod_upload_id : ".$obj_id.",
								sl_mod_upload_id_module: ".$id_module.",
								sl_mod_upload_files_dir: '".$obj_styles[$i]["files_dir"]."'
							},

							onSubmit : function(file, ext){
								
								
								
								// check extension
								if (! (ext && /^(".$obj_styles[$i]["accept"].")$/.test(ext))){
										
										alert('".$slashcore->trad_word("INVALID_EXT")." !');
										return false;
								}

								if (upload_count_".$i." >= ".$obj_styles[$i]["max_upload"].") {
									alert('".$slashcore->trad_word("MAX_UPLOAD_LIMIT")." !');
									return false;
								}
								
								button_".$i.".text('".$slashcore->trad_word("UPLOAD")."');
								this.disable();
								
								// Uploding -> Uploading. -> Uploading...
								interval = window.setInterval(function(){
									var text = button_".$i.".text();
									if (text.length < 13){
										button_".$i.".text(text + '.');					
									} else {
										button_".$i.".text('".$slashcore->trad_word("UPLOAD")."');				
									}
								}, 200);
								
							},
							
							onComplete: function(file, response){
								
								button_".$i.".text('".$slashcore->trad_word("UPLOAD")."');	
								window.clearInterval(interval);
								this.enable();

								if (response.indexOf('success')!=-1){
									upload_count_".$i."++;
								}else{
									alert('".$slashcore->trad_word("FILE_TRANFERT_FAIL")." : '+response);
								}
								
								// add file to the list
								$('#sl_mod_upload_list_".$i."').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
									sl_mod_field_id: ".$i.",
									sl_mod_upload_id: ".$obj_id.",
									sl_mod_upload_id_module: ".$id_module.",
									sl_mod_upload_files_dir: '".$obj_styles[$i]["files_dir"]."'
									});		
							}
						});
						
						$('#sl_mod_upload_list_".$i."').load('../core/plugins/ajaxupload/ajaxupload_list.php', { 
							sl_mod_field_id: ".$i.",
							sl_mod_upload_id: ".$obj_id.",
							sl_mod_upload_id_module: ".$id_module.",
							sl_mod_upload_files_dir: '".$obj_styles[$i]["files_dir"]."'
							});
						
						});
						
						</script>";

				break;
				
				//Show error field
				default:
				echo "No Field Style";
				
			
			}
			
			echo "</div>";
				
			echo "</td></tr>";
			
			
		}
				
		echo "</table>
		</td>
			</tr>
			</table>
			</form>";
			
			
			
		/* CHECK FIELD FUNCTION */
		
		echo "<script type='text/javascript'>
				function check_fields(){";
		
		for ($i=0;$i<count($obj_styles);$i++) {
				
				switch ($obj_styles[$i]["type"]){
					case "input":
						if (isset ($obj_styles[$i]["mandatory"]) &&  $obj_styles[$i]["mandatory"] == "1") { 
							echo "if(document.".$module_title."_add_form.".$module_title."_obj".$i.".value == '') { return false; }";
						}
					break;
					
				}
		}
			
		echo " return true;
			}
			</script>";
			
		echo $code;	

	}
	
	/* ------------------------------- */
	/*	BETA                           */
	/* ------------------------------- */
	
	
	public static function create_title($module_name,$module_title,$undertitle,$tp="default") {
		echo "<div class='sl_adm_modtitle'>";
		echo "<img src='modules/".$module_name."/views/".$tp."/images/".$module_name.".png' align='absmiddle' /> ";
		echo $module_title." >>> <span class='sl_adm_modundertitle'>".$undertitle."</span>";
		echo "</div>";
	}
	
	
	/*
	* Show top control
	* @param $module_name:string Module name
	* @param $controls:Array List of control
	*/
	public static function create_buttons($module_name,$controls) {
	
		$slash = &$GLOBALS["slash"];
	
		echo "<div class='sl_adm_modcontrols'>";
		echo "<table align='right'><tr>";
		
		for ($i=0;$i<count($controls);$i++) {
			switch($controls[$i]) {
				case "add":
					echo "<td align='center' width='20%'><a href='javascript:void(0);' class='add_button'
									onClick=\"javascript:submitForm('".$module_name."','add');\"></a>".$slash->trad_word("ADD")."</td>";
				break;
				case "edit":
					echo "<td align='center' width='20%'><a href='javascript:void(0);' class='edit_button'
									onClick=\"javascript:submitForm('".$module_name."','edit');\"></a>".$slash->trad_word("EDIT")."</td>";	
				break;
				case "publish":
					echo "<td align='center' width='20%'><a href='javascript:void(0);' class='publish_button'
									onClick=\"javascript:submitForm('".$module_name."','set_enabled');\"></a>".$slash->trad_word("ENABLED")."</td>";
				break;
				case "unpublish":
					echo "<td align='center' width='20%'><a href='javascript:void(0);' class='unpublish_button'
									onClick=\"javascript:submitForm('".$module_name."','set_disabled');\"></a>".$slash->trad_word("DISABLED")."</td>";
				break;
				case "del":
				case "delete":
					echo "<td align='center' width='20%'><a href='javascript:void(0);' class='delete_button'
									onClick=\"javascript:submitForm('".$module_name."','delete');\"></a>".$slash->trad_word("DELETE")."</td>";
				break;
				case "save":
					echo "<td align='center' width='20%'><a href='javascript:void(0);' class='apply_button' 
									onClick=\"javascript:submitForm('".$module_name."','add_apply');\"></a>".$slash->trad_word("SAVE")."</td>";
				break;
				case "back":
					echo "<td align='center' width='20%'><a href='index.php?mod=".$module_name."' class='undo_button'></a>
								".$slash->trad_word("BACK")."	</td>";
				break;
				
				
				default:
					echo "<td align='center' width='20%'>- NO CONTROL -</td>";
			
			}
		
		}
		
		echo "</tr></table>";
		echo "</div>";		
								
	}
	
	/*
	* Declare JScript
	* @param $url:string script URL
	*/
	public static function script($url){
		echo "<script type='text/javascript' src='".$url."'></script> \n";
	}
	
	/*
	* Declare Stylesheet
	* @param $url:string script URL
	*/
	public static function stylesheet($url,$media="all"){
		echo "<link rel='stylesheet' href='".$url."' type='text/css' media='".$media."'/> \n";
	}

}


/** 
* @} 
*/

?>