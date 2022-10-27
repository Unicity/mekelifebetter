<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";


	//logging($s_adm_id,'open new member list (user_list.php)');

	$idxfield				= str_quote_smart(trim($idxfield));
	$qry_str				= str_quote_smart(trim($qry_str));

	$from_date			= str_quote_smart(trim($from_date));
	$to_date				= str_quote_smart(trim($to_date));
	
	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));

	$toDay = date("Y-m-d");

	if($to_date == "") $to_date = $toDay;
	if($from_date == "") $from_date = date("Y-m")."-01";

	$que = "and substring(ex_date, 1,10) between '".$from_date."' and '".$to_date."'";

	if ($idxfield != "" && $qry_str != "") {
		$que = $que." and ".$idxfield." like '%$qry_str%' ";
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

	$que .= " and ifnull(del_tf,'N') = 'N' ";

	$query = "select count(*) from tb_excel_log where 1 = 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	
	//logging($s_adm_id,'search user count '.$TotalArticle);
	
	$query2 = "select * from tb_excel_log where 1 = 1 ".$que." order by ex_uid desc limit ". $offset.", ".$nPageSize;
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
<STYLE type='text/css'>
TD {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}

.input { background-color:#000; color:#fff; font-size:14px; cursor:pointer; padding:10px 20px; }
</STYLE>
<script type="text/javascript" src="/js/jquery-1.8.2.js"></script>
<script language="javascript">
function openWin(n){
	window.open("user_off_detail.php?mno="+n, "_blank", "toolbar=no,scrollbars=auto,resizable=no,top=50,left=50,width=600,height=650");
}
function check_data(){

	for(i=0; i < document.frmSearch.r_status.length ; i++) {
		if (document.frmSearch.r_status[i].checked == true) {
			document.frmSearch.reg_status.value = document.frmSearch.r_status[i].value;
		}
	}
			
	document.frmSearch.action="excel_download_history.php";
	document.frmSearch.submit();
}


function onSearch(){

	document.frmSearch.page.value="1";
	document.frmSearch.action="excel_download_history.php";
	document.frmSearch.submit();
}

$(function(){
	$('#allcheck').click(function(){ 
		if($("#allcheck").is(":checked")){ 
			$(".check").prop("checked", true); 
		}else{ 
			$(".check").prop("checked", false);
		} 
	});
});

function goPage(n){
	if(n > 0){
		document.frmSearch.page.value =  n;
		document.frmSearch.action="excel_download_history.php";
		document.frmSearch.submit();
	}
}
</script>
</head>
<BODY bgcolor="#FFFFFF">

<FORM name="frmSearch" id="frmSearch"  method="post" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>엑셀 다운로드 리스트</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		
	</TD>
</TR>
</TABLE>

<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" width="100" style="padding:7px 0px">
					<b>일 자 : &nbsp;</b>
				</td>
				<td  style="padding:7px">
					<input type="text" name="from_date" value="<?echo $from_date?>" size="11" maxlength="10"> ~
					<input type="text" name="to_date" value="<?echo $to_date?>" size="11" maxlength="10"> [2019-01-01의 형태로 입력하세요.]
				</td>
			</tr>
			<tr>
				<td align="right" style="padding:7px 0px">
					<b>검 색 : &nbsp;</b>
				</td>
				<td  style="padding:7px">
					<SELECT NAME="idxfield">
						<OPTION VALUE="" <?if($idxfield == "") echo "selected";?>>선택</OPTION>
						<OPTION VALUE="ex_type" <?if($idxfield == "ex_type") echo "selected";?>>사유</OPTION>
						<OPTION VALUE="ex_task" <?if($idxfield == "ex_task") echo "selected";?>>상세사유</OPTION>
						<OPTION VALUE="ex_cate" <?if($idxfield == "ex_cate") echo "selected";?>>대분류</OPTION>
						<OPTION VALUE="ex_page" <?if($idxfield == "ex_page") echo "selected";?>>페이지명</OPTION>
						<OPTION VALUE="ex_adm" <?if($idxfield == "ex_adm") echo "selected";?>>관리자아이디</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
					<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">&nbsp;					
				</td>
			</tr>
			
		</table>
	</td>
</tr>
</table>

<!-- <table width="100%" border="0">
	<tr>
		<td width="100%">
			<input type="button" value="선택삭제" onclick="deleteChk()" />
		</td>
	</tr>
</table> -->
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<!-- <TH width="3%" style="text-align:center"><input type="checkbox" name="allcheck" id="allcheck" value="Y" title="전체선택" /></TH>	 -->
	<TH width="10%" style="text-align:center">대분류</TH>	
	<TH width="10%" style="text-align:center">페이지명</TH>
	<TH width="10%" style="text-align:center">검색조건</TH>
	<TH width="10%" style="text-align:center">사유</TH>
	<TH width="19%" style="text-align:center">상세사유</TH>
	<TH width="11%" style="text-align:center">관리자아이디</TH>
	<TH width="10%" style="text-align:center">일자</TH>
	<TH width="10%" style="text-align:center">IP</TH>
</TR>     
<?
if ($TotalArticle) {
	
	while($obj = mysql_fetch_object($result2)) {
		$birth = substr($obj->birth,0,4)."-".substr($obj->birth,4,2)."-".substr($obj->birth,6,2);
		$reg_date = substr($obj->reg_date,0,4)."-".substr($obj->reg_date,4,2)."-".substr($obj->reg_date,6,2);
		
		?>
		<TR align="center">
			<!-- <TD><input type="checkbox" name="chk[]" id="chk" class="check" value="<?=$obj->ex_uid?>" /></TD> -->
			<TD height="25"><?=$obj->ex_cate?></TD>
			<TD><?=$obj->ex_page?></TD>
			<TD><?=$obj->ex_detail?></TD>
			<TD><?=$obj->ex_type?></TD>
			<TD align="left"><?=$obj->ex_task?></TD>
			<TD><?=$obj->ex_adm?></TD>
			<TD><?=substr($obj->ex_date,0,16)?></TD>
			<TD><?=$obj->ex_ip?></TD>
		</TR>
		<?
	}
}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left">
	    조회건수 : <?=number_format($TotalArticle)?> 건
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
<input type="hidden" name="mode" value="">
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
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