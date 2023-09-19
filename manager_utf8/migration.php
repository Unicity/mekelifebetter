<?php
//ini_set('display_errors', 1);
include "../dbconn_utf8.inc";
include "./inc/common_function.php";

if(getRealClientIp() != "121.190.224.85") exit;

exit;
include "../AES.php";
$result = mysql_query("select * from tb_admin") or die(mysql_error());	
for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);

	$de_pass = decrypt($key, $iv, $row['EN_PASS']);
	$new_pass = password_hash($de_pass, PASSWORD_DEFAULT);
	//$new_pass = hash('sha256', $de_pass);

	//otp용 secret발급
	$optpass = makeOptPass();

	echo $row['id']." : ".$row['passwd']." -> ".$de_pass." -> ".$new_pass." : ".$optpass."<br>";
	
	ob_flush();
	flush();


	mysql_query("update tb_admin set passwd = '".$new_pass."', optpass='".$optpass."' where id='".$row['id']."'") or die(mysql_error());
}
	




?>