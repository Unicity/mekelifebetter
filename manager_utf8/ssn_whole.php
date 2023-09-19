#!/usr/bin/php
<?php 
error_reporting(1);
ini_set('memory_limit',-1);
ini_set('max_execution_time', 120);
ini_set('display_errors', 1);

//log
$logFileName = "/home/httpd/unicity/manager_utf8/unclaimedCommission/data/log.txt";
$openedFile = fopen($logFileName, 'a+');
$fw = fwrite($openedFile, date("Y-m-d H:i:s")." : ssn start\r\n");
fclose($openedFile);

function hex2bin2($hexdata) {
	$bindata = "";
	for ($i=0;$i < strlen($hexdata);$i+=2) {
		$bindata .= chr(hexdec(substr($hexdata,$i,2)));
	}
	return $bindata;
}

function toPkcs7($value) {
	if (is_null($value)) $value = "" ;
	$padSize = 16 - (strlen($value) % 16);
	return $value . str_repeat(chr($padSize), $padSize);

}

function fromPkcs7($value) {

	$valueLen = strlen($value);  
	if ($valueLen % 16 > 0) $value = "";
	$padSize = ord($value{$valueLen - 1});
	if ( ($padSize < 1) or ($padSize > 16) ) $value = "";
	// Check padding.
	for ($i = 0;$i < $padSize;$i++) {
		if (ord($value{$valueLen - $i - 1}) != $padSize) $value = "";
	}
	return substr($value, 0, $valueLen - $padSize);
}

function encrypt($key, $iv, $value) {
	if (is_null($value)) $value = "";
	$value = toPkcs7($value);  
	$output = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv);
	return base64_encode($output);
}

function decrypt($key, $iv, $value) {
	if (is_null($value)) $value = "";
	$value = base64_decode($value);
	$output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv);
	return fromPkcs7($output);
}


//db 연결 192.168.20.39, 10.107.133.149
$db = new mysqli('10.151.80.39', 'unicity_db_user', 'unicity!2011', 'makelifebetter');
if ($db->connect_errno){
	die($this->link->connect_error);
}
$db->set_charset('utf8');


//암호화 키 가져오기
$result = $db->query("select * from tb_key");
$obj = $result->fetch_array();
$enc_key = hex2bin2($obj['key']);
$enc_iv = hex2bin2($obj['iv']);

require_once '/home/httpd/unicity/manager_utf8/lib/xlsxwriter.class.php';


//$str_title = iconv("UTF-8","EUC-KR","세금신고용 주민번호 리스트");
$str_title = 'ssn';
$filename = $str_title."_".date("Y-m-d").".xlsx";


$result = $db->query("select * from tb_distSSN where 1=1 order by create_date desc");

$header = array(
	'회원번호'=>'string',
	'주민등록번호'=>'string',
	'등록일'=>'string'
);


$writer = new XLSXWriter();
$writer->writeSheetHeader('Sheet1', $header);

$array = array();
while($row = $result->fetch_assoc()) {
	$array[1] = $row['dist_id'];
	$array[2] = ($row['government_id'] != "") ? decrypt($enc_key, $enc_iv, $row['government_id']) : "";
	$array[3] = substr($row['create_date'], 0, 10);

	$writer->writeSheetRow('Sheet1', $array);
}
$writer->writeToStdOut();
$writer->writeToFile('/home/httpd/unicity/manager_utf8/unclaimedCommission/data/'.$filename);

$db->close();


//end log
$openedFile = fopen($logFileName, 'a+');
$fw = fwrite($openedFile, date("Y-m-d H:i:s")." : ssn end\r\n");
fclose($openedFile);

exit(0);
?>