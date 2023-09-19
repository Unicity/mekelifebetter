<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";
	include "./inc/common_function.php";


	//logging($s_adm_id,'open new member list (user_list.php)');
	
	$page				= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));

	$toDay = date("Y-m-d");

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

	$que = " and ifnull(del_tf,'N') = 'N' ";

	$query = "select count(*) from tb_useroff_mail_list where 1 = 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	
	//logging($s_adm_id,'search user count '.$TotalArticle);
	
	$query2 = "select * from tb_useroff_mail_list where 1 = 1 ".$que." order by gno desc limit ". $offset.", ".$nPageSize;
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
.input2 { background-color:#000; color:#fff; font-size:11px; cursor:pointer; padding:5px 10px; }
</STYLE>
<script type="text/javascript" src="/manager_utf8/inc/jquery.js"></script>
<script language="javascript">
function openWin(n){
	window.open("user_off_detail.php?mno="+n, "_blank", "toolbar=no,scrollbars=auto,resizable=no,top=50,left=50,width=600,height=600");
}
function check_data(){

	for(i=0; i < document.frmSearch.r_status.length ; i++) {
		if (document.frmSearch.r_status[i].checked == true) {
			document.frmSearch.reg_status.value = document.frmSearch.r_status[i].value;
		}
	}
			
	document.frmSearch.action="user_off_list.php";
	document.frmSearch.submit();
}

function onSearch(){

	document.frmSearch.page.value="1";
	document.frmSearch.action="user_off_list.php";
	document.frmSearch.submit();
}

function excelDown(){
	location.href="user_off_mailing_excel.php";
}
function updateSendResult(no){

	if(no == ""){
		alert("발송대상이 없습니다");
	}else if(confirm("발송완료처리 하시겠습니까?")){
		$.ajax({
			type: 'post',
			url: 'user_off_mailing_update.php',
			data: {gno: no},
			success: function(msg){
				if(msg == "OK"){
					alert("업데이트되었습니다")
					location.reload();
				}else{
					alert(msg);
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) { 
				alert( textStatus + ", " + errorThrown ); 
			} 
		});		
	}
}

function goPage(n){
	if(n > 0){
		document.frmSearch.page.value =  n;
		document.frmSearch.action="user_off_list.php";
		document.frmSearch.submit();
	}
}
</script>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>회원카드 온라인 발급 메일링 리스트</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp; <span onclick="goExcelHistory('회원카드온라인발급','메일링관리','신규발송대상자엑셀다운로드')" class="input">신규발송대상자엑셀다운로드</span>
	</TD>
</TR>
</TABLE>
<br>

<table width="100%" border="0">
	<tr>
		<td width="100%" align="right">
			&nbsp; * 엑셀다운로드 후에는 메일링 리스트를 새로고침 하여 주세요 <a href="javascript:location.reload()">[새로고침]</a>
		</td>
	</tr>
</table>

<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="7%">번호</TH>	
	<TH width="18%">파일명</TH>
	<TH width="15%">시작번호</TH>
	<TH width="15%">끝번호</TH>
	<TH width="15%">회원수</TH>
	<TH width="15%">생성일자</TH>
	<TH width="15%">발송일자</TH>
</TR>     
<?
if ($TotalArticle) {
	while($obj = mysql_fetch_object($result2)) {
		?>
		<TR align="center">
			<TD height="25"><?=$NumberArticle?></TD>
			<TD><?=$obj->fname?></TD>			
			<TD><?=$obj->enum?></TD>
			<TD><?=$obj->snum?></TD>
			<TD><?=$obj->cnt?></TD>
			<TD><?=$obj->cdate?></TD>
			<TD>
				<? if($obj->senddate == null || $obj->senddate == ""){ ?>
					<span onclick="updateSendResult('<?=$obj->gno?>')" class="input2">발송완료처리</span>
				<? }else{ ?>
					<?=$obj->senddate?>
				<? } ?>
			</TD>			
		</TR>
		<?
		$NumberArticle--;
	}
}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left">
	    건수 : <?=number_format($TotalArticle)?> 건
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
<input type="hidden" name="mode" value="del">
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
<input type="hidden" name="reg_status" value="<?echo $reg_status?>">
<input type="hidden" name="member_nos" value="">
<input type="hidden" name="numbers" value="">

</form>


<? include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/excel_modal.php"; ?>

<iframe name="ifrm" id="ifrm" src="" width="0" height="0" scrolling="no" frameborder=0></iframe>

</body>
</html>

<?
mysql_close($connect);
?>