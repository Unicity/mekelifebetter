<?php 
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_top.php
	// 	Description : 
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script>
/*
	function goPage(strURL) {
		top.frmain.location=strURL;
	}
*/
</script>
</HEAD>
<BODY class="TFRAME">

<?php include "common_load.php" ?>

<BASE target="frmain">
<TABLE border=0 width="100%" cellpadding="0" cellspacing="0" background="../img/bg5.gif">
<TR height="30">
	<TD valign="top"><a href="http://unicore.makelifebetter.co.kr"><IMG src="../img/logo.gif" border=0></a></TD>
	<TD align="right">
	<a href="admin_read.php"><font color="navy">자기정보수정</font></a><font color="navy"> | </font><a href="logout.php"><font color="navy">로그아웃</font></a>&nbsp;&nbsp;
	</TD>
</TR>
</TABLE>
<hr>
</BODY>
</HTML>

