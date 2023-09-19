<?
	include "./admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$member_kind	= str_quote_smart(trim($member_kind));
	$idxfield			= str_quote_smart(trim($idxfield));
	$qry_str			= str_quote_smart(trim($qry_str));
	$page					= str_quote_smart(trim($page));
	$DateSearch		= str_quote_smart(trim($DateSearch));
	$member_kind	= str_quote_smart(trim($member_kind));
	$sort					= str_quote_smart(trim($sort));
	$order				= str_quote_smart(trim($order));

	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	
	
		$case="case substr(VolumePeriod,1,3) when 
'Jan' then '01' when 
'Feb' then '02' when 
'Mar' then '03' when 
'Apr' then '04' when 
'May' then '05' when 
'Jun' then '06' when 
'Jul' then '07' when 
'Aug' then '08' when 
'Sep' then '09' when 
'Oct' then '10' when 
'Nov' then '11' when 
'Dec' then '12' 
else '13' end as tt";

$casee="case substr(VolumePeriod,1,3) when 
'Jan' then '01' when 
'Feb' then '02' when 
'Mar' then '03' when 
'Apr' then '04' when 
'May' then '05' when 
'Jun' then '06' when 
'Jul' then '07' when 
'Aug' then '08' when 
'Sep' then '09' when 
'Oct' then '10' when 
'Nov' then '11' when 
'Dec' then '12' 
else '13' end";

		$case_ye="case substr(VolumePeriod,5,2) when '09' then '2009' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' else '2009' end as tty";

		$case_yee="case substr(VolumePeriod,5,2) when '09' then '2009' when '10' then '2010' when '11' then '2011' when '12' then '2012' when '13' then '2013' when '14' then '2014' when '15' then '2015' else '2009' end";
	
		

	if($DateSearch=="Y"){
		$search_s_date=$sort_s_date_y.$sort_s_date_m;
		$search_e_date=$sort_e_date_y.$sort_e_date_m;
		$search_date=" and ((CONCAT(".$case_yee.",".$casee.")>='$search_s_date') and (CONCAT(".$case_yee.",".$casee.")<='$search_e_date'))";
	}else{
		$search_date="";
	}


	if (!empty($qry_str)) {
		if ($idxfield == "0") {
			$que = " and AA.ID = '$qry_str' ";
		} else if ($idxfield == "1") {
			$que = " and AA.DistributorName = '$qry_str' ";
		}
	}else{
		$que ="";
	}


	if (!empty($qry_str)) {
		if ($idxfield == "0") {
			$que2 = " and ID = '$qry_str' ";
		} else if ($idxfield == "1") {
			$que2 = " and DistributorName = '$qry_str' ";
		}
	}else{
		$que2 ="";
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



		$query = "SELECT count(AA.ID) FROM (SELECT JU_NO, ID, DistributorName, VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport where 1=1 ".$que2." UNION SELECT '' as JU_NO, ID,DistributorName,VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport_MBL where 1=1 ".$que2." ) AA where 1 ".$que.$search_date;

		$query2 = "SELECT AA.JU_NO, AA.ID, AA.DistributorName,AA.VolumePeriod, AA.tty, AA.tt FROM (SELECT JU_NO, ID, DistributorName, VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport where 1=1 ".$que2."  UNION SELECT '' as JU_NO, ID,DistributorName,VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport_MBL where 1=1 ".$que2." ) AA where 1 ".$que.$search_date." order by AA.tty ASC,AA.tt asc limit ". $offset.", ".$nPageSize; ;;

/*	} else {
		$query = "SELECT count(AA.ID) FROM (SELECT JU_NO, ID, DistributorName, VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport UNION SELECT '' as JU_NO, ID, DistributorName,VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport_MBL) AA where 1 ".$search_date;
	
		$query2 = "SELECT AA.JU_NO, AA.ID, AA.DistributorName,AA.VolumePeriod, AA.tty, AA.tt FROM (SELECT JU_NO, ID, DistributorName, VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport UNION SELECT '' as JU_NO, ID,DistributorName,VolumePeriod,".$case.",".$case_ye." FROM tb_Activityreport_MBL) AA order by AA.tty ASC,AA.tt asc";
	}*/

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

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
<script language="javascript">

function check_data(){
	
	document.frmSearch.action="new_activity_list.php";
	document.frmSearch.submit();
}

function onSearch(){
	document.frmSearch.page.value="1";
	document.frmSearch.action="new_activity_list_dev.php";
	document.frmSearch.submit();
}

function goSort() {
	
	document.frmSearch.sort.value = sort;
	document.frmSearch.submit();

}

function goPage(i) {
	document.frmSearch.page.value = i;
	document.frmSearch.submit();
}


function report_form2(n,m){
	window.open('report_form2.php?id='+n+'&VolumePeriod='+m,'form2','width=657,height=800,scrollbars=yes');
}

function report_form1(n,jumin,VolumePeriod){
	ff=document.frmSearch;
	s_date=ff.s_date.value;
	e_date=ff.e_date.value;

	window.open('report_form1.php?id='+n+'&s_date='+s_date+'&e_date='+e_date+"&VolumePeriod="+VolumePeriod,'form1','');
}

function report_form3(n,jumin,VolumePeriod){
	ff=document.frmSearch;
	s_date=ff.s_date.value;
	e_date=ff.e_date.value;
	window.open('report_form3.php?id='+n+'&s_date='+s_date+'&e_date='+e_date+'&JU_NO='+jumin+"&VolumePeriod="+VolumePeriod,'form3','');
}

function goIn() {
	document.frmSearch.action= "new_member_activity_file.php";
	document.frmSearch.submit();
}

function date_value(){
	ff=document.frmSearch;
	s_d=ff.s_date_y.value+"-"+ff.s_date_m.value;
	e_d=ff.e_date_y.value+"-"+ff.e_date_m.value;
	if(s_d > e_d){
		alert('시작날짜가 종료날짜 보다 이전이면 검색이 안됩니다.');
		return;
	}
	ff.s_date.value=s_d+"-01";
	ff.e_date.value=e_d+"-31";
}

function onSearchDate(){
	document.frmSearch.DateSearch.value="Y";
	document.frmSearch.submit();
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

<?
	$y=date("Y");
	$m=date("m");
	$ss_date=$y."-".$m."-01";
	$ee_date=$y."-".$m."-31";
?>
<FORM name="frmSearch" method="post" ction="javascript:check_data();">
<input type="hidden" name="s_date" value="<?=$ss_date?>">
<input type="hidden" name="e_date" value="<?=$ee_date?>">
<input type="hidden" name="DateSearch" value="<?=$DateSearch?>">

<TABLE cellspacing="0" cellpadding="10" border="0" width="100%">
	<TR>
		<TD align="left" style="FONT-SIZE: 20px;"><B>소득증빙자료 관리</B></TD>
		<TD align="right" width="80%" align="center">

		기간별 검색 : 
			
		<select name="sort_s_date_y">
			<?for($i=$y;$i>2005;$i--){?>
			<option value="<?=$i?>" <?if($sort_s_date_y==$i){echo "selected";}?>><?=$i?></option>
			<?}?>
		</select> 년 
		<select name="sort_s_date_m" onchange="date_value();">
			<?for($i=12;$i>0;$i--){
				if(strlen($i)==1){
					$ii="0".$i;
				}else{
					$ii=$i;
				}
				?>
			<option value="<?=$ii?>" <?if($sort_s_date_m==$ii){echo "selected";}?>><?=$ii?></option>
			<?}?>
		</select> 월 ~ 
		<select name="sort_e_date_y" onchange="date_value();">
			<?for($i=$y;$i>2005;$i--){?>
			<option value="<?=$i?>" <?if($sort_e_date_y==$i){echo "selected";}?>><?=$i?></option>
			<?}?>
		</select> 년 
		<select name="sort_e_date_m" onchange="date_value();">
			<?for($i=12;$i>0;$i--){
				if(strlen($i)==1){
					$ii="0".$i;
				}else{
					$ii=$i;
				}
					?>
			<option value="<?=$ii?>" <?if($sort_e_date_m==$ii){echo "selected";}?>><?=$ii?></option>
			<?}?>
		</select> 월 <INPUT TYPE="button" VALUE="검색" onClick="onSearchDate();">
&nbsp;&nbsp;
		<SELECT NAME="idxfield">
			<OPTION VALUE="0" <?if($idxfield == "0") echo "selected";?>>FO</OPTION>
			<OPTION VALUE="1" <?if($idxfield == "1") echo "selected";?>>회원이름</OPTION>
		</SELECT>
		<INPUT TYPE="text" NAME="qry_str" VALUE="<?echo $qry_str?>">&nbsp;
		<INPUT TYPE="button" VALUE="검색" onClick="onSearch();">
		<INPUT TYPE="button" VALUE="등록" onClick="goIn();">	
		</TD>
	</TR>
</TABLE>
<TABLE cellspacing="0" cellpadding="0" class="LIST" border="0">
	<TR>
		<td>영수증 출력 기간(지급일 기준) : 
			
			<select name="s_date_y" onchange="date_value();">
				<?for($i=$y;$i>2005;$i--){?>
				<option value="<?=$i?>" <?if($y==$i){echo "selected";}?>><?=$i?></option>
				<?}?>
			</select> 년 
			<select name="s_date_m" onchange="date_value();">
				<?for($i=12;$i>0;$i--){
					if(strlen($i)==1){
						$ii="0".$i;
					}else{
						$ii=$i;
					}
					?>
				<option value="<?=$ii?>" <?if($m==$ii){echo "selected";}?>><?=$ii?></option>
				<?}?>
			</select> 월 ~ 
			<select name="e_date_y" onchange="date_value();">
				<?for($i=$y;$i>2005;$i--){?>
				<option value="<?=$i?>" <?if($y==$i){echo "selected";}?>><?=$i?></option>
				<?}?>
			</select> 년 
			<select name="e_date_m" onchange="date_value();">
				<?for($i=12;$i>0;$i--){
					if(strlen($i)==1){
						$ii="0".$i;
					}else{
						$ii=$i;
					}
						?>
				<option value="<?=$ii?>" <?if($m==$ii){echo "selected";}?>><?=$ii?></option>
				<?}?>
			</select> 월
		</td>
	</tr>
</table>
<br>


<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
	<TR>
		<TH width="10%" align="center">FO</TH>
		<TH width="15%" align="center">회원명</TH>
		<TH width="20%" align="center">주민등록번호</TH>
		<TH width="20%" align="center">Volume Period</TH>
		<TH width="15%">&nbsp;</TH>
		<TH width="15%">&nbsp;</TH>
		<TH width="15%">&nbsp;</TH>
	</TR>
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		while($obj = mysql_fetch_object($result2)) {
?>
	<TR align="center">
		<TD align="center"><?echo $obj->ID?></TD>
		<TD><?echo $obj->DistributorName?></TD>
		<TD><?echo decrypt($key, $iv, $obj->JU_NO)?></TD>
		<TD><?echo $obj->VolumePeriod?></TD>
		<TD><INPUT TYPE="button" VALUE="Bonus Summary" onClick="report_form2('<?echo $obj->ID?>','<?=$obj->VolumePeriod?>');"></TD>
		<TD><INPUT TYPE="button" VALUE="원천징수영수증" onClick="report_form3('<?echo $obj->ID?>','<?=$obj->JU_NO?>','<?=$obj->VolumePeriod?>');"></TD>
		<TD><INPUT TYPE="button" VALUE="기타소득영수증" onClick="report_form1('<?echo $obj->ID?>','<?=$obj->JU_NO?>','<?=$obj->VolumePeriod?>');"></TD>
		
	</TR>
<?

		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<!--TD align="left">
	    [<b>등록된 회원 수 : <?echo $TotalArticle?> 명 
	</TD-->
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
<input type="hidden" name="mode" value="del">
<input type="hidden" name="member_id" value="">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="member_kind" value="<?echo $member_kind?>">
<input type="hidden" name="sort" value="<?echo $sort?>">
<input type="hidden" name="order" value="<?echo $order?>">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>