<?
/**
* @package		SLASH-CMS
* @subpackage	flashtosql
* @internal     use database in flash
* @version		flashsql.php - Version 9.11.23
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


include ("../../config/sl_config.php");

$config = new SLConfig();

$host = $config->db_host; 
$login = $config->db_user;

$password = $config->db_password;
$database_name = $config->db_name; 




if (isset($_POST["query"]) && $_POST["cmd"] == "query")
{

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	echo "<root>\n";
	
	
	
	$database = mysql_connect($host, $login, $password) or die ("<checkchild error='errorconnexion'></checkchild>");	
	mysql_select_db($database_name, $database) or die ("<checkchild error='errorconnexion'></checkchild>");
	
	
	/*
		$filename = 'test.txt';
		$somecontent = stripslashes($_POST["query"]);

		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)) {

		// In our example we're opening $filename in append mode.
		// The file pointer is at the bottom of the file hence
		// that's where $somecontent will go when we fwrite() it.
		if (!$handle = fopen($filename, 'a')) {
			 echo "Cannot open file ($filename)";
			 exit;
		}

		// Write $somecontent to our opened file.
		if (fwrite($handle, $somecontent) === FALSE) {
			echo "Cannot write to file ($filename)";
			exit;
		}

		echo "Success, wrote ($somecontent) to file ($filename)";

		fclose($handle);

		} else {
					echo "The file $filename is not writable";
		}
		
	
		*/
	
	$query_result = mysql_query(stripslashes($_POST["query"]),$database) or die ("<checkchild error='errorquery'></checkchild>");
			//$query_result = mysql_query("select * from places WHERE name='Air Escargot'",$database) or die ("<checkchild error='errorquery'></checkchild>");
	$numfields = mysql_num_fields($query_result);
	

	for($i=0;$i<$numfields;$i++)
	{
		$fieldname[$i]=mysql_field_name($query_result, $i);
	}

	while($row=mysql_fetch_row($query_result))
	{
	
	   echo "<sqlresult>"; 
	   
		for($i=0;$i<$numfields;$i++)
		{
			echo "<field name='".$fieldname[$i]."'>";
			echo "<value><![CDATA[".utf8_encode(html_entity_decode($row[$i]))."]]></value>";
			echo "</field>";
		}
		
		echo "</sqlresult>\n";
	}
	
	
	
	echo "</root>";
	echo "<checkchild error='noerror'></checkchild>";
	
	
	mysql_free_result($query_result); //libère la mémoire
		
	mysql_close();
}


if (isset($_POST["query"]) && $_POST["cmd"] == "command")
{
	
	$database = mysql_connect($host, $login, $password) or die ("");	
	mysql_select_db($database_name, $database) or die ("");
	
	$query_result = mysql_query(stripslashes($_POST["query"]),$database) or die ("<checkchild error='errorquery'></checkchild>");
	
	mysql_free_result($query_result); //libère la mémoire
		
	mysql_close();
}


?>