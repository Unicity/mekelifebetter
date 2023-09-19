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
	$sort					=	str_quote_smart(trim($sort)); 
	$order				=	str_quote_smart(trim($order)); 
	$idxfield			=	str_quote_smart(trim($idxfield)); 
	$qry_str			=	str_quote_smart(trim($qry_str)); 
	$sel_goods		=	str_quote_smart(trim($sel_goods)); 

	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	if (empty($sort)) {
		$sort = "RegDate";
	}

	if (empty($order)) {
		$order = "desc";
	}

	if (!empty($sel_goods)) {
		$que2 = "and g.b_cate = '$sel_goods' ";
	}


	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and g.goods_name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and g.goods_no like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = " and g.brand like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_goods g, tb_code c where g.autoship = 'Y' and g.b_cate = c.code and c.parent = 'goods' ".$que." ".$que2;
		$query2 = "select * from tb_goods g, tb_code c where g.autoship = 'Y' and g.b_cate = c.code and c.parent = 'goods' ".$que." ".$que2." order by auto_order asc ";

	} else {
		$query = "select count(*) from tb_goods g, tb_code c where g.autoship = 'Y' and g.b_cate = c.code and c.parent = 'goods' ".$que2 ;
		$query2 = "select * from tb_goods g, tb_code c where g.autoship = 'Y' and g.b_cate = c.code and c.parent = 'goods' ".$que2." order by auto_order asc ";
//		echo $query2;
	}
	
	#echo $query2;
	
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$ListArticle = 10000;
	$PageScale = 1000;
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

function init(){
<?	if (!empty($qry_str)) {  ?>
		document.frmSearch.qry_str.value="<?echo $qry_str ?>";
		document.frmSearch.idxfield.options[<?echo $idxfield ?>].selected = true;
<?	} ?>

}

function onView(id) {
	document.location = "goods_view.php?goods_id="+id+"&page=<?echo $page?>&sort=<?echo $sort?>&order=<?echo $order?>&idxfield=<?echo $idxfield?>&qry_str=<?echo $qry_str?>&sel_goods=<?echo $sel_goods?>";
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
				sValues +="^"+frmSearch.CheckItem.value+"^";
			}
		}
	}
	sValues  +=")";
	return sValues;
}


function check_data(){
	
	for(i=0; i < document.frmSearch.rsort.length ; i++) {
		if (document.frmSearch.rsort[i].checked == true) {
			document.frmSearch.sort.value = document.frmSearch.rsort[i].value;
		}
	}

	for(i=0; i < document.frmSearch.rorder.length ; i++) {
		if (document.frmSearch.rorder[i].checked == true) {
			document.frmSearch.order.value = document.frmSearch.rorder[i].value;
		}
	}

	document.location = "autoship_goods_list.php?page=1&sort="+document.frmSearch.sort.value+"&order="+document.frmSearch.order.value+"&idxfield="+document.frmSearch.idxfield.value+"&qry_str="+document.frmSearch.qry_str.value;

}

function setAutoship(iAutoship, id) {
	document.frmSearch.autoship.value = iAutoship;
	document.frmSearch.mode.value = "autoship";
	document.frmSearch.goods_id.value = id; 
	document.frmSearch.action= "autoship_goods_db.php";
	document.frmSearch.submit();
}

function goCheck() {
	document.frmSearch.page.value = 1;
	document.frmSearch.action= "autoship_goods_list.php";
	document.frmSearch.submit();
}

function order_changer() {
	document.frmSearch.target = "frhidden";
	document.frmSearch.goods_id.value = get_goods_ids();
	document.frmSearch.odrs.value = get_odr();
		
	document.frmSearch.mode.value = "odr";
	document.frmSearch.action= "autoship_goods_db.php";
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
	<TD align="left"><B>오토쉽 제품 순서 관리</B></TD>
	<TD align="right" width="700" align="center" bgcolor=silver>
	<SELECT NAME="sel_goods">
		<OPTION VALUE="">제품군전체</OPTION>
<?makeSelByCode("goods")?>
		<OPTION VALUE="1">브랜드</OPTION>
	</SELECT>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">상품명</OPTION>
		<OPTION VALUE="1">상품번호</OPTION>
		<OPTION VALUE="2">브랜드</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
	<INPUT TYPE="submit" VALUE="검색">
	<INPUT TYPE="button" VALUE="나열순서변경" onClick="order_changer();">
	</TD>
</TR>
</TABLE>
<br>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%">&nbsp;</TH> 
	<TH width="10%">번 호</TH>
	<TH width="15%">제품군</TH>
	<TH width="32%">상품명</TH>
	<TH width="10%">등록일</TH>
	<TH width="8%">조회수</TH>
	<TH width="8%">나열순</TH>
	<TH width="8%">CV</TH>
	<TH width="9%">autoship 여부</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d", strtotime($obj->RegDate));
	
?>					
<TR align="center">                    

	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->goods_id?>"></TD>
	<TD><?echo $obj->goods_no?></TD>
	<TD align="left"><?echo $obj->name?></TD>
	<TD align="left"><?echo $obj->goods_name?></TD>
	<TD><?echo $date_s?></TD>
	<TD><?echo $obj->cnt?></TD>
	<TD><input type="text" name="odr" value="<?echo $obj->auto_order?>" size="3" style="text-align:right"></TD>
	
	<TD><?echo $obj->auto_cv?></td>
	<TD>
<?
	if ($obj->autoship == "N") {
		echo "<a href=\"javascript:setAutoship('Y',$obj->goods_id);\"><img src='images/ico_show0.gif' border=0></a>";
	} else {
		echo "<a href=\"javascript:setAutoship('N',$obj->goods_id);\"><img src='images/ico_show1.gif' border=0></a>";
	}
?>	
	</td>

</TR>
	<input type="hidden" name="goods_ids" value="<?echo $obj->goods_id?>">
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
			echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str&sel_goods=$sel_goods&sort=$sort&order=$order'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str&sel_goods=$sel_goods&sort=$sort&order=$order'>이전".$PageScale."개</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str&sel_goods=$sel_goods&sort=$sort&order=$order'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str&sel_goods=$sel_goods&sort=$sort&order=$order'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str&sel_goods=$sel_goods&sort=$sort&order=$order'>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="autoship" value="">
<input type="hidden" name="bshow" value="">
<input type="hidden" name="goods_id" value="<?echo $goods_id?>">

<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="mode" value="del">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
<input type="hidden" name="odrs" value="">
</form>
<script language="javascript">
	document.frmSearch.sel_goods.value = "<?echo $sel_goods?>";
</script>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>