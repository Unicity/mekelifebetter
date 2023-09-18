<?php 
session_start();

//////////////////////////////////////////////////////////////
// 	File Name 	: admin_ok.php
// 	Description : 인증 페이지
//////////////////////////////////////////////////////////////

	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

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
		alertMsg("비밀번호 오류 횟수 초과로 10분간 계정인 잠긴 상태입니다.                            \\n\\n");
		mysql_close($connect);
		exit;
	}

	$query = "select id, passwd, Email, UserName, temp1, temp2, optpass, datediff(NOW(), pw_update_date) AS BB_DATEDIFF from tb_admin where id = '".$adminid."'";
	$result = mysql_query($query);

	if ($row = mysql_fetch_array($result)){

		//비밀번호 검증
		if(!password_verify($adminpasswd, $row['passwd'])){
			updateLoginFail($adminid);
			logging($adminid, "login fail");
			alertMsg("비밀번호가 일치하지 않습니다! \\n\\n");
		
			mysql_close($connect);
			exit;
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

	$en_adminpasswd = encrypt($key, $iv, $adminpasswd);

	//$query = "select id, Email, UserName, temp1, datediff(NOW(), pw_update_date) AS BB_DATEDIFF from tb_admin where id = '$adminid' ";
	$query = "select id, Email, UserName, temp1, temp2, datediff(NOW(), pw_update_date) AS BB_DATEDIFF from tb_admin where id = '$adminid' and EN_PASS = '$en_adminpasswd' ";
	//echo $query;
	$result = mysql_query($query);

	if ($row = mysql_fetch_row($result)){

		/*
		session_register("s_adm_id"); 
		session_register("s_adm_email"); 
		session_register("s_adm_name"); 
		session_register("s_adm_dept"); 
		session_register("s_flag"); 
		*/

		$s_adm_id = str_quote_smart(trim($row[0]));
		$s_adm_email = str_quote_smart(trim($row[1]));
		$s_adm_name = str_quote_smart(trim($row[2]));
		$s_flag = str_quote_smart(trim($row[3]));
		$s_adm_dept = str_quote_smart(trim($row[4]));
		$update_date = (int)trim($row[5]);


		$_SESSION["s_adm_id"] = $s_adm_id; 
		$_SESSION["s_adm_email"] = $s_adm_email; 
		$_SESSION["s_adm_name"] = $s_adm_name; 
		$_SESSION["s_adm_dept"] = $s_adm_dept; 
		$_SESSION["s_flag"] = $s_flag; 

		//logging($s_adm_id, "logged in");
		
		//deleteFailCounter($s_adm_id);
		
		mysql_close($connect);

	} else {
		
		//updateLoginFail($adminid);

		//logging($adminid, "login fail");

		alertMsg("아이디 또는 비밀번호가 일치하지 않습니다!                                  \\n\\n");
		
		mysql_close($connect);
		exit;
	}
	$_SESSION[reg]=$s_adm_name;
	if ($update_date > 180 && $s_adm_id != 'admin') {
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script>
	alert("비밀번호를 6개월 이상 이용 하셨습니다. \n\n새로운 비밀번호로 수정 해 주세요.");
	document.location = "/manager_utf8/admin_password_reset.php";
</script>
</html>
<?
	} else {
?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<script language="javascript">
	document.location = "/manager_utf8/admin_main.php";
</script>
</html>
<?
	}
?>