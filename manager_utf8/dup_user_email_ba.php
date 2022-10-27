<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./config/AES.php";

	$member_no				= str_quote_smart(trim($member_no));

	$query = "select * from tb_userinfo_dup where member_no = $member_no";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$member_no = $list[member_no];
	$number = $list[number];
	$password = $list[password];
	$name = $list[name];
	$email = $list[email];
	$reg_jumin1 = $list[reg_jumin1];
	$reg_jumin2 = $list[reg_jumin2];
	$tax_number = $list[tax_number];
	$hpho1 = $list[hpho1];
	$hpho2 = $list[hpho2];
	$hpho3 = $list[hpho3];
	$reg_status = $list[reg_status];
	$email_date = $list[email_date];
	$email_ma = $list[email_ma];
	$member_kind = $list[member_kind];
	$auth_type = $list[auth_type];
	$k_id = $list[k_id];

//암호화 키 재설정
$enckey = hex2bin("12345678901234567890123456789077");
$enciv = hex2bin("12345678901234567890123456789011");
	$decPassword = decrypt($enckey, $enciv, $password);
	
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script language="javascript">
	
	function goEmail() {

		var authType = '<?echo $auth_type?>';
		var passW = '<?echo $decPassword?>';
		var memNo = '<?echo $member_no?>';
		var k_id = '<?echo $k_id ?>';
		
		if(authType == 'K'){
			var data =  btoa(JSON.stringify({ username: memNo, password: passW }));	

			$.ajax({
				'type':'GET',
				'crossOrigin': true,
				'headers' : {
               	 	'Content-Type' : 'application/json'
            	},
				'url':'https://member-calls2-kr.unicity.com/authorization-v2/createUserSocial?accessToken=""&refreshToken=""&socialId='+k_id+'&userId='+memNo+'&user='+data,
				'dataType' : 'json',
				'success':function (result) {
			
					document.frmSearch.action = "dup_send_email.php";
					document.frmSearch.submit();

				},
				'error':function (result) {
						alert("결과 : " + result);
				
				}
			});
		}else{
			document.frmSearch.action = "dup_send_email.php";
			document.frmSearch.submit();
		}

		
	}

	function goEmailTest(){
	
		var authType = '<?echo $auth_type?>';
		var passW = '<?echo $decPassword?>';
		var memNo = '<?echo $member_no?>';
		//var memNo = '209415382';
		var k_id = '<?echo $k_id ?>';
	


		var data =  btoa(JSON.stringify({ username: memNo, password: passW }));	

			$.ajax({
				'type':'GET',
				'crossOrigin': true,
				'headers' : {
               	 	'Content-Type' : 'application/json'
            	},
				'url':'https://member-calls2-kr.unicity.com/authorization-v2/createUserSocial?accessToken=""&refreshToken=""&socialId='+k_id+'&userId='+memNo+'&user='+data,
				'dataType' : 'json',
				'success':function (result) {
			
						alert("성공");

				},
				'error':function (result) {
						alert("실패");
				
				}
			});
	}
	
</script>
</HEAD>
<BODY>
<table border=0 width=100%>
	<tr>
		<td align="center">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>E-Mail 다시보내기</B> <?=$_SERVER['REMOTE_ADDR']?></TD>
</TR>
</TABLE>
<FORM name="frmSearch" method="post">
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<input type="hidden" name="member_no" value="<?echo $member_no?>">
<input type="hidden" name="munja" value="<?echo $munja?>">
<tr>
	<th>이름 : </th>
	<td><?echo $name?></td>
</tr>
<!--tr>
	<th>주민등록번호 :</th>
	<td><?echo $reg_jumin1?>-<?echo $reg_jumin2?></td>
</tr-->
<tr>
	<th>회원종류 :</th>
	<td><? if (trim($member_kind) == "D" ) { echo " FO 회원"; } else {echo "소비자 회원";} ?></td>
</tr>
<!--tr>
	<th>비밀번호 :</th>
	<td><?echo $password?></td>
</tr-->
<tr>
	<th>FO Number :</th>
	<td><?echo $number?></td>
</tr>
<tr>
	<th>E-Mail :</th>
	<td><?echo $email?></td>
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
		<INPUT TYPE="button" VALUE="보내기" onClick="goEmail();">	
		<INPUT TYPE="button" VALUE="닫 기" onClick="self.close();">	
		<!--<INPUT TYPE="button" VALUE="test보내기(절대 누르지 마세요)" onClick="goEmailTest();">-->
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