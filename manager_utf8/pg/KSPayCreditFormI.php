<!------------------------------------------------------------------------------
 FILE NAME : KSPayCreditForm.asp
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2004-05-03
                                                         http://www.kspay.co.kr
                                                         http://www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
<?
// 기본  (*) 필수항목임
	$storeid      = $HTTP_POST_VARS["storeid"];      // *상점아이디                            
	$storepasswd	 = $HTTP_POST_VARS["storepasswd"];  // 상점승인(취소)용 패스워드 (추후사용)  
	$ordername 	 = $HTTP_POST_VARS["ordername"];    // *주문자명                              
	$ordernumber	 = $HTTP_POST_VARS["ordernumber"];  // *주문번호                              
	$amount		 = $HTTP_POST_VARS["amount"];       // *금액                                  
	$goodname     = $HTTP_POST_VARS["goodname"];     // *상품명                                
	$idnum		 = $HTTP_POST_VARS["idnum"];    	 // 주민번호(정보등록용) 하이픈없이 등록  
	$email		 = $HTTP_POST_VARS["email"];        // 주문자이메일                          
	$phoneno		 = $HTTP_POST_VARS["phoneno"];      // 주문자휴대폰번호                      
	$currencytype = $HTTP_POST_VARS["currencytype"]; // *통화구분 : "WON" : 원화, "USD" : 미화 

// 신용카드 
	$interesttype    = $HTTP_POST_VARS["interesttype"];    // *무이자할부개월수지정 예) "3:6:7:12"
	$installmenttype	= $HTTP_POST_VARS["installmenttype"]; // *할부개월수지정 예) "0:3:4:5:6:7:8:9:10:11:12"

// ISP용
	$KVP_QUOTA_INF    = "";
	$KVP_NOINT_INF 	= "";
	$KVP_CURRENCY     = "";

// Sample
if($storeid == null) {
		$storeid      = "2999199999";             // 상점아이디
		$storepasswd	 = "";                       // 상점승인(취소)용 패스워드 (추후사용)
		$ordername 	 = "박찬호";                 // 주문자명
		$ordernumber	 = "sample_mpi_test";        // 주문번호
		$amount		 = "1004";                   // 금액
		$goodname     = "유니시티1건";        // 상품명 
		$idnum	     = "7003231001010";          // 주민번호(정보등록용) 하이픈없이 등록
		$email		 = "kspay@ksnet.co.kr";      // 주문자이메일
		$phoneno		 = "01699996666";            // 주문자휴대폰번호
		$currencytype = "WON";                   // 통화구분 : "WON" : 원화, "USD" : 미화
		
		$KVP_NOINT_INF = "0204-3:4:5:6,0100-3:6:9";
		$KVP_QUOTA_INF = "0:3:4:5:6:7:8:9:10:11:12:60";
}else {
			if ($storeid      == null)  $storeid      ="2999199999";
			if ($storepasswd  == null)  $storepasswd  ="";
			if ($ordername    == null)  $ordername    ="";
			if ($goodname     == null)  $goodname     ="";
			if ($currencytype == null)  $currencytype ="WON";
			if ($interest     == null)  $interest     ="1";
		
		if($interesttype== "NONE" || $interesttype=="")
			$KVP_NOINT_INF = "NONE";
		else if($interesttype=="1")
			$KVP_NOINT_INF = "ALL";
		else 
			$KVP_NOINT_INF = "0204-" .$HTTP_POST_VARS["interesttype"].",0100-".$HTTP_POST_VARS["interesttype"].",0700-".$HTTP_POST_VARS["interesttype"].",0400-".$HTTP_POST_VARS["interesttype"];

		if($installmenttype == null || $installmenttype="") 
			$KVP_QUOTA_INF = "0:3:4:5:6:7:8:9:10:11:12";
	}

