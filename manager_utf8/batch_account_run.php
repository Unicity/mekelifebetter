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
$html .=  '계좌정보 재전송<br>';

$account_bank = $bank;
$search_bank_code = $bank_code;
$account_name = $name;
$accountNumber = $account;


if($account_bank == "" || $search_bank_code == "" || $account_name == "" || $accountNumber == ""){
	echo "->Fail 정보누락<br>";
	exit;
}

$username = 'krWebEnrollment';
$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

flush();
ob_flush();

$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$number.'&expand=customer';

$username = 'krWebEnrollment';
$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$response = curl_exec($ch);			 
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	 
if (($response != false) && ($status == 200)) {
	
	$data = json_decode($response);
	if($data->items[0]->href != "") {
		$url2 = $data->items[0]->href;					 
		
		$postdata = http_build_query(array(
			'depositBankAccount' => array(
				'bankName' => $account_bank,
				'bin' => $search_bank_code,
				'accountHolder' => $account_name,
				'accountNumber' => $account,
				'accountType' => 'SavingsPersonal',
				'routingNumber' => 1
			)
		));

		curl_setopt($ch, CURLOPT_URL, $url2);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		$response2 = curl_exec($ch);
		$status2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($response2 !== false && ($status2 == 200 ||$status2 == 201)) {
			echo "->OK<br>";
		}else{
			echo "->Fail<br>".$response2;
		}
	}
}else{
	echo "->Fail<br>".$response2;
}

curl_close($ch);

echo $html;  

exit;