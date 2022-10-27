<?php 
	include "./includes/inc/nc_config.php";
	include "./includes/inc/common_functions.php";
	
	session_start(); 
	$sid = $_POST['sid'];
	$token = $_POST['token'];
	
	$_SESSION["token"] = $token;
	$_SESSION["username"] = $sid;
	// 안심본인인증 서비스
	//token_validation();
	
	//getRealClientIp();
	
	
	$sitecode = $Cb_SiteID;				// Site Code for Unicity Korea
    $sitepasswd = $Cb_SitePW;			// Site Password for Unicity Korea
    
    $cb_encode_path = $Cb_encode_path;		// module path (absolute path+module name)
	$authtype = ""; 

	$popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
	$customize 	= "";			//없으면 기본 웹페이지 / Mobile : 모바일페이지
	
	$reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로

	// 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
 	$reqseq  = `$Cb_encode_path SEQ $Cb_SiteID`;
	
 
	 
	// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
	
	//$returnurl = (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/withholdingtax/includes/inc/cert_success.php";	// 성공시 이동될 URL - Success
	$returnurl = (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']."/withholdingtax/includes/inc/cert_success.php";	// 
	$errorurl = (isset($_SERVER['HTTPS']) ? "https" : "https")."://".$_SERVER['HTTP_HOST']."/withholdingtax/includes/inc/cert_fail.php";		// 실패시 이동될 URL - Fail
	 
	// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.

	$_SESSION["REQ_SEQ"] = $reqseq;

	$defaultdata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
			    			  "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
			    			  "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
			    			  "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
			    			  "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
			    			  "9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;
    // plain data for enc_data.
    $plaindata = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	
	$authtype = "X";
    $plaindataX = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	
	$authtype = "M";
    $plaindataM = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	
	$authtype = "C";
    $plaindataC = $defaultdata."9:AUTH_TYPE" . strlen($authtype) . ":". $authtype ;
	 
    $enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;
   
	$enc_dataM = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataM`;
	$enc_dataX = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataX`;
	$enc_dataC = `$cb_encode_path ENC $sitecode $sitepasswd $plaindataC`;
	

    if( $enc_data == -1 )
    {
        $returnMsg = "encryption error.";
        $enc_data = "";
    }
    else if( $enc_data== -2 )
    {
        $returnMsg = "encryption process error.";
        $enc_data = "";
    }
    else if( $enc_data== -3 )
    {
        $returnMsg = "encryption data error.";
        $enc_data = "";
    }
    else if( $enc_data== -9 )
    {
        $returnMsg = "input value error.";
        $enc_data = "";
    }
	
	 
	// IPIN server  part 
	$sSiteCode					= $Ip_SiteID;			// IPIN service site code
	$sSitePw					= $Ip_SitePW;			// IPIN service site password 
	$sModulePath				= $Ip_encode_path;			// Module Path
	$sReturnURL					= (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/withholdingtax/includes/inc/ipin_process.php";			// Return utl
	$sCPRequest					= "";			// when result == success, we need to store sCPRequest value and verify from the success page
		 
	$sCPRequest = `$sModulePath SEQ $sSiteCode`;
	
	$_SESSION['CPREQUEST'] = $sCPRequest;
    
    $sEncData					= "";			// encrypted data
	$sRtnMsg					= "";			// return result msg
	
    // Encryption
    $sEncData	= `$sModulePath REQ $sSiteCode $sSitePw $sCPRequest $sReturnURL`;
    
    // result
    if ($sEncData == -9)
    {
    	$sRtnMsg = "Input value error : one of the parameters are wrong. fail to encrypt";
    } else {
    	$sRtnMsg = "$sEncData has to be encrypted now. if now please contact admin.";
    }
    
    
        
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
                else if(getenv('HTTP_X_FORWARDED'))
                    $ipaddress = getenv('HTTP_X_FORWARDED');
                    else if(getenv('HTTP_FORWARDED_FOR'))
                        $ipaddress = getenv('HTTP_FORWARDED_FOR');
                        else if(getenv('HTTP_FORWARDED'))
                            $ipaddress = getenv('HTTP_FORWARDED');
                            else if(getenv('REMOTE_ADDR'))
                                $ipaddress = getenv('REMOTE_ADDR');
                                else
                                    $ipaddress = 'UNKNOWN';
                                    return $ipaddress;
    }
    echo "SERVER 함수 사용자 아이피 : ".$_SERVER['REMOTE_ADDR'];
    echo "<br>";
    echo "getenv 사용자 아이피 : ".get_client_ip()
    
   
        
        
        
 
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./includes/css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="./includes/css/common.css"/>
	<link rel="stylesheet" type="text/css" href="./includes/css/selectordie.css"/>
	<script type="text/javascript" src="./includes/js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src='./includes/js/common.js'></script>
	<script type="text/javascript" src="./includes/js/selectordie.min.js"></script>
	<title>본인인증</title>
<script type="text/javascript">

	// 인증
	window.name ="Parent_window";
	
	function fnPopupCb(type){

		window.open('', 'popupChk', 'width=408, height=655, top=100, left=100, fullscreen=no, resizable=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		
		if (type == "M") {
			document.form_chk.EncodeData.value = document.form_chk.MEncodeData.value;
		} else if (type == "C") {
			document.form_chk.EncodeData.value = document.form_chk.CEncodeData.value;
		} else if (type == "X") {
			document.form_chk.EncodeData.value = document.form_chk.XEncodeData.value;
		}

		document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		document.form_chk.target = "popupChk";
		document.form_chk.submit();
		
	}

	function fnPopupIp(){
		window.open('', 'popupIPIN', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_ipin.target = "popupIPIN";
		document.form_ipin.action = "https://cert.vno.co.kr/ipin.cb";
		document.form_ipin.submit();
 	}
 
 	function go_next_sign() {
 		console.log('a');
 		document.location = '../../selectperiod_test.php';
 	}

</script>
</head>

<body>

		<div class="cont_wrap">
			<dl class="conttit_wrap">
				<dt>본인인증</dt>
				<dd>원천징수 영수증 출력시 본인인증을 하셔야 합니다.</dd>
			</dl>
			
			<!-- need this form for the certificate verification popup. -->
	<form name="form_chk" method="post">
		<input type="hidden" name="m" value="checkplusSerivce">						<!-- Mandatory data. -->
		<input type="hidden" name="EncodeData" value="<?=$enc_data?>">		<!-- encrypted data for unicity korea -->
	    <input type="hidden" name="MEncodeData" value="<?=$enc_dataM?>">
		<input type="hidden" name="CEncodeData" value="<?=$enc_dataC?>">
		<input type="hidden" name="XEncodeData" value="<?=$enc_dataX?>">
	    <!-- extra fields that we can request from the vendor. leave them blank for now -->
		<input type="hidden" name="param_r1" value="">
		<input type="hidden" name="param_r2" value="">
		<input type="hidden" name="param_r3" value="">
	</form>
	
	<!-- Ipin verification -->
	<form name="form_ipin" method="post">
		<input type="hidden" name="m" value="pubmain">						<!-- 필수 데이타로, 누락하시면 안됩니다. -->
		<input type="hidden" name="enc_data" value="<?= $sEncData ?>">		<!-- 위에서 업체정보를 암호화 한 데이타입니다. -->
		<input type="hidden" name="param_r1" value="">
		<input type="hidden" name="param_r2" value="">
		<input type="hidden" name="param_r3" value="">
	</form>
	
	<form name="vnoform" method="post">
	<input type="hidden" name="enc_data">	
	<input type="hidden" name="param_r1" value="">
    <input type="hidden" name="param_r2" value="">
    <input type="hidden" name="param_r3" value="">
	</form>

			<ul class="certification_list">
				<li class="ty01"><a href="javascript:fnPopupCb('M');">휴대폰 인증</a></li>
				<!--<li class="ty02"><a href="javascript:fnPopupCb('C');">신용카드 인증</a></li>-->
				<li class="ty03"><a href="javascript:fnPopupCb('X');">공인인증서 인증</a></li>
				<li class="ty04"><a href="javascript:fnPopupIp();">I - Pin 인증</a></li>
			</ul>
			<ul class="certification_tip">
				<li>본인명의 휴대폰, 범용공인인증서 또는 I-PIN 인증을 통해 본인 인증을 하시기 바랍니다.</li>
			</ul>
		</div>
</body>
</html>
 