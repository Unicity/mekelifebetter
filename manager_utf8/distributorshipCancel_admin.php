<?php session_start();?>

<?php


	
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	function getCodeName ($code, $parent)  { 

		$sqlstr = "SELECT name FROM tb_code where parent='".$parent."' and code='".$code."'"; 

		$result = mysql_query($sqlstr);
		$list = mysql_fetch_array($result);

		$name = $list[name];

		print($name);

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
	$to_date				= str_quote_smart(trim($to_date));
	$r_status				= str_quote_smart(trim($r_status));
	$r_memberkind		= str_quote_smart(trim($r_memberkind));
	$r_join_kind		= str_quote_smart(trim($r_join_kind));
	$r_active_kind	= str_quote_smart(trim($r_active_kind));
	$r_couple				= str_quote_smart(trim($r_couple));
	$qry_str				= str_quote_smart(trim($qry_str));
	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));
	$reg_status			= str_quote_smart(trim($reg_status));

	$toDay = date("Y-m-d");

	$yyyy= date('Y');
	
	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	if (empty($con_sort)) {
		$con_sort = "cancelDate";
	}

	if (empty($con_order)) {
		$order = "desc";
	}




	if ((empty($r_status)) || ($r_status == "A")) {
		$r_status = "A";
	} else {
		$que = $que." and reg_status = '$r_status' ";		
	}

	if (!empty($qry_str)) {
	
		if ($idxfield == "0") {
			$que = $que." and id like '%$qry_str%' ";
		} else if($idxfield == "1"){
			$que = $que." and UserName like '%$qry_str%' ";
		}
	
	
	
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

	$query = "select count(*) from distributorshipCancel where 1 = 1 ".$que;
	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];

	$query2 = "select * from distributorshipCancel where 1 = 1 ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; ;
	$result2 = mysql_query($query2);



//	$query3 = "select count(*) from tb_userinfo where member_no > '' ".$que. "  ";
//	$result3 = mysql_query($query3,$connect);
//	$row3 = mysql_fetch_array($result3);
//	$TotalArticle3 = $row3[0];
	/*
	echo $query;
	echo $query2."<br>";
	echo $now_query."<br>";
	*/
	


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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.jqGrid.min.js"></script>

