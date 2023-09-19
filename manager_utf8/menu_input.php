<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_group_input.php
	// 	Description : 관리자 그룹 등록 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$sort					= str_quote_smart(trim($sort));
	$order				= str_quote_smart(trim($order));

	$query = "select * from tb_big_menu order by big_menu_order";
	$result = mysql_query($query);

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<TITLE><?echo $g_site_title?></TITLE>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="menu_list.php";
		document.frm.submit();
	}

	function goIn() {
	
		if (document.frm.big_menu.value == "-1") {
			alert("메뉴 그룹을 선택 하세요.");
			document.frm.big_menu.focus();
			return;
		}

		if (document.frm.small_menu.value == "") {
			alert("메뉴명을 입력하세요.");
			document.frm.small_menu.focus();
			return;
		}

		if (document.frm.menu_url.value == "") {
			alert("메뉴 경로를 입력하세요.");
			document.frm.menu_url.focus();
			return;
		}
								
		document.frm.target = "frhidden";
		document.frm.action = "menu_db.php";
		document.frm.submit();
		
	}
	
//-->
</SCRIPT>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<form name='frm' method='post' action='menu_db.php'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>메뉴 관리 (등록)</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="등록" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
	</TD>
</TR>
</TABLE>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		메뉴그룹 선택 :
	</th>
	<td>
		<select name="big_menu">
			<option value="-1">선 택</option>
<?
	while($row = mysql_fetch_array($result)) {
?>
		<option value="<?echo $row[big_menu]?>"><?echo $row[big_menu_name]?></option>
<?
	}
?>
		</select>
	</td>
</tr>
<tr>
	<th>
		메뉴명 :
	</th>
	<td>
		<input type="text" name="small_menu" size="30" value="">
	</td>
</tr>
<tr>
	<th>
		메뉴 경로 :
	</th>
	<td>
		<input type="text" name="menu_url" size="50" value="">
	</td>
</tr>
<tr>
	<th>
		메뉴 설명 :
	</th>
	<td>
		<textarea name="menu_info" cols="70" rows="3"></textarea>
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
</FORM>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>