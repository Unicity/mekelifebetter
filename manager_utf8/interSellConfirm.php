<?php
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	

	
	$member_no	= str_quote_smart(trim($member_no));
	$mode				= str_quote_smart(trim($mode));
	$endmemo				= str_quote_smart(trim($endmemo));
	
	if ($mode == "add") {
		$query = "update internet_sales_warning set
		endmemo = '$endmemo',
		reg_status = '4',
		confirm_date = now()
		where No = $member_no";
	
			
		mysql_query($query) or die("Query Error");
	
	}
	
		
	
?>

<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="x-frame-options" content="deny" />
	<link rel="stylesheet" href="inc/admin.css" type="text/css">
	<title><?echo $g_site_title?></title>
		<script language="javascript">
		
			function init() {
				alert("입력 되었습니다.");
				self.close();
				opener.location.reload();


								
				parent.frames[3].location = 'internetSell.php';	
			}
		
			function goin() {
		
				if(document.frmsearch.endmemo.value == "") {
					alert("처리결과를 입력하셔야 합니다.");
					document.frmsearch.endmemo.focus();
				    return;			
			    }
		
				document.frmsearch.mode.value = "add";
				document.frmsearch.submit();
			}
		
			function goclose() {
				//opener.reload_user();
				self.close();
			}
		
		</script>
	</head>
	<?	if ($mode == "add") { ?>
	<body onload="init();">
	<?	} else {?>
	<body>
	<?	}?>

		<table border=0 width=100%>
			<tr>
				<td align="center">
					<table cellspacing="0" cellpadding="10" class="title">
						<tr>
							<td align="left"><b>처리결과 입력</b></td>
						</tr>
					</table>
					<form name="frmsearch" method="post" action="interSellConfirm.php">
						<input type="hidden" name="member_no" value="<?echo $member_no?>">
						<input type="hidden" name="mode" value="">
						<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#ffffff' bgcolor='#ffffff' bordercolor='#ffffff'>
							<tr>
								<td align='center'>
									<table border="0" cellspacing="1" cellpadding="2" class="in">
										<tr>
											<th>처리내용  : </th>
											<td>
												<textarea name="endmemo" cols="60" rows="6"></textarea>
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
						<input type="button" value="자료 입력" onclick="goin();">	
						<input type="button" value="닫 기" onclick="goclose();">	
					</td>
				</tr>
			</table>
		</form>

		<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

	</body>
</html>
<?
	mysql_close($connect);
?>