?>
<html>
<head>
<title>KSPay</title>
<meta http-equiv="Content-Type" content="text/html charset=utf-8">
<script language=javascript src="https://www.vpay.co.kr/KVPplugin_ssl.js"></script>
<script language="javascript">
<!--
	StartSmartUpdate();
   	function submit_isp(form) {
		if (MakePayMessage(form) == true){
			form.action= "./KSPayCreditPostI.php";
			form.submit();
			return true;
		}
		else {
			alert("지불에 실패하였습니다.");
			return false;
		}
	}

	function getValue(ym)
	{
		var i = 0;
		var form = document.KSPayAuthForm;

		if( ym == "year" ) {
			while( !form.expyear[i].selected ) i++;
			return form.expyear[i].value;
		} 
		else if( ym == "month" ) {
			while( !form.expmon[i].selected ) i++;
			return form.expmon[i].value;
		}
	}

	// vbv결제 처리
	function submitPage()
	{
		var frm = document.Visa3d;
		var realform = document.KSPayAuthForm;
		
		// 비자인증에 필요한 param과 KSNET결제에 필요한 param 중 동일한 것이 존재하므로, 비자인증과정에 필요한 카드번호를 pan에 세팅한다.
		frm.pan.value = realform.cardno.value;
		// 비자인증과정에 필요한 유효기간을 expiry에 세팅한다(YYMM 형태로 세팅하여야 한다.).
		frm.expiry.value = getValue("year").substring(2) + getValue("month");
		
		frm.submit();
	}

	
	/*  realSubmit을 진행할 것인가 아닌가를 판단하는 함수. 이 함수의 호출은 승인 페이지가 아닌 return.jsp로 하게 되며, 
		페이지가 받아두었던 인증값 파라메터들과 리얼서브밋진행여부를 받아 승인페이지로 되넘겨준다. */
	function proceed(arg)
	{
		var frm = document.KSPayAuthForm;
		if(arg) {
			frm.expdt.value = getValue("year").substring(2) + getValue("month");
			frm.action      = "./KSPayCreditPostI.php";
			frm.submit();
		}
	}
