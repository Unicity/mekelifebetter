<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";

	$sYY = date(Y);
	$sMM = date(m);
	$sDD = date(d);

	$sHour = date(H);
	$sMin = date(i);
	$sSec = date(s);

	$today = $sYY."-".$sMM."-".$sDD;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="inc/admin.css" type="text/css">
</head>
<body>
<br><br>
<br><br>
<center>
<TABLE width="100%">
<TR>
	<TD align="center"><B><?echo $g_site_name?> 관리자 시스템에 오신것을 환영 합니다. [<?echo $today?>]</B></TD>
</TR>
</TABLE>
</center>
</body>
</html>