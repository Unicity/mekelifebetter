<?php session_start();?>

<?php //php 시작

	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2017-04-28
	// 	Last Update : 
	// 	Author 		: Park, ChanHo
	// 	History 	:  
	// 	File Name 	: ssn_list.php
	// 	Description : 
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "./admin_session_check.inc";
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

	logging($s_adm_id,'open product training applicant list (eduApply_admin.php)');
	
	$yyyy= date('Y');
	
	$mode = str_quote_smart(trim($mode));

	$qry_str = str_quote_smart(trim($qry_str));
	$idxfield = str_quote_smart(trim($idxfield));
	$page = str_quote_smart(trim($page));
	$con_sort = str_quote_smart(trim($con_sort));
	$con_order = str_quote_smart(trim($con_order));
			


	if ($con_order == "con_a") {
		$order = "asc";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}

	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	if (empty($con_sort)) {
		$con_sort = "applyDate";
	}

	if (empty($con_order)) {
		$order = "desc";
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

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and id like '%$qry_str%' ";
		}
		
		$query = "select count(*) from education_apply where 1 = 1 ".$que;
		//$query2 = "select * from education_apply where 1 = 1 ".$que." and year = ".$yyyy." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
		$query2 = "select * from education_apply where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
		logging($s_adm_id,'search training applicant '.$que.' '.$yyyy);
	
	} else {
		$query = "select count(*) from education_apply where 1 = 1 ";
		//$query2 = "select * from education_apply where 1 = 1 and year = ".$yyyy." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
		$query2 = "select * from education_apply where 1 = 1 order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
	}

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
 	logging($s_adm_id,'search training applicant count '.$TotalArticle);
 	
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="./inc/admin.css" type="text/css">
<script type="text/javascript" src="/js/jquery-1.8.2.js"></script>
<script language="javascript">

function check_data(){
	
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

	document.frmSearch.action="eduApply_admin.php";
	document.frmSearch.submit();
}

function onSearch(){
	
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
	document.frmSearch.action="eduApply_admin.php";
	document.frmSearch.submit();
}

function goPage(i) {
	document.frmSearch.page.value = i;
	document.frmSearch.submit();
}

function excelDown(){
	var frm = document.frmSearch;
	frm.target = "";
	frm.action = "eduApply_excel_list.php";
	frm.submit();
}
function goExecl() {
	var frm = document.frmSearch;
	frm.target = "";
	frm.action = "eduApply_excel_list.php";
	frm.submit();
}

function onView(id){

	document.location = "eduApply_view.php?applyId="+id+"&page=<?echo $page?>&sort=<?echo $con_sort?>&order=<?echo $order?>";
}


