<?php
//개안토큰발행 전송 -> 공용인증으로 변경
set_time_limit(0); 
session_start();

include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";
include "./new_api_function.php";

extract($_POST);

$html =  "<strong style='font-size:14px'>".$number.":".$name." (".$tbl.")</strong><br>";
$password = decrypt($key, $iv, $pwd);
$html .=  "로그인 토근발행 발행 : ";

//로그인 토근발행
$headers = array(
	'Content-Type: application/json'	
);

$url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
$key = base64_encode($number.":".$password);
$data = json_encode(
	array(
		'type' => 'base64',
		'value' => $key,
		'namespace' => "https://hydra.unicity.net/v5a/customers"
	)
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
$result = json_decode($response, true);
$token = $result[token];
curl_close($ch);
	
if (isset($result["error"]["code"])) {
	$html .=  "발행실패 : ".$result["error"]["code"]."<br>";
	echo $html;
	exit;
}
if($token  == ""){
	$html .=  "토큰값 없슴<br>";
	echo $html;
	exit;
}

$me_url = $result[customer][href];

$html .=  "OK<br>";

//주요동의사항 업데이트
$headers = array(
	'Authorization: Bearer '.$token
);

$html .=  '주요동의항목 재전송<br>';

$agree = explode(',',$agree);

$rightsArray = array(
		"agree_01" => array('이메일 통보', 'Unicity', 'SendNoticeEmail', $agree[0]),
		"agree_02" => array('주요안내사항 통보', 'Unicity', 'SendNoticeSms', $agree[1]),
		"agree_03" => array('SMS 통보', 'Unicity', 'SendMail',$agree[2]),
		"selchk1"  => array('개인정보 (하나투어, 레드캡, SMTT) 제공 동의', 'Unicity', 'ShareOrdersDataWithTravelAgency', $agree[3]),
		"selchk2"  => array('마케팅 목적의 이메일 수신 동의', 'Unicity', 'SendMarketingEmail', $agree[4]),
		"selchk3"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', 'SendMarketingSms', $agree[5]),
		"selchk4"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', $agree[6]),
		"selchk5"  => array('본인외 주문에 대한 동의', 'Upline', 'Order', $agree[7])
		);

foreach($rightsArray as $key=>$val) {

	$title = $val[0];
	$holder = $val[1];
	$type = $val[2];
	$agree = $val[3];

	if($agree == "") $agree = "N";
	
	$ch = curl_init();		

	if(strtoupper($agree) == 'Y') {
		$data = json_encode(array(
			'holder' => $holder,
			'type' => $type
		));			
		$url = 'https://hydra.unicity.net/v5a/customers/me/rights';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	} else {				
		$url = 'https://hydra.unicity.net/v5a/customers/me/rights?type='.$type.'&holder='.$holder;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:Bearer '.$token));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	}

	$response = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	$result = json_decode($response);

	$html .=  "-".$title."(".$agree."):";

	if($status == "201" || $status == "204"){  //delete인경우 204
		$html .=  "->OK<br>";			
	}else{			
		$html .=  "->Fail<br>";		
	}
	curl_close($ch);
}
/*
$html .=  "로그인 토큰을 삭제합니다.";

//로그인 토큰 삭제
//curl -i -X DELETE "https://hydra.unicity.net/v5a/loginTokens/d3de00db-5f06-408d-89e7-92c962003291"
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://hydra.unicity.net/v5a/loginTokens/".$token);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if($status == "201" || $status == "204"){  //delete인경우 204
	$html .=  "->OK<br>";			
}else{			
	$html .=  "->Fail<br>";		
}
*/


echo $html;  

exit;




$n = $_GET['uid'] + 1;

echo "<script>parent.go_next('".$n."');</script>";