<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
?>

<?php
	include "./admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";
	ini_set('auto_detect_line_endings', true);
	
 	logging($s_adm_id,'upload ssn list');

 	 
  	if(isset($_POST["submit"]))
	{
		  
		$filename=$_FILES["ssnFile"]["tmp_name"];

		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');

		if($_FILES["ssnFile"]["size"] > 0 && in_array($_FILES['ssnFile']['type'],$mimes)){
			  
			$file = fopen($filename, "r");

		 	$insertQuery = "";
			$row = 1;
		 	$filedataArray = array();
		 	 
			while (!feof($file))  
			{	
			 
				 $filedataArray[] = fgetcsv($file);
			}
			 
		 	$insertQuery =  "INSERT INTO `tb_distSSN` (`dist_id`, `government_id`, `create_date`) VALUES ";
			foreach(array_slice($filedataArray,0) as $row){
	 		 	 
				$governmentID	= encrypt($key, $iv, $row[1]);
				$createDate = isset($row[2]) ? $row[2] : 'now()';
				 
				$values = "('$row[0]', '$governmentID', '".$createDate."') ";

				$query  = $insertQuery.$values;
				logging($s_adm_id,$insertQuery);
			
				//echo $insertQuery;
				$result = mysql_query($query);
				 
				echo($query);
				if(!$result){
					rollback(); // transaction rolls back
		   			echo "transaction rolled back because of the query ".$query;
		    		exit;
				}
				
			}
			
			mysql_close($connect);	 
			 
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
    			opener.parent.frmain.location= "ssn_list.php";
    		//	self.close();
			}
		</SCRIPT>
	</head>
	<body onload="goBack()">
	</body>
	
</html>