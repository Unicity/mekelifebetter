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

	$query = "delete from tb_board where BoardId = 'A2'";
	$result = mysql_query($query);

	$query = "select * from ibo_news order by no asc";
	$result = mysql_query($query);
	
	while($row = mysql_fetch_array($result)) {
			
			$news_id++;
			
			$name = $row[name];
			$subject = $row[subject];
			$comment = $row[comment];
			$count = $row[count];
			$file = $row[file];
			$file_size = $row[file_size];
			$down_count = $row[down_count];
			$date = $row[date];

			$name = str_replace("'", "''",$name);	
			$subject = str_replace("'", "''",$subject);	
			$comment = str_replace("'", "''",$comment);	
			$date = str_replace("'", "''",$date);	
			$comment = stripslashes($comment);			
			
			$query = "insert into tb_board (SeqNo, BoardId, Title, Content, cnt, Image, Image_size, RegDate, 
					  kind, bshow, writer, down_cnt) values 
					  ('$news_id', 'A2', '$subject', '$comment', '$count', '$file', '$file_size', '$date',
					   '0', '1', '$name', '$down_count')";

			echo $query; 					 

			mysql_query($query) or die("Query Error");
		
	}
		
	mysql_close($connect);

?>