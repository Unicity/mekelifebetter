<?php
	header("Content-Type: text/html; charset=UTF-8");
	if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./function.php";
	include "./AES.php";

	$inputUsername = $_POST["username"];
	$inputPassword = $_POST["inputPassword"];

	if (isEmpty($inputUsername) || isEmpty($inputPassword))
	{
		moveTo("../index.html");
	}

	$inputUsername = clean($inputUsername);
	//$inputPassword = clean($inputPassword);
	$encInputPassword = encrypt($key, $iv, $inputPassword);

	$isMember = loginAPI($inputUsername, $inputPassword);
	 
	$IDCheckQuery = "SELECT User.id, UserInfo.Password FROM User LEFT OUTER JOIN UserInfo ON User.id = UserInfo.id WHERE User.id = '".$inputUsername."';";

	$IDCheckResult = mysql_query($IDCheckQuery);

	$isExist = mysql_fetch_array($IDCheckResult);
    

	if ($isExist['id'] != '') {
		 
		if ($isExist['Password'] == null || $isExist['Password'] == '' ){
			echo '<script>console.log('.$isMember.')</script>';
			if($isMember == true) {
				$userInfo = getProgramInfo($inputUsername);

				if (is_session_started() === FALSE) {
      	       		session_start();
    			}
				
				$_SESSION["username"] = isset($userInfo["id"]) ? $userInfo["id"] : $inputUsername;
				$_SESSION["realname"] = isset($userInfo["name"]) ? $userInfo["name"] : $inputUsername;
				if($userInfo['programID'] == null || $userInfo['programID'] == ''){
					$nextLink = "../pages/programSetup01.php";
				} else {
				
					$_SESSION["programID"] = isset($userInfo["programID"]) ? $userInfo["programID"] : null;
					$_SESSION["programType"] = isset($userInfo["programType"]) ? $userInfo["programType"] : 0;
					$_SESSION["bloodsugar"] = isset($userInfo["bloodsugar"]) ? $userInfo["bloodsugar"] : 0;

	 				mysql_close($connect);
	 				$nextLink = "../pages/mainpage.php";
				}
			} else {
				DisplayAlert("아이디 또는 비밀번호를 잘못 입력하셨습니다.1");
				$nextLink = "../index.html";
			}
		} else {

			if($encInputPassword == $isExist['Password']) {
				if (is_session_started() === FALSE) {
      	       		session_start();
    			}
				
				$_SESSION["username"] = isset($userInfo["id"]) ? $userInfo["id"] : $inputUsername;
				$_SESSION["realname"] = isset($userInfo["name"]) ? $userInfo["name"] : $inputUsername;
				
				$userInfo = getProgramInfo($inputUsername);
				

				if($userInfo['programID'] == null || $userInfo['programID'] == ''){
					$nextLink = "../pages/programSetup01.php";
				} else {
					$_SESSION["programID"] = isset($userInfo["programID"]) ? $userInfo["programID"] : null;
					$_SESSION["programType"] = isset($userInfo["programType"]) ? $userInfo["programType"] : 0;
					$_SESSION["bloodsugar"] = isset($userInfo["bloodsugar"]) ? $userInfo["bloodsugar"] : 0;

	 				mysql_close($connect);
	 				$nextLink = "../pages/mainpage.php";
				}

			}else {
				DisplayAlert("아이디 또는 비밀번호를 잘못 입력하셨습니다.2");
				$nextLink = "../index.html";
			}
		}

	} else {
		if($isMember == true) {
			$getNameURL = "https://hydra.unicity.net/v5a/customers?unicity=".$inputUsername."&expand=customer";
			
			$getNameReponse = getAPI($getNameURL); 

			$memberName = $getNameReponse['items'][0]['humanName']['fullName@ko'];
			
			if(isset($memberName)) {
				$query = "insert into User (`id`,`department`,`name`) values ('".$inputUsername."','Mem','".$memberName."')";
				mysql_query($query) or die("Inser User Query Error");
				$nextLink = "../pages/programSetup01.php";

			} else {
				DisplayAlert("문제가 발생했습니다. 잠시후 다시 시도해 주시기 바랍니다.");
				$nextLink = "../index.html";
			}
		} else {
			DisplayAlert("아이디 또는 비밀번호를 잘못 입력하셨습니다.3");
			$nextLink = "../index.html";
		}
	}

	moveTo($nextLink);
?>