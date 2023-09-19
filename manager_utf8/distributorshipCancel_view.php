<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	$s_adm_id = str_quote_smart_session($s_adm_id);


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


	function makeCode ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[code]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}

	function makeCodeAsName ($parent)  { 

		$sqlstr = "SELECT * FROM tb_code where parent='".$parent."' order by code"; 

		$result = mysql_query($sqlstr);
		$total 	= mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++)	{  	//  start 에서 scale 까지 만

			if($i< $total )	{ 								// 전체 자료 개수까지만 출력

				mysql_data_seek($result,$i);
				$row = mysql_fetch_array($result);		
				print("<option value='$row[name]' style='color:352000'>$row[name]</option>\n");

			}
		}
	}

	$mode						= str_quote_smart(trim($mode));
	$member_no			= str_quote_smart(trim($member_no));
	$member_kind		= str_quote_smart(trim($member_kind));
	$idxfield				= str_quote_smart(trim($idxfield));
	$idx						= str_quote_smart(trim($idx));
	$qry_str				= str_quote_smart(trim($qry_str));
	$con_sort				= str_quote_smart(trim($con_sort));
	$con_order			= str_quote_smart(trim($con_order));
	$r_status				= str_quote_smart(trim($r_status));
	$r_memberkind		= str_quote_smart(trim($r_memberkind));
	$r_join_kind		= str_quote_smart(trim($r_join_kind));
	$r_active_kind	= str_quote_smart(trim($r_active_kind));
	$r_couple				= str_quote_smart(trim($r_couple));
	$from_date			= str_quote_smart(trim($from_date));
	$to_date				= str_quote_smart(trim($to_date));
	$autoshipYn				= str_quote_smart(trim($autoshipYn));


	$query = "select * from distributorshipCancel where no = '".$member_no."'";
	
