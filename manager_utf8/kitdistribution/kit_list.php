<?php session_start();?>

<?php
	
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";
	// 티켓분배 개별입력가능
	// 키트등록 그룹명
	// 티켓번호 순서 솔팅
	// 티켓번호 검색시 최소값으로
	$s_adm_id = str_quote_smart_session($s_adm_id);

	logging($s_adm_id,'open ticket list(kit distribution)');
 	
 	$s_flag = str_quote_smart_session($s_flag);

	 
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
	$qry_str				= str_quote_smart(trim($qry_str));
	$page						= str_quote_smart(trim($page));
	$nPageSize			= str_quote_smart(trim($nPageSize));
	 
	 
	$toDay = date("Y-m-d");

	$yyyy= date('Y');
	
	if (empty($idxfield)) {
		$idxfield = "0";
	} 
	
	if (empty($con_sort)  ) {
		$con_sort = "ticketNo";
	}

	if (empty($con_order)) {
		$order = "desc";
	}

 
	if (!empty($qry_str)) {
	
		if ($idxfield == "0") {
			$que = $que." and ttd.ticketNo >= $qry_str ";
		} else if($idxfield == "1"){
			$que = $que." and ttm.leader like '%$qry_str%' ";
		}  
		logging($s_adm_id,'search ticket distribution_kitlist '.$que);
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

	$query = "select count(*) from tb_ticket_detail ttd LEFT OUTER JOIN tb_ticket_master ttm on ttm.orderNo = ttd.orderNo LEFT OUTER JOIN tb_kit_detail tkd on ttd.ticketNo = tkd.ticketNo and ttd.orderNo = tkd.orderNo where 1 = 1  ".$que;

	$result = mysql_query($query,$connect);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
	
	logging($s_adm_id,'search ticket sales count'.$TotalArticle);
	
	$query2 = "select ttd.*, ttm.leader, tkd.baid as kitBA, tkd.fullname as kitFullname, tkd.contactNo as kitContactNo, tkd.creator as kitcreator, tkd.createdDate as kitCreatedDate, tkd.description as kitDescription from tb_ticket_detail as ttd "
			. "LEFT OUTER JOIN tb_ticket_master ttm on ttm.orderNo = ttd.orderNo " 
			. "LEFT OUTER JOIN tb_kit_detail tkd on ttd.ticketNo = tkd.ticketNo and ttd.orderNo = tkd.orderNo " 
			." where 1 = 1   ".$que." order by ".$con_sort." ".$order." limit ". $offset.", ".$nPageSize; 
	//echo $query2;
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
    		}  
        	
    	}

	}
	function onSearch(){
	 	document.frmSearch.page.value="1";
		document.frmSearch.action="kit_list.php";
		document.frmSearch.submit();
	}
	
 
	function goPage(i) {
		document.frmSearch.page.value = i;
		document.frmSearch.submit();
	}

	function onView(id){
		document.frmSearch.orderNo.value = id; 
		document.frmSearch.action= "kit_detail.php";
		document.frmSearch.submit();
	}	

	 

	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;

		var wint = (screen.height - h) / 2;

		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}
	function goExcel() {
		var frm = document.frmSearch;
		frm.target = "";
		frm.action = "kit_excel_list.php";
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

 

	function toggleCheckbox(element){
		var chkboxes = document.getElementsByName("chkTicketOrders");
 		for(var i=0; i<chkboxes.length; i++){
 			var obj = chkboxes[i];
 			obj.checked = element.checked;
 		}
 	}

 	function getCheckedValues()
	{
		var checkboxes = document.getElementsByName('chkTicketOrders');
		var vals = "";
		for (var i=0;i<checkboxes.length;i++) 
		{
	    	if (checkboxes[i].checked){
        		vals += checkboxes[i].value+',';
    		}
		}
		vals = vals.slice(0, -1); 
		url = 'kit_distribute.php?data='+(vals);
	 	NewWindow(url, '일괄처리', 550, 300, 'no');
	 
	}
	function showDesc(val){
		var ticketId = 'ticket_'+val;
		var kitId = 'kit_'+val;
		if(document.getElementById(ticketId).style.display =='') {
			document.getElementById(ticketId).style.display = 'none';
		} else {
			document.getElementById(ticketId).style.display = '';
		}
		if(document.getElementById(kitId).style.display =='') {
			document.getElementById(kitId).style.display = 'none';
		} else {
			document.getElementById(kitId).style.display = '';
		}
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
<form name="frmSearch" method="post" action="">
	<table cellspacing="0" cellpadding="10" class="title" border="0">
		<tr>
			<td align="left"><b>키트등록</b></td>
			<td align="right" width="600" align="center" bgcolor=silver>
			<select name="idxfield">
				<option value="0" <?if($idxfield == "0") echo "selected";?>>티켓번호</OPTION>
				<option value="1" <?if($idxfield == "1") echo "selected";?>>그룹명</OPTION>
				 
			</select>
			<input type="text" name="qry_str" value="<?echo $qry_str?>" onKeyPress="enterPressed(event,'search')" >&nbsp;
			<input type="button" value="검색" onClick="onSearch();">
			<!--<input type="button" value="업로드" onClick="NewWindow('ticket_upload.php', '데이터업로드', 875, 150, 'no');">
			<input type="button" value="추가" onClick="NewWindow('ticket_add.php', '데이터업로드', 875, 380, 'no');">-->
			<input type="button" value="Kit전달" onClick="getCheckedValues();">
			<input type="button" value="엑셀다운로드" onClick="goExcel();">
		 	</td>
		</tr>
	</table>
	 
	

	<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
		<tr align="center">
			<th width="2%" style="text-align: center;"><input type="checkbox" id="chckHead" onchange="toggleCheckbox(this);" /></td>
			<th width="7%" style="text-align: center;">티켓번호</th> 
			<th width="7%" style="text-align: center;">주문번호</th>
			<th width="7%" style="text-align: center;">그룹</th>
			<th width="7%" style="text-align: center;">티켓수령회원번호</th>
			<th width="7%" style="text-align: center;">티켓수령회원이름</th>
			<th width="10%" style="text-align: center;">티켓수령회원연락처</th>
			<th width="10%" style="text-align: center;">티켓전달직원</th>
			<th width="10%" style="text-align: center;">티켓전달일시</th>
			<th width="6%" style="text-align: center;">키트수령회원번호</th>
			<th width="6%" style="text-align: center;">키트수령회원이름</th>
			<th width="10%" style="text-align: center;">키트수령회원연락처</th>
			<th width="10%" style="text-align: center;">키트전달직원</th>
			<th width="10%" style="text-align: center;">키트전달일시</th>
			 
			 
		</tr>     
	<?php
		 
		if ($TotalArticle) {
			
			while($obj = mysql_fetch_object($result2)) {
			 	 
				$createddate_s = date("Y-m-d H:i", strtotime($obj->createdDate));
	?>
		<tr>
			<td align="center"><input type="checkbox" name="chkTicketOrders" value="<?echo $obj->orderNo.'_'.$obj->ticketNo?>"></td>
			<td align="left" style="padding-left: 10px"><a href="javascript:showDesc('<?echo $obj->orderNo.'_'.$obj->ticketNo?>')"> <?echo $obj->ticketPrefix.'-'.$obj->ticketNo?></a> </td>
			<td align="center"><?echo $obj->orderNo?></td>
			<td align="center"><?echo $obj->leader?></td>
			<td align="center"><?echo $obj->baid?></td>
			<td align="center"><?echo $obj->fullName?></td>
			<td align="center"><?echo $obj->contactNo?></td>
			<td align="center"><?echo $obj->creator?></td>
			<td align="center"><?echo $obj->createdDate?></td>
			<td align="center"><?echo $obj->kitBA?></td>
			<td align="center"><?echo $obj->kitFullname?></td>
			<td align="center"><?echo $obj->kitContactNo?></td>
			<td align="center"><?echo $obj->kitcreator?></td>
			<td align="center"><?echo $obj->kitCreatedDate?></td>
		</tr>
		<tr class="description" style="display:none" id="ticket_<?echo $obj->orderNo.'_'.$obj->ticketNo?>">
			<td></td>
			<td colspan="12">티켓전달 : <?echo $obj->description?></td>
		</tr>
		<tr class="description" style="display:none" id="kit_<?echo $obj->orderNo.'_'.$obj->ticketNo?>">
			<td></td>
			<td colspan="12">키트전달 : <?echo $obj->kitDescription?></td>
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
	<input type="hidden" name="orderNo">
</form>
</body>
</html>
<?
mysql_close($connect);
?>