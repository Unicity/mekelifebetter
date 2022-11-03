<?php 

	header("Content-type: text/html; charset=euc-kr");
	session_start();

	/********************************************************************************************************************************************
	NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED
	
	서비스명 : 주민번호 실명확인 서비스 
	페이지명 : 주민번호 실명확인 서비스 처리 페이지

	방화벽 이용 시 아래 IP와 Port를 오픈해주셔야 합니다.
	IP : 203.234.219.72 / Port : 81 ~ 85
	*********************************************************************************************************************************************/
	
	$sSiteCode = "Q662"; 		// NICE평가정보에서 발급한 실명확인 서비스 사이트코드
	$sSitePw = "14622139";			// NICE평가정보에서 발급한 실명확인 서비스 사이트패스워드
   
	// 모듈의 절대경로 (권한:755, FTP업로드방식: 바이너리)
	// ex) $cb_encode_path = 'C:\\module\\cb_namecheck.exe'
	//     $cb_encode_path = '/root/module/RNCheck'
	$cb_encode_path = "/home/httpd/unicity/cb_namecheck/cb_namecheck";

	// 데이터 위변조 검사용 변수 (생략 가능)
	$sReqKey = "Q662";
	$sReqNo = "14622139";

	// 인증결과코드 초기화
	$iReturnCode = "";

	// 결과메세지 초기화
	$sRtnMsg = "";

	// 입력 페이지에서 전달된 입력값 취득
	$strJumin = $_POST["jumin1"].$_POST["jumin2"];
	$strName = $_POST["name"];		
	$callback = $_REQUEST['callback'];

	
	///////////////////////////////////////////문자열 점검///////////////////////////////////////////////////
	if(preg_match("/[#\&\\+\-%@=\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $strJumin, $match)) exit;
	if(preg_match("/[#\&\\+\-%@=\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $strName, $match)) exit;
	///////////////////////////////////////////////////////////////////////////////////////////////////////////

	// 입력값 확인
	if(!empty($strJumin) && !empty($strName)){
		// 데이터 위변조 검사 (생략가능)
		// 입력 페이지에서 세션에 저장된 값과 비교해 위변조 검사  
		if(empty($_SESSION[$sReqKey]) || $_SESSION[$sReqKey] != $sReqNo)
		{
			// 세션값 불일치 시 처리
			// $sRtnMsg = "세션값이 일치하지 않습니다. 다시 인증해주십시오.";
		}
		// else
		// {
			// 실명인증 처리
			// 실행방법은 싱글쿼터(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
			// 예) $iReturnCode = system($cb_encode_path . ' ' . $sSiteCode . ' ' . $sSitePw . ' ' . $strJumin . ' "' . $strName . '"');
			
			
			$iReturnCode = `$cb_encode_path $sSiteCode $sSitePw $strJumin $strName`;
		// }

		// 실명인증 결과코드 생성여부 확인
		if(empty($iReturnCode)){	
			$sRtnMsg = "인증 미실행 오류: 모듈의 경로와 권한을 확인해주시기 바랍니다.";
		}
	}
	else
	{
		$sRtnMsg = "입력값 미전달 오류: 성명이나 주민번호가 전달되지 않았습니다.";
	}

	// 실명인증 결과코드 확인 
	if(!empty($iReturnCode))
	{	
		if ($iReturnCode == "1")
		{
			$sRtnMsg = "인증성공";
		}
		else if($iReturnCode == "2")
		{
			$sRtnMsg = "성명불일치 오류: 주민번호와 성명이 일치하지 않습니다.<br>www.niceid.co.kr 에서 실명정보를 재등록하시거나 NICE 고객센터(1600-1522)로 문의해주십시오.";
		}
		else if($iReturnCode == "3")
		{
			$sRtnMsg = "자료없음 오류: 주민번호가 조회되지 않습니다.<br>www.niceid.co.kr 에서 실명정보를 등록하시거나  NICE 고객센터(1600-1522)로 문의해주십시오.";
		}
		else if($iReturnCode == "5")
		{
			$sRtnMsg = "주민번호 체크썸 오류: 주민번호 생성규칙에 맞지 않는 주민번호입니다.";
		}
		else if($iReturnCode == "9")
		{
			$sRtnMsg = "입력정보 오류: 입력정보가 누락되었거나 정상이 아닙니다."
					. "<br>입력된 사이트코드, 패스워드, 주민번호, 성명 정보를 확인해주시기 바랍니다."
					. "<br>일부 고객만 발생하는 경우 부정사용으로 인한 차단 오류입니다. 차단 처리는 일정 시간이 지나면 자동으로 해제됩니다.";
		}
		else if($iReturnCode == "10")
		{
			$sRtnMsg = "사이트 코드 오류: 사이트코드를 대문자로 입력해주십시오."
					. "<br>사이트코드가 정상인 경우 내/외국인 설정 관련 오류입니다. (예:내국인 인증 계약 후 외국인 인증)"
					. "<br>내/외국인 설정에 맞게 이용한 경우 NICE 계약/관리 담당자에게 정확한 설정 상태를 문의해주십시오.";
		}
		else if($iReturnCode == "11")
		{
			$sRtnMsg = "정지된 사이트 오류: 서비스 계약이 정지된 사이트입니다. NICE 계약/관리 담당자에게 문의해주십시오.";
		}
		else if($iReturnCode == "12")
		{
			$sRtnMsg = "패스워드 불일치 오류: 사이트 패스워드가 일치하지 않습니다. NICE 전산 담당자에게 문의해주십시오.";
		}
		else if($iReturnCode == "21")
		{
			$sRtnMsg = "입력정보 형식 오류: 입력정보의 자릿수를 확인해주십시오. (주민번호:13자리, 패스워드: 8자리)";
		}
		else if($iReturnCode == "31" || $iReturnCode == "32" || $iReturnCode == "34"|| $iReturnCode == "44")
		{
			$sRtnMsg = "통신오류: 당사 서비스 IP를 방화벽에 등록해주십시오.<br>IP:203.234.219.72<br>port:81~85(총 5개 모두 등록)";
		}
		else if($iReturnCode == "50")
		{
			$sRtnMsg="명의도용차단 오류: 명의도용차단 서비스 이용 중인 주민번호입니다."
					. "<br>www.credit.co.kr에서 명의도용차단 서비스 해제 후 재시도 하시거나 NICE고객센터(1600-1522)로 문의해주십시오.";
		}
		else if($iReturnCode == "60" || $iReturnCode == "61" || $iReturnCode == "62" || $iReturnCode == "63")
		{
			$sRtnMsg = "네트워크 장애: 당사 서비스 IP와의 연결상태를 확인해주십시오.<br>IP:203.234.219.72<br>port:81~85(총 5개)";
		}
		else
		{
			$sRtnMsg = "기타 오류: 리턴코드 문서에 기재된 내용을 확인해주십시오."
					. "<br>코드가 문서에 기재되어 있지 않은 경우 NICE 전산담당자에게 문의해주십시오.";
		}
	}
?>

<html>
<head>
	<meta charset="EUC-KR">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>NICE평가정보 실명확인 서비스</title>
	<script>
		// NICE 실명등록 안내 팝업
		function fnNiceIdPopup(){
            var URL ="https://www.niceid.co.kr/name_guid.nc"; 
            var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
            window.open(URL,"",status); 
		}
		
		// NICE 지키미 명의도용차단 일시해제 팝업
		function fnNiceCreditPopup(){
            var URL ="https://www.credit.co.kr/ib20/mnu/BZWPNSOUT01"; 
            var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
            window.open(URL,"",status); 
		}		
		
<?php
	// 성명불일치 오류나 자료업음 오류가 발생한 경우
	if ($iReturnCode =="2" || $iReturnCode == "3")
	{
		// NICE 실명등록 안내팝업 호출
		echo "fnNiceIdPopup();";
	}
	// 명의도용차단 오류가 발생한 경우
	else if ($iReturnCode == "50")
	{
		// NICE 명의도용차단 임시해제 팝업 호출
		echo "fnNiceCreditPopup();";
	}
?>
	</script>
</head>
<body>
	<br><br>
	리턴코드 [<?=$iReturnCode?>]<br>
	<?=$sRtnMsg?>
</body>
</html>