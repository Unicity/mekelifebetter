<?session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<style type="text/css" title="">
body {font-size:12px; line-height:150%;  max-width:480px; max-width:480px; word-break:break-all;}
</style>
<?
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";
include "./new_api_function.php";
?>
<script type="text/javascript" src="./inc/jquery.js"></script>

<?php 
function debug($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

$member_no = "615821";

//전송처리 시작
$result = mysql_query("select * from tb_userinfo where member_no = '".$member_no."'") or die(mysql_error());	
$row = mysql_fetch_array($result);

$account_name = $row[name];
$number = $row[number];
$password = decrypt($key, $iv, $row[password]);
$account = decrypt($key, $iv, $row[account]);
$ename = $row[ename];

echo "$number, $account_name, $password, $account, $ename <br>";

$number = "226336882";
$password = "123456";

echo "*** ".$row[name]."님 API 재전송 Start ***<br>";
flush();
ob_flush();


echo "로그인 토근발행 발행 : ";
flush();
ob_flush();

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
	 echo $result["error"]["code"]."<br>";
	 flush();
	 ob_flush();
	 return;
}
if($token  == ""){
	echo "token error<br>";
	flush();
	ob_flush();
	exit;
}

$me_url = $result[customer][href];

debug($result);

echo "<br>";
echo $me_url;
echo "<br>";

flush();
ob_flush();

echo "영문이름 재전송 : ";
$data = json_encode(array(
		'humanName' => array(
			'firstName' => 'Lee jy', 
			'lastName' => '',
			'firstName@ko' => $account_name,
			'lastName@ko' => ''
			)));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://hydra.unicity.net/v5a/customers/me');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch); 
$data = json_decode($result, true);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

debug($data);
debug($status);

exit;


echo "계좌 재전송 : ";

flush();
ob_flush();

$url = $me_url."/depositBankAccount";

$data = json_encode(array(
		'accountNumber' => '123412341234',
	    'accountHolder' => '이정윤', 
	    'routingNumber' => '1',
	    'bankName' => '국민은행',
	    'accountType' => 'SavingsPersonal',
	    'bin' => '004'
		));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch); 
$data = json_decode($result, true);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

debug($data);
debug($status);
exit;


if(in_array('bank', $chk)){
	$account_bank = $row[account_bank];
	$account = decrypt($key, $iv, $row[account]);
	$account_name = $row[name];

	$result2 = mysql_query("SELECT code FROM tb_code where parent='bank3' and name = '".$row[account_bank]."'") or die(mysql_error()); 
	$row2 = mysql_fetch_array($result2);
	$search_bank_code = $row2[0];

	if($search_bank_code == ""){
		echo "
		<script>
		alert('은행코드를 조회할 수 없습니다.');
		parent.js_close_modal();
		</script>";
		exit;
	}
}

if(in_array('pass', $chk)){
//	echo '비밀번호 업데이트';
}



usleep(300000); //0.3초

$headers = array(
	'Authorization: Bearer '.$token
);

//이메일, 주소, 휴대전화번호, 자택전화번호 재전송
//if(in_array('info', $chk)){
if( count($infodata) > 0){

	echo $infotitle." 재전송";
	//echo "이메일, 주소, 휴대전화번호, 자택전화번호 재전송";
	flush();
	ob_flush();

	/*$data = json_encode(array(
		'mainAddress' => array(
				'address1' =>$address1,
				'address2' => $address2,
				'city' => $city,
				'state' => $state,
				'country' => "KR",
				'zip' => $row['zip']
			),		
		'email' => $row['email'],
		'mobilePhone' => $row['hpho1'].$row['hpho2'].$row['hpho3'],
		'homePhone' => $row['pho1'].$row['pho2'].$row['pho3']
	));
	*/
	$data = json_encode($infodata);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://hydra.unicity.net/v5a/customers/me');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result = curl_exec($ch); 
	$data = json_decode($result, true);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($response !== false && ($status == 200 ||$status == 201)) {		
		echo "->OK<br>";
	}else{
		echo "->Fail<br>";
	}
	flush();
	ob_flush();
}


