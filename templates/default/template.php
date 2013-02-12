<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
/**
* @package		SLASH-CMS
* @version		template.php - Version 9.6.2
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

$this->initialize_modules(); 
$this->load_module("sl_header"); 
?>

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="<?php echo $this->config["site_template_url"]; ?>css/styles.css" />
<link href="<?php echo $this->config["site_template_url"]; ?>images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>

<div class="header">
	<div class="logo"><a href="index.php"><img src="<?php echo $this->config["site_template_url"]; ?>images/slashcms.png" border="0"/></a></div>
	<div class="head">DEFAULT TEMPLATE</div>
</div>

<div class="menu"><?php $this->load_module("sl_menu"); ?></div>

<div class="content">

<?php 
if ($this->sl_param("mod")) {
	$this->load_module($this->sl_param("mod")); 
}
?>

</div>

<div class="footer">Powered by <a href="http://www.slash-cms.com" target="_blank" >Slash CMS</a></div>

<?php $this->execute_modules(); ?>
</body>
</html>