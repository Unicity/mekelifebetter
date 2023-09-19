<?
include "admin_session_check.inc";
include "../dbconn_utf8.inc";
include "./inc/common_function.php";

foreach($_POST as $key=>$val) {
	${$key} = strip_tags(urldecode($val));
}

if($ex_cate == "" || $ex_page == "" || $ex_type == "" || $ex_task == ""){
	//echo "주요항목이 누락되었습니다.";
	//exit;
}

$query = "insert into tb_excel_log (ex_cate, ex_page, ex_detail, ex_type, ex_task, ex_adm, ex_date, ex_ip) values ('".$ex_cate."', '".$ex_page."', '".$ex_detail."', '".$ex_type."', '".$ex_task."', '".$_SESSION['s_adm_id']."', now(), '".getRealClientIp()."') ";
$result = mysql_query($query)  or die(mysql_error());

if($result) echo "OK";
else echo "로그 등록에 실패하였습니다";

@mysql_close();
exit;
?>