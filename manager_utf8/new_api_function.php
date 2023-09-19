<?
session_start();

function updateMembersInformation($mem_account, $email, $mobilePhone, $phone){

	//echo "이메일, 모바일, 전화번호 업데이트 : ";
	flush();
	ob_flush();
	
	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account.'&expand=customer';
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
	
		//$response = json_decode($response, true);
		$data = json_decode($response);
	 
		if($data->items[0]->href != "") {
			$url2 = $data->items[0]->href;
			$postdata = http_build_query(
				array(
					'email' => $email,
					'mobilePhone' => $mobilePhone,
					'homePhone' => $phone
					)				
				);	

			curl_setopt($ch, CURLOPT_URL, $url2);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$response3 = curl_exec($ch);
			$status3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//print_r($response3);
			
			//$response3 = str_replace("fullName@ko","fullNameko",$response3); //@ 있을시 PHP syntax error
			$data = json_decode($response3);
			curl_close($ch);

			apiLog('회원정보수정-Email,HP,TEL(PC)', $mem_account, $postdata, $response3);		

		}else{
			echo "error";
			return;
		}
	}else{
		echo "error";
		return;
	}

	echo "OK";

	flush();
	ob_flush();
}

function updateMembersEmailAndHp($mem_account, $email, $mobilePhone){

	echo "이메일, 모바일 업데이트 API : ";
	flush();
	ob_flush();
	
	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account.'&expand=customer';
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
	
		//$response = json_decode($response, true);
		$data = json_decode($response);
	 
		if($data->items[0]->href != "") {
			$url2 = $data->items[0]->href;
			$postdata = http_build_query(
				array(
					'email' => $email,
					'mobilePhone' => $mobilePhone
					)				
				);	

			curl_setopt($ch, CURLOPT_URL, $url2);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$response3 = curl_exec($ch);
			$status3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			//$response3 = str_replace("fullName@ko","fullNameko",$response3); //@ 있을시 PHP syntax error
			$data = json_decode($response3);
			curl_close($ch);

			apiLog('회원정보수정-Email,HP(PC)', $mem_account, $postdata, $response3);		

		}else{
			echo "error";
			return;
		}
	}else{
		echo "error";
		return;
	}

	echo "OK";

	flush();
	ob_flush();
}

function updateMembersBank($mem_account, $account_bank, $search_bank_code, $account_name, $account){
	
	//echo "계좌 업데이트 API : ";
	flush();
	ob_flush();
	
	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account.'&expand=customer';
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
					'routingNumber' => 1,
				)
			));
			//"depositBankAccount%5BbankName%5D=%EA%B8%B0%EC%97%85%EC%9D%80%ED%96%89&depositBankAccount%5Bbin%5D=003&depositBankAccount%5BaccountHolder%5D=%25ED%2599%25A9%25EA%25B8%2588%25EC%25B2%259C&depositBankAccount%5BaccountNumber%5D=01081743220&depositBankAccount%5BaccountType%5D=SavingsPersonal&depositBankAccount%5BroutingNumber%5D=1"
			curl_setopt($ch, CURLOPT_URL, $url2);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$response2 = curl_exec($ch);
			$status2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$logResult = $response2;

			print_r($response2);

			if (($response2 !== false) && ($status2 == 200 ||$status2 == 201)) {		
				echo "OK";
			}else{
				echo $status2." error";
			}
		
		} else {
			echo "통신장애";
		}

	}else{
		echo "통신장애";
		
	}
	apiLog('회원정보수정-계좌(PC)', $mem_account, $postdata, $response2);		
}


function updateMembersAddress($mem_account, $addressData){

	//echo "주소  업데이트 API : ";
	flush();
	ob_flush();
	
	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account.'&expand=customer';
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
	
		//$response = json_decode($response, true);
		$data = json_decode($response);
	 
		if($data->items[0]->href != "") {
			$url2 = $data->items[0]->href;					 
		
			//주소업데이트
			$postdata = http_build_query(
				array(
					'mainAddress' => array(
						'address1' => $addressData['address1'],
						'address2' => $addressData['address2'],
						'city' => $addressData['city'],
						'state' => $addressData['state'],
						'zip' => $addressData['zip'],
						'country' => 'KR'
					)				
				));	

			curl_setopt($ch, CURLOPT_URL, $url2);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			$response3 = curl_exec($ch);
			$status3 = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			//$response3 = str_replace("fullName@ko","fullNameko",$response3); //@ 있을시 PHP syntax error
			$data = json_decode($response3);
			curl_close($ch);

			apiLog('주소 업데이트(PC)', $mem_account, $postdata, $response3);		


		}else{
			echo "error";
			return;
		}
	}else{
		echo "error";
		return;
	}

	echo "OK";

	flush();
	ob_flush();
}

function updateMembersPassword($member_id, $old_password, $new_passowrd){

	echo "비밀번호 변경 토근발행 : ";
	flush();
	ob_flush();

	//로그인 토근발행
	$url = "https://hydra.unicity.net/v5a/loginTokens";
	$key = base64_encode($member_id.":".$old_password);
	$data = http_build_query(
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
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$response = curl_exec($ch);
	$result = json_decode($response, true);
	$token = $result[token];
	//echo "<pre>";
	//print_r($result);
	//echo "</pre>";
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

	echo $token."<br>";
	echo "비밀번호 업데이트 : ";
	flush();
	ob_flush();


	$data = http_build_query(
			array(
				'value' => $new_password
			));
	//$data = json_encode($data);
	$url = 'https://hydra.unicity.net/v5a/customers/me/password'; // REAL
	$headers = array(
		//'Content-Type: application/json',
		'Authorization: Bearer '.$token
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$output = curl_exec($ch); 
	$result = json_decode($output, true);
	curl_close($ch);
	if (isset($result["error"]["code"])) {
		 echo $result["error"]["code"]."<br>";
		 flush();
		 ob_flush();
		 return;
	}
	echo "OK<br>";
	flush();
	ob_flush();

	apiLog('비밀번호 업데이트(PC)', $mem_account, $data, $output);		

	return "OK";

}


function updateMembersRights($mem_account, $rightsArray){

	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account;
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

				echo $title.":";
				if($status == "201" || $status == "204"){  //delete인경우 204
					$result = json_decode($response);
					echo "(".$result->type.")->OK<br>";			
				}else{
					echo $agree;
					print_r($response);
					print_r($status);
					echo "->Fail<br>";		
				}
				flush();
				ob_flush();

				apiLog('동의사항 업데이트 - '.$title.' : '.$agree.' (PC)', $mem_account, $holder.",".$type, $response);		
			}

		}
	}
	curl_close($ch);
}

function updateMembersRightsNew($mem_account, $rightsArray){

	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account;
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

				foreach($val as $key2=>$val2) {
					$title = $val2[0];
					$holder = $val2[1];
					$type = $val2[2];
					$agree = $val2[3];
				}

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

				echo $title.":";
				if($status == "201" || $status == "204"){  //delete인경우 204
					$result = json_decode($response);
					//print_r($response);
					echo "->OK<br>";			
				}else{
					echo $agree;
					//print_r($response);
					//print_r($status);
					echo "->Fail<br>";		
				}
				flush();
				ob_flush();

				apiLog('동의사항 업데이트 - '.$title.' : '.$agree.' (PC)', $mem_account, $holder.",".$type, $response);		
			}

		}
	}
	curl_close($ch);
}


function apiLog($page, $id, $input, $output){
	@mysql_query("insert into tb_api_log (page, member_no, send_data, return_data, regDate) values ('".$page."', '".$id."', '".$input."', '".$output."', now())");
}
?>