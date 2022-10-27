<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";

	$d=date("d");
	$m=date("m");
	$y=date("Y");
	$H=date("H");
	$i=date("i");

	$this_time = $y.$m.$d; 

	#echo $this_time;
	 	 
	function card_XXX($str){

		$slen = strlen($str);
		
		$tmp = substr($str,0,12)."XXXX";

		return $tmp;
	}

	function date_format2($str){

		$slen = strlen($str);
		if ($slen <> 0) {
			$tmp = substr($str,0,4)."/".substr($str,4,2)."/".substr($str,6,2);
		} else {
			$tmp = "";
		}
		return $tmp;
	}

	function time_format($str){

		$slen = strlen($str);
		if ($slen <> 0) {
			$tmp = substr($str,0,2).":".substr($str,2,2);
		} else {
			$tmp = "";
		}
		return $tmp;
	}

	if (empty($from_date)) {
		$from_date = $this_time;
	}

	if (empty($to_date)) {
		$to_date = $this_time;
	}

	if (!empty($from_date)) {
		$que = " and TradeDate >= '$from_date' ";		
	} 

	if (!empty($to_date)) {
		
		$qry_date = "select date_sub('$to_date', interval -1 day) next_date from seq_kspay ";
		$res_date = mysql_query($qry_date);
		$list = mysql_fetch_array($res_date);
		$next_date = $list[next_date];

		$next_date = str_replace("-","",$next_date);
				
		$que = $que." and TradeDate < '$next_date' ";		
#		$que = $que." and date_sub(inputdate, interval 1 day) < '$to_date' ";		
	}

	if ($s_flag != "1") {
		$que = $que." and inputid = '$s_adm_id' ";				
	}
		
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = $que." and ordernumber like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = $que." and CardNo like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = $que." and inputid like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_kspay where ks_id >= 0 ".$que;
		$query2 = "select * from tb_kspay where ks_id >= 0 ".$que. " order by ks_id desc";

	} else {
		$query = "select count(*) from tb_kspay where ks_id >= 0 ".$que;
		$query2 = "select * from tb_kspay where ks_id >= 0 ".$que. " order by ks_id desc";
	}
	
	#echo $query."<br>";
	#echo $query2;
	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 20;
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/admin.css" type="text/css">
<script language="javascript">

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
	
	document.frmSearch.page.value="1";
	document.frmSearch.action="KSPAY_list.php";
	document.frmSearch.submit();
}

function init(){
<?	if (!empty($qry_str)) {  ?>
		document.frmSearch.qry_str.value="<?echo $qry_str ?>";
		document.frmSearch.idxfield.options[<?echo $idxfield ?>].selected = true;
<?	} ?>

}

function goIn() {
	document.frmSearch.action= "en_KSPAY_form.php";
	document.frmSearch.submit();
}

function goExcel() {

	if (document.frmSearch.from_date.value == "") {
		alert("거래일자를 지정 하셔야 합니다.");
		document.frmSearch.from_date.focus();
		return;
	}

	if (document.frmSearch.to_date.value == "") {
		alert("거래일자를 지정 하셔야 합니다.");
		document.frmSearch.to_date.focus();
		return;
	}

	document.frmSearch.action= "KSPAY_list_excel.php";
	document.frmSearch.submit();
}

function goIn_ISP() {
	document.frmSearch.action= "KSPAY_form_Isp.php";
	document.frmSearch.submit();
}

function goIn_all() {
	document.frmSearch.action= "../pg_auth/KSPAY_form.php";
	document.frmSearch.submit();
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
				sValues += frmSearch.CheckItem.value;
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

//팝업
function NewWindow(mypage, myname, w, h, scroll) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}


</script>
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
.DEK {POSITION:absolute;VISIBILITY:hidden;Z-INDEX:200;}
</STYLE>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">
<DIV ID="dek" CLASS="dek"></DIV> 
<SCRIPT TYPE="text/javascript"> 
Xoffset=-90;     
Yoffset= 10;     

var old,skn,iex=(document.all),yyy=-1000; 

var ns4=document.layers 
var ns6=document.getElementById&&!document.all 
var ie4=document.all 

if (ns4) 
skn=document.dek 
else if (ns6) 
skn=document.getElementById("dek").style 
else if (ie4) 
skn=document.all.dek.style 
if(ns4)document.captureEvents(Event.MOUSEMOVE); 
else{ 
skn.visibility="visible" 
skn.display="none" 
} 
document.onmousemove=get_mouse; 

function popup(msg){ 

	Xoffset= -90;     
	Yoffset= 10;     
	
	var content="<TABLE BGCOLOR='#000000' WIDTH='250' CELLSPACING='0' CELLPADDING='1' BORDER='0'><TR><TD><TABLE BGCOLOR='beige' WIDTH='100%' CELLSPACING='0' CELLPADDING='4' BORDER='0'><TR><TD>"+msg+"</TD></TR></TABLE></TD></TR></TABLE>";

	yyy=Yoffset; 
	if(ns4){skn.document.write(content);skn.document.close();skn.visibility="visible"} 
	if(ns6){document.getElementById("dek").innerHTML=content;skn.display=''} 
	if(ie4){document.all("dek").innerHTML=content;skn.display=''} 
} 

function get_mouse(e){ 
	var x=(ns4||ns6)?e.pageX:event.x+document.body.scrollLeft; 
	skn.left=x+Xoffset; 
	var y=(ns4||ns6)?e.pageY:event.y+document.body.scrollTop; 
	skn.top=y+yyy; 
} 

