<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$member_no	= str_quote_smart(trim($member_no));
	
	$member_no = trim($member_no);
	$member_no = str_replace("^", "'",$member_no);
	
	$query = "update internet_sales_warning set
				reg_status = '3',
				print_date = now()
				where No in $member_no";
	
	mysql_query($query);
	
	$query = "select * from internet_sales_warning where No in $member_no";
	$result = mysql_query($query);
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="x-frame-options" content="deny" />
		<link rel="stylesheet" href="inc/admin.css" type="text/css">
		<title>인터네서 판매 제보</title>
		<script language="javascript">
			function init() {
				print();
			}
		
			function f_close() {
				opener.check_data();
				self.close();
			}
		
		</script>
		<style type="text/css">
			@page a4sheet {size:15.0cm 29.7cm}
			.a4 {page:a4sheet; page-break-after:always}
		</style>
	</head>
	<body onload="init();">
	
<?php include "common_load.php" ?>

		<?	while($row = mysql_fetch_array($result)) { ?>
			<div class="a4">
				<!--[if gte ie 7]><br style='height:0; line-height:0'><![endif]-->
				<table border=0 width=100%>
					<tr>
						<td align="center">
							<table cellspacing="0" cellpadding="10" class="title">
								<tr>
									<td align="left"><b>인터넷 판매 제보</b></td>
								</tr>
							</table>
							<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#ffffff' bgcolor='#ffffff' bordercolor='#ffffff'>
								<tr>
									<td align='center'>
										<table border="1" cellspacing="1" cellpadding="2" class="in2">
										
											<tr>
												<th>회원번호 </th>
												<td><?echo $row[member_no]?></td>
											
											</tr>
											<tr>
												<th>회원성명</th>
												<td><?echo $row[member_name]?></td>
											</tr>
											<tr>
												<th>URL</th>
												<td><?echo $row[url]?></td>
											</tr>
											<?php if($row[url1] != null){?>
												<tr>
													<th>URL1</th>
													<td><?echo $row[url1]?></td>
												</tr>
											<?php }?>
											<?php if($row[url2] != null){?>
												<tr>
													<th>URL2</th>
													<td><?echo $row[url2]?></td>
												</tr>
											<?php }?>
											<?php if($row[url3] != null){?>
											
												<tr>
													<th>URL3</th>
													<td><?echo $row[url3]?></td>
												</tr>
											<?php }?>
											<?php if($row[url4] != null){?>
												<tr>
													<th>URL4</th>
													<td><?echo $row[url4]?></td>
												</tr>	
											<?php }?>	
											<?php if($row[dsc_sel] != null){?>
												<tr>
													<th>DSC</th>
													<td><?echo $row[dsc_sel]?></td>
												</tr>	
											<?php }?>	
												<tr>
													<th>신청일자</th>
													<td><?echo $row[applyDate]?></td>
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
							<!-- <input type="button" value="출력 하기" onclick="print();"> -->	
							<input type="button" value="닫 기" onclick="f_close();">	
						</td>
					</tr>
				</table>
			</center>
			</div>
		<?	} ?>

		<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
	
	</body>
</html>


<?
	mysql_close($connect);
?>