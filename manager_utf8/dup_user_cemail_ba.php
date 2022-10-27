<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$member_no = str_quote_smart(trim($member_no));
		
	$query = "select * from tb_userinfo_dup where member_no = $member_no";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$member_no = $list[member_no];
	$number = $list[number];
	$join_kind = $list[join_kind];
	$name = $list[name];
	$email = $list[email];
	$reg_jumin1 = $list[reg_jumin1];
	$reg_jumin2 = $list[reg_jumin2];
	$tax_number = $list[tax_number];
	$hpho1 = $list[hpho1];
	$hpho2 = $list[hpho2];
	$hpho3 = $list[hpho3];
	$reg_status = $list[reg_status];
	$email_date = $list[email_date];
	$email_ma = $list[email_ma];
	$member_kind = $list[member_kind];
	
?>
<HTML>
<HEAD>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script language="javascript">
	function goEmail() {
<?	if ($member_kind == "D") {?>
		document.frmSearch.action = "dup_send_email_d.php";
<?	} else {?>
		document.frmSearch.action = "dup_send_email_c.php";
<?	}?>
		document.frmSearch.submit();

	}
	
</script>
</HEAD>
<BODY>
<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>E-Mail 신청서 다시보내기</B></TD>
</TR>
</TABLE>
<FORM name="frmSearch" method="post">
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<input type="hidden" name="member_no" value="<?echo $member_no?>">
<tr>
	<th>이름 : </th>
	<td><?echo $name?></td>
</tr>
<!--tr>
	<th>주민등록번호 :</th>
	<td><?echo $reg_jumin1?>-<?echo $reg_jumin2?></td>
</tr-->
<tr>
	<th>회원종류 :</th>
	<td><? if (trim($member_kind) == "D" ) { echo " FO 회원"; } else {echo "소비자 회원";} ?></td>
</tr>
<tr>
	<th>공인인증서 :</th>
	<td>
	<?
		if ($join_kind == "Y") {
			echo "사용함";
		} else {
			echo "사용안함";
		}
	?>
	
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<br>
<br>
</td>
</tr>
</table>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left">&nbsp;</TD>
	<TD align="right" width="600" align="center" bgcolor="silver">
		<INPUT TYPE="button" VALUE="보내기" onClick="goEmail();">	
		<INPUT TYPE="button" VALUE="닫 기" onClick="self.close();">	
	</TD>
</TR>
</TABLE>
</center>
</form>
</body>
</html>
<?
	mysql_close($connect);
?>