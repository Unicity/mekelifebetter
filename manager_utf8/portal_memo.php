<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$member_no	= str_quote_smart(trim($member_no));
	$memo_kind	= str_quote_smart(trim($memo_kind));
	$flag_id	= str_quote_smart(trim($flag_id));
	$mode				= str_quote_smart(trim($mode));
	$memo				= str_quote_smart(trim($memo));
	

	if ($mode == "add") {

		if ($memo_kind == "r") {
			$query = "update tb_portal set 
						memo = '$memo',
						reg_status = '9',
						reject_date = now(),
						reject_ma = '$s_adm_name'
						where member_id = '$member_no'
						and id = '$flag_id'";
						
		} else {
			$query = "update tb_portal set 
						memo = '$memo',
						reg_status = '8',
						wait_date = now(),
						wait_ma = '$s_adm_name'
						where member_id = '$member_no'
						and id = '$flag_id'";
		}
			
		mysql_query($query) or die("Query Error");

	}
		
	

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script language="javascript">

	function init() {
		alert("입력 되었습니다.");
	}

	function goIn() {

		if(document.frmSearch.memo.value == "") {
			alert("사유를 입력하셔야 합니다.");
			document.frmSearch.memo.focus();
		    return;			
	    }

		document.frmSearch.mode.value = "add";

		document.frmSearch.submit();
	}

	function goClose() {
		//opener.reload_user();
		self.close();
	}

</script>
</HEAD>
<?	if ($mode == "add") { ?>
<BODY onload="init();">
<?	} else {?>
<BODY>
<?	}?>

<?php include "common_load.php" ?>

<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>거부 또는 보류 사유 입력</B></TD>
</TR>
</TABLE>
<FORM name="frmSearch" method="post" action="portal_memo.php">
<input type="hidden" name="member_no" value="<?echo $member_no?>">
<input type="hidden" name="memo_kind" value="<?echo $memo_kind?>">
<input type="hidden" name="flag_id" value="<?echo $flag_id?>">
<input type="hidden" name="mode" value="">
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>사유 : </th>
	<td>
		<textarea name="memo" cols="60" rows="6"><?echo $memo?></textarea>
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
	<TD align="right" width="600" align="center" bgcolor=silver>
		<INPUT TYPE="button" VALUE="자료 입력" onClick="goIn();">	
		<INPUT TYPE="button" VALUE="닫 기" onClick="goClose();">	
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