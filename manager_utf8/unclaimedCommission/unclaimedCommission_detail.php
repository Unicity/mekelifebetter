<?php
session_start();
?>

<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";
	include "../inc/common_function.php";

	$s_flag = str_quote_smart_session($s_flag);

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
	function getErrorCode($code){

		$codeArray = explode("-", $code);
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
		$errorMsg ="";
	
		foreach($codeArray as $code){
			$errorMsg .= $code." ".$errorCodes[$code]."<br>";
		}
		return $errorMsg;
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
			"064" => "산림조합중앙회", 
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
			"090" => "카카오뱅크", 
			"089" => "케이뱅크", 
			"264" => "키움증권", 
			"092" => "토스뱅크", 
			"270" => "하나대투증권", 
			"081" => "하나은행", 
			"262" => "하이투자증권", 
			"027" => "한국씨티은행", 
			"243" => "한국투자증권", 
			"269" => "한화증권", 
			"218" => "KB증권"
		);
		return $bankCodes[$code];
	}
 	$member_no = str_quote_smart(trim($member_no));
 	$commissionDate = str_quote_smart(trim($commissionDate));
	 
 	logging($s_adm_id,'view unclaimed Commission detail '.$member_no);

	$query = "select * from unclaimedCommission where id = '".$member_no."' and CommissionDate = '".$commissionDate."'";
	
 	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$ssnQuery = "select government_id, create_date from tb_distSSN where dist_id = '".$member_no."'";
	$ssnResult = mysql_query($ssnQuery);
 

	$commissionDate =  date("Y-m-d", strtotime($list[CommissionDate]));
	$member_no = $list[id];
	$userName = $list[memberName];
	$bankCode = $list[BankCode];
	$AccountNo = decrypt($key, $iv, $list[AccountNo]) ;
	$AccountHolder = $list[memberName];
	$amount = $list[Amount];
	$newBankCode = $list[newBankCode];
	$newAccountNo = decrypt($key, $iv, $list[newAccountNo]);
	$newAccountHolder = $list[newAccountHolder];
	
	$status = $list[status];
	$errorCode = $list[errorCode];
	$comment =  $list[comment];
	$readonly ="";
	if ($status == '30') {
		$readonly = "readonly"; 
	}
?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="../inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function init() {
		var status = '<?echo $status?>';

		if (status == '10') {
	 	 	document.getElementById('bankCode').value = '<?echo $bankCode?>';
		} else {
			document.getElementById('bankCode').value = '<?echo $newBankCode?>';
		}

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
		document.frm_m.action="unclaimedCommission_list.php";
		                        
		document.frm_m.submit();
	}
	function updateInfo(val) {
		var comment = document.frm_m.comment.value + '<br>' +  '<?php echo $comment?>';
		document.frm_m.status.value = val;
		document.frm_m.comment.value = comment;
		document.frm_m.commissionDate.value = '<?php echo $commissionDate?>';
		document.frm_m.member_no.value = '<?php echo $member_no?>';
		document.frm_m.target = "frmain";
		document.frm_m.action="unclaimedCommission_update.php";
		document.frm_m.submit();
	}
