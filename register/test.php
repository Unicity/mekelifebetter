<?php 
session_start();
header('Content-Type: text/html; charset=UTF-8');
//include_once "./includes/dbconfig.php";


$str = urlencode("580924차동국");

//$url = 'https://hydra.unicity.net/v5a/customers.js?_httpMethod=HEAD&taxTerms_taxId='.$str.'&mainAddress_country=KR';
$url = 'https://hydra.unicity.net/v5a/customers.js?_httpMethod=HEAD&mainAddress_country=KR&taxTerms_taxId='.$str;
echo $url;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //서버연결에 대한 timeout 
curl_setopt($ch, CURLOPT_TIMEOUT, 10); //curl 실행에 대한 timeout 
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$response = curl_exec($ch);			 
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
curl_close($ch);

print_r($status);

echo "<br>";

$result = json_decode($response, true);

echo "<pre>";
print_r($result);
echo "</pre>";

echo $result->data->error->code;
echo "========".$result["data"]["error"]["code"]."===================";

exit;

$k_name = addslashes("꽃😍😍😍돌이 '-')*");
$k_name = preg_replace("/[^가-힣]/u", "", $k_name);  ///u가 없으면 이모지 처리시 에러

echo $k_name."<br>";
echo mb_strlen($k_name)."<br>";
echo urldecode('%F0%9F%98%8D%F0%9F%98%8D%F0%9F%98%8D');
exit;


include "./includes/nc_config.pshp";
include "./includes/AES.php";
include "./includes/TranseId.php";
include "./includes/signup_function_test.php";
//include "./includes/common_functions.php";

$name = "홍길동";
$firstChar =  mb_substr($name, 0, 1, 'utf-8');
echo $firstChar;
echo ord($firstChar);
if(ord($firstChar) <= 127){ //한글이 아닌경우
	$resultCode = "forigner";
}

echo $resultCode;

//암호화 키 재설정
/*
$enckey = hex2bin("12345678901234567890123456789077");
$enciv = hex2bin("12345678901234567890123456789011");
echo decrypt($enckey, $enciv, 'a5LNEfOTUmtxpcedyWz0lQ==')."<br>";
echo encrypt($enckey, $enciv, '01046937955');
*/
exit;
?>
<script>
function checkEmailFormat(string) {
	 
	var stringRegx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	var isValid = true; 
	if(stringRegx.test(string)) { 
		isValid = false; 
	} 
	return isValid;
}

function lengthValidator(value, length)
{
	var result = false;
	if (value == "" || value.length < length) {
		result = true;
	}
	return result;
}

alert(lengthValidator('cjd8164', 2));

if (checkEmailFormat('cjd8164@naver.com')) {
	alert('1');
}
</script>

<?php 
exit;

$q = "193690882";

$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$q.'&expand=customer';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	 
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<pre>";
print_r(json_decode($response));
echo "</pre>";
print_r($status);


echo "<br><br>";


$name = urldecode('김다빈');
$url = 'https://hydra.unicity.net/v5a/customers.js?_httpMethod=HEAD&mainAddress_country=KR&spouse_taxTerms_taxId='.urlencode('810208'.$name);
$sendData = '810208'.urlencode(urldecode($name));	
$return = 2	;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //서버 접속시 timeout 설정
curl_setopt($ch, CURLOPT_TIMEOUT, 10); //서버 접속시 timeout 설정
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


echo "<pre>";
print_r(json_decode($response));
echo "</pre>";
print_r($status);

?>