function kill(){ 
	yyy=-1000; 
	if(ns4){skn.visibility="hidden";} 
	else if (ns6||ie4) 
	skn.display="none" 
} 


</script>


<FORM name="frmSearch" method="post" action="javascript:onSearch();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>KSPAY CC transaction list</B></TD>
	<TD align="right" width="400" align="center" bgcolor=silver>
	
	<INPUT TYPE="button" VALUE="Enter Payment" onClick="goIn();">
	<INPUT TYPE="button" VALUE="Download Excel file" onClick="goExcel();">
<!--
	<INPUT TYPE="button" VALUE="ISP 방식 결제 (국민, 비씨)" onClick="goIn_ISP();">

	<INPUT TYPE="button" VALUE="결제" onClick="goIn_all();">
-->
<!--	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">-->	
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" width="100">
					<b>Date : &nbsp;</b>
				</td>
				<td>
					<input type="text" name="from_date" value="<?echo $from_date?>" size="8" maxlength="8">~
					<input type="text" name="to_date" value="<?echo $to_date?>" size="8" maxlength="8"> [ex : 20080707]
				</td>
			</tr>
			<tr>
				<td align="right">
					<b>Search : &nbsp;</b>
				</td>
				<td>
					<SELECT NAME="idxfield">
						<OPTION VALUE="0">Order Number</OPTION>
						<OPTION VALUE="1">Credit Card number</OPTION>
						<OPTION VALUE="2">User ID</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
					<INPUT TYPE="button" VALUE="SEARCH" onClick="onSearch();">
				</td>
			</tr>
			
		</table>
	</td>
</tr>
</table>
<br>
<b>
* Click ‘Transaction No’(blue color) to cancel the cc authorization.<br>
* When you put the cursor on one of ‘Section’ column,  you can see the reason of CC rejection .</b>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR height="25">
	<TH>User ID</TH>
	<TH>Transaction No</TH>
	<TH>Order Number</TH>
<!--
	<TH>주문자명</TH>
	<TH>상품명</TH>
-->	
	<TH>CC type</TH>
	<TH>CC Number</TH>
	<TH>Installment</TH>
	<TH>Amount</TH>
	<TH>Auth number</TH>
	<TH>Section</TH>
	<TH>Auth Date & Time</TH>
	<TH>Cancel Date & Time</TH>
	<TH>Interest</TH>
	<TH>Slip View</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d", strtotime($obj->inputdate));
	
?>					
<TR align="center" height="25">                    
	<TD><?echo $obj->inputid?></TD>
	<TD><A HREF="javascript:NewWindow('KSPAY_cancel_form.php?TransactionNo=<?echo $obj->TransactionNo?>','Cancel_pop', '500','300','NO');"><?echo $obj->TransactionNo?></A></TD>
	<TD><?echo $obj->ordernumber?></TD>
<!--
	<TD>
		<a href="mailto:<?echo $obj->email?>"><?echo $obj->ordername?></a><br>
		<?echo $obj->phoneno?>
	</TD>
	<TD align="left"><?echo $obj->goodname?></TD>
-->
	<TD><? if ($obj->Status == "O") {?><?echo $obj->Message1?><? } ?></TD>
	<TD><?echo $obj->CardNo?></TD>
	<TD><?echo $obj->installment?></TD>
	<TD align="right"><?echo number_format($obj->amount)?></TD>
	<TD><?echo $obj->AuthNo?></TD>
	<TD>
<?	if ($obj->Status == "O") {

		if ($obj->CStatus == "O") {	
			echo "취소승인";
		} else if ($obj->CStatus == "X") {
			echo "취소거절";
		} else {
?>
		<p ONMOUSEOVER="popup('<?echo $obj->Message1?>');" ONMOUSEOUT="kill();">승인</p>
<?
		}

	} else {
?>	
		<p ONMOUSEOVER="popup('<?echo $obj->Message1?> : <?echo $obj->Message2?>');" ONMOUSEOUT="kill();">거절</p>
<?
	}
?>
	</TD>
	<TD><?echo date_format2($obj->TradeDate)?> <?echo time_format($obj->TradeTime)?></TD>
	<TD><?echo date_format2($obj->CTradeDate)?> <?echo time_format($obj->CTradeTime)?></TD>
	<TD>
<? 
	if ($obj->InterestType == "2") { 
		echo "무이자"; 
	} else { 
		echo "일반"; 
	}
?>
	</TD>
<!--
	<TD><?echo $date_s?></TD>
-->
	<TD>
<?	if ($obj->Status == "O") { ?>
		[<A HREF="javascript:NewWindow('receipt.php?ks_id=<?echo $obj->ks_id?>','receipt_pop', '394','708','NO');">Detail</a>]<!--[<a href="#w" onclick="javascript:NewWindow('http://nims.ksnet.co.kr:7001/pg_infoc/src/bill/credit_view.jsp?tr_no=<?echo $obj->TransactionNo?>', 'ks', '450', '650', 'YES')">보기</a>]-->
<?	} ?>
	</TD>
</TR>
<?
			}
		}
	} else {
?>
	<tr><td height="25" colspan="13" align="center">NO DATA.</td></tr>
<?
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center" colspan=12>
<?

$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str&from_date=$from_date&to_date=$to_date'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str&from_date=$from_date&to_date=$to_date'>이전".$PageScale."개</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str&from_date=$from_date&to_date=$to_date'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str&from_date=$from_date&to_date=$to_date'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str&from_date=$from_date&to_date=$to_date'>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
</form>
</body>
</html>
<?
mysql_close($connect);
?>