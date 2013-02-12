<?php
/**
* @package		SLASH-CMS
* @subpackage	IMAGES_FUNCTIONS
* @internal     Images functions
* @version		images.php - Version 11.12.15
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


class sl_images {

	/**
	 * Check max and min size image
	 * @param $source:
	 */
	public function check_image_spec($source, $max_weight=0,$min_width=0,$min_height=0,$max_width=0,$max_height=0) {
		if (file_exists($source)) {
			
			 $i_size = getimagesize($source);
			 
			 if (filesize($source) > ($max_weight * 1024) ) { return - 1; }
			 if ($i_size[0] < $min_width) { return - 2; } 
			 if ($i_size[0] > $max_width ) { return - 3; }
			 if ($i_size[1] < $min_height ){ return - 4; }
			 if ($i_size[1] > $max_height ) { return - 5; }
			 
			 
		}else{
			return 0;	
		}
		
		return 1;
	}


	/**
	 * Resize image to destination directory
	 */
	public function create_image ($source,$destination,$width="auto",$height="auto",$format="auto",$quality=100,$show=false) {
		
		if (file_exists($source)) {
			
			$i_size = getimagesize($source);
			 
			switch ($i_size[2]) { 
				
				case 1: //gif
					$img_src=imagecreatefromgif($source);
				break;
				
				case 2: // jpg and jpeg
					$img_src=imagecreatefromjpeg($source);
				break;
				
				case 3: // png
					$img_src=imagecreatefrompng($source);
				break;
				
				default:
					return 0;
			}
		
			if ($width == "auto" && $height== "auto") {
				$img_dest=imagecreatetruecolor($i_size[0],$i_size[1]); 
				ImageCopyResampled($img_dest, $img_src, 0, 0, 0, 0,$i_size[0], $i_size[1], $i_size[0], $i_size[1]);
			}
			
			if ($width != "auto" || $height != "auto" ) {
				
				
				if ($i_size[0]>$width || $i_size[1]>$height) {
					  $ratio_l=$width/$i_size[0];
					  $ratio_h=$height/$i_size[1];
					  if ($ratio_h > $ratio_l) { $ratio = $ratio_l; } else { $ratio = $ratio_h; }
				} else {
					$ratio = 1;
				}
				

				//$img_dest=imagecreatetruecolor($width,$height);	
				$img_dest=imagecreatetruecolor(round($i_size[0]*$ratio),round($i_size[1]*$ratio));
				ImageCopyResampled($img_dest, $img_src, 0, 0, 0, 0,round($i_size[0]*$ratio), round($i_size[1]*$ratio), $i_size[0], $i_size[1]);
			}
			
			if ($format=="auto"){
				$format=$i_size[2];
			}
			
					
			switch ($format) { 
				
				case 1: //gif
					if (!imagegif($img_dest,$destination)) {
						return 0;
					}
				break;
				
				case 2: // jpg and jpeg
					if (!imagejpeg($img_dest,$destination,$quality)) {
						return 0;
					}
				break;
				
				case 3: // png
				
					if ($quality == 100) {
						if (!imagepng($img_dest,$destination,0)) {
							return 0;
						}
					}else{
						if (!imagepng($img_dest,$destination,$quality)) {
							return 0;
						}
					}
				break;
				
				default:
					return 0;
			}
				
		}else{
			return 0;	
		}
		
		return 1;
	}
	
	
	/**
	* Show resized image
	* @param $max_width:Int Max width
	* @param $max_height:Int Max height
	*/
	public static function show_image($url,$max_width=9999,$max_height=9999){
	
		// Currrent path : Admin
		if (file_exists("../core/common/class/functions/includes/show_image.php")){ 
			$url = "../../../../".$url; 
			return "<img src='../core/common/class/functions/includes/show_image.php?url=".$url."&width=".$max_width."&height=".$max_height."' />";
		}
		
		// Currrent path : Front
		if (file_exists("core/common/class/functions/includes/show_image.php")){ //Front
			$url = "../../../../../".$url; 
			return "<img src='core/common/class/functions/includes/show_image.php?url=".$url."&width=".$max_width."&height=".$max_height."' />";
		}
			
	}
	
	
}

/** 
* @} 
*/

?>