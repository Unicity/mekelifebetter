<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	logging($s_adm_id,'open log list '.$qry_str.'(admin_log.php)');

	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));

	$from_date ==  str_quote_smart(trim($from_date));
	$to_date ==  str_quote_smart(trim($to_date));

	$que = "";

	if($from_date != "") $que = " and  createdDate >= '".substr($from_date, 0, 4)."-".substr($from_date, 4, 2)."-".substr($from_date, 6, 2)." 00:00:00'";
	if($to_date != "") $que = " and createdDate <= '".substr($to_date, 0, 4)."-".substr($to_date, 4, 2)."-".substr($to_date, 6, 2)." 23:59:59'";
	
	if($idxfield != "" && $qry_str != ""){
		if($idxfield == 'log') $que = $que." and actionType like '%".$qry_str."%' ";
		else $que = $que." and ".$idxfield." = '".$qry_str."' ";
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

	$query = "select count(*) from tb_user_log where 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	if($que == "") $query2 = "select * from tb_user_log where id <= ".($TotalArticle - $offset)." order by id desc limit ".$nPageSize;
	else $query2 = "select * from tb_user_log where 1 ".$que." order by id desc limit ". $offset.", ".$nPageSize; ;	
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
<script language="javascript">
function onSearch(){
	document.frmSearch.page.value="1";
	document.frmSearch.action="admin_log_dev.php";
	document.frmSearch.submit();
}


function goPage(i) {
	document.frmSearch.page.value = i;
	document.frmSearch.action="admin_log_dev.php";
	document.frmSearch.submit();
}

function onKeyDown(){
     if(event.keyCode == 13){
		if(document.frmSearch.qry_str.value.length < 2){ 
			alert("검색어를 2자이상 입력해 주세요");
		}else{
			onSearch();
		}
     }
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
<FORM name="frmSearch" method="post" action="javascript:check_data();">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="atype" value="<?echo $atype?>">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>관리자 로그</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp;
	</TD>
</TR>
</TABLE>

<br>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" width="100">
					<b>기 간 : &nbsp;</b>
				</td>
				<td>
					&nbsp;&nbsp;
					<input type="text" name="from_date" value="<?=$from_date?>" size="8" maxlength="8"> ~
					<input type="text" name="to_date" value="<?=$to_date?>" size="8" maxlength="8"> [20220101의 형태로 입력하세요.]
				</td>
			</tr>
			<tr>
				<td align="right" height="40">
					<b>검 색 : &nbsp;</b>
				</td>
				<td colspan="3">
					&nbsp;&nbsp;
					<SELECT NAME="idxfield">
						<OPTION VALUE="adminId" <?if($idxfield == "adminId") echo "selected";?>>관리자ID</OPTION>
						<OPTION VALUE="ip" <?if($idxfield == "ip") echo "selected";?>>IP</OPTION>
						<OPTION VALUE="log" <?if($idxfield == "log") echo "selected";?>>LOG</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
					<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">&nbsp;&nbsp;&nbsp;
					<span style="color:Red">*** LOG검색시 시간이 많이 소요되므로 반드시 기간을 입력하여 주세요 ***</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="100%" border="0">
	<tr>
		<td>* 관리자ID, IP 검색은 유사검색을 하지 않고 입력값과 일치하는 데이터만 검색됩니다 (조회시 다소 시간이 걸릴 수 있습니다).  </td>
		<td align="right">
			<font color="blue">총건수 : <?=$TotalArticle?></font>&nbsp;&nbsp;
		</td>
	</tr>
</table>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="10%" style="text-align:center">No</TH>
	<TH width="20%" style="text-align:center">아이디</TH>
	<TH width="40%" style="text-align:center">LOG</TH>
	<TH width="13%" style="text-align:center">IP</TH>
	<TH width="17%" style="text-align:center">로그일시</TH>

</TR>     
<?

	if ($TotalArticle) {
		
		while($obj = mysql_fetch_object($result2)) {

?>
<TR align="center">
	<TD align="center"><?=$NumberArticle?></TD>
	<TD align="center"><?=$obj->adminId?></TD>
	<TD><?=$obj->actionType?></TD>
	<TD><?=$obj->ip?></TD>
	<TD align="center"><?=$obj->createdDate?></TD>
</TR>
<?
			$NumberArticle--;
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left" style="line-height:160%">
	    
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
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>