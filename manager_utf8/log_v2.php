<?
include "admin_session_check.inc";
include "./inc/global_init.inc";
include "../dbconn_utf8.inc";
include "../AES.php";


	$qry_str	= str_quote_smart(trim($qry_str));
	$idxfield	= str_quote_smart(trim($idxfield));
	$page		= str_quote_smart(trim($page));
	$atype		= str_quote_smart(trim($atype));

	//if($atype == "") $atype = "B"; //계좌인증 기본
	//if (!empty($qry_str)) { //검색어가 있는경우만 쿼리 실행
		//$que = $que." and ".$idxfield." like '%".$qry_str."%' ";
		
		if($atype != ''){
			if($atype == "realname") $que = " and check_kind != 'api' and  check_kind != 'bank'";
			else $que = " and check_kind = '".$atype."'";
		}
		if(!empty($qry_str)) $que = $que." and ".$idxfield." = '".$qry_str."' ";


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

		$query = "select count(*) from tb_log_v2 where 1 ".$que;
		$result = mysql_query($query,$connect) or die(mysql_error());
		$row = mysql_fetch_array($result);
		$TotalArticle = $row[0];

		$query2 = "select * from tb_log_v2 where 1 ".$que." order by uid desc limit ". $offset.", ".$nPageSize; ;
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


		//은행코드 가져오기
		/*
		$sqlstr = "SELECT * FROM tb_code where parent='bank3' order by code"; 
		$results = mysql_query($sqlstr) or die(mysql_error());	
		for($i=0; $i<mysql_num_rows($results); $i++) {
			$rows = mysql_fetch_array($results);
			$bnames = $rows[name];
			$bankCode[$bnames] = $rows[code];
		}
		*/
	//}
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
	document.frmSearch.action="log_v2.php";
	document.frmSearch.submit();
}


function goPage(i) {
	document.frmSearch.page.value = i;
	document.frmSearch.action="log_v2.php";
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
function js_detail(uid){
	window.open("log_v2_detail.php?uid="+uid, "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width=500,height=400");
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
	<TD align="left"><B>회원가입 LOG 조회</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		<button onclick="location.href='bank_auth_log.php'">구 LOG 조회</button>
	</TD>
</TR>
</TABLE>

<br>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" height="40">
					<b>구분 : &nbsp;</b>
				</td>
				<td colspan="3">
					&nbsp;&nbsp;
					<input type="radio" name="atype" id="atype" value="bank" <?if($atype == "bank") echo "checked";?> /> 계좌인증 &nbsp;&nbsp;
					<input type="radio" name="atype" id="atype" value="realname" <?if($atype == "realname") echo "checked";?> /> 실명인증
					<input type="radio" name="atype" id="atype" value="api" <?if($atype == "api") echo "checked";?> /> API
				</td>
			</tr>
			<tr>
				<td align="right" height="40">
					<b>검 색 : &nbsp;</b>
				</td>
				<td colspan="3">
					&nbsp;&nbsp;
					<SELECT NAME="idxfield">
						<OPTION VALUE="name" <?if($idxfield == "name") echo "selected";?>>이름</OPTION>
						<OPTION VALUE="tmpId" <?if($idxfield == "tmpId") echo "selected";?>>세션번호</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>"  onKeyDown="onKeyDown();">&nbsp;
					<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">&nbsp;
					
				</td>
			</tr>
			
		</table>
	</td>
</tr>
</table>
<br>
<table width="100%" border="0">
	<tr>
		<td>* 회원가입 로그를 확인하실 경우에는 <u><strong>세션번호</strong></u>로 조회하여 주세요.</td>
		<td align="right">
			<font color="blue">총건수 : <?=$TotalArticle?></font>&nbsp;&nbsp;
		</td>
	</tr>
</table>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="18%" style="text-align:center">세션번호</TH>
	<TH width="8%" style="text-align:center">회원번호</TH>
	<TH width="8%" style="text-align:center">이름</TH>
	<TH width="8%" style="text-align:center">생년월일</TH>
	<TH width="23%" style="text-align:center">내용</TH>
	<TH width="7%" style="text-align:center">결과</TH>
	<TH width="7%" style="text-align:center">디바이스</TH>
	<TH width="14%" style="text-align:center">로그일시</TH>
	<TH width="7%" style="text-align:center">상세</TH>

</TR>     
<?

	if ($TotalArticle) {
		
		while($obj = mysql_fetch_object($result2)) {

			$yn = ($obj->flag == 'N') ? '<font color="red">'.$obj->flag.'</font>' : $obj->flag ;

			?>
			<TR align="center">
				<TD height="25" align="center"><?=$obj->tmpId?></TD>
				<TD height="25" align="center"><?=($obj->memid != '') ? $obj->memid : '-';?></TD>
				<TD align="center"><?=$obj->name?></TD>
				<TD align="center">
					<?php 
					$obj->jumin1 = str_replace("-","", $obj->jumin1);
					if(strlen($obj->jumin1) == 8) $obj->jumin1 = substr($obj->jumin1, 2,6);
					echo $obj->jumin1;
					?>
				</TD>
				<TD align="left"><?=$obj->gubun?></TD>
				<TD align="center"><?=$yn?></TD>
				<TD align="center">
					<?=($obj->device == "P") ? "PC" : "Mobile";?>
				</TD>
				<TD align="center"><?=$obj->logdate?></TD>
				<TD align="center"><a href="javscript:;" onclick="js_detail('<?=$obj->uid?>')">[조회]</a></TD>
			</TR>
			<?
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