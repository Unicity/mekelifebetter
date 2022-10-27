<!------------------------------------------------------------------------------
 FILE NAME : KSPayCreditForm.php
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2004-05-03
                                                         http:'www.kspay.co.kr
                                                         http:'www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
<? 
// 기본  (*) 필수항목임
        $ILK_HOST     = "https://kspay.ksnet.to/ksmpi/veri_host.jsp";
        
        $storeid      = "2002100263";      // *상점아이디                            
        $storepasswd  = $HTTP_POST_VARS["storepasswd"];  // 상점승인(취소)용 패스워드 (추후사용)  
        $ordername    = $HTTP_POST_VARS["ordername"];    // *주문자명                              
        $ordernumber  = $HTTP_POST_VARS["ordernumber"];  // *주문번호                              
        $amount       = $HTTP_POST_VARS["amount"];       // *금액                                  
        $goodname     = $HTTP_POST_VARS["goodname"];     // *상품명                                
        $idnum        = $HTTP_POST_VARS["idnum"];        // 주민번호(정보등록용) 하이픈없이 등록  
        $email        = $HTTP_POST_VARS["email"];        // 주문자이메일                          
        $phoneno      = $HTTP_POST_VARS["phoneno"];      // 주문자휴대폰번호                      
        $currencytype = $HTTP_POST_VARS["currencytype"]; // *통화구분 : "WON" : 원화, "USD" : 미화 

        // 신용카드 
        $interest     = $HTTP_POST_VARS["interest"]; // *이자구분 "1" : 일반, "2" : 무이자
        
        // Sample
        if ($storeid=="") {
                $storeid      = "2002100263";          // 상점아이디
                $storepasswd  = "";                    // 상점승인(취소)용 패스워드 (추후사용)
                $ordername    = "Unicity BA";              // 주문자명
                $ordernumber  = "Manual Authorization test";     // 주문번호
                $amount       = "1004";                // 금액
                $goodname     = "훈구장난감외1건";     // 상품명 
                $idnum        = "6901271999999";       // 주민번호(정보등록용) 하이픈없이 등록
                $email        = "kspay@ksnet.co.kr";   // 주문자이메일
                $phoneno      = "01699996666";         // 주문자휴대폰번호
                $currencytype = "WON";                 // 통화구분 : "WON" : 원화, "USD" : 미화
                $interest     = "1";                   // 이자구분 "1" : 일반, "2" : 무이자
        }
        else {
                if ($storeid      == null)  $storeid      ="2002100263";
                if ($storepasswd  == null)  $storepasswd  ="";
                if ($ordername    == null)  $ordername    ="";
                if ($goodname     == null)  $goodname     ="";
                if ($currencytype == null)  $currencytype ="WON";
                if ($interest     == null)  $interest     ="1";
        } 

?>
<html>
<head>
<title>KSPay</title>
<meta http-equiv="Content-Type" content="text/html charset=utf-8">
<script language="javascript">
<!--
// ************************** ILK Modification **********************************
// * 중요!!
// * 다른 페이지에서 사용하게 되므로 함수명은 그대로 사용하여야 한다.
// ******************************************************************************
        // 유효기간추출하기
        
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
//        function submitPage()
//        {
                /*MPI용 activex설치확인*/
//                if (false == isActXInstalled()) return false;

//                var frm = document.KSPayAuthForm;
           //          var frm = document.Visa3d;
//                var realform = document.KSPayAuthForm;
                
        //      frm.dummy.value="dummy";

                // 비자인증에 필요한 param과 KSNET결제에 필요한 param 중 동일한 것이 존재하므로, 비자인증과정에 필요한 카드번호를 pan에 세팅한다.
        //      frm.pan.value = realform.cardno.value;
                // 비자인증과정에 필요한 유효기간을 expiry에 세팅한다(YYMM 형태로 세팅하여야 한다.).
        //      frm.expiry.value = getValue("year").substring(2) + getValue("month");
                
//                frm.submit();
//        }

        // 실제 승인페이지로 넘겨주는 form에 xid, eci, cavv를 세팅한다.
//                    function paramSet(xid, eci, cavv)
//      {
//              var frm = document.KSPayAuthForm;//
//              frm.xid.value = xid;
//              frm.eci.value = eci;
//              frm.cavv.value = cavv;
//        }

/* realSubmit을 진행할 것인가 아닌가를 판단하는 함수. 이 함수의 호출은 승인 페이지가 아닌 return.jsp로 하게 되며, 
페이지가 받아두었던 인증값 파라메터들과 리얼서브밋진행여부를 받아 승인페이지로 되넘겨준다. */
        function proceed(arg)
        {
                var frm = document.KSPayAuthForm;
                if(arg == "TRUE") {
                        frm.expdt.value = getValue("year").substring(2) + getValue("month");
                        frm.action      = "./KSPayCreditPostM.php";
                        frm.submit();
                }
        }
