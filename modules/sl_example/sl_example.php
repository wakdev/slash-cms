 <?php
/**
* @package		SLASH-CMS
* @subpackage	sl_example
* @internal     front example module
* @version		sl_example.php - Version YEAR.MONTH.DAY (ex : 9.12.20)
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
*
* Important : 
*	- Pour que le module fonctionne, vous devez définir des méthodes spécifiques (voir plus bas)
*	- Le module ce compose en 2 fichiers, le fichier de traitement (ici même), 
*		et le fichier d'affichage (sl_example_view)
*	- Pour qu'un module fonctionne, il doit être déclaré dans la base de données (table sl_modules).
*/


// Include de la partie "view" qui gère la partie affichage du module,
include ("views/default/sl_example_view.php");

class sl_example extends sl_example_view implements iController {


/* ### PARTIE OBLIGATOIRE ET NECESSAIRE AU BON FONCTIONNEMENT ### */

	/* --- Variables nécessaires --- */
	public $slash; //Vous permet d'avoir accès à la référence du noyau 
	public $params; //Permet le stockage des paramètres de configuration liés au module.
	public $module_name = "sl_example"; //Nom du module
	public $module_id; //Id du module
	
	
	/* --- Fonctions nécessaires --- */
	/* Le noyau Slash fera appel à ces fonctions */
	
	/**
	* Contructeur de la classe, permet l'initialisation du module
	*/
	function __construct(&$core_class_ref,$module_id) {
       $this->slash = $core_class_ref;
       $this->module_id = $module_id;
	}
	
	
	/**
	* Fonction d'initialisation (uniquement dans les cas spécifiques)
	* Executée en 1er sur tout les modules présent et actif dans la base de données,
	* Permet ainsi d'executer le module, même si celui-ci n'est pas chargé
	* cela permet entre autre de définir les headers des modules "permanents" 
	* (ex: le module de sécurité qui vérifie que les sessions de connexion)
	*/
	public function initialize() {
		//no global initialisation for this module
	}

	/**
	* Fonction d'execution (uniquement dans les cas spécifiques)
	* Executée en fin de script (footer), même principe que la fonction d'initialisation
	*/
	public function execute() {
		
	}
	
	/* ## Les fonctions suivantes permettent l'execution du module lorsque celui-ci est appelé 	## */
	/* ## par l'url suivante : index.php?mod=sl_example 										## */
	
	/**
	* Fonction de chargement du header
	* Charge le header
	*/
	public function load_header(){
		$this->header(); //on affiche le header (voir fonction d'affichage sl_example_view.php)
	}
	
	
	/**
	* Fonction de chargement du module
	* Permet d'afficher le contenu du module
	*/
	public function load() {
		// Traitement des données
		$datas = $this->load_data($_GET['id']); //Appel d'une fonction personnalisée
		$this->show_page($datas); // Appel d'une fonction personnalisée d'affichage (voir sl_example_view.php)
	}
	
	/**
	 * Fonction de chargement du footer (juste avant le "</body>" ) 
	 */
	public function load_footer(){
		$this->footer(); //on affiche le footer (voir fonction d'affichage sl_example_view.php)
	}
	
	
	
/* ### FIN DE LA PARTIE OBLIGATOIRE ET NECESSAIRE ### */	
	
	/* --- Vos fonctions personnalisée --- */
	

	/**
	 * Fonction de récupération des données
	 */
	protected function load_data($id) {
		if ($id) {
			$result = mysql_query("SELECT * FROM ".$this->slash->database_prefix."example WHERE id=".$id,$this->slash->db_handle) 
						or $this->slash->show_fatal_error("QUERY_ERROR",mysql_error());
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			return $row;
		} else {
			return 0;
		}
	}
	

}

?>
