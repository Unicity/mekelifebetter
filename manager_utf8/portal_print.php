<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$member_no	= str_quote_smart(trim($flag_id));
	$mode				= str_quote_smart(trim($mode));

	$member_no = trim($member_no);
	$member_no = str_replace("^", "'",$member_no);


		$query = "update tb_portal set 
					reg_status = '3',
					print_date = now(),
					print_ma = '$s_adm_name'
			where id in $member_no";
		
		mysql_query($query);

		
	$query = "select * from tb_portal where id in $member_no";
	$result = mysql_query($query);
	
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
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
</HEAD>
<BODY onload="init();">

<?php include "common_load.php" ?>

<?	while($row = mysql_fetch_array($result)) { 
	$phone_number=$row[phone_number];
	$phonelength=strlen($phone_number);

	$str=$phone_number;
	if($phonelength == '11'){
		$phoneNum=substr($str,0,3)."-".substr($str,3,4)."-".substr($str,7,4);
	}else{
		$phoneNum=substr($str,0,2)."-".substr($str,2,4)."-".substr($str,6,4);
	}
	
?>

<div class="a4">
<!--[if gte IE 7]><br style='height:0; line-height:0'><![endif]-->
<table border=0 width=100%>
	<tr>
		<td align="center">
			<TABLE cellspacing="0" cellpadding="10" class="TITLE">
				<TR>
					<TD align="left"><B>포탈회원 국내거주 확인 </B></TD>
				</TR>
			</TABLE>
			<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
				<tr>
					<td align='center'>
						<TABLE border="0" cellspacing="1" cellpadding="2" class="IN2">
							<tr>
								<th>회원번호 : </th>
								<td><?echo $row[member_id]?></td>
							</tr>
							<tr>
								<th>회원성명 : </th>
								<td><?echo $row[member_name]?></td>
							</tr>
							<tr>
								<th>생년월일 : </th>
								<td><?echo $row[birthDay]?></td>
							</tr>
							<tr>
								<th>전화번호 : </th>
								<td><?echo $phoneNum?></td>
							</tr>
							<tr>
								<th>신청일자 : </th>
								<td><?echo $row[applyDate]?></td>
							</tr>
							<tr>
								<th>앞면 첨부파일 : </th>
								<td><img src="../pmbr/uploads/<?echo $row[photo_file]?>" width="50%"></td>
							</tr>
							<tr>
								<th>뒷면 첨부파일 : </th>
								<td><img src="../pmbr/uploads/<?echo $row[photo_file_back]?>" width="50%"></td>
							</tr>
						</TABLE>
					</td>
				</tr>
			</table>
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