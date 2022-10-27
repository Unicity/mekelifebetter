<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "../admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";

	$str_title = "UnclaimedCommissionList";

	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	//header( "Content-Description: orion70kr@gmail.com" );
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
			"218" => "현대증권", 
			"090" => "카카오뱅크" 
		);
		return $bankCodes[$code];
	}

	function right($value, $count){
		$value = substr($value, (strlen($value) - $count), strlen($value));
		return $value;
	}

	function left($string, $count){
		return substr($string, 0, $count);
	}

	$s_status			= str_quote_smart(trim($s_status));
	$qry_str			= str_quote_smart(trim($qry_str));
	$page					= str_quote_smart(trim($page));
//	$con_sort			= str_quote_smart(trim($con_sort));
	$con_order		= str_quote_smart(trim($con_order));
	$idxfield				= str_quote_smart(trim($idxfield));

	if ($con_order == "con_a") {
		$order = "asc";
	} else {
		$order = "desc";
		$con_order = "con_d";
	}

	 
//	if (empty($con_sort)) {
		$con_sort = "CommissionDate";
//	}

	if (empty($con_order)) {
		$order = "desc";
	}

	if (empty($idxfield)) {
		$idxfield = "0";
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
		
	} 

//	$query2 = "select * from unclaimedCommission where 1 = 1  ".$que."order by ".$con_sort." ".$order ;
	$query2 = "select unclaimedCommission.*, tb_distSSN.dist_id, tb_distSSN.government_id from unclaimedCommission left outer join tb_distSSN on unclaimedCommission.id = tb_distSSN.dist_id where 1 = 1  ".$que."order by ".$con_sort." ".$order ;


	$result2 = mysql_query($query2);

	$query_total = "SELECT SUM(Amount) FROM unclaimedCommission where 1 = 1  ".$que;
	$result_total = mysql_query($query_total);
	$row = mysql_fetch_array($result_total);
	$total_amount = number_format($row[0]);


?>
<!--<font size=3><b>미지급 커미션 리스트</b></font> <br>-->
<style>
.xlGeneral {
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:\@;
	text-align:Left;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;
}
</style>
<br>
출력 일자 : [<?=date("Y년 m월 d일")?> ]
<br>
Total Amount : [<?=$total_amount?> ]
<br>
<br>
<table border="1">
	<tr>
		<td align='center' bgcolor='#F4F1EF'>커미션날짜</td>
		<td align='center' bgcolor='#F4F1EF'>회원번호</td>
		<td align='center' bgcolor='#F4F1EF'>회원이름</td>
		<td align='center' bgcolor='#F4F1EF'>은행코드</td>
		<td align='center' bgcolor='#F4F1EF'>계좌번호</td>
		<td align='center' bgcolor='#F4F1EF'>금액</td>
		<td align='center' bgcolor='#F4F1EF'>에러</td>
		<td align='center' bgcolor='#F4F1EF'>New 은행코드</td>
		<td align='center' bgcolor='#F4F1EF'>New 계좌번호</td>
		<td align='center' bgcolor='#F4F1EF'>New 예금주명</td>
		<td align='center' bgcolor='#F4F1EF'>등록일자</td>
		<td align='center' bgcolor='#F4F1EF'>처리상태</td>
		<td align='center' bgcolor='#F4F1EF'>최근업데이트</td>
		<td align='center' bgcolor='#F4F1EF'>주민번호</td>
		<td align='center' bgcolor='#F4F1EF'>생년월일</td>
		<td align='center' bgcolor='#F4F1EF'>일치여부</td>
	 
	</tr>
<?
	while($obj = mysql_fetch_object($result2)) {
			
		$date_c = date("Y-m-d", strtotime($obj->CommissionDate));
		$date_cr = date("Y-m-d", strtotime($obj->CreatedDate));
		$date_l = date("Y-m-d", strtotime($obj->LastUpdateDate));
		$ssn = decrypt($key, $iv, $obj->government_id);
		$dob = $obj->dob;
		if($date_l =='1970-01-01'){
			$date_l = "";
		}
		if ($obj->status == "10") {
			$status = "신규등록";
		} else if ($obj->status == "20") {
			$status = "DSC처리완료";
		} else if ($obj->status == "30") {
			$status = "완료";
		} else if ($obj->status == "40") {
			$status = "반려";
		} 

		$id	= $obj->id;
?>
	<tr style='border-collapse:collapse;table-layout:fixed;'>
		<td><?php echo $date_c ?></td>
		<td class="xlGeneral"><?echo $id ?></td>
		<td><?echo $obj->memberName ?></td>
		<td><?echo getBankCode($obj->BankCode)."(".$obj->BankCode.")"?></td>
		<td class="xlGeneral"><?echo decrypt($key, $iv, $obj->AccountNo)?></td>
		<td class="xlGeneral"><?echo $obj->Amount?></td>
		<td class="xlGeneral"><?echo getErrorCode($obj->errorCode)?></td>
		<td class="xlGeneral"><?echo $obj->newBankCode?></td>
		<td class="xlGeneral"><?echo decrypt($key, $iv, $obj->newAccountNo)?></td>
		<td><?echo $obj->newAccountHolder?></td>
		<td><?echo $date_cr?></td>
		<td><?echo $status?></td>
		<td><?echo $date_l?></td>
		<td class="xlGeneral"><?echo "".$ssn?></td>
		<td class="xlGeneral"><?echo $dob?></td>
		<td>
		<?
		$ssn_dob = substr($ssn, 0,6);
		if ($ssn_dob == $dob) echo '일치';
		else echo '불일치';
		?>
		</td>
	</tr>
<?
	}
?>

</table>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
