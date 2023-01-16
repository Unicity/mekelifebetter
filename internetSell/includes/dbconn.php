<?php
 
//database login info
	define('DB_HOST', '192.168.20.39');
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
 
?>

