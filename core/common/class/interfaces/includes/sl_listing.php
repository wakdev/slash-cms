<?php
/**
* @package		SLASH-CMS
* @subpackage	INTERFACES
* @internal     Listing
* @version		sl_listing.php
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

class sl_listing {
	
	private $id; //Listing ID
	private $_html; //HTML
	private $data; //Listing data
	
	
	/**
	 * Constructor
	 * @param string $id Listing ID
	 */
	function __construct($id=null){
		//nothing
	}
	
	
	/**
	 * Render HTML
	 */
	public function render(){
		echo $this->_html;
	}
	
	/**
	 * Set listing ID
	 * @param string $id Listing ID
	 */
	public function setID($id=null){
		if ($id!==null){
			$this->id = $id;	
		}
	}
	
	/**
	 * Get listing ID
	 */
	public function getID(){
		if (!empty($this->id)){
			return $this->id;
		}else{
			return null;
		}
	}
	
	/**
	 * Load items
	 * @param array $data Listing data
	 */
	public function load($data=null){
		
	}
	
	
	/**
	 * Set listing params with Array
	 * @param array $arr Params array
	 */
	public function setParamArray($arr=null){
		
	}
	
	/**
	 * Set one param
	 * @param string $name Name
	 * @param string $value Value
	 */
	public function setParam($name=null,$value=null){
		
	}
	
	
	/**
	 * Get current listing filters
	 */
	public function getItemsFilters(){
		
	}
	
	/**
	 * Get current listing limit
	 */
	public function getItemsLimit(){
		
	}
	
	/**
	 * Clear Listing session
	 */
	public function clearSession(){
		
	}
	
	
}




?>