<?php
/**
* @package		SLASH-CMS
* @subpackage	FILES_FUNCTIONS
* @internal     files functions
* @version		files.php - Version 9.12.1
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

class sl_files {

	/**
	 * Check file extension
	 * @param $filetype:string Extension (ex: jpg)
	 * @param $mimes:array Extensions valid Array (ex: array("jpg","gif","txt")
	 * @return True or False
	 */
	public function check_files_extension($file_type,$mimes) {
		return in_array(strtolower($file_type), array_map('strtolower',$mimes));
	}
	
	/**
	 * Get file extension
	 * @param $filename:string url or filename
	 * @return File extension
	 */
	public function get_file_extension($filename){
		return substr($filename, strrpos($filename, '.'));
	}
	
	/**
	* Move file
	* @param $file:string Path of file (ex:/tmp/example.txt)
	* @param $destination:string Path of destionation (ex:/media/files/example.txt)
	* @return True or False
	*/
	public function move_files($file,$destination) {
		if (copy($file,$destination)) {
			return unlink($file);
		}else{
			return false;
		}
	}
	
	/**
	* Make dir
	* @param $destination:string Path of directory (ex:/media/files/mynewdir)
	* @return True or False
	*/
	public function make_dir($destination,$recursive=false) {
		
		if (!$recursive) {
			if (!file_exists($destination)) {
				return mkdir($destination,0777);
			}else{
				return true;
			}
		}else{
		
			$dirs = explode('/', $destination);
			$dir = '';
			foreach ($dirs as $part) {
				$dir.=$part.'/';
				if (!is_dir($dir) && strlen($dir)>0)
				mkdir($dir, 0777);
			}
			return true;

		}
		
	}
	
	/**
	* Remove directory and subdirectory and files
	* @param $destination:string Path of directory (ex:/media/files/mydir)
	* @return True or False
	*/
	public function remove_dir($destination) {
		if(@ ! $opendir = opendir($destination)) {
			return false;
		}
		while(false !== ($readdir = readdir($opendir))) {
			if($readdir !== '..' && $readdir !== '.') {
				$readdir = trim($readdir);
				if(is_file($destination.'/'.$readdir)) {
					if(@ ! unlink($destination.'/'.$readdir)) {
						return false;
					}
				}elseif(is_dir($destination.'/'.$readdir)){
					if(! $this->remove_dir($destination.'/'.$readdir)) {
						return false;
					}
				}
			}
		}
		closedir($opendir);
		if(@ ! rmdir($destination)) {
			return false;
		}
		return true;
	}
	
	
	/**
	* Format name for save file
	* @param $name:string Name of file (ex:myfile.txt)
	* @return Formated name
	*/
	public function format_name($name) {
		$data=strtr($name, "'" , "_");
		$data= utf8_decode($data);
		$data=strtr($data, "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËéèêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ" , "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn");
		return $data;
	}
	
	/**
	* Recursive copy
	* @param $path:string Source
	* @param $dest:string Destination
	* @return True or False
	*/
	public function recursive_copy($path, $dest) {
		if( is_dir($path) ) {
            @mkdir( $dest );
            $objects = scandir($path);
            if( sizeof($objects) > 0 ) {
                foreach( $objects as $file ) {
                    if( $file == "." || $file == ".." ){ continue; }
                    if( is_dir( $path.DIRECTORY_SEPARATOR.$file ) ) {
                        $this->recurse_copy( $path.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file );
                    }else{
                        copy( $path.DIRECTORY_SEPARATOR.$file, $dest.DIRECTORY_SEPARATOR.$file );
                    }
                }
            }
            return true;
        }elseif( is_file($path) ){
            return copy($path, $dest);
        }else{
            return false;
        }
	} 
	
	/**
	* Upload file
	* NOT COMPLETE !!!!!!!!!!!!
	*/
	/*
	public function upload_files() {
		for ($i=0;$i<count($_FILES["sl_upload_list"]);$i++) {
		
			if (isset($_FILES['sl_upload_list']['tmp_name'][$i]) 
				&& $_FILES['sl_upload_list']['tmp_name'][$i] != "") {
			
			}
		}
		
		echo $_FILES["sl_upload_list"]["tmp_name"][$i];
	}
	*/
	
}

/** 
* @} 
*/

?>