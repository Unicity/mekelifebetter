<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

//회원URL조회
$mem_account = '209415382';
$url = "https://hydra.unicity.net/v5a/customers?id.unicity=".$mem_account;

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
echo $member_url."<br>";


$dob = "19830730";
$name = "김민구님";
$englishName = "Hong GG";
$email = "test5454275@gmail.com";

$krFirstName = "";
$krLastName = "";

$krLastName = mb_substr($name,0,1,'UTF-8');
$krFirstName = mb_substr($name,1,mb_strlen($name),'UTF-8');
 
$lastName  = substr($englishName, 0, strpos($englishName, " "));
$firstName = substr($englishName, strlen($lastName)+1);

$taxId = substr($dob,2).$name;
$dob = date("Y-m-d", strtotime($dob));
$enroller = '15745082';
$enroller = 'https://hydra.unicity.net/v5a/customers?id.unicity='.$enroller; //real

$city = '영등포구';
$state = '서울';
$zip = '07205';
$address1 = '양평로22길 21';
$address2 = '1110호';
$type = 'Associate';
$gender = 'female';
$mobilePhone = ''; //01012345678';
$homePhone = ''; //025454275';
/*
$rightsArray = array(
					"agree_01" => array('후원수당의 산정 및 지급기준 등의 변경 - 이메일 통지', 'Unicity', 'SendNoticeEmail', 'N'),
					"agree_02" => array('후원수당의 산정 및 지급기준 등의 변경 - SMS 통지', 'Unicity', 'SendNoticeSms', 'N'),
					"agree_03" => array('후원수당의 산정 및 지급기준 등의 변경 - SMS 통지', 'Unicity', 'SendMail', 'N'),					
					"selchk2"  => array('마케팅 목적의 이메일 수신 동의', 'Unicity', 'SendMarketingEmail', 'N'),
					"selchk3"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', 'SendMarketingSms', 'N'),
					"selchk4"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', 'N'),
					"selchk5"  => array('본인외 주문에 대한 동의', 'Upline', 'Order', 'N'),
					"selchk6"  => array('수첩과 등록증의 이메일 수령', 'Unicity', 'SendMembershipBookEmail', 'N')
					);

foreach($rightsArray as $key=>$val) {

	$title = $val[0];
	$holder = $val[1];
	$type = $val[2];
	$agree = $val[3];

	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account;
	$username = 'krWebEnrollment';
	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); //curl 실행에 대한 timeout 
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	$response = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if (($response !== false) && ($status == 200)) {
		
		$response = json_decode($response, true);
		 
		if (isset($response['items'][0]['href'])) {	
			$url = $response['items'][0]['href'].'/rights';

			echo $url."<br>";

			if(strtoupper($agree) == 'Y') {
				curl_setopt($ch, CURLOPT_URL, $url);		
				$data = http_build_query(array(
					'holder' => $holder,
					'type' => $type
				));			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		
			} else {						
				$url .= '?type='.$type.'&holder='.$holder;								
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
			curl_setopt($ch, CURLOPT_TIMEOUT, 60); //curl 실행에 대한 timeout 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			print_r($status);
			$result = json_decode($response, true);
			print_r($result);

			echo "<br>";

			if($status == "201" || $status == "204"){  //delete인경우 204
				$success = 'Y';
			}else{
				$success = 'N';
			}
		}
	}
	curl_close($ch);
	flush();
	ob_flush();
}
exit;
*/


$data = http_build_query(array(
			/*'mainAddress' => array(
				'city' => $city,
				'country' => 'KR',
				'state' => $state,
				'zip' => $zip,
				'address1' => $address1,
				'address2' => $address2
			),*/
			'humanName' => array(
				'firstName' => $englishName,
				'lastName' => ' ',
				'firstName@ko' => $name,
				'lastName@ko' => 'test'
			),
			/*
			//'type' => $type,
			//'status' => 'Active',
			'gender' => $gender,
			//'password' => array('value' => $password),
			//'enroller' => array('href' => $enroller),
			'birthDate' => $dob,
			'email' => $email,
			'mobilePhone' => $mobilePhone,
			'homePhone' => $homePhone,
			'taxTerms' => array('taxId' => $taxId)					 
			*/
		));


 

$url = $member_url; //정보변경시
//$url = 'https://hydra.unicity.net/v5a/customers'; //가입시

$username = 'krWebEnrollment';
$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

//정보변경
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);   //주석처리시 회원정보 조회 결과로 리턴됩니다.
$output = curl_exec($ch);			 
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);


/*  //가입
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		
$output = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
*/

$result = json_decode($output, true);


echo "<pre>";
print_r($status);
echo "</pre>";

echo "<pre>";
print_r($result);
echo "</pre>";


//$newMemberId = $result["id"]["unicity"];