<?php session_start();?>
<?php 

 	include "./admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";
	
	$data = isset($_GET['data']) ? $_GET['data'] : '';
 

	if ($data == ''){
		 echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
   		 exit();
	}

	echo $data;

	$query = "DELETE FROM tb_distSSN WHERE `id` in ($data) ";

 	mysql_query($query) or die("Query Error".mysql_error());

 	logging($s_adm_id,'delete ssn '.$data);

	echo "<script>window.opener.location.reload();self.close();</script>";
?>