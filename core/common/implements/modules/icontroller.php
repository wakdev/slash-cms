<?php
/**
* @package		SLASH-CMS
* @subpackage	implements
* @internal     interface module controller
* @version		icontroller.php - Version 12.3.01
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

interface iController{

	public function initialize(); //Initialize function 
	public function load_header(); //Load header function
	public function load_footer(); //Load footer function
	public function load(); //Load module function
	public function execute(); //Execute function
	
}

?>