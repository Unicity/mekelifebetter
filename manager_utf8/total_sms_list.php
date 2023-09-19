<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

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

	$mode					= str_quote_smart(trim($mode));
	$seq_no				= str_quote_smart(trim($seq_no));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$order				= str_quote_smart(trim($order));
	$db_name			= str_quote_smart(trim($db_name));
	$page					= str_quote_smart(trim($page));
	$sms_no				= str_quote_smart(trim($sms_no));
	$id						= str_quote_smart(trim($id));
	$telcoinfo		= str_quote_smart(trim($telcoinfo));
	$reg_status		= str_quote_smart(trim($reg_status));
	


	$toDay = date("Y-m-d");
	$toyear = date("Y");
	$tomonth = date("m");

	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	//$sort = "msgkey";
	$sort = "reqdate";

	if (empty($order)) {
		$order = "desc";
	}

	if (empty($db_name)) {
		$db_name = $toyear.$tomonth;
	}

	if (!empty($id)) {
		$que = $que." and id = '$id' ";	
	}

	if (!empty($telcoinfo)) {
		$que = $que." and telcoinfo = '$telcoinfo' ";	
	}


	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = $que." and phone like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = $que." and msg like '%$qry_str%' ";
		}
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

	$query = "select count(*) from sms_log_".$db_name." where 1 = 1 ".$que;

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$query2 = "select * from sms_log_".$db_name." where 1 = 1 ".$que." order by ".$sort." ".$order." limit ". $offset.", ".$nPageSize; ;
	//echo $query2;
	$result2 = mysql_query($query2);

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
<meta http-equiv="X-Frame-Options" content="deny" />
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">

function check_data(){
	
	for(i=0; i < document.frmSearch.rorder.length ; i++) {
		if (document.frmSearch.rorder[i].checked == true) {
			document.frmSearch.order.value = document.frmSearch.rorder[i].value;
		}
	}
	document.frmSearch.action="total_sms_list.php";
	document.frmSearch.submit();
}

function onSearch(){


	document.frmSearch.page.value="1";
	document.frmSearch.action="total_sms_list.php";
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

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>SMS 발송 조회</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp;
	</TD>
</TR>
</TABLE>

<br>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" width="100" height="40">
					<b>발송 날짜 : &nbsp;</b>
				</td>
				<td colspan="3">
					&nbsp;&nbsp;<select name="db_name" style="width:100" onchange="check_data();">
					<?
					$query = "SHOW TABLES where Tables_in_makelifebetter like 'sms_log%'";
					$result_chk = mysql_query($query);
					
					while($row_chk = mysql_fetch_array($result_chk)) {
						$table_nm = $row_chk[0];
						$temp_nm = str_replace("sms_log_" ,"",$table_nm); 
						
					?>
						<option value="<?=$temp_nm?>" <?if($db_name==$temp_nm){?>selected<?}?>><?=$temp_nm?></option>
					<?
					}
					?>
					
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" width="100" height="40">
					<b>발송 유형 : &nbsp;</b>
				</td>
				<td width="280">
					&nbsp;&nbsp;<select name="id" style="width:200">
						<option value="" >전체</option>
						<option value="tb_sms_mem" <?if($id=="tb_sms_mem"){?>selected<?}?>>관리자 SMS 발송</option>
						<option value="each" <?if($id=="each"){?>selected<?}?> >관리자 개별SMS 발송</option>
						<option value="tb_userinfo" <?if($id=="tb_userinfo"){?>selected<?}?> >회원가입</option>
						<option value="tb_userinfo_dup" <?if($id=="tb_userinfo_dup"){?>selected<?}?> >중복회원관리</option>
						<option value="FoShopping" <?if($id=="FoShopping"){?>selected<?}?> >FO무통장</option>
					</select>
				</td>
				<td align="right" width="60" height="40">
					<b>통신사 : &nbsp;</b>
				</td>
				<td>
					&nbsp;&nbsp;<select name="telcoinfo" style="width:100">
						<option value="" >전체</option>
						<option value="SKT" <?if($telcoinfo=="SKT"){?>selected<?}?> >SKT</option>
						<option value="KTF" <?if($telcoinfo=="KTF"){?>selected<?}?> >KTF</option>
						<option value="LGT" <?if($telcoinfo=="LGT"){?>selected<?}?> >LGT</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" height="40">
					<b>검 색 : &nbsp;</b>
				</td>
				<td colspan="3">
					&nbsp;&nbsp;
					<SELECT NAME="idxfield">
						<OPTION VALUE="0" <?if($idxfield == "0") echo "selected";?>>발송번호</OPTION>
						<OPTION VALUE="1" <?if($idxfield == "1") echo "selected";?>>메세지</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
					<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">&nbsp;
					
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
<table width="100%" border="0">
	<tr>
		<td width="100%" align="right">
			<font color="blue">총 발송건수 : <?=$TotalArticle?></font>&nbsp;&nbsp;
		</td>
	</tr>
</table>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="15%">발송유형</TH>
	<TH width="10%">발송번호</TH>
	<TH width="*">발송메세지</TH>
	<TH width="7%">통신사</TH>
	<TH width="10%">발송시간</TH>

</TR>     
<?

	if ($TotalArticle) {
		
		while($obj = mysql_fetch_object($result2)) {
		
		if($obj->ID=="tb_sms_mem"){
			$sms_type="관리자 SMS 발송";
		}else if($obj->ID=="each"){
			$sms_type="관리자 개별SMS 발송";
		}else if($obj->ID=="tb_userinfo"){
			$sms_type="회원가입";
		}else if($obj->ID=="tb_userinfo_dup"){
			$sms_type="중복회원관리";
		}else if($obj->ID=="FoShopping"){
		    $sms_type="FO무통장";
		}
?>
<TR align="center">
	<TD height="25"><?echo $sms_type?></TD>
	<TD align="center"><?echo $obj->del_addr?> <?echo $obj->PHONE?></TD>
	<TD align="left"><?echo $obj->MSG ?></TD>
	<TD><?echo $obj->TELCOINFO?></TD>
	<TD><?echo $obj->WRTDATE?></TD>
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
	    총 발송건수 : <?echo $TotalArticle?> 건
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

<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="member_no" value="">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
<input type="hidden" name="reg_status" value="<?echo $reg_status?>">
<input type="hidden" name="member_nos" value="">
<input type="hidden" name="numbers" value="">

</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>