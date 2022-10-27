<?php
	$key = hex2bin("12345678901234567890123456789077");
	$iv = hex2bin("12345678901234567890123456789011");
	
	function hex2bin($hexdata) {
		$bindata = "";
		for ($i=0;$i < strlen($hexdata);$i+=2) {
			$bindata .= chr(hexdec(substr($hexdata,$i,2)));
		}
		return $bindata;
	}
	
	function Redirect($url)
	{
		header('Location: ' . $url);
		exit();
	}
	
	function encrypt($key, $iv, $value) {
		if (is_null($value)) $value = "";
		$value = toPkcs7($value);
		$output = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv);
		return base64_encode($output);
		
	}
	
	function toPkcs7($value) {
		if (is_null($value)) $value = "" ;
		$padSize = 16 - (strlen($value) % 16);
		return $value . str_repeat(chr($padSize), $padSize);
	
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
	function logger($logType, $name, $dob, $data1, $data2, $flag)
	{
		$querylog = "insert into tb_check_log (check_kind, name, jumin1,  data1, data2, flag, chkdate) values
		('$logType', '$name', '$dob', '$data1', '$data2', '$flag', now())";
			
	
		mysql_query($querylog) or die("Query Error 13".mysql_error());
	}
	
	function bankValidation($mBirth, $accountNum, $bankcode) {

		//------------------------------------------------------------------------------------
		$now=time();
		$now_date_time = date("YmdHi",$now);
		//------------------------------------------------------------------------------------
		// //3 자리 계좌 조회
		$Host = "129.200.9.11"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1
		$Port = "9237"; //Real
		//$Port = "9238"; //Test
		/*
		 공통부분...
		 */
	
		$space = "";
		$inc_code = "UPCHE214";
		$bank_code = "026";
			
		$trans_date = date(Ymd);
		$trans_time = date(His);
		$msg_code = "0800";
		$gubun = "100";
		$no = "000001";
	
		# 하루에 한번 전문을 보내는 것으로 수정 2009-05-26일
		$chk_flag = "N";
	
		$TODAY = date("Y-m-d",strtotime("0 day"));
		$NOWTIME = date("H",strtotime("0 day"));
		$query = "SELECT count(*) as CNT
								FROM tb_check_log
							 WHERE check_kind = 'B'
								 AND chkdate like '".$TODAY."%' ";
		$result = mysql_query($query);
		$rows = @mysql_fetch_array($result);
		$record = $rows[0];
	
		if ($record == 0) {
			$chk_flag = "Y";
		}
	
		if (($NOWTIME == "00") || ($NOWTIME == "23")) {
			$chk_flag = "Y";
		}
		
	
		if ($chk_flag == "Y") {
			$openMsg = sprintf("%9s%8s%2s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%15s%03s%13s%200s",$space,$inc_code,$space,$msg_code,$gubun,1,$no,	$trans_date,$trans_time,$space,$space,$space,$space,$space,$bank_code,$space,$space);
			$ss = global_SendSocketToC($Host, $Port, $openMsg);
		}
		$msg_code = "0600";
		$gubun = "400";
		$trade_date = date(md);
	
		$account = substr($accountNum,0,16);
		$name = $mName;
		$name_euckr = iconv("utf-8","euc-kr", $name);
		

		//$jumin = $reg_jumin1.$reg_jumin2;
		$jumin = $mBirth.$space.$space.$space.$space.$space.$space.$space;
		$searchMsg = sprintf("%9s%8s%2s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%15s%03s%13s%4s%2s%-16s%-22s%-13s%22s%3s%93s%20s%1s%4s", $space, $inc_code, $space, $msg_code, $gubun, 1, $no, $trans_date, $trans_time, $space, $space, $trans_date, $trans_time, $space, $bank_code, $space, $trade_date, $space, $account, $space, $jumin, $space, $bankcode, $space, $space, $space, $space);

		$ss = global_SendSocketToC($Host, $Port, $searchMsg);
		return $ss;
	
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
	function logout()
	{	 
		session_unset();
	}
?>