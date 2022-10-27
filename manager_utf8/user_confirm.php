<?include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$member_no			= str_quote_smart(trim($member_no));
	$re_member_no		= str_quote_smart(trim($member_no));
	$member_no = str_replace("^", "'",$member_no);
		
	$query = "select * from tb_userinfo where member_no in $member_no";
	$result = mysql_query($query);

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script language="javascript">
	function goIn() {
		
		bDelOK = confirm("선택하신 회원의 본인여부가 다 확인 되었습니까?.");
		
		if ( bDelOK ==true ) {
			document.frmSearch.action = "user_confirm_db.php";			
			document.frmSearch.submit();
		} else {
			return;
		}

	}


	function f_close() {
		opener.check_data();
		self.close();
	}
</script>
</HEAD>
<BODY>
<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 회원 가입 (회원 본인 확인) </B></TD>
</TR>
</TABLE>
<FORM name="frmSearch" method="post">
<input type="hidden" name="member_no" value="<?echo $re_member_no?>">

<?	while($row = mysql_fetch_array($result)) { ?>

<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>이름 : </th>
	<td><?echo $row[name]?></td>
</tr>
<tr>
	<th>주민등록번호 :</th>
	<td><?echo $row[reg_jumin1]?>-<?echo $row[reg_jumin2]?></td>
</tr>
<tr>
	<th>회원종류 :</th>
	<td><? if (trim($row[member_kind]) == "D" ) { echo " FO 회원"; } else {echo "소비자 회원";} ?></td>
</tr>
<tr>
	<th>주 소 :</th>
	<td><?echo $row[zip]?><br>
		<?echo $row[addr]?>
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<br>
<br>
<?
	}
?> 
</td>
</tr>
</table>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left">&nbsp;</TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		<INPUT TYPE="button" VALUE="본인 확인" onClick="goIn();">	
		<INPUT TYPE="button" VALUE="닫 기" onClick="f_close();">	
	</TD>
</TR>
</TABLE>
</center>
</form>
<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</body>
</html>

<?
	mysql_close($connect);
?>