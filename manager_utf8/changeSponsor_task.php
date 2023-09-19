<?php session_start();?>
<?php
	
	include "./admin_session_check.inc";
	include "./inc/global_init.inc";

 	$data = isset($_GET['data']) ? $_GET['data'] : '';
  
	if ($data == '') {
    echo "<script type='text/javascript'> alert('Wrong Access');self.close(); </script>";
    exit();
  }
  
 ?>
 
 <!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 
<script language="javascript">
  function approveOrReject(value) {
  
    var data = "<?php echo $data?>";

    data = atob(data);
    document.myForm.commissionData.value = data;
    document.myForm.processType.value = value;  
    document.myForm.action="change_process.php";
    document.myForm.submit();


  }
</script>
</head>
<body>

<?php include "common_load.php" ?>

<form name="myForm" method="post">
  <p>승인? 반려? </p>
  <input type="button" value="승인하기" onClick="approveOrReject('a');">
  <input type="button" value="반려하기" onClick="approveOrReject('r');">
  <input type="button" value="창닫기" onClick="self.close();">
  <input type="hidden" name="commissionData">
  <input type="hidden" name="processType">     
  
</form>
</body>
</html>