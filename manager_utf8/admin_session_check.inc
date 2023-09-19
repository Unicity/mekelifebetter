<?php 
@session_start();
ini_set("display_errors", 0);
header("X-Frame-Options: SAMEORIGIN");

include_once($_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/str_check.php"); 

$s_adm_id = str_quote_smart_session($s_adm_id);

//미로그인 처리
if(!isset($_SESSION["s_adm_id"])){
	?>
	<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-Frame-Options" content="SAMEORIGIN" />
	<script language="javascript">
		alert("로그인 후 이용하여 주세요");
		parent.location="/manager_utf8/admin.php";
	</script>
	<?php 
	exit;
}

/*  //속도문제 고려 dbconn_utf8.inc 파일 하단으로 중복체크 이동
//관리자 중복 세션 체크를 위한 별도 db connection
$db = new mysqli('10.151.80.39', 'unicity_db_user', 'unicity!2011', 'makelifebetter');
if ($db->connect_errno){
	//die($this->link->connect_error);
}
$db->set_charset('utf8');

$result = $db->query("select * from tb_login where sess = '".session_id()."' and id = '".$_SESSION['s_adm_id']."' and del_tf='N'");
$obj = $result->fetch_object();
if($obj->sess == ""){
	session_destroy();
	?>
	<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-Frame-Options" content="SAMEORIGIN" />
	<script language="javascript">
		alert("다른 PC 또는 타 브라우져에서 로그인 중입니다.\n기존 접속을 종료합니다.");
		parent.location="/manager_utf8/admin.php";
	</script>		
	<?php
	exit;
}
//$db->close();
*/
?>