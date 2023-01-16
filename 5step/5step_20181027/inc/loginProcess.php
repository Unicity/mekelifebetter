<?php
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";

	$inputUsername = $_POST["username"];
	$inputPassword = $_POST["inputPassword"];

	if (isEmpty($inputUsername) || isEmpty($inputPassword))
	{
		moveTo("../index.html");
	}

	$url = "https://hydra.unicity.net/v5/loginTokens?expand=whoami";
	$key = base64_encode($inputUsername.":".$inputPassword);
	
	$nextLink = "";

	$data = http_build_query(
		array(
			'type' => 'base64',
			'value' => $key,
			'namespace' => "https://hydra.unicity.net/v5/customers"
		)
	);

	$response = postAPI($url, $data);

	$token = isset($response['token']) ? $response['token'] : '';
	$url = isset($response['href']) ? $response['href'] : '';
	 
	if ($token != '' && $url != ''  ){
		
		updateExpiredProgram($inputUsername);

		$query = "SELECT User.id, User.name, pm.programID "
				."	FROM User "
				."		LEFT OUTER JOIN ProgramMaster AS pm "
				."			ON User.id = pm.userID "
				."			AND pm. delFlag='N' "
				."				WHERE id = '".$inputUsername."' ORDER BY pm.programID desc limit 1";

		$result = mysql_query($query);

		$userInfo = mysql_fetch_array($result);

		if($userInfo["id"] == "" || isset($userInfo["id"]) == false) {

			$getNameURL = "https://hydra.unicity.net/v5/customers?unicity=".$inputUsername."&expand=customer";
			
			$getNameReponse = getAPI($getNameURL); 

			$memberName = $getNameReponse['items'][0]['humanName']['fullName@ko'];
			
			if(isset($memberName)) {
				$query = "insert into User (`id`,`department`,`name`) values ('".$inputUsername."','Mem','".$memberName."')";
				mysql_query($query) or die("Query Error");
				$nextLink = "../pages/programSetup01.php";

			} else {
				DisplayAlert("문제가 발생했습니다. 고객센터로 문의해 주시기 바랍니다.");
				$nextLink = "../index.html";
			}
		 
		} else {
			if($userInfo["programID"] == "" || isset($userInfo["programID"]) == false || $userInfo["programID"] == null) {
				$nextLink = "../pages/programSetup01.php";
			} else {
				$nextLink = "../pages/mainpage.php";
			}
		}
		if (is_session_started() === FALSE) {
        // php >= 5.4.0
       // 	if (session_status() == PHP_SESSION_NONE) {
        	session_start();
    	//	}
    	}
		$_SESSION["username"] = isset($userInfo["id"]) ? $userInfo["id"] : $inputUsername;
		$_SESSION["realname"] = isset($userInfo["name"]) ? $userInfo["name"] : $memberName;
		$_SESSION["programID"] = isset($userInfo["programID"]) ? $userInfo["programID"] : null;

		//$_SESSION["gender"] = $userInfo["gender"];
		//$_SESSION["cleanser"] = $userInfo["isCleanser"];
			
		//echo $userInfo["id"]. '/ '.$userInfo["name"] .' '. $userInfo["isCleanser"];
		mysql_close($connect);
		moveTo($nextLink);
		
	}else {
		DisplayAlert("ID/비밀번호가 잘못되었습니다.");

		moveTo("../index.html");

	}

?>