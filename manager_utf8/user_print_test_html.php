<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE>:::::유니시티기업 홈 사이트 관리자페이지 입니다.:::::</TITLE>
<script language="javascript">
	function init() {
		print();
	}

	function f_close() {
		opener.check_data();
		self.close();
	}

</script>
<style type="text/css">
@page a4sheet {size:15.0cm 29.7cm}
.a4 {page:a4sheet; page-break-after:always}
</style>
</HEAD>
<BODY onload="init();">

<?php include "common_load.php" ?>

<div class="a4">
<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 회원 가입 (회원 정보 입력용) </B></TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN2">
<tr>
	<th>회원종류 :</th>
	<td>소비자 회원</td>
</tr>
<tr>
	<th>성명 : </th>
	<td>강송희</td>
</tr>
<tr>
	<th>영문성명 : </th>
	<td>kang song hee</td>
</tr>
<tr>
	<th>희망 비밀번호 :</th>
	<td>0000</td>
</tr>
<tr>
	<th>주민등록번호 :</th>
	<td>820409-2814915</td>
</tr>

<tr>
	<th>휴대전화번호 :</th>
	<td>010-2539-4624</td>
</tr>
<tr>
	<th>전화번호 :</th>
	<td>052-2539-4624</td>
</tr>
<tr>
	<th>우편번호 :</th>
	<td>683-767</td>
</tr>
<tr>
	<th>주소 :</th>
	<td>울산 북구 연암동 엘지진로아파트  102동 508호</td>
</tr>
<tr>
	<th>수령우편번호 :</th>
	<td>683-767</td>
</tr>
<tr>
	<th>수령주소 :</th>
	<td>울산 북구 연암동 엘지진로아파트  102동 508호</td>
</tr>
<tr>
	<th>E-Mail :</th>
	<td>beehero@hanmail.net</td>
</tr>
<tr>
	<th>메일수신여부 :</th>
	<td>Y</td>
</tr>
<tr>
	<th>후원자 FO :</th>
	<td>123482</td>
</tr>
<tr>
	<th>후원자 :</th>
	<td>유니시티</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
</td>
</tr>
</table>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left">&nbsp;</TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		<INPUT TYPE="button" VALUE="출력 하기" onClick="print();">	
		<INPUT TYPE="button" VALUE="닫 기" onClick="f_close();">	
	</TD>
</TR>
</TABLE>
</center>
</div>
 
</body>
</html>
