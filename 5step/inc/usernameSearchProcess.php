<?php
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";

	$name = isset($_POST["name"]) ? $_POST["name"] : '';
	$phonenumber = isset($_POST["phonenumber"]) ? $_POST["phonenumber"] : '';
	 
	$phonenumber = clean($phonenumber);
	
	$ids = "";
	 
	
	if($name == '' || $phonenumber == ''){
		echo "fail";
		return false;
	}

	$userQuery = "SELECT User.id FROM User JOIN UserInfo ON User.id = UserInfo.id WHERE User.name = '$name' AND UserInfo.contactNo = '$phonenumber' ";


	$result_userQuery = mysql_query($userQuery) or die("userQuery Error".$userQuery);
	
	while ($rows = mysql_fetch_array($result_userQuery)) {
		$ids .= ' '.$rows['id'] ;
	}
  	
  	mysql_close($connect);
	
	echo ltrim($ids, ' ');   	
 

	

?>