#	echo $query;	

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	

	$member_no = $list[id];
	$no = $list[no];
	$userName = $list[UserName];
	$address = $list[address];
	$addressDetail = $list[addressDetail];
	$date_sc = $list[confirm_date];
	$confirm_ma = $list[confirm_ma];

	$phone = $list[Phone];
	$phone1 = substr($phone, 0, 3);
	$phone2 = substr($phone, 3, 4);
	$phone3 = substr($phone, 7, 4);
	$birthDay = $list[birthDay];
	
	$birthDay1 = substr($birthDay, 0, 4);
	$birthDay2 = substr($birthDay, 4, 2);
	$birthDay3 = substr($birthDay, 6, 2);

	$distributorshipCard = $list[distributorshipCard];
	$distributorshipNote = $list[distributorshipNote];
	$autoshipYn = $list[autoshipYn];
	$mainsubchk = $list[mainsubchk];
	
	
	if($distributorshipCard == 'Y'){
		$cardYN = '반납';
	}else if($distributorshipCard == 'N'){
		$cardYN = '분실/훼손';
	}else if($distributorshipCard == 'E'){
		$cardYN = '기타';
	}else if($distributorshipCard == null){
		$cardYN =' ';
	}
	
	if($distributorshipNote == 'Y'){
		$noteYN = '반납';
	}else if($distributorshipNote == 'N'){
		$noteYN = '분실/훼손';
	}else if($distributorshipNote == 'E'){
		$noteYN = '기타';
	}else if($distributorshipNote == ' '){
		$noteYN = ' ';
	}
	
	if($autoshipYn == 'Y'){
		$autoYN = '동의';
	}else if($autoshipYn == 'N'){
		$autoYN = '오토쉽미신청자';
	}else if($autoshipYn == null){
		$autoYN = ' ';
	}
	
	if($mainsubchk == 1){
		$mainsub = "주";
	}else if($mainsubchk == 2){
		$mainsub = "부";
	}
	
	
	$cancelReason = $list[cancelReason];
	$reject_date = $list[reject_date];
	$reject_ma = $list[reject_ma];
	$wait_date = $list[wait_date];
	$wait_ma = $list[wait_ma];
	$print_date = $list[print_date];
	$print_ma = $list[print_ma];
	$reg_status = $list[reg_status];
	$memo = $list[memo];
	$purposeSelect = $list[purposeSelect];
	$selectText = $list[selectText];
	$faxORdsc = $list[faxORdsc];
	$faxNum = $list[faxNum];
	$dscChk = $list[dscChk];
	$purpose = $list[purpose];
	$memberReg = $list[memberReg];
	
	if($dscChk == "D0"){
		$dscChk = "서울";
	}else if ($dscChk == "D1"){
		$dscChk = "인천";
	}else if ($dscChk == "D2"){
		$dscChk = "안산";
	}else if ($dscChk == "D3"){
		$dscChk = "대전";
	}else if ($dscChk == "D4"){
		$dscChk = "원주";
	}else if ($dscChk == "D5"){
		$dscChk = "대구";
	}else if ($dscChk == "D6"){
		 $dscChk = "광주";
	}else if ($dscChk == "D7"){
		 $dscChk = "부산";
	}

	
	if ($reject_date != null) {
		$date_sr = date("Y-m-d [H:i]", strtotime($reject_date));
	} else {
		$date_sr = "";
	}
	
	if ($wait_date != null) {
		$date_sw = date("Y-m-d [H:i]", strtotime($wait_date));
	} else {
		$date_sw = "";
	}
	
	if ($print_date != null) {
		$date_sp = date("Y-m-d [H:i]", strtotime($print_date));
	} else {
		$date_sp = "";
	}

	
	//if ($member_no > 160617) {

		$query = "select check_kind, name, jumin1, jumin2, chkdate from tb_check_log
								where jumin2 = '".$DI."'
									and flag = 'Y' and check_kind in ('C','M','X','I') order by check_no desc limit 2";

					//echo $query;

	//} else {

	//	$query = "select check_kind, name, jumin1, jumin2, chkdate from tb_check_log
	//						where jumin1 = '".$reg_jumin1."'
	//							and jumin2 = '".$reg_jumin2."'
	//							and flag = 'Y' and check_kind in ('C','M','X') order by check_no desc ";

	//	}



	$result_chk = mysql_query($query);
	
	$str_chk_type = "";

	while($row_chk = mysql_fetch_array($result_chk)) {
		$check_kind = $row_chk[check_kind];
		$chkdate = $row_chk[chkdate];
		$chkdate = date("[H:i]", strtotime($chkdate));
		if ($check_kind == "C") $str_check_kind = "신용카드";
		if ($check_kind == "M") $str_check_kind = "모바일";
		if ($check_kind == "X") $str_check_kind = "공인인증서";
		if ($check_kind == "I") $str_check_kind = "아이핀";

		$str_chk_type = $str_chk_type.$str_check_kind." ".$chkdate."<br>";

	}


?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function init() {
		document.frm_m.hpho1.value = "<?echo $hpho1?>";
		document.frm_m.pho1.value = "<?echo $pho1?>";
<? 	if ($member_kind == "D") {?>
		document.frm_m.account_bank.value = "<?echo $account_bank?>";
<?	}?>
	}

	

	function goMemo(kind) {
		var url = "distributorshipCancel_memo.php?memo_kind="+kind+"&member_no="+document.frm_m.no.value;
		NewWindow(url, "memo_page", '600', '250', "no");
	}


	function NewWindow(mypage, myname, w, h, scroll) {
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',noresize'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
	}

	function goBack() {
		document.frm_m.target = "frmain";
		document.frm_m.action="distributorshipCancel_admin.php";
		                        
		document.frm_m.submit();
	}

