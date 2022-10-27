<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$mode				= str_quote_smart(trim($mode));
	$member_no	= str_quote_smart(trim($member_no));
		
	$query = "select * from tb_userinfo_dup where member_no = $member_no";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$member_no = $list[member_no];
	$number = $list[number];
	$password = $list[password];
	$name = $list[name];
	$email = $list[email];
	$reg_jumin1 = $list[reg_jumin1];
	$reg_jumin2 = $list[reg_jumin2];
	$tax_number = $list[tax_number];
	$hpho1 = $list[hpho1];
	$hpho2 = $list[hpho2];
	$hpho3 = $list[hpho3];
	$reg_status = $list[reg_status];
	$email_date = $list[email_date];
	$email_ma = $list[email_ma];
	$member_kind = $list[member_kind];

	$co_number = $list[co_number];
	$ename = $list[ename];
	$addr = $list[addr];
	$addr02 = $list[addr_detail];
	$zip = $list[zip];
	$pho1 = $list[pho1];
	$pho2 = $list[pho2];
	$pho3 = $list[pho3];
	$account = $list[account];
	$account_bank = $list[account_bank];
	$search_bank_code = $list[bank_code];

	$birth_y	= $list[birth_y];
	$birth_m	= $list[birth_m];
	$birth_d	= $list[birth_d];

	$sex			= $list[sex];

	$this_yyyy = date("Y",strtotime("-10 year"));

	$arr_addr = explode(" ",$addr);

	$state	= $arr_addr[0];
	$city		= $arr_addr[1];

	if ($birth_y > substr($this_yyyy,2)) {
		$dob = "19".$birth_y."-".$birth_m."-".$birth_d;
	} else {
		$dob = "20".$birth_y."-".$birth_m."-".$birth_d;
	}

	$JU_NO01 = decrypt($key, $iv, $reg_jumin1);
	$JU_NO02 = decrypt($key, $iv, $reg_jumin2);
	
	$password = decrypt($key, $iv, $password);
	$account = decrypt($key, $iv, $account);

	$mobilePhone						= $hpho1.$hpho2.$hpho3;
	$homePhone							= $pho1.$pho2.$pho3;

	if ($mode == "I") {
		$query = "update tb_userinfo_dup set reg_status = '2' where member_no = $member_no";
		$result = mysql_query($query);
		$result_str = "신청 처리";
	}

	if ($mode == "NEW") {

		$taxId	= $JU_NO01.$name;
		$dob		= $dob;

		$enroller = 'https://hydra.unicity.net/v5a/customers?id.unicity='.$co_number; //real
	 	$type = $member_kind == 'D' ? 'Associate' : 'WholesaleCustomer';
	 	$gender = $sex%2 == 1 ? 'male' : 'female';
	 	$name = str_replace(' ', '',$name);

		$data = http_build_query(array(
					'mainAddress' => array(
						'city' => $city,
						'country' => 'KR',
						'state' => $state,
						'zip' => $zip,
						'address1' => $addr,
						'address2' => $addr02
						),
						'humanName' => array(
							'firstName' => $ename,
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

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); //curl 실행에 대한 timeout 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$output = curl_exec($ch);
		
		$info = curl_getinfo($ch);
	
	  //echo "output : " .$output."<br>";

	 	$result = json_decode($output, true);
		
		$mem_account = $result["id"]["unicity"];
		
		//echo $result["error"]["code"];

		curl_close($ch);


/*
		$str_member_kind = "D";
		$member_kind = (($str_member_kind) == 'D' ? 'A' : 'H'); //If "D" then set as Distributor in status I if not a distributor, then set as status "H" for Customer

		$data = http_build_query(array(
			'Enroller' => $co_number,
			'Sponsor' => $co_number,
			'Username' => '',
			'Password' => $password,
			'GovtID' => $JU_NO01 . '' . $name,
			'AccountType' => $member_kind,
			'FullName' => $ename,
			'NativeName1' => $name,
			'LastName' => '',
			'FirstName' => '',
			'Address1' => $addr,
			'Address2' => $addr02,
			'City' => '',
			'State' => '',
			'Zip' => $zip,
			'CountryCode' => 'kor',
			'Phone1' => $pho1 . '' . $pho2 . '' . $pho3,
			'Phone2' => $hpho1 . '' . $hpho2 . '' . $hpho3,
			'Email' => $email,
			'appDeviceOSName' => 'KorEnr',
		));

		$url = 'https://api.unicity.net/markets/kor/countries/kor/collections/account/resources/create.json';
		$username = 'koreanenrollment';
		$password = 'c53d93ee202b9f9250f0ecc52056a426ef5f4e6d';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
*/

		/* POST */

/*
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$output = curl_exec($ch);
		// $info = curl_getinfo($ch);
		curl_close($ch);

		$mem_account = json_decode(trim($output))->accounts->account[0];
*/

		$query = "insert into tb_api_log (member_no, send_data, return_data, regDate) values ('$member_no','$data','$output', now())";
		$result_logs = mysql_query($query);

		if ($mem_account == "") {
			mysql_close($connect);
			curl_close($ch);
			//echo $result["error"]["error_code"];
			//echo $result["error"]["error_message"];
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script>
	alert("재전송 중 오류가 발생 했습니다. 에러코드 : <?=$result["error"]["error_code"]?> 에러문구 : <?=$result["error"]["error_message"]?> ");
	self.close();
</script>
</HEAD>
</HTML>
<?
			exit();
		}

		$query = "update tb_userinfo_dup set number = '".$mem_account."' where member_no = '$member_no'";
		$result_update = mysql_query($query);

		$url = 'https://hydra.unicity.net/v5a/customers?unicity=' . $mem_account;
		$username = 'krWebEnrollment';
		$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$response = curl_exec($ch);
		//var_dump($response);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//var_dump($status);
		if (($response !== false) && ($status == 200)) {
			$response = json_decode($response, true);
			//var_dump($response);
			if (isset($response['items'][0]['href'])) {
				$url = $response['items'][0]['href'];
				//var_dump($url);
				curl_setopt($ch, CURLOPT_URL, $url);
				$data = http_build_query(array(
					'depositBankAccount' => array(
						'bankName' => $account_bank,
						'bin' => $search_bank_code,
						'accountHolder' => $name,
						'accountNumber' => $account,
						'accountType' => 'SavingsPersonal',
						'routingNumber' => 1,
					)
				,
				));
				//var_dump($data);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //서버연결에 대한 timeout 
				curl_setopt($ch, CURLOPT_TIMEOUT, 60); //curl 실행에 대한 timeout 
				$response = curl_exec($ch);
				//var_dump($response);
				$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				//var_dump($status);
			}
		}
		
		$query = "insert into tb_api_log (member_no, send_data, return_data, regDate) values ('$member_no','$data','$response', now())";
		$result_logs = mysql_query($query);
				
		mysql_close($connect);

		curl_close($ch);
		
		//$query = "update tb_userinfo_dup set reg_status = '2' where member_no = $member_no";
		//$result = mysql_query($query);
		//$result_str = "신청 처리";


	}
	//echo $JU_NO01;
	//echo $JU_NO02;

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<TITLE><?echo $g_site_title?></TITLE>
<script language="javascript">

	var result = "<?=$result_str?>";
	var number = "<?=$number?>";

	$(document).ready(function() {

		if (result == "") {
			js_check_dup();
		}

		if (result == "신청 처리") {
			opener.js_reload();
		}
	});

	function js_check_dup() {

		if (number == "") {
			js_new();
			return null;
		}

		var Throwable_InvalidJumin_Exception = function() {
			js_next();
			return null;
		};

		var Throwable_InvalidJumin_Exception2 = function() {
			js_fail();
			return null;
		};

		var tax_id = "<?=$JU_NO01.$name?>";

		//alert(tax_id);

		$.ajax({
			async: false,
			dataType: 'jsonp',
			type: 'GET',
			url: 'https://hydra.unicity.net/v5a/customers.js?taxTerms_taxId=' + tax_id + '&mainAddress_country=KR&status=active&_httpMethod=HEAD&callback=?',
			success: function(data) {

				//alert(data['meta']['X-Status-Code']);

				if (data['meta']['X-Status-Code'] != '404') {
					return Throwable_InvalidJumin_Exception();
				}

				$.ajax({
					async: false,
					dataType: 'jsonp',
					type: 'GET',
					url: 'https://hydra.unicity.net/v5a/customers.js?spouse_taxTerms_taxId=' + tax_id + '&mainAddress_country=KR&status=active&_httpMethod=HEAD&callback=?',
					success: function(data) {
						if (data['meta']['X-Status-Code'] != '404') {
							return Throwable_InvalidJumin_Exception();
						}
						js_new();
					},
					failure: function() {
						return Throwable_InvalidJumin_Exception2();
					}
				});
			},
			failure: function() {
				return Throwable_InvalidJumin_Exception2();
			}
		});
	}

	//https://hydra.unicity.net/v5a/customers.js?taxTerms_taxId=741016111082&mainAddress_country=KR&status=active&_httpMethod=HEAD&callback=?
	//'https://hydra.unicity.net/v5a/customers.js?taxTerms_taxId=7003231001011&mainAddress_country=KR&status=active&_httpMethod=HEAD&callback=?
	//https://hydra.unicity.net/v5a/customers.js?spouse_taxTerms_taxId=7107252661713&mainAddress_country=KR&status=active&_httpMethod=HEAD&callback=?

	function js_new() {

		document.frmSearch.mode.value = "NEW";
		//alert("NEW");
		//return;
		document.frmSearch.submit();
	}

	function js_next() {
		document.frmSearch.mode.value = "I";
		//alert("I");
		//return;

		document.frmSearch.submit();
	}

	function js_fail() {
		alert('유니시티 본사와 통신이 실퍠하였습니다.');
		return;
	}

</script>
</HEAD>
<BODY>
<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>재전송 결과</B></TD>
</TR>
</TABLE>
<FORM name="frmSearch" method="post">
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<input type="hidden" name="member_no" value="<?echo $member_no?>">
<input type="hidden" name="mode" value="">
<tr>
	<th>이름 : </th>
	<td><?echo $name?></td>
</tr>
<!--tr>
	<th>주민등록번호 :</th>
	<td><?echo $reg_jumin1?>-<?echo $reg_jumin2?></td>
</tr-->
<tr>
	<th>회원종류 :</th>
	<td><? if (trim($member_kind) == "D" ) { echo " FO 회원"; } else {echo "소비자 회원";} ?></td>
</tr>
<!--tr>
	<th>비밀번호 :</th>
	<td><?echo $password?></td>
</tr-->
<tr>
	<th>FO Number :</th>
	<td><?echo $number?></td>
</tr>
<tr>
	<th>E-Mail :</th>
	<td><?echo $email?></td>
</tr>
<tr>
	<th>처리결과 :</th>
	<td><?echo $result_str?></td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<br>
<br>
</td>
</tr>
</table>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left">&nbsp;</TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		<INPUT TYPE="button" VALUE="닫 기" onClick="self.close();">
	</TD>
</TR>
</TABLE>
</center>
</form>
</body>
</html>
<?
	//mysql_close($connect);
?>