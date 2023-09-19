<?
	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";

	$mode			= str_quote_smart(trim($mode));
	$idxfield = str_quote_smart(trim($idxfield));
	$qry_str	= str_quote_smart(trim($qry_str));
	$parent		= str_quote_smart(trim($parent));

?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE>:::::유니시티 관리자 시스템:::::</TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="code_list.php";
		document.frm.submit();
	}

	function goIn() {
					
		if (document.frm.code.value == "") {
			alert("코드를 입력하세요.");
			document.frm.code.focus();
			return;
		}
		
		if (document.frm.name.value == "") {
			alert("내용을 입력하세요.");
			document.frm.name.focus();
			return;
		}
				
		document.frm.target = "frhidden";
		document.frm.action = "code_db.php";
		document.frm.submit();
		
	}

		
//-->
</SCRIPT>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<br>
<br>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<b><a href="code_list.php?parent=goods">제품군</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=brand">브랜드</a></b>&nbsp;&nbsp;
					<!--<b><a href="code_list.php?parent=qna">고객의견분류</a></b>&nbsp;&nbsp;-->
					<b><a href="code_list.php?parent=ask">문의분류</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=pds">자료실분류</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=job">직업</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=bank">은행</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=bank3">3자리은행코드</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=mail">메일계정</a></b>&nbsp;&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<form name='frm' method='post' action='code_db.php'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?if ($parent == 'goods') {?>
	<TD align="left"><B>제품군</B></TD>
<?} else if ($parent == 'brand') {?>
	<TD align="left"><B>브랜드</B></TD>
<?} else if ($parent == 'qna') {?>
	<TD align="left"><B>고객의견함분류</B></TD>
<?} else if ($parent == 'ask') {?>
	<TD align="left"><B>제품문의분류</B></TD>
<?} else if ($parent == 'pds') {?>
	<TD align="left"><B>자료실분류</B></TD>
<?} else if ($parent == 'job') {?>
	<TD align="left"><B>직업</B></TD>
<?} else if ($parent == 'bank') {?>
	<TD align="left"><B>은행</B></TD>
<?} else if ($parent == 'bank3') {?>
	<TD align="left"><B>3자리은행코드</B></TD>
<?} else if ($parent == 'mail') {?>
	<TD align="left"><B>메일계정</B></TD>
<?}?>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="등록" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		code :
	</th>
	<td>
		<input type="text" name="code" size="25" value="">
	</td>
</tr>
<tr>
	<th>
		이름 :
	</th>
	<td>
		<input type="text" name="name" size="25" value="">
	</td>
</tr>
<tr>
	<th>
		설명 :
	</th>
	<td>
		<textarea name="memo" cols=60 rows=3></textarea>
	</td>
</tr>
</TABLE>
<table border="0">
<tr>
	<td width="20">
		&nbsp;
	</td>
	<td>
</td>
</tr>
</table>
<input type="hidden" name="mode" value="add">
<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="parent" value="<?echo $parent?>">
</FORM>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>