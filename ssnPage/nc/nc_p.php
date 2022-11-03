<?php
 
 $strJumin = $_POST["jumin1"].$_POST["jumin2"];
 $strName = $_POST["name"];		
 $strName = iconv("utf-8", "euc-kr", $strName);

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


// 입력값 확인
if(!empty($strJumin) && !empty($strName))
{
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
		$iReturnCode = `$cb_encode_path $sSiteCode $sSitePw $strJumin "$strName"`;
	// }

	// 실명인증 결과코드 생성여부 확인
	if(empty($iReturnCode))
	{	
		$sRtnMsg = "인증 미실행 오류: 모듈의 경로와 권한을 확인해주시기 바랍니다.";
	}
}
else
{
	$sRtnMsg = "입력값 미전달 오류: 성명이나 주민번호가 전달되지 않았습니다.";
}

if(!empty($iReturnCode))
	{	
		if ($iReturnCode == "1")
		{
			$sRtnMsg = "인증성공";
		}
		else if($iReturnCode == "2")
		{
			$sRtnMsg = "성명불일치 오류: 주민번호와 성명이 일치하지 않습니다.";
		}
		else if($iReturnCode == "3")
		{
			$sRtnMsg = "자료없음 오류: 주민번호가 조회되지 않습니다.";
		}
		else if($iReturnCode == "5")
		{
			$sRtnMsg = "주민번호 체크썸 오류: 주민번호 생성규칙에 맞지 않는 주민번호입니다.";
		}
		else if($iReturnCode == "9")
		{
			$sRtnMsg = "입력정보 오류: 입력정보가 누락되었거나 정상이 아닙니다.";
					
		}
		else if($iReturnCode == "10")
		{
			$sRtnMsg = "사이트 코드 오류: 사이트코드를 대문자로 입력해주십시오.";
					
		}
		else if($iReturnCode == "11")
		{
			$sRtnMsg = "정지된 사이트 오류: 서비스 계약이 정지된 사이트입니다.";
		}
		else if($iReturnCode == "12")
		{
			$sRtnMsg = "패스워드 불일치 오류: 사이트 패스워드가 일치하지 않습니다";
		}
		else if($iReturnCode == "21")
		{
			$sRtnMsg = "입력정보 형식 오류: 입력정보의 자릿수를 확인해주십시오. (주민번호:13자리, 패스워드: 8자리)";
		}
		else if($iReturnCode == "50")
		{
			$sRtnMsg="명의도용차단 오류: 명의도용차단 서비스 이용 중인 주민번호입니다.";
		}else
		{
			$sRtnMsg = "기타 오류: 리턴코드 문서에 기재된 내용을 확인해주십시오.";
		}
	}
		echo $sRtnMsg;

?>
 