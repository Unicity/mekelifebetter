<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";
	include "../excel_modal.php";

	logging($s_adm_id,'open refund list (refund_list.php)');
 	$s_flag = str_quote_smart_session($s_flag);
 	//$s_adm_id = str_quote_smart_session($s_adm_id);
	
	function getBankCode($code){
		$bankCodes = array(
			"060" => "BOA은행", 
			"263" => "HMC투자증권", 
			"054" => "HSBC은행", 
			"292" => "LIG투자증권",
			"289" => "NH투자증권", 
			"023" => "SC제일은행", 
			"266" => "SK증권", 
			"039" => "경남은행", 
			"034" => "광주은행", 
			"261" => "교보증권", 
			"004" => "국민은행", 
			"003" => "기업은행", 
			"011" => "농협중앙회", 
			"012" => "농협회원조합", 
			"031" => "대구은행", 
			"267" => "대신증권", 
			"238" => "대우증권", 
			"279" => "동부증권",
			"209" => "유안타증권", 
			"287" => "메리츠종합금융증권", 
			"230" => "미래에셋증권", 
			"059" => "미쓰비시도쿄UFJ은행", 
			"058" => "미즈호코퍼레이트은행", 
			"290" => "부국증권", 
			"032" => "부산은행", 
			"002" => "산업은행", 
			"240" => "삼성증권", 
			"050" => "상호저축은행", 
			"045" => "새마을금고연합회", 
			"007" => "수협중앙회", 
			"291" => "신영증권", 
			"076" => "신용보증기금", 
			"278" => "신한금융투자", 
			"088" => "신한은행", 
			"048" => "신협중앙회", 
			"005" => "외환은행", 
			"020" => "우리은행", 
			"247" => "우리투자증권", 
			"071" => "우체국", 
			"280" => "유진투자증권", 
			"265" => "이트레이드증권", 
			"037" => "전북은행", 
			"035" => "제주은행", 
			"264" => "키움증권", 
			"270" => "하나대투증권", 
			"081" => "하나은행", 
			"262" => "하이투자증권", 
			"027" => "한국씨티은행", 
			"243" => "한국투자증권", 
			"269" => "한화증권", 
			"218" => "KB증권", 
			"089" => "케이뱅크",
			"090" => "카카오뱅크" 
		);
		return $bankCodes[$code];
	}

	$idxfield				= str_quote_smart(trim($idxfield));
	$con_sort				= str_quote_smart(trim($con_sort));
	$con_order			= str_quote_smart(trim($con_order));

	if ($con_order == "con_a") {
		$order = "asc";
		$con_order = "con_a";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}

	$from_date			= str_quote_smart(trim($from_date));
	$s_status				= str_quote_smart(trim($s_status));
	$qry_str				= str_quote_smart(trim($qry_str));
	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));
	 
	 
	$toDay = date("Y-m-d");

	$yyyy= date('Y');
	
	if (empty($idxfield)) {
		$idxfield = "";
	} 
	
	if (empty($con_sort) ) {
		$con_sort = "rh.PostDate";
	}

	if (empty($con_order)) {
		$order = "desc";
	}

	if (empty($DSCSelect)) {
	    $DSCSelect = "";
	} 



	if ((empty($s_status)) || ($s_status == "A")) {
		$s_status = "A";
	} else {
		$que = $que." and rh.RefundStatus = '$s_status' ";		
	}


	switch ($idxfield) {
	    
	 	case '6':
	 		$que = $que." and rh.ReturnNo = $qry_str ";
	 		break;
	 	case '1':
	 		$que = $que." and rh.OrderNo = $qry_str ";
	 		break;
	 	case '2':
	 		$que = $que." and rh.memberID = $qry_str ";
	 		break;
	 	case '3':
	 		$que = $que." and rh.memberName like '%$qry_str%' ";
	 		break;
	 	case '4':
	 		$que = $que." and rh.PostDate between '$qry_str' and '$qry_str1' ";
	 		break;
	 	case '5':
	 		$que = $que." and rh.CreatedDSC like '%$qry_str%' ";
	 		break;
	 	
	 	case '7':
	 	    $qry_str = substr($qry_str,0,2).'/'.substr($qry_str,2,4);
	 	    $que = $que." and rh.CommissionPeriod = '$qry_str' ";
	 	    break;
	 	    
	 		
	 	default:
	 		$que = $que." ";
	 		break;
	 }
	 
	 
	 switch ($DSCSelect) {
	     
	     case '1':
	         $que = $que." and rh.CreatedDSC = '서울DSC' ";
	         break;
	     case '2':
	         $que = $que." and rh.CreatedDSC = '인천DSC' ";
	         break;
	     case '3':
	         $que = $que." and rh.CreatedDSC = '안산DSC' ";
	         break;
	     case '4':
	         $que = $que." and rh.CreatedDSC = '대전DSC' ";
	         break;
	     case '5':
	         $que = $que." and rh.CreatedDSC = '광주DSC' ";
	         break;
	     case '6':
	         $que = $que." and rh.CreatedDSC = '원주DSC' ";
	         break;
	     case '7':
	         $que = $que." and rh.CreatedDSC = '대구DSC' ";
	         break;
	     case '8':
	         $que = $que." and rh.CreatedDSC = '부산DSC' ";
	         break;
	     case '9':
	         $que = $que." and rh.CreatedDSC = 'IT' ";
	         break;
		case '10':
			$que = $que." and rh.CreatedDSC = '제주 DSC' ";
			break;
	         
	     default:
	         $que = $que." ";
	         break;
	 } 
	logging($s_adm_id,'search refund '.$que);
		 
	
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
	if ($s_status == "20") {
		$nPageSize = 20;
		$con_sort = "LastUpdateDate";
	}

	$nPageBlock	= 10;
	
	$offset = $nPageSize*($page-1);

	$query = "select count(*) from tb_refund_header rh join tb_refund_line rl on rh.refundID = rl.refundID where 1 = 1 ".$que;
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	
	logging($s_adm_id,'search refund count'.$TotalArticle);
	
	$query2 = "select * from tb_refund_header rh join tb_refund_line rl on rh.refundID = rl.refundID where 1 = 1".$que." order by rh.CreatedDate desc , refundNo asc limit ". $offset.", ".$nPageSize; ;
	$result2 = mysql_query($query2);



	$query3 = "select * from tb_refund_user where user_name='$s_adm_id'";
	
	//echo $query3;
	$result3 = mysql_query($query3);


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
	
	
	//echo ">>".$s_adm_id;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/admin.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/jquery.jqGrid.min.js"></script>

