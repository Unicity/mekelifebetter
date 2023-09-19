<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	function makeSelByCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by id"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[code]' style='color:352000'>$row[name]</option>");

			}
		}
	}

	$page					=	str_quote_smart(trim($page)); 

	$sel_req_state	=	str_quote_smart(trim($sel_req_state)); 
	$sel_pay_type		=	str_quote_smart(trim($sel_pay_type)); 
	$sel_pay_date		=	str_quote_smart(trim($sel_pay_date)); 
	$qry_str				=	str_quote_smart(trim($qry_str)); 

	if ($sel_req_state <> "") {
		$query_con = $query_con." AND req_state = '$sel_req_state' ";
	}

	if ($sel_pay_type <> "") {
		$query_con = $query_con." AND pay_type = '$sel_pay_type' ";
	}

	if ($sel_pay_date <> "") {
		$query_con = $query_con." AND pay_date = '$sel_pay_date' ";
	}

	if ($qry_str <> "") {
		$query_con = $query_con." AND ".$idxfield." like '%".$qry_str."%' ";
	}

	$query = "select count(*) from tb_autoship_req where 1 = 1 ".$query_con;
	$query2 = "select * from tb_autoship_req where 1 = 1 ".$query_con." order by seq desc ";
	
	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 10;
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
<title><?echo $g_site_title?></title>
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

function js_view(seq) {
	document.location = "autoship_req_view.php?seq="+seq+"&page=<?echo $page?>&sel_req_state=<?echo $sel_req_state?>&sel_pay_type=<?echo $sel_pay_type?>&sel_pay_date=<?echo $sel_pay_date?>&idxfield=<?echo $idxfield?>&qry_str=<?echo $qry_str?>";
}


function goCheck() {
	document.frmSearch.page.value = 1;
	document.frmSearch.action= "autoship_req_list.php";
	document.frmSearch.submit();
}

function get_odr(){
	var sValues = "";
	if(frmSearch.odr != null){
		if(frmSearch.odr.length != null){
			for(i=0; i<frmSearch.odr.length; i++){
				if(sValues != ""){
					sValues += "|";
				}
				sValues += frmSearch.odr[i].value;
			}
		}else{
			sValues += frmSearch.odr.value;
		}
	}
	return sValues;
}

function get_goods_ids(){

	var sValues = "";
	if(frmSearch.goods_ids != null){
		if(frmSearch.goods_ids.length != null){
			for(i=0; i < frmSearch.goods_ids.length; i++){
				if(sValues != ""){
					sValues += "|";
				}
				sValues += frmSearch.goods_ids[i].value;
			}
		}else{
			sValues += frmSearch.goods_ids.value;
		}
	}
	
	return sValues;

}


</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="javascript:goCheck();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>오토쉽 신청 관리</B></TD>
	<TD align="right" width="700" align="center" bgcolor=silver>
	<SELECT NAME="sel_req_state">
		<OPTION VALUE="">신청구분전체</OPTION>
		<OPTION VALUE="1" <? if ($sel_req_state== "1") echo "selected" ?>>신청</OPTION>
		<OPTION VALUE="2" <? if ($sel_req_state== "2") echo "selected" ?>>처리완료</OPTION>
		<OPTION VALUE="3" <? if ($sel_req_state== "3") echo "selected" ?>>변경신청</OPTION>
		<OPTION VALUE="4" <? if ($sel_req_state== "4") echo "selected" ?>>철회신청</OPTION>
		<OPTION VALUE="5" <? if ($sel_req_state== "5") echo "selected" ?>>철회완료</OPTION>
		<OPTION VALUE="0" <? if ($sel_req_state== "0") echo "selected" ?>>등록전취소</OPTION>
	</SELECT>
	<SELECT NAME="sel_pay_type">
		<OPTION VALUE="">결제구분전체</OPTION>
		<OPTION VALUE="bank" <? if ($sel_pay_type== "bank") echo "selected" ?>>계좌이체</OPTION>
		<OPTION VALUE="card" <? if ($sel_pay_type== "card") echo "selected" ?>>신용카드</OPTION>
	</SELECT>
	<SELECT NAME="sel_pay_date">
		<OPTION VALUE="">결제일 전체</OPTION>
		<OPTION VALUE="5" <? if ($sel_pay_date== "5") echo "selected" ?>>5일</OPTION>
		<OPTION VALUE="10" <? if ($sel_pay_date== "10") echo "selected" ?>>10일</OPTION>
		<OPTION VALUE="20" <? if ($sel_pay_date== "20") echo "selected" ?>>20일</OPTION>
	</SELECT>
	<SELECT NAME="idxfield">
		<OPTION VALUE="mem_number" <? if ($idxfield== "mem_number") echo "selected" ?>>회원번호</OPTION>
		<OPTION VALUE="mem_name" <? if ($idxfield== "mem_name") echo "selected" ?>>회원명</OPTION>
		<OPTION VALUE="rec_name" <? if ($idxfield== "rec_name") echo "selected" ?>>수령인</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="<?=$qry_str?>">&nbsp;
	<INPUT TYPE="submit" VALUE="검색">
	</TD>
