<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: sms_list.php
	// 	Description : sms 리스트 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////


	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";

	logging($s_adm_id,'open sms list (sms_list.php)');

	$mode					= str_quote_smart(trim($mode));
	$seq_no				= str_quote_smart(trim($seq_no));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));

	if ($mode == "SEND") {
		//echo "보내기";
		//echo $seq_no."<br>";

		$query = "select contents, callback from tb_sms_master where seq_no = '$seq_no' ";
		
		//echo $query."<br>";

		$result = mysql_query($query);
		$list = mysql_fetch_array($result);

		$contents		= $list[contents];
		$callback		= $list[callback];

		$callback = str_replace("-","",$callback);

		$query = "select ba_number, mem_nm, htel from tb_sms_mem where seq_no = '$seq_no' ";
		
		//echo $title."<br>";
		//echo $contents."<br>";
		//echo $callback."<br>";
		
		//echo $query."<br>";

		$result2 = mysql_query($query);
		$total  = mysql_affected_rows();


		if ($total > 0) {
			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result2,$i);
				$row	= mysql_fetch_array($result2);
				
				$ba_number		= Trim($row["ba_number"]);
				$mem_nm		= Trim($row["mem_nm"]);
				$htel		= Trim($row["htel"]);

				$contents = str_replace("##NUMBER",$ba_number, $contents);
				$contents = str_replace("##NAME",$mem_nm, $contents);

				$query = "Insert into sms_msg (compkey, id, phone, callback, status, wrtdate, reqdate, msg, etc1, etc2) values
									('$seq_no','tb_sms_mem','$htel', '$callback', '0', sysdate(), sysdate(), '$contents','MEM','관리자') ";
				mysql_query($query) or die(mysql_error());

				$query = "Update tb_sms_master set send_state = '1', send_adm = '".session_is_registered("s_adm_id")."',	send_date = now()  where seq_no = '$seq_no' ";
				mysql_query($query) or die(mysql_error());


			}
			logging($s_adm_id, 'sending sms : '.$seq_no);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('발송되었습니다.');
				document.location.replace('sms_list.php');
				</script>";
		}else{

			logging($s_adm_id, 'sending sms fail :'.$seq_no);

			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
			echo "<script language=\"javascript\">\n
				alert('발송 대상을 조회할 수 없습니다.');
				history.back();
				</script>";
		}
		exit;
	}

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and title like '%$qry_str%' ";
		} else {
			$que = " and contents like '%$qry_str%' ";
		}
		logging($s_adm_id,'search sms '.$que);
		$query = "select count(*) from tb_sms_master where del_tf='N' ".$que;
		$query2 = "select * from tb_sms_master where del_tf='N' ".$que. " order by reg_date desc";

	} else {
		$query = "select count(*) from tb_sms_master where del_tf='N'";
		$query2 = "select * from tb_sms_master where del_tf='N' order by reg_date desc";
	}
	
	//echo $query."<br>";

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	logging($s_adm_id,'search sms count '.$TotalArticle);
	//echo $TotalArticle."<br>";

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
<title><?echo $g_site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
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

function re_load(){
	document.frmSearch.submit();
}


function init(){
<?	if (!empty($qry_str)) {  ?>
		document.frmSearch.qry_str.value="<?echo $qry_str ?>";
		document.frmSearch.idxfield.options[<?echo $idxfield ?>].selected = true;
<?	} ?>

}

function onView(id) {
	document.frmSearch.seq_no.value = id; 
	document.frmSearch.action= "sms_input.php";
	document.frmSearch.submit();
}

function goIn() {
	document.frmSearch.action= "sms_input.php";
	document.frmSearch.submit();
}

