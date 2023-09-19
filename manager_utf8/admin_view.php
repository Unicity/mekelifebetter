<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_view.php
	// 	Description : 관리자 보기 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$id				= str_quote_smart(trim($id));
	$page			= str_quote_smart(trim($page));
	$idxfield = str_quote_smart(trim($idxfield));
	$qry_str	= str_quote_smart(trim($qry_str));
	$temp1		= str_quote_smart(trim($temp1));
	$UserDept	= str_quote_smart(trim($UserDept));

	if (empty($id)) {
		$id = $s_adm_id;
	}

	$query = "select * from tb_admin where id = '".$id."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$id					= $list[id];
	$passwd			= $list[passwd];
	$UserName		= $list[UserName];
	$UserInfo		= $list[UserInfo];
	$Phone1			= $list[Phone1];
	$Phone2			= $list[Phone2];
	$Email			= $list[Email];
	$temp1			= $list[temp1];
	$UserDept		= $list[temp2];
	$en_pass = $list[EN_PASS];
	
	$query = "select group_id, group_name  from tb_admin_group order by group_id";
	$result = mysql_query($query);
	
?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<title><?echo $g_site_title?></title>
<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="admin_list.php";
		document.frm.submit();
	}

	function goIn() {
				
		if (document.frm.passwd_chk.checked) {

			if (document.frm.passwd.value == "") {
				alert("비밀번호를 입력하세요.");
				document.frm.passwd.focus();
				return;
			}

			if (parseInt(document.frm.passwd.value.length) < 10) {
				alert("비밀번호는 문자, 숫자, 특수문자의 조합으로 10~16글자로 입력해주세요.");
				document.frm.passwd.focus();
				return;
			}

			if(!CheckPassWord(document.frm.passwd)) {
				document.frm.passwd.focus();
				return;
			}
		}
		
		if (document.frm.UserName.value == "") {
			alert("관리자 성명을 입력하세요.");
			document.frm.UserName.focus();
			return;
		}

		if (document.frm.UserDept.value == "") {
			alert("관리자 부서를 입력하세요.");
			document.frm.UserDept.focus();
			return;
		}

		if (document.frm.Email.value == "" ) {
			alert("이메일을 입력해 주세요.");
			document.frm.Email.focus();
			return;
		}

		if (!isValidEmail(document.frm.Email)) {
			alert("이메일을 정확히 입력해 주세요.");
			document.frm.Email.focus();
			return;
		}
	
		document.frm.target = "frhidden";
		document.frm.action = "admin_db.php";
		document.frm.submit();
		
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

	// 비밀번호 유효성체크
	function CheckPassWord(ObjUserPassWord) {
		
		if(ObjUserPassWord.value.length < 10) {
			alert("비밀번호는 문자, 숫자, 특수문자의 조합으로 10~16글자로 입력해주세요.");
			return false;
		}

		if(ObjUserPassWord.value.length > 16) {
			alert("비밀번호는 문자, 숫자, 특수문자의 조합으로 16글자이하로 입력해주세요.");
			return false;
		}

		var icnt = 0;
		var scnt = 0;
		
		for(var i=0; i < ObjUserPassWord.value.length; i++) {
			ch = ObjUserPassWord.value.charCodeAt(i);
			// charCodeAt 으로 받은 아스키 코드값으로 위 표를 바탕으로 범위 설정
			// 숫자이면 카운트를 올림
			if(ch > 47 && ch < 58) {
				icnt = icnt + 1;
			}
			// 문자이면 카운트를 올림
			if((ch > 64 && ch < 91) || (ch > 96 && ch <123)) {
				scnt = scnt + 1;
			}
		}
		
	
		var special_pattern = /[`~!@#$%^&*|\\\'\";:\/?]/gi;
		if( special_pattern.test(ObjUserPassWord.value) == true ){
			// 
		} else {
			alert('하나 이상의 특수문자가 포함되어 있어야 합니다.');
			return false;
		}


		if(icnt >= 1 && scnt >= 1) {
		}else {
			// 숫자와 문자가 함께 들어가지 않았을 때 처리부분
			alert("비밀번호는 문자, 숫자, 특수문자 조합으로 입력해주세요.");
			return false;
		}

		var SamePass_0 = 0; //동일문자 카운트
		var SamePass_1 = 0; //연속성(+) 카운드
		var SamePass_2 = 0; //연속성(-) 카운드

		var chr_pass_0;
		var chr_pass_1;

		for(var i=0; i < ObjUserPassWord.value.length; i++) {
			chr_pass_0 = ObjUserPassWord.value.charAt(i);
			chr_pass_1 = ObjUserPassWord.value.charAt(i+1);

			//동일문자 카운트
			if(chr_pass_0 == chr_pass_1) {
				SamePass_0 = SamePass_0 + 1
			}

			//연속성(+) 카운드
			if(chr_pass_0.charCodeAt(0) - chr_pass_1.charCodeAt(0) == 1) {
				SamePass_1 = SamePass_1 + 1
			}

			//연속성(-) 카운드
			if(chr_pass_0.charCodeAt(0) - chr_pass_1.charCodeAt(0) == -1) {
				SamePass_2 = SamePass_2 + 1
			}
		}

		if(SamePass_0 > 4) {
			alert("동일문자를 4번 이상 사용할 수 없습니다.");
			return false;
		}

		if(SamePass_1 > 4 || SamePass_2 > 2 ) {
			alert("연속된 문자열(123 또는 321, abc, cba 등)을 3자 이상 사용 할 수 없습니다.");
			return false;
		}
		return true;
	}
//-->
</SCRIPT>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<form name='frm' method='post' action='admin_db.php'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>관리자 관리 (수정)</B></TD>
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
		관리자 그룹 :
	</th>
	<td>
		<select name="temp1">
<?
	while($row = mysql_fetch_array($result)) {
		
		if (trim($temp1) == $row[group_id]) {
?>
		<option value="<?echo $row[group_id]?>" selected><?echo $row[group_name]?></option>
<?
		} else {
?>
		<option value="<?echo $row[group_id]?>"><?echo $row[group_name]?></option>
<?
		}	
	}
?>
		</select>
	</td>
</tr>
<tr>
	<th>
		관리자 ID :
	</th>
	<td>
		<?echo $id?>
	</td>
</tr>
<tr>
	<th>
		상태 :
	</th>
	<td>
		<select name="status">
			<option value="Y" <?=($list[status] == "Y") ? "selected" : "";?>>정상</option>	
			<option value="N" <?=($list[status] == "N") ? "selected" : "";?>>제한</option>	
		</select>
	</td>
</tr>
<tr>
	<th>
		마지막로그인 :
	</th>
	<td>
		<?echo date("Y-m-d H:i:s", $list['last_login'])?>
	</td>
</tr>
<tr>
	<th>
		Password :
	</th>
	<td>
		<input type="password" name="passwd" size="20" value="" maxlength="16"> <input type="checkbox" name="passwd_chk" value="Y" class="radio"> 비밀번호 변경
		<br><br> * 비밀번호는 문자, 숫자, 특수문자 조합으로 10자리 이상 16 자리 이하로 입력해주세요.
	</td>
</tr>
<tr>
	<th>
		관리자 성명 :
	</th>
	<td>
		<input type="text" name="UserName" size="15" value="<?echo $UserName?>">
	</td>
</tr>
<tr>
	<th>
		관리자 부서
	</th>
	<td>
		<input type="text" name="UserDept" size="25" value="<?echo $UserDept?>"> * 영문으로 등록
	</td>
</tr>
<tr>
	<th>
		연락처 1 :
	</th>
	<td>
		<input type="text" name="Phone1" size="15" value="<?echo $Phone1?>">
	</td>
</tr>
<tr>
	<th>
		연락처 2 :
	</th>
	<td>
		<input type="text" name="Phone2" size="15" value="<?echo $Phone2?>">
	</td>
</tr>
<tr>
	<th>
		E-Mail :
	</th>
	<td>
		<input type="text" name="Email" size="25" value="<?echo $Email?>">
	</td>
</tr>
<tr>
	<th>
		관리자 설명
	</th>
	<td>
		<textarea name="UserInfo" cols="60" rows="3"><?echo $UserInfo?></textarea>
	</td>
</tr>

</TABLE>
	</td>
</tr>
</table>
<input type="hidden" name="mode" value="mod">
<INPUT type="hidden" name="id" value="<?echo $id?>">
<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
<!--<INPUT type="hidden" name="temp1" value="<?echo $temp1?>">-->
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
</FORM>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>