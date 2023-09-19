<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/06/21
	// 	Last Update : 2004/06/21
	// 	Author 		: Park, Chan Ho
	// 	History 	: 2004.06.21 by Park ChanHo 
	// 	File Name 	: sms_input.php
	// 	Description : SMS 등록 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$mode					= str_quote_smart(trim($mode));
	$seq_no				= str_quote_smart(trim($seq_no));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$sms_no				= str_quote_smart(trim($sms_no));

	if (!empty($sms_no)) {

		$query = "select * from tb_sms where sms_no = '".$sms_no."'";
		$result = mysql_query($query);
		$list = mysql_fetch_array($result);
		$sql_table = $list[sql_table];
		$sql_str1 = $list[sql_str1];
		$sql_str2 = $list[sql_str2];
		$sql_str3 = $list[sql_str3];
		$sms_name = $list[sms_name];
		$sms_kind = $list[sms_kind];
		$sms_admin = $list[sms_admin];
		$sms_goal = $list[sms_goal];
		$sms_memo = $list[sms_memo];
		$sms_data = $list[sms_data];
		$file_name = $list[file_name];
		$file_use = $list[file_use];
		$reg_date = $list[reg_date];
		$reg_date = date("Y-m-d [H:i]", strtotime($reg_date));


	} 	

	if (strlen($sql_table) > 3) {

		$query = "select count(*) cnt ".$sql_str3 ;
		$result = mysql_query($query);
		
		if ($result) {
			$list = mysql_fetch_array($result);
			$all_cnt = $list[cnt];
			$sql_flag = "1";
		} else {
			$sql_flag = "0";
		}
		
	}

	if (trim($file_use) == "Y") $sql_flag = "1";


	if (strlen($sms_data) > 1) {
		$is_data = "1";
	} else {
		$is_data = "0";
	}

