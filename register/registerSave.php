<?php
ini_set('display_errors', 1);
set_time_limit(0); 
session_start(); 
if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	exit;
}
if (!include_once("./includes/dbconfig.php")){
	echo "The config file could not be loaded";
}

include "./includes/common_functions.php";
$user_device = mobile_check();  // return P or M

include "./includes/nc_config.php";
include "./includes/AES.php";
include "./includes/TranseId.php";
include "./includes/signup_function.php";


//카카오인증인 경우 이름 다시 확인
if($_SESSION["S_FLAG"] =='kakao'){
	$koreanName = preg_replace("/[^가-힣]/u", "", $koreanName);  //u가 없으면 4byte문자 처리시 에러
	if(strlen($koreanName) < 6){  //2자이하
		echo "<script>parent.registerLog('이름을 정확히 입력하여 주세요');</script>";
		exit;
	}
	$_SESSION["S_NM"] = $koreanName;
}

cert_validation();

$dob =    $_SESSION["S_BIRTH"];
$gender = $_SESSION["S_GENDER"];
$name =   $_SESSION["S_NM"];

$type =   $_POST["memberType"];
$k_id =   $_POST["k_id"];


$direct =   $_SESSION["direct"];

//인증값이 없는 경우
if ($dob == "" || $name == "" || $type=="")  {
	Redirect("./certification.php");
	exit;
}

//데이터 검증
foreach($_POST as $key=>$val) {
	$post[$key] = replaceCP949(mysql_real_escape_string($val));
}

//암호화 키 재설정
$enckey = hex2bin2("12345678901234567890123456789077");
$enciv = hex2bin2("12345678901234567890123456789011");


/*                                                                 기존      Table Field
[필수]전자상거래에 관한 이용약관							agree_0 -> term01
[필수]신청계약 동의										agree_1	->			신규	->  agree05
[필수]개인정보 수집 및 이용에 대한 동의						agree_2 -> term02
[필수]개인정보 제3자 제공 및 공유에 대한	동의					agree_3 -> term03  (term03 + term04 통합)
[필수]개인정보 국외 이전에 대한 동의							agree_4 -> term06
[선택]본인 외 주문에 대한 동의								agree_5 -> selchk5  -> sel_agree05
[선택]다단계판매원 수첩과 등록증의 이메일 수령에 대한 동의		agree_6 -> selchk6 (신규  Holder : unicity, Type :  SendMemershipBookEmail)  -> sel_agree06
 

[선택]마케팅 홍보 목적의 이메일 수신동의						agree_7 -> selchk2 -> sel_agree02
[선택]마케팅 홍보 목적의 SMS 수신동의						agree_8 -> selchk3 -> sel_agree03
[선택]마케팅 홍보 목적의 우편물 수신동의						agree_9 -> selchk4 -> sel_agree04
[필수]‘후원수당의 산정 및 지급기준 등의 변경’과 
‘방침 및 절차의 변경’에 대한 통지
- 이메일													agree_10 -> agree_01  -> agree_01
- SMS													agree_11 -> agree_02  -> agree_02
- 우편													agree_12 -> agree_03  -> agree_03

[필수] 본인은 공무원 또는 교원이거나,						agree_13 -> chk_agree3 + chk_agree4 + chk_agree5
다단계판매업자의 지배주주이거나 임직원이 아니며, 
방문판매 등에 관한 법률을 위반한 사실

프레지덴셜 디렉터 1차 이상을 달성한 이력이 없음				agree_14 -> chk_agree6

사업소득으로 국세청에 신고됨과 소득세 등 납세의무				agree_15

삭제항목
개인정보 취급위탁에 대한 제공 및 공유[필수적 항목]			 terms5  
개인정보(하나투어, 레드캡, SMTT) 제공 동의”			         selchk1  -> sel_agree01
후원수당 변경 동의” 항목 삭제										  -> agree_04
*/

$agree_01 = $post['agree_10'];
$agree_02 = $post['agree_11'];
$agree_03 = $post['agree_12'];
$agree_04 = 'N' ;			   //후원수당 변경 동의” 항목 삭제	
$agree_05 = $post['agree_1'];  //신청계약동의 신규

