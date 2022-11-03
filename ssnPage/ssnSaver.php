<?php
 

	if (!include_once("./includes/dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./includes/AES.php";

 	$_POST = json_decode(file_get_contents('php://input'), true);
 	$distID = "";
 	$SSN = "";
 	if ($_POST['distID'] == "" || $_POST['ssn'] == "" ) {
 		echo "회원번호 또는 주민번호를 다시 확인해 주십시오";
 		exit;
 	}

 	$distID = $_POST['distID'];
 	$SSN = encrypt($key, $iv, $_POST['ssn']);

 	$query = "insert into tb_distSSN  (dist_id, government_id) values
						('$distID', '$SSN')";

	if (mysql_query($query) === TRUE) {
		echo true;
	} else {
		echo false;
	}
	 
	mysql_close($connect);

?>
 