<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	//교육일정캘린더 등록하기

	function makeSelByCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

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

	function getCodeName ($parent, $code)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='$parent' and code = '$code'"; 

		$result = mysql_query($sqlstr);
		$list = mysql_fetch_array($result);
		
		$name = $list[name];
		if ($name <> "") { 
			print($name);
		}

	}


	$mode						= str_quote_smart(trim($mode));
	$qry_str				= str_quote_smart(trim($qry_str));
	$idxfield				= str_quote_smart(trim($idxfield));
	$page						= str_quote_smart(trim($page));
	$NewsId					= str_quote_smart(trim($NewsId));

	$Year1					= str_quote_smart(trim($Year1));
	$Month1					= str_quote_smart(trim($Month1));


	if (!empty($Year1) && !empty($Month1)) {
		$que2 = " and y = '$Year1'  and mo = '$Month1' ";
	}

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and file_name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and file_path like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_education_calendar_file where seq>0 ".$que." ".$que2;
		$query2 = "select * from tb_education_calendar_file where seq>0 ".$que. " ".$que2." order by seq desc limit 0,20";

	} else {
		$query = "select count(*) from tb_education_calendar_file where seq>0 ".$que2;
		$query2 = "select * from tb_education_calendar_file where seq>0 ".$que2." order by seq desc limit 0,20";
	}

	#echo $query2;
	
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
	
	
	$sYear1 = date(Y)-1;
	$sYear2 = date(Y)+2;
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
	//document.frmSearch.id.value = id; 
	//document.frmSearch.action= "edu_cal_view.php";
	//document.frmSearch.submit();
}

function goIn() {
	var frm = document.frmSearch;
	if(frm.Year1.value=="")
	{
		alert("등록하고자 하는 년도를 선택해 주세요");
		return false;
	}
	frm.encoding="multipart/form-data";
	frm.mode.value = "add";
	frm.action= "edu_cal_file_db.php";
	frm.submit();
}

function goDel() {

	var check_count = 0;
	var total = document.frmSearch.length;
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.elements[i].checked == true) {
	    	check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("삭제하실 자료를 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.id.value = getIds();
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "edu_cal_file_db.php";
		document.frmSearch.submit();
	}
	else {
		return;
	}

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
				sValues += frmSearch.CheckItem.value;
			}
		}
	}
	sValues  +=")";
	return sValues;
}

function setBshow(iBshow, id) {
	document.frmSearch.bshow.value = iBshow;
	document.frmSearch.mode.value = "bshow";
	document.frmSearch.id.value = id; 
	document.frmSearch.action= "edu_cal_file_db.php";
	document.frmSearch.submit();
}

function goCheck() {
	document.frmSearch.page.value = 1;
	document.frmSearch.action= "edu_cal_file_list.php";
	document.frmSearch.submit();
}


function Set_Date(cName_yy, cName_mm, cYear, cMonth) {
		
		var cStart = "<?echo $sYear1?>";
		var cEnd = "<?echo $sYear2?>";
				
		Set_YY(cName_yy,cStart,cEnd,cYear);
		Set_MM(cName_mm,cMonth);
		
		//ChgDate(cName_yy,cName_mm,cName_dd,cDay);
			
}

function Set_YY(cName_yy, cStart, cEnd, cYY)	{
	with(document.frmSearch)	{
		elements[cName_yy].options[0] = new Option('전체','');
		var nCnt= 1;
		for(var i=cStart;i<=cEnd;i++)	{
			elements[cName_yy].options[nCnt] = new Option(i,i);
			if (cYY==i)
				elements[cName_yy].options[nCnt].selected = true;
			nCnt++;
		}
	}
}

function Set_MM(cName_mm,cMM)	{
	with(document.frmSearch)	{
		for(var i=0;i<12;i++)	{
			nCnt = i+1;
			if (nCnt < 10)	cMonth = "0"+nCnt;
			else		cMonth = nCnt;
			elements[cName_mm].options[i] = new Option(cMonth,cMonth);
			if (cMM == cMonth)
				elements[cName_mm].options[i].selected = true;
		}
	}
}

