<?php session_start();?>
<?php 

 	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../inc/common_function.php";
	
	$processType = isset($_POST['processType']) ? $_POST['processType'] : '';
	$commissionData = isset($_POST['commissionData']) ? $_POST['commissionData'] : '';
	$s_adm_id = str_quote_smart_session($s_adm_id);

	if ($processType == '' || $commissionData == ''){
		 echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
   		 exit();
	}

	$status = $processType=='a' ? '30' : '40';
	$records = explode(',', $commissionData );
	//$query = "UPDATE unclaimedCommission SET `status` = '".$status."' ";
	
	foreach($records as $record) {
		$query = "UPDATE unclaimedCommission SET `status` = '".$status."', LastUpdateDate = now(), LastUpdator = '$s_adm_id' ";
		$cols = explode("_", $record);
		$where = " where `id` = '$cols[0]' and `CommissionDate`='$cols[1]' ; ";
		$query .= $where;

		 
  		mysql_query($query) or die("Query Error".mysql_error());

 		logging($s_adm_id,'unclaimedCommission '.$status.' '.$where);

	}

	
 //	mysql_query($query) or die("Query Error".mysql_error());

// 	logging($s_adm_id,'unclaimedCommission '.$status.' '.$where);

 	echo "<script>window.opener.location.reload();self.close();</script>";
?>