</TR>
</TABLE>
<br>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="8%">회원번호</TH>
	<TH width="8%">오토쉽번호</TH>
	<TH width="8%">회원명</TH>
	<TH width="8%">수령인</TH>
	<TH width="10%">수령인연락처</TH>
	<TH width="8%">결제방법</TH>
	<TH width="8%">결제일</TH>
	<TH width="8%">CV</TH>
	<TH width="8%">가격</TH>
	<TH width="10%">상태</TH>
	<TH width="8%">신청일</TH>
	<TH width="8%">처리일</TH>
</TR>
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s				= date("Y-m-d", strtotime($obj->regdate));
				
				if ($obj->upddate <> "")
					$upddate			= date("Y-m-d", strtotime($obj->upddate));

				if ($obj->candate <> "")
					$candate			= date("Y-m-d", strtotime($obj->candate));

				$auto_no			= $obj->auto_no;
				$pay_type			= $obj->pay_type;
				$req_state		= $obj->req_state;
				$upd_state		= $obj->upd_state;
				$cancel_state	= $obj->cancel_state;

				if ($pay_type == "bank") $str_pay_type = "계좌이체";
				if ($pay_type == "card") $str_pay_type = "신용카드";
				
				if ($req_state == 1) {
					$str_req_state = "신청";
				}

?>
<TR align="center" style="height:25px">
	<TD><a href="javascript:js_view('<?echo $obj->seq?>');"><?echo $obj->mem_number?></a></TD>
	<TD><?echo $obj->auto_no?></TD>
	<TD><a href="javascript:js_view('<?echo $obj->seq?>');"><?echo $obj->mem_name?></a></TD>
	<TD><?echo $obj->rec_name?></TD>
	<TD><?echo $obj->rec_tel?></TD>
	<TD><?echo $str_pay_type?></TD>
	<TD><?echo $obj->pay_date?> 일</TD>
	<TD style="text-align:right"><?echo number_format($obj->auto_total_cv)?>&nbsp;</TD>
	<TD style="text-align:right"><?echo number_format($obj->auto_total_price)?>&nbsp;</TD>
	<TD>
		<?
			if ($req_state == "0") echo "<b><font color='orange'>등록전취소</font></b>";
			if ($req_state == "1") echo "<b><font color='red'>신청</font></b>";
			if ($req_state == "2") echo "<b><font color='navy'>처리완료</font></b>";
			if ($req_state == "3") echo "<b><font color='red'>변경신청</font></b>";
			if ($req_state == "4") echo "<b><font color='red'>철회신청</font></b>";
			if ($req_state == "5") echo "<b><font color='red'>철회완료</font></b>";
		?>
	</td>
	<TD><?echo $date_s?></td>
	<TD><?echo $upddate?></td>
</TR>
<?
		
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center">
<?

$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str&sel_req_state=$sel_req_state&sel_pay_type=$sel_pay_type&sel_pay_date=$sel_pay_date'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str&sel_req_state=$sel_req_state&sel_pay_type=$sel_pay_type&sel_pay_date=$sel_pay_date'>이전".$PageScale."개</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str&sel_req_state=$sel_req_state&sel_pay_type=$sel_pay_type&sel_pay_date=$sel_pay_date'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str&sel_req_state=$sel_req_state&sel_pay_type=$sel_pay_type&sel_pay_date=$sel_pay_date'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str&sel_req_state=$sel_req_state&sel_pay_type=$sel_pay_type&sel_pay_date=$sel_pay_date'>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>

<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="mode" value="del">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>