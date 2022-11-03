<?php
 
	if (!include_once("./includes/dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./includes/AES.php";
	include "./includes/nc_config.php";

	
	$distID	= $_POST['distID']!=''?$_POST['distID']:$_GET['distID'];
	$ssn1		= $_POST['ssn1']!=''?$_POST['ssn1']:$_GET['ssn1'];
	$ssn2		= $_POST['ssn2']!=''?$_POST['ssn2']:$_GET['ssn2'];

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
	$strJumin = $ssn1.$ssn2;
	$strName = $distID;	

	$ssn = $ssn1.$ssn2;
break;
	if ($distID == "" || $ssn == "") {
		echo "회원번호 또는 주민번호를 다시 확인해 주십시오";
		exit;
	}


	// 입력값 확인
	if(!empty($strJumin) && !empty($strName)){
		// 데이터 위변조 검사 (생략가능)
		// 입력 페이지에서 세션에 저장된 값과 비교해 위변조 검사  
		if(empty($_SESSION[$sReqKey]) || $_SESSION[$sReqKey] != $sReqNo){
			// 세션값 불일치 시 처리
			// $sRtnMsg = "세션값이 일치하지 않습니다. 다시 인증해주십시오.";
		}
		// else
		// {
			// 실명인증 처리
			// 실행방법은 싱글쿼터(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
			// 예) $iReturnCode = system($cb_encode_path . ' ' . $sSiteCode . ' ' . $sSitePw . ' ' . $strJumin . ' "' . $strName . '"');
			
			echo $cb_encode_path."<br/>";
			echo $sSiteCode."<br/>";
			echo $sSitePw."<br/>";
			echo $strJumin."<br/>";
			echo $strName."<br/>";
	
			$iReturnCode = `$cb_encode_path $sSiteCode $sSitePw $strJumin $strName`;
		// }

		// 실명인증 결과코드 생성여부 확인
		if(empty($iReturnCode)){	
			$sRtnMsg = "인증 미실행 오류: 모듈의 경로와 권한을 확인해주시기 바랍니다.";
		}
	}else{
		$sRtnMsg = "입력값 미전달 오류: 성명이나 주민번호가 전달되지 않았습니다.";
	}


	
	$SSN = encrypt($key, $iv, $ssn);

	$query = "SELECT COUNT(*) CNT FROM tb_distSSN where dist_id='$distID' and government_id='$SSN' ";

	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	if ($row["CNT"] > 0) {
		
		$query = "update tb_distSSN set create_date = now() where dist_id='$distID' and government_id='$SSN' ";

	} else {

		$query = "insert into tb_distSSN  (dist_id, government_id) values
							('$distID', '$SSN')";

	}

	if (mysql_query($query) === TRUE) {
		echo "T";
	} else {
		echo "F";
	}
	 
	mysql_close($connect);

?>
 