function goDel() {

	var check_count = 0;
	var total = document.frmSearch.length;

	var selData = "";
						 
	for(var i=0; i<total; i++) {
		if(document.frmSearch.elements[i].checked == true) {
	    	if(selData == "") selData = document.frmSearch.elements[i].value;
			else selData = selData + "_" + document.frmSearch.elements[i].value;			
			check_count++;
	    }
	}
	
	if(check_count == 0) {
		alert("삭제하실 자료를 선택해 주십시오.");
	    return;
	}
	
	bDelOK = confirm("정말 삭제 하시겠습니까?");
		
	if ( bDelOK ==true ) {
		document.frmSearch.seq_no.value = selData;
		document.frmSearch.mode.value = "del";
		document.frmSearch.action = "sms_db.php";
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

function js_add_mem(seq_no) {
	
	var url = "sms_add_user.php?seq_no="+seq_no;
	NewWindow(url, "sms_add_page", '700', '560', "no");
		
}

function NewWindow(mypage, myname, w, h, scroll) {
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function js_send_sms(seq_no) {

	bDelOK = confirm("해당 SMS를 발송 하시겠습니까?");
	
	if ( bDelOK ==true ) {
		document.frmSearch.seq_no.value = seq_no;
		document.frmSearch.mode.value = "SEND";
		document.frmSearch.submit();
	}

}

</script>
</head>
<BODY bgcolor="#FFFFFF" onLoad="init();">

<?php include "common_load.php" ?>

<FORM name="frmSearch" method="post" action="sms_list.php">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>SMS 관리</B></TD>
	<TD align="right" width="600" align="center" bgcolor=silver>
	<SELECT NAME="idxfield">
		<OPTION VALUE="0">제목</OPTION>
		<OPTION VALUE="1">내용</OPTION>
	</SELECT>
	<INPUT TYPE="text" NAME="qry_str" VALUE="">&nbsp;
	<INPUT TYPE="submit" VALUE="검색">
	<INPUT TYPE="button" VALUE="등록" onClick="goIn();">
	<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
	</TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="3%">&nbsp;</TH> 
	<TH width="17%">제목</TH>
	<TH width="30%">내용</TH>
	<TH width="10%">등록일</TH>
	<TH width="10%">발송인원</TH>
	<TH width="10%">발송상태</TH>
	<TH width="10%">대상등록</TH>
	<TH width="10%">보내기</TH>
</TR>     
<?
	$result2 = mysql_query($query2);

	if ($TotalArticle) {

		for ($i = 0; $i < $Last; ++$i) {
			mysql_data_seek($result2,$i);
			$obj = mysql_fetch_object($result2);

			if ($i >= $First) {
				
				$date_s = date("Y-m-d [H:i]", strtotime($obj->reg_date));
				if($obj->send_date != "") $sendDate = date("Y-m-d [H:i]", strtotime($obj->send_date));
				else $sendDate = "";
	
?>
<TR align="center">
	<TD><INPUT TYPE="checkbox" name="CheckItem" value="<?echo $obj->seq_no?>"></TD>
	<TD align="left"><A HREF="javascript:onView('<?echo $obj->seq_no?>')"><?echo $obj->title?></a></TD>
	<TD align="left"><?echo $obj->contents?></TD>
	<TD><?echo $date_s?></TD>
	<TD align="right">
		<?
			$query = "select count(*) as CNT from tb_sms_mem where seq_no = '".$obj->seq_no."'";

			$result = mysql_query($query);
			$list = mysql_fetch_array($result);

			$number = $list[CNT];

			echo number_format($number)."&nbsp;";
		?>
	</TD>
	<TD>
		<?
			if ($obj->send_state == "0") {
				echo "발송대기";
			} else {
				echo "발송완료<br>".$sendDate;
			}
		?>
	</TD>
	<TD>
		<input type="button" name="btn" value=" 대상등록 " onclick="js_add_mem('<?=$obj->seq_no?>')">
	</TD>
	<TD>
		<?	if ($number == "0") { ?>
		<input type="button" name="btn" value=" SMS 보내기 " onclick="alert('발송 대상을 틍록해 주세요.');">
		<? } else { ?>
		<input type="button" name="btn" value=" SMS 보내기 " onclick="js_send_sms('<?=$obj->seq_no?>');">
		<? } ?>
	</TD>
</TR>
<?
			}
		}
	}
?>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0">
<TR>
	<TD align="center" colspan=7>
<?
$Scale = floor(($page - 1) / $PageScale);

if ($TotalArticle > $ListArticle)
{

	if ($page != 1)
			echo "[<a href='".$PHP_SELF."?page=1&idxfield=$idxfield&qry_str=$qry_str'>맨앞</a>]";
	// 이전페이지
	if (($TotalArticle + 1) > ($ListArticle * $PageScale))
	{
		$PrevPage = ($Scale - 1) * $PageScale;

		if ($PrevPage >= 0)
				echo "&nbsp;[<a href='".$PHP_SELF."?page=".($PrevPage + 1)."&idxfield=$idxfield&qry_str=$qry_str'>이전".$PageScale."개</a>]";
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
					echo "&nbsp;[<a href='".$PHP_SELF."?page=".$vk."&idxfield=$idxfield&qry_str=$qry_str'>".$vk."</a>]&nbsp;";
			else
				echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
		}

		
	}

	echo "&nbsp;";
	// 다음 페이지
	if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale))
	{
		$NextPage = ($Scale + 1) * $PageScale + 1;
			echo "[<a href='".$PHP_SELF."?page=".$NextPage."&idxfield=$idxfield&qry_str=$qry_str'>이후".$PageScale."개</a>]";
	}

	if ($page != $TotalPage)
			echo "&nbsp;[<a href='".$PHP_SELF."?page=".$TotalPage."&idxfield=$idxfield&qry_str=$qry_str'>맨뒤</a>]&nbsp;&nbsp;";
}
else 
			echo "&nbsp;[1]&nbsp;";	
?>
	</TD>
</TR>
</TABLE>
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="mode" value="del">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
	mysql_close($connect);
?>