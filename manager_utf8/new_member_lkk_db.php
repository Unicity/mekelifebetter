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

	function isExist($sid)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_member_lkk where reg_no='$sid'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);

		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}

	function getMaxCode()  { 
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(member_id) CNT FROM tb_member_lkk "; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;
		
		if (strlen($iNewid) == 1) {
			$iNewid = "0".$iNewid;
		} 
		
		return $iNewid;
	
	}

	$mode					= str_quote_smart(trim($mode));

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$sort					= str_quote_smart(trim($sort));
	$order				= str_quote_smart(trim($order));

	$member_kind	= str_quote_smart(trim($member_kind));

	if ($mode == "add") {

		$sMax = getMaxCode();

		$reg_no				= str_quote_smart(trim($reg_no));
		$name					= str_quote_smart(trim($name));
		$number				= str_quote_smart(trim($number));
		$phone				= str_quote_smart(trim($phone));
		$zip					= str_quote_smart(trim($zip));
		$addr					= str_quote_smart(trim($addr));
		$email				= str_quote_smart(trim($email));
		$member_kind	= str_quote_smart(trim($member_kind));

		if(!isExist($reg_no) == 1) {

			$query = "insert into tb_member_lkk (member_id, reg_no, name, number, phone, zip, addr, temp2, regDate, email, member_kind) values 
					  ('$sMax', '$reg_no', '$name', '$number', '$phone', '$zip', '$addr', '$email', now(), '$email', '$member_kind')";

			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
				alert('등록 되었습니다.');
				parent.frames[3].location = 'member_list.php?member_kind=$member_kind';
				</script>";
			exit;

		} else {
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
				alert('이미 등록된 주민등록번호 (TAX No) 입니다.');
				</script>";
			exit;
		}
	} else if ($mode == "mod") {

		$member_id		= str_quote_smart(trim($member_id));
		$reg_no				= str_quote_smart(trim($reg_no));
		$name					= str_quote_smart(trim($name));
		$number				= str_quote_smart(trim($number));
		$phone				= str_quote_smart(trim($phone));
		$zip					= str_quote_smart(trim($zip));
		$addr					= str_quote_smart(trim($addr));
		$email				= str_quote_smart(trim($email));
		$member_kind	= str_quote_smart(trim($member_kind));

		#if(!isExist($reg_no) == 1) {

			$query = "update tb_member_lkk set 
						reg_no = '$reg_no',
						name = '$name',
						number = '$number',
						phone = '$phone',
						zip = '$zip',
						addr = '$addr',
						email = '$email', 
						temp2 = '$email', 
						regDate = now() 
				    	where member_id = '$member_id'";
			 
			mysql_query($query) or die("Query Error");
			mysql_close($connect);

		#} else {
		#	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
		#<script language=\"javascript\">\n
		#		alert('이미 등록된 주민등록번호 (TAX No) 입니다.');
		#		</script>";
		#	exit;
		#}
		
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			</script>";
		exit;

	} else if ($mode == "del") {

		$member_id		= str_quote_smart(trim($member_id));
		$member_id = str_replace("^", "'",$member_id);
		for($i=0;$i<$CheckItem.length;$i++)
		{
			if(!empty($CheckItem[$i]))
			{
				$query = "delete from tb_member_lkk where idx in ($CheckItem[$i])";

				//echo $query; 					 
				@mysql_query($query) or die("Query Error");
			}
		}
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'new_member_lkk_list.php';
			</script>";
		exit;

	}
?>