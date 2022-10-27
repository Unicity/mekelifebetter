<?
include "admin_session_check.inc";
include "../dbconn_utf8.inc";

if($_POST['gno'] == ""){
	echo "발송대상이 없습니다";
	exit;
}



$query = "select * from  tb_useroff_mail_list where gno = '".$_POST['gno']."'";
$result = mysql_query($query)  or die(mysql_error());
$row = mysql_fetch_array($result);

if($row[0] == ""){
	echo "조회대상이 없습니다";
	exit;
}

$wdate = date("Y-m-d H:i:s");

//echo "$row[snum], $row[enum], $row[cnt], $_SESSION[s_adm_id]";

//$sql = "update tb_useroff set email_send_yn='Y', email_send_date='$wdate', email_send_grp='$_POST[gno]', email_send_adm='$_SESSION[s_adm_id]' where mno >= $row[enum] and mno <= $row[snum] and email != ''";

$sql = "update tb_useroff set email_send_yn='Y', email_send_date='$wdate' where email_send_grp='".$_POST[gno]."' and mno >= $row[enum] and mno <= $row[snum] and email != ''";
$result = mysql_query($sql) or die(mysql_error());	

if($result){
	$result2 = mysql_query("update tb_useroff_mail_list set senddate='$wdate' where gno='".$_POST[gno]."'") or die(mysql_error());
	if(!$result2){
		echo "결과 업데이트가 되지 않았습니다.";
		exit;
	}
}else{
	echo "업데이트가 되지 않았습니다.";
	exit;
}

echo "OK";

@mysql_close();
exit;
?>