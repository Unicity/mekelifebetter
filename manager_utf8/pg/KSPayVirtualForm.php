<!------------------------------------------------------------------------------
 FILE NAME : KSPayVirtualForm.php
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2003/11/29
                                                         http:'www.kspay.co.kr
                                                         http:'www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
<? 
// 기본  (*) 필수항목임
	
	$storeid      = $HTTP_POST_VARS["storeid"];      // *상점아이디                            
	$storepasswd  = $HTTP_POST_VARS["storepasswd"];  // 상점승인(취소)용 패스워드 (추후사용)  
	$ordername    = $HTTP_POST_VARS["ordername"];    // *주문자명                              
	$ordernumber  = $HTTP_POST_VARS["ordernumber"];  // *주문번호                              
	$amount       = $HTTP_POST_VARS["amount"];       // *금액                                  
	$goodname     = $HTTP_POST_VARS["goodname"];     // *상품명                                
	$idnum        = $HTTP_POST_VARS["idnum"];        // 주민번호(정보등록용) 하이픈없이 등록  
	$email        = $HTTP_POST_VARS["email"];        // 주문자이메일                          
	$phoneno      = $HTTP_POST_VARS["phoneno"];      // 주문자휴대폰번호                      

	
	// Sample
	if ($storeid=="") {
		$storeid      = "2999199999";          // 상점아이디
		$storepasswd  = "";                    // 상점승인(취소)용 패스워드 (추후사용)
		$ordername    = "이훈구";              // 주문자명
		$ordernumber  = "sample_mpi_test";     // 주문번호
		$amount       = "1004";                // 금액
		$goodname     = "훈구장난감외1건";     // 상품명 

	}

?>
<html>
<head>
<title>KSPay</title>
<meta http-equiv="Content-Type" content="text/html charset=utf-8">
<script language="javascript">
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
<form name=KSPayAuthForm method=post action="./KSPayVirtualPost.php" onSubmit="">
<!--기본-------------------------------------------------------------------------------------------->
<input type=hidden name="storeid"    	  value="<? echo($storeid    )?>">
<input type=hidden name="storepasswd" 	  value="<? echo($storepasswd)?>">
<input type=hidden name="authty" 		  value="6000">

<input type=hidden name="ordernumber"     value="<? echo($ordernumber ) ?>">
<input type=hidden name="ordername"       value="<? echo($ordername   ) ?>">
<input type=hidden name="idnum"           value="<? echo($idnum       ) ?>">
<input type=hidden name="goodname"        value="<? echo($goodname    ) ?>">
<input type=hidden name="amount"          value="<? echo($amount      ) ?>">

<table border=0 width=0>
<tr>
<td align=center>
<table width=280 cellspacing=0 cellpadding=0 border=0 bgcolor=#4F9AFF>
<tr>
<td>
<table width=100% cellspacing=1 cellpadding=2 border=0>
<tr bgcolor=#4F9AFF height=25>
<td align=left><font color="#FFFFFF">
KSPay 가상계좌 발급
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
	<td><?echo($goodname)?></td>
</tr>
<tr>
	<td>금액 :</td>
	<td><?echo($amount)?></td>
</tr>
<tr>
	<td colspan=3><hr noshade size=1></td>
</tr>
<tr>
	<td height="22">입금은행</td>
		<td>
		<select name="bankcode">
	    	<option value="">은행선택</option>
	    	<option value="04" selected>국민은행(04)</option>
	    	<option value="11">농협은행(11)</option>
	    	<option value="26">신한은행(26)</option>
	    	<option value="20">우리은행(20)</option>
	    	<option value="23">제일은행(23)</option>
	    	<option value="21">조흥은행(21)</option>
	  </select>
	</td>
</tr>
<tr>
<td colspan=2 align=center>	
<input type=submit  value=" 발 급 ">
</td>
</tr>
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
