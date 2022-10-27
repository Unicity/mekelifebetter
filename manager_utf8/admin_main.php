<?
session_start();

if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	exit;
}

	include "./inc/global_init.inc";
	include "./inc/common_function.php";

	if(!session_is_registered("s_adm_id")){
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script language="javascript">
		alert("세션이 종료 되어 다시 로그인 하셔야 합니다.");
		document.location="admin.php";
	</script>
</head>
</html>
<?
		exit;
	}
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<TITLE><?echo $g_site_title?></TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
	var queryString;
	var queryStringHis;
	var queryStringView;
-->
</SCRIPT>
</HEAD>
<FRAMESET ROWS="3,100,*" BORDER="0" frameborder="no" framespacing="1">
	<FRAME SRC="frhidden.html" NAME="frhidden" NORESIZE MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="no"> 
	<FRAME SRC="admin_top.php" NAME="frtop" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="no">
	<FRAMESET COLS="190, *" frameborder=no border=0 framespacing=0 >
		<FRAME SRC="admin_left.php" NAME="frleft" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="auto">
<?if($s_flag == "3"){?>
		<FRAME SRC="/manager/pg/KSPAY_list.php" NAME="frmain" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="auto">
<?}else{?>
		<FRAME SRC="admin_body.php" NAME="frmain" MARGINWIDTH="0" MARGINHEIGHT="0" SCROLLING="auto">
<?}?>
	</FRAMESET>
	<NOFRAMES>
	<BODY bgcolor="white" text="black" link="blue" vlink="purple" alink="red">
	<p>이 페이지를 보려면, 프레임을 볼 수 있는 브라우저가 필요합니다.</p>
	</BODY>
	</NOFRAMES>
</FRAMESET>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</HTML>
