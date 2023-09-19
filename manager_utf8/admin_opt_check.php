<?php
session_start();

include "../dbconn_utf8.inc";
include "./inc/global_init.inc";
include "./inc/common_function.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST'){
	exit;
}
if(empty($_SESSION["tmp_s_adm_id"])){
	echo "login";
	exit;
}

$optpass = str_quote_smart(trim($_POST['optpass']));
$optcode = str_quote_smart(trim($_POST['optcode']));

if($optpass == "" || strlen($optcode) != 6){
	echo "OTP 코드를 바르게 입력하여 주세요";
	exit;
}

$query = "select id, passwd, Email, UserName, temp1, temp2, optpass, datediff(NOW(), pw_update_date) AS BB_DATEDIFF from tb_admin where id = '".$_SESSION["tmp_s_adm_id"]."'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
if($row['id'] == ""){
	echo "login";
	exit;
}

include_once $_SERVER['DOCUMENT_ROOT']."/manager_utf8/otp/GoogleAuthenticator.php";
$ga = new PHPGangsta_GoogleAuthenticator();
$oneCode = $ga->getCode($row['optpass']);

if($optcode == $oneCode){


	//기로그인 세션확인
	$result2 = mysql_query("select count(*) as cnt from tb_login where id = '".$row['id']."' and del_tf='N'") or die(mysql_error());	
	$row2 = mysql_fetch_array($result2);
	
	if($row2['cnt'] > 0){
		mysql_query("update tb_login set memo = '중복로그인', del_tf = 'Y' where id = '".$row['id']."' and del_tf = 'N'");
	}

	//로그인 테이블 등록	
	mysql_query("insert into tb_login (sess, id, logintime, ip) values ('".session_id()."', '".$row['id']."', '".time()."', '".getRealClientIp()."')");	

	//로그인 처리	
	$s_adm_id = str_quote_smart(trim($row['id']));
	$s_adm_email = str_quote_smart(trim($row['Email']));
	$s_adm_name = str_quote_smart(trim($row['UserName']));
	$s_flag = str_quote_smart(trim($row['temp1']));
	$s_adm_dept = str_quote_smart(trim($row['temp2']));
	$update_date = (int)trim($row['BB_DATEDIFF']);


	$_SESSION["s_adm_id"] = $s_adm_id; 
	$_SESSION["s_adm_email"] = $s_adm_email; 
	$_SESSION["s_adm_name"] = $s_adm_name; 
	$_SESSION["s_adm_dept"] = $s_adm_dept; 
	$_SESSION["s_flag"] = $s_flag; 


	//임시세션 초기화
	$_SESSION["tmp_s_adm_id"] = "";

	//last_login time update
	mysql_query("update tb_admin set last_login = '".time()."' where id = '".$s_adm_id."'");

	//login log
	logging($s_adm_id, "logged in");
	
	$_SESSION['reg'] = $s_adm_name;
	//if ($update_date >= 180 && $s_adm_id != 'admin') {
	if ($update_date >= 90) {
		echo "passchg";
	} else {
		echo "success";
	}

}else{

	echo "OTP 코드를 확인하여 주세요";
}

mysql_close($connect);

exit;