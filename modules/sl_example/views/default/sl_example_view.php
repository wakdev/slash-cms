<?php
/**
* @package		SLASH-CMS
* @subpackage	sl_example
* @internal     front example module
* @version		sl_example_view.php - - Version YEAR.MONTH.DAY (ex : 9.12.20)
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

/**
* Ce fichier est un exemple pour la création d'un module front.
* Cette partie represente la partie affichage du module.
*/

class sl_detours_pages_view implements iView{


	/**
	* Fonction d'affichage du header (généralement les scripts et css)
	* A noter : Certains scripts son déjà inclus de base (Jquery.js), 
	* Il n'est donc pas nécessaire de les redéclarer
	*/
	protected function header () {
		echo "<script type='text/javascript' src='core/plugins/jquery_plugins/interface/js/interface.js'></script> \n";
	}
	
	
	/**
	* Fonction d'affichage du module
	*/
	protected function show_page($datas) {
		echo "<div id='my_content'>";
		// Trad word permet d'afficher la traduction du mot title dans la langue configuré.
		echo $this->slash->trad_word("TITLE")." : ".$datas["my_title"];
		echo "</div>";
	}

	/**
	* Fonction d'affichage du footer (généralement l'execution des scripts)
	*/
	protected function footer() {

		echo "	<script type='text/javascript'> 
					$(document).ready(function(){ 
						alert('Slash cms is Cool !');
					}); 	
				</script>";
	}

}

?>