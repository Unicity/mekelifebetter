<?php

if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
 	 
	$from = $_POST["from"];
	$to = $_POST["to"];

	$query ="SELECT `Board`.`id`,
					`User`.`name`,
					`User`.`department`,
				    `Board`.`title`,
				    `Board`.`description`,
				    `Board`.`filename`,
				    `Board`.`writer`,
				    `Board`.`lastModifyDate`,
				    IFNULL(`Replies`.`replies`, 0) AS `replies`,
				    CASE WHEN DATE(NOW()) = DATE(`Board`.`lastModifyDate`) THEN 'N'
				    ELSE 'O' END AS NewFlag,
                    `Replies`.`newreply`
				    FROM 5step.Board 
					JOIN User on Board.writer = User.id
				    LEFT OUTER JOIN 
				    (select boardId, count(id) AS replies,
						case when max(lastModifyDate) between DATE_ADD(NOW(), INTERVAL -2 HOUR) and now()
   						 then 'O'
   						 else 'X' end as newreply
    					from Board_Reply
				 		group by boardId) Replies
					ON Board.id = Replies.boardId
					ORDER BY `Board`.`lastModifyDate` DESC LIMIT ".$from.", ".$to;

	$response = array();
	$result = mysql_query($query);
	 
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	while($rows = mysql_fetch_array($result)) {
		$row = array (
			"id" =>$rows['id'],
			"name" =>$rows['name'],
			"department" =>$rows['department'],
			"title" =>$rows['title'],
			"description" =>$rows['description'],
			"filename" =>$rows['filename'],
			"writer" =>$rows['writer'],
			"lastModifyDate" =>$rows['lastModifyDate'], 
			"replies" =>$rows['replies'],
			"newflag" =>$rows['NewFlag'],
			"newreply" =>$rows['newreply'],
		);
		$response[] = $row;
	}
	mysql_close($connect);
	echo json_encode($response);
?>