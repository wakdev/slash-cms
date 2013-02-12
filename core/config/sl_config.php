<?php
/**
* @package		SLASH-CMS
* @subpackage	CONFIG
* @internal     Slash cms configuration file
* @version		sl_config.php - Version 12.3.1
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

class SLConfig {
	
	/**
	* DATABASE PROPERTIES
	*/
		
	public $db_type = "MySQL"; // MySQL ou PDO
	public $db_host = "localhost"; //Database host
	public $db_name = "db_slashcms"; //Database name
	public $db_user = "root"; //Database user
	public $db_password = ""; //Database password
	public $db_prefix = "sl_"; //Database prefix
	
	
	// Cache path (relative from document root)
	public $use_cache = true;
	public $cache_path = "/cache/";
	
	/* 
	@todo => Move var in database 
	var $site_name = "SLASH CMS";
	var $site_url = "http://www.wakdev.com/";
	
	var $default_title = "my title";
	var $default_description = "SLASH CMS";
	var $default_keywords = "SLASH CMS";
	*/	
}
?>