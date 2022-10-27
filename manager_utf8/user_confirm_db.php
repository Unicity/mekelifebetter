<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
	}

	$member_no			= str_quote_smart(trim($member_no));
	$re_member_no		= str_quote_smart(trim($member_no));
	$member_no = str_replace("^", "'",$member_no);
		
	$re_member_no = trim($re_member_no);

	$query = "update tb_userinfo set 
				reg_status = '2',  
				confirm_person_date = now(),  
				confirm_person_ma = '$s_adm_name'  
				where member_no in $member_no";
		
	mysql_query($query) or die("Query Error");
		
	#echo $query."<BR>";

	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
		alert('처리 되었습니다.');
		document.location = 'user_confirm.php?member_no=$re_member_no';
		</script>";
	exit;

?>