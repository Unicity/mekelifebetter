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

	$query = "delete from tb_faq ";
	$result = mysql_query($query);

	$query = "select * from faq order by no asc";
	$result = mysql_query($query);
	
	while($row = mysql_fetch_array($result)) {
			
			$news_id++;
			
			$cate = $row[cate];
			$subject = $row[subject];
			$comment = $row[comment];

			$cate = str_replace("'", "''",$cate);	
			$subject = str_replace("'", "''",$subject);	
			$comment = str_replace("'", "''",$comment);	
			$comment = stripslashes($comment);			
			$subject = stripslashes($subject);			

			if (trim($cate) == "갱신") {
				$cate = "S1";
			} else if (trim($cate) == "반품") {
				$cate = "S2";
			} else if (trim($cate) == "방침및절차") {
				$cate = "S3";
			} else if (trim($cate) == "수당") {
				$cate = "S4";
			} else if (trim($cate) == "인터넷주문") {
				$cate = "S5";
			} else if (trim($cate) == "전화주문") {
				$cate = "S6";
			} else if (trim($cate) == "주문") {
				$cate = "S7";
			} else if (trim($cate) == "정수기") {
				$cate = "S8";
			} else if (trim($cate) == "카드") {
				$cate = "S9";
			}
						
			$query = "insert into tb_faq (SeqNo, BoardId, RegDate, Title, Content, bshow, cnt) values 
					  ('$news_id', '$cate', now(),  '$subject', '$comment', '1', '0')";

			echo $query; 					 

			mysql_query($query) or die("Query Error");
		
	}
		
	mysql_close($connect);

?>