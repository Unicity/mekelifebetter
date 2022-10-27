<?php
	include "../inc/common_function.php";

	function getToken($inputUsername, $inputPassword){
		$url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
		$key = base64_encode($inputUsername.":".$inputPassword);
	
		$nextLink = "";

		$data = http_build_query(
			array(
				'type' => 'base64',
				'value' => $key,
				'namespace' => "https://hydra.unicity.net/v5a/customers"
			)
		);

		$response = postAPI($url, $data);

		$token = isset($response['token']) ? $response['token'] : 'error';
		// $url = isset($response['href']) ? $response['href'] : '';

		return $token;
		
	}
	function postAPI($url, $data)
	{	 
		$result = "";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
				
		$result = json_decode($response, true);
	 	print_r($result);
	 	if (isset($result["error"]["code"])) {
	 		 $result = "error";
	 	}

	 	curl_close($ch);
		
		return $result;
		
	}
	function getAPI($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			 
		$response = curl_exec($ch);
		
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (($response != false) && ($status == 200)) {
	 	
	 		$result = json_decode($response, true);
	 	}

	 	curl_close($ch);
	
		return $result;
	
	}
	
	function getPasswordResetToken($number, $contactNo) {
		$customer = "https://hydra.unicity.net/v5a/customers?id.unicity=".$number;
 
	 	$data = json_encode(
					array(
						'customer' => array(
							'href' =>  $customer
						),
						'mobilePhone' => $contactNo
					)
				);
	 	
	 	echo $data;
	 	
	 	$url = "https://hydra.unicity.net/v5a/passwordresettokens";

	 	$result = postAPI($url, $data);
	 
	 	print_r($result);
	}

	function updateUserPassword($number, $decryptPassword) {

		//Find user by unicity id
		$url = "https://hydra.unicity.net/v5a/customers?id.unicity=".$number;

		//	$username = 'krWebEnrollment';
		//	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);
		
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			 

		if (($response != false) && ($status == 200)) {
		
			$response = json_decode($response, true);	 
			
			if (isset($response['items'][0]['href'])) {
				$url = $response['items'][0]['href'].'/password';
				
				echo $url.'<br>';
				curl_setopt($ch, CURLOPT_URL, $url);
				$data = http_build_query(
					array ('value'=> $decryptPassword )
				);
				print_r($data);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
				$response = curl_exec($ch);
				 
				print_r($response);	

				curl_close($ch);
			}
			
		}
	}
	function updateUserInfoAPI($city, $state, $zip, $address1, $address2, $email, $mobilePhone, $homePhone, $token)
	{

		$data =  json_encode(
					array(
						'mainAddress' => array(
							'city' =>  $city,
							'country' => 'KR',
							'state' => $state,
							'zip' => $zip,
							'address1' => $address1,
							'address2' => $address2
						),
						'email' => $email,
						'mobilePhone' => $mobilePhone,
						'homePhone' => $homePhone 
		 			)
				);
		

		$url = 'https://hydra.unicity.net/v5a/customers/me'; // REAL

		$headers = array(
    		'Content-Type: application/json',
    		'Authorization: Bearer '.$token
  		);
	 	print_r($headers);

	 	$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output = curl_exec($ch); 
	 
	 	$result = json_decode($output, true);
		
		print_r($result); 
		//APIlogger($newMemberId, $data, $output);
	
		curl_close($ch);
	}
	// not using because of token
	function updateUserInfo($mem_account, $city, $state, $zip, $address1, $address2, $email, $mobilePhone, $homePhone, $account_bank, $search_bank_code, $account_name, $account) {
		$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account; //real
		//$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account; //test

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
			 
		if (($response != false) && ($status == 200)) {
			$response = json_decode($response, true);
			 
			if (isset($response['items'][0]['href'])) {
				$url = $response['items'][0]['href'];
					 
				curl_setopt($ch, CURLOPT_URL, $url);
				$data =  http_build_query(
					array(
						'mainAddress' => array(
							'city' =>  $city,
							'country' => 'KR',
							'state' => $state,
							'zip' => $zip,
							'address1' => $address1,
							'address2' => $address2
						),
						'email' => $email,
						'mobilePhone' => $mobilePhone,
						'homePhone' => $homePhone ,

						'depositBankAccount' => array(
						'bankName' => $account_bank,
						'bin' => $search_bank_code,
						'accountHolder' => $account_name,
						'accountNumber' => $account,
						'accountType' => 'SavingsPersonal',
						'routingNumber' => 1,
						)
		 			)
				);
				 
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$response = curl_exec($ch);
				 
				$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				//APIlogger($mem_account, $data, $response); 
				
				curl_close($ch);
			}
		}

	}

	function updateAccountInfo($mem_account, $account_bank, $search_bank_code, $account_name, $account)
	{
		$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account; //real
		//$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account; //test

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
			 
		if (($response != false) && ($status == 200)) {
			$response = json_decode($response, true);
			 
			if (isset($response['items'][0]['href'])) {
				$url = $response['items'][0]['href'];
					 
				curl_setopt($ch, CURLOPT_URL, $url);
				$data = http_build_query(array(
					'depositBankAccount' => array(
						'bankName' => $account_bank,
						'bin' => $search_bank_code,
						'accountHolder' => $account_name,
						'accountNumber' => $account,
						'accountType' => 'SavingsPersonal',
						'routingNumber' => 1,
					)
				,
				));
				 
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$response = curl_exec($ch);
				 
				$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				APIlogger($mem_account, $data, $response); 
				
				curl_close($ch);
			}
		}
	}
	 
	function getBankCode($bankname){
	 	$bankCodes = array(
			"BOA은행" => "060", 
			"HMC투자증권" => "263", 
			"HSBC은행" => "054", 
			"LIG투자증권" => "292", 
			"NH투자증권" => "289", 
			"SC제일은행" => "023", 
			"SK증권" => "266", 
			"경남은행" => "039", 
			"광주은행" => "034", 
			"교보증권" => "261", 
			"국민은행" => "004", 
			"기업은행" => "003", 
			"농협중앙회" => "011", 
			"농협회원조합" => "012", 
			"대구은행" => "031", 
			"대신증권" => "267", 
			"대우증권" => "238", 
			"동부증권" => "279", 
			"동양종합금융증권" => "209", 
			"메리츠종합금융증권" => "287", 
			"미래에셋증권" => "230", 
			"미쓰비시도쿄UFJ은행" => "059", 
			"미즈호코퍼레이트은행" => "058", 
			"부국증권" => "290", 
			"부산은행" => "032", 
			"산업은행" => "002", 
			"삼성증권" => "240", 
			"상호저축은행" => "050", 
			"새마을금고연합회" => "045", 
			"수협중앙회" => "007", 
			"신영증권" => "291", 
			"신용보증기금" => "076", 
			"신한금융투자" => "278", 
			"신한은행" => "088", 
			"신협중앙회" => "048", 
			"외환은행" => "005", 
			"우리은행" => "020", 
			"우리투자증권" => "247", 
			"우체국" => "071", 
			"유진투자증권" => "280", 
			"이트레이드증권" => "265", 
			"전북은행" => "037", 
			"제주은행" => "035", 
			"키움증권" => "264", 
			"하나대투증권" => "270", 
			"하나은행" => "081", 
			"하이투자증권" => "262", 
			"한국씨티은행" => "027", 
			"한국투자증권" => "243", 
			"한화증권" => "269", 
			"KB증권" => "218", 
			"케이뱅크" => "089", 
			"카카오뱅크" => "090"
		);
		return $bankCodes[$bankname];
	}
	?>