<?php
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";
	include "./AES.php";

	$name = isset($_POST["name"]) ? $_POST["name"] : '';
	$phonenumber = isset($_POST["phonenumber"]) ? $_POST["phonenumber"] : '';
	$username = isset($_POST["username"]) ? $_POST["username"] : '';
	$pwd = isset($_POST["pwd"]) ? $_POST["pwd"] : '';

	if($name == '' || $phonenumber == '' || $username == '' || $pwd == '' ){
		echo 'fail';
		return false;
	}

	$enc_password =  encrypt($key, $iv, $pwd);
	 
	$userInfoUpdateQuery = "UPDATE UserInfo SET password='$enc_password' WHERE id='$username';" ;

	$result_userInfoUpdateQuery = mysql_query($userInfoUpdateQuery) or die("userInfoUpdateQuery Error");

	mysql_close($connect);
	// $result_userInfoQuery = 1;
	// $result_userQuery = 1;
	if ($result_userInfoUpdateQuery == 1 && $result_userInfoUpdateQuery == 1) {
		echo 'success';
	} else {
		echo 'fail';
	}
	
	 

?>