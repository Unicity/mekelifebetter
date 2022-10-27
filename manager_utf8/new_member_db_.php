<?
	include "./admin_session_check.inc";
	include "../dbconn_utf8.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	function isExistUserInfoAsJumin($reg1, $reg2)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_userinfo where reg_jumin1='$reg1' and reg_jumin2='$reg2' "; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);

		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}

	function isExistUserInfoAsTax($tax)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_userinfo where tax_number='$tax'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);

		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}

	$reg_jumin1			= str_quote_smart(trim($reg_jumin1));
	$reg_jumin2			= str_quote_smart(trim($reg_jumin2));
	$tax_number			= str_quote_smart(trim($tax_number));
	$member_kind		= str_quote_smart(trim($member_kind));

	$name						= str_quote_smart(trim($name));
	$number					= str_quote_smart(trim($number));
	$zip						= str_quote_smart(trim($zip));
	$addr						= str_quote_smart(trim($addr));
	$sex						= str_quote_smart(trim($sex));
	$sponsor_number	= str_quote_smart(trim($sponsor_number));
	$sponsor_name		= str_quote_smart(trim($sponsor_name));
	$appdate				= str_quote_smart(trim($appdate));

	$hpho1					= str_quote_smart(trim($hpho1));
	$hpho2					= str_quote_smart(trim($hpho2));
	$hpho3					= str_quote_smart(trim($hpho3));
	$email					= str_quote_smart(trim($email));
	$email_flag			= str_quote_smart(trim($email_flag));
	$interest				= str_quote_smart(trim($interest));

	$name = eregi_replace(" ", "", $name);
	$sponsor_name = eregi_replace(" ", "", $sponsor_name);
	
	if (strlen($reg_jumin1) == 6 ) {	

		if ((substr($reg_jumin1, 0,2) == "00" ) || 
			(substr($reg_jumin1, 0,2) == "01" ) || 
			(substr($reg_jumin1, 0,2) == "02" ) || 
			(substr($reg_jumin1, 0,2) == "03" ) || 
			(substr($reg_jumin1, 0,2) == "04" ) ) { 

			$birth_y = "20".substr($reg_jumin1, 0,2);		
		} else {
			$birth_y = "19".substr($reg_jumin1, 0,2);		
		}		
		
		$birth_m = substr($reg_jumin1, 2,2);
		$birth_d = substr($reg_jumin1, 4,6);

	}

	if ($sex == "Female" ) {	
			$sex = "2";		
	} else {
			$sex = "1";		
	}

	if (strlen($reg_jumin1) > 5 ) {

		if (!isExistUserInfoAsJumin($reg_jumin1, $reg_jumin2) == 1) {


			$query = "insert into tb_userinfo (name, number, reg_jumin1, reg_jumin2,
			          hpho1, hpho2, hpho3, zip, addr, del_zip, del_addr, email, email_flag, member_kind, 
			          interest, birth_y, birth_m, birth_d, sex, regdate, reg_status, live, ldate, visit_count,
			          co_name, co_number) values 
					  ('$name', '$number', '$reg_jumin1', '$reg_jumin2', 
					   '$hpho1', '$hpho2', '$hpho3', '$zip', '$addr', '$zip', '$addr', '$email', '$email_flag', '$member_kind', 
			           '$interest', '$birth_y', '$birth_m', '$birth_d','$sex', '$appdate', '4', 'N', now(), 0,
			           '$sponsor_name', '$sponsor_number' )";

			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('등록 되었습니다.');
				</script>";
			exit;

		} else {
	
			$query = "update tb_userinfo set 
							hpho1 = '$hpho1',
							hpho2 = '$hpho2',
							hpho3 = '$hpho3',
							email = '$email',
							email_flag = '$email_flag',
							interest = '$interest',
							moddate = now()
						where member_no = '$member_no'";
						 
			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('수정 되었습니다.');
				</script>";
			exit;
	
		}

	} else {

		if (!isExistUserInfoAsTax($tax_number) == 1) {

			$query = "insert into tb_userinfo (name, number, reg_jumin1, reg_jumin2, tax_number,
			          hpho1, hpho2, hpho3, zip, addr, del_zip, del_addr, email, email_flag, member_kind, 
			          interest, birth_y, birth_m, birth_d, sex, regdate, reg_status, live, ldate, visit_count,
			          co_name, co_number) values 
					  ('$name', '$number', '$reg_jumin1', '$reg_jumin2', '$tax_number',
					   '$hpho1', '$hpho2', '$hpho3', '$zip', '$addr', '$zip', '$addr', '$email', '$email_flag', '$member_kind', 
			           '$interest', '$birth_y', '$birth_m', '$birth_d','$sex', '$appdate', '4', 'N', now(), 0,
			           '$sponsor_name', '$sponsor_number' )";

			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('등록 되었습니다.');
				</script>";
			exit;

		} else {
	
			$query = "update tb_userinfo set 
							hpho1 = '$hpho1',
							hpho2 = '$hpho2',
							hpho3 = '$hpho3',
							email = '$email',
							email_flag = '$email_flag',
							interest = '$interest',
							moddate = now(),
						where member_no = '$member_no'";
						 
			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('수정 되었습니다.');
				</script>";
			exit;
	
		}

	}

?>