<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_input.php
	// 	Description : 관리자 추가 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	logging($s_adm_id,'open sms register page (sms_input.php)');

	$mode					= str_quote_smart(trim($mode));
	$seq_no				= str_quote_smart(trim($seq_no));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));

	if ($seq_no) {

		$query = "select seq_no, title, contents, callback, sms_type, send_state, reg_date from tb_sms_master where seq_no = '".$seq_no."' and del_tf='N'";
	
		$result = mysql_query($query);
		$list = mysql_fetch_array($result);

		$seq_no			= $list[seq_no];
		$title			= $list[title];
		$contents		= $list[contents];
		$callback		= $list[callback];
		$sms_type		= $list[sms_type];
		$send_state	= $list[send_state];
		$reg_date		= $list[reg_date];


		if($seq_no == ""){
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">
				alert('조회 대상이 없습니다.');
				history.back();
				</script>";
			exit;
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
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="sms_list.php";
		document.frm.submit();
	}

	function goIn() {
		
		var seq_no = "<?=$seq_no?>";

		if (document.frm.title.value == "") {
			alert("제목을 입력하세요.");
			document.frm.title.focus();
			return;
		}

		if (document.frm.callback.value == "") {
			alert("보내는 전화번호를 입력하세요.");
			document.frm.callback.focus();
			return;
		}

		if (document.frm.contents.value == "") {
			alert("내용을 입력하세요.");
			document.frm.contents.focus();
			return;
		}
		
		if (seq_no == "") {
			document.frm.mode.value = "add";
		} else {
			document.frm.mode.value = "mod";
			document.frm.seq_no.value = seq_no;
		}

		document.frm.target = "frhidden";
		document.frm.action = "sms_db.php";
		document.frm.submit();
		
	}

	function cal_pre(tmpForm,flag) {
		var max_len=0;
		var frm;

		if(tmpForm == 'frm'){
			max_len=80;
		}else{
			max_len=130;
		}
		if(flag == 'Y'){
			frm='parent';
		} else if(flag == 'K'){
			frm='wrt_frm';
		} else{
			frm='document';
		}
		cal_byte(tmpForm,max_len,frm);
	}

	function cal_byte(aquery,max_len,frm) {
		var tmpStr;
		var byte;
		var temp=0;
		var onechar;
		var tcount;
		var cnt=0;
		tcount = 0;

		tmpStr = eval(frm+"."+aquery+".contents");
		byte = 	 eval(frm+"."+aquery+".cbyte");

		//	alert(tmpStr);
	
		temp = tmpStr.value.length;

		msg_length = cal_msglen(tmpStr.value,aquery,frm);
		byte.value = msg_length;

		if (byte.value > max_len) {
			alert("메시지 내용은"+max_len+"바이트 이상은 전송하실수 없습니다.\r\n 쓰신 메세지는 "+(msg_length - max_len)+"바이트가 초과되었습니다.\r\n 초과된 부분은 자동으로 삭제됩니다.");

			tmpStr.value = assert_msglen(tmpStr.value, max_len);
			byte.value = cal_msglen(tmpStr.value);
			return;
		}
	}

	function assert_msglen(tmpStr, maximum) {
		var k = 0;
		var tcount = 0;
		var onechar;
		var msglen = tmpStr.length;

		for(k=0;k<msglen;k++) {
			onechar = tmpStr.charAt(k);
			if(escape(onechar).length > 4) {
				tcount += 2;
			} else {
				tcount++;
			}

			if(tcount>maximum) {
				tmpStr = tmpStr.substring(0,k-1);
				break;
			}
		}
		return tmpStr;
	}

	function cal_msglen(message,aquery,frm) {
		var nbytes = 0;
		var cnt=0;

		for (i=0; i<message.length; i++) {
			var ch = message.charAt(i);
			if (escape(ch).length > 4) {			
				if(aquery == 'gsms'){cnt = han_ck(aquery,nbytes,frm);}
				nbytes += 2;
				nbytes -= cnt;

			} else {
				nbytes++;
			}
		}

		return nbytes;
	}

	function han_ck(aquery,tcount,frm) {
		var tmpStr;
		var temp=0;
		var cnt=2;
	
		tmpStr = eval(frm+"."+aquery+".SND_MSG");
		temp = tmpStr.value.length;

		tmpStr = tmpStr.value.substring(0,tcount);
		alert('해외 전송에서 한글은 전송하지 않습니다');

		var msg = eval(frm+"."+aquery+".SND_MSG");
		msg.value = tmpStr;
		return cnt;
	}

	function js_chk_sms() {
		var frm = document.frm;
		if (frm.sms_type.checked == true) {
			frm.contents.value = frm.sms_msg.value;
			frm.callback.value = frm.callback_msg.value;
		} else {
			frm.contents.value = "";
			frm.callback.value = "";
		}
		cal_pre('frm','N');
	}
//-->
</SCRIPT>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<form name='frm' method='post' action='admin_db.php'>
<input type="hidden" name="seq_no" value="<?=$seq_no?>">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>SMS 관리 (등록)</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="등록" name="btn3">
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
		제목 :
	</th>
	<td>
		<input type="text" name="title" size="35" value="<?echo $title?>">
	</td>
</tr>
<tr>
	<th>
		보내는 전화번호 :
	</th>
	<td>
		<input type="text" name="callback" size="35" value="<?echo $callback?>"> &nbsp; 회원관련 : 0424821860
	</td>
</tr>
<tr>
	<th>
		내용 :
	</th>
	<td>
		<textarea cols='35' rows='10'  name='contents'  onkeyup="javascript:cal_pre('frm','N')" style="text-size:9pt;OVERFLOW: hidden;BACKGROUND-COLOR: #FFFFFF; BORDER-BOTTOM-WIDTH: 0px; BORDER-LEFT-WIDTH: 0px; BORDER-RIGHT-WIDTH: 0px; BORDER-TOP-WIDTH: 0px; colorvc_resdate: #000000" ><?echo $contents?></textarea>
		<input type=text name="cbyte"  readonly value=0 size=2 style='background-color:#CCCCCC; border-width:0; border-color:#000000; border-style:solid;text-align:right;'>/<input type=text size=2 readonly style='background-color:#CCCCCC; border-width:0; border-color:#000000; border-style:solid;text-align:right;' value='80'> Byte
		<br />
		* 회원 번호가 들어갈 경우 ##NUMBER 로 입력 하세요<br />
		* 이름이 들어갈 경우 ##NAME 로 입력 하세요<br />
	</td>
</tr>
<tr>
	<th>
		메시지 :
	</th>
	<td>
		<input type="checkbox" name="sms_type" value="MEM" <? if ($sms_type == "MEM") echo "checked"?> onClick="js_chk_sms();"> 회원가입 기본문구 삽입
		<input type="hidden" name="sms_msg" value='유니시티코리아 회원가입이 완료되었습니다 회원번호 "##NUMBER"입니다 감사합니다'>
		<input type="hidden" name="callback_msg" value='0424821860'>
	</td>
</tr>

</TABLE>
	</td>
</tr>
</table>
<input type="hidden" name="mode" value="add">
<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
</FORM>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>