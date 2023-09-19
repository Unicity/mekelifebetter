<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2015-05-26
	// 	Last Update : 2015-05-26
	// 	Author 		: Park, ChanHo
	// 	History 	: 2015-05-26 by Park ChanHo 
	// 	File Name 	: admin_group_view.php
	// 	Description : 오토쉽 보기 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	function sql_result_array($handle,$row) {
		$count = mysql_num_fields($handle);
		for($i=0;$i<$count;$i++){
			$fieldName = mysql_field_name($handle,$i);
			$ret[$fieldName] = mysql_result($handle,$row,$i);
			//echo $fieldName . "=" . $ret[$fieldName] . "<BR>";
		}
		return $ret;
	}
	
	//echo $mode;


	$mode					=	str_quote_smart(trim($mode)); 
	$old_state		=	str_quote_smart(trim($old_state)); 
	$req_state		=	str_quote_smart(trim($req_state)); 
	$memo					=	str_quote_smart(trim($memo)); 
	$seq					=	str_quote_smart(trim($seq)); 
	$page					=	str_quote_smart(trim($page)); 
	$sort					=	str_quote_smart(trim($sort)); 
	$order				=	str_quote_smart(trim($order)); 
	$idxfield			=	str_quote_smart(trim($idxfield)); 
	$qry_str			=	str_quote_smart(trim($qry_str)); 
	$sel_goods		=	str_quote_smart(trim($sel_goods)); 
	
	$auto_no				=	str_quote_smart(trim($auto_no)); 
	$old_req_date		=	str_quote_smart(trim($old_req_date)); 
	$req_type				=	str_quote_smart(trim($req_type)); 


	if ($mode == "mod") {
		
		if ($old_state <> $req_state) {

			$query = "update tb_autoship_req set req_state = '$req_state', upddate = now(), memo = '$memo' where seq = '$seq' ";
			mysql_query($query);

			if ($old_state == "1") {
				$req_type = "신청처리완료";
			}

			if ($old_state == "3") {
				$req_type = "변경처리완료";
			}

			if ($old_state == "4") {
				$req_type = "철회처리완료";
			}

			if ($old_state == "2") {
				$req_type = "신청처리반려";
			}

			if ($old_state == "2") {
				$req_type = "신청처리반려";
			}

			if ($old_state == "5") {
				$req_type = "철회처리반려";
			}

			$query = "insert into tb_autoship_req_history (seq, req_date, res_date, req_type, req_admin_id, old_req_state, change_req_state) 
									values ('$seq', '$old_req_date', now(), '$req_type', '$s_adm_id', '$old_state','$req_state');";
			mysql_query($query);
		}
	}

	if ($mode == "mod_auto_no") {
		
		//echo "ssss";

		$query = "update tb_autoship_req set auto_no = '$auto_no' where seq = '$seq' ";
		mysql_query($query);

	}

	$query = "select * from tb_autoship_req where seq = '".$seq."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$mem_number = $list[mem_number];
	$auto_no = $list[auto_no];
	$mem_name = $list[mem_name];
	$zip = $list[zip];
	$addr = $list[addr];
	$addr_detail = $list[addr_detail];
	$rec_name = $list[rec_name];
	$rec_tel = $list[rec_tel];
	$email = $list[email];
	$pay_date = $list[pay_date];
	$pay_type = $list[pay_type];
	$m_type = $list[m_type];
	$mem_birth = $list[mem_birth];
	$bank_code = $list[bank_code];
	$bank_nm = $list[bank_nm];
	$bank_account = $list[bank_account];
	$ApprovalType = $list[ApprovalType];
	$TransactionNo = $list[TransactionNo];
	$TradeDate = $list[TradeDate];
	$TradeTime = $list[TradeTime];
	$IssCode = $list[IssCode];
	$AuthNo = $list[AuthNo];
	$card_nm = $list[card_nm];
	$card_num = $list[card_num];
	$card_exp_mm = $list[card_exp_mm];
	$card_exp_yy = $list[card_exp_yy];
	$card_chk_num = $list[card_chk_num];
	$auto_total_price = $list[auto_total_price];
	$auto_total_cv = $list[auto_total_cv];
	$req_state = $list[req_state];
	$upd_state = $list[upd_state];
	$cancel_state = $list[cancel_state];
	$regdate = $list[regdate];
	$upddate = $list[upddate];
	$memo = $list[memo];
	$cash_tf = $list[cash_tf];
	$cash_info = $list[cash_info];

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Frame-Options" content="deny" />
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	var req_state = "<?=$req_state?>";

	function js_list() {
		document.frm.target = "";
		document.frm.action="autoship_req_list.php";
		document.frm.submit();
	}

	function js_change_state() {
		
		if (req_state == "4") {
			document.frm.req_state.value = "5";
		} else {
			document.frm.req_state.value = "2";
		}
		document.frm.mode.value = "mod";
		document.frm.target = "";
		document.frm.action = "autoship_req_view.php";
		document.frm.submit();
	}
	
	function js_auto_no_update() {

		document.frm.mode.value = "mod_auto_no";
		document.frm.target = "";
		document.frm.action = "autoship_req_view.php";
		document.frm.submit();

	}

