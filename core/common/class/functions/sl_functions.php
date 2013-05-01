<?php
/**
* @package		SLASH-CMS
* @subpackage	SL_FUNCTIONS
* @internal     Slash core functions
* @version		sl_functions.php - Version 10.7.6
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
* @file
* @name sl_functions
* @defgroup sl_functions functions
* Slash functions
* @{
*/




/**
 * Autoload functions class
 * @param string $class
 */
function sl_functions_autoloader($class) {
	$inc_url = "includes/".strtolower($class).".php";
	if (file_exists(dirname(__FILE__)."/".$inc_url)){
		include ($inc_url);
	}
}

spl_autoload_register('sl_functions_autoloader');


/** 
* @} 
*/

?>