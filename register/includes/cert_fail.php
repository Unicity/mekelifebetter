<?php

	session_start();
	if (!include_once("../includes/dbconfig.php")){
		echo "The config file could not be loaded";
	}

	$user_device = "P";
	$mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
	if (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
		$user_device = "M";
	}
	//**************************************************************************************************************
	//NICE신용평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED

	//서비스명 :  체크플러스 - 안심본인인증 서비스
	//페이지명 :  체크플러스 - 결과 페이지

	//보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다. 
	//**************************************************************************************************************
	
	//include "../korea/includes/nc_config.php";

	if(!extension_loaded('CPClient')) {
		dl('CPClient.' . PHP_SHLIB_SUFFIX);
	}
	$CP_module = 'CPClient';

	//크롬80 업데이트에 따른 수정 2020.02.03
	if($_GET["EncodeData"] != ""){		
		$enc_data = $_GET["EncodeData"];		// 암호화된 결과 데이타
		$sReserved1 = $_GET['param_r1'];		
		$sReserved2 = $_GET['param_r2'];
		$sReserved3 = $_GET['param_r3'];
	}else if($_POST["EncodeData"] != ""){
		$enc_data = $_POST["EncodeData"];		// 암호화된 결과 데이타
		$sReserved1 = $_POST['param_r1'];		
		$sReserved2 = $_POST['param_r2'];
		$sReserved3 = $_POST['param_r3'];
	}


	//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
	if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {echo "입력 값 확인이 필요합니다 : ".$match[0]; exit;} // 문자열 점검 추가. 
	if(base64_encode(base64_decode($enc_data))!=$enc_data) {echo "입력 값 확인이 필요합니다"; exit;}

	if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved1, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
	if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved2, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
	if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved3, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	if ($enc_data != "") {
		
		$function = 'get_decode_data';// 암호화된 결과 데이터의 복호화
		if (extension_loaded($CP_module)) {
			$plaindata = $function($sitecode, $sitepasswd, $enc_data);
		} else {
			$plaindata = "Module get_response_data is not compiled into PHP";
		}
		//echo "[plaindata] " . $plaindata . "<br>";

		if ($plaindata == -1){
			$returnMsg  = "암/복호화 시스템 오류";
		}else if ($plaindata == -4){
			$returnMsg  = "복호화 처리 오류";
		}else if ($plaindata == -5){
			$returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
		}else if ($plaindata == -6){
			$returnMsg  = "복호화 데이터 오류";
		}else if ($plaindata == -9){
			$returnMsg  = "입력값 오류";
		}else if ($plaindata == -12){
			$returnMsg  = "사이트 비밀번호 오류";
		}else{
			// 복호화가 정상적일 경우 데이터를 파싱합니다.
			$function = 'get_cipher_datetime';// 암호화된 결과 데이터 검증 (복호화한 시간획득)
			if (extension_loaded($CP_module)) {
				$ciphertime = $function($sitecode,$sitepasswd,$enc_data);
			} else {
				$ciphertime = "Module get_cipher_datetime is not compiled into PHP";
			}

			$requestnumber = GetValue($plaindata , "REQ_SEQ");
			$errcode = GetValue($plaindata , "ERR_CODE");
			$authtype = GetValue($plaindata , "AUTH_TYPE");
		}
	}

		$qlog = "insert into tb_log_v2 (tmpId, gubun, check_kind, name, jumin1, jumin2, phone, data1, data2, recieveData, msg, flag, device, logdate) values 
			( '".$_SESSION['ssid']."', '본인인증-성공', '$authtype', '".iconv("EUC-KR","UTF-8",$name)."', '$birthdate', '$dupinfo', '$mobile_no', '$requestnumber', '$responsenumber', '".iconv("EUC-KR","UTF-8",$plaindata)."', '$returnMsg', 'N', '$user_device', now())";	
		mysql_query($qlog) or die(mysql_error());

		mysql_close($connect);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>::::: 유니시티 코리아 :::::</title>
<script type="text/javascript">
<!--

	function init() {
		alert("본인 인증이 실패 하였습니다.");
		self.close();
	}

//-->
</script>
</head>
<body onload="init();">
</body>
</html>
<?
	function GetValue($str , $name) {
		$pos1 = 0;  //length의 시작 위치
		$pos2 = 0;  //:의 위치

		while( $pos1 <= strlen($str) ) {
			$pos2 = strpos( $str , ":" , $pos1);
			$len = substr($str , $pos1 , $pos2 - $pos1);
			$key = substr($str , $pos2 + 1 , $len);
			$pos1 = $pos2 + $len + 1;
			if( $key == $name ) {
				$pos2 = strpos( $str , ":" , $pos1);
				$len = substr($str , $pos1 , $pos2 - $pos1);
				$value = substr($str , $pos2 + 1 , $len);
				return $value;
			} else {
				// 다르면 스킵한다.
				$pos2 = strpos( $str , ":" , $pos1);
				$len = substr($str , $pos1 , $pos2 - $pos1);
				$pos1 = $pos2 + $len + 1;
			}
		}
	}
?>
