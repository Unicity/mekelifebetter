<?session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<?
#	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";
	include "./new_api_function.php";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
	}

	function isExist($reg_jumin1, $reg_jumin2)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_userinfo where reg_jumin1='$reg_jumin1' and reg_jumin2='$reg_jumin2'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);

		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}

	$mode					= str_quote_smart(trim($mode));

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
		$enc_password = encrypt($key, $iv, $password);

		logging($s_adm_id,'add new member ');

		if(!isExist($reg_jumin1, $reg_jumin2) == 1) {

			$query = "insert into tb_userinfo (name, password, reg_jumin1, reg_jumin2, pho1, pho2, pho3, hpho1,
			          hpho2, hpho3, zip, addr, addr_detail, email, email_flag, account, account_bank, member_kind, job, 
			          interest, birth_y, birth_m, birth_d, sex, regdate, reg_status, live, ldate, visit_count, hphone_flag, JU_NO01, JU_NO02, en_account) values 
					  ('$name', '$enc_password', '$reg_jumin1', '$reg_jumin2', '$pho1', '$pho2', '$pho3', '$hpho1',
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

		$member_no					= str_quote_smart(trim($member_no));
		$ename							= str_quote_smart(trim($ename));
		$number							= str_quote_smart(trim($number));
		$password						= str_quote_smart(trim($password));
		$active_kind				= str_quote_smart(trim($active_kind));
		$couple							= str_quote_smart(trim($couple));
		$couple_name				= str_quote_smart(trim($couple_name));
		$couple_ename				= str_quote_smart(trim($couple_ename));
		$couple_reg_jumin1	= str_quote_smart(trim($couple_reg_jumin1));
		$couple_reg_jumin2	= str_quote_smart(trim($couple_reg_jumin2));
		$pho1								= str_quote_smart(trim($pho1));
		$pho2								= str_quote_smart(trim($pho2));
		$pho3								= str_quote_smart(trim($pho3));
		$hpho1							= str_quote_smart(trim($hpho1));
		$hpho2							= str_quote_smart(trim($hpho2));
		$hpho3							= str_quote_smart(trim($hpho3));
		$zip								= str_quote_smart(trim($zip));
		$addr								= str_quote_smart(trim($addr));
		$addr_detail				= str_quote_smart(trim($addr02));
		$del_zip						= str_quote_smart(trim($del_zip));
		$del_addr						= str_quote_smart(trim($del_addr));
		$del_addr_detail		= str_quote_smart(trim($del_addr02));
		$email							= str_quote_smart(trim($email));
		$email_flag					= str_quote_smart(trim($email_flag));
		$hphone_flag				= str_quote_smart(trim($hphone_flag));
		$account						= str_quote_smart(trim($account));
		$account_bank				= str_quote_smart(trim($account_bank));
		$co_number					= str_quote_smart(trim($co_number));
		$co_name						= str_quote_smart(trim($co_name));
		$member_kind				= str_quote_smart(trim($member_kind));
		$interest						= str_quote_smart(trim($interest));
		$birth_y						= str_quote_smart(trim($birth_y));
		$birth_m						= str_quote_smart(trim($birth_m));
		$birth_d						= str_quote_smart(trim($birth_d));
		$sex								= str_quote_smart(trim($sex));
		$agree_01						= str_quote_smart(trim($agree_01));
		$agree_02						= str_quote_smart(trim($agree_02));
		$agree_03						= str_quote_smart(trim($agree_03));
		$agree_04						= str_quote_smart(trim($agree_04));

		$sel_agree01				= str_quote_smart(trim($sel_agree01));
		$sel_agree02				= str_quote_smart(trim($sel_agree02));
		$sel_agree03				= str_quote_smart(trim($sel_agree03));
		$sel_agree04				= str_quote_smart(trim($sel_agree04));
		$sel_agree05				= str_quote_smart(trim($sel_agree05));

		$reg_status					= str_quote_smart(trim($reg_status));

		$couple_reg_jumin1 = encrypt($key, $iv, $couple_reg_jumin1);
		$couple_reg_jumin2 = encrypt($key, $iv, $couple_reg_jumin2);
		$en_account				 = encrypt($key, $iv, $account);
		//$enc_password = encrypt($key, $iv, $password);
		
		$changeHistory = "";

		$result = mysql_query("select * from tb_userinfo where member_no = '".$member_no."'") or die(mysql_error());	
		$row = mysql_fetch_array($result);
		

		//비밀번호 변경
		$passSql = "";
		if(strlen($password) > 3 && $row[password] != $enc_password){
			$old_password = decrypt($key, $iv, $row[password]);
			$updateMembersPasswordResult = updateMembersPassword($number, $old_password, $password);
			if($updateMembersPasswordResult == "OK") {
				$passSql = "password = '".$enc_password."',";
				$changeHistory .= "비밀번호변경<br>";
			}
		}
				
			
		if($row[ename] != $ename){  //영문성명 변경
			$changeHistory .=  "영문성명 변경 : ".$row[ename]."->".$ename."<br>";
		}
		
		
		if($row[zip] != $zip1 || $row[addr] != $addr || $row[addr_detail] != $addr02){ //우편번호 변경
			$changeHistory .=  "주소 변경 : ".$row[zip]." ". $row[addr]." ".$row[addr_detail]."->".$zip1." ".$addr." ".$addr02."<br>";
		
			$changeAddress = array(
					'address1' => $dong,
					'address2' => $addt02,
					'city' => $city,
					'state' => $state,
					'zip' => $zip1,
					'country' => 'KR'
				);
			updateMembersAddress($mem_account, $changeAddress);
		}

		


		if($row[account_bank] != $account_bank || decrypt($key, $iv, $row[account]) != $account){
			$result1 = mysql_query("select code from tb_code where parent='bank3' and name='".$account_bank."'") or die(mysql_error());	
			$row1 = mysql_fetch_array($result1);			
			$changeHistory .=  "계좌정보 변경".$row[account_bank]." ".decrypt($key, $iv, $row[account])."->".$account_bank."(".$row1['code'].") ".$account."<br>";
		}

		
		$hpChange = 'N';
		if($row[hpho1].$row[hpho2].$row[hpho3] != $hpho1.$hpho2.$hpho3){
			$hpChange = 'Y';
			$changeHistory .= "핸드폰번호 변경 : ".$row[hpho1].$row[hpho2].$row[hpho3]."->".$hpho1.$hpho2.$hpho3."<br>";
		}

		$emailChange = 'N';
		if($row[email] != $email){ 
			$emailChange = 'Y';
			$changeHistory .=  "email변경 : ".$row[email]."->".$email."<br>";
		}

		if($hpChange == 'Y' || $emailChange =='Y'){
			updateMembersEmailAndHp($number, $email, $hpho1.$hpho2.$hpho3);
		}

		if($row[reg_status] != $reg_status && $reg_status == '4'){ //처리사항 완료로 변경시

			$rightsArray = array(
							"agree_01" => array('이메일 통보', 'Unicity', 'SendNoticeEmail', $agree_01),
							"agree_02" => array('주요안내사항 통보', 'Unicity', 'SendNoticeSms', $agree_02),
							"agree_03" => array('SMS 통보', 'Unicity', 'SendMail', $agree_03),
							"sel_agree01"  => array('개인정보 (하나투어, 레드캡, SMTT) 제공 동의', 'Unicity', 'ShareOrdersDataWithTravelAgency', $sel_agree01),
							"sel_agree02"  => array('마케팅 목적의 이메일 수신 동의', 'Unicity', 'SendMarketingEmail', $sel_agree02),
							"sel_agree03"  => array('마케팅 목적의 SMS 수신 동의', 'Unicity', 'SendMarketingSms', $sel_agree03),
							"sel_agree04"  => array('마케팅 목적의 우편물 수신 동의', 'Unicity', 'SendMarketingMail', $sel_agree04),
							"sel_agree05"  => array('본인외 주문에 대한 동의', 'Upline', 'Order', $sel_agree05)
							);

			if($number != '') updateMembersRights($number, $rightsArray);
		}

		if($changeHistory != ''){
			
			$sql = "insert into tb_userinfo_history set
					gubun = 'userinfo',
					member_no = '".$member_no."', 
					number = '".$number."',
					name = '".$krname."',
					changed = '".$changeHistory."',
					redAdm = '".$s_adm_id."',
					regDate = now();";

			@mysql_query($sql);

		}

		
		//logging($s_adm_id,'modify member '.$number); 
		
		//password = '$enc_password',

		$query = "update tb_userinfo set 
						number = '$number',
						ename = '$ename',
						$passSql						
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
			
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "
			<html>\n
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			</script>\n
			</html>";
		exit;

	} else if ($mode == "del") {
		
		$member_no					= str_quote_smart(trim($member_no));
		
		logging($s_adm_id,'modify member '.$member_no); 

		$query = "insert into tb_userinfo_del_log (member_no, number, name, ename, reg_jumin1, reg_jumin2, 
										 pho1, pho2, pho3, hpho1, hpho2, hpho3, zip, addr, addr_detail, del_zip, del_addr,
										 del_addr_detail, email, email_flag, account, account_bank, co_number, co_name, regdate)  
							select member_no, number, name, ename, reg_jumin1, reg_jumin2, 
										 pho1, pho2, pho3, hpho1, hpho2, hpho3, zip, addr, addr_detail, del_zip, del_addr,
										 del_addr_detail, email, email_flag, account, account_bank, co_number, co_name, regdate
							 from tb_userinfo where member_no = '$member_no' ";
		
		//echo $query;

		mysql_query($query) or die("Query Error1");

		$query = "insert into tb_userinfo_del_log_admin (member_no, del_date, del_admin) values ('$member_no', now(), '$s_adm_id') ";
		//echo $query;
		mysql_query($query) or die("Query Error2");



		$query = "delete from tb_userinfo 
				    where member_no = '$member_no'";

		#echo $query; 					 
		mysql_query($query) or die("Query Error");

		mysql_close($connect);

		echo "
			<html>\n
			<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
			<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'user_list.php';
			</script>\n
			</html>";
		exit;

	}
?>