<?php 
set_time_limit(0);
session_start();
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";
include "./inc/common_function.php";

echo '<meta charset="utf-8">';

//이정윤 : 226336882
$result = mysql_query("select *, trim(TRAILING FROM password) as trim_pass from tb_userinfo_dup where number = '217069082' ") or die(mysql_error());	
$row = mysql_fetch_array($result);


//OV9LFI8nrBsIWxEjIo9v4HwrsdiB6s

$number = $row[number];
$password = $row[trim_pass]."=>".decrypt($key, $iv, $row[trim_pass]);
$password = TRIM($password);
echo $password."<br>";

//print_r($row);
//echo "<br>";


	echo "로그인 토근발행 -> ";
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
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$token = $result[token];
	echo "<pre>";
	print_r($result);
	print_r($status);
	echo "</pre>";
		
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

	echo $token."<Br>";
	echo $me_url."<Br>";
	flush();
	ob_flush();

	/*
	$infodata = array();
	$infodata[humanName] = array(
				'firstName' => 'JY',
				'lastName' => 'LEE1',
				'fullName' => 'JY LEE1',
				'fullName@ko' => '이정윤'
				);

	$data = json_encode($infodata);
	echo $data."<br>";
	
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

	echo "<pre>";
	print_r($data);
	echo "</pre>";
	flush();
	ob_flush();
	

	//정보
	$headers = array(
		//'Content-Type: application/json',
		'Authorization: Bearer '.$token
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $me_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result = curl_exec($ch); 
	$data = json_decode($result, true);
	
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	*/

	curl_close($ch);


	exit;

	sleep(1);



	//권한업데이트
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

	$url = 'https://hydra.unicity.net/v5a/customers/me/rights';

	echo "==============".$token."=============<br>";


	/*
	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$number;
	$username = 'krWebEnrollment';
	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	$response = curl_exec($ch);
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if (($response !== false) && ($status == 200)) {
		
		$response = json_decode($response, true);
		 
		if (isset($response['items'][0]['href'])) {	
			$url = $response['items'][0]['href'].'/rights';
			
			foreach($rightsArray as $key=>$val) {

				$title = $val[0];
				$holder = $val[1];
				$type = $val[2];
				$agree = $val[3];

				if(strtoupper($agree) == 'Y') {
					curl_setopt($ch, CURLOPT_URL, $url);		
					$data = http_build_query(array(
						'holder' => $holder,
						'type' => $type
					));			
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		
				} else {				
					$url .= '?type='.$type.'&holder='.$holder;								
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				}

				$response = curl_exec($ch);
				$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				echo $title.$agree.":";
				if($status == "201" || $status == "204"){  //delete인경우 204
					$result = json_decode($response);
					echo "(".$result->type.")->OK<br>";			
				}else{					
					print_r($response);
					print_r($status);
					echo "->Fail<br>";		
				}
				flush();
				ob_flush();

				//apiLog('동의사항 업데이트 - '.$title.' : '.$agree.' (PC)', $mem_account, $holder.",".$type, $response);		
			}

		}
	}
	curl_close($ch);

	sleep(1);

	*/
	

	/*
	//renew roken - 결과값 이상함
	$data = json_encode(
		array(
			"type"=>"loginToken",
			"value"=>$token,
			"namespace"=>"https://hydra.unicity.net/v5a/customers"
		));	
	$ch = curl_init();		
	curl_setopt($ch, CURLOPT_URL, 'https://hydra.unicity.net/v5a/loginTokens');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	//$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$result = json_decode($response);
	//print_r($result);
	//print_r($status);
	flush();
	ob_flush();
	curl_close($ch);

	echo "<br>===================<br>";
	print_r($result);
	*/	

	/*
	
	foreach($rightsArray as $key=>$val) {

		$title = $val[0];
		$holder = $val[1];
		$type = $val[2];
		$agree = $val[3];
		
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
		echo $title.$holder.$type.$agree.":";

		$result = json_decode($response);
		//print_r($response);
		//print_r($status);

		if($status == "201" || $status == "204"){  //delete인경우 204
			echo "(".$result->type.")->OK<br>";			
		}else{			
			echo "->Fail<br>";		
		}
		flush();
		ob_flush();
		curl_close($ch);
	}
	sleep(1);
	*/


	//$data = http_build_query(
	//		array(
	//			'value' => $new_password
	//		));
	//$data = json_encode($data);

	$ch = curl_init();		

	$headers = array(
		//'Content-Type: application/json',
		'Authorization: Bearer '.$token
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $me_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$result = curl_exec($ch); 
	$data = json_decode($result, true);
	
	echo "========data parse=====<br>";
	echo $data[unicity]."<br>";
	echo $data[mainAddress][address1]."<br>";
	echo $data[mainAddress][address2]."<br>";
	echo $data[mainAddress][city]."<br>";
	echo $data[mainAddress][state]."<br>";
	echo $data[mainAddress][zip]."<br>";
	echo "<br>";
	echo $data[humanName]['firstName']."<br>";
	echo $data[humanName]['lastName']."<br>";
	echo $data[humanName]['fullName']."<br>";
	echo $data[humanName]['fullName@ko']."<br>";
	echo "<br>";
	echo $data[id]['unicity']."<br>";
	echo "<br>";
	echo $data[sponsoredCustomers]['href']."<br>";
	echo "<br>";
	echo $data[birthDate]."<br>";
	echo $data[email]."<br>";
	echo $data[enroller]['href]']."<br>";
	echo $data[enroller]['id']."<br>";
	echo $data[gender]."<br>";
	echo $data[homePhone]."<br>";
	echo $data[mobilePhone]."<br>";
	echo $data[taxTerms]['taxId']."<br>";
	print_r($data[rights]);


	foreach($rightsArray as $key=>$val) {

		$title = $val[0];
		$holder = $val[1];
		$type = $val[2];
		$agree = $val[3];


		echo "-".$title."->";

		$cnt = 0;
		for($i=0; $i<count($data[rights]); $i++) {
			if($data[rights][$i][type] == $type){
				$cnt = 1;
				break;
			}
		}
		if($cnt = 1) echo "Y<Br>";
		else echo "N<br>";
	}
	/*
	for($i=0; $i<count($data[rights]); $i++) {
		if($data[rights][$i][type] == 'SendNoticeEmail') echo '이메일 통보 : '
		else if($data[rights][$i][type] == 	'SendNoticeSms'	"agree_02" => array('주요안내사항 통보', 'Unicity', , $row['agree_02']),
		else if($data[rights][$i][type] == 	'SendMail'	"agree_03" => array('SMS 통보', 'Unicity', , $row['agree_03']),
		else if($data[rights][$i][type] == 	'ShareOrdersDataWithTravelAgency'	"selchk1"  => array('개인정보 (하나투어, 레드캡, SMTT) 제공 동의', 'Unicity', , $row['sel_agree01']),
		else if($data[rights][$i][type] == 	'SendMarketingEmail'	"selchk2"  => array('마케팅 목적의 이메일 수신 동의', 'Unicity', , $row['sel_agree02']),
		else if($data[rights][$i][type] == 	'SendMarketingSms'	"selchk3"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', , $row['sel_agree03']),
		else if($data[rights][$i][type] == 	'SendMarketingMail'	"selchk4"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', , $row['sel_agree04']),
		else if($data[rights][$i][type] == 	'Order'	"selchk5"  => array('본인외 주문에 대한 동의', 'Upline', , $row['sel_agree05'])

		echo $data[rights][$i][holder]."->". $data[rights][$i][type]."<br>";
	}
	*/
	echo "<br>";
	
	if (isset($result["error"]["code"])) {
		 echo $result["error"]["code"]."<br>";
		 flush();
		 ob_flush();
		 return;
	}

	flush();
	ob_flush();




	curl_close($ch);