$selchk1 = 'N';				   //개인정보(하나투어, 레드캡, SMTT) 제공 동의” 항목 삭제
$selchk2 = $post['agree_7'];
$selchk3 = $post['agree_8'];
$selchk4 = $post['agree_9'];
$selchk5 = $post['agree_5'];
$selchk6 = $post['agree_6'];   //회원수첩

$englishName = $post["englishName"];
$password = $post["password"];
$zip = $post["zip"];

$state = $post["state"];	
$city = $post["city"];
$address1 = $post["address1"];	
$address2 = $post["address2"];	
$address1_sub = $post["address1_sub"];	//api 전송용 동/건물명 
$fullAddress = $post["fulladdress"]; 
$detailAddress = $post["detailaddress"];

//주소 특수문자제거
$replace_char = array('샾', '숖', 'ㆍ', '・', '·', '잌', '믑', '틍');
$replace_char2 = array('샵', '숍', '', '', '', '익', '읍', '층');

for($i=0; $i<count($replace_char); $i++) {
	$address1 = str_replace($replace_char[$i], $replace_char2[$i], $address1);
	$address2 = str_replace($replace_char[$i], $replace_char2[$i], $address2);
	$address1_sub = str_replace($replace_char[$i], $replace_char2[$i], $address1_sub);	
	$fullAddress = str_replace($replace_char[$i], $replace_char2[$i], $fullAddress);	
	$detailAddress = str_replace($replace_char[$i], $replace_char2[$i], $detailAddress);	
}

$mobileNo1 = $post["mobileNo1"];	
$mobileNo2 = $post["mobileNo2"];	
$mobileNo3 = $post["mobileNo3"];
$mobilePhone = $mobileNo1.$mobileNo2.$mobileNo3;

//0000, 9999 연속번호로 등록시 국번초기화
$phoneNo1 = chkSameNumner($post["phoneNo1"]);	
$phoneNo2 = chkSameNumner($post["phoneNo2"]);
$phoneNo3 = chkSameNumner($post["phoneNo3"]);
if($phoneNo1 != "" && $phoneNo2 != "" && $phoneNo3 != ""){
	$homePhone = $phoneNo1.$phoneNo2.$phoneNo3;
}else{
	$phoneNo1 = "";
	$phoneNo2 = "";
	$phoneNo3 = "";
	$homePhone = "";
}

$bankcode = $post["bankcode"];
$accountNo = preg_replace('/[^0-9]/','',$post["accountNo"]);

$email1 = $post["email1"];	
$email2 = $post["email2"];	
$email = $email1.'@'.$email2;

$sponsorNo = $post["sponsorNo"];
$sponsorName = $post["sponsorName"];


//가입정보 temp 저장
$temp_birthYear		= substr(trim($dob), 2, 2);
$temp_birthMonth		= substr(trim($dob), 4, 2);
$temp_birthDate  	= substr(trim($dob), 6, 2);
$temp_birthday       = $birthYear.$birthMonth.$birthDate;
$temp_encPassword = encrypt($enckey, $enciv, $password);
$temp_encAccountNo = encrypt($enckey, $enciv, $accountNo);
$temp_encDob = encrypt($enckey, $enciv, $birthday);
$temp_encGender = encrypt($enckey, $enciv, $gender);

//로그v2저장
$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', '회원가입처리시작', '$name', '".$dob."', '".$temp_encPassword."', '".$bankcode."-".$temp_encAccountNo."', 'Y', '".$user_device."', now())";
@mysql_query($qlog);


$temp_no = insertMemeber('tb_userinfo_temp', $temp_encPassword, $name, $englishName, $temp_encDob, $temp_encGender, $distType, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $fullAddress, $detailAddress, $email, $temp_encAccountNo, $bankName, $sponsorNo, $sponsorName, $type, $temp_birthYear, $temp_birthMonth, $temp_birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device,$k_id);