/************************** ILK Modification end ******************************/
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

<!--  mpi plugin설치 확인 -->
<script language=javascript src="https://kspay.ksnet.to/ksmpi/mpiActxCheck.js"></script>
<script>
        isActXInstalled();
</script>
<!--  mpi plugin설치 확인 -->

<form name=KSPayAuthForm method=post action="" onSubmit="">
<!--기본-------------------------------------------------------------------------------------------->
<input type=hidden name="storeid"         value="<? echo($storeid    )?>">
<input type=hidden name="storepasswd"     value="<? echo($storepasswd)?>">
<input type=hidden name="authty"                  value="1000">

<!--일반신용카드------------------------------------------------------------------------------------>
<input type=hidden name="expdt"           value="">
<input type=hidden name="email"           value="<? echo($email       ) ?>">
<input type=hidden name="phoneno"         value="<? echo($phoneno     ) ?>">
<input type=hidden name="interest"        value="<? echo($interest    ) ?>">
<input type=hidden name="ordernumber"     value="<? echo($ordernumber ) ?>">
<input type=hidden name="ordername"       value="<? echo($ordername   ) ?>">
<input type=hidden name="idnum"           value="<? echo($idnum       ) ?>">
<input type=hidden name="goodname"        value="<? echo($goodname    ) ?>">
<input type=hidden name="amount"          value="<? echo($amount      ) ?>">
<input type=hidden name="currencytype"    value="<? echo($currencytype) ?>">

<!--Visa3d------------------------------------------------------------------------------------->
<input type="hidden" name="xid"           value="">
<input type="hidden" name="eci"           value="">
<input type="hidden" name="cavv"          value="">
<!--Visa3d------------------------------------------------------------------------------------->

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
        <td>상품 :</td>
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
        <td>신용카드 :</td>
        <td>
                <input type=text name=cardno size=20 maxlength=16 value="">
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
<!------------------------------------ ILK Modification --------------------------------------------->
<!---------------------------------------------------------------------------------------------------
        중요!!!
        IFrame의 src를 내용이 아무것도 없는 dummy.jsp로 주는 것은 https 즉 SSL로 결제가 이루어지는 경우
        현재 페이지 로드시 보안관련 메시지가 뜨는 것을 방지하기 위함이다.
        이 부분 삭제시 "보안되지 않는 항목도 표시하시겠습니까?" 라는 메시지가 뜨므로 주의.
----------------------------------------------------------------------------------------------------->
<!--사용자인증 페이지로 post 될 폼-->
<IFRAME id=ILKFRAME name=ILKFRAME style="display:none" src="dummy.asp"></IFRAME>
<? 
        $ret="http://'return.php가 존재하는 귀사의URL입니다'/return.php";
        //ret = "http://localhost/web/kspayweb/sample/return.asp"
        if ($currencytype=="" || $currencytype=="WON")
        {
                $currencytype="410";
        }
        else
        {
                $currencytype="840";
        } 
?>
<div style="display:none"> 
<FORM name=Visa3d action="<? echo ($ILK_HOST) ?>"  target="ILKFRAME" method=post>
   <INPUT type="hidden"   name=pan             size="19" maxlength="19" value="">
   <INPUT type="hidden"   name=expiry          size="6"  maxlength="6"  value="">
   <INPUT type="hidden"   name=purchase_amount size="20" maxlength="20" value="<? echo ($amount) ?>">
   <INPUT type="hidden"   name=amount          size="20" maxlength="20" value="<? echo ($amount) ?>">
   <INPUT type="hidden"   name=description     size="80" maxlength="80" value="none">
   <INPUT type="hidden"   name=currency        size="3"  maxlength="3"  value="<? echo ($currencytype) ?>">
   <INPUT type="hidden"   name=recur_frequency size="4"  maxlength="4"  value="">
   <INPUT type="hidden"   name=recur_expiry    size="8"  maxlength="8"  value="">
   <INPUT type="hidden"   name=installments    size="4"  maxlength="4"  value="">   
   <INPUT type="hidden"   name=device_category size="20" maxlength="20" value="0">
   <INPUT type="hidden"   name="name"          size="20"                value="">
   <INPUT type="hidden"   name="url"           size="20"                value="http://www.test_store.co.kr">
   <INPUT type="hidden"   name="country"       size="20"                value="410">
   <INPUT type="password" name="dummy"                                  value="">
   <INPUT type="hidden"   name="returnUrl"                              value="<? echo ($ret) ?>">
</FORM>
</div>
<!------------------------------------ ILK Modification end ----------------------------------------->
</body>
</html>
