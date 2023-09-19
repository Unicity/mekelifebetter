<?php
	include "admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../inc/common_function.php";
	include "../AES.php";
	
	$startDate			= isset($_POST['startDate']) ? $_POST['startDate'] : "";
	$baId 				= isset($_POST['baId']) ? $_POST['baId'] : "";
	$baName 			= isset($_POST['baName']) ? $_POST['baName'] : "";
	$endDate 	= isset($_POST['endDate']) ? $_POST['endDate'] : "";
	$reg_status 	= isset($_POST['reg_status']) ? $_POST['reg_status'] : "";
	$autoshipYn 	= isset($_POST['autoshipYn']) ? $_POST['autoshipYn'] : "";
	$note 	= isset($_POST['note']) ? $_POST['note'] : "";
	$typeCheck = isset($_POST['typeCheck']) ? $_POST['typeCheck'] : "";
	$s_no = isset($_POST['s_no']) ? $_POST['s_no'] : "";

	if($typeCheck !='edit'){
		$insertSmember = "INSERT INTO tb_smember(`start_date`, `member_no`, `member_name`, `end_date`, `reg_status`, `autoshipYn`, `note`) VALUES ('$startDate','$baId','$baName','$endDate','$reg_status','$autoshipYn','$note')";
		$insertSmemberResult = mysql_query($insertSmember) or die("Query Error");
		$alert = '저장이 완료 됐습니다.';
		echo "<script>alert('$alert');
			history.go(-1);</script>";
	}else if($typeCheck =='edit'){
		$updateSmember = "update tb_smember set start_date = '$startDate',
		                                        end_date = '$endDate',
												reg_status = '$reg_status'
											where member_no = '$baId' 
											  and s_no = '$s_no'";
		mysql_query($updateSmember) or die("Query Error");


		$alert = '수정이 완료 됐습니다.';
		echo "<script>alert('$alert');
		history.go(-2);</script>";											  
	}
?>	