//$ref_device = ($user_device == 'M') ? 'M' : 'W';
//@mysql_query("update tb_userinfo_temp set REF = '".$ref_device."', auth_type = '".$_SESSION["S_AUTH_TYPE"]."' where member_no = '".$temp_no."'");
// temp 저장 끝


$bankName = CodeByName("bank3", trim($bankcode));

if ($type == "D") {
	
	//계좌인증 진행
	$bankAuth = bankValidationApi($accountNo, $bankcode);	
	$bankResult = json_decode($bankAuth);

	//응답코드가 없는 경우
	if(str_replace(" ",'',trim($bankResult->replyCode)) == ""){ //응답값이 없는 경우 전용선 방식 조회 룰 추가 2021-03-15
		$accountChecker = bankValidation($dob, $accountNo, $bankcode) ;
		$bankResult->replyCode = substr($accountChecker[msg],51,4);
			
		$compare_str = substr($accountChecker[msg],122,22);
		$compare_str = iconv("euc-kr", "utf-8", $compare_str);	
		if ( strlen($compare_str) > strlen($_SESSION["S_NM"]) ) {
			$compare_str = substr($compare_str, 0, strlen($_SESSION["S_NM"]));
		}
		$bankResult->accountName = $compare_str;
	}
			
	if($bankResult->accountName == "" || $bankResult->replyCode != "0000"){
		echo "<script>parent.registerLog('계좌 정보를 조회할 수 없습니다. 계좌정보를 확인하여 주세요');</script>";
		exit;
	}

	//계좌주와 실명이 다른 경우
	$rtnNameCheck = explode('(', $bankResult->accountName);
	$chkName = str_replace(" ","", $_SESSION["S_NM"]);

	//외국인인 경우 리턴값이 9자리이므로 9자리로 맞춤
	if(preg_replace("/([a-zA-Z])/", "", $chkName) == ""){ 
		if(strlen($chkName) > 9) $chkName = substr($chkName,0,9);
	}

	//if($_SESSION["S_FLAG"] ==  'kakao'){  //카카오인증의 경우 계좌인증 성명을 회원명으로 자동 대체 처리  - 210711 김민구과장님 요청으로 삭제

	//	$name = $rtnNameCheck[0];	
	//	$_SESSION["S_NM"] = $name;

	//}else{
		if(strtolower($rtnNameCheck[0]) != strtolower($chkName)){
			echo "<script>parent.registerLog('계좌 예금주와 성명이 일치하지 않습니다.');</script>";
			exit;
		}
	//}
}

$birthYear		= substr(trim($dob), 2, 2);
$birthMonth		= substr(trim($dob), 4, 2);
$birthDate  	= substr(trim($dob), 6, 2);
$birthday       = $birthYear.$birthMonth.$birthDate;
$encPassword = encrypt($enckey, $enciv, $password);
$encAccountNo = encrypt($enckey, $enciv, $accountNo);
$encDob = encrypt($enckey, $enciv, $birthday);
$encGender = encrypt($enckey, $enciv, $gender);


$existApiResult = isExist($name, $birthYear, $birthMonth, $birthDate);

//if(isExist($name, $birthYear, $birthMonth, $birthDate) == 0){  //신규회원

