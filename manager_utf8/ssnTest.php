<?
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

	logging($s_adm_id,'open ssn list (ssn_list.php)');

	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	$mode					= str_quote_smart(trim($mode));

	$from_date	= str_quote_smart(trim($from_date));
	$to_date	= str_quote_smart(trim($to_date));

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$con_sort			= str_quote_smart(trim($con_sort));
	$con_order		= str_quote_smart(trim($con_order));

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
		$con_sort = "create_date";
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

	if (!empty($from_date)) {
		$que = " and create_date >= '$from_date' ";		
	}

	if (!empty($to_date)) {
		$que = $que." and date_sub(create_date, interval 1 day) <= '$to_date' ";	
		//$que = $que." and regdate <= '$to_date' ";		
	}

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and dist_id like '%$qry_str%' ";
		}
		
		$query = "select count(*) from tb_distSSN where 1 = 1 ".$que;
		$query2 = "select * from tb_distSSN where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;

		logging($s_adm_id,'search ssn '.$que);
	} else {
		$query = "select count(*) from tb_distSSN where 1 = 1 ".$que;
		$query2 = "select * from tb_distSSN where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
	}

	if($to_date != ""){
		$result = mysql_query($query,$connect);
		$row = mysql_fetch_array($result);

		$TotalArticle = $row[0];
		logging($s_adm_id,'search ssn count '.$TotalArticle);
	}

	
	
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

		document.frmSearch.action="ssnTest.php";
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
		document.frmSearch.action="ssnTest.php";
		document.frmSearch.submit();
	}

	function goSort() {
		
		document.frmSearch.con_sort.value = sort;
		document.frmSearch.submit();

	}

	function goPage(i) {
		document.frmSearch.page.value = i;
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
					sValues += "^"+frmSearch.CheckItem.value+"^";
				}
			}
		}
		sValues  +=")";
		return sValues;
	}

	function excelDown(){
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "ssn_excel_list.php";
		frm.submit();
	}

	function goExecl() {
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "ssn_excel_list.php";
		frm.submit();
	}

	function goExecl2() {
		if(confirm("전체 다운로드는 시간이 다소 걸릴 수 있습니다.\n버튼 클릭 후 잠시 기다려 주세요.\n다운로드 받으시겠습니까?")){
			var frm = document.frmSearch;
			frm.target = "";
			frm.action = "ssn_excel_list_t.php";
			frm.submit();
		}
	}

	function toggleCheckbox(element){
		var chkboxes = document.getElementsByName("chkSsn");
 	
 		for(var i=0; i<chkboxes.length; i++){
 			var obj = chkboxes[i];
 			obj.checked = element.checked;
 		}
 	}
 

	function getCheckedValues()
	{
		var checkboxes = document.getElementsByName('chkSsn');
		var vals = "";
		for (var i=0;i<checkboxes.length;i++) 
		{
	    	if (checkboxes[i].checked) 
    		{
        	vals += checkboxes[i].value+',';
    		}
		}
		vals = vals.slice(0, -1); 
		url = 'ssn_delete.php?data='+vals;
	 	NewWindow(url, '일괄처리', 1000, 1000, 'no');
	 
	}
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
</STYLE>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>세금신고용 주민번호 조회</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	
	<?php 
		 	 
	if ($s_flag == 8 || $s_flag == 2 || $s_flag == 1 ||$s_adm_id =='jihyun' ) {

			if($qry_str != ""){
				if($idxfield == "0") $criteria = "회원번호 :".$qry_str;
				else if($idxfield == "0") $criteria = "회원이름 :".$qry_str;
			}

	?>
	
	<!-- <INPUT TYPE="button" VALUE="엑셀받기" onClick="goExecl();"> -->
	<INPUT TYPE="button" VALUE="엑셀받기" onClick="goExcelBefore()">

	<?php if($_SESSION["s_adm_id"] == "admin" || $_SESSION["s_adm_id"] == "eycho" || $_SESSION["s_adm_id"] == "alsrnkmg"){ ?>
	<INPUT TYPE="button" VALUE="전체엑셀받기" onClick="goExecl2()">
	<?php } ?>


	<?php } 
	if ($s_flag == 1 || ($s_flag == 8 && $s_adm_id=='eycho') ) { ?>
	 
	<INPUT TYPE="button" VALUE="삭제" onClick="getCheckedValues();">	
	<input type="button" value="데이터업로드" onClick="NewWindow('ssn_excel_upload.php', '데이터업로드', 650, 150, 'no');">
	 
<?php } ?>
	</TD>
