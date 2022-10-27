<?php
session_start();
?>

<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";

	$s_adm_id = str_quote_smart_session($s_adm_id);
//	echo $s_flag;
	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	function str_cut($str,$len){
		$slen = strlen($str);
		if (!$str || $slen <= $len) $tmp = $str;
		else	$tmp = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$len))."...";
		return $tmp;
	}

  	$orderNo = str_quote_smart(trim($orderNo));
 	 
 	logging($s_adm_id,'view ticket sales detail '.$member_no);

	$query = "select * from tb_ticket_master where orderNo = '".$orderNo."' ";
	
 	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	
	$orderNo = $list[orderNo];
	$eventName = $list[eventName];
	$baid = $list[baid];
	$fullName = $list[fullName];
	$fullNameEn = $list[fullNameEn];
	$leader = $list[leader];
	$contactNo = $list[contactNo];
	$orderedQty = $list[orderedQty];
	$description = $list[description];

	$createdDate =  date("Y-m-d", strtotime($list[createdDate]));
	$creator =  $list[creator];

	$ticketQuery = "select * from tb_ticket_detail where orderNo = '".$orderNo."' "; 
	$ticketQueryresult = mysql_query($ticketQuery);


 	logging($s_adm_id,'view kit distribution detail '.$member_no);

	$kitQuery = "select * from tb_kit_detail where orderNo = '".$orderNo."' "; 
	$kitQueryresult = mysql_query($kitQuery);
?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="../inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}

	function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="ticket_list.php";
		                        
		document.frm_m.submit();
	}
	function saveTicketInfo(){
		var orderedQty = '<?php echo $orderedQty ?>';
		document.frm_m.orderedQty.value = orderedQty;
		document.frm_m.orderNo.value =  '<?php echo $orderNo;?>';
		var ticketPrefix = document.frm_m.ticketPrefix;
		var fullName = document.frm_m.fullName;
		var baid = document.frm_m.baid;
		var contactNo = document.frm_m.contactNo;
		var description = document.frm_m.description;
		
		if (ticketPrefix.value == ''){
			alert('티켓구분자를 선택해주세요');
			return false;
		}

		if (orderedQty == 1){	
			if(document.frm_m.ticket0.value == ''){
				alert('티켓 번호를 다시 확인해주세요');
				return false;
			}
		} else if (orderedQty == 2) {
			if(document.frm_m.ticket0.value == '' || document.frm_m.ticket1.value == ''){
				alert('티켓 번호를 다시 확인해주세요');
				return false;
			}
			if(document.frm_m.ticket0.value == document.frm_m.ticket1.value){
				alert('티켓 번호를 다시 확인해주세요1');
				return false;
			}
		} else {
			if(document.getElementById('chkTicketPanel').checked) {
				for(var i=0; i<orderedQty; i++){
			      if (document.getElementById('ticket'+i).value ==""){
			      	alert('티켓번호를 입력해주세요');
			        document.getElementById('ticket'+i).focus();
			        return false;
			      }
			    }
			} else {
				var startValue = document.frm_m.startNo.value;
				var endVaule = document.frm_m.endNo.value;

				if(startValue == '' || endVaule == ''){
					alert('티켓 번호를 다시 확인해주세요');
					return false;
				}
				if(startValue ==  endVaule){
					alert('티켓 번호를 다시 확인해주세요');
					return false;
				}
			 
				if ((endVaule-startValue+1) != orderedQty) {
					alert('주문수량과 티켓입력 수량이 상이합니다.');
					return false;
				}

			}
			

		}

		if(fullName.value == '') {
			alert('회원성명을 입력해주세요');
			document.getElementById("fullName").focus();
			return false;
		}

		if(baid.value == '') {
			alert('회원번호를 입력해주세요');
			document.getElementById("baid").focus();
			return false;
		}
		document.frm_m.target = "frmain";
		document.frm_m.action="ticket_detail_save.php";
		document.frm_m.submit();
	}
	function checker(val){
		var inText = val.value;
  		var deny_char = /^[ㄱ-ㅎ|가-힣|a-z|A-Z|0-9|\*]+$/
  		 
  		if (!deny_char.test(inText)) {
    		alert("영문자와 한글,숫자만을 입력하세요");
    		val.value = "";
    		val.focus();
    		return false;
  		}
  		return true;
	}
	 
	function showEachTicket(){
		if(document.getElementById('chkTicketPanel').checked){
			document.getElementById('ticketRange').style.display="none";
			document.getElementById('ticketIndividual').style.display="";

		}else {
			document.getElementById('ticketRange').style.display="";
			document.getElementById('ticketIndividual').style.display="none";
		}
	}
