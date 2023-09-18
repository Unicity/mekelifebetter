<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_group_view.php
	// 	Description : 관리자 그룹 보기 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$id	= str_quote_smart(trim($id));

	$query = "select group_name from tb_admin_group where group_id = '".$id."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$group_name = $list[group_name];

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="admin_group_list.php";
		document.frm.submit();
	}

	function goIn() {
	
		if (document.frm.group_name.value == "") {
			alert("관리자 그룹 이름 입력하세요.");
			document.frm.group_name.focus();
			return;
		}
								
		document.frm.target = "frhidden";
		document.frm.action = "admin_group_db.php";
		document.frm.submit();
		
	}
	
//-->
</SCRIPT>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<form name='frm' method='post' action='admin_group_db.php'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>관리자 그룹 관리 (수정)</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="수정" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		관리자 그룹 이름 :
	</th>
	<td>
		<input type="text" name="group_name" size="30" value="<?echo $group_name?>">
	</td>
</tr>
</TABLE>
<table border="0">
	</td>
</tr>
</table>
<input type="hidden" name="mode" value="mod">
<input type="hidden" name="group_id" value="<?echo $id?>">
</FORM>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>