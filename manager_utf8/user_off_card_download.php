<?
include "admin_session_check.inc";

include "../dbconn_utf8.inc";

if($_GET['mno'] == ""){
	echo'
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript">
	alert("대상이 없습니다");
	history.back();
	</script>';
	exit;
}

$result = mysql_query("select * from tb_useroff where mno = '".$_GET['mno']."'") or die(mysql_error());	
$row = mysql_fetch_array($result);

if($row[0] == ""){
	echo'
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript">
	alert("조회대상이 없습니다");
	self.close();
	</script>';
	exit;
}

$fixNum = sprintf("%010d",$row[mno]);
$cardNum = $row[reg_num].$fixNum;


$saveImg = $_SERVER['DOCUMENT_ROOT']."/formmail/card/".$cardNum.".png";

if(!file_exists($saveImg)){
	echo'
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript">
	alert("파일이 없습니다");
	history.back();
	</script>';
	exit;
}
$filesize = filesize($saveImg);
$fileName = "회원카드_".$row['name']."_".$row['reg_num'].".png";
   
header('Content-Type: application/x-octetstream');
header('Content-Length: '.filesize($saveImg));
header('Content-Disposition: attachment; filename='.$fileName);
header('Content-Transfer-Encoding: binary');

$fp = fopen($saveImg, "r");
fpassthru($fp);
fclose($fp);
?>