<script language="javascript">
	var selVal = "";
    $(document).ready(function() {
    	//$('[name=qry_str1]').hide();
		selVal = document.frmSearch.idxfield.value;
    	if(selVal == '4'){
			$('[name=qry_str1]').show();
		}else{
			$('[name=qry_str1]').hide();
		}		
    });




	function enterPressed(event, type){
		if (window.event.keyCode == 13)
    	{
    		 
    			onSearch();	
    		 
       }

	}
	function onSearch(){
		
	/*	for(i=0; i < document.frmSearch.rsort.length ; i++) {
			if (document.frmSearch.rsort[i].checked == true) {
				document.frmSearch.con_sort.value = document.frmSearch.rsort[i].value;
			}
		}
	
		for(i=0; i < document.frmSearch.rorder.length ; i++) {
			if (document.frmSearch.rorder[i].checked == true) {
				document.frmSearch.con_order.value = document.frmSearch.rorder[i].value;
			}
		}
	*/
		document.frmSearch.page.value="1";
		document.frmSearch.action="./refund_list.php";
		document.frmSearch.submit();
	}
	

	function onSearch1(){
		selVal = document.frmSearch.idxfield.value;

		if(selVal == '4'){
			$('[name=qry_str1]').show();
		}else{
			$('[name=qry_str1]').hide();
		}		
		
		
		/*	for(i=0; i < document.frmSearch.rsort.length ; i++) {
				if (document.frmSearch.rsort[i].checked == true) {
					document.frmSearch.con_sort.value = document.frmSearch.rsort[i].value;
				}
			}
		
			for(i=0; i < document.frmSearch.rorder.length ; i++) {
				if (document.frmSearch.rorder[i].checked == true) {
					document.frmSearch.con_order.value = document.frmSearch.rorder[i].value;
				}
			}
		*/
			document.frmSearch.page.value="1";
			document.frmSearch.action="./refund_list.php";
			document.frmSearch.submit();
		}
	
	function check_data(){
		  
		for(i=0; i < document.frmSearch.s_status.length; i++) {
			if (document.frmSearch.s_status[i].checked == true) {
				 
				document.frmSearch.status.value = document.frmSearch.s_status[i].value;
			}
		}
		/*		
		for(i=0; i < document.frmSearch.rsort.length ; i++) {
			if (document.frmSearch.rsort[i].checked == true) {
				document.frmSearch.con_sort.value = document.frmSearch.rsort[i].value;
			}
		}
		for(i=0; i < document.frmSearch.rorder.length ; i++) {
			if (document.frmSearch.rorder[i].checked == true) {
				document.frmSearch.con_order.value = document.frmSearch.rorder[i].value;
			}
		}
*/

		document.frmSearch.action="./refund_list.php";
		document.frmSearch.submit();

	}
		

	function goPage(i) {
		document.frmSearch.page.value = i;
		document.frmSearch.submit();
	}

	function onView(type){
		document.frmSearch.type.value=type
		document.frmSearch.action= "./refund_detail.php";
		document.frmSearch.submit();
	}	

	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;

		var wint = (screen.height - h) / 2;

		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}
	
	function excelDown(){
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "refund_excel.php";
		frm.submit();
	}

	function goExecl(){
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "refund_excel.php";
		frm.submit();
	}
	

	function goCSV() {
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "../unclaimedCommission/unclaimedCommission_csv_list.php";
		frm.submit();
	}

	var date = new Date();
	function getTimeStamp() {
	  var s =
		leadingZeros(date.getFullYear(), 4) + '-' +
		leadingZeros(date.getMonth() + 1, 2) + '-' +
		leadingZeros(date.getDate(), 2) + ' ' +

		leadingZeros(date.getHours(), 2) + ':' +
		leadingZeros(date.getMinutes(), 2) + ':' +
		leadingZeros(date.getSeconds(), 2);

	  return s;
	}

	function leadingZeros(n, digits) {
	  var zero = '';
	  n = n.toString();

	  if (n.length < digits) {
		for (i = 0; i < digits - n.length; i++)
		  zero += '0';
	  }
	  return zero + n;
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
					sValues += "^"+frmSearch.CheckItem.value+"^";
				}
			}
		}
		sValues  +=")";
		return sValues;
	}

	function toggleCheckbox(element){
		var chkboxes = document.getElementsByName("CheckItem");
 	
 		for(var i=0; i<chkboxes.length; i++){
 			var obj = chkboxes[i];
 			obj.checked = element.checked;
 		}
 	}
 

	function getCheckedValues(){
		var checkboxes = document.getElementsByName('CheckItem');

		var vals = "";
		for (var i=0;i<checkboxes.length;i++) 
		{
	    	if (checkboxes[i].checked) 
    		{
        	vals += checkboxes[i].value+',';
    		}
		}
		vals = vals.slice(0, -1); 
		//alert(vals);	
		url = 'refund_task.php?data='+btoa(vals);
	 	NewWindow(url, '일괄처리', 350, 250, 'no');
	 
	}

	function onViewDetail(idVal,RefundNo){
		//alert(RefundNo);
		document.frmSearch.type.value='modify'
		document.frmSearch.idVal.value=idVal
		document.frmSearch.RefundNo.value=RefundNo	

		document.frmSearch.action= "./refund_detail.php";
		document.frmSearch.submit();

	}	

	function changeSelect(){
		selVal = document.frmSearch.idxfield.value;

		if(selVal == '4'){
			$('[name=qry_str1]').show();
		}else{
			$('[name=qry_str1]').hide();
		}		
	}

	function getDelteValues(){
		
		var checkboxes = document.getElementsByName('CheckItem');
	

		var vals = "";
		for (var i=0;i<checkboxes.length;i++) {	    	
			if (checkboxes[i].checked) {
        		vals += checkboxes[i].value+',';
    		}
		}
		vals = vals.slice(0, -1); 
	
		document.frmSearch.deleteVal.value=vals

		alert(document.frmSearch.deleteVal.value);

		
		document.frmSearch.action= "./refund_delete.php";
		document.frmSearch.submit();

	}	
