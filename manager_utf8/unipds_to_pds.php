<?
	include "../dbconn_utf8.inc";
   	
	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}
	
	$news_id = 0;

	$query = "delete from tb_pds where NewsId = 'A1'";
	$result = mysql_query($query);

	$query = "select * from com_pds order by no asc";
	$result = mysql_query($query);
	
	while($row = mysql_fetch_array($result)) {
			
			$news_id++;
			
			$file = $row[file];
			$subject = $row[subject];
			$comment = $row[comment];
			$date = $row[date];
			$count = $row[count];
			$cate = $row[cate];
			$file_size = $row[file_size];
			$down_count = $row[down_count];

			$name = str_replace("'", "''",$name);	
			$subject = str_replace("'", "''",$subject);	
			$comment = str_replace("'", "''",$comment);	
			$comment = stripslashes($comment);			

			$date_2 = str_replace("-", "",$date);

			if (trim($cate) == "F & E") {
				$cate = "P3";
			} else if (trim($cate) == "Personal Care") {
				$cate = "P1";
			} else if (trim($cate) == "nutritionals") {
				$cate = "P2";
			}

			$query = "insert into tb_pds (SeqNo, Title, Content, RegDate, cnt, Image, Image_size, down_cnt, 
					  Source, bshow, s_Date, NewsId, isBa) values 
					  ('$news_id', '$subject', '$comment', '$date', '$count', '$file', '$file_size', '$down_count',
					   '$cate', '1', '$date_2', 'A1', '1')";

			echo $query; 					 

			mysql_query($query) or die("Query Error");
		
	}
		
	mysql_close($connect);

?>