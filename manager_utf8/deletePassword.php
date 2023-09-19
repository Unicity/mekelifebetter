<?php
    include "../dbconn_utf8.inc";
	
	
		
	$query = "select * from reset_password where 1 = 1 and flag = 'N' and new_member_no=0 ";
	$result2 = mysql_query($query,$connect);


	while($obj = mysql_fetch_object($result2)) {
		$memberNo =$obj-> member_no;
		
		$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$memberNo.'&expand=customer';

		$ch = curl_init();                                 
		curl_setopt($ch, CURLOPT_URL, $url);         
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$responseInfo = curl_exec($ch);
		$json_resultInfo = json_decode($responseInfo, true);
			
		$href = $json_resultInfo['items'][0]['href']."<br/>";
		$phone = $json_resultInfo['items'][0]['mobilePhone']."<br/>";
		$uniId = $json_resultInfo['items'][0]['unicity']."<br/>";
		//echo $href;
		//echo $uniId;
		curl_close($ch);

		updateInfo($href,$uniId);

		}
	
	
	
	function updateInfo($href,$uniId){
		echo $href;
		echo $uniId;
		$update = "update reset_password set member_href= '$href',new_member_no='$uniId' where member_no ='$uniId' and new_member_no=0";
	
		mysql_query($update) or die("Query Error");
		echo $update;
	}
?>


<html>
	<head>
	</head>
	<body>
		<form>
			<div>
				<input type='button' value='시작'>
			</div>		
		</form>		
	</body>			
</html>
