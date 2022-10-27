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
		$no = $row[no];
			
		$query1 = "insert into tb_board (SeqNo, BoardId, Title, Content, cnt, Image, Image_size, 
				RegDate, kind, bshow, writer, down_cnt) 
				Select '$news_id', 'A2', subject, comment, count, file, file_size, date, '0', '1', name, down_count 
				from uni_news where no = '$no' ";

		echo $query1."<br>"; 					 

		mysql_query($query1) or die("Query Error");
		
	}
		
	mysql_close($connect);

?>