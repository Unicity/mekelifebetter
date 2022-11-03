<?php 
header('Content-Type: text/html; charset=UTF-8');

//보안을 위해 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다. 
	
	/********************************************************************************************************************************************
	NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED

	서비스명 : 간편실명 서비스 
	페이지명 : 간편실명 서비스 처리 페이지
	*********************************************************************************************************************************************/

	$sSiteID = "FN42";                     // NICE평가정보에서 발급한 IPIN 서비스 사이트코드
    $sSitePW = "64791279";              // NICE평가정보에서 발급한 IPIN 서비스 사이트패스워드

	// 모듈의 절대경로 (권한:755, FTP업로드방식: 바이너리)
	$cb_encode_path = "/home/httpd/unicity/cb_namecheck/SNameCheck";			

	$strJumin  = $_POST["jumin"];			// 생년월일 (6자리, 8자리)
	$strgender = $_POST["gender"];			// 여성:0, 남성:1 
	$strName   = $_POST["name"];			// 이름

	$strName = iconv("UTF-8", "EUC-KR", $strName);

	///////////////////////////////////////////문자열 점검///////////////////////////////////////////////////
	if(preg_match("/[#\&\\+\-%@=V\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $strJumin, $match)) exit;
	if(preg_match("/[#\&\\+\-%@=\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $strName, $match)) exit;
	if(preg_match("/[#\&\\+\-%@=\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $strgender, $match)) exit;
	///////////////////////////////////////////////////////////////////////////////////////////////////////////

	// 리턴코드 초기화
	$iReturnCode  = "";	

	/********************************************************************************************************************************************
	암호화 데이타 생성 방식
	: 입력된 생년월일의 자리수에 따라 파라미터 수가 달라집니다. 
	  생년월일의 자리수와 성별값 입력여부를 반드시 확인해주시기 바랍니다.
	
	1) 주민번호 앞자리 인증(생년월일 6자리, 이름)
  	$iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strName`;			

	2) 생년월일 인증(생년월일 8자리, 성별, 이름)	
	$iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strgender $strName`;	
	*********************************************************************************************************************************************/

	// 암호화 데이타 생성
	// 싱글쿼터(`) 외에 'exec(), system(), shell_exec()' 등 귀사 정책에 맞는 실행함수 적용이 가능합니다.
	// $iReturnCode = system("$cb_encode_path $sSiteID $sSitePW $strJumin $strgender $strName");	
	$iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strgender $strName`;	

	echo "리턴코드 [" . $iReturnCode . "]";

	// 리턴코드에 따른 처리사항
	switch($iReturnCode){
		//실명인증 성공
		case 1:
?>
		<script>     
			alert("인증성공");          
		</script>    
      
		<!-- 성공처리 예시 (특정 페이지로 입력정보 전송)
		<html>
			<head>
			</head>
			<body onLoad="document.form1.submit()">
				<form name="form1" method="post" action=XXX.php>
					<input type="hidden" name="jumin" value="<?=$strJumin?>">
					<input type="hidden" name="gender" value="<?=$strgender?>">
					<input type="hidden" name="name" value="<?=$strName?>">
				</form>
			</body>
		</html>
		-->
     
<?
			break;

		// 자료없음	
		// www.niceid.co.kr 에서 실명등록 확인 또는 고객센터(1600-1522)로 문의 필요  			
		case 3:
?>
            <script>
               var URL ="https://www.niceid.co.kr/front/personal/register_online.jsp"; 
               var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
               window.open(URL,"",status); 
            </script> 

<?
			break;

		// 명의도용차단 
		// www.creditbank.co.kr 에서 명의도용차단 서비스 임시해제 후 재시도, 또는 고객센터(1600-1533)로 문의 필요                                                                            
		case 50;
?>
            <script>
               var URL ="http://www.creditbank.co.kr/its/itsProtect.cb?m=namecheckProtected"; 
               var status = "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width= 640, height= 480, top=0,left=20"; 
               window.open(URL,"",status); 
            </script> 

<?
			break;

		default:
		// 인증실패 (기타 사유)
		// 9 : 입력정보 오류 (자세한 사유는 리턴코드.txt 문서 참조)
		// 10 : 사이트 코드 오류 (사이트 코드의 대소문자를 확인해 주세요. 대문자로 입력하셔야 합니다.)
		// 11 : 정지된 사이트 ( 당사 계약 담당자에게 문의 주십시오.)
		// 12 : 사이트 패스워드 오류 (사이트 패스워드를 확인해 주십시오.)
		// 30번대 : 네트워크 장애 (방화벽 이용 시 서비스 IP 등록 후 접속 테스트)
		// 60번대 : 네트워크 장애 (방화벽 이용 시 서비스 IP 등록 후 접속 테스트)
		// 		    * 서비스 IP: 203.234.219.72, Port: 81~85 (총 5개)	
		// 			* 네트워크 확인 URL (예: http://203.234.219.72:81/check.asp)
		// 그 외 : 리턴코드.txt 문서 참조		
?>
		   <script>
				alert("인증에 실패 하였습니다. 리턴코드:[<?=$iReturnCode?>]");
				history.go(-1);
		   </script>
<?
			break;
	}
?>
 