//-->
</SCRIPT>
</HEAD>
<BODY>

<?php include "common_load.php" ?>

<form name='frm' method='post'>
<input type="hidden" name="sel_req_state" value="<?=$sel_req_state?>">
<input type="hidden" name="sel_pay_type" value="<?=$sel_pay_type?>">
<input type="hidden" name="sel_pay_date" value="<?=$sel_pay_date?>">
<input type="hidden" name="idxfield" value="<?=$idxfield?>">
<input type="hidden" name="qry_str" value="<?=$qry_str?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" name="old_state" value="<?=$req_state?>">
<input type="hidden" name="old_req_date" value="<?=$regdate?>">
<input type="hidden" name="mode" value="">

<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>오토쉽 신청 관리</B></TD>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<!--<input type="button" onClick="goIn();" value="상태변경" name="btn3">-->
		<input type="button" onClick="js_list();" value="목록" name="btn4">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>회원번호 :</th>
	<td><?echo $mem_number?></td>
</tr>
<tr>
	<th>오토쉽번호 :</th>
	<td>
		<input type="text" name="auto_no" value="<?echo $auto_no?>">
		<? if ($auto_no) { ?>
		<input type="button" onClick="js_auto_no_update();" value="오토쉽번호 수정" name="btn5">
		<? } else { ?>
		<input type="button" onClick="js_auto_no_update();" value="오토쉽번호 등록" name="btn5">
		<? } ?>
	</td>
</tr>
<tr>
	<th>신청인 :</th>
	<td><?echo $mem_name?></td>
</tr>
<tr>
	<th>수령인 :</th>
	<td><?echo $rec_name?></td>
</tr>
<tr>
	<th>수령인 연락처 :</th>
	<td><?echo $rec_tel?></td>
</tr>
<tr>
	<th>이메일 :</th>
	<td><?echo $email?></td>
</tr>
<tr>
	<th>수령 주소 :</th>
	<td><?echo $zip?> <?echo $addr?> <?echo $addr_detail?></td>
</tr>
<tr>
	<th>신청인 생년월일 :</th>
	<td><?echo $mem_birth?></td>
</tr>
<tr>
	<th>결제일 :</th>
	<td><?echo $pay_date?> 일</td>
</tr>
<tr>
	<th>결제방법 :</th>
	<td>
	<?
			if ($pay_type == "bank") echo "계좌이체";
			if ($pay_type == "card") echo "신용카드";
	?>
	</td>
</tr>
<?
	if ($pay_type == "bank") {
?>
<tr>
	<th>계좌이체 은행 :</th>
	<td><?echo $bank_nm?></td>
</tr>
<tr>
	<th>계좌 번호 :</th>
	<td>
		<? $bank_account = decrypt($key, $iv, $bank_account); ?>
		<?echo $bank_account?>
	</td>
</tr>
<tr>
	<th>현금연수증 :</th>
	<td>
		<? if ($cash_tf == "Y") echo "신청" ?>
		<? if ($cash_tf == "N" || $cash_tf == "") echo "신청안함" ?>
	</td>
</tr>
<tr>
	<th>발행정보 :</th>
	<td>
		<? if ($cash_tf == "Y") { ?>
		<?=$cash_info?>
		<? } ?>
	</td>
</tr>
<?
	}
?>
<?
	if ($pay_type == "card") {
?>
<tr>
	<th>카드사 :</th>
	<td><?echo $card_nm?></td>
</tr>
<tr>
	<th>카드번호 :</th>
	<td>
		<? $card_num = decrypt($key, $iv, $card_num); ?>
		<?echo $card_num?>
	</td>
</tr>
<tr>
	<th>유효기간 :</th>
	<td>
		<?echo $card_exp_yy?> 년 <?echo $card_exp_mm?> 월 
	</td>
</tr>
<?
	}
?>

<tr>
	<th>상태 :</th>
	<td>
		<?
			if ($req_state == "0") echo "<b><font color='orange'>등록전취소</font></b>";
			if ($req_state == "1") echo "<b><font color='red'>신청</font></b>";
			if ($req_state == "2") echo "<b><font color='navy'>처리완료</font></b>";
			if ($req_state == "3") echo "<b><font color='red'>변경신청</font></b>";
			if ($req_state == "4") echo "<b><font color='red'>철회신청</font></b>";
			if ($req_state == "5") echo "<b><font color='red'>철회완료</font></b>";
			//if ($req_state== "1") echo "신청";
		?>
		<input type="hidden" name="req_state" value="">
		<?
			if ($req_state == "1" || $req_state == "3" || $req_state == "4" ) {
		?>
		&nbsp;&nbsp;<input type="button" onClick="js_change_state();" value="처리완료로 변경" name="btn5">
		<?
			}
		?>
	</td>
</tr>
<tr>
	<th>메모 :</th>
	<td>
		<textarea name="memo" cols="80" rows="5"><?=$memo?></textarea>
	</td>
