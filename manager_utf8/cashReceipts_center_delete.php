<?php
	include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";

	$type = $_POST['deleteVal'];

	$alert ='삭제 완료';
	$deleteQuery = "delete from tb_cashReceipts where cash_num in ($type)";
	echo $deleteQuery;
	mysql_query($deleteQuery) or die("Query Error");
	    
	    echo "<script>alert('$alert');
		  history.go(-1);</script>";
?>