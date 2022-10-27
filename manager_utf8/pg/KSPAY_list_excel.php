<?
	header( "Content-type: application/vnd.ms-excel" ); 
	header( "Content-Disposition: attachment; filename=$from_date-$to_date.xls" ); 
	header( "Content-Description: PHP4 Generated Data" ); 

	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";

	function card_XXX($str){

		$slen = strlen($str);
		
		$tmp = substr($str,0,12)."XXXX";

		return $tmp;
	}

	function date_format2($str){

		$slen = strlen($str);
		if ($slen <> 0) {
			$tmp = substr($str,0,4)."/".substr($str,4,2)."/".substr($str,6,2);
		} else {
			$tmp = "";
		}
		return $tmp;
	}

	function time_format($str){

		$slen = strlen($str);
		if ($slen <> 0) {
			$tmp = substr($str,0,2).":".substr($str,2,2);
		} else {
			$tmp = "";
		}
		return $tmp;
	}

	if (!empty($from_date)) {
		$que = " and TradeDate >= '$from_date' ";		
	}

	if (!empty($to_date)) {
		
		$qry_date = "select date_sub('$to_date', interval -1 day) next_date from seq_kspay ";
		$res_date = mysql_query($qry_date);
		$list = mysql_fetch_array($res_date);
		$next_date = $list[next_date];

		$next_date = str_replace("-","",$next_date);
				
		$que = $que." and TradeDate < '$next_date' ";		
#		$que = $que." and date_sub(inputdate, interval 1 day) < '$to_date' ";		
	}

	if ($s_flag != "1") {
		$que = $que." and inputid = '$s_adm_id' ";				
	}
		
	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = $que." and ordernumber like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = $que." and CardNo like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = $que." and inputid like '%$qry_str%' ";
		} 
		
		$query = "select * from tb_kspay where ks_id >= 0 ".$que. " order by ks_id desc";

	} else {
		$query = "select * from tb_kspay where ks_id >= 0 ".$que. " order by ks_id desc";
	}
			
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?echo $g_site_title?></title>
</head>
<BODY bgcolor="#FFFFFF">
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="1" bgcolor="silver">
<TR height="25">
	<TH>로그인ID</TH>
	<TH>거래번호</TH>
	<TH>주문번호</TH>
<!--
	<TH>주문자명</TH>
	<TH>상품명</TH>
-->	
	<TH>카드종류</TH>
	<TH>카드번호</TH>
	<TH>할부</TH>
	<TH>금액</TH>
	<TH>승인번호</TH>
	<TH>구분</TH>
	<TH>승인일시</TH>
	<TH>취소일시</TH>
	<TH>이자</TH>
	<TH>오류 또는 거절 사유</TH>
<!--	
	<TH>처리일</TH>
-->
</TR>     
<?
	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {
				
		$date_s = date("Y-m-d", strtotime($row[inputdate]));
	
?>					
<TR align="center" height="25" bgcolor="white">                    
	<TD><?echo $row[inputid]?></TD>
	<TD>[<font color=red><?echo $row[TransactionNo]?></font>]</TD>
	<TD>[<font color=red><?echo $row[ordernumber]?></font>]</TD>
	<TD><? if ($row[Status] == "O") {?><?echo $row[Message1]?><? } ?></TD>
	<TD><?echo card_XXX($row[CardNo])?></TD>
	<TD><?echo $row[installment]?></TD>
	<TD align="right"><?echo number_format($row[amount])?></TD>
	<TD><?echo $row[AuthNo]?></TD>
	<TD>
<?		if ($row[Status] == "O") {

			if ($row[CStatus] == "O") {	
				echo "취소승인";
			} else if ($row[CStatus] == "X") {
				echo "취소거절";
			} else {
				echo "승인";
			}

		} else {
				echo "거절";
		}
?>
	</TD>
	<TD><?echo date_format2($row[TradeDate])?> <?echo time_format($row[TradeTime])?></TD>
	<TD><?echo date_format2($row[CTradeDate])?> <?echo time_format($row[CTradeTime])?></TD>
	<TD>
<? 
		if ($row[InterestType] == "2") { 
			echo "무이자"; 
		} else { 
			echo "일반"; 
		}
?>
	</TD>
<!--
	<TD><?echo $date_s?></TD>
-->
	<TD>
<?	if ($row[Status] == "X") { ?>
		<?echo $row[Message1]?>, <?echo $row[Message2]?>
<?	} ?>
	</TD>
</TR>
<?
	}
?>
</TABLE>
</form>
</body>
</html>
<?
mysql_close($connect);
?>