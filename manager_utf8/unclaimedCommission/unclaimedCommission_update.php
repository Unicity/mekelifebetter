<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";

	$status = isset($_POST['status']) ? $_POST['status'] : "";
	$query = "update unclaimedCommission set ";
	$s_adm_id = str_quote_smart_session($s_adm_id);
	
	$query .= "status='".$status."'";
	$query .= ", LastUpdateDate = now()";
	$query .= ", LastUpdator = '".$s_adm_id."'";

	if ($status == "20" || $status == "40") {	
		$bankCode = isset($_POST['bankCode']) ? $_POST['bankCode'] : "";
		$newAccountNo = isset($_POST['newAccountNo']) ? $_POST['newAccountNo'] : "";
		$newAccountHolder = isset($_POST['newAccountHolder']) ? $_POST['newAccountHolder'] : "";
		$comment = isset($_POST['comment']) ? $_POST['comment'] : "";
		$commissionDate = isset($_POST['commissionDate']) ? $_POST['commissionDate'] : "";
		$member_no = isset($_POST['member_no']) ? $_POST['member_no'] : "";

		$newAccountNo = encrypt($key, $iv, $newAccountNo) ;

		if($bankCode != "") {
			$query .= ", newBankCode = '".$bankCode."'";
		}

		if($newAccountNo != ""){
			$query .= ", newAccountNo = '".$newAccountNo."'";
		}

		if($newAccountHolder != ""){
			$query .= ", newAccountHolder = '".$newAccountHolder."'";
		}

		if($comment != "" ){
			$query .= ", comment = '".$comment."'";
		}
	} 
	
	$query .= " where commissionDate='".$commissionDate."' and id='".$member_no."'";

	  

	$result = mysql_query($query); 
	
	if($result) {
		 echo "<script> alert('업데이트 완료')</script>";
	}  
	else {
		echo "<script> alert('업데이트 실패')</script>";
	}
	logging($s_adm_id,'status update unclaimed Commission to'.$status.' for '.$member_no);

?>
<html>
	<head>
		<SCRIPT language="javascript">
			function goBack() {
				location.target = "frmain";
    			location.href = "unclaimedCommission_list.php";
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
</html>