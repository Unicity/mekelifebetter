<?
header("Content-type: application/json; charset=utf-8");

	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
 
	 
	$id = $_POST["username"];
	$selectedDate = $_POST["selectedDate"];
	$programID = $_POST["programID"];
/*	$query ="
			SELECT 	
		  			User.id, 
		  			ifnull(S1.qty,0) AS totalWater, 
		  			ifnull(S2.calorie,0) AS totalCalorie, 
					ifnull(S3.type1, 0) as type1, 
					ifnull(S3.type2, 0) as type2,
					ifnull(S3.type3, 0) as type3,
					ifnull(S3.type5, 0) as type5,
					CASE WHEN S1.qty >=2 
						  AND S2.calorie >= 300 
						  AND ( (S3.type1 >= 1 
						     AND S3.type2 >= 1 
						     AND S3.type3 = 2) 
						  OR S3.type5 =2 )
            		THEN 1 
            		ELSE 0 END AS result1,
            		CASE WHEN S3.type5 =2 
            		THEN 1 
            		ELSE 0 END AS result2,
            		User.date as Createdate
			FROM (select User.id, contestDate.date,User.isCleanser  FROM User,contestDate 
				where User.id='".$id."' 
					and contestDate.date <=  now()  
					and Date_format(contestDate.date, '%Y-%c')= '".$selectedMonth."')
				User
                LEFT OUTER JOIN 
	 			( SELECT id, sum(qty) AS qty, Date_format(createdate, '%Y-%m-%d') AS Createdate 
	 				FROM Step1Record
	  				WHERE Date_format(createdate, '%Y-%c')='".$selectedMonth."' 
	  				GROUP BY id, Date_format(createdate, '%Y-%m-%d')  
	  			) AS S1
                ON User.id = S1.id AND User.date = S1.Createdate
                
	  			LEFT OUTER JOIN 
	  			( SELECT id, sum(calorie) AS calorie, Date_format(createdate, '%Y-%m-%d') AS Createdate  
	  				FROM Step2Record
	  				WHERE Date_format(createdate, '%Y-%c')='".$selectedMonth."' 
	  				GROUP BY id, Date_format(createdate, '%Y-%m-%d')
	  			) AS S2 
	  				ON User.id = S2.id  
	  					AND User.date = S2.Createdate
	 			LEFT OUTER JOIN 
	  			( SELECT id,
					sum(case when type = 1 then qty end) AS 'type1',
					sum(case when type = 2 then qty end) AS 'type2',
					sum(case when type = 3 then qty end) AS 'type3',
					sum(case when type = 5 then qty end) AS 'type5',
					Createdate
					FROM ( 
						SELECT id, type AS type, qty, Date_format(createdate, '%Y-%m-%d') AS Createdate 
							FROM Step3Record
							WHERE Date_format(createdate, '%Y-%c')= '".$selectedMonth."') AS a
							GROUP BY id, Createdate
				) AS S3 
					ON  User.id = S3.id  
					AND User.date = S3.Createdate";

	$query ="SELECT 	
		  			User.id, 
		  			ifnull(S1.qty,0) AS totalWater, 
		  			ifnull(S2.calorie,0) AS totalCalorie, 
					ifnull(S3.type1, 0) as type1, 
					ifnull(S3.type2, 0) as type2,
					ifnull(S3.type3, 0) as type3,
					ifnull(S3.type5, 0) as type5,
                    ifnull(S3.type6, 0) as type6,
                    ifnull(S3.type71, 0) as type71,
                    ifnull(S3.type72, 0) as type72,
					CASE WHEN User.isCleanser = 0 AND S3.type5 >= 2
							THEN 1
						WHEN User.isCleanser in (1,2) AND ( (S3.type1 >= 1 
						     AND S3.type2 >= 1 
						     AND S3.type3 = 2) 
						   )
							THEN 1 
						ELSE 0 END AS result1,
           		
                   CASE WHEN User.isCleanser = 0 AND S3.type5 = 2
							THEN 1
						WHEN User.isCleanser = 1 AND (S3.type71 = 1 AND S3.type72 = 1) AND  S3.type5 = 2
							THEN 1
                        WHEN User.isCleanser = 2 AND (S3.type71 = 1 AND S3.type72 = 1) AND  (S3.type5 = 2 AND S3.type6 = 1)
							THEN 1 
						ELSE 0 END AS result2,
           		User.date as Createdate
			FROM (select User.id, contestDate.date,User.isCleanser  FROM User,contestDate 
				where User.id='".$id."' 
					and contestDate.date <=  now()  
					and Date_format(contestDate.date, '%Y-%c')= '".$selectedMonth."')
				User
				 LEFT OUTER JOIN 
	 			( SELECT id, sum(qty) AS qty, Date_format(createdate, '%Y-%m-%d') AS Createdate 
	 				FROM Step1Record
	  				WHERE Date_format(createdate, '%Y-%c')='".$selectedMonth."' 
	  				GROUP BY id, Date_format(createdate, '%Y-%m-%d')  
	  			) AS S1
                ON User.id = S1.id AND User.date = S1.Createdate
                
	  			LEFT OUTER JOIN 
	  			( SELECT id, sum(calorie) AS calorie, Date_format(createdate, '%Y-%m-%d') AS Createdate  
	  				FROM Step2Record
	  				WHERE Date_format(createdate, '%Y-%c')='".$selectedMonth."' 
	  				GROUP BY id, Date_format(createdate, '%Y-%m-%d')
	  			) AS S2 
	  				ON User.id = S2.id  
	  					AND User.date = S2.Createdate
	  					
	 			LEFT OUTER JOIN 
	  			( SELECT id,
					sum(case when type = 1 then qty end) AS 'type1',
					sum(case when type = 2 then qty end) AS 'type2',
					sum(case when type = 3 then qty end) AS 'type3',
					sum(case when type = 5 then qty end) AS 'type5',
                    sum(case when type = 6 then qty end) AS 'type6',   
                    sum(case when type = 71 then qty end) AS 'type71',   
                    sum(case when type = 72 then qty end) AS 'type72',   
					Createdate
					FROM ( 
						SELECT id, type AS type, qty, Date_format(createdate, '%Y-%m-%d') AS Createdate 
							FROM Step3Record
							WHERE Date_format(createdate, '%Y-%c')= '".$selectedMonth."') AS a
							GROUP BY id, Createdate
				) AS S3 
					ON  User.id = S3.id  
					AND User.date = S3.Createdate";  
					// Step3에 type 3은 클린져
					// Step3에 type 5은 BIOS7
					// Step3에 type 6은 프로바이오닉스
				*/
	$query = " SELECT record.ProductID, Product.ProductName, record.Step, record.amount, record.CreateDate  FROM (SELECT ProductID, Step, SUM(Amount) AS amount, Date_format(CreateDate, '%Y-%c-%e') AS CreateDate FROM 5step.StepRecord WHERE ProgramID = '$programID' AND Date_format(CreateDate, '%Y-%c-%e') = '$selectedDate' AND member_no = '$id' GROUP BY ProductID, Step, Date_format(CreateDate, '%Y-%c-%e') ) AS record JOIN Product ON record.ProductID = Product.ProductID AND record.Step = Product.Step ORDER BY record.Step";

	$response = array();
	$result = mysql_query($query);
	 
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	while($rows = mysql_fetch_array($result)) {
		$row = array (
			"ProductID" =>$rows['ProductID'],
			"ProductName" =>$rows['ProductName'],
			"Step" =>$rows['Step'],
			"amount" =>$rows['amount'],
			"CreateDate" =>$rows['CreateDate']
			 
		);
		$response[] = $row;
	}
	mysql_close($connect);
	echo json_encode($response);

?>