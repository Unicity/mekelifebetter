<?
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";

$member_no	= str_quote_smart(trim($member_no));
$account 	= str_quote_smart(trim($account));

$query = "select * from tb_userinfo_dup where member_no = '".$member_no."'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);

if($row[member_no] == ""){
	echo "정보를 조회할 수 없습니다";
	exit;
}
if($account == ""){
	echo "계좌정보가 없습니다";
	exit;
}

$mem_account = $row[number];
$account_bank = $row[account_bank];
$account_name = $row[name];

$result2 = mysql_query("SELECT code FROM tb_code where parent='bank3' and name = '".$row[account_bank]."'") or die(mysql_error()); 
$row2 = mysql_fetch_array($result2);
$search_bank_code = $row2[0];

if($search_bank_code == ""){
	echo "계좌코드 조회 오류";
	exit;
}

//echo "$mem_account,$account_bank,$account_name,$search_bank_code,$account";
//exit;


//$url = 'https://hydra.unicity.net/v5-test/customers?unicity='. $mem_account; //test
$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account;
$username = 'krWebEnrollment';
$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$response = curl_exec($ch);

//var_dump($response);

$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//var_dump($status);

if (($response !== false) && ($status == 200)) {
	$response = json_decode($response, true);	
	//var_dump($response);
	if (isset($response['items'][0]['href'])) {
		$url = $response['items'][0]['href'];
		
		//var_dump($url);

		curl_setopt($ch, CURLOPT_URL, $url);
		$data = http_build_query(array(
			'depositBankAccount' => array(
				'bankName' => $account_bank,
				'bin' => $search_bank_code,
				'accountHolder' => urlencode($account_name),
				'accountNumber' => $account,
				'accountType' => 'SavingsPersonal',
				'routingNumber' => 1,
			)
		,
		));
		//"depositBankAccount%5BbankName%5D=%EA%B8%B0%EC%97%85%EC%9D%80%ED%96%89&depositBankAccount%5Bbin%5D=003&depositBankAccount%5BaccountHolder%5D=%25ED%2599%25A9%25EA%25B8%2588%25EC%25B2%259C&depositBankAccount%5BaccountNumber%5D=01081743220&depositBankAccount%5BaccountType%5D=SavingsPersonal&depositBankAccount%5BroutingNumber%5D=1"
		
		//var_dump($data);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($ch);

		$logResult = $response;
		
		$response = json_decode($response, true);
		if($response['error'][error_message] != ""){

			echo $response['error'][error_message];
		}

		//var_dump($response);

		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
		//var_dump($status);
		
		echo "OK";
	} else {

		echo "통신장애입니다";
	}

}else{

	echo "통신장애 입니다.";
	
}

mysql_query("insert into tb_api_log (page, member_no, send_data, return_data, regDate) values ('중복회원 계좌정보 업데이트(관리자)', '".$member_no."','".$data."','".$logResult."', now())");

curl_close($ch);
mysql_close($connect);
?>