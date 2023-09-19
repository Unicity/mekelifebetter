<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$member_no	= str_quote_smart(trim($member_no));
	$mode				= str_quote_smart(trim($mode));

	$member_no = trim($member_no);
	$member_no = str_replace("^", "'",$member_no);

	if ($s_flag != "1") {
	
		$query = "update tb_userinfo_dup set 
					reg_status = '3',
					print_date = now(),
					print_ma = '$s_adm_name'
			where member_no in $member_no";
		
		mysql_query($query);

	}
		
	$query = "select * from tb_userinfo_dup where member_no in $member_no";
	$result = mysql_query($query);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
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

<?	while($row = mysql_fetch_array($result)) { ?>
<div class="a4">
<!--[if gte IE 7]><br style='height:0; line-height:0'><![endif]-->
<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 회원 가입 (회원 정보 입력용) </B></TD>
</TR>
</TABLE>
<table height='35' width='590' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN2">
<colgroup>
	<col width="150">
	<col width="440">
</colgroup>
<tr>
	<th>회원종류 :</th>
	<td><? if (trim($row[member_kind]) == "D" ) { echo " FO 회원"; } else {echo "소비자 회원";} ?></td>
</tr>
<tr>
	<th>성명 : </th>
	<td><?echo $row[name]?></td>
</tr>
<tr>
	<th>영문성명 : </th>
	<td><?echo $row[ename]?></td>
</tr>
<tr>
	<th>희망 비밀번호 :</th>
	<td><?echo $row[password]?></td>
</tr>
<tr>
	<th>주민등록번호 :</th>
	<td><?echo decrypt($key, $iv, $row[JU_NO01])?>-<?echo decrypt($key, $iv, $row[JU_NO02])?></td>
</tr>
<? if (trim($row[member_kind]) == "D") { ?>
<tr>
	<th>활동유형 :</th>
	<td>
		<? if (trim($row[active_kind]) == "1") {?>
        	후원사업자 (자가소비)
        <? } else {?>
         	소매이익사업자(부가가치 신고대상자) 
        <? }?>	
	</td>
</tr>
<?	} ?>

<? if (trim($row[couple]) == "Y") { ?>
<tr>
	<th>배우자 성명 :</th>
	<td><?echo $row[couple_name]?></td>
</tr>
<tr>
	<th>배우자 영문성명 :</th>
	<td><?echo $row[couple_ename]?></td>
</tr>
<tr>
	<th>주민등록번호 :</th>
	<td><?echo decrypt($key, $iv, $row[couple_reg_jumin1])?>-<?echo decrypt($key, $iv, $row[couple_reg_jumin2])?></td>
</tr>
<?	} ?>
<tr>
	<th>휴대전화번호 :</th>
	<td><?echo $row[hpho1]?>-<?echo $row[hpho2]?>-<?echo $row[hpho3]?></td>
</tr>
<tr>
	<th>전화번호 :</th>
	<td><?echo $row[pho1]?>-<?echo $row[pho2]?>-<?echo $row[pho3]?></td>
</tr>
<tr>
	<th>우편번호 :</th>
	<td><?echo $row[zip]?></td>
</tr>
<tr>
	<th>주소 :</th>
	<td><?echo $row[addr]?> <?echo $row[addr_detail]?></td>
</tr>
<tr>
	<th>수령우편번호 :</th>
	<td><?echo $row[del_zip]?></td>
</tr>
<tr>
	<th>수령주소 :</th>
	<td><?echo $row[del_addr]?> <?echo $row[del_addr_detail]?></td>
</tr>
<tr>
	<th>E-Mail :</th>
	<td><?echo $row[email]?></td>
</tr>
<tr>
	<th>메일수신여부 :</th>
	<td><?echo $row[email_flag]?></td>
</tr>
<? if (trim($row[member_kind]) == "D") { ?>
<tr>
	<th>거래 은행 :</th>
	<td><?echo $row[account_bank]?></td>
</tr>
<tr>
	<th>계좌번호 :</th>
	<td><?echo decrypt($key, $iv, $row[account])?></td>
</tr>
<? }?>
<tr>
	<th>후원자 FO :</th>
	<td><?echo $row[co_number]?></td>
</tr>
<tr>
	<th>후원자 :</th>
	<td><?echo $row[co_name]?></td>
</tr>
<tr>
	<th>본인외주문 동의 :</th>
	<td><?echo $row[sel_agree05]?></td>
</tr>



<tr>
	<th>회원수첩 이메일발송 동의 :</th>
	<td><?=$row[sel_agree06]?></td>
</tr>
<tr>
	<th>후원수당등 변경통지 :</th>
	<td>
		<?if ($row[agree_01] == "Y"){?>☑️<?}else{?>⬜<?}?> 이메일 &nbsp;
		<?if ($row[agree_02] == "Y"){?>☑️<?}else{?>⬜<?}?> SMS &nbsp; 
		<?if ($row[agree_03] == "Y"){?>☑️<?}else{?>⬜<?}?> 우편
	</td>
</tr>
<tr>
	<th>마케팅 동의 :</th>
	<td>
		<?if ($row[sel_agree02] == "Y"){?>☑️<?}else{?>⬜<?}?> 이메일 수신 &nbsp;
		<?if ($row[sel_agree03] == "Y"){?>☑️<?}else{?>⬜<?}?> SMS 수신 &nbsp; 
		<?if ($row[sel_agree04] == "Y"){?>☑️<?}else{?>⬜<?}?> 우편물 수신
	</td>
</tr>
</TABLE>
	</td>
</tr>
</table>
<br>
<br>
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
<?
	}
?>
</body>
</html>

<?
	mysql_close($connect);
?>