function ChgDate(cName_yy, cName_mm, cName_dd,cDD)	{
	with(document.frmSearch)	{
		var nCnt= 0;
		var nLastDay = 0;
		var cYear = elements[cName_yy].options[elements[cName_yy].selectedIndex].value;
		var cMonth = elements[cName_mm].options[elements[cName_mm].selectedIndex].value;
		

		if ((cMonth=="01")||(cMonth=="03")||(cMonth=="05")||(cMonth=="07")||(cMonth=="08")||(cMonth=="10")||(cMonth=="12"))
			nLastDay = 31;
		else	nLastDay = 30;

		if (cMonth=="02")	{
			if (parseFloat(cYear/4)==parseInt(cYear/4))	{
				if (parseFloat(cYear/100)==parseInt(cYear/100))
					if (parseFloat(cYear/400)==parseInt(cYear/400))
						nLastDay=29;
					else	nLastDay=28;
				else	nLastDay=29;
			}
			else	nLastDay=28;
		}

		
	}	
}
</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">
<FORM name="frmSearch" method="post" action="javascript:goCheck();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>

	<TD align="left"><B>교육일정관련파일</B></TD>

	<TD align="right" width="600" align="center" bgcolor=silver>

	<SELECT name="Year1" >
		<option value="">9999</option>
		<option value=""></option>
	</SELECT>년&nbsp;

	<SELECT name="Month1" >
		<option value="">99</option>
		<option value=""></option>
	</SELECT>월&nbsp;

	<SELECT NAME="idxfield">
		<OPTION VALUE="0">파일명</OPTION>
		<OPTION VALUE="1">파일경로</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
	<INPUT TYPE="submit" VALUE="검색">
	<INPUT TYPE="button" VALUE="등록" onClick="goIn();">
	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
<script language="javascript">
	Set_Date("Year1","Month1","<?echo $Year1?>","<?echo $Month1?>");
</script>
	</TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR align="center">
	<TH width="3%">&nbsp;</TH> 
	<TH width="10%">번 호</TH>
	<TH width="21%">파일명</TH>
	<TH width="21%">파일경로</TH>
	<TH width="10%">행사일</TH>
	<TH width="10%">등록일</TH>
	<TH width="10%">전시여부</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d", strtotime($obj->wdate));
				$edu_cal = $obj->y."-".$obj->mo;
	
?>					
<TR align="center">                    

	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->seq?>"></TD>
	<TD><?echo $obj->seq?></TD>
	<TD align="left"><?echo $obj->file_name?></TD>
	<TD><?echo $obj->file_path?></TD>
	<TD><?echo $edu_cal?></TD>
	<TD><?echo $date_s?></TD>
	<TD>
<?
	if ($obj->bshow == "0") {
		echo "<a href='javascript:setBshow(1,$obj->seq);'><img src='images/ico_show0.gif' border=0></a>";
	} else {
		echo "<a href='javascript:setBshow(0,$obj->seq);'><img src='images/ico_show1.gif' border=0></a>";
	}
?>	
	</td>

</TR>
<?
			}
		}
	}
?>
</TABLE>

<!--input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="NewsId" value="<?echo $NewsId?>">
<input type="hidden" name="bshow" value="">
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
</form-->
<TABLE>
<!--form name='frm' method='post' enctype='multipart/form-data'-->
<TR>
	<TD>&nbsp;</TD>
	<TD>&nbsp;</TD>
</TR>

<tr>
	<th>
		파일업로드 :
	</th>
	<td>
		<input type="file" name="Image" size="50" value="">
	</td>
</tr>
<TR>
	<TD></TD>
	<TD><input type="button" onClick="goIn();" value="등록" name="btn3"></TD>
</TR>

</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="NewsId" value="<?echo $NewsId?>">
<input type="hidden" name="bshow" value="">
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="del">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
<?	if ($NewsId == "A1"){?>
<script language="javascript">
	document.frmSearch.sel_goods.value = "<?echo $sel_goods?>";
</script>
<?	}?>
</html>
<?
mysql_close($connect);
?>