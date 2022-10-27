<?php session_start();?>
<?php 

 	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../inc/common_function.php";
	
	 
	$orderNo = isset($_POST['orderNo']) ? $_POST['orderNo'] : '';
	$eventName = isset($_POST['eventName']) ? $_POST['eventName'] : '';
	$fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
	 
	$baid = isset($_POST['baid']) ? $_POST['baid'] : '';
	$leader = isset($_POST['leader']) ? $_POST['leader'] : '';
	$contactNo = isset($_POST['contactNo']) ? $_POST['contactNo'] : '';
	$orderedQty = isset($_POST['orderedQty']) ? $_POST['orderedQty'] : '';
	$description = isset($_POST['description']) ? $_POST['description'] : '';

	if ($orderNo == '' || $eventName=='' || $fullName=='' || $baid =='' || $leader=='' || $contactNo=='' || $orderedQty ==''){
		 echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
   		 exit();
	}
	
	$ticketPrefix = isset($_POST['ticketPrefix']) ? $_POST['ticketPrefix'] : '';

	$ticketNos = array();
	for($i=0; $i<$orderedQty; $i++){
		$ticketNo = isset($_POST['ticketNo'.$i]) ? $_POST['ticketNo'.$i] : '';
		 
		if ($ticketNo != ''){
			array_push($ticketNos,$ticketNo);
		}
	}


	if ( count($ticketNos) >0 && (count($ticketNos) != $orderedQty) ){
		echo "<script type='text/javascript'> alert('티켓 저장 숫자가 상이하여 업데이트 불가합니다.');self.close(); </script>";
   		exit();
	}
	 
	$ticketWhereClause = rtrim($ticketWhereClause,",");
	
	$duplicationCheckQuery = "SELECT count(*) FROM tb_ticket_detail WHERE orderNo= '$orderNo'";
	$duplicationCheckResult = mysql_query($duplicationCheckQuery);

	$row = mysql_fetch_array($duplicationCheckResult);
	if($row[0] != 0){
		echo "<script type='text/javascript'> alert('중복주문이 존재하여 업데이트 불가합니다.');self.close(); </script>";
   		exit();
	}

	$addOrderQuery = "INSERT INTO tb_ticket_master (`orderNo`, `eventName`, `baid`, `fullName`, `leader`,`contactNo`,`orderedQty`,`description`,`createdDate`,`creator`) VALUES ('$orderNo','$eventName','$baid','$fullName', '$leader','$contactNo','$orderedQty','$description',now(),'$s_adm_id') ;";

 	mysql_query($addOrderQuery) or die("Query Error".mysql_error());

 	if (count($ticketNos) > 0){
 		$values = "";
 		$ticketInsertQuery = "INSERT INTO tb_ticket_detail (`orderNo`, `ticketPrefix`, `ticketNo`, `baid`, `fullName`, `contactNo`, `qty`, `description`, `creator`, `createdDate`) VALUES ";
 		for($i=0; $i<count($ticketNos); $i++){
 			$values .= " ('$orderNo','$ticketPrefix' ,'$ticketNos[$i]', '$baid', '$fullName', '$contactNo', 1, '$description', '$s_adm_id', now()) ,";
 		}
 		$values = rtrim($values, ",");

 		$ticketInsertQuery .= $values;

 		mysql_query($ticketInsertQuery) or die("Query Error".mysql_error());
 	}

 	logging($s_adm_id,'add new ticket order '.$orderNo);

	echo "<script>window.opener.location.reload();self.close();</script>"; 
?>