</TR>
</TABLE>

<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
	<tr>
		<td align='center'>
			<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>				
				<tr>
					<td align="right" width="100" style="height:27px">
						<b>등록일자 : &nbsp;</b>
					</td>
					<td> &nbsp;
						<input type="text" name="from_date" value="<?echo $from_date?>" size="11" maxlength="10">~
						<input type="text" name="to_date" value="<?=($to_date != "") ? $to_date : date("Y-m-d");?>" size="11" maxlength="10" placeholder="YYYY-MM-DD"> [2004-12-01의 형태로 입력, <font color="red">미입력시 리스트가 출력되지 않습니다.</font>]
					</td>
				</tr>
				<tr>
					<td align="right" width="100" style="height:27px">
						<b>검색 : &nbsp;</b>
					</td>
					<td>
						&nbsp;
						<SELECT NAME="idxfield">
							<OPTION VALUE="0" <?if($idxfield == "0") echo "selected";?>>회원번호</OPTION>
						</SELECT>
						<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
						<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">
					</td>
				</tr>
				<tr>
					<td align="right" width="100" style="height:27px">
						<b>정렬 : &nbsp;</b>
					</td>
					<td>
						<table width='99%' bgcolor="#EEEEEE">
							<tr align="center">
								<td align="left">
									<b><input type="radio" name="rsort" value="create_date" <?if($con_sort == "create_date") echo "checked";?> onClick="check_data();"> 등록일 </b>
									<b><input type="radio" name="rsort" value="dist_id" <?if($con_sort == "dist_id") echo "checked";?> onClick="check_data();"> 회원번호 </b>
								<td align="right">
									<b><input type="radio" name="rorder" value="con_d" <?if($con_order == "con_d") echo "checked";?> onClick="check_data();">오름차순 </b>
									<b><input type="radio" name="rorder" value="con_a" <?if($con_order == "con_a") echo "checked";?> onClick="check_data();">내림차순 </b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver"  style="margin-top:10px">
<TR>
	<TH width="2%" style="text-align: center;"><input type="checkbox" id="chckHead" onchange="toggleCheckbox(this);" /></td>
	<TH width="10%">회원번호</TH>
	<TH width="78%">주민등록번호</TH>
	<TH width="10%">등록일</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		while($obj = mysql_fetch_object($result2)) {
			
			$create_date = date("Y-m-d H:i", strtotime($obj->create_date));

			$jumin_number = decrypt($key, $iv, $obj->government_id);
?>
<TR align="center" style="height:25px">
	<TD align="center"><input type="checkbox" name="chkSsn" value="<?echo $obj->id?>"></TD>
	<TD><?echo $obj->dist_id?></TD>
	<TD style="text-align:left;padding-left:20px"><?echo left($jumin_number, 6)?>-<?echo substr($jumin_number, 6,1) ?>****** 
		<?php if($obj->create_date == '2017-01-01 00:00:00') echo '신고자'; ?>
	</TD>
	<TD><?echo $create_date?></TD>
</TR>
<?

		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left">
	    등록된 주민등록번호 수 : <?echo $TotalArticle?> 개
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
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
<input type="hidden" name="idVal" value="">
</form>

<script type="text/javascript">
function goExcelBefore() {	
	var checkboxes = document.getElementsByName('chkSsn');	

	var vals = "";
	for (var i=0;i<checkboxes.length;i++) {	    	
		if (checkboxes[i].checked) {
			vals += checkboxes[i].value+',';
		}
	}
	vals = vals.slice(0, -1); 

	if(vals == ""){
		alert("선택내역이 없습니다");
		return;
	}			
	document.frmSearch.idVal.value=vals;				

	goExcelHistory('수당관리','세금신고용 주민번호 조회','<?=$criteria?>');
}
</script>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

<? include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/excel_modal.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>