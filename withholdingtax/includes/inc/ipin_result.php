<?php

	session_start();

	include "./nc_config.php";
	if (!include_once("./dbconfig.php")){
		echo "The config file could not be loaded";
	}


	$user_device = "P";
	$mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
	if (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
		$user_device = "M";
	}
	
?>

<?php

	/********************************************************************************************************************************************
		NICE신용평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED
		
		서비스명 : 가상주민번호서비스 (IPIN) 서비스
		페이지명 : 가상주민번호서비스 (IPIN) 사용자 인증 정보 결과 페이지
		
				   수신받은 데이터(인증결과)를 복호화하여 사용자 정보를 확인합니다.
	*********************************************************************************************************************************************/

	
	$sSiteCode					= $Ip_SiteID;			// IPIN 서비스 사이트 코드		(NICE평가정보에서 발급한 사이트코드)
	$sSitePw					= $Ip_SitePW;			// IPIN 서비스 사이트 패스워드	(NICE평가정보에서 발급한 사이트패스워드)

	$sEncData					= "";			// 암호화 된 사용자 인증 정보
	$sDecData					= "";			// 복호화 된 사용자 인증 정보
	$sRtnMsg					= "";			// 처리결과 메세지

	$sModulePath				= $Ip_encode_path;

	$sEncData = $_POST['enc_data'];	// ipin_process.php 에서 리턴받은 암호화 된 사용자 인증 정보

	//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
	if(preg_match('~[^0-9a-zA-Z+/=]~', $sEncData, $match)) {echo "입력 값 확인이 필요합니다"; exit;}
	if(base64_encode(base64_decode($sEncData))!=$sEncData) {echo "입력 값 확인이 필요합니다!"; exit;}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////  
	
	// ipin_main.php 에서 저장한 세션 정보를 추출합니다.
	// 데이타 위변조 방지를 위해 확인하기 위함이므로, 필수사항은 아니며 보안을 위한 권고사항입니다.
	$sCPRequest = $_SESSION['CPREQUEST'];
	 
	if ($sEncData != "") {

	// Decrypt user informaiton
    	// 
    	$sDecData = `$sModulePath RES $sSiteCode $sSitePw $sEncData`;

		//echo "2";
		
		if ($sDecData == -9) {
			$sRtnMsg = "입력값 오류 : 복호화 처리시, 필요한 파라미터값의 정보를 정확하게 입력해 주시기 바랍니다.";
		} else if ($sDecData == -12) {
			$sRtnMsg = "NICE신용평가정보에서 발급한 개발정보가 정확한지 확인해 보세요.";
		} else {
			
			/*
			- 복호화된 데이타 구성
			'데이터에 대한 byte:데이터' 형식으로 구성되어 있습니다.
			*/



			$arrData = split(":", $sDecData);
			$iCount = count($arrData);
			 
			if ($iCount >= 5) {

				/*
				다음과 같이 사용자 정보를 추출할 수 있습니다.
				사용자에게 보여주는 정보는, '이름' 데이타만 노출 가능합니다.
				
				사용자 정보를 다른 페이지에서 이용하실 경우에는
				보안을 위하여 암호화 데이타($sEncData)를 통신하여 복호화 후 이용하실것을 권장합니다. (현재 페이지와 같은 처리방식)
					
				만약, 복호화된 정보를 통신해야 하는 경우엔 데이타가 유출되지 않도록 주의해 주세요. (세션처리 권장)
				form 태그의 hidden 처리는 데이타 유출 위험이 높으므로 권장하지 않습니다.
				*/
				
				$strResultCode		= GetValue($sDecData, "RESULT_CODE");			// 결과코드
				 
				if ($strResultCode == 1) {
					$strCPRequest	= GetValue($sDecData, "CPREQUESTNO");				// CP 요청번호
					
					if ($sCPRequest == $strCPRequest) {
				
						$sRtnMsg = "사용자 인증 성공";

						$strVno      			= GetValue($sDecData, "VNUMBER");	// 가상주민번호 (13자리이며, 숫자 또는 문자 포함)
						$strUserName			= iconv("EUC-KR", "UTF-8",GetValue($sDecData, "NAME"));	// 이름
						$strDupInfo				= GetValue($sDecData, "DUPINFO");	// 중복가입 확인값 (64Byte 고유값)
						$strAgeInfo				= GetValue($sDecData, "AGECODE");	// 연령대 코드 (개발 가이드 참조)
						$strGender				= GetValue($sDecData, "GENDERCODE");	// 성별 코드 (개발 가이드 참조)
						$strBirthDate			= GetValue($sDecData, "BIRTHDATE");	// 생년월일 (YYYYMMDD)
						$strNationalInfo		= GetValue($sDecData, "NATIONALINFO");	// 내/외국인 정보 (개발 가이드 참조)
						$strAuthInfo		= GetValue($sDecData, "AUTHMETHOD");      // 본인확인 수단 (개발 가이드 참조)
						$strCoInfo				= GetValue($sDecData, "COINFO1");	// 연계정보 확인값 (CI - 88 byte 고유값)
						$strCIUpdate		= GetValue($sDecData, "CIUPDATE");		// CI 갱신정보
					
					} else {
						$sRtnMsg = "CP 요청번호 불일치 : 세션에 넣은 $sCPRequest 데이타를 확인해 주시기 바랍니다.";
					}
				} else {
					$sRtnMsg = "리턴값 확인 후, NICE신용평가정보 개발 담당자에게 문의해 주세요. [$strResultCode]";
				}

			} else {
				$sRtnMsg = "리턴값 확인 후, NICE신용평가정보 개발 담당자에게 문의해 주세요.";
			}

		}
	} else {
		$sRtnMsg = "처리할 암호화 데이타가 없습니다.";
	}
	 
	if ($sRtnMsg == "사용자 인증 성공") {

		 
	 
		// DI 값 등을 세션에 ,, 
		$_SESSION["S_CI"]			= $strCoInfo;
		$_SESSION["S_DI"]			= $strDupInfo;
		$_SESSION["S_BIRTH"]	= $strBirthDate;
		$_SESSION["S_GENDER"] = $strGender;
		$_SESSION["S_NM"] =  $strUserName;

	} else {

	 
	}

	$yn = ($sRtnMsg == "사용자 인증 성공") ? "Y" : "N";
	$qlog = "insert into tb_log_v2 (tmpId, gubun, check_kind, name, jumin1, jumin2, gender, phone, data1, data2, recieveData, msg, flag, device, logdate) values 
		( '".$_SESSION['ssid']."', '본인인증원천-".$resultCode."(ipin)', 'ipin', '$strUserName', '$strBirthDate', '$strDupInfo', '$strGender', '$mobile_no', '$strResultCode', '$strCPRequest', '$sDecData', '$sRtnMsg', '$yn', '$user_device', now())";	
	mysql_query($qlog) or die(mysql_error());	

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>NICE신용평가정보 가상주민번호 서비스</title>
<script type="text/javascript">
<!--

	function init() {
		alert("본인 인증이 성공 하였습니다.");
		document.location = "../../selectperiod.php";
	}

//-->
</script>
<body onload="init();">
	<form name="user" method="post">
		<input type="hidden" name="enc_data" value="<?= $sEncData ?>"><br>
	</form>
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