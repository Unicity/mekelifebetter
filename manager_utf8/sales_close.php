
<?php
    include "../dbconn_utf8.inc";       
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>마감관리</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src =" https://cdnjs.cloudflare.com/ajax/libs/fetch-jsonp/1.0.6/fetch-jsonp.min.js " > </script>
	</head>
	<body bgcolor="#FFFFFF">
		<form name="frmSearch" method="post" action="sales_close_search.php">	
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left"><b>마감관리</b></td>
					<td align="left" bgcolor=silver>
						날짜
						<input type="text" name="sDate" id="sDate" value="">
						<input type="submit" value="submit">
					</td>
				</tr>
			</table>   
		</form>
	</body>
</html>
