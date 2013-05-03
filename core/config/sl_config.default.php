<?php
/**
* @package SLASH-CMS
* @subpackage CONFIG
* @internal Slash cms configuration file
* @version sl_config.php - Version 13.05.02
* @author Julien Veuillet [http://www.wakdev.com]
* @copyright Copyright(C) 2009 - Today. All rights reserved.
* @license GNU/GPL

This program is free software : you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.

*/

class SLConfig {

/**
* DATABASE PROPERTIES
*/

public $db_type = "_bdd_type"; // MySQLi / MySQL / PDO
public $db_host = "_bdd_host"; //Database host
public $db_port = "_bdd_port"; //Database port
public $db_name = "_bdd_name"; //Database name
public $db_user = "_bdd_user"; //Database user
public $db_password = "_bdd_pwd"; //Database password
public $db_prefix = "_bdd_prefix"; //Database prefix


// Cache path (relative from document root)
public $use_cache = true;
public $cache_path = "_cache_path";

public $site_path = "_site_path"; //For exemple : "/slashcms/"

public $error_level = SL_ERROR; //SL_DEBUG (development) | SL_INFO | SL_ERROR (production)

//Logs
public $logs = true;
public $logs_rotation = "week"; //week, day, hour, month
/*
@todo => Move var in database
var $site_name = "_site_name";
var $site_url = "_site_url";
var $default_title = "my title";
var $default_description = "SLASH CMS";
var $default_keywords = "SLASH CMS";
*/	
}
?>