?>		
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<style type="text/css">
<!--
A:link {COLOR: 0022B3;FONT-FAMILY: "굴림";FONT-SIZE: 9pt;text-decoration:none;}
A:visited{	COLOR: 0022B3;FONT-FAMILY: "굴림";	FONT-SIZE: 9pt;text-decoration:none;}
A:hover{COLOR: 108eff;FONT-FAMILY: "굴림";FONT-SIZE: 9pt;text-decoration:none;}
TD{COLOR: #636363;FONT-FAMILY: "굴림";FONT-SIZE: 9pt;line-height:1.5;}
-->
</style>
<title>* eMs for Unicity Korea   -  마케팅등록</title>
<script>
<!--

function go_next(url) {

	document.iform.action = url;
	document.iform.submit();
}


function send_test() {

	if (document.iform.sms_no.value == "") {
		alert("캠페인을 먼저 등록 하세요.");
		return;	
	}

<?
	if ($is_data == "0") {
?>
	alert("저장된 SMS내용이 없습니다.");
	return;	
<?
	}
?>

	if( document.iform.selected_list.length < 1) {
		alert("선택된 메일이 없습니다.  ");
	} else {

		selectallval();

		document.iform.action = 'sms_test_sending.php';
		document.iform.submit();
	}
}


function selectallval() {
	var txt = "";
	
	for( i=0;i<document.iform.selected_list.length;i++){
		document.iform.selected_list.options[i].selected = true;
		txt = txt + document.iform.selected_list.options[i].value + "|";
	}
	document.iform.select_phone.value = txt;
//	alert(document.iform.select_phone.value);

}

function add_mail(){
	var i = 0;

	var selected_value;	//	선택되어진 값
	var selected_text;	//	선택되어진 문자

	for( i=0;i<document.iform.org_list.length;i++){
		if( document.iform.org_list.options[i].selected == true ){
			selected_value = document.iform.org_list.options[i].value;
			selected_text = document.iform.org_list.options[i].text;

//	값 소멸, 생성
			document.iform.selected_list.options[document.iform.selected_list.length] = new Option(selected_text,selected_value,false,false);
			document.iform.org_list.options[i] = null;
			i--;
		}	
	}
}

function del_mail(){
	var i = 0;

	var selected_value;	//	선택되어진 값
	var selected_text;	//	선택되어진 문자

	for( i=0;i<document.iform.selected_list.length;i++){
		if( document.iform.selected_list.options[i].selected == true ){
			selected_value = document.iform.selected_list.options[i].value;
			selected_text = document.iform.selected_list.options[i].text;

//	값 소멸, 생성
			document.iform.org_list.options[document.iform.org_list.length] = new Option(selected_text,selected_value,false,false);
			document.iform.selected_list.options[i] = null;
			i--;
		}
	}

}

// 조회된 Record를 insert 한다.
function showMessage()
{
add_mail();
if( document.iform.msg.value.length > 2 )
alert(document.iform.msg.value);
document.iform.msg.value = "";
}
-->
</script>
</head>
<body bgcolor="F5F5E6" text="#000000" leftmargin="0" topmargin="0" onload="javascript:showMessage()">

<?php include "common_load.php" ?>

<form name= "iform" method="post">
<!-- 전체테이블시작-->
<TABLE cellspacing='0' cellpadding='0' width=100%>
<TR>
	<TD>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr valign="top">
			<td height="10" bgcolor="A39674"></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC">
				<tr height="24" bgcolor="#FFFFFF" valign="middle">
					<td colspan="2" align="right"><font color="#0099CC">&gt;&gt;캠페인 등록</font>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr bgcolor="D7D1BB">
					<td colspan="2" height="1"></td>
				</tr>
				<tr bgcolor="#F9F9F9">
					<td bgcolor="FBFBF5" colspan="2">
						<table width="639" border="0" cellspacing="0" cellpadding="0" style=padding-left:15pt;>
						<tr valign=bottom>
							<td height="23" width="80" style=padding-left:10pt;><b>
								<font color="#666666"><img src="http://210.116.103.148:8080/img/regist_icon.gif" width="3" height="3" align='absmiddle'>
								캠페인 명</font></b>
							</td>
							<td height="23" width="228" style=padding-left:10pt;>
								<?echo $sms_name?>
							</td>
							<td height="23" width="59" style=padding-left:10pt;><b>
								<font color="#666666"><img src="http://210.116.103.148:8080/img/regist_icon.gif" width="3" height="3" align='absmiddle'>
								등록일</font></b>
							</td>
							<td height="23" width="272" style="padding-left:10pt;" align='absmiddle'>
								<?echo $reg_date?>
							</td>
						</tr>
						<tr valign=top>
							<td width="80" style=padding-left:10pt; height="23"><b>
								<font color="#666666"><img src="http://210.116.103.148:8080/img/regist_icon.gif" width="3" height="3" align='absmiddle'>
								당담자</font></b></td>
							<td width="228" style=padding-left:10pt; height="23">
								<?echo $sms_admin?>
							</td>
							<td width="59" style=padding-left:10pt; height="23"><b>
								<font color="#666666">&nbsp;</font></b></td>
							<td width="272" style=padding-left:10pt; height="23">
								&nbsp;
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr bgcolor="D7D1BB">
					<td colspan="2" height="1"></td>
				</tr>
				</table>
			</td>
		</tr>
		<tr bgcolor="D7D1BB">
			<td colspan="2" height="1"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td class=ems09><img border='0'  src='http://210.116.103.148:8080/img/trans.gif' width=1 height=5></td>
</tr>
<tr style=padding-left:15pt;>
	<td>
		<table cellspacing='0' cellpadding='0' border='0' bordercolor="#999999" width='540'>
		<tr>
			<td width='80' valign="bottom"><a href='javascript:go_next("sms_input.php")'><img src="http://210.116.103.148:8080/img/tab01_dw.gif" width="80" height="34" border="0"></a></td>
			<td width='95' valign="bottom"><a href='javascript:go_next("sms_data.php")'><img src="http://210.116.103.148:8080/img/tab03_dw.gif" width="95" height="34" border="0"></a></td>
			<td width='115' valign="bottom"><a href='javascript:go_next("sms_select_target.php")'><img src="http://210.116.103.148:8080/img/tab04_dw.gif" width="115" height="34" border="0"></a></td>
			<td width='115' nowrap><img src="http://210.116.103.148:8080/img/tab05_up.gif" width="115" height="34" border="0"></td>
			<td width='63' valign="bottom"><a href='javascript:go_next("sms_real_send.php")'><img src="http://210.116.103.148:8080/img/tab06_dw.gif" width="63" height="34" border="0"></a></td>
			<td width='72' valign="bottom"><a href='javascript:go_next("sms_report.php")'><img src="http://210.116.103.148:8080/img/tab07_dw.gif" width="72" height="34" border="0"></a></td>
		</tr>
		</table>
	</td>
</tr>
<tr style=padding-left:15pt;>
	<td>
		<table width=540 border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="1" bgcolor="#000000"></td>
			<td align='center'>
				<table cellspacing='0' cellpadding='0' width='538' bgcolor='#FFFFFF' border=0>
				<tr>
					<td class=ems09 width='538' align='center'>
		<!--	위 공백-->
						<table cellspacing='0' cellpadding='0' border=0>
						<tr>
							<td class=ems09 width=500 align='center'><br>
								<table cellspacing='0' cellpadding='0' border='0' >
								<tr>
									<td class='ems09' colspan='2'><b>[ 테스트 리스트 ]</b>
										<img border='0'  src='http://210.116.103.148:8080/img/trans.gif' width='200' height='1' align='absmiddle'>
									</td>
									<td class='ems09' colspan='2'><b>[ 테스트 대상 ]</b>
									</td>
								</tr>
								<tr>
									<td class=ems09 ><img border='0'  src='http://210.116.103.148:8080/img/trans.gif' width=1 height=2>
									</td>
								</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class=ems09 width=498 align='center'>
								<table cellspacing='0' cellpadding='0' border='0' >
								<tr>
									<td class='ems09' >
										<select name='org_list' size='8' style=" WIDTH: 200px" multiple>
<?	
		$query = "select * from tb_admin where Phone2 <> '' order by UserName desc ";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)) {

?>
											<option value="<?echo $row[Phone2]?>"><?echo $row[UserName]?> (<?echo $row[Phone2]?>)</option>
<?
		}
