<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";

 	$type = $_GET['type'];

	echo $type;
 ?>
 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form action="import.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="type" value="<?php echo $type?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
  <table width="600" border="1">
  	<tr>
  		<td>
  			파일 형식   <br> CSV Only
  		</td>
  		<td colspan="2">
  			데이터 순  <br> Commission Date, BA#, Name, DoB, BankCode, AccountNo, Amount, Error Code
  		</td>
  		
  	</tr>
  	<tr>
  		<td>File Name:</td>
  		<td><input type="file" name="commissionFile" id="commissionFile" accept=".csv" /></td>
  		<td><input type="submit" name="submit" value="Upload" /></td>

  	</tr>
  </table>
  </form>

  <?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

  </body>
  </html>
	 