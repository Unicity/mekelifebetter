<?php 
header('Content-Type: text/html; charset=UTF-8');

	include "admin_session_check.inc";
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

	function isExist($sid)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_admin where id='$sid'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);

		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}

	if ($mode == "add") {

		$id				= str_quote_smart(trim($id));
		$passwd		= str_quote_smart(trim($passwd));
		$UserName = str_quote_smart(trim($UserName));
		$UserInfo = str_quote_smart(trim($UserInfo));
		$Phone1		= str_quote_smart(trim($Phone1));
		$Phone2		= str_quote_smart(trim($Phone2));
		$Email		= str_quote_smart(trim($Email));
		$UserDept = str_quote_smart(trim($UserDept));
		$status = str_quote_smart(trim($status));

		//$en_pass = encrypt($key, $iv, $passwd);
		$en_pass = "";

		if(!isExist($id) == 1) {

			$optpass = makeOptPass();
			//$new_pass = password_hash($passwd, PASSWORD_DEFAULT);
			$new_pass = base64_encode(hash('sha256', $passwd, true));

			$query = "insert into tb_admin (id, passwd, UserName, UserInfo, Phone1, Phone2, Email, regDate, temp1, temp2, optpass, EN_PASS, pw_update_date, status, last_login) values 
					  ('$id', '$new_pass', '$UserName', '$UserInfo', '$Phone1', '$Phone2', '$Email', now(), '$temp1', '$UserDept', '$optpass', '$en_pass', now(), '$status', '".time()."')";

			mysql_query($query) or die("Query Error");
			
			logging_admin($s_adm_id,'add user '.$id);
			
			mysql_close($connect);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('등록 되었습니다.');
				parent.frames[3].location = 'admin_list.php';
				</script>";
			exit;

		} else {
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('이미 등록된 ID 입니다.');
				</script>";
			exit;
		}
	} else if ($mode == "mod") {

		//echo $passwd_chk;

		$id				= str_quote_smart(trim($id));
		$passwd		= str_quote_smart(trim($passwd));
		$UserName = str_quote_smart(trim($UserName));
		$UserInfo = str_quote_smart(trim($UserInfo));
		$Phone1		= str_quote_smart(trim($Phone1));
		$Phone2		= str_quote_smart(trim($Phone2));
		$Email		= str_quote_smart(trim($Email));
		$temp1		= str_quote_smart(trim($temp1));
		$status		= str_quote_smart(trim($status));

		$UserDept = str_quote_smart(trim($UserDept));

		//$en_pass = encrypt($key, $iv, $passwd);
		$en_pass = "";
		//$new_pass = password_hash($passwd, PASSWORD_DEFAULT);
		$new_pass = base64_encode(hash('sha256', $passwd, true));

		$change_str = "";

		$result = mysql_query("select * from tb_admin where id = '".$id."'") or die(mysql_error());	
		$row = mysql_fetch_array($result);

		if($row[temp1] != $temp1) $change_str .= "temp1:".$row[temp1]."->".$temp1.",";
		if($row[UserName] != $UserName) $change_str .= "UserName:".$row[UserName]."->".$UserName.",";
		if($row[temp2] != $UserDept) $change_str .= "UserDept:".$row[temp2]."->".$UserDept.",";
		if($row[Phone1] != $Phone1) $change_str .= "Phone1:".$row[Phone1]."->".$Phone1.",";
		if($row[Phone2] != $Phone2) $change_str .= "Phone2:".$row[Phone2]."->".$Phone2.",";
		if($row[Email] != $Email) $change_str .= "Email:".$row[Email]."->".$Email.",";
		if($row[UserInfo] != $UserInfo) $change_str .= "UserInfo:".$row[UserInfo]."->".$UserInfo.",";
			

		if($passwd_chk=="Y") {
			logging_admin($s_adm_id, "modify user password ".$UserName);

			$query = "update tb_admin set 
								passwd = '$new_pass',
								UserName = '$UserName',
								UserInfo = '$UserInfo',
								Phone1 = '$Phone1',
								Phone2 = '$Phone2',
								Email = '$Email', 
								temp1 = '$temp1', 
								temp2 = '$UserDept', 
								EN_PASS = '$en_pass',
								pw_update_date = now(),
								status = '$status'
					where id = '$id'";

		} else {
			//logging_admin($s_adm_id, "modify user ".$UserName.' UserInfo ='.$UserInfo.' Phone1='.$Phone1.' Phone2'.$Phone2.' Email='.$Email.' temp1='.$temp1.' temp2='.$UserDept);
			$query = "update tb_admin set 
								UserName = '$UserName',
								UserInfo = '$UserInfo',
								Phone1 = '$Phone1',
								Phone2 = '$Phone2',
								Email = '$Email', 
								temp1 = '$temp1',
								temp2 = '$UserDept',
								status = '$status'
					where id = '$id'";
		}

		//echo $query;
		mysql_query($query) or die("Query Error");

		if($change_str != ""){
			logging_admin($s_adm_id, "modify user ".$UserName." ".$change_str);
		}
		
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			</script>";
		exit;

	} else if ($mode == "reset") {
			 
		$adminid		= str_quote_smart(trim($adminid));
		$oldadminpasswd	= str_quote_smart(trim($oldadminpasswd));
		$newadminpasswd	= str_quote_smart(trim($newadminpasswd));

		$result = mysql_query("select * from tb_admin where id = '$adminid'") or die(mysql_error());
		$row = mysql_fetch_array($result);

		//비밀번호 sha256 암호화로 변경
	

		if(substr($row['passwd'], 0, 4) == "$2y$") {
			if(!password_verify($oldadminpasswd, $row['passwd'])){
				msg_replace('비밀번호가 일치하지 않습니다.', '/manager_utf8/admin_password_reset.php');
				exit;
			}
		}else{
			$hash_oldadminpasswd = base64_encode(hash('sha256', $oldadminpasswd, true));

			if($row['passwd'] != $hash_oldadminpasswd){
				msg_replace('비밀번호가 일치하지 않습니다.', '/manager_utf8/admin_password_reset.php');
				exit;
			}
		}


		//$new_pass = password_hash($newadminpasswd, PASSWORD_DEFAULT);
		$new_pass = base64_encode(hash('sha256', $newadminpasswd, true));


		$query = "UPDATE tb_admin SET 
					passwd = '$new_pass'
					, EN_PASS = ''
					, pw_update_date = now()
					WHERE id = '$adminid'";

		mysql_query($query) or die("Query Error");
		
		$result =  mysql_affected_rows();

		if($result == 1){
			msg_replace('수정 되었습니다.', '/manager_utf8/admin_main.php');
 		} else {
			msg_err('처리가 되지 않았습니다');
		}


	}  else if ($mode == "del") {
		$id = str_quote_smart(trim($id));
		$id = str_replace("^", "'",$id);

		$query = "delete from tb_admin 
				    where id in $id";


		mysql_query($query) or die("Query Error1");

		logging_admin($s_adm_id,'delete user '.$id);
		
		mysql_close($connect);

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'admin_list.php';
			</script>";
		exit;

	}
?>