//-->
</SCRIPT>
</HEAD>
<body>
<form name="frm_m" method="post">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>티켓구매주문정보</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<INPUT type="button" onClick="goBack();" value="목록" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
	<tr>
		<td align='center'>
			<table border="0" cellspacing="1" cellpadding="2" class="IN">
				<tr>
					<th>
						이벤트 
					</th>
					<td><?echo $eventName?></td>
				</tr>
				<tr>
					<th>
						주문번호 
					</th>
					<td><?echo $orderNo?></td>
				</tr>
				<tr>
					<th>
						회원성명 
					</th>
					<td><?echo $fullName?></td>
				</tr>
				<tr>
					<th>
						회원번호 
					</th>
					<td><?echo $baid?></td>
				</tr>
				<tr>
					<th>
						그룹
					</th>
					<td><?echo $leader?></td>
				</tr>
				<tr>
					<th>
						연락처 
					</th>
					<td><?echo $contactNo?></td>
				</tr>
				<tr>
					<th>
						주문수량 
					</th>
					<td><?echo $orderedQty?></td>
				</tr>
				<tr>
					<th>
						기타내용 
					</th>
					<td><?echo $description?></td>
				</tr>
			</table>
			
		</td>
	</tr>
	<tr>
		<td colspan=2>
		</td>
	</tr>
	<tr>
		<td  colspan="2" style="font-size: 20px; font-weight:bold">
			 티켓전달정보
		</td>
	</tr>
	<?php
 
		
		if(mysql_num_rows($ticketQueryresult) > 0) {
			 
			$ticketNo = "";
			$ticketPrefix = "";
			$ticketfullName = "";
			$ticketbaid ="";
			$ticketcontactNo = "";
			$ticketdescription = "";
			$ticketcreator = "";
			$ticketcreatedDate = "";
			
			while($ticketList = mysql_fetch_assoc($ticketQueryresult)){
				$ticketNo .= $ticketList[ticketNo]." / ";
				$ticketPrefix = $ticketList[ticketPrefix];
				$ticketfullName = $ticketList[fullName];
				$ticketbaid = $ticketList[baid];
				$ticketcontactNo = $ticketList[contactNo];
				$ticketdescription = $ticketList[description];
				$ticketcreator =$ticketList[creator];
				$ticketcreatedDate =$ticketList[createdDate];

			}
	?>
	<tr>
		<td align='center'>

			<table border="0" cellspacing="1" cellpadding="2" class="IN">
				<tr>
					<th>
						티켓구분자 
					</th>
					<td><?echo $ticketPrefix?></td>
				</tr>
				<tr>
					<th>
						티켓번호 
					</th>
					<td><?echo $ticketNo?></td>
				</tr>
				<tr>
					<th>
						회원성명 
					</th>
					<td><?echo $ticketfullName?></td>
				</tr>
				<tr>
					<th>
						회원번호 
					</th>
					<td><?echo $ticketbaid?></td>
				</tr>
				<tr>
					<th>
						연락처 
					</th>
					<td><?echo $ticketcontactNo?></td>
				</tr>
				<tr>
					<th>
						기타내용 
					</th>
					<td><?echo $ticketdescription?></td>
				</tr>
				<tr>
					<th>
						티켓전달직원 
					</th>
					<td><?echo $ticketcreator?></td>
				</tr>
				<tr>
					<th>
						티켓전달일시
					</th>
					<td><?echo $ticketcreatedDate?></td>
				</tr>
			</table>
			
		</td>
	</tr>
	<?php } else { ?>
		<tr>
		<td align='center'>

			<table border="0" cellspacing="1" cellpadding="2" class="IN">
				<tr>
					<th>
						티켓구분자 
					</th>
					<td>
						<select name="ticketPrefix" id="ticketPrefix">
							<option value="">선택하세요</option>
							<option value="E">E</option>
							<option value="L">L</option>
							<option value="O">O</option>
							<option value="R">R</option>
							<option value="S">S</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						티켓번호
					</th>
					<?php if($orderedQty <3) { ?>
					<td>
						<?php for($i=0; $i<$orderedQty; $i++) {?>
						<input type="number" name="ticket<?php echo $i?>" id="ticket<?php echo $i?>" maxlength="5">	
						<?php } ?>
					</td>
					<?php } else {?>
					<td>
						<input type="checkbox" name="chkTicketPanel" id="chkTicketPanel" onchange="showEachTicket()">단건입력
						<div id="ticketRange">
							<input type="number" name="startNo" id="startNo" maxlength="5"> ~ <input type="number" name="endNo"  id="endNo" maxlength="5">
						</div>
						<div id="ticketIndividual" style="display:none">
							<?php for($i=0; $i<$orderedQty; $i++) {?>
								<input type="number" name="ticket<?php echo $i?>" id="ticket<?php echo $i?>" maxlength="5">	
							<?php } ?>
						</div>
					</td>
				<?php }?>
				</tr>
				<tr>
					<th>
						회원성명 
					</th>
					<td><input type="text" name="fullName" id="fullName" maxlength="10"></td>
				</tr>
				<tr>
					<th>
						회원번호 
					</th>
					<td><input type="text" name="baid" id="baid" maxlength="10"></td>
				</tr>
				<tr>
					<th>
						연락처 
					</th>
					<td><input type="text" name="contactNo" id="contactNo" maxlength="15"></td>
				</tr>
				<tr>
					<th>
						기타내용(Max 140자)
					</th>
					<td><input type="text" name="description" id="description" maxlength="140" size="50"></td>
				</tr>
				<tr>
					<th>
						<input type="hidden" name="orderedQty">
						<input type="hidden" name="orderNo">
						 
					</th>
					<td><input type="button" name="saveTicket" value="티켓정보저장" onclick="saveTicketInfo()"></td>
				</tr>
			</table>
			
		</td>
	</tr>
	<?php } ?>	
	<tr>
		<td colspan=2>
		</td>
	</tr>
	<?php
 
		
		if(mysql_num_rows($kitQueryresult) > 0) {
	?>
	<tr>
		<td  colspan="2" style="font-size: 20px; font-weight:bold">
			 키트전달정보
		</td>
	</tr> 
	<?php
			 
			$kitTicketNo = "";
			$kitfullName = "";
			$kitbaid ="";
			$kitcontactNo = "";
			$kitdescription = "";
			$kitcreator = "";
			$kitcreatedDate = "";
			
			while($kitList = mysql_fetch_assoc($kitQueryresult)){
				$kitTicketNo .= $kitList[ticketNo]." / ";
				$kitfullName = $kitList[fullName];
				$kitbaid = $kitList[baid];
				$kitcontactNo = $kitList[contactNo];
				$kitdescription = $kitList[description];
				$kitcreator =$kitList[creator];
				$kitcreatedDate =$kitList[createdDate];

			}
	?>
	<tr>
		<td align='center'>

			<table border="0" cellspacing="1" cellpadding="2" class="IN">
				<tr>
					<th>
						티켓번호 
					</th>
					<td><?echo $kitTicketNo?></td>
				</tr>
				<tr>
					<th>
						회원성명 
					</th>
					<td><?echo $kitfullName?></td>
				</tr>
				<tr>
					<th>
						회원번호 
					</th>
					<td><?echo $kitbaid?></td>
				</tr>
				<tr>
					<th>
						연락처 
					</th>
					<td><?echo $kitcontactNo?></td>
				</tr>
				<tr>
					<th>
						기타내용 
					</th>
					<td><?echo $kitdescription?></td>
				</tr>
				<tr>
					<th>
						키트전달직원 
					</th>
					<td><?echo $kitcreator?></td>
				</tr>
				<tr>
					<th>
						키트전달일시
					</th>
					<td><?echo $kitcreatedDate?></td>
				</tr>
			</table>
			
		</td>
	</tr>
<?php }?>	
</table>
</form>
</body>
</html>
<?
mysql_close($connect);
?>