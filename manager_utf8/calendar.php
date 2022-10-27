<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$page					=	str_quote_smart(trim($page)); 
	$mode					=	str_quote_smart(trim($mode)); 
	$id						=	str_quote_smart(trim($id)); 
	$NewsId				=	str_quote_smart(trim($NewsId)); 
	$idxfield			=	str_quote_smart(trim($idxfield)); 
	$qry_str			=	str_quote_smart(trim($qry_str)); 

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and Title like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and Content like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_global_news where NewsId = '".$NewsId."' ".$que;
		$query2 = "select * from tb_global_news where NewsId = '".$NewsId."' ".$que. " order by SeqNo desc";

	} else {
		$query = "select count(*) from tb_global_news where NewsId = '".$NewsId."'";
		$query2 = "select * from tb_global_news where NewsId = '".$NewsId."' order by SeqNo desc";
	}
	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 10;
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

function form_check(){

	if (frmSearch.query.value=="") {
		alert("검색어를 넣으세요");
		return false;
	} else {
		frmSearch.submit();
	}

}

function init(){
<?	if (!empty($qry_str)) {  ?>
		document.frmSearch.qry_str.value="<?echo $qry_str ?>";
		document.frmSearch.idxfield.options[<?echo $idxfield ?>].selected = true;
<?	} ?>

}

function onView(id) {
	document.frmSearch.id.value = id; 
	document.frmSearch.action= "global_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "global_input.php";
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
		alert("삭제하실 자료를 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "global_db.php";
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
				sValues += frmSearch.CheckItem.value;
			}
		}
	}
	sValues  +=")";
	return sValues;
}

function setBshow(iBshow, id) {
	document.frmSearch.bshow.value = iBshow;
	document.frmSearch.mode.value = "bshow";
	document.frmSearch.id.value = id; 
	document.frmSearch.action= "global_db.php";
	document.frmSearch.submit();
}

function goCheck() {
	document.frmSearch.page.value = 1;
	document.frmSearch.action= "global_list.php";
	document.frmSearch.submit();
}


</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">
<FORM name="frmSearch" method="post" action="javascript:goCheck();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?
	if ($NewsId == "A1") {
?>
	<TD align="left"><B>글로벌뉴스</B></TD>
<?
	} else if ($NewsId == "A2") {
?>
	<TD align="left"><B>글로벌뉴스</B></TD>
<?
	}
?>
	<TD align="right" width="400" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">제 목</OPTION>
		<OPTION VALUE="1">내 용</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
	<INPUT TYPE="submit" VALUE="검색">
	<INPUT TYPE="button" VALUE="등록" onClick="goIn();">
	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
	</TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%">&nbsp;</TH> 
	<TH width="10%">번 호</TH>
<?
	if ($NewsId == "A6") {
?>
	<TH width="20%">이미지</TH>
	<TH width="47%">제 목</TH>
<?
	} else {
?>
	<TH width="55%">제 목</TH>
<?
	}
?>
	<TH width="15%">등록일</TH>
	<TH width="10%">조회수</TH>
	<TH width="10%">전시여부</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d [H:i]", strtotime($obj->RegDate));
	
?>					
<TR align="center">                    

	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->SeqNo?>"></TD>
	<TD><A HREF="javascript:onView('<?echo $obj->SeqNo?>')"><?echo $obj->SeqNo?></A></TD>
<?
	if ($NewsId == "A6") {
	
		if ($obj->Image != "none") {
?>
	<TD align="left"><img src="../<?echo $obj->Image?>" border=0 height=60></TD>
<?
		} else {
?>
	<TD align="left">이미지 없음</TD>
<?
		}
	}
?>

	<TD align="left">
<?
	$s_kind = "";

	if ($obj->kind == "0") {
		$s_kind = "[일반]";
	} else {
		$s_kind = "[링크]";
	}
?>	
	
	<?echo $s_kind?> <?echo $obj->Title?></TD>

	<TD><?echo $date_s?></TD>
	<TD><?echo $obj->cnt?></TD>
	<TD>
<?
	if ($obj->bshow == "0") {
		echo "<a href='javascript:setBshow(1,$obj->SeqNo);'><img src='images/ico_show0.gif' border=0></a>";
	} else {
		echo "<a href='javascript:setBshow(0,$obj->SeqNo);'><img src='images/ico_show1.gif' border=0></a>";
	}
?>	
	</td>
</TR>
<?
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>

<?
	if ($NewsId == "A6") {
?>
	<TD align="center" colspan=5>
<?
	} else {
?>
	<TD align="center" colspan=4>
<?
	}


$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&NewsId=$NewsId&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&NewsId=$NewsId&qry_str=$qry_str'>이전".$PageScale."개</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&NewsId=$NewsId&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&NewsId=$NewsId&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&NewsId=$NewsId&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="NewsId" value="<?echo $NewsId?>">
<input type="hidden" name="id" value="">
<input type="hidden" name="bshow" value="">
<input type="hidden" name="mode" value="del">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>