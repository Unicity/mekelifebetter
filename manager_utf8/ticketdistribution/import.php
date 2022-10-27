<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
?>

<?php

	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";
	 
	ini_set('auto_detect_line_endings', true);
 	
 	logging($s_adm_id,'upload ticket sales list');
 	
  	$filename=$_FILES["ticketFile"]["tmp_name"];
		 
	$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
 

	if($_FILES["ticketFile"]["size"] > 0 && in_array($_FILES['ticketFile']['type'],$mimes)){
		 
		$file = fopen($filename, "r");

	 	$insertQuery = "";
		$filedataArray = array();
	 	$s_adm_id = str_quote_smart_session($s_adm_id);
		
 		while($row = fgetcsv($file, filesize($filename))) {
		 	 echo count($row);

			if($row[0]) {
				// foreach($data as $row) {
				 	$insertQuery =  "insert into tb_ticket_master (`orderNo`, `eventName`, `baid`, `fullName`, `leader`, `contactNo`, `orderedQty`, `description`, `createdDate`, `creator`) values ('$row[0]', '$row[1]', '$row[2]',  '$row[3]', '$row[4]', '$row[5]', '$row[6]', '$row[7]',  now(), '$s_adm_id') ; ";
			}  else echo 'Invalid File:Please Upload CSV File';
			 
			$result = mysql_query($insertQuery);

			if(!$result){
	    		rollback(); // transaction rolls back
	   			echo "transaction rolled back because of the query ".$insertQuery;
   				exit;
			}	 
		}
			 
	} 

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<SCRIPT language="javascript">
			function goBack() {
				//parent.location.target = "frmain";
    			//parent.location.href = "unclaimedCommission_list.php";
    			opener.parent.frmain.location= "ticket_list.php";
    			self.close();
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
</html>