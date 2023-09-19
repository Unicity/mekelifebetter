<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_list.php
	// 	Description : 관리자 리스트 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	logging($s_adm_id,'open admin list (admin_list.php)');
	
	$qry_str	= str_quote_smart(trim($qry_str));
	$idxfield	= str_quote_smart(trim($idxfield));
	$page			= str_quote_smart(trim($page));

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and a.id like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and a.UserName like '%$qry_str%' ";
		} else {
			$que = " and a.temp2 like '%$qry_str%' ";
		}
		logging($s_adm_id,'search admin '.$que);

		$query = "select count(*) from tb_admin a ,tb_admin_group g where a.temp1 = g.group_id ".$que;
		$query2 = "select * from tb_admin a ,tb_admin_group g where a.temp1 = g.group_id ".$que. " order by a.regDate desc";

	} else {
		$query = "select count(*) from tb_admin a ,tb_admin_group g where a.temp1 = g.group_id";
		$query2 = "select * from tb_admin a ,tb_admin_group g where a.temp1 = g.group_id order by a.regDate desc";
	}

	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	logging($s_adm_id,'search admin count '.$TotalArticle);
	
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
	document.frmSearch.action= "admin_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "admin_input.php";
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
		alert("삭제하실 관리자를 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "admin_db.php";
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

</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="admin_list.php">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>관리자 관리</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">관리자 ID</OPTION>
		<OPTION VALUE="1">관리자 성명</OPTION>
		<OPTION VALUE="2">관리자 부서명</OPTION>
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
	<TH width="13%">관리자 ID</TH>
	<TH width="10%">관리자 성명</TH>
	<TH width="10%">관리자 부서</TH>
	<TH width="10%">관리자 그룹</TH>
	<TH width="10%">연락처</TH>
	<TH width="14%">E-Mail</TH>
	<TH width="10%">등록일</TH>
	<TH width="10%">상태</TH>
	<TH width="10%">마지막로그인</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				if($obj->regDate == "") $date_s = '-';
				else $date_s = date("Y-m-d [H:i]", strtotime($obj->regDate));
	
?>					
<TR align="center">                    
	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->id?>"></TD>
	<TD><A HREF="javascript:onView('<?echo $obj->id?>')"><?echo $obj->id?></A></TD>
	<TD align="left"><?echo $obj->UserName?></TD>
	<TD><?echo $obj->temp2?></TD>
	<TD><?echo $obj->group_name?></TD>
	<TD><?echo $obj->Phone1?></TD>
	<TD><?echo $obj->Email?></TD>
	<TD><?echo $date_s?></TD>
	<TD><?echo ($obj->status == "Y") ? "정상" : "제한"; ?></TD>
	<TD><?=($obj->last_login == "") ? "-" : date("Y-m-d [H:i]", $obj->last_login);?></TD>
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
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>