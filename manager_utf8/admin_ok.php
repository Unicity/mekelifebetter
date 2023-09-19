<?php 
session_start();

//////////////////////////////////////////////////////////////
// 	File Name 	: admin_ok.php
// 	Description : 인증 페이지
//////////////////////////////////////////////////////////////

	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";
	
	//6개월 미로그인자 상태 일괄 업데이트
	$six_month =  time() - (86400 * 180);
	$result = mysql_query("update tb_admin set status = 'N' where status = 'Y' and last_login < '".$six_month."'") or die(mysql_error());	

	function alertMsg ($strMsg) {

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script langauge=\"JavaScript\">\n
			alert('$strMsg');
			history.back();
			</script>\n";
		exit;
	}

	function no_cache () {
		Header("Cache-Control: no-cache, must-revalidate");
		Header("Pragma: no-cache");
		Header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
	}

	function updateLoginFail($id)
	{
		
		$query="SELECT count(*) FROM tb_password_fail_counter WHERE id = '$id' ";
		 
		$result = mysql_query($query);

		$row = mysql_fetch_row($result);

		

		if($row[0] == 0){
			$query2 = "INSERT INTO tb_password_fail_counter (`id`, `counter`, `createdDate`) values ('$id', 1, now()) ";

		} else {
			$query2 = "UPDATE tb_password_fail_counter SET `counter` = `counter` + 1, `createdDate` = now() WHERE id = '$id'  ";
		}
		
		mysql_query($query2);
		 
	}

	function checkPasswordFailCounter($id){
		$query="SELECT case when timestampdiff(MINUTE, createdDate, now())<10 AND counter%5=0 THEN 'Y' ELSE 'N' END AS isLocked FROM makelifebetter.tb_password_fail_counter WHERE id = '$id' ";

		$result = mysql_query($query);

		$row = mysql_fetch_row($result);
		 
		if ($row[0] == 'Y') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function deleteFailCounter($id){
		$query = "DELETE FROM makelifebetter.tb_password_fail_counter WHERE id = '$id' ";

		mysql_query($query);

	}

	no_cache();

	$adminid		= str_quote_smart(trim($adminid));
	$adminpasswd	= str_quote_smart(trim($adminpasswd));


	if (checkPasswordFailCounter($adminid) == TRUE){
		alertMsg("비밀번호 오류 횟수 초과로 10분간 계정인 잠긴 상태입니다.   \\n\\n");
		mysql_close($connect);
		exit;
	}

	$query = "select id, passwd, Email, UserName, temp1, temp2, optpass, datediff(NOW(), pw_update_date) AS BB_DATEDIFF from tb_admin where id = '".$adminid."' and status = 'Y'";
	$result = mysql_query($query);

	//비밀번호 sha256 암호화로 변경
	$hashedpwd = base64_encode(hash('sha256', $adminpasswd, true));


	if ($row = mysql_fetch_array($result)){

		if(substr($row['passwd'], 0, 4) == "$2y$") {
			//비밀번호 검증
			if(!password_verify($adminpasswd, $row['passwd'])){
				updateLoginFail($adminid);
				logging($adminid, "login fail");
				alertMsg("비밀번호가 일치하지 않습니다! \\n\\n");
			
				mysql_close($connect);
				exit;
			}else{
				//비밀번호 업데이트
				mysql_query("update tb_admin set passwd = '".$hashedpwd."' where id = '".$adminid."'");
			}
		}else{
			if($row['passwd'] != $hashedpwd){
				updateLoginFail($adminid);
				logging($adminid, "login fail");
				alertMsg("비밀번호가 일치하지 않습니다! \\n\\n");
			
				mysql_close($connect);
				exit;
			}
		}

		$_SESSION["tmp_s_adm_id"] = $adminid; 

		deleteFailCounter($adminid);
		
		//otp 로그인 페이지 이동
		?>
		<html>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script>
		document.location = "/manager_utf8/admin_otp.php";
		</script>
		</html>

		<?php 
	
	} else {
		alertMsg("아이디 또는 비밀번호가 일치하지 않습니다! \\n\\n");
		
		mysql_close($connect);
		exit;

	}

	exit;
?>