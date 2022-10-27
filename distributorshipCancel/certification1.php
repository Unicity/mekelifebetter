<?php 
	include "./includes/config/nc_config.php";	
	
	session_start(); 
	// 안심본인인증 서비스
	$sitecode = $Cb_SiteID;				// Site Code for Unicity Korea
    $sitepasswd = $Cb_SitePW;			// Site Password for Unicity Korea
    
    
    $cb_encode_path = $Cb_encode_path;		// module path (absolute path+module name)
	$authtype = ""; 

	$popgubun 	= "N";		//Y : 취소버튼 있음 / N : 취소버튼 없음
	$customize 	= "";			//없으면 기본 웹페이지 / Mobile : 모바일페이지
	
	$reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로

	// 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
 	$reqseq  = `$Cb_encode_path SEQ $Cb_SiteID`;
 	$isMobile = "1";
 	$useragent=$_SERVER['HTTP_USER_AGENT'];

 	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
 	{
 		$isMobile="2";
 	}

 
 	if($isMobile == "2"){
 		header("location:https://www.makelifebetter.co.kr/_m/join/certification_mobile.php");
 	exit();
 	}


	// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
	
	$returnurl = (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/distributorshipCancel/signup/cert_success1.php";	// 성공시 이동될 URL - Success
	$errorurl = (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/distributorshipCancel/signup/cert_fail.php";		// 실패시 이동될 URL - Fail
	 
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
	$sReturnURL					= (isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST']."/distributorshipCancel/signup/ipin_process.php";			// Return utl
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
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="./css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="./css/common.css"/>
	<link rel="stylesheet" type="text/css" href="./css/selectordie.css"/>
	<!--<link rel="stylesheet" type="text/css" href="./css/join_dev.css" /> -->
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

		console.log('value 1 : ' + document.form_chk.MEncodeData.value);
		console.log('value ' + document.form_chk.EncodeData.value);
		
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
 /* 자식창에서 바로 팝업 오픈 될 수 있도록 수정
 	function go_next_sign() {
			var trust_link = 'https://www.makelifebetter.co.kr/distributorshipCancel/distributorApply.php'
	     window.open(trust_link, '_blank','width=600, height=950');
 	     
	   	 //window.open("" ,"https://www.makelifebetter.co.kr/distributorshipCancel/distributorApply.php","toolbar=no, width=600, height=650, directories=no, status=no, scrollorbars=no, resizable=no"); 
 		
 	}
*/
</script>
</head>

<body>

	<div class="cont_wrap">
		<dl class="conttit_wrap">
		</dl>
		<div style="height: 10px;"></div>
		<div align="center">
			<font color="red" size="5px;">※  해지하시고자 하는 당사자 명의로 본인인증을 받아야 합니다.<br/>
			<예> 부사업자 해지를 희망 할 경우, 부사업자 명의로 본인인증을 받아야 하며,<br/>
			회원쉽 전체 해지를 희망 할 경우, 회원쉽(주사업자) 명의로 본인인증을 받아야 합니다.
			</font>
		</div>
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
			
				
		
			<li class="ty01" style="margin-left: 5%"><a href="javascript:fnPopupCb('M');">휴대폰 인증</a></li>

			<!--  <li class="ty02"><a href="javascript:fnPopupCb('C');">신용카드 인증</a></li>-->
			
			<li class="ty03"><a href="javascript:fnPopupCb('X');">공인인증서 인증</a></li>
			
			<li class="ty04"><a href="javascript:fnPopupIp();">I - Pin 인증</a></li>
		</ul>
		
		<ul class="certification_tip">
			<li style="margin-left: 10%">본인명의 휴대폰, 범용공인인증서 또는 I-PIN 인증을 통해 본인 인증을 하시기 바랍니다.</li>
		</ul>
	</div>
</body>
</html>