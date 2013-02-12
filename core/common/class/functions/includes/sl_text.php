<?php
/**
* @package		SLASH-CMS
* @subpackage	TEXT_FUNCTIONS
* @internal     Text functions
* @version		text.php - Version 9.10.21
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

class sl_text {

	/**
	 * Substring text function
	 * @param $text:string Text
	 * @param $width:int Width
	 * @param $etc:bool Add ... or not
	 * @return substr text
	 */
	public function substring($text,$width,$etc=false) {
		
		if (strlen($text)>$width) {
			$text = substr($text, 0, $width); 
		}
		if ($etc) { $text.="..."; }
		return $text;
	}

	/**
	 * Substring text function 2 (not cut complete word)
	 * @param $text:string Text
	 * @param $width:int Width
	 * @param $etc:bool Add ... or not
	 * @return substr text
	 */
	public function substring_word($text,$width,$etc=false) {
		if (strlen($text)>$width) {
			$text = substr($text, 0, $width)."..."; 
			$t = explode(" ",$text);
			$i = 0;
			$ret = "";
			while (!empty($t[$i+1])) {
				$ret .= " ".$t[$i];
				$i++;
			}
		} else {
			$ret = $text;
		}
		if ($etc) { $ret.="..."; }
		return $ret;
	}
	
	
}

/** 
* @} 
*/

?>