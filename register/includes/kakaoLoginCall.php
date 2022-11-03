<?php
session_start();

define('KAKAO_CLIENT_ID', 'abda45f8a0495e41791983180aa4ccfc'); 
define('KAKAO_CALLBACK_URL', 'https://www.makelifebetter.co.kr/register/includes/kakaoLogin.php');
$kakao_state = md5(microtime() . mt_rand()); // 보안용 값 
$_SESSION['kakao_state'] = $kakao_state; 
$kakao_apiURL = "https://kauth.kakao.com/oauth/authorize?client_id=".KAKAO_CLIENT_ID."&redirect_uri=".urlencode(KAKAO_CALLBACK_URL)."&response_type=code&state=".$kakao_state;



	
?>