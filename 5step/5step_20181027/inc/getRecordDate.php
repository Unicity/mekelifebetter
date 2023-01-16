<?php
	header("Content-type: application/json; charset=utf-8");

	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
 
	 
	$id = $_POST["username"];
	$selectedMonth = $_POST["selectedMonth"];

	$query = " SELECT DISTINCT (DATE_FORMAT(CreateDate,'%e')) as RecordDate from StepRecord where DATE_FORMAT(CreateDate,'%Y-%c') = '$selectedMonth' and member_no = $id";
	 

	$response = array();
	$result = mysql_query($query);
	 
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	while($rows = mysql_fetch_array($result)) {
		$row = array (
			"RecordDate" =>$rows['RecordDate']
		);
		$response[] = $row;
	}
	mysql_close($connect);
	echo json_encode($response);
?>