//계좌정보 재전송
if(in_array('bank', $chk)){
	
	echo "계좌정보 재전송";
	flush();
	ob_flush();

	$data = json_encode(array(
			'depositBankAccount' => array(
					'bankName' => $account_bank,
					'bin' => $search_bank_code,
					'accountHolder' => $account_name,
					'accountNumber' => $account,
					'accountType' => 'SavingsPersonal',
					'routingNumber' => 1,
				)
			));

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://hydra.unicity.net/v5a/customers/me');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer '.$token));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result = curl_exec($ch); 
	$data = json_decode($result, true);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($response !== false && ($status == 200 ||$status == 201)) {		
		if($data[depositBankAccount][bin] == $search_bank_code && $data[depositBankAccount][accountNumber] == $account) echo "->OK<br>";
		else echo "->Fail<br>";
	}else{
		echo "->Fail<br>";
	}
	flush();
	ob_flush();
}

//주요동의사항 업데이트
if(in_array('agree', $chk)){	
	echo '주요동의항목 재전송<br>';
	flush();
	ob_flush();
	$rightsArray = array(
			"agree_01" => array('이메일 통보', 'Unicity', 'SendNoticeEmail', $row['agree_01']),
			"agree_02" => array('주요안내사항 통보', 'Unicity', 'SendNoticeSms', $row['agree_02']),
			"agree_03" => array('SMS 통보', 'Unicity', 'SendMail', $row['agree_03']),
			"selchk1"  => array('개인정보 (하나투어, 레드캡, SMTT) 제공 동의', 'Unicity', 'ShareOrdersDataWithTravelAgency', $row['sel_agree01']),
			"selchk2"  => array('마케팅 목적의 이메일 수신 동의', 'Unicity', 'SendMarketingEmail', $row['sel_agree02']),
			"selchk3"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', 'SendMarketingSms', $row['sel_agree03']),
			"selchk4"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', $row['sel_agree04']),
			"selchk5"  => array('본인외 주문에 대한 동의', 'Upline', 'Order', $row['sel_agree05'])
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

		//echo $title.$holder.$type.$agree.":";
		echo "-".$title."(".$agree."):";

		if($status == "201" || $status == "204"){  //delete인경우 204
			echo "->OK<br>";			
		}else{			
			echo "->Fail<br>";		
		}
		flush();
		ob_flush();
		curl_close($ch);
	}
	usleep(300000); //0.3초
	
} 
echo "*** API전송이 완료되었습니다 ***";

echo "<br><br>";

echo "*** 본사에 등록된 회원정보를 조회합니다 ***<br>";
$ch = curl_init();		

$headers = array(
	'Authorization: Bearer '.$token
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $me_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch); 
$data = json_decode($result, true);

//echo "========data parse=====<br>";
//echo $data[unicity]."<br>";
echo "address1->".$data[mainAddress][address1]."<br>";
echo "address2->".$data[mainAddress][address2]."<br>";
echo "city->".$data[mainAddress][city]."<br>";
echo "state->".$data[mainAddress][state]."<br>";
echo "zip->".$data[mainAddress][zip]."<br>";
//echo "<br>";
//echo "firstName->".$data[humanName]['firstName']."<br>";
//echo "lastName->".$data[humanName]['lastName']."<br>";
//echo "fullName->".$data[humanName]['fullName']."<br>";
//echo "fullName@ko->".$data[humanName]['fullName@ko']."<br>";
//echo "<br>";
//echo "".$data[id]['unicity']."<br>";
//echo "<br>";
//echo "".$data[sponsoredCustomers]['href']."<br>";
//echo "<br>";
//echo "birthDate->".$data[birthDate]."<br>";
echo "email->".$data[email]."<br>";
//echo "".$data[enroller]['href]']."<br>";
//echo "enroller->".$data[enroller]['id']['unicity']."<br>";
//echo "gender->".$data[gender]."<br>";
echo "homePhone->".$data[homePhone]."<br>";
echo "mobilePhone->".$data[mobilePhone]."<br>";
//echo "taxTerms->".$data[taxTerms]['taxId']."<br>";

//print_r($data[rights])."<br>";

echo "주요동의사항<br>";
if(count($data[rights]) < 1){
	echo "- 동의 내역 없슴<br>";
}else{
	for($i=0; $i<count($data[rights]); $i++) {
		echo "-holder:".$data[rights][$i][holder].", type:".$data[rights][$i][type]."<br>";
	}
}
flush();
ob_flush();
exit;
?>
