<?php
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	
	$confirmDate = $_REQUEST['confirmDate'];
	$member_no	= str_quote_smart(trim($member_no));
	$mode	    = str_quote_smart(trim($mode));
	$reg_status	= str_quote_smart(trim($reg_status));
	$member_no = trim($member_no);
	$member_no = str_replace("^", "'",$member_no);
	
	$cancelQuery = "select cancelDate from distributorshipCancel where no = '".$member_no."'";
	
	$query_result = mysql_query($cancelQuery);
	$query_row = mysql_fetch_array($query_result);
	
	$cancelDate = $query_row["cancelDate"];
	$flag = $_POST['flag'];
	$forUpdateNo = $_POST['no'];
	$delFlag = $_POST['flag'];

	if($reg_status == '4'){
		$Date = "confirm_date = now()";
		$admName = "confirm_ma = '$s_adm_name'";
	}else if($reg_status == '3'){
		$Date = "print_date = now()";
		$admName = "print_ma = '$s_adm_name'";
	}else if($reg_status == '8'){
		$Date = "wait_date = now()";
		$admName = "wait_ma = '$s_adm_name'";
	}else if($reg_status == '9'){
		$Date = "reject_date = now()";
		$admName = "reject_ma = '$s_adm_name'";
	}else if($reg_status == '2'){
		$Date = "cancelDate = now()";
	}


	if($flag=='update'){
		$queryUpdate = "update distributorshipCancel SET 
						reg_status = $reg_status
		 				where no = $forUpdateNo";
		echo $queryUpdate;
		mysql_query($queryUpdate) or die("Query Error");
	
		echo "<script>alert('수정이 완료 되었습니다.');
			parent.frames[3].location = 'distributorshipCancel_admin.php';	
			</script>";
	
	}
	
	if($reg_status != '2'){
	$query = "update distributorshipCancel set
				     reg_status = $reg_status,
					 $Date,	     
					$admName
			where no = $member_no";

	}else if($reg_status == '2'){
		
		$query = "update distributorshipCancel set
		reg_status = $reg_status
		where no = $member_no";
		 
	}
	mysql_query($query) or die("Query Error");
	
		echo "<script>alert('완료 되었습니다.');
			parent.frames[3].location = 'distributorshipCancel_admin.php';	
			</script>";
	
	
?>