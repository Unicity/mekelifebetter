<?
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	function str_cut($str,$len){
		$slen = strlen($str);
		if (!$str || $slen <= $len) $tmp = $str;
		else	$tmp = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$len))."...";
		return $tmp;
	}

	function makeCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[code]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}

	function makeCodeAsName ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[name]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}


?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	
	function lengthCheck1(form, str) {
		if (str.length >= 6) {
			frm_m.reg_jumin1.blur();
			frm_m.reg_jumin2.focus();
		}
	}

	function lengthCheck2(form, str) {
		if (str.length >= 7) {
			frm_m.reg_jumin2.blur();
			frm_m.hpho1.focus();

			if (frm_m.reg_jumin1.value.length == 6) {
				if (frm_m.reg_jumin1.value.substring(0,2) > 4) {
					frm_m.birth_y.value = "19"+frm_m.reg_jumin1.value.substring(0,2);		
				} else {
					frm_m.birth_y.value = "20"+frm_m.reg_jumin1.value.substring(0,2);		
				}
				
				frm_m.birth_m.value = frm_m.reg_jumin1.value.substring(2,4);
				frm_m.birth_d.value = frm_m.reg_jumin1.value.substring(4,6);
			}

			if (frm_m.reg_jumin2.value.length == 7) {
				if (frm_m.reg_jumin2.value.substring(0,1) == 1) {
					frm_m.sex[0].checked = 1;
				} else {
					frm_m.sex[1].checked = 1;				
				}
			}

		}
	}

	function f_sel_mail() {

		if (document.frm_m.sel_mail.selectedIndex == 1) {
			document.frm_m.email2.style.visibility = "visible"; 			
			document.frm_m.email2.value = ""; 			
			document.frm_m.email2.focus(); 			
		} else {
			document.frm_m.email2.value = frm_m.sel_mail.value; 
			document.frm_m.email2.style.visibility = "hidden"; 			
		}
		
	}

	function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="user_list.php";
		document.frm_m.submit();
	}

	function goIn() {
				
		if (document.frm_m.name.value == "") {
			alert("이름을 입력하세요.");
			document.frm_m.name.focus();
			return;
		}
		
		if (document.frm_m.password.value == "") {
			alert("희망 하는 비밀번호를 입력하세요.");
			document.frm_m.password.focus();
			return;
		}

		if (document.frm_m.reg_jumin1.value == "") {
			alert("주민등록번호를 입력하세요.");
			document.frm_m.reg_jumin1.focus();
			return;
		}

		if(!isNumber(document.frm_m.reg_jumin1)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.reg_jumin1.focus();
			return;
		}

		if (document.frm_m.reg_jumin2.value == "") {
			alert("주민등록번호를 입력하세요.");
			document.frm_m.reg_jumin2.focus();
			return;
		}

		if(!isNumber(document.frm_m.reg_jumin2)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.reg_jumin2.focus();
			return;
		}

		if(!CheckJuminForm(document.frm_m.reg_jumin1.value, document.frm_m.reg_jumin2.value)) {
			alert("유효하지 않은 주민 등록 번호 입니다.");
			document.frm_m.reg_jumin1.focus();
			return;
		}

		if (document.frm_m.birth_y.value == "") {
			alert("생년월일 입력하세요.");
			document.frm_m.birth_y.focus();
			return;
		}

		if(!isNumber(document.frm_m.birth_y)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.birth_y.focus();
			return;
		}

		if (document.frm_m.birth_m.value == "") {
			alert("생년월일 입력하세요.");
			document.frm_m.birth_m.focus();
			return;
		}

		if(!isNumber(document.frm_m.birth_m)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.birth_m.focus();
			return;
		}

		if (document.frm_m.birth_d.value == "") {
			alert("생년월일 입력하세요.");
			document.frm_m.birth_d.focus();
			return;
		}

		if(!isNumber(document.frm_m.birth_d)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.birth_d.focus();
			return;
		}

		if (document.frm_m.hpho2.value == "") {
			alert("휴대전화번호를 입력하세요.");
			document.frm_m.hpho2.focus();
			return;
		}

		if(!isNumber(document.frm_m.hpho2)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.hpho2.focus();
			return;
		}

		if (document.frm_m.hpho3.value == "") {
			alert("휴대전화번호를 입력하세요.");
			document.frm_m.hpho3.focus();
			return;
		}

		if(!isNumber(document.frm_m.hpho3)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.hpho3.focus();
			return;
		}

		if (document.frm_m.pho2.value == "") {
			alert("전화번호를 입력하세요.");
			document.frm_m.pho2.focus();
			return;
		}

		if(!isNumber(document.frm_m.pho2)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.pho2.focus();
			return;
		}

		if (document.frm_m.pho3.value == "") {
			alert("전화번호를 입력하세요.");
			document.frm_m.pho3.focus();
			return;
		}

		if(!isNumber(document.frm_m.pho3)) {
			alert("숫자만 입력해 주세요.");
			document.frm_m.pho3.focus();
			return;
		}

		if (document.frm_m.zip1.value == "") {
			alert("우편번호를 입력하세요.");
			document.frm_m.zip1.focus();
			return;
		}

		if (document.frm_m.zip2.value == "") {
			alert("우편번호를 입력하세요.");
			document.frm_m.zip2.focus();
			return;
		}

		document.frm_m.zip.value = document.frm_m.zip1.value+"-"+document.frm_m.zip2.value;


		if (document.frm_m.addr.value == "") {
			alert("주소를 입력하세요.");
			document.frm_m.addr.focus();
			return;
		}

		if (document.frm_m.email1.value == "" ) {
			alert("이메일을 입력해 주세요.");
			document.frm_m.email1.focus();
			return;
		}

		if (document.frm_m.email2.value == "" ) {
			alert("이메일을 입력해 주세요.");
			document.frm_m.email2.focus();
			return;
		}

		document.frm_m.email.value = document.frm_m.email1.value+"@"+document.frm_m.email2.value;

		if (!isValidEmail(document.frm_m.email)) {
			alert("이메일을 정확히 입력해 주세요.");
			document.frm_m.email1.focus();
			return;
		}

		if (document.frm_m.account_bank.value == "" ) {
			alert("거래은행을 선택해 주십시오.");
			document.frm_m.account_bank.focus();
			return;
		}

		if (document.frm_m.account.value == "" ) {
			alert("계좌번호를 입력해 주십시오.");
			document.frm_m.account.focus();
			return;
		}

		if (document.frm_m.job.value == "" ) {
			alert("거래은행을 선택해 주십시오.");
			document.frm_m.job.focus();
			return;
		}

		var check_count = 0;
		var check_result = "";
		var total = document.frm_m.chk_interest.length;
						 
		for(var i=0; i< total; i++) {
			if(document.frm_m.chk_interest[i].checked == true) {				
				check_result = check_result+"Y";
	    		check_count++;
	   	 	} else {
				check_result = check_result+"N";
			}
	   	 	
		}

		if(check_count == 0) {
			alert("하나 이상의 관심 항목을 선택해 주십시오 .");
	    	return;
		}

		document.frm_m.interest.value = check_result;
		
//		alert(document.frm_m.interest.value);
			
		document.frm_m.target = "frhidden";
		document.frm_m.action = "user_db.php";
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

	function CheckJuminForm(J1, J2) {
	
		var SUM=0;
	
		for(i=0;i<J1.length;i++)  {
	
			if (J1.charAt(i) >= 0 || J1.charAt(i) <= 9) { 
				if(i == 0) {
					SUM = (i+2) * J1.charAt(i);
				} else { 
					SUM = SUM + (i+2) * J1.charAt(i);
				}
			} else {
				return false;
			}
		}
	
		for(i=0;i<2;i++) {
			
			if (J2.charAt(i) >= 0 || J2.charAt(i) <= 9) {
				SUM = SUM + (i+8) * J2.charAt(i);
			} else { 
				return false;
			}
		}
	
		for(i=2;i<6;i++) {
	
			if (J2.charAt(i) >= 0 || J2.charAt(i) <= 9) {
				SUM = SUM + (i) * J2.charAt(i);
			} else {
				return false;
			}
		}
	
		var checkSUM = SUM % 11;
	
		if(checkSUM == 0) {
			var checkCODE = 10;
		} else if (checkSUM ==1) {
			var checkCODE = 11;
		} else {
			var checkCODE = checkSUM;
		}
	
		var check1 = 11 - checkCODE; 
	
		if (J2.charAt(6) >= 0 || J2.charAt(6) <= 9) {
			var check2 = parseInt(J2.charAt(6))
		} else {
			return false;
		}
	
		if(check1 != check2) {
			return false;
		} else {
			return true; 
		}
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
<form name="frm_m" method="post" action="user_db.php">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 회원 가입</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="등록" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		이름 :
	</th>
	<td>
		<input type="text" name="name" size="15" value="">
	</td>
</tr>
<tr>
	<th>
		희망 비밀번호 :
	</th>
	<td>
		<input type="text" name="password" size="15" value="">
	</td>
</tr>
<tr>
	<th>
		주민등록번호 :
	</th>
	<td>
		<input type="text" name="reg_jumin1" size="6" value="" maxlength="6" onkeyup='lengthCheck1(this.form,this.value);'>-
		<input type="text" name="reg_jumin2" size="7" value="" maxlength="7" onkeyup='lengthCheck2(this.form,this.value);'>
	</td>
</tr>
<tr>
	<th>
		생년월일 :
	</th>
	<td>
		<input type="text" name="birth_y" size="4" maxlength="4" value=""> 년
		<input type="text" name="birth_m" size="2" maxlength="2" value=""> 월
		<input type="text" name="birth_d" size="2" maxlength="2" value=""> 일
	</td>
</tr>
<tr>
	<th>
		성별 :
	</th>
	<td>
		<input type="radio" name="sex" value="1" checked> 남
		<input type="radio" name="sex" value="2"> 여
	</td>
</tr>
<tr>
	<th>
		휴대전화번호 :
	</th>
	<td>
		<select name="hpho1" size=1 style="width:60">
			<option value="010">010</option>
			<option value="011">011</option>
			<option value="016">016</option>
			<option value="016">017</option>
			<option value="018">018</option>
			<option value="019">019</option>
		</select> -		
		<input type="text" name="hpho2" size="4" desc="휴대전화번호" maxlength="4" value=""> -
		<input type="text" name="hpho3" size="4" desc="휴대전화번호" maxlength="4" value="">
	</td>
</tr>
<tr>
	<th>
		전화번호 :
	</th>
	<td>
		<select name="pho1" size=1 style="width:60">
			<option value="02"> 02</option>
			<option value="031">031</option>
			<option value="032">032</option>
			<option value="033">033</option>
			<option value="041">041</option>
			<option value="042">042</option>
			<option value="043">043</option>
			<option value="051">051</option>
			<option value="052">052</option>
			<option value="053">053</option>
			<option value="054">054</option>
			<option value="055">055</option>
			<option value="061">061</option>
			<option value="062">062</option>
			<option value="063">063</option>
			<option value="064">064</option>
		</select> -
		<input type="text" name="pho2" size="4" desc="전화번호" maxlength="4" value=""> -
		<input type="text" name="pho3" size="4" desc="전화번호" maxlength="4" value="">
	</td>
</tr>
<tr>
	<th>
		우편번호 :
	</th>
	<td>
		<input type="text" name="zip1" size="3" value="" readonly=1> -
		<input type="text" name="zip2" size="3" value="" readonly=1>
		<input type="button" name="btn1" value="주소찾기" onClick="NewWindow('../common/new_zipcode.php','view','445','180','no');">
	</td>
</tr>
<tr>
	<th>
		주 소 :
	</th>
	<td>
		<input type="text" name="addr" size="60" value="">
	</td>
</tr>
<tr>
	<th>
		E-Mail :
	</th>
	<td>
		<input type="text" name="email1" size="10" value="">@
		<select name="sel_mail" onChange="f_sel_mail();">
			<option value="">이메일 주소 선택</option>
			<option value="0">사용자 직접 입력</option>
			<? makeCodeAsName ("mail"); ?>
		</select>
		<input type="text" name="email2" size="20" value="" style="visibility:hidden">
	</td>
</tr>
<tr>
	<th>
		메일수신여부 :
	</th>
	<td>
		<input type="radio" name="email_flag" value="Y" checked > 수신 
		<input type="radio" name="email_flag" value="N"> 수신거부
	</td>
</tr>
<tr>
	<th>
		거래 은행 :
	</th>
	<td>
		<select name="account_bank">
			<? makeCodeAsName ("bank"); ?>
		</select>
	</td>
</tr>
<tr>
	<th>
		계좌번호 :
	</th>
	<td>
		<input type="text" name="account" size="30" value="">
	</td>
</tr>
<tr>
	<th>
		회원종류 :
	</th>
	<td>
		<input type="radio" name="member_kind" value="D" checked > FO 회원 
		<input type="radio" name="member_kind" value="C"> 소비자 회원
	</td>
</tr>
<tr>
	<th>
		직 업 :
	</th>
	<td>
		<select name="job">
			<? makeCodeAsName ("job"); ?>
		</select>
	</td>
</tr>
<tr>
	<th>
		관심 분야 :
	</th>
	<td>
		<input type="checkbox" name="chk_interest" value="Y"> 관심 분야 1
		<input type="checkbox" name="chk_interest" value="Y"> 관심 분야 2
		<input type="checkbox" name="chk_interest" value="Y"> 관심 분야 3
		<input type="checkbox" name="chk_interest" value="Y"> 관심 분야 4
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
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
<input type="hidden" name="email" value="">
<input type="hidden" name="zip" value="">
<input type="hidden" name="interest" value="">
</FORM>
<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</body>
</html>
<?
mysql_close($connect);
?>