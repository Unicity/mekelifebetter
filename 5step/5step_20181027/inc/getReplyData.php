<?php

if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
 	 
	$boardId = $_POST["boardId"];
 

	$query ="SELECT  `Board_Reply`.`id`, 
					 `Board_Reply`.`description`, 
					 `Board_Reply`.`lastModifyDate`, 
					 `User`.`name`,
					 `User`.`department` from `Board_Reply`
				JOIN User on Board_Reply.writer = User.id 
    			WHERE boardId = ".$boardId." order by `Board_Reply`.`id`";

	$response = array();
	$result = mysql_query($query);
	 
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	while($rows = mysql_fetch_array($result)) {
		$row = array (
			"id" =>$rows['id'],
			"description" =>$rows['description'],
			"lastModifyDate" =>$rows['lastModifyDate'], 
			"name" =>$rows['name'],
			"department" =>$rows['department']
		);
		$response[] = $row;
	}
	mysql_close($connect);
	echo json_encode($response);
?>