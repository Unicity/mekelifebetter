<?php session_start();?>
<?php 

 	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../inc/common_function.php";
	
	 
	$ticketNo = isset($_POST['ticketNo']) ? $_POST['ticketNo'] : '';
	$fullName = isset($_POST['fullName']) ? $_POST['fullName'] : '';
	$baid = isset($_POST['baid']) ? $_POST['baid'] : '';
	$contactNo = isset($_POST['contactNo']) ? $_POST['contactNo'] : '';
	$description = isset($_POST['description']) ? $_POST['description'] : '';

	if ($ticketNo == ''){
		 echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
   		 exit();
	}
	 
	 
	$records = explode(',', $ticketNo );
	//$query = "UPDATE unclaimedCommission SET `status` = '".$status."' ";
	 
	
	$ticketWhereClause = array();

	foreach($records as $record) {
 
		$values = explode('_', $record) ;
		array_push($ticketWhereClause, " orderNo = '".$values[0]."' and ticketNo = ".$values[1]);

	}


	for($i=0; $i<count($ticketWhereClause);$i++){
		$duplicationCheckQuery = "SELECT count(*) FROM tb_kit_detail WHERE ".$ticketWhereClause[$i];
	
		$duplicationCheckResult = mysql_query($duplicationCheckQuery);

		$row = mysql_fetch_array($duplicationCheckResult);
		if($row[0] != 0){
			echo "<script type='text/javascript'> alert('중복티켓번호가 존재하여 업데이트 불가합니다. ".$ticketWhereClause[$i]."');  </script>";
	   		exit();
		}
		$ticketQuery = "SELECT orderNo, ticketNo, '$baid', '$fullName', '$contactNo', '$description', '$s_adm_id', now() FROM tb_ticket_detail WHERE ".$ticketWhereClause[$i];
		echo $ticketQuery;

		$kitInsertQuery = "INSERT INTO tb_kit_detail $ticketQuery";

		echo $kitInsertQuery."<br>";
 		mysql_query($kitInsertQuery) or die("Query Error".mysql_error());

 		logging($s_adm_id,'kit updated '.$orderNo);
		
	}

	echo "<script>window.opener.location.reload();self.close();</script>"; 

	 
	/*

	$ticketWhereClause = rtrim($ticketWhereClause,",");
	
	$duplicationCheckQuery = "SELECT count(*) FROM tb_kit_detail WHERE ticketNo in ($ticketWhereClause)";
	
	$duplicationCheckResult = mysql_query($duplicationCheckQuery);

	$row = mysql_fetch_array($duplicationCheckResult);
	if($row[0] != 0){
		echo "<script type='text/javascript'> alert('중복티켓번호가 존재하여 업데이트 불가합니다.');self.close(); </script>";
   		exit();
	}

	$ticketQuery = "SELECT orderNo, ticketNo, '$baid', '$fullName', '$contactNo', '$description', '$s_adm_id', now() FROM tb_ticket_detail WHERE ticketNo in ($ticketWhereClause)";

	echo $ticketQuery;

	$kitInsertQuery = "INSERT INTO tb_kit_detail $ticketQuery";

	echo $kitInsertQuery."<br>";
 	mysql_query($kitInsertQuery) or die("Query Error".mysql_error());

 	logging($s_adm_id,'kit updated '.$orderNo);

	echo "<script>window.opener.location.reload();self.close();</script>"; */
?>