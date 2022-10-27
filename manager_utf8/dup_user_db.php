<?session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<?

#	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

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
			
			logging($s_adm_id,'add dup user '.$name);

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

		//password = '$password',

		$passSql = "";
		if(strlen($password) > 3){
			$enc_password = encrypt($key, $iv, $password);
			$passSql = "password = '$enc_password',";
		}
		
		$query = "update tb_userinfo_dup set 
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
		
		//echo $query;
		//exit;

		mysql_query($query) or die("Query Error");
		//mysql_query($query) or die(mysql_error());

		logging($s_adm_id,'modify dup user '.$number);

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

		logging($s_adm_id,'delete dup user '.$member_no);
		
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