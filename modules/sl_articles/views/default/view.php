<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_articles
* @internal     articles module
* @version		view.php - Version 11.5.30
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

* @addtogroup sl_articles
* @{

*/


class sl_articles_view extends slModel implements iView{
	

	/**
	 * Show HTML Header
	 */
	public function header () {
		
		sl_interface::script("core/plugins/jquery_plugins/interface/js/interface.js");

	}
	
	
	/**
	 * Show categories list
	 * @param $message message
	 */
	public function show_article($row_article) {
		
		echo "	<div class='pg_title'>  
					<div class='ct_title_left'></div>
					<div class='tp_title_center'>".$row_article["title"]."</div>
					<div class='ct_title_right'></div>
					<div class='ct_sstitle'></div>
				</div>
			";
		
		echo "<div class='tp_page_top'></div><div class='tp_page'>".$row_article["content"]."</div><div class='tp_page_bottom'></div>";
		
		if (count($row_article["attachments"]) > 0) {
			$url = "medias/attachments/sl_articles/".$row_article["id"]."/".$row_article["attachments"][0]["filename"];
			echo sl_images::show_image($url);
		}
	
		
	}

		
	/**
	 * Show 404 page
	 * @param $message message
	 */
	public function show_404() {
		echo "	<div class='ct_title'>  
					<div class='ct_title_left'></div>
					<div class='ct_title_center'>404 : Page not found</div>
					<div class='ct_title_right'></div>
					<div class='ct_sstitle'></div>
				</div>
			";
		
		echo "<div class='ct_page_top'></div><div class='ct_page'>The page you're looking for can't be found.</div><div class='ct_page_bottom'></div>";
	}
	
	/**
	 * HTML footer
	 */
	public function footer() {
	
	
	/*
		echo "
		<script type='text/javascript'> 
 
 		 	$(document).ready(function(){ 
				alert('test');
		
			}); 		
		</script>";*/
	
	}
	
	
	
	
}


/** 
* @} 
*/


?>