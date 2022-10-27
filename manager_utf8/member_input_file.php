<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2003/7/28
	// 	Last Update : 2003/7/28
	// 	Author 		: Park, ChanHo
	// 	History 	: 2003.7.28 by Park ChanHo 
	// 	File Name 	: user_c_input.php
	// 	Description : the customer member update  View
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$qry_str		= str_quote_smart(trim($qry_str));
	$idxfield		= str_quote_smart(trim($idxfield));
	$page				= str_quote_smart(trim($page));
	$sort				= str_quote_smart(trim($sort));
	$order			= str_quote_smart(trim($order));
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">

function goUpdate() {
	document.frmSearch.target = "frhidden";
	document.frmSearch.action = "member_file_db.php";
	document.frmSearch.submit();
}

//function goBack() {
//	document.frmSearch.action = "member_list.php";
//	document.frmSearch.submit();
//}

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
<FORM name="frmSearch" method="post" enctype='multipart/form-data'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>회원 목록 갱신</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<!--<INPUT TYPE="button" VALUE="목록" onClick="goBack();">-->	
	<INPUT TYPE="button" VALUE="정보갱신" onClick="goUpdate();">	
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" width="100%">
<form name='frm_img' method='post' action='member_db.php' enctype='multipart/form-data'>
<tr>
	<th bgcolor="#666666" colspan="2" align="center">
		<b><font color="#FFFFFF">회원 파일</font></b>
	</th>
</tr>
</table>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		소비자 회원 파일 :
	</th>
	<td>
		<input type="file" name="cfile" size="60">
	</td>
</tr>
<tr>
	<th>
		분배 회원 파일 :
	</th>
	<td>
		<input type="file" name="dfile" size="60">
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>