<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script type="text/javascript" src="inc/jquery.js"></script>
<script language="javascript">
function js_excel() {

	if($('#upfile').val() == ""){
		alert("업로드할 파일을 선택하여 주세요");
	}else{
		if(confirm("업로드 하시겠습니까?")){
			$('#frm').submit();
		}
	}
}

</script>

<STYLE type='text/css'>
.btn { padding:7px 10px; background:#000; color:#fff !important; }
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>동의사항 일괄 전송</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp;
	</TD>
</TR>
</TABLE>
<br>
<div style="padding:10px">
	<p>* 엑셀파일을 <strong><u>탭으로분리(*.txt)로 저장</u></strong> 또는 메모장등을 통해서 라인당 하나의 회원번호로 작성해서 업로드 하여 주세요.</p>
	
	<form name="frm" id="frm" method="post" action="batch_upload.php" enctype="multipart/form-data" style="padding:10px;">
		<input type="file" name="upfile" id="upfile" style="border:1px solid #eee; width:300px;" />
		<a href="javascript:js_excel();" class="btn blue">파일올리기</a>
	</form>


	<p>- 처리시간이 많이 소요됩니다.</p>
	<p>- 가급적 적은 수량으로 분할하여 처리하여 주세요.</p>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>