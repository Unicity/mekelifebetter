<?php
session_start();
include_once "./includes/dbconfig.php";

$q = isset($_POST["q"])? $_POST["q"] : "";
if ($q == "") {
	echo "none";
	return;
}

$user_device = "P";
$mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
if (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
	$user_device = "M";
}

$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$q.'&expand=customer';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	 
$response = curl_exec($ch);
$response2 = $response; 
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (($response !== false) && ($status == 200)) {
	$response = json_decode($response, true);
	
	if ($response['items'][0]['status'] == 'Active' && $response['items'][0]['type'] == 'Associate') {  
		$result = isset($response['items'][0]['humanName']['fullName@ko'])? $response['items'][0]['humanName']['fullName@ko'] : $response['items'][0]['humanName']['fullName'] ;
	}else{
		$result = "200";
	}
}else if($status == '404'){
	$result = "404";
}else{
	$result = "none2";;
}

//로그v2저장
if(substr($result,0,4) == "none" || $result == "404" || $result == "200"){
	$yn = "N";
	$success = "후원자조회api-실패";
}else{
	$yn = "Y";
	$success = "후원자조회api-성공";
}
$qlog = "insert into tb_log_v2 (tmpId, gubun, check_kind, name, data1, data2, sendData, recieveData, msg, flag, device, logdate) values 
	( '".$_SESSION['ssid']."', '$success', 'api', '".$_SESSION['S_NM']."', '$q', '$status', '$q', '".addslashes($response2)."', '$result', '$yn', '$user_device', now())";	
mysql_query($qlog) or die(mysql_error());

echo $result;
curl_close($ch);
?>