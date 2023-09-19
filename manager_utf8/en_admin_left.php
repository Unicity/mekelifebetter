<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_left.php
	// 	Description : 메뉴 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	
	$query = "select group_item from tb_admin_group where group_id = '$s_flag'";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$group_item = $list[group_item];
	
	$query = "select menu_id, big_menu, small_menu, menu_url from tb_admin_menu where menu_id in (".$group_item.")";

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {

		$menu_id[] = $row[menu_id];
		$big_menu[] = $row[big_menu];
		$small_menu[] = $row[small_menu];
		$menu_url[] = $row[menu_url];
		
	}

	mysql_close($connect);
	
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<TITLE><?echo $g_site_title?></TITLE>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<SCRIPT language="javascript">

	var old='';
	function menu(name)	{

		submenu=eval("submenu_"+name+".style");
						
		if(submenu.display != 'block') {
				
			submenu.display='block';
						
		} else {	
			submenu.display='none';
		}
	}

	function goPage(strURL) {
		top.frmain.location=strURL;
	}

</SCRIPT>
</HEAD>
<BODY class="LFRAME">

<?php include "common_load.php" ?>

<BASE target="frmain">
<!--
<table width=150 border=0>
<tr>
	<td width=80% align="center">
		<table width=135 height="25" border=0 bgcolor="gray">
			<tr><td align="center">
				<a href="http://www.be-kr.co.kr" target="_new"><font color="#FFFFFF"><b>홈페이지</b></font></a>
			</td></tr>
		</table>
	</td>
</tr>
</table>
-->
<TABLE width=150 cellpadding="0" cellspacing="0">
<TR valign="top">
	<td height="14">
	</td>
</TR>
<TR valign="top">
	<TD align=right width=150 bgcolor="#FFFFFF">

	
<?
	

	$isExist = 0;

	for ($i = 0; $i < sizeof($menu_id); $i++) {
			
		if ($big_menu[$i] == "5") {
			
			if ($isExist == 0) {
?>
	<p height="30" align="center"><b><a onclick="menu(6);" style="CURSOR:hand"><font color="#000000">Description</font></a></b><br>
<span id=submenu_6 style="DISPLAY: block;"> 
	<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
	<tr><td> 
<?
				$isExist = 1;
			}		

			if ($i==22){
?>				
	<input type=button value="<?echo $small_menu[$i]?>" width=150 height=30 onclick="goPage('./pg/en_KSPAY_list.php')"></br>
<?
			}}
	}

	if ($isExist == 1) {
?>
	</td>
	</tr>
	</table>
</span>
<?
	}
?>

	</TD>
</TR>
</TABLE>
<a href="#d" onclick="goPage('r_n_d_news_list.php?BoardId=R1');"></a><br>
<a href="#d" onclick="goPage('edu_cal_list.php');"></a>
<!--
<a onclick="menu(1);" style="CURSOR:hand" >aaa</a>
<span id=submenu_1 style="DISPLAY: none;"> 
	<table cellpadding=0 cellspacing=0 class=tblfnt border=0>
	<tr> 
		<td height="1" colspan="3" ></td>
	</tr>
	<tr>
	<td height="1" bgcolor="#FFEAE8" class="cust_faq01" style="padding-left:10px;padding-right:10px;padding-top:10px;padding-bottom:10px;">
	</td>
	</tr>
	<tr> 
		<td height="1" colspan="3"></td>
	</tr>
	</table>
</span>

<a href="new_member_input_file.php">.</a>
<br>
<a href="new_member_list.php?member_kind=C">.</a>
<br>
<a href="new_member_list.php?member_kind=D">.</a>
-->
<br>
<br>
</BODY>
</HTML>