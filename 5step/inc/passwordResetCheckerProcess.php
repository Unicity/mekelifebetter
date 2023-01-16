<?php
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";

	$name = isset($_POST["name"]) ? $_POST["name"] : '';
	$phonenumber = isset($_POST["phonenumber"]) ? $_POST["phonenumber"] : '';
	$username = isset($_POST["username"]) ? $_POST["username"] : '';
	
	 
	$phonenumber = clean($phonenumber);
	$username = clean($username);
	 
	
	if($name == '' || $phonenumber == '' || $username == ''){
		echo 'fail';
		return false;
	}

	$userQuery = "SELECT count(*) FROM User WHERE id = '$username' AND name = '$name' ";

	$result_userQuery = mysql_query($userQuery) or die("userQuery Error");
	$v = mysql_fetch_row($result_userQuery);
  	 
  	if ($v[0] == 1) {
		
		$userInfoQuery = "SELECT count(*) FROM UserInfo WHERE id = '$username' AND contactNo='$phonenumber' ";
		$result_userInfoQuery = mysql_query($userInfoQuery) or die("userInfoQuery Error");
		
		$val = mysql_fetch_row($result_userInfoQuery);	
		 
		mysql_close($connect);
		
		if($val[0] == 1) {
			echo 'success';
		} else {
			echo 'fail1';
		}

	} else {
		mysql_close($connect);
		echo 'fail2';
	}

	

?>