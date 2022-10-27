<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$member_no	= str_quote_smart(trim($member_no));
	
	$query = "select * from internet_sales_warning where No = $member_no";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	
	$endmemo = $list[endmemo];

	mysql_query($query) or die("Query Error");
		
	

?>
<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="x-frame-options" content="deny" />
	<link rel="stylesheet" href="inc/admin.css" type="text/css">
	<title><?echo $g_site_title?></title>
		<script language="javascript">
		
			function goclose() {
				//opener.reload_user();
				self.close();
			}
		
		</script>
	</head>
	<body>
		<table border=0 width=100%>
			<tr>
				<td align="center">
					<table cellspacing="0" cellpadding="10" class="title">
						<tr>
							<td align="left"><b>처리결과</b></td>
						</tr>
					</table>
					<form name="frmsearch" method="post">
						<input type="hidden" name="member_no" value="<?echo $member_no?>">
						<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#ffffff' bgcolor='#ffffff' bordercolor='#ffffff'>
							<tr>
								<td align='center'>
									<table border="0" cellspacing="1" cellpadding="2" class="in">
										<tr>
											<th>처리결과 : </th>
											<td>
												<textarea name="memo" cols="60" rows="6" readonly="readonly"><?echo $endmemo?></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<br>
						<br>
					</td>
				</tr>
			</table>
			<table cellspacing="0" cellpadding="10" class="title">
				<tr>
					<td align="left">&nbsp;</td>
					<td align="right" width="600" align="center" bgcolor=silver>
						<input type="button" value="닫 기" onclick="goclose();">	
					</td>
				</tr>
			</table>
		</form>
	</body>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</html>
<?
	mysql_close($connect);
?>