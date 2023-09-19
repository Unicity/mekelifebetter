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
	$r_status				= str_quote_smart(trim($r_status));
	$r_memberkind		= str_quote_smart(trim($r_memberkind));
	
	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));

	$toDay = date("Y-m-d");

	if ($r_status != ""){
		$que = $que." and email_send_yn = '$r_status' ";		
	}



	if ($qry_str != "") {

		if($idxfield == "addr"){
			$que = $que." and concat(addr,' ',addr2) like '%$qry_str%' ";

		}else if($idxfield == "email_send_date" || $idxfield = "write_date"){
			
			$que = $que." and replace(substring(".$idxfield.",1,10),'-','') = '$qry_str' ";

		}else{
			$que = $que." and ".$idxfield." like '%$qry_str%' ";
		}
		//logging($s_adm_id,'search user '.$que);
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

	if($qry_str != ""){

		$query = "select count(*) from tb_useroff where 1 = 1 ".$que;
		$result = mysql_query($query,$connect);
		$row = mysql_fetch_array($result);
		$TotalArticle = $row[0];
		
		//logging($s_adm_id,'search user count '.$TotalArticle);
		
		$query2 = "select * from tb_useroff where 1 = 1 ".$que." order by mno desc limit ". $offset.", ".$nPageSize;
		$result2 = mysql_query($query2);
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
			
	document.frmSearch.action="user_off_list.php";
	document.frmSearch.submit();
}


function onSearch(){

	document.frmSearch.page.value="1";
	document.frmSearch.action="user_off_list.php";
	document.frmSearch.submit();
}

function deleteChk(){
	var cnt = $("input:checkbox[id=chk]:checked").length;
	
	if(cnt < 1){
		alert("선택된 대상이 없습니다");
	}else if(confirm("선택대상을 삭제하시겠습니까?")){
		
		document.frmSearch.mode.value="delete";
		var datas = $("#frmSearch").serialize();

		$.ajax({
			type: 'post',
			url: 'https://www.makelifebetter.co.kr/manager_utf8/user_off_del.php',
			data: datas,
			success: function(msg){
				if(msg == "OK"){
					alert("삭제되었습니다");
					location.reload();
				}else{
					alert(msg);
				}
			},
			error: function( jqXHR, textStatus, errorThrown ) { 
				alert( textStatus + ", " + errorThrown ); 
			} 
		});
		document.frmSearch.mode.value="";
	}
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
		document.frmSearch.action="user_off_list.php";
		document.frmSearch.submit();
	}
}
</script>
</head>
<BODY bgcolor="#FFFFFF">

<?php include "common_load.php" ?>


<FORM name="frmSearch" id="frmSearch"  method="post" action="javascript:check_data();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>회원카드 온라인 발급 관리</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
		&nbsp; <span onclick="location.href='user_off_input.php'" class="input">신규등록</span>
	</TD>
</TR>
<tr>
	<td colspan="2" align="right" style="font-size:11px; font-weight:normal">* 2019.06.11자로 회원등록시 이메일 중복체크를 하지 않습니다</td>
</tr>
</TABLE>

