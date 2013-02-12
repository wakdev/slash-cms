<?php
/**
* @package		SLASH-CMS
* @subpackage	CHECKING_FUNCTIONS
* @internal     Checking functions
* @version		filters.php - Version 10.2.10
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

class sl_filters {

	/**
	 * Check time format
	 * @param $time:string Time
	 * @return True or False
	 */
	public function is_time($time) {
		
		$ret = true;
		
		switch (strlen($time)) {
		
			case 5: //Format 00:00
				$t = explode(":",$time);
								
				if ( intval($t[0]) < 0 || intval($t[0]) > 23 || intval($t[1]) < 0 || intval($t[1]) > 59) {
					$ret = false;
				}
				
			break;
			
			case 8: //Format 00:00:00
			
				$t = explode(":",$time);
								
				if ( intval($t[0]) < 0 || intval($t[0]) > 23 || intval($t[1]) < 0 || intval($t[1]) > 59 || intval($t[2]) < 0 || intval($t[2]) > 59 ) {
					$ret = false;	
				}
			
			break;
			
			default:
				$ret = false;
		}
		
		return $ret;
	}

	/**
	 * Compare date
	 * @param $first:date First date
	 * @param $first:date First date
	 * @return True if date passed or return false
	 */
	public function date_compare($first,$second) {
	
		if (strtotime($first) <  strtotime($second) ) {
			return true;
		}else{
			return false;
		}
		
	}
	
	/**
	* Check Mail
	* @param $mail:mail string
	* @return True or false
	*/
	public function check_mail($mail) 
	{ 
		$syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
		if(preg_match($syntaxe,$mail)) 
			return true; 
		else 
			return false; 
	}
	
	/**
	* Parse Url Query
	* @param $url:Url
	* @return Array
	*/
	public function parse_url_query($url) {
		
		$url = parse_url($url); 
		
		if (isset($url["query"])) {
		
			$queryParts = explode('&', $url["query"]);
	   
			$params = array();
			foreach ($queryParts as $param) {
				$item = explode('=', $param);
				$params[$item[0]] = $item[1];
			}
	   
			return $params; 
		}else{
			return null;
		}
	}
	
}

/** 
* @} 
*/

?>