?>

										</select>
									</td>
									<td align=center width='100'><div>
										<a href='javascript:add_mail()'><img border='0' src='http://210.116.103.148:8080/img/arrowbut06_1209.gif'></a></div><img border='0' src='http://210.116.103.148:8080/img/trans.gif' height=7 width=1></a></div><div>
										<a href='javascript:del_mail()'><img border='0' src='http://210.116.103.148:8080/img/arrowbut07_1209.gif'></a></div>
									</td>
									<td class='ems09'>
										<select name='selected_list' size='8' style=" WIDTH: 200px" multiple>
										</select>
									</td>
								</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class=ems09><img border='0'  src='http://210.116.103.148:8080/img/trans.gif' width=498 height=25></td>
						</tr>
						</table>
					</td>
				</tr>

<!-- 공백 끝-->
<!--  버튼-->
				<tr>
					<td class=ems09 colspan=2>
						<table cellspacing='0' cellpadding='0' width=538>
						<tr>
							<td class='ems09'><div align='center' class='ems09'>
								<a href='javascript:send_test()'><img src='http://210.116.103.148:8080/img/dwems_button_testsend_1206.gif' border='0' alt='시험발송'></a></div>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class=ems09 colspan=2>
						<table cellspacing='0' cellpadding='0' width=500>
						<tr><td class=ems09  ><img border='0'  src='http://210.116.103.148:8080/img/trans.gif' width=500 height=25></td></tr>
						</table>
					</td>
				</tr>
				</table>

			</td>
			<td bgcolor="#000000" width="1"></td>
		</tr>
		<tr>
			<td colspan="3" bgcolor="#000000" height="1"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<!--내용부분box 끝-->
<!-- 전체테이블끝-->
<input type='hidden' name='sms_no' value='<?echo $sms_no?>'>
<input type='hidden' name='select_phone' value=''>
<input type='hidden' name='msg' value=''>
</form>
</body>
</html>
<?
mysql_close($connect);
?>