<?php 
ini_set('display_errors', 1);
set_time_limit(0); 
session_start(); 

if (!include_once("./includes/dbconfig.php")){
	echo "The config file could not be loaded";
}

$str_message = "유니시티코리아 회원가입이 완료되었습니다 회원번호 test입니다 감사합니다";
$query = "Insert into sms_msg (compkey, id, phone, callback, status, wrtdate, reqdate, msg, etc1, etc2, etc3) values ('123456','tb_userinfo','01030777531', '0424821860', '0', sysdate(), sysdate(), '$str_message','WEB','test','D') ";
$result_update = mysql_query($query) or die(mysql_error());