<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
		<table bgcolor="#EEEEEE" width="100%" cellpadding='0' cellspacing='0' border='1' bordercolorlight='#FFFFFF' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
			<tr>
				<td align="right" width="100" style="padding:7px 0px">
					<b>발송여부 : &nbsp;</b>
				</td>
				<td  style="padding:7px">
					<input type="radio" name="r_status" value="" <? if ($r_status == "") echo "checked" ?>  onClick="check_data();"> 전체 &nbsp;
					<input type="radio" name="r_status" value="Y" <? if ($r_status == "Y") echo "checked" ?>  onClick="check_data();"> 발송 &nbsp;
					<input type="radio" name="r_status" value="N" <? if ($r_status == "N") echo "checked" ?>  onClick="check_data();"> 미발송 &nbsp;
				</td>
			</tr>
			<!-- <tr>
				<td align="right" width="100" style="padding:7px 0px">
					<b>등 록 일 : &nbsp;</b>
				</td>
				<td  style="padding:7px">
					<input type="text" name="from_date" value="<?echo $from_date?>" size="11" maxlength="10"> ~
					<input type="text" name="to_date" value="<?echo $to_date?>" size="11" maxlength="10"> [2019-01-01의 형태로 입력하세요.]
				</td>
			</tr> -->
			<tr>
				<td align="right" style="padding:7px 0px">
					<b>검 &nbsp;&nbsp;&nbsp; 색 : &nbsp;</b>
				</td>
				<td  style="padding:7px">
					<SELECT NAME="idxfield">
						<OPTION VALUE="name" <?if($idxfield == "name") echo "selected";?>>성명</OPTION>
						<OPTION VALUE="birth" <?if($idxfield == "birth") echo "selected";?>>생년월일</OPTION>
						<OPTION VALUE="reg_num" <?if($idxfield == "reg_num") echo "selected";?>>등록번호</OPTION>
						<OPTION VALUE="reg_date" <?if($idxfield == "reg_date") echo "selected";?>>등록일자</OPTION>
						<OPTION VALUE="email" <?if($idxfield == "email") echo "selected";?>>이메일</OPTION>
						<OPTION VALUE="addr" <?if($idxfield == "addr") echo "selected";?>>주소</OPTION>
						<OPTION VALUE="email_send_date" <?if($idxfield == "email_send_date") echo "selected";?>>발송일자</OPTION>
						<OPTION VALUE="write_date" <?if($idxfield == "write_date") echo "selected";?>>업로드일자</OPTION>
					</SELECT>
					<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
					<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">&nbsp;
					* 생년월일, 등록일자, 발송일자, 업로드일자 검색시 숫자만 입력하세요(yyyymmdd)
				</td>
			</tr>
			
		</table>
	</td>
</tr>
</table>

<table width="100%" border="0">
	<tr>
		<td width="100%">
			<input type="button" value="선택삭제" onclick="deleteChk()" />
		</td>
	</tr>
</table>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%" style="text-align:center"><input type="checkbox" name="allcheck" id="allcheck" value="Y" title="전체선택" /></TH>	
	<TH width="8%" style="text-align:center">이름</TH>	
	<TH width="7%" style="text-align:center">생년월일</TH>
	<TH width="7%" style="text-align:center">등록번호</TH>
	<TH width="7%" style="text-align:center">등록일자</TH>
	<TH width="16%" style="text-align:center">주소1</TH>
	<TH width="8%" style="text-align:center">주소2</TH>
	<TH width="11%" style="text-align:center">이메일</TH>
	<TH width="6%" style="text-align:center">Email_Consent</TH>
	<TH width="6%" style="text-align:center">Birth_Check</TH>
	<TH width="7%" style="text-align:center">발송여부</TH>
	<TH width="7%" style="text-align:center">발송일자</TH>
	<TH width="7%" style="text-align:center">업로드일자</TH>
</TR>     
<?
if ($TotalArticle) {
	
	while($obj = mysql_fetch_object($result2)) {
		$birth = substr($obj->birth,0,4)."-".substr($obj->birth,4,2)."-".substr($obj->birth,6,2);
		$reg_date = substr($obj->reg_date,0,4)."-".substr($obj->reg_date,4,2)."-".substr($obj->reg_date,6,2);
		
		?>
		<TR align="center">
			<TD><input type="checkbox" name="chk[]" id="chk" class="check" value="<?=$obj->mno?>" /></TD>
			<TD height="25"><A HREF="javascript:openWin('<?=$obj->mno?>')"><?=masking_name($obj->name)?></A></TD>
			<TD><?=masking_birth($birth)?></TD>
			<TD><?=$obj->reg_num?></TD>
			<TD><?=$reg_date?></TD>
			<TD align="left"><?=masking_addr($obj->addr)?></TD>
			<TD align="left"><?=masking_addr($obj->addr2)?></TD>
			<TD><?=masking_email($obj->email)?></TD>
			<TD><?=$obj->email_yn?></TD>
			<TD><?=$obj->birth_chk?></TD>
			<TD>
				<?=$obj->email_send_yn?>
				<? if($obj->email_error != ""){ echo "(".$obj->email_error.")"; } ?>
			</TD>
			<TD><?=substr($obj->email_send_date,0,10)?></TD>
			<TD><?=substr($obj->write_date,0,10)?></TD>
		</TR>
		<?
	}
}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="left">
	    조회 회원 수 : <?=number_format($TotalArticle)?> 명
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