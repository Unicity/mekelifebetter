<?php

	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";


	logging($s_adm_id,'modify training applicant detail '.$applyId.' '.$yyyy);

	$yyyy= date('Y');
	$applyId = $_POST['applyId'];
	$UserName = $_POST['UserName'];
	$Phone = $_POST['phone'];
	$email = $_POST['email'];
	$birthDay = $_POST['birthDay'];
	$licenseNum = $_POST['licenseNum'];
	$representativeName = $_POST['representativeName'];
	$representativeBirth = $_POST['representativeBirth'];
	$deputyEduYn = $_POST['deputyEduYn'];
	$deputyReason = $_POST['deputyReason'];
	$modifyDate = $_POST['modifyDate'];
	$cancelYn = $_POST['cancelYn'];
	$cancelDate = $_POST['cancelDate'];
	$cancelReason = $_POST['cancelReason'];
	$companyMember = $_POST['companyMember'];
	
	logging($s_adm_id,'modify training applicant detail '.$applyId.' '.$yyyy);
	
	$query = "UPDATE education_apply SET Phone = '".$Phone."', email = '".$email."',birthDay= '".$birthDay."',licenseNum= '".$licenseNum."',representativeName = '".$representativeName."',representativeBirth='".$representativeBirth."',deputyEduYn='".$deputyEduYn."',deputyReason='".$deputyReason."',cancelYn='".$cancelYn."' ,cancelDate='".$cancelDate."',cancelReason='".$cancelReason."',companyMember='".$companyMember."'  WHERE id = '".$applyId."' and year = '".$yyyy."' ";

	mysql_query($query) or die("Query Error");
	mysql_close($connect);
	
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
			alert('수정이 완료 되었습니다.');
			parent.frames[3].location = 'eduApply_view.php?applyId=$applyId';
		</script>";
	exit;
?>