/************************** ILK Modification end ******************************/
-->
-->
</script>
<style type="text/css">
	BODY{font-size:9pt line-height:160%}
	TD{font-size:9pt line-height:160%}
	A {color:blueline-height:160% background-color:#E0EFFE}
	INPUT{font-size:9pt}
	SELECT{font-size:9pt}
	.emp{background-color:#FDEAFE}
	.white{background-color:#FFFFFF color:black border:1x solid white font-size: 9pt}
</style>
</head>

<body onload="" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 onFocus="" >
<form name=KSPayAuthForm method=post action="" onSubmit="">
<!--기본-------------------------------------------------------------------------------------------->
<input type=hidden name="storeid"    	value="<? echo($storeid)?>">
<input type=hidden name="storepasswd" 	value="<? echo($storepasswd)?>">
<input type=hidden name="authty" 		value="1300">

<!--일반신용카드------------------------------------------------------------------------------------>
<input type=hidden name="expdt"           value="">
<input type=hidden name="email"           value="<? echo($email)?>">
<input type=hidden name="phoneno"         value="<? echo($phoneno)?>">
<input type=hidden name="ordernumber"     value="<? echo($ordernumber)?>">
<input type=hidden name="ordername"       value="<? echo($ordername)?>">
<input type=hidden name="idnum"           value="<? echo($idnum)?>">
<input type=hidden name="goodname"        value="<? echo($goodname)?>">
<input type=hidden name="amount"          value="<? echo($amount)?>">
<input type=hidden name="currencytype"    value="<? echo($currencytype)?>">

<!--ISP신용카드------------------------------------------------------------------------------------->
<input type=hidden name=KVP_PGID 		value="A0029">	<!-- PG -->
<input type=hidden name=KVP_SESSIONKEY 	value="">  	    <!-- 세션키  --> 
<input type=hidden name=KVP_ENCDATA 	value="">     	<!-- 암호된데이터 -->
<input type=hidden name=KVP_CURRENCY 	value="<? echo($currencytype)?>"> 	<!-- 지불 화폐 단위 (WON/USD) : 한화 - WON, 미화 - USD-->
<input type=hidden name=KVP_NOINT 		value="">       <!-- 무이자구분(1:무이자,0:일반) -->
<input type=hidden name=KVP_QUOTA 		value="">       <!-- 할부 -->
<input type=hidden name=KVP_CARDCODE 	value="">    	<!-- 카드코드 -->
<input type=hidden name=KVP_CONAME 		value="">      	<!-- 카드명 -->
<input type=hidden name=KVP_RESERVED1 	value="">   	<!-- 예비1 -->
<input type=hidden name=KVP_RESERVED2 	value="">   	<!-- 예비2 -->
<input type=hidden name=KVP_RESERVED3 	value="">   	<!-- 예비3 -->
<input type=hidden name=KVP_IMGURL 		value="">	
<input type=hidden name=KVP_QUOTA_INF 	value="<? echo($KVP_QUOTA_INF)?>">	<!--할부값-->
<input type=hidden name=KVP_GOODNAME 	value="<? echo($goodname)?>">		<!--상품명-->
<input type=hidden name=KVP_PRICE 		value="<? echo($amount)?>">		<!--금액-->
<input type=hidden name=KVP_NOINT_INF 	value="<? echo($KVP_NOINT_INF)?>">	<!--일반, 무이자-->

<table border=0 width=0>
<tr>
<td align=center>
<table width=280 cellspacing=0 cellpadding=0 border=0 bgcolor=#4F9AFF>
<tr>
<td>
<table width=100% cellspacing=1 cellpadding=2 border=0>
<tr bgcolor=#4F9AFF height=25>
<td align=left><font color="#FFFFFF">
KSPay 신용카드 승인 ^^
</font></td>
</tr>
<tr bgcolor=#FFFFFF>
<td valign=top>
<table width=100% cellspacing=0 cellpadding=2 border=0>
<tr>
<td align=left>
<table>
<tr>
	<td>상품명 :</td>
	<td><? echo($goodname)?></td>
</tr>
<tr>
	<td>금액 :</td>
	<td><? echo($amount)?></td>
</tr>
<tr>
	<td colspan=3><hr noshade size=1></td>
</tr>
<tr>
	<td colspan=2>
	국민/비씨는 안전결제 이용☞ <input type=button name=ISP onclick="return submit_isp(KSPayAuthForm)" value=" ISP결제 ">
	</td>
</tr>
<tr>
	<td>신용카드 :</td>
	<td>
		<input type=text name=cardno  size=20 maxlength=16 value="">
	</td>
</tr>
<tr>
	<td>유효기간 :</td>
	<td>
              	<select name="expyear">
<?
	$time = time();
	$date = getdate($time);
	$dYear = $date[year];
	for($i = $dYear; $i <= $dYear+10; $i++) {
		echo("<option value=$i ");
		if($i == ($dYear)) echo(" selected ");
		echo("> $i </option>");
	}
?>

		</select>년/
		<select name="expmon">
			<option value="01">01</option>
			<option value="02" selected>02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
		</select>월
	</td>
</tr>
<tr>
	<td>할부 :</td>
	<td>
		<select name="installment">
			<option value="00" selected>일시불</option>
			<option value="03">03개월</option>
			<option value="04">04개월</option>
			<option value="05">05개월</option>
			<option value="06">06개월</option>
			<option value="07">07개월</option>
			<option value="08">08개월</option>
			<option value="09">09개월</option>
			<option value="10">10개월</option>
			<option value="11">11개월</option>
			<option value="12">12개월</option>
		</select>
	</td>
</tr>
<tr>
	<td>주민번호 :</td>
	<td>
		XXXXXX - <input type=text name=lastidnum size=10 maxlength=7 value="">
	</td>
</tr>
<tr>
	<td>비밀번호 :</td>
	<td><input type=password name=passwd size=4 maxlength=4 value=""></td>
</tr>

<tr>
<td colspan=2 align=center>
		<input type=button onclick="javascript:submitPage()" value=" 승 인 ">
		<input type=button value=" 취 소 ">
</tr>
</td>
</tr>
</table>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</table>
</form>
</body>
</html>
