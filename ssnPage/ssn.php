<?php
 
	if (!include_once("./includes/dbconn.php")){
		echo "The config file could not be loaded";
	}
	include "./includes/AES.php";
	include "./includes/nc_config.php";

	$distID		= $_POST['distID']!=''?$_POST['distID']:$_GET['distID'];
	$ssn1		= $_POST['ssn1']!=''?$_POST['ssn1']:$_GET['ssn1'];
	$ssn2		= $_POST['ssn2']!=''?$_POST['ssn2']:$_GET['ssn2'];

	$ssn		= $ssn1.$ssn2;

	if ($distID == "" || $ssn == "") {
		echo "회원번호 또는 주민번호를 다시 확인해 주십시오";
		exit;
	}
	
	$SSN = encrypt($key, $iv, $ssn);

	$query = "SELECT COUNT(*) as CNT FROM tb_distSSN where dist_id='$distID' and government_id='$SSN' ";

	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	if ($row["CNT"] > 0) {
		
		$query = "update tb_distSSN set create_date = now() where dist_id='$distID' and government_id='$SSN' ";

	} else {

		$query = "insert into tb_distSSN  (dist_id, government_id) values ('$distID', '$SSN')";

	}

	$rseult3 = mysql_query($query) or die(mysql_error());

	//if (mysql_query($query) === TRUE) {
	if($rseult3){
		echo "T";
	} else {
		echo "F";
	}
	 
	mysql_close($connect);

?>
 