<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";


	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$atype	= str_quote_smart(trim($atype));

	if($atype == "") $atype = "B"; //계좌인증 기본


	if (!empty($qry_str)) { //검색어가 있는경우만 쿼리 실행

		//$que = $que." and ".$idxfield." like '%".$qry_str."%' ";
		$que = $que." and ".$idxfield." = '".$qry_str."' ";
	

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

		$query = "select count(*) from tb_check_log where check_kind= '".$atype."' and flag = 'Y' ".$que;

		$result = mysql_query($query,$connect);
		$row = mysql_fetch_array($result);
		$TotalArticle = $row[0];

		$query2 = "select * from tb_check_log where check_kind= '".$atype."' and flag = 'Y' ".$que." order by check_no desc limit ". $offset.", ".$nPageSize; ;
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
		$sqlstr = "SELECT * FROM tb_code where parent='bank3' order by code"; 
		$results = mysql_query($sqlstr) or die(mysql_error());	
		for($i=0; $i<mysql_num_rows($results); $i++) {
			$rows = mysql_fetch_array($results);
			$bnames = $rows[name];
			$bankCode[$bnames] = $rows[code];
		}
	}
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
	document.frmSearch.action="bank_auth_log.php";
	document.frmSearch.submit();
}


function goPage(i) {
	document.frmSearch.page.value = i;
	document.frmSearch.action="bank_auth_log.php";
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

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="javascript:check_data();">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="atype" value="<?echo $atype?>">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>(구) <?=($atype == "B") ? "계좌인증" : "실명인증";?> LOG 조회</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		<button onclick="location.href='log_v2.php'">가입/계좌/실명/API 로그 조회(V2)</button>&nbsp;
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
					<b>인증구분 : &nbsp;</b>
				</td>
				<td colspan="3">
					&nbsp;&nbsp;
					<input type="radio" name="atype" id="atype" value="B" <?if($atype == "B") echo "checked";?> /> 계좌인증 &nbsp;&nbsp;
					<input type="radio" name="atype" id="atype" value="M" <?if($atype == "M") echo "checked";?> /> 실명인증
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
						<OPTION VALUE="jumin1" <?if($idxfield == "jumin1") echo "selected";?>>생년월일(YYYYMMDD)</OPTION>
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
		<td>* 검색은 유사검색을 하지 않고 입력하신 값과 일치하는 데이터만 검색됩니다 (조회시 다소 시간이 걸릴 수 있습니다).</td>
		<td align="right">
			<font color="blue">총건수 : <?=$TotalArticle?></font>&nbsp;&nbsp;
		</td>
	</tr>
</table>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="16%" style="text-align:center">회원번호</TH>
	<TH width="16%" style="text-align:center">이름</TH>
	<TH width="16%" style="text-align:center">생년월일</TH>
	<TH width="16%" style="text-align:center"><? if($atype== "B"){ ?>은행명<? }else { ?>SESSION<? } ?></TH>
	<TH width="16%" style="text-align:center"><? if($atype== "B"){ ?>계좌번호<? }else { ?>REQ_SEQ<? } ?></TH>
	<TH width="20%" style="text-align:center">로그일시</TH>

</TR>     
<?

	if ($TotalArticle) {
		
		while($obj = mysql_fetch_object($result2)) {

			//회원번호 가져오기 - 시간걸림
			
			$msql = "select * from 
					(
					select 'info' as tbl, number, name, regdate from tb_userinfo where name='".$obj->name."' and concat(birth_y,birth_m,birth_d) = substring(".$obj->jumin1.",3,6) 
					union all
					select 'dup' as tbl, number, name, regdate from tb_userinfo_dup where name='".$obj->name."' and concat(birth_y,birth_m,birth_d) = substring(".$obj->jumin1.",3,6) 
					) c
					order by c.regdate limit 0, 1";
			$resultm = mysql_query($msql) or die(mysql_error());
			$rowm = mysql_fetch_array($resultm);

			

?>
<TR align="center">
	<TD height="25" align="center">
		<?
		if($rowm[number] != "") echo $rowm[number];
		else echo "-";
		if($rowm[tbl] == "dup") echo " (갱신회원)";
		?>
	</TD>
	<TD height="25" align="center"><?=$obj->name?></TD>
	<TD align="center"><?=$obj->jumin1?><!--<?=$obj->jumin2?> --></TD>
	<TD align="center"><?=$obj->data1?>
		<?
		if($atype== "B") if($bankCode[$obj->data1] != "") echo " (".$bankCode[$obj->data1].")";
		?>
	</TD>	
	<TD align="center">
		<? 
		if($atype== "B") echo decrypt($key, $iv, $obj->data2);
		else echo $obj->data2;
		?>
	</TD>
	<TD align="center"><?=$obj->chkdate?></TD>
</TR>
<?
		//	}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left" style="line-height:160%">
	    * 회원번호는 <u>이름</u>과 <u>생년월일</u>로만 조회하여 <strong>신뢰성이 떨어질 수 있습니다</strong>.<br>
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