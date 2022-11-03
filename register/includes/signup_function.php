<?php
//error_reporting(0);

	function CodeByName ($parent, $code) {

		$b = 0;
		
		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' and code = '".$code."'"; 

		$result = mysql_query($sqlstr);
		$list = @mysql_fetch_array($result);

		$b = $list[name];
		
		return $b;

	}

	function isExist($name, $year, $month, $date) {
		
		$b = 0;

		//if (isExistAPI($name, $year, $month, $date) > 0) // Check Duplication from main Distributor fields
		
		$result1 = isExistAPI($name, $year, $month, $date);		
		if($result1 == 'F'){
			$b = 'F';
			return $b;
		}else if($result1 > 0){
			$b = 1;
			return $b;
		}

		if($b == 0){		
			
			//if (isPartnerExistAPI($name, $year, $month, $date) > 0) // Check Duplication from partner Distributor fields

			$result2 = isPartnerExistAPI($name, $year, $month, $date);
			if($result2 == 'F'){
				$b = 'F';
				return $b;
			}else if($result2 > 0){
				$b = 1;
				return $b;
			}
		}
		if($b == 0){
			if (isExistUserInfo($name, $year, $month, $date) > 0) // Check Duplication from the local DB(userinfo)
			{
				$b = 1;
				return $b;
			}
		}
		if($b == 0){
			if (isExistDupUserInfo($name, $year, $month, $date) > 0) // Check Duplication from the local DB(userinfo_dup)
			{
				$b = 1;
				return $b;
			}
		}
		return $b;
	}

	function logger($logType, $name, $dob, $data1, $data2, $flag)
	{
		$querylog = "insert into tb_check_log (check_kind, name, jumin1,  data1, data2, flag, chkdate) values
						('$logType', '$name', '$dob', '$data1', '$data2', '$flag', now())";
		 
		
		@mysql_query($querylog);
		
		
	}

	function APIlogger($MemberNo, $send, $receive)
	{
		$querylog = "insert into tb_api_log (member_no, send_data, return_data, regDate) values
						('$MemberNo', '$send', '$receive', now())";
		
		@mysql_query($querylog);
	}

	function remove_special_str($value) {
		$value = str_replace("&quot;", "\"",$value);
		$value = str_replace("&#039;", "'",$value);
		$value = str_replace("&#037;", "%",$value);
		$value = str_replace("&gt;", ">",$value);
		$value = str_replace("&lt;", "<",$value);
		$value = str_replace("%7b","", $value);
		$value = str_replace("%7d","", $value);
		$value = str_replace("{","", $value);
		$value = str_replace("}","", $value);
		$value = str_replace("&#40;", "(",$value);
		$value = str_replace("&#41;", "}",$value);
		$value = str_replace("ᆞ", "",$value);
	
		$string = preg_replace("/[#\&\+\%=\/\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $value); 
	
		return $string;
	}


	//api방식 계좌인증
	function bankValidationApi($accountNo, $bankcode) {
		/*
		요청URL	rfb/account/accountname    통신방식	POST						
		Input Parameters	Size	필수	Description				note
		date				8		O	요청날짜				
		time				6		O	요청시간				
		compCode       		8		O	은행에서 부여한 업체별 코드				
		bankCode       		3		O	은행코드(계약은행)				
		seqNo    			6		O	일련번호				
		agencyYn			1			FCS대행 사용여부				
		accountBankCode 	3		O	조회계좌 은행코드				
		accountNo			15		O	계좌번호				
		socialId 			13		실명번호(생년월일,사업자번호등)	*실명번호 일치여부는 당행만 가능
		amount   			13		금액				"가상계좌 조회시 사용 (외환, 농협, 우리, 씨티)"
		
		Output Parameters	Size	Description		note
		replyCode			4		응답코드			정상 : 0000
		accountName			20		예금주명				
		successYn			1						Y: 성공, N : 실패, W : 타임아웃
							
		운영 : https://cmsapi.ksnet.co.kr/ksnet/rfb/account/accountname
		개발 : https://cmsapitest.ksnet.co.kr/ksnet/rfb/account/accountname

		test계좌정보 <- 테스트시 계좌정보 에러남
		국민(004) 205707093213 가권찬
		신한(088) 110220505420 마단박

		ekey : A91D2B6121AA07C748B9CA4323963E69
		msalt : MA01
		kscode : 1372
		seqNo(6자리) : 일일초기화, 향후 조회내역 조회시 필요하나 중복된 값을 전송해도 처리에는 문제 없슴 -> date('His')발송처리
		*/		
		global 	$user_device;
		
		$ekey = "A91D2B6121AA07C748B9CA4323963E69";
		$msalt = "MA01";
		$kscode = "1372";
		
		$sendDate = date("Ymd");
		$sendTime = date("Hid");
		$companyCode = "UPCHE214";
		$companyBankCode = "026";


		//개시전문전송 - 일1회
		$result = mysql_query("select count(*) as cnt from tb_log_v2_start where date = '".date("Y-m-d")."' and flag = 'Y'");	
		$row = mysql_fetch_array($result);

		if($row['cnt'] < 1){
			$sendData = 'JSONData={
					"kscode":"'.$kscode.'","ekey":"'.$ekey.'","msalt":"'.$msalt.'",
					"reqdata":
					[
						{
							"date":"'.$sendDate.'",
							"time":"'.$sendTime.'",
							"seqNo":"'.$sendTime.'",
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

			//print_r($resultJson);

			$flag = ($resultJson->replyCode == '0000') ? 'Y' : 'N';

			//결과등록
			$sql = "insert into tb_log_v2_start (date, sendData, recieveData, flag, logdate) values ('".date("Y-m-d")."','".$sendData."','".$reponse."','".$flag."',now())";
			@mysql_query($sql);
		}
		
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

		//계좌인증전송
		$qlog = "insert into tb_log_v2 (tmpId, gubun, check_kind, name, data1, sendData, flag, device, logdate) values 
		( '".$_SESSION['ssid']."', '계좌인증API전송', 'bank', '".$_SESSION['S_NM']."', '".$sendTime."', '$sendData', 'N', '$user_device', now())";	
		@mysql_query($qlog);
		$log_id  = mysql_insert_id();
		
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

		$yn = ($resultJson->accountName != "" && $resultJson->replyCode = "0000") ? "Y" : "N";

		//계좌인증결과 업데이트
		$qlog = "update tb_log_v2 set 
						gubun = '계좌인증API조회결과-".$yn."',
						data2 = '".$resultJson->accountName."',
						recieveData = '".$reponse."',
						msg = '".$resultJson->replyCode."' ,
						flag = '".$yn."'
					where uid = '".$log_id."'";
		mysql_query($qlog);

		return $reponse;
	}

	
	//전용선방식 계좌인증	- API가 실패일 경우 전송
	function bankValidation($dob, $accountNo, $bankcode) {

		global 	$user_device;
		
		//------------------------------------------------------------------------------------
		$now=time(); 
		$now_date_time = date("YmdHi",$now); 
		//------------------------------------------------------------------------------------
		// //3 자리 계좌 조회
		$Host = "129.200.9.11"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1
		$Port = "9237"; //Real
		//$Port = "9238"; //Test

		$space = "";
		$inc_code = "UPCHE214";
		$bank_code = "026";
		 
		$trans_date = date(Ymd);
		$trans_time = date(His);
		$msg_code = "0800";
		$gubun = "100";
		$no = "000001";
		
		# 하루에 한번 전문을 보내는 것으로 수정 2009-05-26일 -> API실패일 경우 무조건 전문 재전송 처리
		$TODAY = date("Y-m-d",strtotime("0 day"));
		$NOWTIME = date("H",strtotime("0 day"));
		$openMsg = sprintf("%9s%8s%2s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%15s%03s%13s%200s",$space,$inc_code,$space,$msg_code,$gubun,1,$no,	$trans_date,$trans_time,$space,$space,$space,$space,$space,$bank_code,$space,$space);
		$ss = global_SendSocketToC($Host, $Port, $openMsg);
		#전문 전송 끝

		$msg_code = "0600";
		$gubun = "400";
		$trade_date = date(md);
	
		$account = substr($accountNo,0,16);
		$name = $name;
		$name_euckr = iconv("utf-8","euc-kr", $name);
		//$jumin = $reg_jumin1.$reg_jumin2;
		$jumin = $dob.$space.$space.$space.$space.$space.$space.$space;
		$searchMsg = sprintf("%9s%8s%2s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%15s%03s%13s%4s%2s%-16s%-22s%-13s%22s%3s%93s%20s%1s%4s", $space, $inc_code, $space, $msg_code, $gubun, 1, $no, $trans_date, $trans_time, $space, $space, $trans_date, $trans_time, $space, $bank_code, $space, $trade_date, $space, $account, $space, $jumin, $space, $bankcode, $space, $space, $space, $space);	

		//계좌인증재전송
		$sendTime = date("Hid");
		$qlog = "insert into tb_log_v2 (tmpId, gubun, check_kind, name, data1, sendData, flag, device, logdate) values 
		( '".$_SESSION['ssid']."', '계좌인증재전송(전용선)', 'bank', '".$_SESSION['S_NM']."', '".$sendTime."', '$searchMsg', 'N', '$user_device', now())";	
		@mysql_query($qlog);
		$log_id  = mysql_insert_id();


		$ss = global_SendSocketToC($Host, $Port, $searchMsg);

		//계좌재전송결과 업데이트
		$yn = (substr($$ss[msg],51,4) == '0000') ? 'Y' : 'N';
		$qlog = "update tb_log_v2 set 
						gubun = '계좌인증재전송(전용선)조회결과-".$yn."',						
						recieveData = '".$ss[msg]."',
						msg = '".substr($ss[msg],51,4)."' ,
						flag = '".$yn."'
					where uid = '".$log_id."'";
		@mysql_query($qlog);

		echo "msg :" .substr($ss[msg],51,4); 
 	 	
 	 	return $ss;
	}
	

	function insertMemeber($tableName, $password, $name, $ename, $reg_jumin1, $reg_jumin2, $active_kind, $phoneNo1, $phoneNo2, $phoneNo3, $mobileNo1, $mobileNo2, $mobileNo3, $zip, $addr1, $addr2, $email, $accountNo, $bankname, $sponsorNo, $sponsorName, $memberType, $birthYear, $birthMonth, $birthDate, $gender, $bankcode, $agree_01, $agree_02, $agree_03, $agree_04, $agree_05, $selchk1, $selchk2, $selchk3, $selchk4, $selchk5, $selchk6, $user_device,$k_id = '')
	{
		$query =  "insert into $tableName 
		(name, ename, password, reg_jumin1, reg_jumin2, active_kind, couple, pho1, pho2, pho3, hpho1,	hpho2, hpho3, zip, addr, addr_detail, del_zip, del_addr, del_addr_detail , email,  account, account_bank, co_number, co_name, regdate,  member_kind, birth_y, birth_m, birth_d, sex, reg_status, live, ldate, visit_count, DI, bank_code, JU_NO01, JU_NO02, en_account, agree_01, agree_02, agree_03, agree_04, agree_05, sel_agree01, sel_agree02, sel_agree03, sel_agree04, sel_agree05, sel_agree06, REF, auth_type, k_id) 
		values ('$name', '$ename', '$password', '$reg_jumin1','$reg_jumin2', '$active_kind', 'N', '$phoneNo1', '$phoneNo2', '$phoneNo3', '$mobileNo1', '$mobileNo2', '$mobileNo3', '$zip', '$addr1', '$addr2',  '$zip', '$addr1', '$addr2', '$email', '$accountNo', '$bankname', '$sponsorNo', '$sponsorName',  now(), '$memberType', '$birthYear', '$birthMonth', '$birthDate', '$gender', 2, 'N', now(), 0, '".$_SESSION["S_DI"]."', '$bankcode', '$reg_jumin1', '$reg_jumin2', '$accountNo', '$agree_01', '$agree_02', '$agree_03', '$agree_04', '$agree_05', '$selchk1', '$selchk2', '$selchk3', '$selchk4', '$selchk5', '$selchk6', '$user_device', '$_SESSION[S_AUTH_TYPE]','$k_id')  ";
			
		mysql_query($query) or die("Query Error 10".mysql_error());
		$new_member_no = mysql_insert_id();
		return $new_member_no;
	}
	
	function updateColumn($tableName, $columnName, $value, $condition)
	{
		$query = "Update $tableName set $columnName = '$value' where $condition";

		$result_update = mysql_query($query);

		return $result_update;
	}
	function isExistUserInfo($name, $year, $month, $date) {
		global 	$user_device;
		$query = "SELECT count(*) FROM makelifebetter.tb_userinfo WHERE name='$name' AND birth_y='$year' and birth_m='$month' and birth_d='$date' ";
		$result = mysql_query($query);
		$rows = @mysql_fetch_array($result);
		$record = $rows[0];

		 //로그v2저장
		$already = ($record > 0) ? "중복" : "신규";			
		$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'userinfo조회-".$already."', '$name', '".$year.$month.$date."', '".addslashes($query)."', '$record', 'Y', '$user_device', now())";	
		mysql_query($qlog) or die("isExistUserInfo-".mysql_error());

		return $record;

	}
	function isExistDupUserInfo($name, $year, $month, $date) {
		global 	$user_device;
		$query = "SELECT count(*) FROM makelifebetter.tb_userinfo_dup WHERE name='$name' AND birth_y='$year' and birth_m='$month' and birth_d='$date' ";
		$result = mysql_query($query);
		$rows = @mysql_fetch_array($result);
		$record = $rows[0];

		//로그v2저장
		$already = ($record > 0) ? "중복" : "신규";			
		$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'userinfo_dup조회-$already', '$name', '".$year.$month.$date."', '".addslashes($query)."', '$record', 'Y', '$user_device', now())";	
		mysql_query($qlog) or die("isExistDupUserInfo-".mysql_error());
		return $record;
	}

	function isExistAPI($name, $year, $month, $date) {
		global 	$user_device;

		$name = mb_substr($name,0,mb_strlen($name),'UTF-8');
		$sendData = $year.$month.$date.urlencode($name);	
		$url = 'https://hydra.unicity.net/v5a/customers.js?_httpMethod=HEAD&taxTerms_taxId='.$sendData.'&mainAddress_country=KR';	

		$return = 2	;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);			 
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close($ch);
		
		echo "isExistAPI<Br>";

		//if ($response !== false )  {
		if (($response !== false) && ($status == 200) )  {

			$result = json_decode($response, true);

			if(isset($result["data"]["error"]["code"]) && ($result["data"]["error"]["code"] == "404" || $result["data"]["error"]["code"] == 404))
			{
				$return = 0	;				
			} else {
				$return = 1	;
			}
			
			//로그v2저장
			$already = ($return == 0) ? "신규" : "중복";			
			$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, msg, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'isExistAPI-성공($already)', '$name', '".$year.$month.$date."', '$url', '$response', '$status', 'Y', '$user_device', now())";	
		} else {
			$return = 'F';

			//로그v2저장
			$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, msg, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'isExistAPI-실패', '$name', '".$year.$month.$date."', '$url', '회신값없슴-".$response."', '$status', 'N', '$user_device', now())";	
		}
		mysql_query($qlog) or die("isExistAPI-".mysql_error());

		return $return;
	}

	function isPartnerExistAPI($name, $year, $month, $date) {
		global 	$user_device;
		$name = urldecode($name);
		$url = 'https://hydra.unicity.net/v5a/customers.js?_httpMethod=HEAD&mainAddress_country=KR&spouse_taxTerms_taxId='.urlencode($year.$month.$date.$name);
		$sendData = $year.$month.$date.urlencode(urldecode($name));	
		$return = 2	;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
		//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		echo "isPartnerExistAPI<Br>";

		//if (($response !== false) && ($status == 200)) {
		if ($status == 200)  {	
			$result = json_decode($response, true);			
			 
			 if(isset($result["data"]["error"]["code"]) && ($result["data"]["error"]["code"] == "404" || $result["data"]["error"]["code"] == 404))
			 {	
			 	$return = 0	;
			 } else {				
			 	$return = 1	;
			 }
			 
			 //로그v2저장
			$already = ($return == 0) ? "신규" : "중복";			
			$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, msg, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'isPartnerExistAPI-성공($already)', '$name', '".$year.$month.$date."', '$url',  '$response', '$status', 'Y', '$user_device', now())";	
			mysql_query($qlog) or die(mysql_error());

		}else{

			$return = 'F';

			 //로그v2저장
			$qlog = "insert into tb_log_v2 (tmpId, gubun, name, jumin1, sendData, recieveData, msg, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'isPartnerExistAPI-실패', '$name', '".$year.$month.$date."', '$url', '$response', '$status', 'N', '$user_device', now())";	
			mysql_query($qlog) or die(mysql_error());
			
		}
		  
		return $return;
	}

	function createMemberAPI($city, $state, $zip, $address1, $address2, $name, $englishName, $type, $gender, $password, $enroller, $dob, $email, $mobilePhone, $homePhone)
	{
		global $user_device;

		$krFirstName = "";
		$krLastName = "";
		if (getLastnameChecker($name))
		{  
			$krLastName = mb_substr($name,0,2,'UTF-8');
			$krFirstName = mb_substr($name,2,mb_strlen($name),'UTF-8');
		} else {
			$krLastName = mb_substr($name,0,1,'UTF-8');
			$krFirstName = mb_substr($name,1,mb_strlen($name),'UTF-8');
		}
		 
		$lastName  = substr($englishName, 0, strpos($englishName, " "));
		$firstName = substr($englishName, strlen($lastName)+1);
	 
		$taxId = substr($dob,2).$name;
		$dob = date("Y-m-d", strtotime($dob));

		 //로그v2저장
		$qlog = "insert into tb_log_v2 (tmpId, memid, gubun, name, jumin1, sendData, recieveData, msg, flag, device, logdate) values ( '".$_SESSION['ssid']."', '', 'createMemberAPI_Start', '$name', '".str_replace("-","",$dob)."', '', '', '', '', '$user_device', now())";	
		mysql_query($qlog) or die("createMemberAPI_Start:".mysql_error());

		if($enroller != "") $enroller = trim($enroller);

		$enroller = 'https://hydra.unicity.net/v5a/customers?id.unicity='.$enroller; //real
		//$enroller = 'https://hydra.unicity.net/v5-test/customers?id.unicity='.$enroller; //test
	 	$type = $type == 'D' ? 'Associate' : 'WholesaleCustomer';
	 	$gender = $gender%2 == 1 ? 'male' : 'female';
	 	 
		$data = http_build_query(array(
					'mainAddress' => array(
						'city' => $city,
						'country' => 'KR',
						'state' => $state,
						'zip' => $zip,
						'address1' => $address1,
						'address2' => $address2
						),
						'humanName' => array(
							'firstName' => $englishName,
							'lastName' => ' ',
							'firstName@ko' => $name,
							'lastName@ko' => ' '
						),
						'type' => $type,
						'status' => 'Active',
						'gender' => $gender,
						'password' => array('value' => $password),
						'enroller' => array('href' => $enroller),
						'birthDate' => $dob,
						'email' => $email,
						'mobilePhone' => $mobilePhone,
						'homePhone' => $homePhone,
						'taxTerms' => array('taxId' => $taxId)					 
				));
		 
		$url = 'https://hydra.unicity.net/v5a/customers'; // REAL
	//	$url = 'https://hydraqa.unicity.net/v5-test/customers'; // TEST
	//	$username = 'krWebEnrollment';
	//	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';
	 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		
		$output = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

	 	$result = json_decode($output, true);
		
		$newMemberId = $result["id"]["unicity"];

		$yn = ($newMemberId != "") ? "Y" : "N";
		
		if($newMemberId != ''){
			//회원번호 업데이트 
			$qlog = "update tb_log_v2 set memid = '".$newMemberId."' where tmpId = '".$_SESSION['ssid']."'";	
			@mysql_query($qlog);
		
		} else {
			if($result["error"]["error_code"] == "2102"){  //"error_message":"Duplicate government ID. Error #XXXX"  
				$newMemberId = "duplicate";
			}
		}

		 //로그v2저장
		$qlog = "insert into tb_log_v2 (tmpId, memid, gubun, name, jumin1, sendData, recieveData, msg, flag, device, logdate) values ( '".$_SESSION['ssid']."', '$newMemberId', 'createMemberAPI - $newMemberId', '$name', '".str_replace("-","",$dob)."', '".addslashes($data)."', '$output', '$status', '$yn', '$user_device', now())";	
		@mysql_query($qlog);

	
		return $newMemberId;
	}

	function updateAccountInfo($mem_account, $account_bank, $search_bank_code, $account_name, $account, $name="", $birth="")
	{
		global 	$user_device;
		$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account.'&expand=customer';

		$username = 'krWebEnrollment';
		$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);			 
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$yn = "N";
			 
		if (($response != false) && ($status == 200)) {
			
			//$response = json_decode($response, true);
			$data = json_decode($response);
			 
			//if (isset($response['items'][0]['href'])) {
				//$url = $response['items'][0]['href'];
			if($data->items[0]->href != "") {
				$url2 = $data->items[0]->href;					 
				
				$postdata = http_build_query(array(
					'depositBankAccount' => array(
						'bankName' => $account_bank,
						'bin' => $search_bank_code,
						'accountHolder' => $account_name,
						'accountNumber' => $account,
						'accountType' => 'SavingsPersonal',
						'routingNumber' => 1
					)
				));

				curl_setopt($ch, CURLOPT_URL, $url2);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				$response2 = curl_exec($ch);
				$status2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				$yn = "Y";
			}
		}

		//로그v2저장		
		$qlog = "insert into tb_log_v2 (tmpId, gubun, memid, name, jumin1, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', 'updateAccountInfo', '$mem_account', '$name', '$birth', '$postdata', '$response2', '$yn', '$user_device', now())";	
		@mysql_query($qlog);

	}
	
	function updateDownlineFlagAll($name, $mem_account, $rightsArray){

		global 	$user_device;
		foreach($rightsArray as $key=>$val) {

			$title = $val[0];
			$holder = $val[1];
			$type = $val[2];
			$agree = $val[3];
		
			$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$mem_account;
			$username = 'krWebEnrollment';
			$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
			curl_setopt($ch, CURLOPT_TIMEOUT, 60); //curl 실행에 대한 timeout 
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			$response = curl_exec($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if (($response !== false) && ($status == 200)) {
				
				$response = json_decode($response, true);
				 
				if (isset($response['items'][0]['href'])) {	
					$url = $response['items'][0]['href'].'/rights';

					if(strtoupper($agree) == 'Y') {
						curl_setopt($ch, CURLOPT_URL, $url);		
						$data = http_build_query(array(
							'holder' => $holder,
							'type' => $type
						));			
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);		
					} else {						
						$url .= '?type='.$type.'&holder='.$holder;								
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
						
					}
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
					curl_setopt($ch, CURLOPT_TIMEOUT, 60); //curl 실행에 대한 timeout 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$response = curl_exec($ch);
					$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

					if($status == "201" || $status == "204"){  //delete인경우 204
						$success = 'Y';
					}else{
						$success = 'N';
					}
					//로그v2저장		
					$qlog = "insert into tb_log_v2 (tmpId, memid, gubun, name, sendData, recieveData, flag, device, logdate) values ( '".$_SESSION['ssid']."', '$mem_account', '동의API-".$title."', '$name', '".$holder.":".$type.":".$agree."', '$response', '$success', '$user_device', now())";	
					@mysql_query($qlog);
				}
			}
			curl_close($ch);
		}
	}

	function getLastnameChecker($name) {
		$lastnames = array("남궁", "황보", "제갈", "선우", "사공", "서문", "독고");
		$isLongLastname = false;
		foreach($lastnames as $lastname) {
			if ($lastname == substr($name,0,2))
			{
				$isLongLastname =true;
			}
		}

		return $isLongLastname;
	}
	
	function sendSMS($int_member_no, $memberNo, $memberType, $mobileNo) {
		
		global 	$user_device;
		$result_update = "";
		
		if (!isset($mobileNo))
		{
			$query = "select number, member_kind, hpho1, hpho2, hpho3 from tb_userinfo where member_no = '".trim($int_member_no)."'";

			$result = mysql_query($query);
			$list = mysql_fetch_array($result);

			$memberNo = $list[number];
			$memberType = $list[member_kind];
			$mobileNo = $list[hpho1].$list[hpho2].$list[hpho3];

		} else {
		 	 
				$str_message = "유니시티코리아 회원가입이 완료되었습니다 회원번호 \"".$memberNo."\"입니다 감사합니다";
				$query = "Insert into sms_msg (compkey, id, phone, callback, status, wrtdate, reqdate, msg, etc1, etc2, etc3) values ('$int_member_no','tb_userinfo','$mobileNo', '0424821860', '0', sysdate(), sysdate(), '$str_message','WEB','회원가입','$memberType') ";
				$result_update = mysql_query($query);
		} 
		return $result_update;
	}
	function  sendMail() {

	}
	function MsgAndLinkTo($msg, $url) {
		echo "<script language=\"javascript\">\n
					alert('$msg');
					location.href = '$url'; </script>";
					
	}

	function LinkTo($url) {
		echo "<script language=\"javascript\">\n
					location.href = '$url'; </script>";
					
	}

	function MsgAndGoBack($msg) {
		echo "<script language=\"javascript\">\n
					alert('$msg');
					window.history.back();
					</script>";
	}
 
 	function DisplayAlert($msg) {
		echo "<script language=\"javascript\">\n
			  alert('$msg');
			 </script>";
	}

	function kakoUpdate(){
		
	}

?>