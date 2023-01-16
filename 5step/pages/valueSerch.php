<?php
include "../inc/dbconn.php";
$crdateDate = $_POST['val'];


$query = "select id,checkValue,type,DATE_FORMAT(createdate,'%Y.%m.%d') as createDate from healthCheck where createDate = '".$crdateDate."'";
$result = mysql_query($query);


while($row = mysql_fetch_row($result)) {
	$createDate[]= $row[3];
	$valType[]= $row[2];
	$checkValue[]= $row[1];
}


/*
$query_row = mysql_fetch_array($result);


$id= $query_row["id"];
$checkValue= $query_row["checkValue"];
$type= $query_row["type"];
$createDate= $query_row["createDate"];
*/
//$createDate = substr($createDate,0,4).".".substr($createDate,4,2).".".substr($createDate,6,2);

echo(json_encode(array( "chkvalue" => $checkValue, "valType" => $valType, "createDate" => $createDate)));

?>