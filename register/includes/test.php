<?php
session_start();
if (!include_once("../includes/dbconfig.php")){
	echo "The config file could not be loaded";
}

$result = mysql_query("select count(*) as cnt from tb_log_v2_start where date = '".date("Y-m-d")."' and flag = 'Y'") or die(mysql_error());	
$row = mysql_fetch_array($result);

if($row['cnt'] < 1){

	//전문전송
	$ekey = "A91D2B6121AA07C748B9CA4323963E69";
	$msalt = "MA01";
	$kscode = "1372";

	$sendDate = date("Ymd");
	$sendTime = date("Hid");
	$companyCode = "UPCHE214";
	$companyBankCode = "026";

	$sendData = 'JSONData={
					"kscode":"'.$kscode.'","ekey":"'.$ekey.'","msalt":"'.$msalt.'",
					"reqdata":
					[
						{
							"date":"'.$sendDate.'",
							"time":"'.$sendTime.'",
							"seqNo":"'.(int)$sendTime.'",
							"compCode":"'.$companyCode.'",
							"bankCode":"'.$companyBankCode.'"
						}
					]
				}';


	$api_url = 'https://cmsapi.ksnet.co.kr/ksnet/rfb/bankstart';
	$ch = curl_init($api_url); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData); 
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$reponse = curl_exec($ch); 
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch); 		

	$resultJson = json_decode($reponse);

	echo $status."<br>";
	echo $resultJson->replyCode;  //0000
	echo $resultJson->successYn;  //Y
	print_r($resultJson);

	$flag = ($resultJson->replyCode == '0000') ? 'Y' : 'N';

	//결과등록
	$sql = "insert into tb_log_v2_start (date, sendData, recieveData, flag, logdate) values ('".date("Y-m-d")."','".$sendData."','".$reponse."','".$flag."',now())";
	$result = mysql_query($sql) or die(mysql_error());	

}
?>