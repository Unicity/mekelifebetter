<?php
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	
	$confirmDate = $_REQUEST['confirmDate'];
	$member_no	= str_quote_smart(trim($member_no));
	$member_id	= str_quote_smart(trim($member_id));
	$reg_status	= str_quote_smart(trim($reg_status));
	$mode	    = str_quote_smart(trim($mode));

	$member_no = trim($member_no);
	$member_no = str_replace("^", "'",$member_no);

	if($reg_status == '4'){
		$Date = "confirm_date = now()";
		$admName = "confirm_ma = '$s_adm_name'";
	}else if($reg_status == '3'){
		$Date = "print_date = now()";
		$admName = "print_ma = '$s_adm_name'";
	}else if($reg_status == '2'){
		$Date = "apply_date = now()";
	}
	
	$query = "update tb_portal set
				     reg_status = '$reg_status',
					 $Date,	     
					$admName
			where id = $member_no
	          and member_id = '$member_id'";
		mysql_query($query) or die("Query Error");
	
		echo "<script>alert('완료 되었습니다.');
			parent.frames[3].location = 'portal_admin.php';	
			</script>";
	
?>