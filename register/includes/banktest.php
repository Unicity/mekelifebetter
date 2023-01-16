<?php 
//API방식
$ekey = "A91D2B6121AA07C748B9CA4323963E69";
$msalt = "MA01";
$kscode = "1372";


$sendDate = date("Ymd");
$sendTime = date("Hid");
$companyCode = "UPCHE214";
$companyBankCode = "026";

$bankcode = "004";
$accountNo = "28220104154421";



//계좌인증
$sendData = 'JSONData={
				"kscode":"'.$kscode.'","ekey":"'.$ekey.'","msalt":"'.$msalt.'",
				"reqdata":
				[
					{
						"date":"'.$sendDate.'",
						"time":"'.$sendTime.'",
						"seqNo":"'.$sendTime.'",
						"accountBankCode":"'.$bankcode.'",
						"accountNo":"'.$accountNo.'",
						"agencyYn":"N",						
						"compCode":"'.$companyCode.'",
						"bankCode":"'.$companyBankCode.'"
					}
				]
			}';
$sendData = 'JSONData={"kscode":"1372","ekey":"A91D2B6121AA07C748B9CA4323963E69","msalt":"MA01", "reqdata": [ { "date":"20220921", "time":"161121", "seqNo":"161121","accountBankCode":"004","accountNo":"28220104154421","agencyYn":"N","compCode":"UPCHE214","bankCode":"026"}]}';
print_r($sendData);

//계좌인증전송
$api_url = 'https://cmsapi.ksnet.co.kr/ksnet/rfb/account/accountname'; //운영
//$api_url = 'https://cmsapitest.ksnet.co.kr/ksnet/rfb/account/accountname';  //개발

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
print_r($status);
print_r($reponse);


$yn = ($resultJson->accountName != "" && $resultJson->replyCode = "0000") ? "Y" : "N";

?>