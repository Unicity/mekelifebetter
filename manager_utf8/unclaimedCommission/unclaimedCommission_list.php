<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";

	logging($s_adm_id,'open unclaimedCommission list (unclaimedCommission_list.php)');
 	$s_flag = str_quote_smart_session($s_flag);

	//echo $s_adm_id;

	function getErrorCode($code){
		$errorCodes = array(
			"DEP13911" =>"S-more포인트 통장은 S-more포인트만 입금 가능합니다.",
			"EEF90417" =>"타행이체거래 불가 계좌입니다.",
			"EEF90415" =>"불입금이 상위(1회 불입단위가 있는 경우)합니다.",
			"EEF90410" =>"이관 계좌입니다.",
			"CIB00113" =>"등록되지 않은 이용자 아이디",
			"CIB10101" =>"존재하지 않은 계좌",
			"CSV01389" =>"계좌번호 오류",
			"DEP00043" =>"계좌상태 확인",
			"EEF90401" =>"수취인계좌 없음",
			"EEF90409" =>"법적제한계좌",
			"EEF90411" =>"해지계좌",
			"EEF90412" =>"잡좌계좌(휴면계좌)",
			"EEF90413" =>"기타수취불가 계좌",
			"ELB00011" =>"입금가능하지 않은 계좌",
			"ELB00016" =>"당행 존재하지 않은 계좌",
			"ETA00305" =>"번호오류(타행 계좌번호 길이는 14자리를 초과할 수 없습니다.)",
			"ETA00310" =>"타행 정상성 오류",
			"ETA00315" =>"정상성 오류 (정당한 계좌번호가 아님)",
			"ETA00325" =>"정상성 오류 (정당한 계좌번호가 아님)",
			"EEF90723" =>"사고신고 계좌",
			"DEP50330" =>"입금/지급정지가 기등록 되어 있습니다.",
			"CCT50071" =>"기업인터넷뱅킹 급여이체(193)는 유동성계좌만 입금 가능합니다.",
			"EEF90411" =>"기존계좌해지",
			"EEF90412" =>"기존계좌해지",
			"EEF90413" =>"기존계좌해지",
			"EEF90435" =>"타행이체거래 불가 계좌입니다.",
			"EEF90308" =>"외환-하나 통합으로 인한 오류",
			"EEF90727" =>"거래중지 계좌",
			"EEF90310" =>"해당지점 처리 불가 입니다.",
			"DEF00300" =>"잔액증명 발급게좌로 발급일자로는 거래 할 수 없습니다.",
			"DEF53905" =>"장기 미사용에 따른 거래중지 계좌입니다.",
			"EEF90724" =>"거래중지 계좌해당은행에 문의하세요.",
			"DEP53905" =>"장기 미사용에 따른 거래중지 계좌입니다.",
			"DEP13915" =>"수취인의 계좌는 신한행복지킴이통장으로 입금이 불가 합니다.",
			"CCT40021" =>"당행에 존재하지 않는 계좌 번호 입니다.",
			"EEF90407" =>"입력한 계좌번호는 정당한 계좌번호가 아닙니다. 해당 은행에 존재하지 않는 계좌번호 길이입니다.",
			"EF90438" =>"압류금지 전용 계좌로 입금불가"
		);
		return $errorCodes[$code];
	}
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
			"090" => "카카오뱅크",
			"092" => "토스뱅크" 
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
		$idxfield = "0";
	} 
	
	if (empty($con_sort) || $con_sort != "CommissionDate") {
		$con_sort = "CommissionDate";
	}

	if (empty($con_order)) {
		$order = "desc";
	}




	if ((empty($s_status)) || ($s_status == "A")) {
		$s_status = "A";
	} else {
		$que = $que." and status = '$s_status' ";		
	}

	if (!empty($qry_str)) {
	
		if ($idxfield == "0") {
			$que = $que." and id like '%$qry_str%' ";
		} else if($idxfield == "1"){
			$que = $que." and memberName like '%$qry_str%' ";
		} 
		logging($s_adm_id,'search unclaimed commission '.$que);
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
	if ($s_status == "20") {
		$nPageSize = 100;
		$con_sort = "LastUpdateDate";
	}

	$nPageBlock	= 10;
	
	$offset = $nPageSize*($page-1);

	$query = "select count(*) from unclaimedCommission where 1 = 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	
	logging($s_adm_id,'search unclaimed commission count'.$TotalArticle);
	
	$query2 = "select * from unclaimedCommission where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/admin.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqgrid/4.6.0/js/jquery.jqGrid.min.js"></script>

<script language="javascript">
	function enterPressed(event, type){
		if (window.event.keyCode == 13)
    	{
    		if (type == 'search')
    		{
    			onSearch();	
    		} else if (type =='name') {
    			getName();
    		} else {}
        	
    	}

	}
	function onSearch(){
		
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
	
		document.frmSearch.page.value="1";
		document.frmSearch.action="unclaimedCommission_list.php";
		document.frmSearch.submit();
	}
	

	function check_data(){
		  
		for(i=0; i < document.frmSearch.s_status.length; i++) {
			if (document.frmSearch.s_status[i].checked == true) {
				 
				document.frmSearch.status.value = document.frmSearch.s_status[i].value;
			}
		}
				
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

		document.frmSearch.action="unclaimedCommission_list.php";
		document.frmSearch.submit();

	}
		

	function goPage(i) {
		document.frmSearch.page.value = i;
		document.frmSearch.submit();
	}

	function onView(id,date){
		document.frmSearch.member_no.value = id; 
		document.frmSearch.commissionDate.value=date;
		document.frmSearch.action= "unclaimedCommission_detail.php";
		document.frmSearch.submit();
	}	

	function goPrint() {

		var check_count = 0;
		
		if (document.frmSearch.CheckItem == null ) {
			alert("출력하실 회원이 없습니다.");
		    return;		
		}

		var total = document.frmSearch.CheckItem.length;

		if (document.frmSearch.CheckItem.length == null) {
			if(document.frmSearch.CheckItem.checked == true) {
		    	check_count++;
		    }
		}
							 
		for(var i=0; i<total; i++) {
			if(document.frmSearch.CheckItem[i].checked == true) {
		    	check_count++;
		    }
		}
		
		if(check_count == 0) {
			alert("출력하실 회원을 선택해 주십시오.");
		    return;
		}
		
		document.frmSearch.member_no.value = getIds();
		
		var url = "distributorshipCancel_print.php?member_no="+document.frmSearch.member_no.value;
		alert(url);
		NewWindow(url, "print_page", '700', '500', "yes");
		
	}

	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;

		var wint = (screen.height - h) / 2;

		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}

	function excelDown(path){
		var frm = document.frmSearch;
		frm.target = "";
		if(path != "") frm.action = path;
		else frm.action = "unclaimedCommission_excel_list.php";
		frm.submit();
	}

	function goExcel() {
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "unclaimedCommission_excel_list.php";
		frm.submit();
	}

	function goCSV() {
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "unclaimedCommission_csv_list.php";
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
		var chkboxes = document.getElementsByName("chkCommission");
 	
 		for(var i=0; i<chkboxes.length; i++){
 			var obj = chkboxes[i];
 			obj.checked = element.checked;
 		}
 	}
 

	function getCheckedValues()
	{
		var checkboxes = document.getElementsByName('chkCommission');
		var vals = "";
		for (var i=0;i<checkboxes.length;i++) 
		{
	    	if (checkboxes[i].checked) 
    		{
        	vals += checkboxes[i].value+',';
    		}
		}
		vals = vals.slice(0, -1); 
		url = 'unclaimedCommission_task.php?data='+btoa(vals);
	 	NewWindow(url, '일괄처리', 350, 250, 'no');
	 
	}

	function layer_popup(el){
        
        var $el = $(el);    //레이어의 id를 $el 변수에 저장
        var isDim = $el.prev().hasClass('dimBg'); //dimmed 레이어를 감지하기 위한 boolean 변수

        isDim ? $('.dim-layer').fadeIn() : $el.fadeIn();

        var $elWidth = ~~($el.outerWidth()),
            $elHeight = ~~($el.outerHeight()),
            docWidth = $(document).width(),
            docHeight = $(document).height();

        // 화면의 중앙에 레이어를 띄운다.
        if ($elHeight < docHeight || $elWidth < docWidth) {
            $el.css({
                marginTop: -$elHeight /2,
                marginLeft: -$elWidth/2
            })
        } else {
            $el.css({top: 0, left: 0});
        }

        $el.find('a.btn-layerClose').click(function(){
            isDim ? $('.dim-layer').fadeOut() : $el.fadeOut(); // 닫기 버튼을 클릭하면 레이어가 닫힌다.
            return false;
        });

        $('.layer .dimBg').click(function(){
            $('.dim-layer').fadeOut();
            return false;
        });

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

	* {
            margin: 0;
            padding: 0;
            }

            .pop-layer .pop-container {
            padding: 20px 25px;
            }

            .pop-layer p.ctxt {
            color: #666;
            line-height: 25px;
            }

            .pop-layer .btn-r {
            width: 100%;
            margin: 10px 0 20px;
            padding-top: 10px;
            border-top: 1px solid #DDD;
            text-align: right;
            }

            .pop-layer {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 410px;
            height: auto;
            background-color: #fff;
            border: 5px solid #000000;
            z-index: 10;
            }

            .dim-layer {
            display: none;
            position: fixed;
            _position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            }

            .dim-layer .dimBg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            opacity: .5;
            filter: alpha(opacity=50);
            }

            .dim-layer .pop-layer {
            display: block;
            }

            a.btn-layerClose {
            display: inline-block;
            height: 25px;
            padding: 0 14px 0;
            border: 1px solid #304a8a;
            background-color: #3f5a9d;
            font-size: 13px;
            color: #fff;
            line-height: 25px;
            }

            a.btn-layerClose:hover {
            border: 1px solid #091940;
            background-color: #1f326a;
            color: #fff;
            }

