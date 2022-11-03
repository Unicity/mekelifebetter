<?
	$distID = $_POST['distID']!=''?$_POST['distID']:$_GET['distID'];
//echo ">>>".$distID."<br/>";
	$distID = trim($distID);

	$url = 'https://hydra.unicity.net/v5a/customers?unicity='.$distID.'&expand=customer';
	$username = 'krWebEnrollment';
	$password = 'qKZ95XCrpzth5MgCpzMGQwXHw7ZMHg';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	
	$response = curl_exec($ch);
	 
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	//echo $response;

	if (($response !== false) && ($status == 200)) {
		echo $response;
	} else {

		$response = '{"items":[{"humanName":{"firstName":"Korea","lastName":"Unicity","fullName":"Korea Unicity","fullName@ko":"false"}}]}';
		echo $response;
	}

	curl_close($ch);

?>