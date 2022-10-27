<?php
	header("Content-Type: text/html; charset=UTF-8");


	include "../inc/function.php";

	$inputUsername = $_POST["inputUsername"];
	$inputPassword = $_POST["inputPassword"];

	if (isEmpty($inputUsername) || isEmpty($inputPassword))
	{
		redirect("../index.html");
	}

	$url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
	$key = base64_encode($inputUsername.":".$inputPassword);
	
	$data = http_build_query(array(
					 			'type' => 'base64',
								'value' => $key,
								'namespace' => "https://hydra.unicity.net/v5a/employees"
								)
							);

						 
	$response = postAPI($url, $data);

	if (is_array($response)){
	    // 로그인 API변경으로 방법 변경 20200813
		//if($response["whoami"]["username"] == "employee") {
			// php < 5.4.0
			if (session_id() == '') {
			// php >= 5.4.0
			// if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			$_SESSION["username"] = $response["employee"]["username"];
			$_SESSION["token"] = $response["token"];
		//	DisplayAlert($_SESSION["username"]);
			moveTo("./mainpage.php");
			
		/*로그인 API변경으로 방법 변경 20200813
		} else {
			DisplayAlert("Unicity Korea의 직원이 아닙니다.");
			
			moveTo("../index.html");
		}*/
	}else {
		DisplayAlert("ID/비밀번호가 잘못되었습니다.");
		
		moveTo("../index.html");

	}
	 
?>
