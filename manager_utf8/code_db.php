<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?
	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	$mode		= str_quote_smart(trim($mode));

	if ($mode == "add") {

		$code		= str_quote_smart(trim($code));
		$name		= str_quote_smart(trim($name));
		$parent = str_quote_smart(trim($parent));
		$memo		= str_quote_smart(trim($memo));

		$query = "insert into tb_code (parent, code, name, memo) values 
					  ('$parent', '$code', '$name', '$memo')";

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script language=\"javascript\">\n
			alert('등록 되었습니다.');
			parent.frames[3].location = 'code_list.php?parent=$parent';
			</script>";
		exit;

	} else if ($mode == "mod") {

		$id		= str_quote_smart(trim($id));
		$code = str_quote_smart(trim($code));
		$name = str_quote_smart(trim($name));
		$memo = str_quote_smart(trim($memo));

		$query = "update tb_code set 
					code = '$code',
					name = '$name',
					memo = '$memo'
				    where id = '$id'";
					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			</script>";
		exit;

	} else if ($mode == "del") {

		$parent = str_quote_smart(trim($parent));
		$id			= str_quote_smart(trim($id));
		$id			= str_replace("^", "'",$id);

		$query = "delete from tb_code
				    where id in $id";

		#echo $query; 					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
					<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'code_list.php?parent=$parent';
			</script>";
		exit;

	}
?>