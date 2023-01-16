<?php

if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
 	 
	$boardId = $_POST["boardId"];
	$writer = $_POST["username"];
	$description = $_POST["description"];
 

	$query ="INSERT INTO Board_Reply (boardId, description, writer, lastModifyDate) values (".$boardId.", '".$description."', '".$writer."' , now())";

	$response = array();
	$result = mysql_query($query);
	 
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	return $result;
	mysql_close($connect);
	echo json_encode($response);
?>