//-->

	function goIn(){
		
		if(confirm("수정하시겠습니까?")){   
			document.frm_m.action = "distributorshipCancel_confirm.php";
			document.frm_m.submit();
		}
	}	

	function goAutoship(val) {

		/*
		frm_m.target = "frmain";
		document.frm_m.action="deleteAutoship.php";
		document.frm_m.submit();
		*/
		if(confirm("해지까지 시간은 약 10초 정도 소요 됩니다.오토쉽을 해지 하시겠습니까?")){  
			var param = {memberNo : val};

			$.ajax({
					url: "deleteAutoship.php",
					async : false,
					dataType : "json",
					data:param,
					type:"POST",
					success	: function(result) {                
						if(result.resultVal=='ok'){
							alert("오토쉽 해지 완료");
						}
					}
			});	
		}
		
	}
</SCRIPT>
</HEAD>
<body  onLoad="init();">

<?php include "common_load.php" ?>

<form name="frm_m" method="post">

	<input type="hidden" name="flag" value="update">
	<input type="hidden" name="no" value="<?echo $no?>">
	<input type="hidden" name="s_adm_name" value="<?echo $s_adm_name?>">
	<input type="hidden" name="memberNo" value="<?echo $member_no?>">
	<TABLE cellspacing="0" cellpadding="10" class="TITLE">
		<TR>
			<TD align="left"><B>디스트리뷰터쉽 해지 관리</B></TD>
			<TD align="right" width="300" align="center" bgcolor=silver>
				<input type="button" onClick="goIn();" value="수정" name="btn3">
				<input type="button" onClick="goBack();" value="목록" name="btn4">
				<?
					if($autoshipYn=='Y'){
				?>
				<input type="button" onClick="goAutoship('<?echo $member_no?>');" value="오토쉽 해지" name="btn4">
				<?
					}
				?>

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
					주,부 사업자 	
				</th>
				<td><?php echo $mainsub ?></td>	
			</tr>
			<tr>
				<th>
					회원성명 :
				</th>
				<td><?echo $userName?></td>
			</tr>
			<tr>
				<th>
					회원번호 :
				</th>
				<td><?echo $member_no?></td>
			</tr>
			<tr>
				<th>
					주소 :
				</th>
				<td><?echo $address?>&nbsp;<?php echo $addressDetail?></td>
			</tr>
			<tr>
				<th>
					생년월일 :
				</th>
				<td><?echo $birthDay1?>-<?echo $birthDay2?>-<?echo $birthDay3?></td>
			</tr>
			<tr>
				<th>
					연락처 :
				</th>
				<td><?echo $phone1?>-<?echo $phone2?>-<?echo $phone3?></td>
			</tr>
		<!--  	
			<tr>
				<th>
					오토쉽 계약여부 :
				</th>
				<td><?echo $autoYN?></td>
			</tr>
		-->	
			<tr>
				<th>
					회원등록증 :
				</th>
				<td><?echo $cardYN?></td>
			</tr>
			<tr>
				<th>
					회원수첩 :
				</th>
				<td><?echo $noteYN?></td>
			</tr>
			<tr>
				<th>
					해지사유 :
				</th>
				<td><?echo $cancelReason?></td>
			</tr>
			<?php if($memberReg =='Y'){?>
			<tr>
				<th>
					회원 등록서 제출처  :
				</th>
				<td>
					<? if($purposeSelect == "P0"){
							echo "국민보험공단";
						}else if($purposeSelect == "P1"){
							echo "고용보험";
						}else if($purposeSelect == "P2"){
							echo "은행";
						}else if($purposeSelect == "P3"){
							echo "국민연금공단";
						}else if($purposeSelect == "P4"){
							echo "기타 (".$selectText.")";
						}
						
					?>
					
				</td>
			</tr>
			<tr>
				<th>
					회원 등록서 발급 목적 :
				</th>
				<td><?echo $purpose?></td>
			</tr>
			<tr>
				<th>
					수령방법 :
				</th>
				<td><?
						if($faxORdsc == "Y"){
							echo "팩스 (".$faxNum.")";
						}else if($faxORdsc == "N"){
							echo "dsc (".$dscChk.")";
						}
					?>
				</td>
			</tr>
			<?php }?>
		</table>
		
		<table border="0" width='100%' cellspacing="1" cellpadding="1">
			<tr>
				<th bgcolor="#DDDDDD" width="137">
					신청 처리 정보:
				</th>
				<td bgcolor="#EEEEEE">
					<table border=0>
						<tr>
							<td width=200><b>출력일</b> : <?echo $date_sp?></td>
							<td width=150><b>출력자</b> : <?echo $print_ma?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td><b>완료일</b> : <?echo $date_sc?></td>
							<td><b>완료자</b> : <?echo $confirm_ma?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td><b>보류일</b> : <?echo $date_sw?></td>
							<td><b>보류자</b> : <?echo $wait_ma?></td>
							<td>
								<?php if ($reg_status != "4"){ ?>
									<input type="button" value="보류 내용 입력" onClick="goMemo('w');">
								<?php } ?>
							</td>

						</tr>
						<tr>
							<td><b>거부일</b> : <?echo $date_sr?></td>
							<td><b>거부자</b> : <?echo $reject_ma?></td>
							<td>
								<?php if ($reg_status != "4"){ ?>
									<input type="button" value="거부 내용 입력" onClick="goMemo('r');">
								<?php } ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?	#if ($s_flag == "1") {?>
			<tr>
				<th bgcolor="#DDDDDD">
					회원 처리 사항:
				</th>
				<td bgcolor="#EEEEEE" style="min-height:25px">
					<?php if ($reg_status == "4"){ ?>
						완료 &nbsp;&nbsp; <span style="font-size:11px;; color:#990066">* 수정 필요시 관리자에게 문의하여 주세요</span>
						<input type="hidden" name="reg_status" value="<?echo $reg_status?>">
					<?php }else{ ?>
					<select name="reg_status">
						<option value = "1" <?if ($reg_status == "1") echo "selected";?>>신청 (본인확인)</option>
						<option value = "2" <?if ($reg_status == "2") echo "selected";?>>신청</option>
						<option value = "3" <?if ($reg_status == "3") echo "selected";?>>출력</option>
						<option value = "4" <?if ($reg_status == "4") echo "selected";?>>완료</option>
						<option value = "8" <?if ($reg_status == "8") echo "selected";?>>보류</option>
						<option value = "9" <?if ($reg_status == "9") echo "selected";?>>거부</option>
					</select>&nbsp; <!--최고 관리자로 접속하셨을 경우만 변경 가능 합니다.-->
					<?php } ?>
				</td>
			</tr>
			<?	#} else { ?>
				<!--<input type="hidden" name="reg_status" value="<?echo $reg_status?>">-->
			<?	#} ?>
			<tr>
				<th bgcolor="#DDDDDD">
					보류 또는 거부사유:
				</th>
				<td bgcolor="#EEEEEE">
					<?echo nl2br($memo)?>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<input type="hidden" name="mode" value="mod">
<input type="hidden" name="zip" value="">
<input type="hidden" name="del_zip" value="">
<input type="hidden" name="email" value="">
<input type="hidden" name="interest" value="">
<input type="hidden" name="member_no" value="<?echo $member_no?>">
<input type="hidden" name="member_kind" value="<?echo $member_kind?>">
<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
<input type="hidden" name="idx" value="<?echo $idx?>">
<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="con_sort" value="<?echo $con_sort?>">
<input type="hidden" name="con_order" value="<?echo $con_order?>">
<input type="hidden" name="r_status" value="<?echo $r_status?>">
<input type="hidden" name="r_memberkind" value="<?echo $r_memberkind?>">
<input type="hidden" name="r_join_kind" value="<?echo $r_join_kind?>">
<input type="hidden" name="r_active_kind" value="<?echo $r_active_kind?>">
<input type="hidden" name="r_couple" value="<?echo $r_couple?>">
<input type="hidden" name="from_date" value="<?echo $from_date?>">
<input type="hidden" name="to_date" value="<?echo $to_date?>">

</form>
</body>
</html>
<?
mysql_close($connect);
?>