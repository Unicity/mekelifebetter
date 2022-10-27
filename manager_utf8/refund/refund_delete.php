<?php
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../inc/common_function.php";
	include "../../AES.php";
	$type = $_POST['deleteVal'];

	$alert ='삭제 완료';
	$deleteQuery = "DELETE FROM  a, b using tb_refund_header as a left join tb_refund_line as b on a.RefundID = b.RefundID where b.RefundID in ($type)";
	//echo $deleteQuery;
	mysql_query($deleteQuery) or die("Query Error");
	    
	    echo "<script>alert('$alert');
		  history.go(-2);</script>";
?>
