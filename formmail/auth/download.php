<?
if($_GET['cnum'] == "" || $_GET['name'] == ""){
	echo'
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript">
	alert("대상이 없습니다");
	history.back();
	</script>';
	exit;
}

$saveImg = $_SERVER['DOCUMENT_ROOT']."/formmail/card/".$_GET['cnum'].".png";

if(!file_exists($saveImg)){
	echo'
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript">
	alert("파일이 없습니다");
	history.back();
	</script>';
}
$filesize = filesize($saveImg);
$fileName = "회원카드_".urldecode($_GET['name']).".png";
   
header('Content-Type: application/x-octetstream');
header('Content-Length: '.filesize($saveImg));
header('Content-Disposition: attachment; filename='.$fileName);
header('Content-Transfer-Encoding: binary');

$fp = fopen($saveImg, "r");
fpassthru($fp);
fclose($fp);
?>