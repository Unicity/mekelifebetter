<?php

/**
 * @author Gilbert Mena
 * @copyright 2008
 */
//smtp server login info
	define('SMTP_SERVER', ''); 			//SET THE SMTP SERVER HOST
	define('SMTP_PORT', '');   			//SET THE SMTP SERVER PORT
	define('SMTP_USERNAME', '');   		//SET THE SMTP SERVER USERNAME
	define('SMTP_PASSWORD', '');   		//SET THE SMTP SERVER PASSWORD
	define('SMTP_REPLY_EMAIL', '');   	//SET THE EMAIL ADDRESS PEOPLE WILL REPLY TO
	define('SMTP_REPLY_NAME', '');   	//SET THE NAME (FIRST AND LAST) THAT PEOPLE REPLY TO
	define('SMTP_FROM_EMAIL', '');   	//SET THE EMAIL ADDRESS PEOPLE WILL SEE IN THE FROM FIELD
	define('SMTP_FROM_NAME', '');    	//SET THE NAME  

//database login info
	//define('DB_HOST', 'localhost');
	define('DB_HOST', '10.107.133.149');
	define('DB_USER', 'unicity_db_user');
	define('DB_PASS', 'unicity!2011');
//	define('RANDBUS', 'randit.php');
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
	define('UPLOAD_DIR', 'uploads/');
	define('MAX_FILE_SIZE', '8388608');
	/*	@var $f Functions */
	//$f = new Functions();
	//1048576 = 1 meg
	
	
	//$key is the blowfish encryption key and variables.
	//$key = "G00dLuCkWiThCrAcKiNgThIs14h7Jtf(*9o1!`~009)";
	//$iv_size = mcrypt_get_iv_size(MCRYPT_XTEA, MCRYPT_MODE_ECB);
	//$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

?>