</script>
<style type='text/css'>
body {
	font-family: Sans-serif, Arial, Monospace; 
}
td {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</style>
</head>
<body bgcolor="#FFFFFF">
<?
if($qry_str != ""){
	if($idxfield == "0") $criteria = "회원번호 :".$qry_str;
	else if($idxfield == "0") $criteria = "회원이름 :".$qry_str;
}
?>
<form name="frmSearch" method="post">
	<table cellspacing="0" cellpadding="10" class="title" border="0">
		<tr>
			<td align="left"><b>환불처리</b></td>
			<?php if($s_adm_id=='alsrnkmg' || $s_adm_id=='jihyun' || $s_adm_id=='jaekim' || $s_adm_id=='jahlee' || $s_adm_id=='bdlee'|| $s_adm_id=='kykim'|| $s_adm_id=='hrhan'|| $s_adm_id=='sjlee'|| $s_adm_id=='hsna'|| $s_adm_id=='kmbok' 
			    || $s_adm_id=='hycho'|| $s_adm_id=='danl' || $s_adm_id=='jupark' || $s_adm_id=='hwchoi'|| $s_adm_id=='dhpark'|| $s_adm_id=='sahwang' || $s_adm_id=='dhseo' || $s_adm_id=='jkkim' || $s_adm_id=='bmkim'|| $s_adm_id=='eycho'
				|| $s_adm_id=='mrchoi'|| $s_adm_id=='wsjeong'|| $s_adm_id=='jblee'|| $s_adm_id=='hgpark' ||$s_adm_id=='wjpark' ||$s_adm_id=='admin'){?>
			<td align="right" width="600" align="center" bgcolor=silver>
			<input type="button" value="추가" onclick="onView('new');" >
			<input type="button" value="엑셀 다운로드" onClick="excelDown();">
			<?php }?>
			<?php if($s_adm_id=='alsrnkmg' || $s_adm_id=='jihyun'|| $s_adm_id=='bmkim'|| $s_adm_id=='eycho'|| $s_adm_id=='mrchoi'){?>
			<input type="button" value="완료" onclick="getCheckedValues();" >
			<input type="button" value="삭제" onclick="getDelteValues();" >
			<?php }?>
			
		
			<!--  <input type="button" value="엑셀 다운로드" onClick="goExcelHistory('반품관리','환불등록','<?=$criteria?>');">-->
		 	</td>
		 
		</tr>
	</table>
	
	<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
		<tr>
			<td align='center'>
				<table width='99%' bgcolor="#EEEEEE">
					<tr align="center">
					
						<td align="left">
							<input type="radio" name="s_status" value="A" <? if ($s_status == "A") echo "checked" ?>  onClick="check_data();"> 전체 &nbsp;&nbsp;							 
							<input type="radio" name="s_status" value="10" <? if ($s_status == "10") echo "checked" ?>  onClick="check_data();"> 신규등록 &nbsp;&nbsp;							 
							<!-- <input type="radio" name="s_status" value="20" <? if ($s_status == "20") echo "checked" ?>  onClick="check_data();"> 수정완료&nbsp;&nbsp; -->
							<input type="radio" name="s_status" value="30" <? if ($s_status == "30") echo "checked" ?>  onClick="check_data();"> 완료&nbsp;&nbsp;
							<input type="radio" name="s_status" value="40" <? if ($s_status == "40") echo "checked" ?>  onClick="check_data();"> 반려&nbsp;&nbsp;
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
		<tr>
			<td align='center'>
				<table width='99%' bgcolor="#EEEEEE">
					<tr align="center">
						<td align="left">
							
							<span>※ DSC선택</span>
							<select name="DSCSelect">
								<option value="" >전체</option>
								<option value="9" <?if($DSCSelect == "9") echo "selected";?>>IT</option>
                				<option value="1" <?if($DSCSelect == "1") echo "selected";?>>서울</option>
                				<option value="2" <?if($DSCSelect == "2") echo "selected";?>>인천</option>
                				<option value="3" <?if($DSCSelect == "3") echo "selected";?>>안산</option>
                				<option value="4" <?if($DSCSelect == "4") echo "selected";?>>대전</option>
                				<option value="5" <?if($DSCSelect == "5") echo "selected";?>>광주</option>
                				<option value="6" <?if($DSCSelect == "6") echo "selected";?>>원주</option>
                				<option value="7" <?if($DSCSelect == "7") echo "selected";?>>대구</option>
                				<option value="8" <?if($DSCSelect == "8") echo "selected";?>>부산</option>
								<option value="10" <?if($DSCSelect == "10") echo "selected";?>>제주</option>
							</select>
			
							
							<select name="idxfield" onchange="changeSelect()">
                    			<option value="">선택</option>			
                    			<option value="6" <?if($idxfield == "6") echo "selected";?>>반품번호</option>
                    			<option value="1" <?if($idxfield == "1") echo "selected";?>>주문번호</option>
                    			<option value="2" <?if($idxfield == "2") echo "selected";?>>회원번호</option>
                    			<option value="3" <?if($idxfield == "3") echo "selected";?>>회원이름</option>
                    			<option value="4" <?if($idxfield == "4") echo "selected";?>>매출일시</option>
                    			<option value="7" <?if($idxfield == "7") echo "selected";?>>커미션</option>
                    		<!-- 
                    				<option value="5" <?if($idxfield == "5") echo "selected";?>>dsc</option>
                    	   -->
							</select>
							<input type="text" name="qry_str" value="<?echo $qry_str?>" onKeyPress="enterPressed(event,'search')" >&nbsp;
							<input type="text" name="qry_str1" value="<?echo $qry_str1?>" onKeyPress="enterPressed(event,'search')" >&nbsp;
							<input type="button" value="검색" onClick="onSearch1();">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
		<tr align="center">
			<th width="2%" style="text-align: center;" rowspan="3"><input type="checkbox" id="chckHead" onchange="toggleCheckbox(this);" /></td>
			<th width="5%" style="text-align: center;" rowspan="3">Postdate</th>
			<th width="6%" style="text-align: center;" rowspan="3">BA#</th>
			<th width="6%" style="text-align: center;" rowspan="3">Name</th>
			<th width="6%" style="text-align: center;" rowspan="3">커미션기간</th>
			<th width="6%" style="text-align: center;" rowspan="3">ORD#</th>
			<th width="6%" style="text-align: center;" rowspan="3">RTN#</th> 
			<th width="6%" style="text-align: center;" rowspan="3">주문일</th>
			
			<th width="6%" style="text-align: center;" rowspan="3">주문금액</th>
			
			<th width="12%" style="text-align: center;" colspan="2" rowspan="2">RTN Total PV</th>
			<th width="12%" style="text-align: center;" colspan="2"rowspan="2">RTN Total Amount</th>
			<th width="54%" style="text-align: center;" colspan="7">Deduction</th>
			<th width="12%" style="text-align: center;" colspan="4">Payment</th>
	
			<th width="6%" style="text-align: center;" rowspan="3">DSC</th>
			<!-- <th width="6%" style="text-align: center;" rowspan="3">etc</th>
		<th width="12%" style="text-align: center;" rowspan="2" colspan="2">재승인</th>
		 	<th width="6%" style="text-align: center;" rowspan="3" >재승인일</th>
		 -->
		</tr>     
		<tr>
			
			<th width="6%" style="text-align: center;" rowspan="2">Commission</th>
			<th width="6%" style="text-align: center;" rowspan="2">Card Fee</th>
			<th width="6%" style="text-align: center;" colspan="5">결제정보</th>
			<th width="6%" style="text-align: center;" rowspan="2">승인금액</th>
			<th width="6%" style="text-align: center;" rowspan="2">승인번호</th>
			<th width="6%" style="text-align: center;" rowspan="2">Cash</th>
			<th width="6%" style="text-align: center;" rowspan="2">Card</th>

		</tr>
		<tr>
			<th width="6%" style="text-align: center;">Adjustment</th>
			<th width="6%" style="text-align: center;">Volume</th>
			<th width="6%" style="text-align: center;">Adjustment</th>
			<th width="6%" style="text-align: center;">Payment</th>
			<th width="6%" style="text-align: center;">상점ID</th>
			<th width="6%" style="text-align: center;">Card/Bank</th>
			<th width="6%" style="text-align: center;">Card/Account#</th>
			
			<th width="6%" style="text-align: center;">유효기간</th>
			<th width="6%" style="text-align: center;">할부</th>
				
		
			
			
	<!-- 
			<th width="6%" style="text-align: center;">재승인금액</th>
			<th width="6%" style="text-align: center;">승인번호</th>
	 -->
		</tr>
		<?php 
		$result2 = mysql_query($query2);
		
		if ($TotalArticle) {
		    while($obj = mysql_fetch_object($result2)) {
		        $post_s = date("Y-m-d", strtotime($obj->PostDate));
		        $order_s = date("Y-m-d", strtotime($obj->OrderDate));
		        $Last_s = date("Y-m-d", strtotime($obj->LastModifiedDate));
		        $CardNumber = decrypt($key, $iv, $obj-> CardNumber);
		        $CardNumber = substr($CardNumber,0,4)." ".substr($CardNumber,4,4)." ".substr($CardNumber,8,4)." ".substr($CardNumber,12,4);
		        
		        $OrderTotal= number_format($obj-> OrderTotal);
		        
		        $ReturnAmountAdjustment =number_format($obj-> ReturnAmountAdjustment);
		        $ReturnAmount =number_format($obj-> ReturnAmount);
		        $cashAmount =number_format($obj-> cashAmount);
		        $cardAmount =number_format($obj-> cardAmount);
		        $ApprovalAmount=number_format($obj-> ApprovalAmount);
		        
		        if($obj-> StoreName == 'new'){
		            $StoreName = '신상점';
		        }else if($obj-> StoreName == 'internet'){
		            $StoreName = '인터넷';
		        }else if ($obj-> StoreName == 'allat'){
		            $StoreName = '올앳';
		        }else{
		            $StoreName = '무통장';
		        }
		        
		        if($obj-> ReturnPVAdjustment == 0){
		            $obj-> ReturnPVAdjustment = '-';
		        }
		        
		        if($obj-> ReturnAmountAdjustment == 0){
		            $obj-> ReturnAmountAdjustment = '-';
		        }
		        
		        if($obj-> Commission == 0){
		            $obj-> Commission = '-';
		        }
		        
		        if($obj-> CardFee == 0){
		            $obj-> CardFee = '-';
		        }
		        
		        if($cashAmount == 0){
		            $cashAmount= '-';
		        }
		        
		        if($cardAmount == 0){
		            $cardAmount = '-';
		        }
		        
		        
		?>
 		<tr>
 			<td align="center"><input type="checkbox" name="CheckItem" value="<?echo $obj->RefundID?>"></td>
 			
 			<td style="width: 5%" align="center"><?echo $post_s?></td>
 			<td style="width: 5%" align="center"><a href="javascript:onViewDetail('<?echo $obj->memberID?>','<?echo $obj->RefundNo?>')"><?echo $obj-> memberID?></a></td>
 			<td style="width: 5%" align="center"><?echo $obj-> memberName?></td>
 		
 			
 		<?php if($obj->RefundLine > 0){?>
 	
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom-color: red;" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5;border-bottom: 2" align="center"></td>
 		
 		<?php }else{ ?>
 			<td style="width: 5%" align="center"><?echo $obj-> CommissionPeriod?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> OrderNo?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnNo?></td>
 			<td style="width: 5%" align="center"><?echo $order_s?></td>
 			
 			<td style="width: 5%" align="center"><?echo $OrderTotal?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnPVAdjustment?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnPV?></td>
 			<td style="width: 5%" align="center"><?echo $ReturnAmountAdjustment?></td>
 			<td style="width: 5%" align="center"><?echo $ReturnAmount?></td>
 		<?php }?>
 			
 			<td style="width: 5%" align="center"><?echo $obj-> Commission?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> CardFee?></td>
 			<td style="width: 5%" align="center"><?echo $StoreName?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> StoreID?></td>
 		
 			<td style="width: 5%" align="center"><?echo $CardNumber?></td>
 			 
 			<td style="width: 5%" align="center"><?echo $obj-> ExpireDate?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> Installment?></td>
 			
 			<td style="width: 5%" align="center"><?echo $ApprovalAmount?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ApprovalNumber?></td>
 			
 			<td style="width: 5%" align="center"><?echo $cashAmount?></td>
 			<td style="width: 5%" align="center"><?echo $cardAmount?></td>
 		
 	
 			
 			<td style="width: 5%" align="center"><?echo $obj-> CreatedDSC?></td>
 			<!-- <td style="width: 5%" align="center"><?echo $obj-> Comments?></td>
 			
 	
	
 			<td style="width: 5%" align="center"><?echo $obj-> ReApprovalAmount?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReApprovalNumber?></td>
 			 	
 			<td style="width: 5%" align="center"><?echo $Last_s?></td>
 		 -->	
 		</tr>
		 
		 <?php 
		    }
		  }?>
	 
	</table>
	<table cellspacing="1" cellpadding="5" class="LIST" border="0">
		<tr>
			<td align="left">
	    		등록된 회원 수 : <?echo $TotalArticle?> 개
			</td>
			<td align="right">
				<?
					$Scale = floor(($page - 1) / $PageScale);
					if ($TotalArticle > $ListArticle){
						if ($page != 1)
							echo "[<a href=javascript:goPage('1');>맨앞</a>]";
							// 이전페이지
								if (($TotalArticle + 1) > ($ListArticle * $PageScale)){
									$PrevPage = ($Scale - 1) * $PageScale;
									if ($PrevPage >= 0)
										echo "&nbsp;[<a href=javascript:goPage('".($PrevPage + 1)."');>이전".$PageScale."개</a>]";
								}

							echo "&nbsp;";

							// 페이지 번호
							for ($vj = 0; $vj < $PageScale; $vj++){
								$vk = $Scale * $PageScale + $vj + 1;
								if ($vk < $TotalPage + 1){
									if ($vk != $page)
										echo "&nbsp;[<a href=javascript:goPage('".$vk."');>".$vk."</a>]&nbsp;";
									else
										echo "&nbsp;<b>[".$vk."]</b>&nbsp;";
								}
							}

							echo "&nbsp;";
							// 다음 페이지
							if ($TotalArticle > (($Scale + 1) * $ListArticle * $PageScale)){
								$NextPage = ($Scale + 1) * $PageScale + 1;
								echo "[<a href=javascript:goPage('".$NextPage."');>이후".$PageScale."개</a>]";
							}

							if ($page != $TotalPage)
								echo "&nbsp;[<a href=javascript:goPage('".$TotalPage."');>맨뒤</a>]&nbsp;&nbsp;";
					}
					
					else 
						echo "&nbsp;[1]&nbsp;";	
				?>
			</td>
		</tr>
	</table>
 
	<input type="hidden" name="page" value="<?echo $page?>">
	<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
	<input type="hidden" name="con_order" value="<?echo $con_order?>">
	<input type="hidden" name="status" value="<?echo $status?>">
	<input type="hidden" name="type" value="">
	<input type="hidden" name="idVal" value="">
	<input type="hidden" name="RefundNo" value="">
	<input type="hidden" name="deleteVal" value="">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>