</tr>
</TABLE>
<br>
<TABLE cellspacing="0" cellpadding="5" width="100%">
<TR>
	<TD align="left"><b>오토쉽 신청 상품<b></TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="10%">상품번호</TH>
	<TH width="40%">상품명</TH>
	<TH width="15%">CV</TH>
	<TH width="15%">가격</TH>
	<TH width="20%">신청수량</TH>
</TR>
<?
	// autoship 상품을 순서 대로 조회 합니다
	$query = "select A.goods_name, A.goods_no, B.goods_id, B.auto_cv, B.auto_price, B.req_cnt from tb_goods A,  tb_autoship_goods B 
						 where A.goods_id = B.goods_id and B.seq = '$seq' order by A.auto_order asc";

	//echo $query;

	$result = mysql_query($query);

	$arr_rs = array();

	if ($result <> "") {
		for($i=0;$i < mysql_num_rows($result);$i++) {
			$arr_rs[$i] = sql_result_array($result,$i);
		}
	}
	
	$total_cv = 0;
	$total_price = 0;
	$total_cnt = 0;

	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs) ; $j++) {
			
			$goods_id			= trim($arr_rs[$j]["goods_id"]);
			$goods_no			= trim($arr_rs[$j]["goods_no"]);
			$goods_name		= trim($arr_rs[$j]["goods_name"]);
			$auto_cv			= trim($arr_rs[$j]["auto_cv"]);
			$auto_price		= trim($arr_rs[$j]["auto_price"]);
			$req_cnt		= trim($arr_rs[$j]["req_cnt"]);


			$total_cv = $total_cv + $auto_cv; 
			$total_price = $total_price + $auto_price;
			$total_cnt = $total_cnt + $req_cnt;

?>
<TR align="center" style="height:25px">
	<TD><?=$goods_no?></TD>
	<TD><?=$goods_name?></TD>
	<TD><?=number_format($auto_cv)?></TD>
	<TD><?=number_format($auto_price)?></TD>
	<TD><?=$req_cnt?></TD>
</TR>
<?
		}
	}
?>
<TR align="center" style="height:25px;background:#EFEFEF">
	<TD style="height:25px;background:#EFEFEF">합계</TD>
	<TD style="height:25px;background:#EFEFEF">&nbsp;</TD>
	<TD style="height:25px;background:#EFEFEF"><?=number_format($total_cv)?></TD>
	<TD style="height:25px;background:#EFEFEF"><?=number_format($total_price)?></TD>
	<TD style="height:25px;background:#EFEFEF"><?=$total_cnt?></TD>
</TR>
</table>
<br>
<TABLE cellspacing="0" cellpadding="5" width="100%">
<TR>
	<TD align="left"><b>처리내역<b></TD>
</TR>
</TABLE>
<TABLE cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
<TR>
	<TH width="15%">신청일</TH>
	<TH width="15%">처리일</TH>
	<TH width="15%">처리내용</TH>
	<TH width="15%">처리관리자ID</TH>
	<TH width="15%">변경전상태</TH>
	<TH width="15%">변경후상태</TH>
</TR>
<?
	$query = "select * from tb_autoship_req_history 
						 where seq = '$seq' order by req_date desc";

	$result = mysql_query($query);

	$arr_rs = array();

	if ($result <> "") {
		for($i=0;$i < mysql_num_rows($result);$i++) {
			$arr_rs[$i] = sql_result_array($result,$i);
		}
	}

	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs) ; $j++) {
			
			$req_date					= trim($arr_rs[$j]["req_date"]);
			$res_date					= trim($arr_rs[$j]["res_date"]);
			$req_type					= trim($arr_rs[$j]["req_type"]);
			$req_admin_id			= trim($arr_rs[$j]["req_admin_id"]);
			$old_req_state		= trim($arr_rs[$j]["old_req_state"]);
			$change_req_state	= trim($arr_rs[$j]["change_req_state"]);

?>
<TR align="center" style="height:25px">
	<TD><?=$req_date?></TD>
	<TD><?=$res_date?></TD>
	<TD><?=$req_type?></TD>
	<TD><?=$req_admin_id?></TD>
	<TD>
		<?
			if ($old_req_state == "1") echo "<b><font color='red'>신청</font></b>";
			if ($old_req_state == "2") echo "<b><font color='navy'>처리완료</font></b>";
			if ($old_req_state == "3") echo "<b><font color='red'>변경신청</font></b>";
			if ($old_req_state == "4") echo "<b><font color='red'>철회신청</font></b>";
			if ($old_req_state == "5") echo "<b><font color='red'>철회완료</font></b>";
		?>
	</TD>
	<TD>
		<?
			if ($change_req_state == "1") echo "<b><font color='red'>신청</font></b>";
			if ($change_req_state == "2") echo "<b><font color='navy'>처리완료</font></b>";
			if ($change_req_state == "3") echo "<b><font color='red'>변경신청</font></b>";
			if ($change_req_state == "4") echo "<b><font color='red'>철회신청</font></b>";
			if ($change_req_state == "5") echo "<b><font color='red'>철회완료</font></b>";
		?>
	</TD>
</TR>
<?
		}
	}
?>
</table>
</FORM>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>