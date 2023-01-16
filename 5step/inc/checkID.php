<?php
header("Content-Type: text/html; charset=UTF-8");

if (!include_once("./dbconn.php")){
		echo "The config file could not be loaded";
	}
include "./function.php";
 

$q = isset($_GET["q"])? $_GET["q"] : "none";
if ($q == "none") {
	echo "none";
	return;
}

$q = clean($q);

$query = "SELECT COUNT(*) FROM User WHERE id = '".$q."';";

$result = mysql_query($query);

$v = mysql_fetch_row($result);

mysql_close();

echo $v[0];

?>