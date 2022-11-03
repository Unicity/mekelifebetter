<?php
session_start();


	
	$returnCode = $_GET["code"]; // 서버로 부터 토큰을 발급받을 수 있는 코드를 받아옵니다
	$restAPIKey = "abda45f8a0495e41791983180aa4ccfc"; // 본인의 REST API KEY를 입력해주세요
	$callbacURI = urlencode("https://www.makelifebetter.co.kr/register/includes/kakaoLogin.php"); // 본인의 Call Back URL을 입력해주세요

    $getTokenUrl = "https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=".$restAPIKey."&redirect_uri=".$callbacURI."&code=".$returnCode;
	

	$isPost = false;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getTokenUrl);
	curl_setopt($ch, CURLOPT_POST, $isPost);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$headers = array();
	$loginResponse = curl_exec ($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);
	
    $accessToken= json_decode($loginResponse)->access_token; //Access Token만 따로 뺌

	
	$header = "Bearer ".$accessToken; // Bearer 다음에 공백 추가
	$getProfileUrl = "https://kapi.kakao.com/v2/user/me";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getProfileUrl);
	curl_setopt($ch, CURLOPT_POST, $isPost);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$headers = array();
	$headers[] = "Authorization: ".$header;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$profileResponse = curl_exec ($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);
	
	//var_dump($profileResponse); // Kakao API 서버로 부터 받아온 값

	
	$profileResponse = json_decode($profileResponse);
	//echo "kakoVal:".$profileResponse;

	$userId = $profileResponse->id;
	$userName = $profileResponse->properties->nickname;
	$userEmail = $profileResponse->kakao_account->email;
	$phone_number = $profileResponse->kakao_account->phone_number;
	//$phone_number = $profileResponse->kakao_account->phone_number;
	$birthyear = $profileResponse->kakao_account->birthyear;
	$birthday = $profileResponse->kakao_account->birthday;

	$gender = $profileResponse->kakao_account->gender;

	$tel1 = substr($phone_number, 4, 2);
	$tel2 = substr($phone_number, 7, 4);
	$tel3 = substr($phone_number, 12, 4);
	 

	$birthYD = $birthyear.$birthday;
	$phone_number='0'.$tel1.$tel2.$tel3;

	
	

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	</head>
	<body onload="init();">
		<form name="kakao_submit" method ="POST">
			<input type="hidden" name="k_name" value ="<?echo $userName ?>">
			<input type="hidden" name="k_email" value ="<?echo $userEmail ?>">
			<input type="hidden" name="k_phone_number" value ="<?echo $phone_number ?>">
			<input type="hidden" name="k_birthday" value ="<?echo $birthYD ?>">
			<input type="hidden" name="k_gender" value ="<?echo $gender ?>">
			<input type="hidden" name="sponsor_id" value ="">
			<input type="hidden" name="flag" value ="kakao">
			<input type="hidden" name="flagk" value ="kakaok">
			<input type="hidden" name="k_id" value ="<?echo $userId?>">

		</form>
	</body>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script>
		function init(){
			
			var kakaoForm = document.kakao_submit;
			kakaoForm.action = "../registerForm.php";
			kakaoForm.submit();

		}
	</script>
</html>
