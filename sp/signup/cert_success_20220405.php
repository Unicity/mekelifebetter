<?php
	session_start();
	if (!include_once("../includes/config/dbconfig.php")){
		echo "The config file could not be loaded";
	}

	include "../includes/config/nc_config.php";
	//**************************************************************************************************************
	//NICE신용평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED
	
	//서비스명 :  체크플러스 - 안심본인인증 서비스
	//페이지명 :  체크플러스 - 결과 페이지
	
	//보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다.
	//**************************************************************************************************************
	
	//include "../korea/includes/nc_config.php";
	
	$CP_module = $Cb_encode_path;
	
	$sitecode = $Cb_SiteID	;					// NICE로부터 부여받은 사이트 코드
	$sitepasswd = $Cb_SitePW;				// NICE로부터 부여받은 사이트 패스워드
	$flagTT = $_GET["flag"];	

	echo $flagTT;
	
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
	
//	if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved1, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
//	if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved2, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
//	if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved3, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	if ($enc_data != "") {
	    /*
	     $function = 'get_decode_data';// 암호화된 결과 데이터의 복호화
	     if (extension_loaded($CP_module)) {
	     $plaindata = $function($sitecode, $sitepasswd, $enc_data);
	     } else {
	     $plaindata = "Module get_response_data is not compiled into PHP";
	     }
	     */
	    $plaindata = `$CP_module DEC $sitecode $sitepasswd $enc_data`;
	    
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
	        /*$function = 'get_cipher_datetime';// 암호화된 결과 데이터 검증 (복호화한 시간획득)
	         if (extension_loaded($CP_module)) {
	         $ciphertime = $function($sitecode,$sitepasswd,$enc_data);
	         } else {
	         $ciphertime = "Module get_cipher_datetime is not compiled into PHP";
	         }*/
	        
	        $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;
	        $requestnumber = GetValue($plaindata , "REQ_SEQ");
	        $responsenumber = GetValue($plaindata , "RES_SEQ");
	        $authtype = GetValue($plaindata , "AUTH_TYPE");
	        $name = GetValue($plaindata , "NAME");
	        $birthdate = GetValue($plaindata , "BIRTHDATE");
	        $gender = GetValue($plaindata , "GENDER");
	        $nationalinfo = GetValue($plaindata , "NATIONALINFO");	//내/외국인정보(사용자 매뉴얼 참조)
	        $dupinfo = GetValue($plaindata , "DI");
	        $conninfo = GetValue($plaindata , "CI");
	        $mobile_no = GetValue($plaindata , "MOBILE_NO");
	        
	        //	echo 'session : '.$_SESSION["REQ_SEQ"].'<br> req : '.$requestnumber;
	        
	        if(strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0) {
	            echo "Session Value wrong.". iconv("EUC-KR","UTF-8","세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.<br>");
	            
	            $querylog = "insert into tb_session_check_log (name, data1, data2, chkdate) values
										('".iconv("EUC-KR","UTF-8",$name)."', '".$_SESSION["REQ_SEQ"]."'.'$requestnumber', now())";
	            
	            mysql_query($querylog) or die("tb_session_check_log Query Error");
	            mysql_close($connect);
	            
	            $requestnumber = "";
	            $responsenumber = "";
	            $authtype = "";
	            $name = "";
	            $birthdate = "";
	            $gender = "";
	            $nationalinfo = "";
	            $dupinfo = "";
	            $conninfo = "";
	            $mobile_no = "";
	        }
	    }
	}
	?>
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

	if ($authtype <> "") {

		$querylog = "insert into tb_check_log (check_kind, name, jumin1, jumin2, data1, data2, flag, chkdate) values 
		( '$authtype', '".iconv("EUC-KR","UTF-8",$name)."', '$birthdate', '$dupinfo', '$requestnumber', '$responsenumber', 'Y', now())";

		mysql_query($querylog) or die("tb_check_log Query Error");
		mysql_close($connect);

		// DI 값 등을 세션에 ,, 
	 
		$_SESSION["S_CI"] = $conninfo;
		
		$_SESSION["S_DI"] = $dupinfo;
		$_SESSION["S_BIRTH"] = $birthdate;
		$_SESSION["S_GENDER"] = $gender;
		$_SESSION["S_NM"] = iconv("EUC-KR","UTF-8",$name);
		$_SESSION["S_MOBILE_NO"] = $mobile_no;
		$confirmTime= date("h:i:s");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>
<title>::::: 유니시티 코리아 :::::</title>
<script type="text/javascript">
var birthdate = "<?php echo $birthdate?>";
var s_nm = "<?php echo iconv("EUC-KR","UTF-8",$name) ?>";
var mChkDate = "<?php echo $confirmTime ?>";


	function init() {
		
		<?	
		if($sReserved2=='y'){
			$distID = '';
			$fullName = '';
			if($sReserved3){
				
				//list($distID,$fullName) = explode(':',$sReserved3);
			}
			?>
				try
				{
					$(document).ready(function(){
				
					$('#sReserved1').val('<?=$sReserved1?>');
					$('#jsonValue').val('<?=$sReserved3?>');
					$('#parentInputName').val(s_nm);
					$("#form").submit();
				
					});
				}
				catch (e)
				{
				}
			<?
		}else {}
		?>

		$logQuery= "insert into ChangeSponsorLog (name, logDate, spName, updateInsert,Id) value('".$name."',now(),'".$name."','".$updateInsert."','".$distID."')";

		mysql_query($logQuery) or die("ChangeSponsorSMSQuery-".mysql_error());
		
		alert("본인 인증이 성공 하였습니다.");
		window.opener.agreeForm.parentInputName.value = s_nm;

		$(opener.location).attr("href","javascript:go_next_sign()");
		
		//opener.go_next_sign();
		self.close();
	}

</script>
</head>
<body onload="init();">
<form method="post" id="form" action="https://www.makelifebetter.co.kr/sp/changeSponsor.php?mobile=y">
	<input type="hidden" name="parentInputName" id="parentInputName" value="" />
	<input type="hidden" name="jsonValue" id="jsonValue" value="" />
</form>
</body>
</html>
<?
	} else {
		$querylog = "insert into tb_check_log (check_kind, name, jumin1, jumin2, data1, data2, flag, chkdate) values 
		( '$authtype', '".iconv("euc-kr","utf-8",$name)."', '$birthdate', '$dupinfo', '$requestnumber', '$responsenumber', 'N', now())";

		mysql_query($querylog) or die("Query Error");
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
		opener.sign();
	}

//-->
</script>
</head>
<body onload="init();">
</body>
</html>
<?
	}
?>