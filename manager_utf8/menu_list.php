<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_group_list.php
	// 	Description : 관리자 그룹 리스트 화면
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

	$query = "select count(*) from tb_admin_menu m, tb_big_menu b where m.big_menu = b.big_menu";
	$query2 = "select m.menu_id, b.big_menu_name, m.small_menu, m.menu_url, m.big_menu from tb_admin_menu m, tb_big_menu b where m.big_menu = b.big_menu order by b.big_menu";

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

function onView(id) {
	document.frmSearch.menu_id.value = id; 
	document.frmSearch.action= "menu_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "menu_input.php";
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
		alert("삭제하실 메뉴를 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.menu_id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "menu_db.php";
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

function goCheck() {
	document.frmSearch.page.value = 1;
	document.frmSearch.action= "menu_list.php";
	document.frmSearch.submit();
}

</script>
</head>
<BODY bgcolor="#FFFFFF"">
<FORM name="frmSearch" method="post" action="javascript:goCheck();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>메뉴 관리</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<INPUT TYPE="button" VALUE="등록" onClick="goIn();">
	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
	</TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="3" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%">&nbsp;</TH> 
	<TH width="17%">메뉴 그룹</TH>
	<TH width="30%">메뉴명</TH>
	<TH width="50%">메뉴 경로</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {
		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d [H:i]", strtotime($obj->regDate));
	
?>					
<TR align="center">                    
	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->menu_id?>"></TD>
	<TD align="left"><?echo $obj->big_menu_name?></TD>
	<TD align="left"><A HREF="javascript:onView('<?echo $obj->menu_id?>')"><?echo $obj->small_menu?></A></TD>
	<TD align="left"><?echo $obj->menu_url?></TD>
</TR>
<?
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center" colspan=7>
<?
$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str'>이전".$PageScale."개</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="menu_id" value="">
<input type="hidden" name="mode" value="del">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>