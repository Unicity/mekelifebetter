<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";

 ?>
 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<form action="import.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
  <table width="850" border="1">
  	<tr>
  		<td>
  			파일 형식   <br> CSV Only
  		</td>
  		<td colspan="2">
  			데이터 순  <br> 주문번호(10), 이벤트명(20), 회원번호(15), 회원명(50), 그룹(30), 연락처(15), 주문수량(숫자), 설명
  		</td>
  		
  	</tr>
  	<tr>
  		<td>File Name:</td>
  		<td><input type="file" name="ticketFile" id="ticketFile" accept=".csv" /></td>
  		<td><input type="submit" name="submit" value="Upload" /></td>

  	</tr>
  </table>
  </form>
  </body>
  </html>
	 