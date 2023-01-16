<?php
	
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
 

	$query ="
		 
        SELECT 
    TodayRecord.*, YesterRank.yesterdayRank
FROM
    ((SELECT 
        id,
            name,
            department,
            step1total,
            step2total,
            step3total,
            step5total,
            total,
            @rank:=IF(@pre_value = total, @rank, @rank + 1) AS Rank
    FROM
        (SELECT 
        Step1.id,
            Step1.department,
            Step1.name,
            Step1.step1total,
            Step2.step2total,
            Step3.step3total,
            Step3.step5total,
            SUM(Step1.step1total + Step2.step2total + Step3.step3total + Step3.step5total) AS total
    FROM
        ((SELECT 
        User.id,
            User.department,
            User.name,
            IFNULL(SUM(point), 0) AS step1total
    FROM
        User
    LEFT OUTER JOIN (SELECT 
        id,
            SUM(qty) AS qty,
            CASE
                WHEN IFNULL(SUM(qty), 0) < 0.5 THEN 1
                WHEN
                    0.5 <= IFNULL(SUM(qty), 0)
                        AND IFNULL(SUM(qty), 0) < 1
                THEN
                    2
                WHEN
                    1 <= IFNULL(SUM(qty), 0)
                        AND IFNULL(SUM(qty), 0) < 1.5
                THEN
                    3
                WHEN
                    1.5 <= IFNULL(SUM(qty), 0)
                        AND IFNULL(SUM(qty), 0) < 2
                THEN
                    4
                WHEN IFNULL(SUM(qty), 0) >= 2 THEN 5
            END AS point,
            DATE_FORMAT(createdate, '%Y-%m-%d') AS Createdate
    FROM
        Step1Record
    GROUP BY id , DATE_FORMAT(createdate, '%Y-%m-%d')) a ON User.id = a.id
    GROUP BY User.id , User.department , User.name) Step1
    JOIN (SELECT 
        User.id,
            User.department,
            User.name,
            IFNULL(SUM(point), 0) AS step2total
    FROM
        User
    LEFT OUTER JOIN (SELECT 
        id,
            SUM(qty) AS qty,
            CASE
                WHEN IFNULL(SUM(calorie), 0) < 100 THEN 1
                WHEN
                    100 <= IFNULL(SUM(calorie), 0)
                        AND IFNULL(SUM(calorie), 0) < 200
                THEN
                    2
                WHEN
                    200 <= IFNULL(SUM(calorie), 0)
                        AND IFNULL(SUM(calorie), 0) < 300
                THEN
                    3
                WHEN
                    300 <= IFNULL(SUM(calorie), 0)
                        AND IFNULL(SUM(calorie), 0) < 400
                THEN
                    4
                WHEN 400 <= IFNULL(SUM(calorie), 0) THEN 5
                ELSE 0
            END AS point,
            DATE_FORMAT(createdate, '%Y-%m-%d') AS Createdate
    FROM
        Step2Record
    GROUP BY id , DATE_FORMAT(createdate, '%Y-%m-%d')) a ON User.id = a.id
    GROUP BY User.id , User.department , User.name) Step2 ON Step1.id = Step2.id
    JOIN (SELECT 
        User.id,
            User.department,
            User.name,
            IFNULL(SUM(step3point), 0) AS step3total,
            IFNULL(SUM(step5point), 0) AS step5total
    FROM
        User
    LEFT OUTER JOIN (SELECT 
        id,
            CASE
                WHEN
                    IFNULL(a.type1, 0) >= 1
                        AND IFNULL(a.type2, 0) >= 1
                        AND IFNULL(a.type3, 0) = 2
                THEN
                    5
                WHEN
                        IFNULL(a.type1, 0) = 0
                        AND IFNULL(a.type2, 0) = 0
                        AND  IFNULL(a.type3, 0) = 0  
                THEN
                    0
                ELSE 2
            END AS step3point,
            CASE
                WHEN IFNULL(a.type5, 0) = 2 THEN 5
                WHEN IFNULL(a.type5, 0) = 1 THEN 2
                ELSE 0
            END AS step5point
    FROM
        (SELECT 
        id,
            IFNULL(SUM(CASE
                WHEN type = 1 THEN qty
            END), 0) AS 'type1',
            IFNULL(SUM(CASE
                WHEN type = 2 THEN qty
            END), 0) AS 'type2',
            IFNULL(SUM(CASE
                WHEN type = 3 THEN qty
            END), 0) AS 'type3',
            IFNULL(SUM(CASE
                WHEN type = 5 THEN qty
            END), 0) AS 'type5',
            Createdate
    FROM
        (SELECT 
        id,
            type AS type,
            qty,
            DATE_FORMAT(createdate, '%Y-%m-%d') AS Createdate
    FROM
        Step3Record) AS a
    GROUP BY id , Createdate) a) aa ON User.id = aa.id
    GROUP BY User.id , User.department , User.name) Step3 ON Step1.id = Step3.id)
    GROUP BY Step1.id , Step1.department , Step1.name) today, (SELECT @rank:=0, @pre_value:=NULL) a) ORDER BY total DESC) TodayRecord
        JOIN
    ((SELECT 
        id,
            name,
            department,
            step1total,
            step2total,
            step3total,
            step5total,
            total,
            @rank1:=IF(@pre_value1 = total, @rank1, @rank1 + 1) AS yesterdayRank
    FROM
        (SELECT 
        Step1.id,
            Step1.department,
            Step1.name,
            Step1.step1total,
            Step2.step2total,
            Step3.step3total,
            Step3.step5total,
            SUM(Step1.step1total + Step2.step2total + Step3.step3total + Step3.step5total) AS total
    FROM
        ((SELECT 
        User.id,
            User.department,
            User.name,
            IFNULL(SUM(point), 0) AS step1total
    FROM
        User
    LEFT OUTER JOIN (SELECT 
        id,
            SUM(qty) AS qty,
            CASE
                WHEN IFNULL(SUM(qty), 0) < 0.5 THEN 1
                WHEN
                    0.5 >= IFNULL(SUM(qty), 0)
                        AND IFNULL(SUM(qty), 0) < 1
                THEN
                    2
                WHEN
                    1 >= IFNULL(SUM(qty), 0)
                        AND IFNULL(SUM(qty), 0) < 1.5
                THEN
                    3
                WHEN
                    1.5 >= IFNULL(SUM(qty), 0)
                        AND IFNULL(SUM(qty), 0) < 2
                THEN
                    4
                WHEN IFNULL(SUM(qty), 0) >= 2 THEN 5
            END AS point,
            DATE_FORMAT(createdate, '%Y-%m-%d') AS Createdate
    FROM
        Step1Record
    WHERE
        Createdate < DATE_SUB(NOW(), INTERVAL 1 DAY)
    GROUP BY id , DATE_FORMAT(createdate, '%Y-%m-%d')) a ON User.id = a.id
    GROUP BY User.id , User.department , User.name) Step1
    JOIN (SELECT 
        User.id,
            User.department,
            User.name,
            IFNULL(SUM(point), 0) AS step2total
    FROM
        User
    LEFT OUTER JOIN (SELECT 
        id,
            SUM(qty) AS qty,
            CASE
                WHEN IFNULL(SUM(calorie), 0) < 100 THEN 1
                WHEN
                    100 <= IFNULL(SUM(calorie), 0)
                        AND IFNULL(SUM(calorie), 0) < 200
                THEN
                    2
                WHEN
                    200 <= IFNULL(SUM(calorie), 0)
                        AND IFNULL(SUM(calorie), 0) < 300
                THEN
                    3
                WHEN
                    300 <= IFNULL(SUM(calorie), 0)
                        AND IFNULL(SUM(calorie), 0) < 400
                THEN
                    4
                WHEN 400 <= IFNULL(SUM(calorie), 0) THEN 5
                ELSE 0
            END AS point,
            DATE_FORMAT(createdate, '%Y-%m-%d') AS Createdate
    FROM
        Step2Record
    WHERE
        Createdate < DATE_SUB(NOW(), INTERVAL 1 DAY)
    GROUP BY id , DATE_FORMAT(createdate, '%Y-%m-%d')) a ON User.id = a.id
    GROUP BY User.id , User.department , User.name) Step2 ON Step1.id = Step2.id
    JOIN (SELECT 
        User.id,
            User.department,
            User.name,
            IFNULL(SUM(step3point), 0) AS step3total,
            IFNULL(SUM(step5point), 0) AS step5total
    FROM
        User
    LEFT OUTER JOIN (SELECT 
        id,
            CASE
                WHEN
                    IFNULL(a.type1, 0) >= 1
                        AND IFNULL(a.type2, 0) >= 1
                        AND IFNULL(a.type3, 0) = 2
                THEN
                    5
                WHEN
                        IFNULL(a.type1, 0) = 0
                        AND IFNULL(a.type2, 0) = 0
                        AND  IFNULL(a.type3, 0) = 0  
                THEN
                    0
                ELSE 2
            END AS step3point,
            CASE
                WHEN IFNULL(a.type5, 0) = 2 THEN 5
                WHEN IFNULL(a.type5, 0) = 1 THEN 2
                ELSE 0
            END AS step5point
    FROM
        (SELECT 
        id,
            IFNULL(SUM(CASE
                WHEN type = 1 THEN qty
            END), 0) AS 'type1',
            IFNULL(SUM(CASE
                WHEN type = 2 THEN qty
            END), 0) AS 'type2',
            IFNULL(SUM(CASE
                WHEN type = 3 THEN qty
            END), 0) AS 'type3',
            IFNULL(SUM(CASE
                WHEN type = 5 THEN qty
            END), 0) AS 'type5',
            Createdate
    FROM
        (SELECT 
        id,
            type AS type,
            qty,
            DATE_FORMAT(createdate, '%Y-%m-%d') AS Createdate
    FROM
        Step3Record
    WHERE
        Createdate <= DATE_SUB(NOW(), INTERVAL 1 DAY)) AS a
    GROUP BY id , Createdate) a) aa ON User.id = aa.id
    GROUP BY User.id , User.department , User.name) Step3 ON Step1.id = Step3.id)
    GROUP BY Step1.id , Step1.department , Step1.name) today, (SELECT @rank1:=0, @pre_value1:=NULL) a1) ORDER BY total DESC) YesterRank ON TodayRecord.id = YesterRank.id
ORDER BY total desc
         
          ";

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
			"step1Total" =>$rows['step1total'],
			"step2Total" =>$rows['step2total'],
			"step3Total" =>$rows['step3total'],
			"step5Total" =>$rows['step5total'],
			"total" =>$rows['total'],
		 	"rank"=>$rows['Rank'],
			"id2" =>$rows['id2'],
			"yesterdayRank" =>$rows['yesterdayRank']
		);
		array_push($response, $row);
	}
	mysql_close($connect);
	echo json_encode($response);
?>