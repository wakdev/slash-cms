<?php
/**
* @package		SLASH-CMS
* @subpackage	SLSETUP
* @internal     Slash core system
* @version		default.php - Version 13.04.24
* @author		Loïc Bajard
* @copyright	Copyright(C) 2013 - Today. All rights reserved.
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

class DefaultView{
	
	function __construct(){
		
	}
	private function loadHeader($state){
		echo "<!DOCTYPE html>\n
		<html>\n
		<head>\n
        <meta charset='utf-8'>\n
        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>\n
        <title>Installation de SLASH-CMS - ".$state."</title>\n
        <meta name='description' content=''>\n
        <meta name='viewport' content='width=device-width'>\n
        <link rel='stylesheet' href='css/bootstrap.min.css'>\n
        <link rel='stylesheet' href='css/main.css'>\n
    </head>\n
    <body>\n
    <div class='container'>\n
    <div class='head'>\n
    <h3>SLASH-CMS</h3>\n
    </div>\n
    <hr>\n";
    echo "<div class='hero-unit'>\n";
	}
	private function loadFooter(){
		echo "</div>\n";
		echo "</div>\n
		</body>\n
		<script src='js/jquery-1.9.1.min.js'></script>\n
        <script src='js/bootstrap.min.js'></script>\n
		</html>";
	}

	public function loadInit($datas,$errors = null,$fatals = null){
		$this->loadHeader("Bienvenue");



    	echo "<h1>Bonjour et bienvenue dans SLASH-CMS</h1>\n";
		echo "<p>Cet assistant va vous guider au cours des diff&eacute;rentes &eacute;tapes d'installation.</p>\n";
		
		// Fatal errors
		if($fatals) echo "<h3 class='text-error'>Veuillez corriger les erreurs suivantes avant de continuer</h3>\n";
		foreach ($fatals as $fatal) {
			echo "<div class='alert alert-error'>".$fatal."</div>\n";
		}
		if($fatals){
			echo "<a class='btn btn-primary btn-warning' href='index.php'>Re-tester</a>\n";
		}else{
			
			echo "<form method='POST'>\n";
			echo "<input type='hidden' name='init_state' value='".$datas['init_state']."'>\n";
			echo "<fieldset>\n";
			echo "<legend>Param&egrave;tres de la base de donn&eacute;es</legend>\n";

			echo "<div class='control-group".(isset($errors['bdd_host']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='bdd_host'>Serveur</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='text' name='bdd_host' placeholder='bdd_host' value='".$datas['bdd_host']."'>\n";
			if(isset($errors['bdd_host'])) echo "<span class='help-inline'>".$errors['bdd_host']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['bdd_port']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='bdd_port'>Port</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='text' name='bdd_port' placeholder='bdd_port' value='".$datas['bdd_port']."'>\n";
			if(isset($errors['bdd_port'])) echo "<span class='help-inline'>".$errors['bdd_port']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['bdd_user']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='bdd_user'>Utilisateur</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='text' name='bdd_user' placeholder='bdd_user' value='".$datas['bdd_user']."'>\n";
			if(isset($errors['bdd_user'])) echo "<span class='help-inline'>".$errors['bdd_user']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['bdd_pwd']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='bdd_pwd'>Mot de passe</label>\n";
			echo "<div class='controls'>\n";
			echo "<input type='text' name='bdd_pwd' placeholder='bdd_pwd' value='".$datas['bdd_pwd']."'>\n";
			if(isset($errors['bdd_pwd'])) echo "<span class='help-inline'>".$errors['bdd_pwd']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['bdd_name']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='bdd_name'>Nom de la base</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='text' name='bdd_name' placeholder='bdd_name' value='".$datas['bdd_name']."'>\n";
			if(isset($errors['bdd_name'])) echo "<span class='help-inline'>".$errors['bdd_name']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['bdd_prefix']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='bdd_prefix'>Prefixe des tables</label>\n";
			echo "<div class='controls'>\n";
			echo "<input type='text' name='bdd_prefix' placeholder='bdd_prefix' value='".$datas['bdd_prefix']."'>\n";
			if(isset($errors['bdd_prefix'])) echo "<span class='help-inline'>".$errors['bdd_prefix']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "</fieldset>\n";



			echo "<fieldset>\n";
			echo "<legend>Compte administrateur</legend>\n";

			echo "<div class='control-group".(isset($errors['admin_name']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='admin_name'>Nom complet</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='text' name='admin_name' placeholder='Jhon Doe' value='".$datas['admin_name']."'>\n";
			if(isset($errors['admin_name'])) echo "<span class='help-inline'>".$errors['admin_name']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['admin_user']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='admin_user'>Identifiant</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='text' name='admin_user' placeholder='superjhon' value='".$datas['admin_user']."'>\n";
			if(isset($errors['admin_user'])) echo "<span class='help-inline'>".$errors['admin_user']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['admin_pwd']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='admin_pwd'>Mot de passe</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='password' name='admin_pwd' value='".$datas['admin_pwd']."'>\n";
			if(isset($errors['admin_pwd'])) echo "<span class='help-inline'>".$errors['admin_pwd']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['admin_pwd_confirm']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='admin_pwd_confirm'>Confirmation</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='password' name='admin_pwd_confirm' value='".$datas['admin_pwd_confirm']."'>\n";
			if(isset($errors['admin_pwd_confirm'])) echo "<span class='help-inline'>".$errors['admin_pwd_confirm']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "<div class='control-group".(isset($errors['admin_mail']) ?" error":"")."'>\n";
			echo "<label class='control-label' for='admin_mail'>E-mail</label>\n";
			echo "<div class='controls'>\n";
			echo "<input required type='email' name='admin_mail' placeholder='jhondoe@example.com' value='".$datas['admin_mail']."'>\n";
			if(isset($errors['admin_mail'])) echo "<span class='help-inline'>".$errors['admin_mail']."</span>\n";
			echo "</div>\n";
			echo "</div>\n";

			echo "</fieldset>\n";


			echo "<br>";
			echo "<input type='submit' class='btn btn-primary btn-large' name='submit' value='Continuer'>\n";
			echo "</form>\n";
		}



		$this->loadFooter();
	}
	public function loadCheckSettings($fatals=null,$warnings=null){
		$this->loadHeader("Test des param&egrave;tres");
		echo "<h2>Test des param&egrave;tres</h2>";
		if($fatals) echo "<h3 class='text-error'>Veuillez corriger les erreurs suivantes avant de continuer</h3>\n";
		foreach ($fatals as $fatal) {
			echo "<div class='alert alert-error'>".$fatal."</div>\n";
		}
		if($fatals){
			echo "<a class='btn btn-primary btn-warning' href='index.php?state=checkSettings'>Re-tester</a>\n";
			echo "<a class='btn btn-primary btn-inverse' href='index.php'>Modifier les param&egrave;tres</a>\n";
		}else{
			foreach ($warnings as $warning) {
				echo "<div class='alert alert-warning'>".$warning."</div>\n";
			}
			echo "<div class='alert alert-success'>Tous les paramètres sont corrects, vous pouvez continuer</div>\n";
			echo "<a class='btn btn-primary btn-primary' href='index.php?state=install'>Continuer</a>\n";
		}
	}

	public function loadInstall($fatals=null){
		$this->loadHeader("Installation de la base de donn&eacute;es");
		echo "<h2>Installation de la base de donn&eacute;es</h2>";
		if($fatals) echo "<h3 class='text-error'>Veuillez corriger les erreurs suivantes avant de continuer</h3>\n";
		foreach ($fatals as $fatal) {
			echo "<div class='alert alert-error'>".$fatal."</div>\n";
		}
		if($fatals){
			echo "<a class='btn btn-primary btn-warning' href='index.php?state=install'>Re-tester</a>\n";
			echo "<a class='btn btn-primary btn-inverse' href='index.php'>Modifier les param&egrave;tres</a>\n";
		}else{
			echo "<div class='alert alert-success'>Yipikaye! SLASH-CMS est install&eacute;.</div>\n";
			echo "<div class='alert alert-info'>Votre compte administreur a &eacute;t&eacute; cr&eacute;e.<br>
			N'oubliez pas de supprimer le répertoire setup.</div>\n";
			echo "<a class='btn btn-primary btn-primary' href='../admin'>Aller au panneau d'administration</a>\n";
		}
	}
	
}
?>