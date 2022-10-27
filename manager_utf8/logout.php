<?
	session_start();
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";
	logging($s_adm_id,"Logged out");
	 
	session_destroy();
	echo "<script>top.location.replace('admin.php')</script>";
?>
