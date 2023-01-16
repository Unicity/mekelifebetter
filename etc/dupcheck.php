<?php 

	$checkvalue = isset($_POST["checkvalue"])? $_POST["checkvalue"] : "";
 	
	if ($checkvalue != ""){
		isExistAPI($checkvalue);
		isPartnerExistAPI($checkvalue);
	}

	function isExistAPI($val) {

		//$val = urlencode($val);
		//$url = 'https://hydra.unicity.net/v5/customers.js?_httpMethod=HEAD&mainAddress_country=KR&taxTerms_taxId='.$year.$month.$date.$name;
		$url = 'https://hydra.unicity.net/v5/customers.js?_httpMethod=HEAD&mainAddress_country=KR&taxTerms_taxId='.$val;	
		$sendData = $val;	
		$return = 2	;
		 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			 
		$response = curl_exec($ch);
		
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		
		//APIlogger($memid, urlencode($sendData), 'check'.$status);	

		echo "<script> console.log('isExistAPI : ".$status." ');</script>"; 

		if (($response !== false) && ($status == 200) )  {
			$result = json_decode($response, true);
			echo 'isExistAPI<br>';
			echo $url.'<br>'; 
			print_r($result);
			echo '<br>';
			echo "<script> console.log('isExistAPI : ".$result."');</script>"; 

			if(isset($result["data"]["error"]["code"]) && ($result["data"]["error"]["code"] == "404" || $result["data"]["error"]["code"] == 404))
			{
				$return = 0	;
			} else {
				$return = 1	;
			}
			
			//APIlogger($memid, urlencode($sendData), $return);	 
		}
		
		curl_close($ch);
		 
		return $return;
	}

	function isPartnerExistAPI($val) {

		//$val = urlencode($val);

		//$url = 'https://hydra.unicity.net/v5/customers.js?_httpMethod=HEAD&mainAddress_country=KR&taxTerms_taxId='.$year.$month.$date.$name;
		$url = 'https://hydra.unicity.net/v5/customers.js?_httpMethod=HEAD&mainAddress_country=KR&spouse_taxTerms_taxId=' .$val;		
		
		$sendData = $year.$val;	
		$memid = 22222222;
		$return = 2	;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			 
			 
		$response = curl_exec($ch);
	 
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	 
	 	//APIlogger($memid, urlencode($sendData), 'check'.$status);	
		echo "<script> console.log('isPartnerExistAPI status : ".$status." ');</script>";
		if (($response !== false) && ($status == 200) ) {
			
			$result = json_decode($response, true);
			echo 'isPartnerExistAPI<br>';
			echo $url.'<br>'; 
			print_r($result);
			echo '<br>';
			if(isset($result["data"]["error"]["code"]) && ($result["data"]["error"]["code"] == "404" || $result["data"]["error"]["code"] == 404))
			{
				$return = 0	;
			} else {
				$return = 1	;
			}
			 
			// APIlogger($memid, urlencode($sendData), $return);	 
		}
			 
		curl_close($ch);
		  
		return $return;
	}
?>