<script language="javascript">
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
		document.frmSearch.action="distributorshipCancel_admin.php";
		document.frmSearch.submit();
	}
	

	function check_data(){

		for(i=0; i < document.frmSearch.r_status.length ; i++) {
			if (document.frmSearch.r_status[i].checked == true) {
				document.frmSearch.con_sort.value = document.frmSearch.r_status[i].value;
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

		document.frmSearch.action="distributorshipCancel_admin.php";
		document.frmSearch.submit();

	}
		

	function goPage(i) {
		document.frmSearch.page.value = i;
		document.frmSearch.submit();
	}

	function onView(id){
		document.frmSearch.member_no.value = id; 
		document.frmSearch.action= "distributorshipCancel_view.php";
		document.frmSearch.submit();
	}	

	function goPrint() {


		var checkboxes = document.getElementsByName('CheckItem');
    

            var vals = "";
                for (var i=0;i<checkboxes.length;i++) {	    	
                    if (checkboxes[i].checked) {
                        vals += checkboxes[i].value+',';
                    }
                }
		    vals = vals.slice(0, -1); 

            if (vals=='' ||vals== null ){
                alert("출력 체크 하셔야 합니다.");
                return false;
            }
            
		
/*
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
	alert(check_count);	
*/
		document.frmSearch.member_no.value = getIds();
		
		var url = "distributorshipCancel_print.php?member_no="+vals;
		

		NewWindow(url, "print_page", '700', '500', "yes");
		
	}

	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
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

	function goConfirm(id){

		if(confirm("완료하시겠습니까?")){   
			document.frmSearch.confirmDate.value = getTimeStamp();
			document.frmSearch.member_no.value = id;
			document.frmSearch.reg_status.value = "4";
			document.frmSearch.action = "distributorshipCancel_confirm.php";
			document.frmSearch.submit();
		}	
	}	


	function toggleCheckbox(element){
		    var chkboxes = document.getElementsByName("CheckItem");
 	
 		        for(var i=0; i<chkboxes.length; i++){
 			        var obj = chkboxes[i];
 			        obj.checked = element.checked;
 		        }
 	    }

</script>
<style type='text/css'>
td {FONT-SIZE: 9pt}
.h {FONT-SIZE: 9pt; LINE-HEIGHT: 120%}
.h2 {FONT-SIZE: 9pt; LINE-HEIGHT: 180%}
.s {FONT-SIZE: 8pt}
.l {FONT-SIZE: 11pt}
.text {  line-height: 125%}
</style>
</head>
<body bgcolor="#FFFFFF">
<form name="frmSearch" method="post" action="javascript:check_data();">
	<table cellspacing="0" cellpadding="10" class="title">
		<tr>
			<td align="left"><b>디스트리뷰터쉽 해지조회</b></td>
			<td align="right" width="600" align="center" bgcolor=silver>
			<select name="idxfield">
				<option value="0" <?if($idxfield == "0") echo "selected";?>>회원번호</OPTION>
				<OPTION VALUE="1" <?if($idxfield == "1") echo "selected";?>>회원이름</OPTION>
			</select>
			<input type="text" name="qry_str" value="<?echo $qry_str?>">&nbsp;
			<input type="button" value="검색" onClick="onSearch();">
			<!--  <input type="button" value="엑셀 다운로드" onClick="goExecl();">-->
			<!--
			<INPUT TYPE="button" VALUE="삭제" onClick="goDel();">	
			-->
			</td>
		</tr>
	</table>
	
	<b>* 처리 하실 단계를 선택하세요.</b>
	<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
		<tr>
			<td align='center'>
				<table width='99%' bgcolor="#EEEEEE">
					<tr align="center">
						<td align="left">
							<input type="radio" name="r_status" value="A" <? if ($r_status == "A") echo "checked" ?>  onClick="check_data();"> 전체 &nbsp;&nbsp;
							<!--input type="radio" name="r_status" value="1" <? if ($r_status == "1") echo "checked" ?>  onClick="check_data();"> 신청 (본인여부확인) &nbsp;&nbsp;-->
							<input type="radio" name="r_status" value="2" <? if ($r_status == "2") echo "checked" ?>  onClick="check_data();"> 신청 &nbsp;&nbsp;
							<input type="radio" name="r_status" value="3" <? if ($r_status == "3") echo "checked" ?>  onClick="check_data();"> 출력 처리 (프린트 출력) &nbsp;&nbsp;
							<input type="radio" name="r_status" value="4" <? if ($r_status == "4") echo "checked" ?>  onClick="check_data();"> 완료 (서버 입력 완료)&nbsp;&nbsp;
							<input type="radio" name="r_status" value="8" <? if ($r_status == "8") echo "checked" ?>  onClick="check_data();"> 보류&nbsp;&nbsp;
							<input type="radio" name="r_status" value="9" <? if ($r_status == "9") echo "checked" ?>  onClick="check_data();"> 신청 거부&nbsp;&nbsp;
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
							<b><input type="radio" name="rsort" value="cancelDate" <?if($con_sort == "cancelDate") echo "checked";?> onClick="check_data();"> 등록일 </b>
							<b><input type="radio" name="rsort" value="address" <?if($con_sort == "address") echo "checked";?> onClick="check_data();"> 주소 </b>
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
			<th width="3%" style="text-align: center;">주,부사업자</th>
			<th width="5%" style="text-align: center;">성명</th>
			<th width="5%" style="text-align: center;">회원번호</th>
			<th width="5%" style="text-align: center;">주소</th>
			<th width="5%" style="text-align: center;">생년월일</th>
			<th width="5%" style="text-align: center;">전화번호</th>
		
		<!--<th width="5%" style="text-align: center;">오토쉽 계약여부</th>
			<th width="5%" style="text-align: center;">회원<br/>등록증</th>
			<th width="5%" style="text-align: center;">등록증<br/>반납</th>
			<th width="5%" style="text-align: center;">회원수첩</th>
			<th width="5%" style="text-align: center;">수첩반납</th> 
		-->
			
			<th width="16%" style="text-align: center;">해지신청사유</th>
			<th width="5%" style="text-align: center;">해지<br/>신청일자</th>
			<th width="5%" style="text-align: center;">처리상태</th>
			<?php	if (($r_status == 1) || ($r_status == 2) ) {?>
			<th width="5%" style="text-align: center;">선택</th>
			<?php }else if ($r_status == "3"){ ?>
				<th width="5%" style="text-align: center;">출력일</th>
				<th width="5%" style="text-align: center;">출력자</th>

				<th width="5%" style="text-align: center;">완료처리</th>
			<?php }else if($r_status == "4"){?>
				<th width="5%" style="text-align: center;">완료일</th>
				<th width="5%" style="text-align: center;">완료자</th>
			<?php } else if($r_status == "8"){?>
				<th width="5%" style="text-align: center;">보류일</th>
				<th width="5%" style="text-align: center;">보류자</th>
			<?php } else if($r_status == "9"){?>
				<th width="5%" style="text-align: center;">신청거부일</th>
				<th width="5%" style="text-align: center;">신청거부자</th>
			<?php }?>
				<th width="10%">해지거절사유</th>
				<th width="6%">오토쉽 여부</th>
				<th width="6%">회원 등록증<br/> 확인 발급서</th>
				<th width="4%">신청방법</th>
		</tr>     
	<?php
		$result2 = mysql_query($query2);
		if ($TotalArticle) {
	
			while($obj = mysql_fetch_object($result2)) {
				$date_s = date("Y-m-d", strtotime($obj->cancelDate));
				$print_s = date("Y-m-d", strtotime($obj->print_date));
				$confirm_s = date("Y-m-d", strtotime($obj->confirm_date));
				$wait_s = date("Y-m-d", strtotime($obj->wait_date));
				$reject_s = date("Y-m-d", strtotime($obj->reject_date));
				
				$phone1 = substr($obj->Phone, 0, 3);
				$phone2 = substr($obj->Phone, 3, 4);
				$phone3 = substr($obj->Phone, 7, 4);
				
				$birthDay1 = substr($obj->birthDay, 0, 4);
				$birthDay2 = substr($obj->birthDay, 4, 2);
				$birthDay3 = substr($obj->birthDay, 6, 2);
				$memo = nl2br($obj->memo);
				
				if($obj->distributorshipCard == 'Y'){ 
					$cardYN = '반납';
				}else if($obj->distributorshipCard == 'N'){
					$cardYN = '분실/훼손';
				}else if($obj->distributorshipCard == 'E'){
					$cardYN = '기타';
				}else if($obj->distributorshipCard == null){
					$cardYN = ' ';
				}
				
				if($obj->distributorshipNote == 'Y'){
					$noteYN = '반납';
				}else if($obj->distributorshipNote == 'N'){
					$noteYN = '분실/훼손';
				}else if($obj->distributorshipNote == 'E'){
					$noteYN = '기타';
				}else if($obj->distributorshipCard == null){
					$noteYN = ' ';
				}
				
				if($obj->autoshipYn == 'Y'){
					$autoYN = '동의';
				}else if($obj->autoshipYn == 'N'){
					$autoYN = '오토쉽미신청자';
				}else if($obj->autoshipYn == null){
					$autoYN = ' '; 
				}
				
		
				if($obj->address == ""){
					$address = "";
				}else if($obj->address <>""){
					$address = $obj->address;
				}
				
				if($obj->mainsubchk == 1){
					$mainsub = "주";
				}else if($obj->mainsubchk == 2){
					$mainsub = "부";
				}


				if($obj->certification == 'Y'){
					$certValue='마'; 
				}else if($obj->certification == 'N'){
					$certValue='홈';
				}else if($obj->certification == ''){
					$certValue='';
				}
				
				
	?>
		<tr>
			<td align="center"><?echo $mainsub?></td>
			<td align="center"><?echo $obj->UserName?></td>
			<td align="center"><a HREF="javascript:onView('<?echo $obj->no?>')"><?echo $obj->id?></a></td>
			<td align="center"><?echo $address?></td>
			<td align="center"><?echo $birthDay1?>-<?echo $birthDay2?>-<?echo $birthDay3?></td>
			<td align="center"><?echo $phone1?>-<?echo $phone2?>-<?echo $phone3?></td>
			<!--  <td align="center"><?echo $autoYN?></td>
			<td align="center"><?echo $cardYN?></td>
			<td align="center"><?echo $obj->cardetc?></td>
			<td align="center"><?echo $noteYN?></td>
			<td align="center"><?echo $obj->noteetc?></td>-->
			<td align="center"><?echo $obj->cancelReason?></td>
			<td align="center"><?echo $obj->cancelDate?></td>
			<td align="center">
				<?	
					if ($obj->reg_status == "1") {
						echo "신청(본인확인)";
					} else if ($obj->reg_status == "2") {
						echo "신청";
					} else if ($obj->reg_status == "3") {
						echo "출력처리";
					} else if ($obj->reg_status == "4") {
						echo "완료";
					} else if ($obj->reg_status == "8") {
						echo "보류";
					} else if ($obj->reg_status == "9") {
						echo "거부";
					} 
				?>	
			</td>
	
	<?	if (($r_status == 1) || ($r_status == 2)) {?>
		<td align="center"><input type="checkbox" name="CheckItem" value="<?echo $obj->no?>"></td>
	<?	} else if($r_status == 3){?>
		<td align="center"><?php echo $print_s?></td>
		<td align="center"><?php echo $obj->print_ma?></td>
		<td align="center"><input width="5%" type="button" value="완료" onClick="goConfirm('<?=$obj->no?>');"></td>
	<? } else if($r_status == 4){?>
		<td align="center"><?php echo $confirm_s?></td>
		<td align="center"><?php echo $obj->confirm_ma?></td>
	<? } else if($r_status == 8){?>
		<td align="center"><?php echo $wait_s?></td>
		<td align="center"><?php echo $obj->wait_ma?></td>
	<? }  else if($r_status == 9){?>
		<td align="center"><?php echo $reject_s?></td>
		<td align="center"><?php echo $obj->reject_ma?></td>
	<? } ?>
		<td><?php echo $memo?></td>
		<td align="center">
			<?php 
			if ($obj->autoshipYn == "Y"){
				echo "신청";
			}else if($obj->autoshipYn == "N"){
				echo "미신청";
			}	
			?>
		</td>
		
		<td align="center">
			<?php 
			if ($obj->memberReg == "Y"){
				echo "신청";
			}else if($obj->memberReg == "N"){
				echo "미신청";
			}	
			?>
		</td>

		<td align="center"><?echo $certValue?></td>
		
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
	<table cellspacing="0" cellpadding="10" class="TITLE">
		<tr>
			<td align="left">&nbsp;</td>
			<td align="right" width="600" align="center" bgcolor=silver>
		
		<?  if ($r_status == "2") { ?>
				<input type="button" value="프린트 출력" onClick="goPrint();">	
				<input TYPE="button" value="Test 프린트 출력" onClick="goPrint2();">
		<?	} else if ($r_status == "3") { ?>
			<!--<INPUT TYPE="button" VALUE="FO 일괄저장" onClick="goInputAllBA();">--><!--&nbsp;&nbsp;	<INPUT TYPE="button" VALUE="BA 입력" onClick="goInputBA();"-->	
		<?	}?>
		
			</td>
		</tr>
	</table>
	<input type="hidden" name="page" value="<?echo $page?>">
	<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
	<input type="hidden" name="con_order" value="<?echo $con_order?>">
	<input type="hidden" name="reg_status" value="">
	<input type="hidden" name="member_no" value="">
	<input type="hidden" name="confirmDate" value="">
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>