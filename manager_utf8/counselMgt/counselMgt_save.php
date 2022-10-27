<?php
session_start();
?>

<?php
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";

	$s_flag = str_quote_smart_session($s_flag);
	$s_adm_id = str_quote_smart_session($s_adm_id);
	
	$member_no = str_quote_smart(trim($member_no));
	$member_name = str_quote_smart(trim($member_name));
	$received_no = str_quote_smart(trim($received_no));
	$counsel_Type1 = str_quote_smart(trim($counsel_Type1));
	$location = str_quote_smart(trim($location));
	$counsel_Type2 = str_quote_smart(trim($counsel_Type2));
	$status = trim($status);
	$description = trim($description);
	$transferred_dept = str_quote_smart(trim($transferred_dept));
	$transferred_staff = str_quote_smart(trim($transferred_staff));
	$short_comment = trim($short_comment);


	$query = "insert into tb_counsel 
				(`member_no`
				,`name`
				,`contact_no`
				,`counsel_type1`
				,`counsel_type2`
				,`location`
				,`transferred_dept`
				,`transferred_staff`
				,`transferred_date`
				,`short_comment`
				,`description`
				,`updator`
				,`updatedate`
				,`createdate`
				,`status` 
				) values (
				'$member_no'
				,'$member_name'
				,'$received_no'
				,'$counsel_Type1'
				,'$counsel_Type2'
				,'$location'
				,'$transferred_dept'
				,'$transferred_staff'
				, now()
				,'$short_comment'
				,'$description'
				,'$s_adm_id'
				,now()
				,now()
				,$status
			)";
	 

$result = mysql_query($query); 
	
	if($result) {
		 echo "<script> alert('업데이트 완료')</script>";



	}  
	else {
		echo "<script> alert('업데이트 실패')</script>";
	}

?>
<html>
	<head>
		<SCRIPT language="javascript">
			function goBack() {
				location.target = "frmain";
    			location.href = "counselMgtList.php";
    			
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
</html>