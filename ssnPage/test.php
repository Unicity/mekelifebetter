<?php
if (!include_once("./includes/dbconn.php")){
	echo "The config file could not be loaded";
}
exit;

//중복등록 삭제

$query = "SELECT * FROM tb_distSSN where id > 211125 order by id desc";
$result = mysql_query($query) or die(mysql_error());

for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);

	$result2 = mysql_query("select count(*) from tb_distSSN where id > 211022 and dist_id = '$row[dist_id]' and government_id = '".$row['government_id']."'") or die(mysql_error());	
	$row2 = mysql_fetch_array($result2);

	if($row2[0] > 1){
		echo "$i : $row[id] , $row[dist_id], $row[government_id], $row2[0] <br>";
		//mysql_query("delete from tb_distSSN where id > 211022 and id != '".$row['id']."' and dist_id = '".$row['dist_id']."' and government_id = '".$row['government_id']."'") or die(mysql_error());	
	}
}
?>