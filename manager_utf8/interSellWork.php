<?php
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";

$workingDate = $_REQUEST['workingDate'];
$member_no = $_REQUEST['member_no'];
$reg_status = $_REQUEST['reg_status'];



$query = "update internet_sales_warning set
		  reg_status = '$reg_status',
		  working_date = '$workingDate'
		  where No = $member_no";

		mysql_query($query) or die("Query Error");
	echo "<script>alert('처리중으로 변경 되었습니다.');
			parent.frames[3].location = 'internetSell.php';	
			</script>";	
?>