//-->
</SCRIPT>
</HEAD>
<body  onLoad="init();">
<form name="frm_m" method="post">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>미지급 커미션 관리</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>

		<input type="button" onClick="goBack();" value="목록" name="btn4">

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
						커미션 날짜 
					</th>
					<td><?echo $commissionDate?></td>
				</tr>
				<tr>
					<th>
						회원성명 
					</th>
					<td><?echo $userName?></td>
				</tr>
				<tr>
					<th>
						회원번호 
					</th>
					<td><?echo $member_no?></td>
				</tr>
				<tr>
					<th>
						은행코드 
					</th>
					<td><?echo getBankCode($bankCode)?></td>
				</tr>
				<tr>
					<th>
						계좌번호 
					</th>
					<td><?echo $AccountNo?></td>
				</tr>
				<tr>
					<th>
						계좌주명 
					</th>
					<td><?echo $AccountHolder?></td>
				</tr>
				<tr>
					<th>
						금액(원) 
					</th>
					<td><?echo number_format($amount)?> 원</td>
				</tr>

				<tr>
					<th>
						에러코드 
					</th>
					<td><?echo getErrorCode($errorCode)?></td>
				</tr>
				<tr>
					<th>
						 Comment
					</th>
					<td><?echo nl2br($comment);?></td>
				</tr>
				<tr>
					<th>
						등록된 주민번호
					</th>
					<td>
				<?php
				while($obj = mysql_fetch_object($ssnResult)) {
					 
					$ssn_no = decrypt($key, $iv, $obj->government_id);
					$ssn_no = substr($ssn_no,0,6).'-'.substr($ssn_no,6,1).'****** ';
					$createddate_s = date("Y-m-d H:i", strtotime($obj->create_date));
				 	echo $ssn_no.'('.$createddate_s.')'.'<br>';
				}
				?>
					</td>
				</tr>
			</table>
			
		</td>
	</tr>
	<tr>
		<td colspan=2>
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="1" cellpadding="2" class="IN">
				<tr>
					<th>
						새로운 은행코드 
					</th>
					<td><select name="bankCode" id="bankCode">
							<option value="">거래은행 선택</option>
										<option value='060'>BOA은행</option>
										<option value='263'>HMC투자증권</option>
										<option value='054'>HSBC은행</option>
										<option value='292'>LIG투자증권</option>
										<option value='289'>NH투자증권</option>
										<option value='023'>SC제일은행</option>
										<option value='266'>SK증권</option>
										<option value='039'>경남은행</option>
										<option value='095'>경찰청</option>
										<option value='034'>광주은행</option>
										<option value='261'>교보증권</option>
										<option value='004'>국민은행</option>
										<option value='003'>기업은행</option>
										<option value='011'>농협중앙회</option>
										<option value='012'>농협회원조합</option>
										<option value='031'>대구은행</option>
										<option value='267'>대신증권</option>
										<option value='238'>대우증권</option>
										<option value='279'>동부증권</option>
										<option value='287'>메리츠종합금융증권</option>
										<option value='230'>미래에셋증권</option>
										<option value='032'>부산은행</option>
										<option value='064'>산림조합중앙회</option>
										<option value='002'>산업은행</option>
										<option value='240'>삼성증권</option>
										<option value='050'>상호저축은행</option>
										<option value='045'>새마을금고연합회</option>
										<option value='007'>수협중앙회</option>
										<option value='291'>신영증권</option>
										<option value='278'>신한금융투자</option>
										<option value='088'>신한은행</option>
										<option value='048'>신협중앙회</option>
										<option value='056'>알비에스은행</option>
										<option value='005'>외환은행</option>
										<option value='020'>우리은행</option>
										<option value='247'>우리투자증권</option>
										<option value='071'>우체국</option>
										<option value='209'>유안타증권</option>
										<option value='280'>유진투자증권</option>
										<option value='265'>이트레이드증권</option>
										<option value='037'>전북은행</option>
										<option value='035'>제주은행</option>
										<option value='090'>카카오뱅크</option>
										<option value='089'>케이뱅크</option>
										<option value='264'>키움증권</option>
										<option value='092'>토스뱅크</option>
										<option value='270'>하나대투증권</option>
										<option value='081'>하나은행</option>
										<option value='262'>하이투자증권</option>
										<option value='027'>한국씨티은행</option>
										<option value='243'>한국투자증권</option>
										<option value='269'>한화증권</option>
										<option value='218'>KB증권</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>
						새로운 계좌번호 
					</th>
					<td><input type="text" name="newAccountNo" maxlength="30" value="<? echo $status=='10' ? $AccountNo : $newAccountNo?>" <?echo $readonly;?> ></td>
				</tr>
				<tr>
					<th>
						계좌주명 
					</th>
					<td><input type="text" name="newAccountHolder" maxlength="30" value="<?echo $status=='10' ? $userName : $newAccountHolder ?>" <?echo $readonly;?> ></td>
				</tr>
				<tr>
					<th>
						Comment 추가 
					</th>
					<td><input type="text" name="comment" length="100" maxlength="100" <?echo $readonly;?>></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align=center>
			<?php 
				if( $status != "30") {
				if ($s_flag == "3" || $s_flag == "1" || $s_flag == "5") { ?>
					<input type="button" name="dscSave" value="자료수정(CS/DSC용)" onclick="updateInfo(20)"> 
			<?php } ?>
			<?php 
				if ($s_flag == "8" || $s_flag =="1"|| $s_flag =="9") { ?>
					<input type="button" name="finComplete" value="지급완료" onclick="updateInfo(30)"> 
					<input type="button" name="finReject" value="반려" onclick="updateInfo(40)">
			<?php } } ?>
			<input type="hidden" name="status" value="">
			<input type="hidden" name="commissionDate" value="">
			<input type="hidden" name="member_no" value="">
		</td>

	</tr>
</table>
</form>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>
<?
mysql_close($connect);
?>