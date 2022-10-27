<!------------------------------------------------------------------------------
 FILE NAME : KSPAY_cancel_form.jsp
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2004-05-03
                                                         http:'www.kspay.co.kr
                                                         http:'www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
<?
	$storeid      = $HTTP_POST_VARS["storeid"];      // *상점아이디                            
	$storepasswd  = $HTTP_POST_VARS["storepasswd"];  // 상점승인(취소)용 패스워드 (추후사용)  
	$TransactionNo  = $HTTP_GET_VARS["TransactionNo"];  // 


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

	include "../../dbconn_utf8.inc";

	$query = "select * from tb_kspay where TransactionNo = '".$TransactionNo."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$TradeDate = $list[TradeDate];
	$TradeTime = $list[TradeTime];
	$CardNo = $list[CardNo];
	$amount = $list[amount];
	$installment = $list[installment];
	$AuthNo = $list[AuthNo];
	
	$TradeDate = date_format2($TradeDate);
	$TradeTime = time_format($TradeTime);
	
	$CardNo = card_XXX($CardNo);
	
	mysql_close($connect);
	
	$TransactionNo = trim($TransactionNo);
	// Sample
	if ($storeid=="") {
		$storeid      = "2002100263";          // 상점아이디
		$storepasswd  = "";                    // 상점승인(취소)용 패스워드 (추후사용)
	}
	else {
		if ($storeid      == null)  $storeid      ="2002100263";
		if ($storepasswd  == null)  $storepasswd  ="";
	} 

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html charset=utf-8">
<LINK rel="stylesheet" HREF="../inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
</head>
<BODY>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
	<TD align="left"><B>KSPAY 승인 취소 화면</B></TD>
</TR>
</TABLE>
<form name=KSPayAuthForm method=post action="./KSPayCancelPost.php">
<!--기본-------------------------------------------------------------------------------------------->
<input type=hidden name="storeid"    	  value="<? echo($storeid    )?>">
<input type=hidden name="storepasswd" 	  value="<? echo($storepasswd)?>">
<input type=hidden name="trno" 	  value="<? echo($TransactionNo)?>">
<table width="100%">
<tr>
	<td align="center">
<table height='35' width='95%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>승인구분 :</th>
	<td>
		<select name="authty">
			<option value="1010" selected>승인취소</option>
		</select>
	</td>
</tr>
<tr>
	<th>승인일시 :</th>
	<td>
		<?echo ($TradeDate)?> <?echo ($TradeTime)?>
	</td>
</tr>
<tr>
	<th>카드번호 :</th>
	<td>
		<?echo ($CardNo)?>
	</td>
</tr>
<tr>
	<th>금 액 :</th>
	<td>
		<?echo number_format($amount)?> 원
	</td>
</tr>
<tr>
	<th>할부개월 :</th>
	<td>
		<?echo ($installment)?>
	</td>
</tr>
<tr>
	<th>승인번호 :</th>
	<td>
		<?echo ($AuthNo)?>
	</td>
</tr>
<tr>
	<th>거래번호 :</th>
	<td>
		<?echo ($TransactionNo)?>
	</td>
</tr>
</table>
	</td>
</tr>
</table>
</td>
</tr>
</table>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan=2 align=center>
		<input type=submit  value=" 확 인 ">
		<input type=button  value=" 취 소 " onclick="self.close();">
	</td>
</tr>
</table>
</form>
</body>
</html>