</script>
<style type='text/css'>
td {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</style>
</head>
<body bgcolor="#FFFFFF">

<?
if($qry_str != ""){
	if($idxfield == "0") $criteria = "회원번호 :".$qry_str;
	else if($idxfield == "0") $criteria = "회원이름 :".$qry_str;
}
?>
<form name="frmSearch" method="post" ction="javascript:check_data();">
<table cellspacing="0" cellpadding="10" class="title">
<tr>
	<td align="left"><b>건식 보수교육신청</b></td>
	<td align="right" width="600" align="center" bgcolor=silver>
	<select name="idxfield">
		<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</OPTION>
	</select>
	<input type="text" name="qry_str" value="<?echo $qry_str?>">&nbsp;
	<input type="button" value="검색" onClick="onSearch();">
	<!-- <input type="button" value="엑셀 다운로드" onClick="goExecl();"> -->
	<input type="button" value="엑셀 다운로드" onClick="goExcelHistory('회원관리','건식 보수교육신청','<?=$criteria?>');">
	<!--
	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
	-->
	</td>
</tr>
</table>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table width='99%' bgcolor="#EEEEEE">
			<tr align="center">
				<td align="left">
					<b><input type="radio" name="rsort" value="applyDate" <?if($con_sort == "applyDate") echo "checked";?> onClick="check_data();"> 등록일 </b>
					<b><input type="radio" name="rsort" value="id" <?if($con_sort == "id") echo "checked";?> onClick="check_data();"> 회원번호 </b>
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
<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<tr align="center">
	<th width="5%" style="text-align: center;">회원번호</th>
	<th width="5%" style="text-align: center;">성명</th>
	<th width="5%" style="text-align: center;">생년월일</th>
	<th width="10%" style="text-align: center;">휴대폰</th>
	<th width="15%" style="text-align: center;">이메일</th>
	<th width="10%" style="text-align: center;">인허가번호</th>
	<th width="5%" style="text-align: center;">대표자<br/>성명</th>
	<th width="5%" style="text-align: center;">대표자<br/>생년월일</th>
	<th width="5%" style="text-align: center;">대리여부</th>
	<th width="15%" style="text-align: center;">대리사유</th>
	<th width="5%" style="text-align: center;">신청일자</th>
	<th width="5%" style="text-align: center;">취소여부</th>
	<th width="5%" style="text-align: center;">수정일자</th>
	<th width="5%" style="text-align: center;">수정자</th>
</tr>     
<?php
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		while($obj = mysql_fetch_object($result2)) {
			
			$date_s = date("Y-m-d", strtotime($obj->applyDate));
			$date_m = date("Y-m-d", strtotime($obj->modifyDate));
			if($date_m =='1970-01-01'){
				$date_m = "";
			}
?>
<tr>                    
	<td style="width: 5%" align="center"><a href="javascript:onView('<?echo $obj->id?>')"><?echo $obj->id?></a></td>
	<td style="width: 5%" align="center"><?echo $obj->UserName?></td>
	<td style="width: 5%" align="center"><?echo $obj->birthDay?></td>
	<td><?echo $obj->Phone?></td>
	<td><?echo $obj->email?></td>
	<td><?echo $obj->licenseNum?></td>
	<td><?echo $obj->representativeName?></td>
	<td><?echo $obj->representativeBirth?></td>
	<td>
		<select id="deputyEduYn" name="deputyEduYn" style="width: 100%;text-align:center;" disabled="true";>	
			<option value='Y'<?if($obj->deputyEduYn=="Y"){?>selected<?}?>>Y</option>
			<option value='N'<?if($obj->deputyEduYn=="N"){?>selected<?}?>>N</option>
		</select>
	</td>
	<td>
		<select id="deputyReason" name="deputyReason" style="width:100%;text-align:center;" disabled="true";>	
			<option value='0'<?if($obj->deputyReason==""){?>selected<?}?>></option>
			<option value='1'<?if($obj->deputyReason=="1"){?>selected<?}?>>천재지변, 질병사고, 업무상 국외출장 등의 사유로 교육을 받은 수 없는 경우</option>
			<option value='2'<?if($obj->deputyReason=="2"){?>selected<?}?>>영업자가 영업에 직접 종사하지 아니한 경우</option>
			<option value='3'<?if($obj->deputyReason=="3"){?>selected<?}?>>2곳 이상의 장소에서 같은 영업자가 영업을 하려는 경우</option>	
		</select>
	</td>
	<td align="center"><?php echo $date_s ?></td>
	<td>
		<select id="cancelYn" name="cancelYn" style="width:100%;text-align:center;" disabled="true";>	
			<option value='Y'<?if($obj->cancelYn=="Y"){?>selected<?}?>>Y</option>
			<option value='N'<?if($obj->cancelYn=="N"){?>selected<?}?>>N</option>
		</select>
	</td>
	<td align="center"><?php echo $date_m ?></td>
	<td align="center"><?php echo $obj->companyMember ?></td>
</tr>
<?php

		}
	}
?>
</table>
<table cellspacing="1" cellpadding="5" class="LIST" border="0">
<tr>
	<td align="left">
	    등록된 회원 수 : <?echo $TotalArticle?> 개
	</td>
	<td align="right">
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
	</td>
</tr>
</table>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

<? include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/excel_modal.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>