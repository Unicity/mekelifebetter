<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$member_no	= str_quote_smart(trim($member_no));
	$mode				= str_quote_smart(trim($mode));
		
	$query = "select * from tb_userinfo where member_no = $member_no";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$member_no = $list[member_no];
	$number = $list[number];
	$password = $list[password];
	$name = $list[name];
	$reg_jumin1 = $list[reg_jumin1];
	$reg_jumin2 = $list[reg_jumin2];
	$tax_number = $list[tax_number];
	$hpho1 = $list[hpho1];
	$hpho2 = $list[hpho2];
	$hpho3 = $list[hpho3];
	$reg_status = $list[reg_status];
	$sms_date = $list[sms_date];
	$sms_ma = $list[sms_ma];
	$member_kind = $list[member_kind];

	if ($mode == "SEND") {

		$str_hphone = $hpho1.$hpho2.$hpho3;
		//$str_hphone = "01052264159";

		$str_message = "유니시티코리아 회원가입이 완료되었습니다 회원번호 \"".$number."\"입니다 감사합니다";
		$query = "Insert into sms_msg (compkey, id, phone, callback, status, wrtdate, reqdate, msg, etc1, etc2, etc3) values
		('$member_no','tb_userinfo','$str_hphone', '0424821860', '0', sysdate(), sysdate(), '$str_message','WEB','재발송','$member_kind') ";
		$result_update = mysql_query($query);
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<script language="javascript">
	alert("문자가 발송 되었습니다.");
	self.close();
</script>
</head>
</html>
<?
		mysql_close($connect);
		exit;
	}
?>
<HTML>
<HEAD>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script language="javascript">
	function goSms() {

		document.frmSearch.mode.value = "SEND";
		document.frmSearch.action = "user_sms_ba.php";
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
	<TD align="left"><B>SMS 다시보내기</B></TD>
</TR>
</TABLE>
<FORM name="frmSearch" method="post">
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<input type="hidden" name="mode" value="">
<input type="hidden" name="member_no" value="<?echo $member_no?>">
<tr>
	<th>이름 : </th>
	<td><?echo $name?></td>
</tr>
<tr>
	<th>회원번호 :</th>
	<td><?echo $number?></td>
</tr>
<tr>
	<th>회원종류 :</th>
	<td><? if (trim($member_kind) == "D" ) { echo " FO 회원"; } else {echo "소비자 회원";} ?></td>
</tr>
<tr>
	<th>휴대전화번호 :</th>
	<td><?echo $hpho1?>-<?echo $hpho2?>-<?echo $hpho3?></td>
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
	<TD align="right" width="600" align="center" bgcolor=silver>
		<INPUT TYPE="button" VALUE="보내기" onClick="goSms();">	
		<INPUT TYPE="button" VALUE="닫 기" onClick="self.close();">	
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