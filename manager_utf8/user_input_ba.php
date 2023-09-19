<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$member_no		= str_quote_smart(trim($member_no));
	$re_member_no = str_quote_smart(trim($member_no));
	$member_no = str_replace("^", "'",$member_no);

	$query = "select * from tb_userinfo where member_no in $member_no";
	$result = mysql_query($query);

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script language="javascript">
	function goIn() {

		var check_count = 0;
		var total = document.frmSearch.member_no.length;
		
		if (total != null) {
		
			for(var i=0; i<total; i++) {
				if(document.frmSearch.number[i].value == "") {
					alert("FO number를 반드시 입력하셔야 합니다.");
					document.frmSearch.number[i].focus();
		    		return;			
	    		}
			}

			for(var i=0; i<total; i++) {
				if(document.frmSearch.password[i].value == "") {
					alert("비밀번호를 반드시 입력하셔야 합니다.");
					document.frmSearch.number[i].focus();
		    		return;			
	    		}
			}
		} else {

				if(document.frmSearch.number.value == "") {
					alert("FO number를 반드시 입력하셔야 합니다.");
					document.frmSearch.number.focus();
		    		return;			
	    		}

				if(document.frmSearch.password.value == "") {
					alert("비밀번호를 반드시 입력하셔야 합니다.");
					document.frmSearch.number.focus();
		    		return;			
	    		}

		}
		
		document.frmSearch.member_nos.value = getMember_Nos();
		document.frmSearch.numbers.value = getNumbers();
		document.frmSearch.passwords.value = getPwds();

		bDelOK = confirm("입력하신 정보가 모두 맞는지 확인 되었나요?");
		
		if ( bDelOK ==true ) {
			document.frmSearch.action = "user_ba_db.php";			
			document.frmSearch.submit();
		} else {
			return;
		}

	}


	function getMember_Nos(){

		var sValues = "";

		if(frmSearch.member_no != null){
			if(frmSearch.member_no.length != null){
				for(i=0; i<frmSearch.member_no.length; i++){
					if(sValues != ""){
						sValues += "|";
					}
					sValues += frmSearch.member_no[i].value;
				}
			}else{
				sValues += frmSearch.member_no.value;
			}
		}
		return sValues;
	}

	function getNumbers(){

		var sValues = "";

		if(frmSearch.number != null){
			if(frmSearch.number.length != null){
				for(i=0; i<frmSearch.number.length; i++){
					if(sValues != ""){
						sValues += "|";
					}
					sValues += frmSearch.number[i].value;
				}
			}else{
				sValues += frmSearch.number.value;
			}
		}
		return sValues;
	}

	function getPwds(){

		var sValues = "";

		if(frmSearch.password != null){
			if(frmSearch.password.length != null){
				for(i=0; i<frmSearch.password.length; i++){
					if(sValues != ""){
						sValues += "|";
					}
					sValues += frmSearch.password[i].value;
				}
			}else{
				sValues += frmSearch.password.value;
			}
		}
		return sValues;
	}

	function f_close() {
		opener.check_data();
		self.close();
	}
	
</script>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 회원 가입 (회원 FO 입력용) </B></TD>
</TR>
</TABLE>
<FORM name="frmSearch" method="post">
<input type="hidden" name="re_member_no" value="<?echo $re_member_no?>">
<input type="hidden" name="member_nos" value="">
<input type="hidden" name="numbers" value="">
<input type="hidden" name="passwords" value="">

<?	while($row = mysql_fetch_array($result)) { ?>

<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<input type="hidden" name="member_no" value="<?echo $row[member_no]?>">
<tr>
	<th>회원종류 :</th>
	<td><? if (trim($row[member_kind]) == "D" ) { echo " FO 회원"; } else {echo "소비자 회원";} ?></td>
</tr>
<tr>
	<th>이름 : </th>
	<td><?echo $row[name]?></td>
</tr>
<tr>
	<th>주민등록번호 :</th>
	<td><?echo $row[reg_jumin1]?>-<?echo $row[reg_jumin2]?></td>
</tr>
<tr>
	<th>희망 비밀번호 :</th>
	<td><?echo $row[password]?></td>
</tr>
<tr>
	<th>FO Number :</th>
	<td><input type="text" name="number" size="15" value="<?echo $row[number]?>"></td>
</tr>
<tr>
	<th>비밀번호 :</th>
	<td><input type="text" name="password" size="15" value="<?echo $row[password]?>"></td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<br>
<br>
<?
	}
?> 
</td>
</tr>
</table>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left">&nbsp;</TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		<INPUT TYPE="button" VALUE="자료 입력" onClick="goIn();">	
		<INPUT TYPE="button" VALUE="닫 기" onClick="f_close();">	
	</TD>
</TR>
</TABLE>
</center>
</form>
<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</body>
</html>

<?
	mysql_close($connect);
?>