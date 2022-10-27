<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	function getCodeName ($code, $parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' and code='".$code."'"; 

		$result = mysql_query($sqlstr);
		$list = mysql_fetch_array($result);

		$name = $list[name];

		print($name);

	}

	$mode					= str_quote_smart(trim($mode));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$sort					= str_quote_smart(trim($sort));
	$order				= str_quote_smart(trim($order));
	$from_date		= str_quote_smart(trim($from_date));
	$to_date			= str_quote_smart(trim($to_date));
	$page					= str_quote_smart(trim($page));
	$BoardId			= str_quote_smart(trim($BoardId));
	$r_status			= str_quote_smart(trim($r_status));
	$r_memberkind	= str_quote_smart(trim($r_memberkind));
	$r_join_kind	= str_quote_smart(trim($r_join_kind));
	$r_active_kind= str_quote_smart(trim($r_active_kind));
	$r_couple			= str_quote_smart(trim($r_couple));


	$toDay = date("Y-m-d");

	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	if (empty($sort)) {
		$sort = "regdate";
	}

	if (empty($order)) {
		$order = "desc";
	}

	if (!empty($from_date)) {
		$que = " and regdate >= '$from_date' ";		
	}

	if (!empty($to_date)) {
		$que = $que." and date_sub(regdate, interval 1 day) <= '$to_date' ";		
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
		}

	}
		
	$query = "select count(*) from tb_userinfo where member_no > '' ".$que;
	$query2 = "select * from tb_userinfo where member_no > '' ".$que." order by ".$sort." ".$order ;
	$query3 = "select count(*) from tb_userinfo where member_no > '' ".$que. " and regdate like '$toDay%' ";


	#echo $query."<BR>"; 
	#echo $query2."<BR>";
	#echo $query3."<BR>";
	
	$result3 = mysql_query($query3,$connect);
	$row3 = mysql_fetch_array($result3);
	$TotalArticle3 = $row3[0];

	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 60;
	$PageScale = 10;
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
<meta http-equiv="X-Frame-Options" content="deny" />
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
			document.frmSearch.sort.value = document.frmSearch.rsort[i].value;
		}
	}

	for(i=0; i < document.frmSearch.rorder.length ; i++) {
		if (document.frmSearch.rorder[i].checked == true) {
			document.frmSearch.order.value = document.frmSearch.rorder[i].value;
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
		if (document.frmSearch.from_date.value.length != 8 ) {
			alert("날짜의 형식은 20040420으로 입력 하셔야 합니다");
			document.frmSearch.from_date.focus();
			return;
		} else {

			sYYYY = document.frmSearch.from_date.value.substr(0,4);

			sMM = document.frmSearch.from_date.value.substr(4,2);
			
			if (sMM.substr(0,1) == 0) {
				sMM = sMM.substr(1,1);			
			}
			
			sDD = document.frmSearch.from_date.value.substr(6,2);

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
		if (document.frmSearch.to_date.value.length != 8 ) {
			alert("날짜의 형식은 20040420으로 입력 하셔야 합니다");		
			document.frmSearch.to_date.focus();
		} else {

			sYYYY = document.frmSearch.to_date.value.substr(0,4);
			sMM = document.frmSearch.to_date.value.substr(4,2);

			if (sMM.substr(0,1) == 0) {
				sMM = sMM.substr(1,1);			
			}

			sDD = document.frmSearch.to_date.value.substr(6,2);

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
			document.frmSearch.sort.value = document.frmSearch.rsort[i].value;
		}
	}

	for(i=0; i < document.frmSearch.rorder.length ; i++) {
		if (document.frmSearch.rorder[i].checked == true) {
			document.frmSearch.order.value = document.frmSearch.rorder[i].value;
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

</script>
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">
<FORM name="frmSearch" method="get" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>온라인 등록 회원 관리</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp;
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
					<input type="radio" name="r_status" value="1" <? if ($r_status == "1") echo "checked" ?>  onClick="check_data();"> 신청 (본인여부확인) &nbsp;&nbsp;
					<input type="radio" name="r_status" value="2" <? if ($r_status == "2") echo "checked" ?>  onClick="check_data();"> 신청 &nbsp;&nbsp;
					<input type="radio" name="r_status" value="3" <? if ($r_status == "3") echo "checked" ?>  onClick="check_data();"> 출력 처리 (프린트 출력) &nbsp;&nbsp;
					<input type="radio" name="r_status" value="4" <? if ($r_status == "4") echo "checked" ?>  onClick="check_data();"> 완료 (서버 입력 완료)&nbsp;&nbsp;
					<input type="radio" name="r_status" value="8" <? if ($r_status == "8") echo "checked" ?>  onClick="check_data();"> 보류&nbsp;&nbsp;
					<input type="radio" name="r_status" value="9" <? if ($r_status == "9") echo "checked" ?>  onClick="check_data();"> 신청 거부&nbsp;&nbsp;
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
					<input type="text" name="from_date" value="<?echo $from_date?>" size="8" maxlength="8">~
					<input type="text" name="to_date" value="<?echo $to_date?>" size="8" maxlength="8"> [20041201의 형태로 입력하세요.]
				</td>
			</tr>
			<tr>
				<td align="right" width="100">
					<b>회원 종류 : &nbsp;</b>
				</td>
				<td>
					<input type="radio" name="r_memberkind" value="A" <? if ($r_memberkind == "A") echo "checked" ?>> 전체 &nbsp;&nbsp;
					<input type="radio" name="r_memberkind" value="D" <? if ($r_memberkind == "D") echo "checked" ?>> FO 회원 &nbsp;&nbsp;
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
						<OPTION VALUE="2" <?if($idxfield == "2") echo "selected";?>>FO Number</OPTION>
						<OPTION VALUE="3" <?if($idxfield == "3") echo "selected";?>>주소</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
					<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">
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
					<b><input type="radio" name="rsort" value="regdate" <?if($sort == "regdate") echo "checked";?> onClick="check_data();"> 가입일 </b>
					<b><input type="radio" name="rsort" value="ldate" <?if($sort == "ldate") echo "checked";?> onClick="check_data();"> 최근로그인일 </b>
					<b><input type="radio" name="rsort" value="reg_status" <?if($sort == "reg_status") echo "checked";?> onClick="check_data();"> 처리 상태 </b>
					<b><input type="radio" name="rsort" value="name" <?if($sort == "name") echo "checked";?> onClick="check_data();"> 이름 </b>
					<b><input type="radio" name="rsort" value="number" <?if($sort == "number") echo "checked";?> onClick="check_data();"> FO </b>
					<b><input type="radio" name="rsort" value="visit_count" <?if($sort == "visit_count") echo "checked";?> onClick="check_data();"> 방문수 </b>
				<td align="right">
					<b><input type="radio" name="rorder" value="desc" <?if($order == "desc") echo "checked";?> onClick="check_data();">오름차순 </b>
					<b><input type="radio" name="rorder" value="asc" <?if($order == "asc") echo "checked";?> onClick="check_data();">내림차순 </b>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
	
			mysql_data_seek($result2,$i);

			$obj = mysql_fetch_object($result2);
			
			if ($i >= $First) {
				
				$date_s1 = date("Y-m-d", strtotime($obj->regdate));
				

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
	
?>					
	<?echo $obj->name?>,&nbsp;
	<?echo $obj->reg_jumin1?>-<?echo $obj->reg_jumin2?>,&nbsp;
<?	
	if (trim($obj->member_kind) == "D") {
		echo "FO 회원,&nbsp;";
	} else if (trim($obj->member_kind) == "C") {
		echo "소비자회원,&nbsp;";
	} 
?>	
	<?echo $date_s1?><br>
<?
			}
		}
	}
?>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left">
	    조회 회원 수 : <?echo $TotalArticle?> 명, 오늘 가입한 회원 : <?echo $TotalArticle3?>명 
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
<?	} else if ($r_status == "3") { ?>
		<INPUT TYPE="button" VALUE="FO 입력" onClick="goInputBA();">	
<?	}?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="member_no" value="">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
<input type="hidden" name="reg_status" value="<?echo $reg_status?>">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>