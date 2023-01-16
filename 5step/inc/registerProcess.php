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
		echo 'test';
		return false;
	}

	$enc_password =  encrypt($key, $iv, $pwd);
	 
	$userInfoQuery = "INSERT INTO UserInfo (`id`, `password`, `contactNo`) VALUES ('".$username."', '".$enc_password."', '".$phonenumber."')";
	
	$result_userInfoQuery = mysql_query($userInfoQuery) or die("userInfoQuery Error");

	$userQuery = "INSERT INTO User (`id`, `department`, `name`) VALUES ('".$username."', 'nonmember', '".$name."')" ;

	$result_userQuery = mysql_query($userQuery) or die("userQuery Error");

	mysql_close($connect);
	// $result_userInfoQuery = 1;
	// $result_userQuery = 1;
	if ($result_userInfoQuery == 1 && $result_userQuery == 1) {
		echo 'success';
	} else {
		echo 'fail';
	}
	
	 

?>