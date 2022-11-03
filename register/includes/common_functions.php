<?php
function Redirect($url){
	echo "<script>location.href = '$url';</script>";	
	exit;
}


function cert_validation(){	
	if ($_SESSION['S_BIRTH'] == '' || $_SESSION['S_GENDER'] == '' || $_SESSION['S_NM'] == '')  {
		Redirect("./certification.php");
	}
}

function msg_err($msg){
	echo "<script>
		  alert(\"".$msg."\");
		  history.go(-1);
		 </script>";	
	exit;
}

function mobile_check(){
	$is_mobile = "P";
	$mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
	if (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
		$is_mobile = "M";
	}
	return $is_mobile;
}

function addParamRule($obj,$rule){

	$chk = 1;
	$obj = trim($obj);
	 
	if($obj){

	  //한글체크
		if(!eregi("kr",trim($rule))){
			if(preg_match("/[\xA1-\xFE\xA1-\xFE]/",$obj)) $chk = 0;
		}
	  
	  //영문체크
		if(!eregi("en",trim($rule))){
			if(preg_match("/[a-zA-Z]/",$obj)) $chk = 0;
		}
	  
	  //숫자체크
		if(!eregi("int",trim($rule))){
			if(preg_match("/[0-9]/",$obj)) $chk = 0;
		}

	  //특수문자체크
		if(!eregi("special",trim($rule))){
			if(preg_match("/[!#$%^&*()?+=\/]/",$obj)) $chk = 0;
		}

	  //echo $obj.":".$rule.":".$chk." // ";
			
	}
	return $chk;
}

function getRealIp(){
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP')) $ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR')) $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED')) $ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR')) $ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED')) $ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR')) $ipaddress = getenv('REMOTE_ADDR');
	else $ipaddress = 'unknown';
	return $ipaddress;
}


function removeXss($value){
	$value = mysql_real_escape_string(strip_tags($value));
	return $value;
}

//0000, 9999 연속번호로 등록시 국번초기화
function chkSameNumner($str){
	$str = str_replace(" ","",$str); //space
	$str = str_replace("	","",$str); //tab	
	if($str != ""){
		for($p=0; $p<=9; $p++) {
			$p1 = $p.$p.$p.$p;
			$p2 = $p.$p.$p;
			if($str == $p1 || $str == $p2){
				$str = "";
				break;
			}
		}
	}
	return $str;
}

function replaceCP949($str){
	$str = str_replace('샾', '샵', $str);
	$str = str_replace('ㆍ', '-', $str);
	$str = str_replace('·', '-', $str);
	$str = str_replace('잌·', '익', $str);
	$str = str_replace('믑', '읍', $str);
	$str = str_replace('틍', '층', $str);
	$str = str_replace('숖·', '숍', $str);
	return $str;
}


function debug($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
?>