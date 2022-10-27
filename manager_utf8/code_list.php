<?
	include "admin_session_check.inc";
	include "../dbconn_utf8.inc";

	$parent = $parent;

	$page			= str_quote_smart(trim($page));
	$parent		= str_quote_smart(trim($parent));
	$idxfield = str_quote_smart(trim($idxfield));
	$qry_str	= str_quote_smart(trim($qry_str));

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and code like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = " and memo like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_code where parent = '".$parent."' ".$que;
		$query2 = "select * from tb_code where parent = '".$parent."' ".$que. " order by code";

	} else {
		$query = "select count(*) from tb_code where parent = '".$parent."'";
		$query2 = "select * from tb_code where parent = '".$parent."' order by code";
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
<meta http-equiv="X-Frame-Options" content="deny" />
<title>:::::유니시티 관리자 시스템:::::</title>
<link rel="stylesheet" href="inc/admin.css" type="text/css">
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</STYLE>
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
	document.frmSearch.action= "code_view.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "code_input.php";
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
		document.frmSearch.action = "code_db.php";
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

</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">
<br>
<br>
<FORM name="frmSearch" method="post" action="code_list.php">
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<!--<b><a href="code_list.php?parent=goods">제품군</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=brand">브랜드</a></b>&nbsp;&nbsp;
					 <b><a href="code_list.php?parent=qna">고객의견분류</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=ask">문의분류</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=pds">자료실분류</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=job">직업</a></b>&nbsp;&nbsp;-->
					<b><a href="code_list.php?parent=bank">은행</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=bank3">3자리은행코드</a></b>&nbsp;&nbsp;
					<b><a href="code_list.php?parent=mail">메일계정</a></b>&nbsp;&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?if ($parent == 'goods') {?>
	<TD align="left"><B>제품군</B></TD>
<?} else if ($parent == 'brand') {?>
	<TD align="left"><B>브랜드</B></TD>
<?} else if ($parent == 'qna') {?>
	<TD align="left"><B>고객의견함분류</B></TD>
<?} else if ($parent == 'ask') {?>
	<TD align="left"><B>제품문의분류</B></TD>
<?} else if ($parent == 'pds') {?>
	<TD align="left"><B>자료실분류</B></TD>
<?} else if ($parent == 'job') {?>
	<TD align="left"><B>직업</B></TD>
<?} else if ($parent == 'bank') {?>
	<TD align="left"><B>은행</B></TD>
<?} else if ($parent == 'bank3') {?>
	<TD align="left"><B>3자리은행코드</B></TD>
<?} else if ($parent == 'mail') {?>
	<TD align="left"><B>메일계정</B></TD>
<?}?>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">이 름</OPTION>
		<OPTION VALUE="1">코 드</OPTION>
		<OPTION VALUE="2">설 명</OPTION>
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
	<TH width="20%">코 드</TH>
	<TH width="20%">이 름</TH>
	<TH width="57%">설 명</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
	
?>					
<TR align="center">                    

	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->id?>"></TD>
	<TD align="left"><A HREF="javascript:onView('<?echo $obj->id?>')"><?echo $obj->code?></A></TD>
	<TD align="left"><?echo $obj->name?></TD>
	<TD align="left"><?echo $obj->memo?></TD>
</TR>
<?
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center" colspan=4>
<?
$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>이전".$PageScale."개</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&parent=$parent&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
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
<input type="hidden" name="parent" value="<?echo $parent?>">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>