<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	include "./common_functions.php";
	
	$id = isset($_POST['username']) ? $_POST['username'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';

	

	if ($id != '' && $password != '' ){

		$url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
		$key = base64_encode($id.":".$password);
	
		$data = http_build_query(array(
					 			'type' => 'base64',
								'value' => $key,
								'namespace' => "https://hydra.unicity.net/v5a/customers"
								)
							);
		$response = postAPI($url, $data);

		//$authorizationCode = "Bearer ".$response['token'];
		if (is_array($response)){	
	//		$url2 = $response['customer']['href'];
		
			if (session_id() == '') {
	        // php >= 5.4.0
	        // if (session_status() == PHP_SESSION_NONE) {
	        	 
	        	session_start();
	    	}
				
			$_SESSION["token"] = $response['token'];
			$_SESSION["username"] = $id;

			echo "<script> window.location.href = 'https://www.makelifebetter.co.kr/withholdingtax/certification.php';</script>";

		 
		} else {
				 echo "<script> alert('통신 중 오류가 발생했습니다. 다시 시도 해 주세요.'); window.location.href = 'https://www.makelifebetter.co.kr/withholdingtax/login.php';</script>";
			
		} 
	} else {
		echo "<script> alert('아이디 또는 비밀번호가 잘못되었습니다.'); window.location.href = 'https://www.makelifebetter.co.kr/withholdingtax/login.php';</script>";

	}

	

?>