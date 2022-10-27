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


	$page			= str_quote_smart(trim($page));
	$idxfield = str_quote_smart(trim($idxfield));
	$qry_str	= str_quote_smart(trim($qry_str));
	$order = str_quote_smart(trim($order));
	$sort	= str_quote_smart(trim($sort));

	$query = "select count(*) from v_userinfo";
	$query2 = "select * from v_userinfo";
	$query3 = "select count(*) from v_userinfo";


	#echo $query."<BR>"; 
	#echo $query2."<BR>";
	#echo $query3."<BR>";
	
	$result3 = mysql_query($query3,$connect);
	$row3 = mysql_fetch_array($result3);
	$TotalArticle3 = $row3[0];

	
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
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="10%">이름</TH>
	<TH width="13%">FO NO</TH>
	<TH width="10%">신청일</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
	
			mysql_data_seek($result2,$i);

			$obj = mysql_fetch_object($result2);
			
			if ($i >= $First) {
				
				$date_s1 = date("Y-m-d", strtotime($obj->appdate));
				
?>					
<TR align="center">                    
	<TD><?echo $obj->name?></TD>
	<TD><?echo $obj->number?></TD>
	<TD><?echo $date_s1?></TD>
</TR>
<?
			}
		}
	}
?>
</TABLE>
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