if($existApiResult == 0){  //신규회원
	
	$member_no = insertMemeber('tb_userinfo', $encPassword, $name, $englishName, $encDob, $encGender, $distType, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $fullAddress, $detailAddress, $email, $encAccountNo, $bankName, $sponsorNo, $sponsorName, $type, $birthYear, $birthMonth, $birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device);

	if(($member_no != "") && !is_null($member_no)){

		//가입방법 및 가입디바이스 저장	
		$ref_device = ($user_device =='M') ? 'M' : 'W';
		//@mysql_query("update tb_userinfo set REF = '".$ref_device."', auth_type = '".$_SESSION["S_AUTH_TYPE"]."' where member_no = '".$member_no."'");

		//로그v2저장
		$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'tb_userinfo 신규등록', '$name', '".$birthday."', '', '', 'Y', '$user_device', now())";	
		@mysql_query($qlog);
	
		$api_address2 = $address2." ".$address1_sub;
		$newMemberId = createMemberAPI($city, $state, $zip, $address1, $api_address2, $name, $englishName, $type, $gender, $password, $sponsorNo, $dob, $email, $mobilePhone, $homePhone);

		if($newMemberId == "duplicate"){ //government ID(이름+생년월일) 중복 에러 -> 중복회원 처리 20210825 추가

			$member_no_dup = insertMemeber('tb_userinfo_dup', $encPassword, $name, $englishName, $encDob, $encGender, $distType, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $fullAddress, $detailAddress, $email, $encAccountNo, $bankName, $sponsorNo, $sponsorName, $type, $birthYear, $birthMonth, $birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device,$k_id);
	
			if($member_no_dup){
				@mysql_query("delete from tb_userinfo where member_no = '".$member_no."'");  //신규회원테이블에서 삭제
				updateColumn("tb_userinfo_temp", "DEL_TF", "Y", 'member_no = '.$temp_no); //임시테이블데이터 삭제
			}
			
			//로그v2저장
			$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'createMemberAPI-error(Duplicate government ID)', '$name', '".$birthday."', '', '', 'Y', '$user_device', now())";	
			
			echo "<script>parent.registerDone('dup','".$password."');</script>";
			exit;
			 
		}else if ($member_no != "" && $newMemberId  !="" && !empty($newMemberId)) {
	
			updateColumn('tb_userinfo', 'number', $newMemberId, 'member_no = '.$member_no);
				
			updateColumn("tb_userinfo_temp", "DEL_TF", "Y", 'member_no = '.$temp_no); //임시테이블데이터 삭제

			//계좌전송
			if ($type == "D") {
				$birth = $birthYear.$birthMonth.$birthDate;
				updateAccountInfo($newMemberId, $bankName,  $bankcode, $name, $accountNo, $name, $birth);
			}

			$rightsArray = array(
					"agree_01" => array('후원수당의 산정 및 지급기준 등의 변경 - 이메일 통지', 'Unicity', 'SendNoticeEmail', $agree_01),
					"agree_02" => array('후원수당의 산정 및 지급기준 등의 변경 - SMS 통지', 'Unicity', 'SendNoticeSms', $agree_02),
					"agree_03" => array('후원수당의 산정 및 지급기준 등의 변경 - SMS 통지', 'Unicity', 'SendMail', $agree_03),					
					"selchk2"  => array('마케팅 목적의 이메일 수신 동의', 'Unicity', 'SendMarketingEmail', $selchk2),
					"selchk3"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', 'SendMarketingSms', $selchk3),
					"selchk4"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', $selchk4),
					"selchk5"  => array('본인외 주문에 대한 동의', 'Upline', 'Order', $selchk5),
					"selchk6"  => array('수첩과 등록증의 이메일 수령', 'Unicity', 'SendMembershipBookEmail', $selchk5)
					);

			updateDownlineFlagAll($name, $newMemberId, $rightsArray);

			$r = sendSMS($member_no, $newMemberId,  $type, $mobilePhone);

			//처리상태 완료로 변경 - 이성수부장님 요청
			@mysql_query("update tb_userinfo set reg_status = '4' where member_no = '".$member_no."'");

			//로그업데이트
			$qlog = "update tb_log_v2 set memid = '".$newMemberId."' where tmpId = '".$_SESSION['ssid']."'";
			mysql_query($qlog);

			echo "<script>parent.registerDone('".$newMemberId."','".$password."');</script>";
			exit;
	 
		} else {

			if($newMemberId == ""){ //신규회원가입API 통신 장애

				$dup_member_no = insertMemeber('tb_userinfo_dup', $encPassword, $name, $englishName, $encDob, $encGender, $distType, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $fullAddress, $detailAddress, $email, $encAccountNo, $bankName, $sponsorNo, $sponsorName, $type, $birthYear, $birthMonth, $birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device,$k_id);
				
				@mysql_query("update tb_userinfo_dup set reg_status = 'F' where member_no = '$dup_member_no'");
				
				@mysql_query("delete from tb_userinfo where member_no = '$member_no'");

				//로그v2저장
				$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'API 통신 장애-중복테이블등록($dup_member_no)', '$name', '".$birthday."', '', '', 'Y', '$user_device', now())";	
				@mysql_query($qlog);
			}


			echo "<script>parent.registerLog('회원가입 도중 문제가 발생했습니다. 가까운 DSC나 콜센터를 통해 문의바랍니다.');</script>";
			exit;
		}

	}else{

		$member_no = insertMemeber('tb_userinfo_dup', $encPassword, $name, $englishName, $encDob, $encGender, $distType, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $fullAddress, $detailAddress, $email, $encAccountNo, $bankName, $sponsorNo, $sponsorName, $type, $birthYear, $birthMonth, $birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device,$k_id);

		if($member_no != ''){

			//가입방법 및 가입디바이스 저장	
			$ref_device = ($user_device =='M') ? 'M' : 'W';
			@mysql_query("update tb_userinfo set REF = '".$ref_device."', auth_type = '".$_SESSION["S_AUTH_TYPE"]."' where member_no = '".$member_no."'");

		}

		echo "<script>parent.registerLog('회원가입 도중 문제가 발생했습니다. 가까운 DSC나 콜센터를 통해 문의바랍니다.');</script>";
		exit;
	}

}else if($existApiResult == 'F'){  //통신장애
	
	$member_no = insertMemeber('tb_userinfo_dup', $encPassword, $name, $englishName, $encDob, $encGender, $distType, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $fullAddress, $detailAddress, $email, $encAccountNo, $bankName, $sponsorNo, $sponsorName, $type, $birthYear, $birthMonth, $birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device,$k_id);
	
	if($member_no){
		
		updateColumn("tb_userinfo_temp", "DEL_TF", "Y", 'member_no = '.$temp_no); //임시테이블데이터 삭제

		@mysql_query("update tb_userinfo_dup set reg_status = 'F' where member_no = '$member_no'");  //통신장애 업데이트
		
		//가입방법 및 가입디바이스 저장	
		$ref_device = ($user_device =='M') ? 'M' : 'W';
		//@mysql_query("update tb_userinfo set REF = '".$ref_device."', auth_type = '".$_SESSION["S_AUTH_TYPE"]."' where member_no = '".$member_no."'");
	}
	
	//로그v2저장
	$qlog = "insert into tb_log_v2 (tmpId, gubun, memid, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', '회원가입실패-중복테이블 저장', '$mem_account', '$name', '".$birth_y.$birth_m.$birth_d."', '".$rights."', '".$resultText."', 'N', '$user_device', now())";	
	mysql_query($qlog) or die("isExistUserInfo-".mysql_error());
	
	echo "<script>parent.registerLog('회원가입 도중 문제가 발생했습니다. 가까운 DSC나 콜센터를 통해 문의바랍니다.');</script>";
	exit;


}else{  //중복회원 등

	$member_no = insertMemeber('tb_userinfo_dup', $encPassword, $name, $englishName, $encDob, $encGender, $distType, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $fullAddress, $detailAddress, $email, $encAccountNo, $bankName, $sponsorNo, $sponsorName, $type, $birthYear, $birthMonth, $birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device,$k_id);
	
	if($member_no){
		updateColumn("tb_userinfo_temp", "DEL_TF", "Y", 'member_no = '.$temp_no); //임시테이블데이터 삭제
	}
	
	//로그v2저장
	$qlog = "insert into tb_log_v2 (tmpId, gubun, memid, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', '회원가입실패-중복테이블 저장', '$mem_account', '$name', '".$birth_y.$birth_m.$birth_d."', '".$rights."', '".$resultText."', 'N', '$user_device', now())";	
	mysql_query($qlog) or die("isExistUserInfo-".mysql_error());
	
	echo "<script>parent.registerDone('dup','".$password."');</script>";
	exit;
}

exit;
?>