<?php session_start();?>
<?php 

 	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../inc/common_function.php";
	
	 
	$orderNo = isset($_POST['orderNo']) ? $_POST['orderNo'] : '';
	$fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
	$baid = isset($_POST['baid']) ? $_POST['baid'] : '';
	$contactNo = isset($_POST['contactNo']) ? $_POST['contactNo'] : '';
	$description = isset($_POST['description']) ? $_POST['description'] : '';

	if ($orderNo == ''){
		 echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
   		 exit();
	}

	 
	$records = explode(',', $orderNo );
	//$query = "UPDATE unclaimedCommission SET `status` = '".$status."' ";
	echo $orderNo."<br>";
	
	$ticketWhereClause = "";

	foreach($records as $record) {

		 $ticketWhereClause .= "'".$record."' ,"; 

	}

	$ticketWhereClause = rtrim($ticketWhereClause,",");
	
	$duplicationCheckQuery = "SELECT count(*) FROM tb_kit_detail WHERE orderNo in ($ticketWhereClause)";
	$duplicationCheckResult = mysql_query($duplicationCheckQuery);

	$row = mysql_fetch_array($duplicationCheckResult);
	if($row[0] != 0){
		echo "<script type='text/javascript'> alert('중복주문이 존재하여 업데이트 불가합니다.');self.close(); </script>";
   		exit();
	}

	$ticketQuery = "SELECT orderNo, ticketNo, '$baid', '$fullName', '$contactNo', '$description', '$s_adm_id', now() FROM tb_ticket_detail WHERE orderNo in ($ticketWhereClause)";

	echo $ticketQuery;

	$kitInsertQuery = "INSERT INTO tb_kit_detail $ticketQuery";

	echo $kitInsertQuery."<br>";
 	mysql_query($kitInsertQuery) or die("Query Error".mysql_error());

 	logging($s_adm_id,'kit updated '.$orderNo);

	echo "<script>window.opener.location.reload();self.close();</script>"; 
?>