<?php
//Include config
//Global includes
include ("../../../../common/constants/sl_constants.php"); //Defines
include "../../../../config/sl_config.php";
$sl_config = new SLConfig;

/**
 * Resize image
 */
function resize_image ($source,$width="auto",$height="auto",$format="auto",$quality=100) {
		
		$i_size = getimagesize($source);
		 
		switch ($i_size[2]) { 
			
			case 1: //gif
				header('Content-type: ' .image_type_to_mime_type(IMAGETYPE_GIF));
				$img_src=imagecreatefromgif($source);
			break;
			
			case 2: // jpg and jpeg
				header('Content-type: ' .image_type_to_mime_type(IMAGETYPE_JPEG));
				$img_src=imagecreatefromjpeg($source);
			break;
			
			case 3: // png
				header('Content-type: ' .image_type_to_mime_type(IMAGETYPE_PNG));
				$img_src=imagecreatefrompng($source);
			break;
			
			default:
				return 0;
		}
	
		
		if ($width == "auto" && $height== "auto") {
			$f_size = $i_size;
			$img_dest=imagecreatetruecolor($i_size[0],$i_size[1]); 
			ImageCopyResampled($img_dest, $img_src, 0, 0, 0, 0,$i_size[0], $i_size[1], $i_size[0], $i_size[1]);
		}
		if($width != "auto" || $height != "auto"){
			if ($i_size[0]>$width || $i_size[1]>$height) {
			if($width == "auto") $ratio = $height/$i_size[1];
				else $ratio = $width/$i_size[0];
			} else {
				$ratio = 1;
			}
			$f_size[0] = round($i_size[0]*$ratio);
			$f_size[1] = round($i_size[1]*$ratio);
			
			$img_dest=imagecreatetruecolor($f_size[0],$f_size[1]);
			ImageCopyResampled($img_dest, $img_src, 0, 0, 0, 0,$f_size[0], $f_size[1], $i_size[0], $i_size[1]);
		}
		
		
		
		
		if ($format=="auto"){
			$format=$i_size[2];
		}
		
		
				
		switch ($format) { 
			
			case 1: //gif
				if (!imagegif($img_dest)) {
					return 0;
				}else{
					imagedestroy($img_dest);
				}
			break;
			
			case 2: // jpg and jpeg
				if (!imagejpeg($img_dest,null,$quality)) {
					return 0;
				}else{
					imagedestroy($img_dest);
				}
			break;
			
			case 3: // png
				if ($quality == 100) {
						if (!imagepng($img_dest,null,0)) {
							return 0;
						}else{
							imagedestroy($img_dest);
						}
				}else{
						if (!imagepng($img_dest,null,$quality)) {
							return 0;
						}else{
							imagedestroy($img_dest);
						}
				}
			break;
			
			default:
				return 0;
		}
			
	
	return 1;
}



/**
 * Display an image resized to specified dimensions.
 * Save it to the cache if doesn't exist yet
 * 
 */
function resize_image_or_cache ($source,$width="auto",$height="auto",$format="auto",$quality=100, $force = false) {
		global $sl_config;
		$cache_root = $_SERVER['DOCUMENT_ROOT'].$sl_config->cache_path;
		
		$i_size = getimagesize($source);

		switch ($i_size[2]) { 
			
			case 1: //gif
				header('Content-type: ' .image_type_to_mime_type(IMAGETYPE_GIF));
				$img_src=imagecreatefromgif($source);
			break;
			
			case 2: // jpg and jpeg
				header('Content-type: ' .image_type_to_mime_type(IMAGETYPE_JPEG));
				$img_src=imagecreatefromjpeg($source);
			break;
			
			case 3: // png
				header('Content-type: ' .image_type_to_mime_type(IMAGETYPE_PNG));
				$img_src=imagecreatefrompng($source);
			break;
			
			default:
				return 0;
		}

		// Final dimensions
		if ($width == "auto" && $height== "auto") {
			$f_size = $i_size;
		}
		if($width != "auto" || $height != "auto"){
			if ($i_size[0]>$width || $i_size[1]>$height) {
			if($width == "auto") $ratio = $height/$i_size[1];
				else $ratio = $width/$i_size[0];
			} else {
				$ratio = 1;
			}
			$f_size[0] = round($i_size[0]*$ratio);
			$f_size[1] = round($i_size[1]*$ratio);
		}

		// Build the cached image path string
		$cache = preg_replace('/(\.\.\/)+(.*)\.\w+/',"$2",$source);
		$cache .= "_".$f_size[0]."_".$f_size[1].".jpg";
		$cache_root.str_replace(basename($cache), "", $cache);
		$cache_root.str_replace(basename($cache), "", $cache);

		// The image not found in the cache or force is specified
		if(!file_exists($cache_root.$cache) || $force){
			
			if ($width == "auto" && $height== "auto") {
				$img_dest=imagecreatetruecolor($i_size[0],$i_size[1]); 
				ImageCopyResampled($img_dest, $img_src, 0, 0, 0, 0,$i_size[0], $i_size[1], $i_size[0], $i_size[1]);
			}
			
			if ($width != "auto" || $height != "auto" ) {

				//$img_dest=imagecreatetruecolor($width,$height);	
				$img_dest=imagecreatetruecolor($f_size[0],$f_size[1]);
				ImageCopyResampled($img_dest, $img_src, 0, 0, 0, 0,$f_size[0], $f_size[1], $i_size[0], $i_size[1]);
			}
			
			if ($format=="auto"){
				$format=$i_size[2];
			}

			// Create the folders structure
			if(!file_exists($cache_root.str_replace(basename($cache), "", $cache))) mkdir($cache_root.str_replace(basename($cache), "", $cache),0777,true);
			switch ($format) { 
				
				case 1: //gif
					if (!imagegif($img_dest,$cache_root.$cache)) {
						return 0;
					}else{
						imagedestroy($img_dest);
					}
				break;
				
				case 2: // jpg and jpeg
					if (!imagejpeg($img_dest,$cache_root.$cache,$quality)) {
						return 0;
					}else{
						imagedestroy($img_dest);
					}
				break;
				
				case 3: // png
					if ($quality == 100) {
							if (!imagepng($img_dest,$cache_root.$cache,0)) {
								return 0;
							}else{
								imagedestroy($img_dest);
							}
					}else{
							if (!imagepng($img_dest,$cache_root.$cache,$quality)) {
								return 0;
							}else{
								imagedestroy($img_dest);
							}
					}
				break;
				
				default:
					return 0;
			}
	}
			
	echo file_get_contents($cache_root.$cache);
	return 1;
}






// --- MAIN --- //
$url;
$width;
$height;

if (isset($_GET["url"]) && $_GET["url"] != ""){$url = $_GET["url"];}
if (isset($_GET["width"]) && $_GET["width"] != ""){$width = $_GET["width"];} else { $width="auto"; }
if (isset($_GET["height"]) && $_GET["height"] != ""){$height = $_GET["height"];} else { $height="auto"; }

if (file_exists($url)) {

	if ($sl_config->use_cache == true){
		resize_image_or_cache($url,$width,$height);
	}else{
		resize_image($url,$width,$height);
	}
}
?>