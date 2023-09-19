<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/3/7
	// 	Last Update : 2004/3/7
	// 	Author 		: Park, ChanHo
	// 	History 	: 2003.3.7 by Park ChanHo 
	// 	File Name 	: member_input_result.php
	// 	Description : 
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";


	$query1 = "select count(*) from tb_member where member_kind = 'C' ";

	$query2 = "select count(*) from tb_member where member_kind = 'D' ";

	#echo $query1; 
	#echo $query2;
		
	$result1 = mysql_query($query1,$connect);
	$result2 = mysql_query($query2,$connect);

	$row = mysql_fetch_array($result1);
	$TotalArticle1 = $row[0];
	$row = mysql_fetch_array($result2);
	$TotalArticle2 = $row[0];

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">


function goListC() {
	document.location = "member_list.php?member_kind=C";
}

function goListD() {
	document.location = "member_list.php?member_kind=D";
}

function goIn() {
	document.location = "member_input_file.php";
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

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>회원 갱신 결과</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<INPUT TYPE="button" VALUE="소비자회원목록" onClick="goListC();">
	<INPUT TYPE="button" VALUE="분배회원목록" onClick="goListD();">	
	<INPUT TYPE="button" VALUE="회원등록" onClick="goIn();">	
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" width="100%">
<tr>
	<th bgcolor="#666666" colspan="2" align="center">
		<b><font color="#FFFFFF">회원 갱신 결과</font></b>
	</th>
</tr>
</table>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		소비자 회원  :
	</th>
	<td>
		<?echo $TotalArticle1?> 명
	</td>
</tr>
<tr>
	<th>
		분배 회원 파일 :
	</th>
	<td>
		<?echo $TotalArticle2?> 명
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>