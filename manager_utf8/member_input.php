<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: member_input.php
	// 	Description : 회원 추가 화면
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
		document.frm.action="member_list.php";
		document.frm.submit();
	}

	function goIn() {
	
		if (document.frm_m.reg_no.value == "") {
			alert("아이디를 입력하세요.");
			document.frm_m.reg_no.focus();
			return;
		}
						
		if (document.frm_m.name.value == "") {
			alert("성명을 입력하세요.");
			document.frm_m.name.focus();
			return;
		}

		if (document.frm_m.number.value == "") {
			alert("FO를 입력하세요.");
			document.frm_m.number.focus();
			return;
		}

//		if (document.frm_m.email.value == "" ) {
//			alert("이메일을 입력해 주세요.");
//			document.frm_m.email.focus();
//			return;
//		}

		if (document.frm_m.email.value != "") {
			if (!isValidEmail(document.frm_m.email)) {
				alert("이메일을 정확히 입력해 주세요.");
				document.frm_m.email.focus();
				return;
			}
		}
		
		document.frm_m.addr.value = document.frm_m.Addr1.value;
		document.frm_m.zip.value = document.frm_m.Zip.value;
		
		document.frm_m.target = "frhidden";
		document.frm_m.action = "member_db.php";
		document.frm_m.submit();
		
	}
	
	function isValidEmail(input) {
//    var format = /^(\S+)@(\S+)\.([A-Za-z]+)$/;
    	var format = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
    	return isValidFormat(input,format);
	}
	
	function isValidFormat(input,format) {
    	if (input.value.search(format) != -1) {
        	return true; //올바른 포맷 형식
    	}
    	return false;
	}

	function containsCharsOnly(input,chars) {
    	for (var inx = 0; inx < input.value.length; inx++) {
       		if (chars.indexOf(input.value.charAt(inx)) == -1)
           		return false;
    	}
    	return true;	
	}

	function isNumber(input) {
    	var chars = "0123456789";
    	return containsCharsOnly(input,chars);
	}

	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}

//-->
</SCRIPT>
</HEAD>
<BODY>
<form name='frm_m' method='post' action='member_db.php'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?  if ($member_kind == "C") {?>
	<TD align="left"><B>소비자 회원 관리 (등록)</B></TD>
<?	} else {?>
	<TD align="left"><B>분배 회원 관리 (등록)</B></TD>
<?  }?>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="등록" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
	</TD>
</TR>
</TABLE>
<font color='red'> 파일로 등록을 하시면 개별적으로 입력한 회원 정보도 같이 갱신 됩니다.</font>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		주민등록번호<br>(TAX No) :
	</th>
	<td>
		<input type="text" name="reg_no" size="15" value=""><br>
		주민등록번호 입력시 '-'를 포함하여 입력하세요.
	</td>
</tr>
<tr>
	<th>
		회원명 :
	</th>
	<td>
		<input type="text" name="name" size="15" value="">
	</td>
</tr>
<tr>
	<th>
		FO :
	</th>
	<td>
		<input type="text" name="number" size="15" value="">
	</td>
</tr>
<tr>
	<th>
		연락처
	</th>
	<td>
		<input type="text" name="phone" size="40" value=""><br>
		두개 이상일 경우 ',' 로 구분하여 입력하세요. 
	</td>
</tr>
<tr>
	<th>
		우편번호 :
	</th>
	<td>
		<input type="text" name="Zip" size="7" value="<?echo $Zip?>">
		<input type="button" name="btn1" value="주소찾기" onClick="NewWindow('../common/zipcode.php','view','445','195','no');">
	</td>
</tr>
<tr>
	<th>
		주 소 :
	</th>
	<td>
		<input type="text" name="Addr1" size="60" value="<?echo $Addr1?>">
	</td>
</tr>
<tr>
	<th>
		E-Mail :
	</th>
	<td>
		<input type="text" name="email" size="25" value="">
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<input type="hidden" name="mode" value="add">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="member_kind" value="<?echo $member_kind?>">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
<INPUT type="hidden" name="addr" value="">
<INPUT type="hidden" name="zip" value="">
</FORM>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>