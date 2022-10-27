<?php
	function redirect($url)
	{
    	header('Location: ' . $url);
    	exit();
	}

	function isEmpty($value)
	{	// php < 5.4.0
		if ($value == '') {
		// php >= 5.4.0
		// if (session_status() == PHP_SESSION_NONE) {
 	   		return true;
 	   	}
 	   	return false;
	}
	function DisplayAlert($msg) {
		echo "<script language=\"javascript\">\n
					alert('$msg');
					</script>";
	}
	function moveTo($location) {
		echo "<script language=\"javascript\">\n
					location.href = '$location'  ;
					</script>";
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
	 	
	 	if (isset($result["error"]["code"])) {
	 		$result = "";
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
	function isAdmin($username) {
		$adminusers = array("silee", "aidenl", "seungb", "ykson", "bkkim", "hyyoon", "sjkim", "hoyoun", "yspark", "tyyu", "jeongso");
		$return = 0;
		
		foreach($adminusers as $admin){
			if($username == $admin) {
				$return = 1;
			}
		}

		return $return;
	}
?>