<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/9/10
	// 	Last Update : 2003/9/10
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.9.10 by Park ChanHo 
	// 	File Name 	: get_email_file.php
	// 	Description : 이메일 추출
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";

?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">

function goUpdate() {
	document.frmSearch.action = "get_email_file_db.php";
	document.frmSearch.submit();
}

</script>
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" enctype='multipart/form-data'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>회원 이메일 추출</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<!--<INPUT TYPE="button" VALUE="목록" onClick="goBack();">-->	
	<INPUT TYPE="button" VALUE="실행" onClick="goUpdate();">	
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" width="100%">
<form name='frm_img' method='post' action='get_email_file_db' enctype='multipart/form-data'>
<tr>
	<th bgcolor="#666666" colspan="2" align="center">
		<b><font color="#FFFFFF">회원 파일</font></b>
	</th>
</tr>
</table>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		회원 파일 :
	</th>
	<td>
		<input type="file" name="cfile" size="60">
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
