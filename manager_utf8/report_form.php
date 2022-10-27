<?session_start();
	ini_set("display_errors", 0);

	include_once($_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/str_check.php"); 

	function getCustomerURL($id){
		$url = "https://hydra.unicity.net/v5a/customers?id.unicity=".$id."&expend=customer";

		//	$username = 'krWebEnrollment';
		//	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);
		
		$response = curl_exec($ch);
		
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (($response != false) && ($status == 200)) {
	 	
	 		$result = json_decode($response, true);
	 	}

	 	curl_close($ch);
	
		return $result;

	}
	$VolumePeriod = str_quote_smart_session($VolumePeriod);
	echo "VolumePeriod".$VolumePeriod;
	$id = str_quote_smart_session($id);

	if(session_is_registered("s_adm_id")){
		$report_flag="Y";
	}else{
		if ($s_number) {
			if($id==$s_number){
				$report_flag="Y";
			}else{
				$report_flag="N";
			}
		}else{
			$report_flag="N";
		}
	}

	if($report_flag=="N"){
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
		<script language="javascript">
			alert("세션이 종료 되었거나 보안검사에 실패하였습니다.\n\n 다시 로그인 후 시도해주세요. \n\n 같은 현상이 반복될 시 고객센타로 문의 주시기 바랍니다.");
			self.close();
		</script>
<?
		die;
	}
	else {
		//Find user by unicity id
		
		$customerURL =  getCustomerURL($id);
		$customerURL = $customerURL['items'][0]['href'].'/commissionstatements';
		
		//echo "customerURL==>".$customerURL;
		$username = 'krWebEnrollment';
		$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $customerURL);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);

			 
		$response = json_decode($response, true);
		var_dump($response);
		$url = $response['items'][0]['href'];
		
		 echo "url==>".$url;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<title>Bonus Detail</title>
<style type="text/css">
body, table, td { margin : 0; padding : 0; }
body { font-size:12px; color:#3e6682; font-family: verdana, 돋움; }
 
</style>
</head>
 
<body onload="">
 
 <?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<? }
?>
