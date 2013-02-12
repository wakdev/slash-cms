<?php
/**
* @package		SLASH-CMS
* @subpackage	SEO_FUNCTIONS
* @internal     SEO functions
* @version		sl_seo.php - Version 10.7.6
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

class sl_seo {

	/**
	 * SEO text function
	 * @param $text:string Text
	 * @return seo text
	 */
	public function seo_text($text) {
		$str = strtolower($text);
		$str = strtr(
		strtr($str,
		'ְֱֲֳִֵַָֹÊֻּֽ־ֿׁׂ׃װױײ״ÙÚÛÜÝאבגדהוחטיךכלםמןסעףפץצרשתûü‎ÿ.',
		'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy-'),
		array('Þ' => 'TH', '‏' => 'th', '׀' => 'DH', 'נ' => 'dh', 'ß' => 'ss',
		'' => 'OE', '' => 'oe', 'ֶ' => 'AE', 'ז' => 'ae', 'µ' => 'u'));
		$str = preg_replace('/[^\w]/', '-', $str);
		$str = preg_replace('/[-]{2,}/', '-', $str);
		
		return strip_tags($str);
	}
	
	
	/*
	* Clear string
	*/
	
	public function clear_text($data){
		$data=strtr($data, "'" , "_");
		$data=strtr($data, "ְֱֲֳִֵאבגדהוׂ׃װױײ״עףפץצרָֹÊֻיטךכַחּֽ־ֿלםמןÙÚÛÜשתûüÿׁס" , "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn");
		
		return ($data);
	}
	

	/*
	* Sanitize string
	*/
	public function str_sanitize($str) {    
		$str = strtolower($str);
		$str = strtr(
		strtr($str,
		'ְֱֲֳִֵַָֹÊֻּֽ־ֿׁׂ׃װױײ״ÙÚÛÜÝאבגדהוחטיךכלםמןסעףפץצרשתûü‎ÿ.',
		'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy-'),
		array('Þ' => 'TH', '‏' => 'th', '׀' => 'DH', 'נ' => 'dh', 'ß' => 'ss',
		'' => 'OE', '' => 'oe', 'ֶ' => 'AE', 'ז' => 'ae', 'µ' => 'u'));
		$str = preg_replace('/[^\w]/', '-', $str);
		$str = preg_replace('/[-]{2,}/', '-', $str);
		return $str;    
	}
	
}

/** 
* @} 
*/

?>