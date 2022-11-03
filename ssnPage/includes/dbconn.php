<?php
@extract($_GET);
@extract($_POST);

require_once $_SERVER['DOCUMENT_ROOT']."/mysql-wrapper.php";

//$connect = mysql_pconnect ("localhost","unicity_db_user","unicity!2011");
//$connect = mysql_pconnect ("10.107.133.149","unicity_db_user","unicity!2011");
$connect = mysql_pconnect ("210.116.103.149","unicity_db_user","unicity!2011");
mysql_select_db("makelifebetter",$connect);

mysql_query("SET NAMES utf8");
mysql_query("SET character_set_server = utf8");


/*//database login info
	//define('DB_HOST', 'localhost');
	//define('DB_HOST', '10.107.133.149');
	define('DB_HOST', '210.116.103.149');
	define('DB_USER', 'unicity_db_user');
	define('DB_PASS', 'unicity!2011');
	define('DATABASE', 'makelifebetter');
	define('SALT_LENGTH', 10);
	
	$connect = mysql_pconnect(DB_HOST, DB_USER, DB_PASS);
	if (!$connect)
	{
		die("Could not connect to the database.  MySQL returned the following error" . mysql_error());
	}
	define('LINK', $connect);
	mysql_select_db(DATABASE, LINK);
	mysql_set_charset('utf8',LINK); 
 */
?>