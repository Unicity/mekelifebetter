<?php
session_start();
?>

<?php
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";

	 
	$s_adm_id = str_quote_smart_session($s_adm_id);

	$id = str_quote_smart(trim($id));
	$member_no = str_quote_smart(trim($member_no));
	$member_name = str_quote_smart(trim($member_name));
	$received_no = str_quote_smart(trim($received_no));
	$counsel_Type1 = str_quote_smart(trim($counsel_Type1));
	$location = str_quote_smart(trim($location));
	$counsel_Type2 = str_quote_smart(trim($counsel_Type2));
	$description = trim($description);
	$transferred_dept = str_quote_smart(trim($transferred_dept));
	$transferred_staff = str_quote_smart(trim($transferred_staff));
	$short_comment = trim($short_comment);
	$status = trim($status);

	 
	$query = "update tb_counsel 
			  set
				`member_no`   = '$member_no'
				,`name` 	  = '$member_name'
				,`contact_no` = '$received_no'
				,`counsel_type1` = '$counsel_Type1'
				,`counsel_type2` = '$counsel_Type2'
				,`location` = '$location'
				,`transferred_dept` = '$transferred_dept'
				,`transferred_staff` = '$transferred_staff'
				,`transferred_date` = now()
				,`short_comment` = '$short_comment'
				,`description` = '$description'
				,`updator` = '$s_adm_id'
				,`updatedate` = now()
				,`status` = '$status'
				where `id` = $id";
	 

$result = mysql_query($query); 
	
	if($result) {
		 echo "<script> alert('Successfully Modified')</script>";



	}  
	else {
		echo "<script> alert('Modification Failed')</script>";
	}

?>
<html>
	<head>
		<SCRIPT language="javascript">
			function goBack() {
				//location.target = "frmain";
    			//location.href = "counselMgtList.php";
    			opener.location.reload(true);
    			window.close();
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
</html>