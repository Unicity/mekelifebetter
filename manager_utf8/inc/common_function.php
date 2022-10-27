<?php
	function getRealClientIp(){
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP')) {
			$ipaddress = getenv('HTTP_CLIENT_IP');
		} else if(getenv('HTTP_X_FORWARDED_FOR')) {
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		} else if(getenv('HTTP_X_FORWARDED')) {
			$ipaddress = getenv('HTTP_X_FORWARDED');
		} else if(getenv('HTTP_FORWARDED_FOR')) {
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		} else if(getenv('HTTP_FORWARDED')) {
			$ipaddress = getenv('HTTP_FORWARDED');
		} else if(getenv('REMOTE_ADDR')) {
			$ipaddress = getenv('REMOTE_ADDR');
		} else {
			$ipaddress = 'unknown';
		} 
		return $ipaddress;
	}

	function logging($id, $message){
		
		$message = str_replace("'","",$message);
		
		$Insertquery = "insert into tb_user_log (adminId, actionType, ip) values ('".$id."', '".$message."', '".getRealClientIp()."')";

		mysql_query($Insertquery) or die("Query Error".mysql_error());
	
	}

	function logging_admin($id, $message){
		
		$message = str_replace("'","",$message);
		
		$Insertquery = "insert into tb_admin_log (adminId, actionType, ip, createdDate) values ('".$id."', '".$message."', '".getRealClientIp()."', now())";

		mysql_query($Insertquery) or die("Query Error".mysql_error());
	
	}

	function APIlogger($id, $input, $output){
		
		$input = str_replace("'","",$input);
		$output = str_replace("'","",$output);
		
		$Insertquery = "insert into tb_api_log (member_no, send_data, return_data) values ('".$id."', '".$input."', '".$output."')";

		mysql_query($Insertquery) or die("Query Error".mysql_error());
	
	}

	function msg_err($str){
		echo "<script>
			  alert('".$str."');
			  history.back();
			  </script>";
		exit;
	}

	
?>