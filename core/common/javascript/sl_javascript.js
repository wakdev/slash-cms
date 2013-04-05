/**
* @package		SLASH-CMS
* @subpackage	JAVASCRIPT_FUNCTIONS
* @internal     Javascript functions
* @version		sl_javascript.js - Version 10.1.13
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




/**
* Global Variables
*/
var tabs; // For Tabs



/**
* Form submit functions
* @param module Module name
* @param action Form action
*/
function submitForm(module,action,id) {


//Module with Tab (Save Active Tab)
if (module == "sla_gallery" &&  document.sla_gallery_nav_form ) {
	eval("document.sla_gallery_nav_form.sla_gallery_tab.value="+ (tabs.tabs('option', 'selected')) );	
}	

if (module == "sla_menu_config" &&  document.sla_menu_config_nav_form ) {
	eval("document.sla_menu_config_nav_form.sla_menu_config_tab.value="+ (tabs.tabs('option', 'selected')) );	
}	

/* ------------------------ */

if (action.substr(0,5) == "order") {

	var orderby = "";
	var sort = "";
	
	if (action.lastIndexOf("asc") != -1 && (action.length - action.lastIndexOf("asc")) == 3) {
		sort = "asc";
		orderby = action.substr(6,(action.lastIndexOf("asc")-7));
	} 
	
	if (action.lastIndexOf("desc") != -1 && (action.length - action.lastIndexOf("desc")) == 4) {
		sort = "desc";
		orderby = action.substr(6,(action.lastIndexOf("desc")-7));
	}

	action = "order";

}

/* ------------------------ */

 		

	switch (action){
		
		/* -------------*/
		/* Basic Action */
		/* -------------*/
		case "add" :
		case "edit":
		case "delete":
		case "print":
		case "set_enabled":
		case "set_disabled":
			eval("document."+module+"_nav_form."+module+"_act.value='"+action+"'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		/* ------------*/
		/* Sort Action */
		/* ------------*/
		case "nbbypage" :
			eval("document."+module+"_nav_form."+module+"_nbbypage.value=document."+module+"_nav_form.nbbypage.options[document."+module+"_nav_form.nbbypage.selectedIndex].value");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		/* ------------------------ */
		
		case "order":
			eval("document."+module+"_nav_form."+module+"_orderby.value='"+orderby+"'");
			eval("document."+module+"_nav_form."+module+"_sort.value='"+sort+"'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		
		break;
		
		case "search":
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		case "reset":
			eval("document."+module+"_nav_form."+module+"_search.value='#'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		/* Ascending compatibility */
		case "select_cat" :
			eval("document."+module+"_nav_form."+module+"_categorie.value=document."+module+"_nav_form.categorie.options[document."+module+"_nav_form.categorie.selectedIndex].value");
			eval("document."+module+"_nav_form."+module+"_page.value=1");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		/* ------------------------- */
		
		case "select_cat1" :
			eval("document."+module+"_nav_form."+module+"_categorie1.value=document."+module+"_nav_form.categorie1.options[document."+module+"_nav_form.categorie1.selectedIndex].value");
			eval("document."+module+"_nav_form."+module+"_page.value=1");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		case "select_cat2" :
			eval("document."+module+"_nav_form."+module+"_categorie2.value=document."+module+"_nav_form.categorie2.options[document."+module+"_nav_form.categorie2.selectedIndex].value");
			eval("document."+module+"_nav_form."+module+"_page.value=1");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		case "select_cat3" :
			eval("document."+module+"_nav_form."+module+"_categorie3.value=document."+module+"_nav_form.categorie3.options[document."+module+"_nav_form.categorie3.selectedIndex].value");
			eval("document."+module+"_nav_form."+module+"_page.value=1");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		case "select_cat4" :
			eval("document."+module+"_nav_form."+module+"_categorie4.value=document."+module+"_nav_form.categorie4.options[document."+module+"_nav_form.categorie4.selectedIndex].value");
			eval("document."+module+"_nav_form."+module+"_page.value=1");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		/* ------------*/
		/* Item Action */
		/* ------------*/
		case "single_edit":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='edit'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
	
		break;
		
		case "single_delete":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='delete'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
	
		break;
		
		case "single_print":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='print'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		case "single_show":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='show_item'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		case "single_set_enabled":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='set_enabled'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
	
		break;
		
		case "single_set_home":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='set_home'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		case "single_set_disabled":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='set_disabled'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
	
		break;
		
		case "single_set_up":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='set_up'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		case "single_set_down":
			unCheckAll(module);
			eval("document."+module+"_nav_form."+module+"_act.value='set_down'");
			eval("document."+module+"_nav_form."+module+"_page.value="+(pager_i + 1));
			eval("document."+module+"_nav_form['"+module+"_checked["+id+"]'].checked = true");
			obj_form = eval("document."+module+"_nav_form");
			obj_form.submit();
		break;
		
		
		/* ------------*/
		/* Form Action */
		/* ------------*/
		
		
		/* Ascending compatibility */
		case "add_apply":
			obj_form = eval("document."+module+"_add_form");
			obj_form.submit();
		break;
		
		case "del_apply":
			obj_form = eval("document."+module+"_del_form");
			obj_form.submit();
	
		break;
		/* ------------------------- */
		
		
		/* - Action - */
		case "enabled_apply":
			eval("document."+module+"_form."+module+"_act.value='set_enabled'");
			obj_form = eval("document."+module+"_form");
			obj_form.submit();
		break;
		
		case "disabled_apply":
			eval("document."+module+"_form."+module+"_act.value='set_disabled'");
			obj_form = eval("document."+module+"_form");
			obj_form.submit();
		break;
		
		case "delete_apply":
			eval("document."+module+"_form."+module+"_act.value='delete'");
			obj_form = eval("document."+module+"_form");
			obj_form.submit();
		break;
		
		case "print_apply":
			window.print();
		break;
		
		/* --- */
		
		
		default:
		
	}


	
	return false;
}


/**
 * Uncheck
 */
function unCheckAll(module) {
		
		var elements = eval("document."+module+"_nav_form.getElementsByTagName('input')");
		for ( var i=0; i < elements.length; i++){
                         elements[i].checked =  false;
           }
	return false;    	
}

/**
 * Check all
 * @param module
 * @param nbbypage
 * @returns {Boolean}
 */
function checkAll(module,nbbypage) {
		
		var elements = eval("document."+module+"_nav_form.getElementsByTagName('input')");
		
		var start = ( (pager_i + 1)  * nbbypage) - nbbypage ;
		var end = ( (pager_i + 1)  * nbbypage);
		
		for (var i = start; i<end ; i++) {
			if (document.getElementById(module+"_checked["+i+"]") ) {
				document.getElementById(module+"_checked["+i+"]").checked = true;
			}
		}
	return false;
}


