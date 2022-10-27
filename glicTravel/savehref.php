<?php

header('Content-Type:text/html;charset=UTF-8');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<?php

include "includes/config/config.php";
include "./includes/AES.php";

$distName = $_POST['custHref'];
$distID = $_POST['unicityId'];



//$array = array("Rose","Lili","Jasmine","Hibiscus","Tulip","Sun Flower","Daffodil","Daisy");
for($i = 0, $size = sizeof($distID); $i < $size; ++$i) { 

	$insertQuery="insert into tb_glic_href (
		member_no, 
		href
	
		) 
		value(
		'".$distID[$i]."',
		'".$distName[$i]."'
		
		)";
	
		mysql_query($insertQuery) or die("insertQuery Error");
   
}






	

	

?>

