<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";

	$s_adm_id = str_quote_smart_session($s_adm_id);

	logging($s_adm_id,'save ticket (ticket_detail_save.php)');

	$orderNo = str_quote_smart(trim($orderNo));
 	$orderedQty = str_quote_smart(trim($orderedQty));
 	$fullName = str_quote_smart(trim($fullName));
 	$baid = str_quote_smart(trim($baid));
 	$contactNo = str_quote_smart(trim($contactNo));
 	$description = str_quote_smart(trim($description));
 	$chkTicketPanel = str_quote_smart(trim($chkTicketPanel));
 	
 	$ticketPrefix = str_quote_smart(trim($ticketPrefix));

 	 

 	$ticketValue1 = "";
 	$ticketValue2 = "";
 	$tickets = array();
 	if ($orderedQty == 1){
 		$ticketValue1 = str_quote_smart(trim($ticket0)); 
 	} else if($orderedQty == 2){
 		$ticketValue1 = str_quote_smart(trim($ticket0)); 
 		$ticketValue2 = str_quote_smart(trim($ticket1)); 
	} else {
		if ($chkTicketPanel == 'on'){
			for($i=0; $i<$orderedQty; $i++){
				$ticketNo = isset($_POST['ticket'.$i]) ? $_POST['ticket'.$i] : '';
		 
				if ($ticketNo != ''){
					array_push($tickets,$ticketNo);
				}
			}
		} else {
			$ticketValue1 = str_quote_smart(trim($startNo)); 
 			$ticketValue2 = str_quote_smart(trim($endNo)); 	
		}
		
	}
	$ticketCheckQuery = "";
	if(count($tickets) > 0) {
		for($i=0; $i<count($tickets); $i++){
 			$ticketNos .= " '$tickets[$i]',";
 		}
 		$ticketNos = rtrim($ticketNos, ",");
 		$ticketCheckQuery = "SELECT COUNT(*) FROM tb_ticket_detail WHERE ticketPrefix = '$ticketPrefix' and ticketNo IN ($ticketNos)";
	}else {
		if($orderedQty == 1) {
			$ticketCheckQuery = "SELECT COUNT(*) FROM tb_ticket_detail WHERE ticketPrefix = '$ticketPrefix' and ticketNo IN ('$ticketValue1')";
		}
		else {
			$ticketCheckQuery = "SELECT COUNT(*) FROM tb_ticket_detail WHERE ticketPrefix = '$ticketPrefix' and ticketNo IN ('$ticketValue1','$ticketValue2')";
		}
		
	}
	
//	echo $ticketCheckQuery;
//	exit();

	$ticketCheckQueryResult = mysql_query($ticketCheckQuery);
	$existingTicket = mysql_fetch_array($ticketCheckQueryResult);
	 
	if($existingTicket[0] != 0){
		echo "<script> alert('주문번호와 티켓번호가 중복입니다.'); location.target='frmain'; location.href='./ticket_list.php';</script>";
		exit();
	}

	$ticketInsertQuery = "INSERT INTO tb_ticket_detail (`orderNo`, `ticketPrefix`, `ticketNo`, `baid`, `fullName`, `contactNo`, `qty`, `description`, `creator`, `createdDate`) values ";
	$values = "";
	if ($orderedQty == 1){
 		$values = "('$orderNo','$ticketPrefix', '$ticketValue1','$baid','$fullName','$contactNo', 1,'$description','$s_adm_id',now())";
 	} else if ($orderedQty == 2){
 		$values = "('$orderNo','$ticketPrefix','$ticketValue1','$baid','$fullName','$contactNo', 1,'$description','$s_adm_id',now()) ";
 		$values .= ", ('$orderNo','$ticketPrefix','$ticketValue2','$baid','$fullName','$contactNo', 1,'$description','$s_adm_id',now()) ";
 	} 
 	else {
 		if ($chkTicketPanel == 'on'){
 			for($i=0; $i<count($tickets); $i++){
 				$values .= "('$orderNo','$ticketPrefix','$tickets[$i]','$baid','$fullName','$contactNo', 1,'$description','$s_adm_id',now()) ,"; 
 			}	
 		} else {
 			for($i=$ticketValue1; $i <= $ticketValue2; $i++){
	 			$values .= " ('$orderNo','$ticketPrefix','$i','$baid','$fullName','$contactNo', 1,'$description','$s_adm_id',now()) ,";	
	 		}

 		}
 		$values = rtrim($values,",");
	}
	$ticketInsertQuery .= $values." ;";
	echo $ticketInsertQuery;
	 
	$result = mysql_query($ticketInsertQuery);

			if(!$result){
	    		rollback(); // transaction rolls back
	   			echo "transaction rolled back because of the query ".$ticketInsertQuery;
   				exit;
			}	 

	mysql_close($connect);
 	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<SCRIPT language="javascript">
			function goBack() {
				//parent.location.target = "frmain";
    			//parent.location.href = "unclaimedCommission_list.php";
    			location.target=  "frmain";
    			location.href = "ticket_list.php";
    		 
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
</html>