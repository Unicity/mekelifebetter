<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";

	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	function getCodeName ($code, $parent)  { 

		$sqlstr = "SELECT name FROM tb_code where parent='".$parent."' and code='".$code."'"; 

		$result = mysql_query($sqlstr);
		$list = mysql_fetch_array($result);

		$name = $list[name];

		print($name);
	}

	//if(getRealClientIp() == "121.190.224.191"){


	/*
	//회원번호 없는 회원 중복테이블로 이관처리 - 처리상태 통신장애(F)로 처리
		$result = mysql_query("select * from tb_userinfo where ifnull(number,'') = '' and member_no > 631638") or die(mysql_error());		
		while($row=mysql_fetch_object($result)) {
			//print_r($row);
			$sql = "";
			$i = 1;
			foreach($row as $key=>$val) {
				if($key != 'member_no' && $key != 'number' && $key != 'number'){
					if($key == "sms_send_flag"){
						$sql .= "api_fail = 'N',";
					}else if($key == "reg_status"){
						$sql .= "reg_status = 'F',";
					}else{
						$sql .= $key." = '".$val."',";
					}
					$i++;
				}				
			}

			if($sql != "") $sql = "insert into tb_userinfo_dup set ".substr($sql, 0, strlen($sql) -1);
			if(mysql_query($sql)){
				mysql_query("delete from tb_userinfo whree member_no = '".$row['member_no']."'");				
			}
		}
	//}
	*/

	logging($s_adm_id,'open new member list (user_list.php)');

	$idxfield				= str_quote_smart(trim($idxfield));
	$con_sort				= str_quote_smart(trim($con_sort));
	$con_order			= str_quote_smart(trim($con_order));

	if ($con_order == "con_a") {
		$order = "asc";
		$con_order = "con_a";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}

	$from_date			= str_quote_smart(trim($from_date));
	$to_date				= str_quote_smart(trim($to_date));
	$r_status				= str_quote_smart(trim($r_status));
	$r_memberkind		= str_quote_smart(trim($r_memberkind));
	$r_join_kind		= str_quote_smart(trim($r_join_kind));
	$r_active_kind	= str_quote_smart(trim($r_active_kind));
	$r_couple				= str_quote_smart(trim($r_couple));
	$qry_str				= str_quote_smart(trim($qry_str));
	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));
	$reg_status			= str_quote_smart(trim($reg_status));

	$toDay = date("Y-m-d");

	if (empty($idxfield)) {
		$idxfield = "0";
	} 

	if (empty($con_sort)) {
		$con_sort = "regdate";
	}

	if (empty($con_order)) {
		$order = "desc";
	}

	if (!empty($from_date)) {
		$que = " and regdate >= '$from_date' ";		
	}

	if (!empty($to_date)) {
		$que = $que." and date_sub(regdate, interval 1 day) <= '$to_date' ";	
		//$que = $que." and regdate <= '$to_date' ";		
	}

	if ((empty($r_status)) || ($r_status == "A")) {
		$r_status = "A";
	} else {
		$que = $que." and reg_status = '$r_status' ";		
	}

	if ((empty($r_memberkind)) || ($r_memberkind == "A")) {
		$r_memberkind = "A";
	} else {
		$que = $que." and member_kind = '$r_memberkind' ";		
	}

	if ((empty($r_join_kind)) || ($r_join_kind == "A")) {
		$r_join_kind = "A";
	} else {
		$que = $que." and join_kind = '$r_join_kind' ";		
	}

	if ((empty($r_active_kind)) || ($r_active_kind == "A")) {
		$r_active_kind = "A";
	} else {
		if ($r_active_kind == "Y") {
			$que = $que." and active_kind = '0' ";
		} else {
			$que = $que." and active_kind = '1' ";
		}		
	}

	if ((empty($r_couple)) || ($r_couple == "A")) {
		$r_couple = "A";
	} else {
		$que = $que." and couple = '$r_couple' ";		
	}
			
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = $que." and name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = $que." and account like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = $que." and number like '%$qry_str%' ";
		} else if ($idxfield == "3") {
			$que = $que." and addr like '%$qry_str%' ";			
		} else if ($idxfield == "4") {
			$jumin1 = substr($qry_str,0,6);
			$jumin2 = substr($qry_str,6);
		//	$que = $que." and reg_jumin1 = '$jumin1' and reg_jumin2 = '$jumin2' ";			
			$que = $que." and ((concat(reg_jumin1,reg_jumin2) like '%$qry_str%') or (concat(birth_y,birth_m,birth_d) like '%$qry_str%')) ";
		}
		logging($s_adm_id,'search user '.$que);
	}

	if ($page <> "") {
		$page = (int)($page);
	} else {
		$page = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;
	
	$offset = $nPageSize*($page-1);

	$query = "select count(*) from tb_userinfo where 1 = 1 ".$que;

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	logging($s_adm_id,'search user count '.$TotalArticle);
	
	$query2 = "select * from tb_userinfo where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
	$result2 = mysql_query($query2);


	$now_que = " and date_format(date_sub(regdate, interval 0 day),'%Y-%m-%d') = date_format(now(),'%Y-%m-%d') ";	
	$now_query = "select count(*) from tb_userinfo where 1 = 1 ".$now_que;

	$now_result = mysql_query($now_query,$connect);
	$now_row = mysql_fetch_array($now_result);
	$now_TotalArticle = $now_row[0];
	
	//echo $query2;

//	$query3 = "select count(*) from tb_userinfo where member_no > '' ".$que. "  ";
//	$result3 = mysql_query($query3,$connect);
//	$row3 = mysql_fetch_array($result3);
//	$TotalArticle3 = $row3[0];
	/*
	echo $query;
	echo $query2."<br>";
	echo $now_query."<br>";
	*/
	
	$ListArticle = $nPageSize;
	$PageScale = $nPageSize;
	$TotalPage = ceil($TotalArticle / $ListArticle);		// 총 페이지수

	if (!$TotalPage)
		$TotalPage = 0;

	if (empty($page))
		$page = 1;


	# 이전 페이지
	$Prev = $page - 1;
	if ($Prev < 0)
		$Prev = 0;

	# 다음 페이지
	$Next = $page + 1;
	if ($Next > $TotalPage)
		$Next = $TotalPage;

	# 현재 보여줄 글의 개수 계산
	$First = $ListArticle * $Prev;
	$Last = $First + $ListArticle;
	if ($Last > $TotalArticle)
		$Last = $TotalArticle;

	$Scale = floor($page / ($ListArticle * $PageScale));

	# 게시물 번호
	$NumberArticle = $TotalArticle - $First;
?>
<html>
<head>
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- <meta http-equiv="X-Frame-Options" content="deny" /> -->
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">

function check_data(){

	for(i=0; i < document.frmSearch.r_status.length ; i++) {
		if (document.frmSearch.r_status[i].checked == true) {
			document.frmSearch.reg_status.value = document.frmSearch.r_status[i].value;
		}
	}
			
	for(i=0; i < document.frmSearch.rsort.length ; i++) {
		if (document.frmSearch.rsort[i].checked == true) {
			document.frmSearch.con_sort.value = document.frmSearch.rsort[i].value;
		}
	}

	for(i=0; i < document.frmSearch.rorder.length ; i++) {
		if (document.frmSearch.rorder[i].checked == true) {
			document.frmSearch.con_order.value = document.frmSearch.rorder[i].value;
		}
	}

	document.frmSearch.action="user_list.php";
	document.frmSearch.submit();
}

function onSearch(){


	var sYYYY = "";
	var sMM = "";
	var sDD = "";
	
	if (document.frmSearch.from_date.value != "") {
		if (document.frmSearch.from_date.value.length != 10 ) {
			alert("날짜의 형식은 20040420으로 입력 하셔야 합니다");
			document.frmSearch.from_date.focus();
			return;
		} else {

			sYYYY = document.frmSearch.from_date.value.substr(0,4);

			sMM = document.frmSearch.from_date.value.substr(5,2);
			
			if (sMM.substr(0,1) == 0) {
				sMM = sMM.substr(1,1);			
			}
			
			sDD = document.frmSearch.from_date.value.substr(8,2);

			if (sDD.substr(0,1) == 0) {
				sDD = sDD.substr(1,1);			
			}
						
			if (!isDate(sYYYY, sMM, sDD)) {
				document.frmSearch.from_date.focus();
				return;
			}

		}
	}

	if (document.frmSearch.to_date.value != "") {
		if (document.frmSearch.to_date.value.length != 10 ) {
			alert("날짜의 형식은 20040420으로 입력 하셔야 합니다");		
			document.frmSearch.to_date.focus();
		} else {

			sYYYY = document.frmSearch.to_date.value.substr(0,4);
			sMM = document.frmSearch.to_date.value.substr(5,2);

			if (sMM.substr(0,1) == 0) {
				sMM = sMM.substr(1,1);			
			}

			sDD = document.frmSearch.to_date.value.substr(8,2);

			if (sDD.substr(0,1) == 0) {
				sDD = sDD.substr(1,1);			
			}

			if (!isDate(sYYYY, sMM, sDD)) {
				document.frmSearch.to_date.focus();
				return;
			}

		}	
	}
	
	for(i=0; i < document.frmSearch.rsort.length ; i++) {
		if (document.frmSearch.rsort[i].checked == true) {
			document.frmSearch.con_sort.value = document.frmSearch.rsort[i].value;
		}
	}

	for(i=0; i < document.frmSearch.rorder.length ; i++) {
		if (document.frmSearch.rorder[i].checked == true) {
			document.frmSearch.con_order.value = document.frmSearch.rorder[i].value;
		}
	}

	document.frmSearch.page.value="1";
	document.frmSearch.action="user_list.php";
	document.frmSearch.submit();
}

function onView(id) {
	document.frmSearch.member_no.value = id; 
	document.frmSearch.action= "user_view.php";
	document.frmSearch.submit();
}

function onView2(id) {
	document.frmSearch.member_no.value = id; 
	document.frmSearch.action= "user_view_v2.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "user_input.php";
	document.frmSearch.submit();
}

function goSort() {
	
	document.frmSearch.sort.value = sort;
	document.frmSearch.submit();

}

function goPage(i) {
	document.frmSearch.page.value = i;
	document.frmSearch.submit();
}

function goDel() {

	var check_count = 0;
	var total = document.frmSearch.length;
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.elements[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("삭제하실 회원을 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.UserIDs.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "user_db.php";
		document.frmSearch.submit();
	}
	else {
		return;
	}

}

function goInputAllBA(){
	
	var check_count = 0;
	var total = document.frmSearch.no.length;

	if (total != null) {
		var num=0;
		for(var i=0; i<total; i++) {
			if(document.frmSearch.banumber[i].value != "") {
				num++;
			}
		}
		if(num < 1){
			alert('FO번호를 입력해주세요');
			return;
		}

	} else {
		var num=0;
	
		if(document.frmSearch.banumber.value == "") {
			alert('FO번호를 입력해주세요');
			return;
		}
	}

	document.frmSearch.member_nos.value = getMember_Nos();
	document.frmSearch.numbers.value = getNumbers();
	
	bDelOK = confirm("입력하신 정보가 모두 맞는지 확인 되었나요?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.action = "user_ba_db2.php";			
		document.frmSearch.submit();
	} else {
		return;
	}

}

function getNumbers(){

		var sValues = "";

		if(frmSearch.banumber != null){
			if(frmSearch.banumber.length != null){

				for(i=0; i<frmSearch.banumber.length; i++){
				//	if(sValues != ""){
					if(i>0){
						sValues += "|";
					}
					sValues += frmSearch.banumber[i].value;
				}
			}else{
				sValues += frmSearch.banumber.value;
			}
		}
		return sValues;
	}

function getMember_Nos(){

		var sValues = "";

		if(frmSearch.no != null){
			if(frmSearch.no.length != null){
				for(i=0; i<frmSearch.no.length; i++){
					if(sValues != ""){
						sValues += "|";
					}
					sValues += frmSearch.no[i].value;
				}
			}else{
				sValues += frmSearch.no.value;
			}
		}
		return sValues;
	}

function goConfirm() {

	var check_count = 0;
	
	if (document.frmSearch.CheckItem == null ) {
		alert("본인 여부 승인 회원이 없습니다.");
	    return;		
	}

	var total = document.frmSearch.CheckItem.length;
	
	if (document.frmSearch.CheckItem.length == null) {
		if(document.frmSearch.CheckItem.checked == true) {
	    	check_count++;
	    }
	}
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.CheckItem[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("본인여부 승인하실 회원을 선택해 주십시오.");
	    return;
	}
	
	document.frmSearch.member_no.value = getIds();

	var url = "user_confirm.php?member_no="+document.frmSearch.member_no.value;

	NewWindow(url, "print_page", '700', '500', "yes");
	
}


function goPrint() {

	var check_count = 0;
	
	if (document.frmSearch.CheckItem == null ) {
		alert("출력하실 회원이 없습니다.");
	    return;		
	}

	var total = document.frmSearch.CheckItem.length;
	
	if (document.frmSearch.CheckItem.length == null) {
		if(document.frmSearch.CheckItem.checked == true) {
	    	check_count++;
	    }
	}
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.CheckItem[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("출력하실 회원을 선택해 주십시오.");
	    return;
	}
	
	document.frmSearch.member_no.value = getIds();

	var url = "user_print.php?member_no="+document.frmSearch.member_no.value;

	NewWindow(url, "print_page", '700', '500', "yes");
	
}

function goPrint2() {

	var check_count = 0;
	
	if (document.frmSearch.CheckItem == null ) {
		alert("출력하실 회원이 없습니다.");
	    return;		
	}

	var total = document.frmSearch.CheckItem.length;
	
	if (document.frmSearch.CheckItem.length == null) {
		if(document.frmSearch.CheckItem.checked == true) {
	    	check_count++;
	    }
	}
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.CheckItem[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("출력하실 회원을 선택해 주십시오.");
		return;
	}
	
	document.frmSearch.member_no.value = getIds();

	var url = "user_print_test.php?member_no="+document.frmSearch.member_no.value;

	NewWindow(url, "print_page", '700', '500', "yes");
	
}

function goInputBA() {

	var check_count = 0;

	if (document.frmSearch.CheckItem == null ) {
		alert("입력하실 회원이 없습니다..");
	    return;		
	}
	var total = document.frmSearch.CheckItem.length;

	if (document.frmSearch.CheckItem.length == null) {
		if(document.frmSearch.CheckItem.checked == true) {
	    	check_count++;
	    }
	}
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.CheckItem[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
	    return;
	}
	
	document.frmSearch.member_no.value = getIds();

	var url = "user_input_ba.php?member_no="+document.frmSearch.member_no.value;

	NewWindow(url, "input_page", '700', '500', "yes");
	
}

function NewWindow(mypage, myname, w, h, scroll) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function getIds(){
	var sValues = "(";
	if(frmSearch.CheckItem != null){
		if(frmSearch.CheckItem.length != null){
			for(i=0; i<frmSearch.CheckItem.length; i++){
				if(frmSearch.CheckItem[i].checked == true){
					if(sValues != "("){
						sValues += ",";
					}
					sValues +="^"+frmSearch.CheckItem[i].value+"^";
				}
			}
		}else{
			if(frmSearch.CheckItem.checked == true){
				sValues += "^"+frmSearch.CheckItem.value+"^";
			}
		}
	}
	sValues  +=")";
	return sValues;
}

function isDate(sYYYY, sMM, sDD){
		
	var bOk = false;
	
//	if(sYYYY=="" || sMM=="" || sDD==""){
//		alert("'년' '월' '일' 을 모두 입력 하여야 합니다.");
//	}else 
	if(sYYYY.length != 4){
		alert("'년' 은 숫자 4자로 입력 가능 합니다.");
	}else if(!isNum(sYYYY)){
		alert("'년' 은 숫자만 가능 합니다.");
	}else if(!isNum(sMM)){
		alert("'월' 은 숫자만 가능 합니다.");
	}else if(!isNum(sDD)){
		alert("'일' 은 숫자만 가능 합니다.");
	}else if(parseInt(sMM)>12 || parseInt(sMM)<1){
		alert("'월' 은 1~12 숫자만 가능 합니다.");
	}else{
	
		sMM = sMM.length==1?"0"+sMM:sMM;
		var iMaxDD = getLastDay(sYYYY, sMM);
		if(parseInt(sDD)<1 || parseInt(sDD)>iMaxDD){
			alert("'일' 은 1~"+iMaxDD+" 까지 가능 합니다.");
		}else
			bOk = true;
	}
	
	return bOk;
}
	
function getLastDay(sYYYY, sMM){
	var nLastDay = 0;
	var cYear = sYYYY;
	var cMonth = sMM;
	

	if ((cMonth=="01")||(cMonth=="03")||(cMonth=="05")||(cMonth=="07")||(cMonth=="08")||(cMonth=="10")||(cMonth=="12"))
		nLastDay = 31;
	else	nLastDay = 30;

	if (cMonth=="02")	{
		if (parseFloat(cYear/4)==parseInt(cYear/4))	{
			if (parseFloat(cYear/100)==parseInt(cYear/100))
				if (parseFloat(cYear/400)==parseInt(cYear/400))
					nLastDay=29;
				else	nLastDay=28;
			else	nLastDay=29;
		}
		else	nLastDay=28;
	}
	
	return nLastDay;
}
function isNum(sNum){
	var bOk = true;
	for(i=0; i<sNum.length; i++){
		if(sNum.charAt(i)<'0' || sNum.charAt(i)>'9'){
			bOk = false;
		}
	}
	return bOk;
}


function goEmail(member_no) {
	var url = "user_email_ba.php?member_no="+member_no+"&munja=Y";
	NewWindow(url, "email_page", '700', '280', "no");
}


function goReSend(member_no) {
	
	bDelOK = confirm('재전송 하시겠습니까?');
	
	if (bDelOK==true) {
		var url = "user_reg_ba.php?member_no="+member_no+"&munja=Y";
		NewWindow(url, "reg_page", '700', '280', "no");
	}

}

function js_reload() {
	document.frmSearch.submit();
}

</script>
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
.btn { padding:10px 20px; background:#000; color:#fff !important; font-size:14px; }
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">
<FORM name="frmSearch" method="post" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 등록 회원 관리</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		<a href="batch_agree.php" class="btn">동의사항 일괄 전송</a>
		<a href="batch_account.php" class="btn">계좌정보 일괄 전송</a>
	</TD>
</TR>
</TABLE>
<br>
<b>* 처리 하실 단계를 선택하세요.</b>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<input type="radio" name="r_status" value="A" <? if ($r_status == "A") echo "checked" ?>  onClick="check_data();"> 전체 &nbsp;&nbsp;
					<!--input type="radio" name="r_status" value="1" <? if ($r_status == "1") echo "checked" ?>  onClick="check_data();"> 신청 (본인여부확인) &nbsp;&nbsp;-->
					<input type="radio" name="r_status" value="2" <? if ($r_status == "2") echo "checked" ?>  onClick="check_data();"> 신청 &nbsp;&nbsp;
					<input type="radio" name="r_status" value="3" <? if ($r_status == "3") echo "checked" ?>  onClick="check_data();"> 출력 처리 (프린트 출력) &nbsp;&nbsp;
					<input type="radio" name="r_status" value="4" <? if ($r_status == "4") echo "checked" ?>  onClick="check_data();"> 완료 (서버 입력 완료)&nbsp;&nbsp;
					<input type="radio" name="r_status" value="8" <? if ($r_status == "8") echo "checked" ?>  onClick="check_data();"> 보류&nbsp;&nbsp;
					<input type="radio" name="r_status" value="9" <? if ($r_status == "9") echo "checked" ?>  onClick="check_data();"> 신청 거부&nbsp;&nbsp;
					<input type="radio" name="r_status" value="F" <? if ($r_status == "F") echo "checked" ?>  onClick="check_data();"> 통신 장애&nbsp;&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" width="100">
					<b>가입일 : &nbsp;</b>
				</td>
				<td>
					<input type="text" name="from_date" value="<?echo $from_date?>" size="11" maxlength="10">~
					<input type="text" name="to_date" value="<?echo $to_date?>" size="11" maxlength="10"> [2004-12-01의 형태로 입력하세요.]
				</td>
			</tr>
			<tr>
				<td align="right" width="100">
					<b>회원 종류 : &nbsp;</b>
				</td>
				<td>
					<input type="radio" name="r_memberkind" value="A" <? if ($r_memberkind == "A") echo "checked" ?>> 전체 &nbsp;&nbsp;
					<input type="radio" name="r_memberkind" value="D" <? if ($r_memberkind == "D") echo "checked" ?>> 회원 &nbsp;&nbsp;
					<input type="radio" name="r_memberkind" value="C" <? if ($r_memberkind == "C") echo "checked" ?>> 소비자 회원 &nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td align="right" width="100">
					<b>공인 인증서 : &nbsp;</b>
				</td>
				<td>
					<input type="radio" name="r_join_kind" value="A" <? if ($r_join_kind == "A") echo "checked" ?>> 전체 &nbsp;&nbsp;
					<input type="radio" name="r_join_kind" value="Y" <? if ($r_join_kind == "Y") echo "checked" ?>> 사용 &nbsp;&nbsp;
					<input type="radio" name="r_join_kind" value="N" <? if ($r_join_kind == "N") echo "checked" ?>> 사용 안함 &nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td align="right" width="100">
					<b>활동 유형 : &nbsp;</b>
				</td>
				<td>
					<input type="radio" name="r_active_kind" value="A" <? if ($r_active_kind == "A") echo "checked" ?>> 전체 &nbsp;&nbsp;
					<input type="radio" name="r_active_kind" value="Y" <? if ($r_active_kind == "Y") echo "checked" ?>> 소매이익사업자(부가가치 신고대상자) &nbsp;&nbsp;
					<input type="radio" name="r_active_kind" value="N" <? if ($r_active_kind == "N") echo "checked" ?>> 후원사업자 (자가소비) &nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td align="right" width="100">
					<b>배우자 유무 : &nbsp;</b>
				</td>
				<td>
					<input type="radio" name="r_couple" value="A" <? if ($r_couple == "A") echo "checked" ?>> 전체 &nbsp;&nbsp;
					<input type="radio" name="r_couple" value="Y" <? if ($r_couple == "Y") echo "checked" ?>> 배우자 등록 &nbsp;&nbsp;
					<input type="radio" name="r_couple" value="N" <? if ($r_couple == "N") echo "checked" ?>> 배우자 없음&nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td align="right">
					<b>검 색 : &nbsp;</b>
				</td>
				<td>
					<SELECT NAME="idxfield">
						<OPTION VALUE="0" <?if($idxfield == "0") echo "selected";?>>이름</OPTION>
						<OPTION VALUE="1" <?if($idxfield == "1") echo "selected";?>>계좌번호</OPTION>
						<OPTION VALUE="2" <?if($idxfield == "2") echo "selected";?>>회원번호<!-- FO Number --></OPTION>
						<OPTION VALUE="3" <?if($idxfield == "3") echo "selected";?>>주소</OPTION>
						<OPTION VALUE="4" <?if($idxfield == "4") echo "selected";?>>생년월일</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
					<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">&nbsp;
					생년월일 검색 시 숫자만 입력 하세요.(yymmdd)
				</td>
			</tr>
			
		</table>
	</td>
</tr>
</table>
<br>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<b><input type="radio" name="rsort" value="regdate" <?if($con_sort == "regdate") echo "checked";?> onClick="check_data();"> 가입일 </b>
					<b><input type="radio" name="rsort" value="ldate" <?if($con_sort == "ldate") echo "checked";?> onClick="check_data();"> 최근로그인일 </b>
					<b><input type="radio" name="rsort" value="email_mod_date" <?if($con_sort == "email_mod_date") echo "checked";?> onClick="check_data();"> 이메일 수정일 </b>
					<b><input type="radio" name="rsort" value="ldate" <?if($con_sort == "ldate") echo "checked";?> onClick="check_data();"> 최근로그인일 </b>
					<b><input type="radio" name="rsort" value="name" <?if($con_sort == "name") echo "checked";?> onClick="check_data();"> 이름 </b>
					<b><input type="radio" name="rsort" value="number" <?if($con_sort == "number") echo "checked";?> onClick="check_data();"> 회원 </b>
					<b><input type="radio" name="rsort" value="visit_count" <?if($con_sort == "visit_count") echo "checked";?> onClick="check_data();"> 방문수 </b>
				<td align="right">
					<b><input type="radio" name="rorder" value="con_d" <?if($con_order == "con_d") echo "checked";?> onClick="check_data();">오름차순 </b>
					<b><input type="radio" name="rorder" value="con_a" <?if($con_order == "con_a") echo "checked";?> onClick="check_data();">내림차순 </b>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="100%" border="0">
	<tr>
		<td width="100%" align="right">
			<font color="blue">이름앞에 (M)이 있는 경우 모바일로 가입신청한 회원입니다.</font>&nbsp;&nbsp;<br>
			인증방식안내 : 모 (모바일)&nbsp;&nbsp;<!-- 신 (신용카드)&nbsp;&nbsp; -->공 (공인인증서)&nbsp;&nbsp;아 (아이핀)&nbsp;&nbsp;[시간]&nbsp;&nbsp;
		</td>
	</tr>
</table>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="6%">이름</TH>
	<TH width="*">주소</TH>
	<TH width="6%">생년월일</TH>
	<TH width="4%">성별</TH>
	<TH width="7%">연락처</TH>
	<TH width="6%">신청종류</TH>
	<TH width="7%">회원번호<!-- FO NO --></TH>
	<TH width="6%">신청일</TH>
	<TH width="7%">처리상태</TH>
	<TH width="7%">후원자ID</TH>
	<TH width="7%">후원자이름</TH>
	<TH width="6%">인증방식</TH>
	<TH width="3%">본인외주문</TH>
<? 	if ($r_status == "A") {?> 
	<TH width="10%">최근로그인</TH>
	<TH width="3%">방문</TH>

<? 	} else if ($r_status == "1") {?> 
	<TH width="9%">최근로그인</TH>
	<TH width="3%">방문</TH>
<? 	} else if ($r_status == "2") {?> 
<!--
	<TH width="14%">본인확인일</TH>
	<TH width="6%">확인자</TH>
-->
<? 	} else if ($r_status == "3") {?> 
	<TH width="14%">출력일</TH>
	<TH width="6%">출력자</TH>
	<TH width="8%">완료메일보내기</TH>
<? 	} else if ($r_status == "4") {?> 
	<TH width="14%">완료일</TH>
	<TH width="6%">완료자</TH>
<? 	} else if ($r_status == "8") {?> 
	<TH width="14%">보류일</TH>
	<TH width="6%">보류자</TH>
<? 	} else if ($r_status == "9") {?> 
	<TH width="14%">거부일</TH>
	<TH width="6%">거부자</TH>
<? 	} else if ($r_status == "F") {?> 
	<TH width="4%">재전송</TH>
<?	}?>
<?	if (($r_status == 1) || ($r_status == 2) ) {?>
	<TH width="4%">선택</TH> 
<?	}?>
</TR>     
<?

	if ($TotalArticle) {
		
		while($obj = mysql_fetch_object($result2)) {
		
		//	if ($i >= $First) {
				if(!empty($obj->regdate)){
					$date_s1 = date("Y-m-d", strtotime($obj->regdate));
				}else{
					$date_s1 = "";
				}
				

				if ($r_status == "A") { 

					if ($obj->ldate != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->ldate));
					} else {
						$date_s2 = "";
					}
				
					$temp = number_format($obj->visit_count);

	 			} else if ($r_status == "1") { 

					if ($obj->ldate != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->ldate));
					} else {
						$date_s2 = "";
					}
				
					$temp = number_format($obj->visit_count);

	 			} else if ($r_status == "2") { 

					if ($obj->confirm_person_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->confirm_person_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->confirm_person_ma;

				} else if ($r_status == "3") { 

					if ($obj->print_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->print_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->print_ma;

				} else if ($r_status == "4") { 

					if ($obj->confirm_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->confirm_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->confirm_ma;

				} else if ($r_status == "8") { 

					if ($obj->wait_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->wait_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->wait_ma;

				} else if ($r_status == "9") { 

					if ($obj->reject_date != null) {
						$date_s2 = date("Y-m-d [H:i]", strtotime($obj->reject_date));
					} else {
						$date_s2 = "";
					}
				
					$temp = $obj->reject_ma;

				} 

				if ($obj->co_number != null) {
					$email_mod_date = $obj->co_number;
				} else {
					$email_mod_date = "";
				}

				if ($obj->co_name != null) {
					$co_name = $obj->co_name;
				} else {
					$co_name = "";
				}
				
				if($obj->auth_type != ''){
					
					$check_kind = $obj->auth_type;
					if ($check_kind == "C") $str_chk_type = "신";
					if ($check_kind == "M") $str_chk_type = "모";
					if ($check_kind == "X") $str_chk_type = "공";
					if ($check_kind == "I") $str_chk_type = "아";
					if ($check_kind == "K") $str_chk_type = "카";
				
				}else{

					if ($obj->member_no > 160617) {

						$query = "select check_kind, name, jumin1, jumin2, chkdate from tb_check_log
											where jumin2 = '".$obj->DI."'
												and flag = 'Y' and check_kind in ('C','M','X','I') AND chkdate > ADDDATE('".$obj->regdate."',-15) order by check_no desc ";

						//echo $query;

					} else {

						$query = "select check_kind, name, jumin1, jumin2, chkdate from tb_check_log
											where jumin1 = '".$obj->reg_jumin1."'
												and jumin2 = '".$obj->reg_jumin2."'
												and flag = 'Y' and check_kind in ('C','M','X')  AND chkdate > ADDDATE('".$obj->regdate."',-15) order by check_no desc ";
					}
					

					$result_chk = mysql_query($query);
					
					$str_chk_type = "";

					while($row_chk = mysql_fetch_array($result_chk)) {
						$check_kind = $row_chk[check_kind];
						$chkdate = $row_chk[chkdate];
						$chkdate = date("[H:i]", strtotime($chkdate));
						if ($check_kind == "C") $str_check_kind = "신";
						if ($check_kind == "M") $str_check_kind = "모";
						if ($check_kind == "X") $str_check_kind = "공";
						if ($check_kind == "I") $str_check_kind = "아";
						$str_chk_type = $str_chk_type.$str_check_kind." ".$chkdate."<br>";

					}
				}

				if (trim($obj->REF) == "M") $str_mobile = "(M)";
				if (trim($obj->REF) == "W") $str_mobile = "";
				$flag = "";
				if (trim($obj->sel_agree05) == "Y") {$flag= "Y";}
				else if (trim($obj->sel_agree05) == "N") {$flag= "N";}
				else $flag="N";

					$gender = $obj->sex % 2;

				if ($gender == 1 ) $gender = "남";
				else {
					$gender = "여";
				}
				//if ($obj->api_fail == "N") $str_name = "<font color='blue'>".$str_mobile.$obj->name."</font>";
				//if ($obj->api_fail == "Y") $str_name = "<font color='red'>".$str_mobile.$obj->name."</font>";

				if ($obj->reg_status != "F") $str_name = "<font color='blue'>".$str_mobile.$obj->name."</font>";
				if ($obj->reg_status == "F") $str_name = "<font color='red'>".$str_mobile.$obj->name."</font>";
?>
<TR align="center">
	<TD height="25"><A HREF="javascript:onView('<?echo $obj->member_no?>')"><?echo $str_mobile?><?echo $obj->name?></A></TD>
	<TD align="left"><?echo $obj->del_addr?> <!-- <?echo $obj->del_addr_detail?> --></TD>
	<?php 
	if (trim($obj->member_kind) == "D") { 
		$jumin1 = decrypt($key, $iv, $obj->JU_NO01);
		if($jumin1 == "") $jumin1 = decrypt($key, $iv, $obj->reg_jumin1);
		if($jumin1 == "") $jumin1 = $obj->birth_y.$obj->birth_m.$obj->birth_d;
		?>
	<TD><?=$jumin1?><?=decrypt($key, $iv, 'VkL26oMc5xklbmeqvggHQw==')?></TD>	
	<?php } else { ?>
	<TD><?echo $obj->birth_y?><?echo $obj->birth_m?><?echo $obj->birth_d?></TD>
	<?php } ?>
	<TD><?echo $gender;?></TD>
	<TD><?echo $obj->hpho1?>-<?echo $obj->hpho2?>-<?echo $obj->hpho3?></TD>
	<TD>
<?	

	if (trim($obj->member_kind) == "D") {
		echo "회원";
	} else if (trim($obj->member_kind) == "C") {
		echo "소비자회원";
	} 

?>	
	</TD>
	<TD>
	<?
	if ($r_status == "3"){
		if($obj->number){?>
		<?echo $obj->number?>
	<?}else{?>
		<input type="text" name="banumber">
		<input type="hidden" name="no" value="<?=$obj->member_no?>">
	<?}
	}else{
		echo $obj->number;
	}
	?>

	</TD>
	<TD><?echo $date_s1?></TD>
	<TD>
<?	
	if ($obj->reg_status == "1") {
		echo "신청(본인확인)";
	} else if ($obj->reg_status == "2") {
		echo "신청";
	} else if ($obj->reg_status == "3") {
		echo "출력처리";
	} else if ($obj->reg_status == "4") {
		echo "완료";
	} else if ($obj->reg_status == "8") {
		echo "보류";
	} else if ($obj->reg_status == "9") {
		echo "거부";
	} else if ($obj->reg_status == "F") {
		echo "<font color='red'>통신장애</font>";
	} 
?>	
	</TD>
	<TD><?echo $email_mod_date?></TD>
	<TD><?echo $co_name?></TD>
	<TD><?echo $str_chk_type?></TD>
	<TD><?echo $flag?></TD>
<? if ($r_status == "2" || $r_status == "F") {
	
}else{?> 
	<TD><?echo $date_s2?></TD>
	<TD align="center"><?echo $temp?></TD>

<?}?>
<? if ($r_status == 3) {?>
	<TD><input type="button" value="완료메일&문자" onClick="goEmail('<?=$obj->member_no?>');"></TD>
<? }?>

<?	if (($r_status == 1) || ($r_status == 2) ) {?>
	<TD align="center"><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->member_no?>"></td>
<?	}?>

<? if ($r_status == "F") {?>
	<TD><input type="button" value="재전송" onClick="goReSend('<?=$obj->member_no?>');"></TD>
<? }?>
</TR>
<?
		//	}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left">
	    조회 회원 수 : <?echo $TotalArticle?> 명, 오늘 가입한 회원 : <?//echo $TotalArticle3?><?echo $now_TotalArticle;?>명 
	</TD>
	<TD align="right">
<?
$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href=javascript:goPage('1');>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href=javascript:goPage('".($PrevPage + 1)."');>이전".$PageScale."개</a>]";
	}

	echo "&nbsp;";

	// 페이지 번호
	for ($vj = 0; $vj < $PageScale; $vj++)
	{
//		$ln = ($Scale * $PageScale + $vj) * $ListArticle + 1;
		$vk = $Scale * $PageScale + $vj + 1;
		if ($vk < $TotalPage + 1)
		{
			if ($vk != $page)
					echo "&nbsp;[<a href=javascript:goPage('".$vk."');>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href=javascript:goPage('".$NextPage."');>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href=javascript:goPage('".$TotalPage."');>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left">&nbsp;</TD>
	<TD align="right" width="600" align="center" bgcolor=silver>

<?	if ($r_status == "1") { ?>
		<INPUT TYPE="button" VALUE="본인여부확인" onClick="goConfirm();">	
<?	} else if ($r_status == "2") { ?>
		<INPUT TYPE="button" VALUE="프린트 출력" onClick="goPrint();">	
		<INPUT TYPE="button" VALUE="Test 프린트 출력" onClick="goPrint2();">
<?	} else if ($r_status == "3") { ?>
	<!--<INPUT TYPE="button" VALUE="FO 일괄저장" onClick="goInputAllBA();">-->	<!--&nbsp;&nbsp;	<INPUT TYPE="button" VALUE="BA 입력" onClick="goInputBA();"-->
<?	}?>

	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="member_no" value="">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
<input type="hidden" name="reg_status" value="<?echo $reg_status?>">
<input type="hidden" name="member_nos" value="">
<input type="hidden" name="numbers" value="">

</form>
</body>
</html>
<?
mysql_close($connect);
?>