</style>
</head>
<body bgcolor="#FFFFFF">
<form name="frmSearch" method="post" action="javascript:check_data();">
	<?
	if($s_status == "A" || $s_status == "") $criteria = "전체";
	else if($s_status == "10") $criteria = "신규등록";
	else if($s_status == "20") $criteria = "수정완료";
	else if($s_status == "30") $criteria = "지급완료";
	else if($s_status == "40") $criteria = "반려";

	if($qry_str != ""){
		if($idxfield == "0") $criteria = $criteria.", 회원번호 :".$qry_str;
		else if($idxfield == "0") $criteria = $criteria.", 회원이름 :".$qry_str;
	}
	?>
	
	<table cellspacing="0" cellpadding="10" class="title" border="0">
		<tr>
			<td align="left"><b>미지급커미션처리</b></td>
			<td align="right" width="600" align="center" bgcolor=silver>
			<select name="idxfield">
				<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</OPTION>
				<OPTION VALUE="1" <?if($idxfield == "1") echo "selected";?>>회원이름</OPTION>
			</select>
			<input type="text" name="qry_str" value="<?echo $qry_str?>" onKeyPress="enterPressed(event,'search')" >&nbsp;
			<input type="button" value="검색" onClick="onSearch();">
			<?php 
				if($s_flag == "1" || $s_flag == "8" || $s_flag == "9") {
			?>

			<input type="button" value="데이터업로드" onClick="NewWindow('unclaimedCommission_upload.php?type=new', '데이터업로드', 650, 100, 'no');">
			<!--<input type="button" value="일괄승인" onClick="NewWindow('unclaimedCommission_upload.php?type=approve', '데이터업로드', 650, 50, 'no');">
			<input type="button" value="일괄반려" onClick="NewWindow('unclaimedCommission_upload.php?type=reject', '데이터업로드', 650, 50, 'no');"> -->
			<input type="button" value="일괄처리" onclick="getCheckedValues();" >
				<!-- <input type="button" value="CSV 다운로드" onClick="goCSV();"> -->
				<input type="button" value="CSV 다운로드" onClick="goExcelHistory('수당관리','미지급커미션처리','<?=$criteria?>','unclaimedCommission_csv_list.php')">
			<?php } ?>			
				<!-- <input type="button" value="엑셀 다운로드" onClick="goExcel();"> -->
				<input type="button" value="엑셀 다운로드" onClick="goExcelHistory('수당관리','미지급커미션처리','<?=$criteria?>','unclaimedCommission_excel_list.php')">
				<?php if($s_adm_id=='alsrnkmg'){ ?>
					<input type="button" value="엑셀 업로드" onclick="layer_popup('#layer1');" >
				<?php }?>
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
							<!--input type="radio" name="r_status" value="1" <? if ($r_status == "1") echo "checked" ?>  onClick="check_data();"> 신청 (본인여부확인) &nbsp;&nbsp;-->
							<input type="radio" name="s_status" value="10" <? if ($s_status == "10") echo "checked" ?>  onClick="check_data();"> 신규등록 &nbsp;&nbsp;
							 
							<input type="radio" name="s_status" value="20" <? if ($s_status == "20") echo "checked" ?>  onClick="check_data();"> 수정완료&nbsp;&nbsp;
							<input type="radio" name="s_status" value="30" <? if ($s_status == "30") echo "checked" ?>  onClick="check_data();"> 지급완료&nbsp;&nbsp;
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
							<b><input type="radio" name="rsort" value="cancelDate" <?if($con_sort == "cancelDate") echo "checked";?> onClick="check_data();"> 커미션날짜 </b>
						</td>
						<td align="right">
							<b><input type="radio" name="rorder" value="con_d" <?if($con_order == "con_d") echo "checked";?> onClick="check_data();">오름차순 </b>
							<b><input type="radio" name="rorder" value="con_a" <?if($con_order == "con_a") echo "checked";?> onClick="check_data();">내림차순 </b>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	

	<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
		<tr align="center">
			<th width="2%" style="text-align: center;"><input type="checkbox" id="chckHead" onchange="toggleCheckbox(this);" /></td>
			<th width="8%" style="text-align: center;">커미션날짜</th>
			<th width="8%" style="text-align: center;">회원번호</th>
			<th width="8%" style="text-align: center;">회원이름</th>
			<th width="8%" style="text-align: center;">생년월일</th>
			<th width="5%" style="text-align: center;">은행코드</th>
			<!--<th width="10%" style="text-align: center;">계좌번호</th>-->
			<th width="5%" style="text-align: center;">금액(원)</th>
			<th width="5%" style="text-align: center;">New 은행코드</th>
			<!--<th width="10%" style="text-align: center;">New 계좌번호</th> -->
			<th width="8%" style="text-align: center;">New 예금주명</th>
			<th width="8%" style="text-align: center;">등록일자</th>
			<th width="10%" style="text-align: center;">에러코드</th>
			<th width="5%" style="text-align: center;">처리상태</th>
			<th width="10%" style="text-align: center;">최근변경일</th>
			<th width="10%" style="text-align: center;">최근변경자</th>
		</tr>     
	<?php
		$result2 = mysql_query($query2);
		if ($TotalArticle) {
	
			while($obj = mysql_fetch_object($result2)) {
				$commissiondate_s = date("Y-m-d", strtotime($obj->CommissionDate));
				$createddate_s = date("Y-m-d H:i", strtotime($obj->CreatedDate));
				$lastUpdateDate_s = date("Y-m-d H:i", strtotime($obj->LastUpdateDate)); // null exception
				
			  
	?>
		<tr>
			<td align="center"><input type="checkbox" name="chkCommission" value="<?echo $obj->id?>_<?echo $obj->CommissionDate?>"></td>
			<td align="center"><?echo $commissiondate_s?></td>
			<td align="center"><a HREF="javascript:onView('<?echo $obj->id?>','<?echo $obj->CommissionDate?>')"><?echo $obj->id?></a></td>
			<td align="center"><?echo $obj->memberName?></td>
			<td align="center"><?echo $obj->dob?></td>
			<td align="center"><?echo getBankCode($obj->BankCode)?></td>
			<td align="center"><? echo number_format($obj->Amount);?></td> 
			<td align="center"><?echo getBankCode($obj->newBankCode)?></td>
			<td align="center"><?echo $obj->newAccountHolder?></td>
			<td align="center"><?echo $createddate_s?></td>
			<td align="center"><?echo $obj->errorCode?></td>
			<td align="center">
				<?	
					if ($obj->status == "10") {
						echo "신규등록";
					} else if ($obj->status == "20") {
						echo "수정완료";
					} else if ($obj->status == "30") {
						echo "지급완료";
					} else if ($obj->status == "40") {
						echo "반려";
					}  
				?>	
			</td>
			<td align="center"><?echo $lastUpdateDate_s?></td>
			<td align="center"><?echo $obj->LastUpdator?></td>
	
 
	</tr>
	<?php
	
			}
		}
	?>
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
	<input type="hidden" name="member_no" value="">
	<input type="hidden" name="commissionDate" value="">
	<input type="hidden" name="confirmDate" value="">
</form>
<div id="layer1" class="pop-layer">
            <div class="pop-container">
                <div class="pop-conts">
                    
                    <form enctype="multipart/form-data" action="./excel_read_uc.php" method="post">
                        <table border="1">	 
                            <tr>		
                                <th style="background-color:#DCDCDC">파일</th>		
                                <td><input type="file" name="excelFile"/></td>	
                            </tr>	
                            <tr>		
                                <th style="background-color:#DCDCDC">등록</th>		
                                <td style="text-align:center;"><input type="submit" value="업로드"/></td>	
                            </tr>
                        </table>    
                    </form>
                    <div class="btn-r">
                        <a href="#" class="btn-layerClose">Close</a>
                    </div>
                    <!--// content-->
                </div>
            </div>
        </div>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/excel_modal.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>