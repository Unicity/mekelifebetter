<?php
session_start();
?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";

 	 logging($s_adm_id,'upload unclaimedCommission list');

	function bankcodeChecker($bankcode) {
		$thecode = $bankcode ; 
		if(strlen($thecode) == 2) {
			$thecode =  "0".$thecode;
		} else if(strlen($thecode) == 1) {
			$thecode = "00".$thecode;
		} else {
			$thecode = $thecode;
		}

		return $thecode;
	}
    $type = $_POST["type"];
  	if(isset($_POST["submit"]))
	{
		 
		$filename=$_FILES["commissionFile"]["tmp_name"];
		 
		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');

		if($_FILES["commissionFile"]["size"] > 0 && in_array($_FILES['commissionFile']['type'],$mimes)){
			
			$file = fopen($filename, "r");

		 	$insertQuery = "";
			$row = 1;
		 	$filedataArray = array();
		 	$s_adm_id = str_quote_smart_session($s_adm_id);
			while (($data = fgetcsv($file)) !== FALSE)
			{	
				 $filedataArray[] = $data;
			}
		 	 
			foreach(array_slice($filedataArray,1) as $row){
	 			if($type == "new" && $row[0] != "") {
					$accountNo	= encrypt($key, $iv, $row[5]);
					$bankcode = bankcodeChecker($row[4]);
					$insertQuery =  "insert into unclaimedCommission (`commissionDate`, `id`, `memberName`, `dob`, `BankCode`, `AccountNo`, `Amount`, `status`, `errorCode`, `CreatedDate`, `LastUpdateDate`, `Creator`) values ('$row[0]', '$row[1]', '$row[2]',  '$row[3]', '$bankcode', '$accountNo', '$row[6]', '10', '$row[7]', now(), now(), '$s_adm_id') ; ";
					//echo mb_detect_encoding($row[2]).'<br>';
					//echo $insertQuery.'<br>';
				} else if ($type == "approve" && $row[0] != ""){
					$insertQuery = "update unclaimedCommission set `status`='30' where `id`='$row[1]' and `commissionDate` = '$row[0]'";
				} else if ($type == "reject" && $row[0] != "") {
					$insertQuery = "update unclaimedCommission set `status`='40' where `id`='$row[1]' and `commissionDate` = '$row[0]'";
				}
					$result = mysql_query($insertQuery);

					if(!$result){
		    			rollback(); // transaction rolls back
		   				echo "transaction rolled back because of the query ".$insertQuery;
		    			//exit;
					}	 

			}
			 
		}
		else
			echo 'Invalid File:Please Upload CSV File';
	} else {
		echo 'no File';
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
    			opener.parent.frmain.location= "unclaimedCommission_list.php";
    			self.close();
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
	
</html>