<?php
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
$html .=  "회원 고유주소 확인 : ";

//회원 고유주소
$url = "https://hydra.unicity.net/v5a/customers?id.unicity=".$number; //fo_number
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
$output = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$result = json_decode($output, true);

$member_url = $result['items'][0]['href'];

if($member_url == ""){
	echo "회원 고유주소 조회 실패";
	exit;
} else {
	echo "OK<br>";
}
flush();
ob_flush();


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

$username = 'krWebEnrollment';
$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

foreach($rightsArray as $key=>$val) {

	$title = $val[0];
	$holder = $val[1];
	$type = $val[2];
	$agree = $val[3];
	$agree_url = $member_url.'/rights';

	if($agree == "") $agree = "N";
	
	$ch = curl_init();		

	if(strtoupper($agree) == 'Y') {
		$data = http_build_query(array(
				'holder' => $holder,
				'type' => $type
			));	
		curl_setopt($ch, CURLOPT_URL, $agree_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
	} else {				
		$agree_url = $agree_url.'?type='.$type.'&holder='.$holder;	
		curl_setopt($ch, CURLOPT_URL, $agree_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
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



echo $html;  

exit;


$n = $_GET['uid'] + 1;

echo "<script>parent.go_next('".$n."');</script>";