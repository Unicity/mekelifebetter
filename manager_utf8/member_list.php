<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2003/7/28
	// 	Last Update : 2003/7/28
	// 	Author 		: Park, ChanHo
	// 	History 	: 2003.7.28 by Park ChanHo 
	// 	File Name 	: member_list.php
	// 	Description : the customer member  View
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$sort					= str_quote_smart(trim($sort));
	$order				= str_quote_smart(trim($order));
	$member_kind	= str_quote_smart(trim($member_kind));

	if (empty($member_kind)) {
		$member_kind = "D";
	} 
	
	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	if (empty($sort)) {
		$sort = "name";
	}

	if (empty($order)) {
		$order = "asc";
	}
			
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and number like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and name like '%$qry_str%' ";			
		} else if ($idxfield == "2") {
			$que = " and phone like '%$qry_str%' ";
		}
		
		$query = "select count(*) from tb_member where member_kind = '$member_kind'  ".$que;
		$query2 = "select * from tb_member where member_kind = '$member_kind'  ".$que." order by ".$sort." ".$order ;

	} else {
		$query = "select count(*) from tb_member where member_kind = '$member_kind' ";
		$query2 = "select * from tb_member where member_kind = '$member_kind'  order by ".$sort." ".$order ;
	}

	#echo $query."<BR>"; 
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
<meta http-equiv="X-Frame-Options" content="deny" />
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<script language="javascript">

function check_data(){
	
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

	document.frmSearch.action="member_list.php";
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
	document.frmSearch.action="member_list.php";
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

function onView(member_id) {
	document.frmSearch.member_id.value = member_id; 
	document.frmSearch.action= "member_view.php";
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
		document.frmSearch.member_id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "member_db.php";
		document.frmSearch.submit();
	}
	else {
		return;
	}

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

function goIn() {
	document.frmSearch.action= "member_input.php";
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
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?  if ($member_kind == "C") {?>
	<TD align="left"><B>소비자 회원 관리</B></TD>
<?	} else {?>
	<TD align="left"><B>분배 회원 관리</B></TD>
<?  }?>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0" <?if($idxfield == "0") echo "selected";?>>FO</OPTION>
		<OPTION VALUE="1" <?if($idxfield == "1") echo "selected";?>>회원이름</OPTION>
		<OPTION VALUE="2" <?if($idxfield == "2") echo "selected";?>>연락처</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
	<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">
	<INPUT TYPE="button" VALUE="등록" onClick="goIn();">	
	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<b><input type="radio" name="rsort" value="number" <?if($sort == "number") echo "checked";?> onClick="check_data();"> FO </b>
					<b><input type="radio" name="rsort" value="name" <?if($sort == "name") echo "checked";?> onClick="check_data();"> 회원명 </b>
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
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%">&nbsp;</TH> 
	<TH width="10%">FO</TH>
	<TH width="13%">회원명</TH>
	<TH width="13%">주민번호 (TAX No)</TH>
	<TH width="12%">연락처</TH>
	<TH width="8%">우편번호</TH>
	<TH width="36%">주소</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
	
			mysql_data_seek($result2,$i);

			$obj = mysql_fetch_object($result2);
			
			if ($i >= $First) {
				
				$date_s1 = date("Y-m-d", strtotime($obj->regdate));
					
?>					
<TR align="center">                    
	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->member_id?>"></TD>
	<TD><A HREF="javascript:onView('<?echo $obj->member_id?>')"><?echo $obj->number?></a></TD>
	<TD align="left">
<?
	if ($obj->email <> "") {
?>
		<a href="mailto:<?echo $obj->email?>"><?echo $obj->name?></a>
<?
	} else {
?>	
	<?echo $obj->name?>
<?
	}
?>
	</TD>
	<TD><?echo $obj->reg_no?></TD>
	<TD><?echo $obj->phone?></TD>
	<TD><?echo $obj->zip?></TD>
	<TD align="left"><?echo $obj->addr?></TD>
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
	    [<b><?echo $date_s1?></b>] 까지 등록된 회원 수 : <?echo $TotalArticle?> 명 
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
<input type="hidden" name="mode" value="del">
<input type="hidden" name="member_id" value="">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="member_kind" value="<?echo $member_kind?>">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>