<?session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<?

#	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	
	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
	}

	function isExist($reg_jumin1, $reg_jumin2)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_userinfo_dup where reg_jumin1='$reg_jumin1' and reg_jumin2='$reg_jumin2'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);


		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}
	
	function APIlogger($MemberNo, $send, $receive)
	{
		$querylog = "insert into tb_api_log (member_no, send_data, return_data, regDate) values
		('$MemberNo', '$send', '$receive', now())";
	
		mysql_query($querylog) or die("Query Error 14".mysql_error());
	
			
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
		$result = printf($result);
		echo "
		<html>\n
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
		<script language=\"javascript\">\n
		alert('$response');
		</script>\n
		</html>";
		 
		if (isset($result["error"]["code"])) {
			$result = "";
		}
	
		curl_close($ch);
	
		return $result;
	
	}
	
	function updateMemberAPI($number,$ename,$name,$homePhone,$mobilePhone,$password,$co_number,$email,$zip,$addr,$addr_detail,$birthday,$sex){
		
	
		$taxId = substr($birthday,2).$name;
		$dob =date("Y-m-d", strtotime($birthday));
		$gender = $gender== 1 ? 'male' : 'female';
		
		$enroller = 'https://hydra.unicity.net/v5a/customers?id.unicity='.$co_number;
	
		
		$data = http_build_query(array(
				'mainAddress' => array(
						'city' => '',
						'state' => '',
						'country' => 'KR',
						'zip' => $zip,
						'address1' => $addr,
						'address2' => $addr_detail
				),
				'humanName' => array(
						'firstName' => $ename,
						'lastName' => ' ',
						'firstName@ko' => $name,
						'lastName@ko' => ' '
				),
	
				
				'type' => 'Associate',
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
	
		$data = urldecode(stripslashes($data));
		
		
		$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$number;
			
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$output = curl_exec($ch);
		
		
		
		$result = json_decode($output, true);
	
		$newMemberId = $result["id"]["unicity"];
		
		APIlogger($newMemberId, $data, $output);
		
		curl_close($ch);
		return $newMemberId;
	}
	
	function updateAccountInfo($mem_account, $account_bank, $search_bank_code, $account_name, $account)
	{
		//$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account; //real
		$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account; //test
	
		$username = 'krWebEnrollment';
		$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);
	
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
		if (($response != false) && ($status == 200)) {
			$response = json_decode($response, true);
	
			if (isset($response['items'][0]['href'])) {
				$url = $response['items'][0]['href'];
	
				curl_setopt($ch, CURLOPT_URL, $url);
				$data = http_build_query(array(
						'depositBankAccount' => array(
								'bankName' => $account_bank,
								'bin' => $search_bank_code,
								'accountHolder' => $account_name,
								'accountNumber' => $account,
								'accountType' => 'SavingsPersonal',
								'routingNumber' => 1,
						)
						,
				));
					
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$response = curl_exec($ch);
					
				$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					
				curl_close($ch);
			}
		}
	}

	
	$mode	= str_quote_smart(trim($mode));
	if ($mode == "add") {

		$name					= str_quote_smart(trim($name));
		$password			= str_quote_smart(trim($password));
		$reg_jumin1		= str_quote_smart(trim($reg_jumin1));
		$reg_jumin2		= str_quote_smart(trim($reg_jumin2));
		$pho1					= str_quote_smart(trim($pho1));
		$pho2					= str_quote_smart(trim($pho2));
		$pho3					= str_quote_smart(trim($pho3));
		$hpho1				= str_quote_smart(trim($hpho1));
		$hpho2				= str_quote_smart(trim($hpho2));
		$hpho3				= str_quote_smart(trim($hpho3));
		$zip					= str_quote_smart(trim($zip));
		$addr					= str_quote_smart(trim($addr));
		$addr_detail	= str_quote_smart(trim($addr02));
		$email				= str_quote_smart(trim($email));
		$email_flag		= str_quote_smart(trim($email_flag));
		$hphone_flag	= str_quote_smart(trim($hphone_flag));
		$account			= str_quote_smart(trim($account));
		$account_bank = str_quote_smart(trim($account_bank));
		$member_kind	= str_quote_smart(trim($member_kind));
		$job					= str_quote_smart(trim($job));
		$interest			= str_quote_smart(trim($interest));
		$birth_y			= str_quote_smart(trim($birth_y));
		$birth_m			= str_quote_smart(trim($birth_m));
		$birth_d			= str_quote_smart(trim($birth_d));
		$sex					= str_quote_smart(trim($sex));

		$reg_jumin1 = encrypt($key, $iv, $reg_jumin1);
		$reg_jumin2 = encrypt($key, $iv, $reg_jumin2);
		$en_account = encrypt($key, $iv, $account);

		if(!isExist($reg_jumin1, $reg_jumin2) == 1) {

			$query = "insert into tb_userinfo_dup (name, password, reg_jumin1, reg_jumin2, pho1, pho2, pho3, hpho1,
			          hpho2, hpho3, zip, addr, addr_detail, email, email_flag, account, account_bank, member_kind, job, 
			          interest, birth_y, birth_m, birth_d, sex, regdate, reg_status, live, ldate, visit_count, hphone_flag, JU_NO01, JU_NO02, en_account) values 
					  ('$name', '$password', '$reg_jumin1', '$reg_jumin2', '$pho1', '$pho2', '$pho3', '$hpho1',
			          	'$hpho2', '$hpho3', '$zip', '$addr', '$addr_detail', '$email', '$email_flag', '$en_account', '$account_bank', '$member_kind', '$job', 
			          	'$interest', '$birth_y', '$birth_m', '$sex', '$birth_d', now(), '1', 'N', now(), 0, '$hphone_flag','$reg_jumin1', '$reg_jumin2','en_account')";

			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "
				<html>\n
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
				<script language=\"javascript\">\n
				alert('등록 되었습니다.');
				parent.frames[3].location = 'user_list.php';
				</script>\n
				</html>";
			exit;

		} else {
			echo "
				<html>\n
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
				<script language=\"javascript\">\n
				alert('이미 등록된 주민 등록 번호 입니다.');
				</script>\n
				</html>";
			exit;
		}

	} else if ($mode == "mod") {
		$name					= str_quote_smart(trim($name));
		$member_no				= str_quote_smart(trim($member_no));
		$ename						= str_quote_smart(trim($ename));
		$number						= str_quote_smart(trim($number));
		$password					= str_quote_smart(trim($password));
		$active_kind			= str_quote_smart(trim($active_kind));
		$couple						= str_quote_smart(trim($couple));
		$couple_name			= str_quote_smart(trim($couple_name));
		$couple_ename			= str_quote_smart(trim($couple_ename));
		$couple_reg_jumin1 = str_quote_smart(trim($couple_reg_jumin1));
		$couple_reg_jumin2 = str_quote_smart(trim($couple_reg_jumin2));
		$pho1							= str_quote_smart(trim($pho1));
		$pho2							= str_quote_smart(trim($pho2));
		$pho3							= str_quote_smart(trim($pho3));
		$hpho1						= str_quote_smart(trim($hpho1));
		$hpho2						= str_quote_smart(trim($hpho2));
		$hpho3						= str_quote_smart(trim($hpho3));
		$zip							= str_quote_smart(trim($zip));
		$addr							= str_quote_smart(trim($addr));
		$addr_detail			= str_quote_smart(trim($addr02));
		$del_zip					= str_quote_smart(trim($del_zip));
		$del_addr					= str_quote_smart(trim($del_addr));
		$del_addr_detail	= str_quote_smart(trim($del_addr02));
		$email						= str_quote_smart(trim($email));
		$email_flag				= str_quote_smart(trim($email_flag));
		$hphone_flag			= str_quote_smart(trim($hphone_flag));
		$account					= str_quote_smart(trim($account));
		$account_bank			= str_quote_smart(trim($account_bank));
		$co_number				= str_quote_smart(trim($co_number));
		$co_name					= str_quote_smart(trim($co_name));
		$member_kind			= str_quote_smart(trim($member_kind));
		$interest					= str_quote_smart(trim($interest));
		$birth_y					= str_quote_smart(trim($birth_y));
		$birth_m					= str_quote_smart(trim($birth_m));
		$birth_d					= str_quote_smart(trim($birth_d));
		$sex							= str_quote_smart(trim($sex));
		$agree_01					= str_quote_smart(trim($agree_01));
		$agree_02					= str_quote_smart(trim($agree_02));
		$agree_03					= str_quote_smart(trim($agree_03));
		$agree_04					= str_quote_smart(trim($agree_04));

		$sel_agree01			= str_quote_smart(trim($sel_agree01));
		$sel_agree02			= str_quote_smart(trim($sel_agree02));
		$sel_agree03			= str_quote_smart(trim($sel_agree03));
		$sel_agree04			= str_quote_smart(trim($sel_agree04));
		$sel_agree05 			= str_quote_smart(trim($sel_agree05));

		$reg_status				= str_quote_smart(trim($reg_status));

		$couple_reg_jumin1 = encrypt($key, $iv, $couple_reg_jumin1);
		$couple_reg_jumin2 = encrypt($key, $iv, $couple_reg_jumin2);
		$en_account				 = encrypt($key, $iv, $account);
		
		$homePhone = $pho1.$pho2.$pho3;
		$mobilePhone = $hpho1.$hpho2.$hpho3;
		$birthday = $birth_y.$birth_m.$birth_d;
		
		$namespace = "https://hydra.unicity.net/v5a/customers";
		$tokenurl =  "https://hydra.unicity.net/v5a/loginTokens";
		$credential = base64_encode($number.':'.$password);
		$authData = array (
				'type' => 'base64',
				'value' => $credential,
				'namespace' => $namespace
		);
		
		
		$tokenCh = curl_init();
		curl_setopt($tokenCh, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($tokenCh, CURLOPT_URL, $tokenurl);
		curl_setopt($tokenCh, CURLOPT_POST, count($authData));
		curl_setopt($tokenCh, CURLOPT_POSTFIELDS, $authData_string);
		curl_setopt($tokenCh, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($tokenCh, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($tokenCh, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($tokenCh, CURLOPT_HEADER, false);
		curl_setopt($tokenCh, CURLOPT_NOBODY, false);
		$tokenResponse = curl_exec($tokenCh);
		echo "
			<html>\n
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
			alert('$number');
			alert('$password');
			alert('$tokenResponse');
			</script>\n
			</html>";
		$test = json_decode($tokenResponse);
		$setvalue = $test->{'token'};
		curl_close($tokenCh);
		
		$newMemberId = updateMemberAPI($number,$ename,$name,$homePhone,$mobilePhone,$password,$co_number,$email,$zip,$addr,$addr_detail,$birthday,$sex);

		
		$query = "update tb_userinfo_dup set 
						number = '$number',
						ename = '$ename',
						password = '$password',
						active_kind = '$active_kind',
						couple = '$couple',
						couple_name = '$couple_name',
						couple_ename = '$couple_ename',
						couple_reg_jumin1 = '$couple_reg_jumin1',
						couple_reg_jumin2 = '$couple_reg_jumin2',
						hpho1 = '$hpho1',
						hpho2 = '$hpho2',
						hpho3 = '$hpho3',
						pho1 = '$pho1',
						pho2 = '$pho2',
						pho3 = '$pho3',
						zip = '$zip',
						addr = '$addr',
						addr_detail = '$addr_detail',
						del_zip = '$del_zip',
						del_addr = '$del_addr',
						del_addr_detail = '$del_addr_detail',
						email = '$email',
						email_flag = '$email_flag',
						hphone_flag = '$hphone_flag',
						account = '$en_account',
						account_bank = '$account_bank',
						co_name = '$co_name',
						co_number = '$co_number',
						birth_y = '$birth_y',
						birth_m = '$birth_m',
						birth_d = '$birth_d',
						sex = '$sex',
						member_kind = '$member_kind',
						interest = '$interest',
						moddate = now(),
						reg_status = '$reg_status',
						agree_01 = '$agree_01',
						agree_02 = '$agree_02',
						agree_03 = '$agree_03',
						agree_04 = '$agree_04',
						sel_agree01 = '$sel_agree01',
						sel_agree02 = '$sel_agree02',
						sel_agree03 = '$sel_agree03',
						sel_agree04 = '$sel_agree04',
						sel_agree05 = '$sel_agree05',
						en_account = '$en_account'
						where member_no = '$member_no'";
		
		//echo $query;
		//exit;

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		
		echo "
			<html>\n
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
			alert('수정 되었습니다.,');
			</script>\n
			</html>";
		exit;

	} else if ($mode == "del") {

		$member_no				= str_quote_smart(trim($member_no));


		$query = "insert into tb_userinfo_dup_del_log (member_no, number, name, ename, reg_jumin1, reg_jumin2, 
										 pho1, pho2, pho3, hpho1, hpho2, hpho3, zip, addr, addr_detail, del_zip, del_addr,
										 del_addr_detail, email, email_flag, account, account_bank, co_number, co_name, regdate)  
							select member_no, number, name, ename, reg_jumin1, reg_jumin2, 
										 pho1, pho2, pho3, hpho1, hpho2, hpho3, zip, addr, addr_detail, del_zip, del_addr,
										 del_addr_detail, email, email_flag, account, account_bank, co_number, co_name, regdate
							 from tb_userinfo_dup where member_no = '$member_no' ";
		
		//echo $query;

		mysql_query($query) or die("Query Error1");

		$query = "insert into tb_userinfo_dup_del_log_admin (member_no, del_date, del_admin) values ('$member_no', now(), '$s_adm_id') ";
		//echo $query;
		mysql_query($query) or die("Query Error2");

		$query = "delete from tb_userinfo_dup 
				    where member_no = '$member_no'";

		#echo $query;
		mysql_query($query) or die("Query Error3");

		mysql_close($connect);

		echo "
			<html>\n
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'dup_user_list.php';
			</script>\n
			</html>";
		exit;

	}
?>