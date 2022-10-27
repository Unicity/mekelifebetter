<?
        //////////////////////////////////////////////////////////////
        //
        //      Date            : 2004/11/08
        //      Last Update : 2004/11/08
        //      Author          : Park, ChanHo
        //      History         : 2004.11.08 by Park ChanHo 
        //      File Name       : KSPAY_form.php
        //      Description : KSPAY_form 화면
        //      Version         : 1.0
        //
        //////////////////////////////////////////////////////////////

        include "../admin_session_check.inc";
        include "../inc/global_init.inc";
      	include "../../dbconn_utf8.inc";

		$sqlstr = "SELECT seq_ksnum FROM seq_kspay "; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["seq_ksnum"];


        $query = "UPDATE seq_kspay set seq_ksnum = seq_ksnum + 1 ";

        mysql_query($query) or die("Query Error");
        mysql_close($connect);


?>              
<!------------------------------------------------------------------------------
 FILE NAME : KSPay_form.php
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2004-05-03
                                                         http:'www.kspay.co.kr
                                                         http:'www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
<? 
// 기본  (*) 필수항목임
//        $ILK_HOST     = "https://kspay.ksnet.to/ksmpi/veri_host.jsp";
        $ILK_HOST     = "./KSPayCreditPostM.php";
        
        $storeid      = $HTTP_POST_VARS["storeid"];      // *상점아이디                            
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
//              $ordername    = "이훈구";              // 주문자명
//              $ordernumber  = "sample_mpi_test";     // 주문번호
//              $amount       = "1004";                // 금액
//              $goodname     = "훈구장난감외1건";     // 상품명 
//              $idnum        = "6901271999999";       // 주민번호(정보등록용) 하이픈없이 등록
//              $email        = "kspay@ksnet.co.kr";   // 주문자이메일
//              $phoneno      = "01699996666";         // 주문자휴대폰번호
                $currencytype = "WON";                 // 통화구분 : "WON" : 원화, "USD" : 미화
//              $interest     = "1";                   // 이자구분 "1" : 일반, "2" : 무이자
        } else {
                if ($storeid      == null)  $storeid      ="2999199999";
                if ($storepasswd  == null)  $storepasswd  ="";
                if ($ordername    == null)  $ordername    ="";
                if ($goodname     == null)  $goodname     ="";
                if ($currencytype == null)  $currencytype ="WON";
                if ($interest     == null)  $interest     ="1";
        } 

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html charset=utf-8">
<LINK rel="stylesheet" HREF="../inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>
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
        function submitPage()
        {
                
                /*MPI용 activex설치확인*/
                //if (false == isActXInstalled()) return false;

                var frm = document.Visa3d;
                
                var realform = document.KSPayAuthForm;
                
                frm.dummy.value="dummy";
                frm.purchase_amount.value=realform.amount.value;
                frm.amount.value=realform.amount.value;
                realform.idnum.value=realform.idnum1.value+realform.idnum2.value;

                if (realform.ordernumber.value =="") {
                        alert("주문번호를 입력하세요.");
                        realform.ordernumber.focus();
                        return; 
                }

                if (realform.ordername.value =="") {
                        alert("주문자를 입력하세요.");
                        realform.ordername.focus();
                        return; 
                }


                if(!isNumber(realform.phoneno)) {
                        alert("'-' 없이 숫자만 입력해 주세요.");
                        realform.phoneno.focus();
                        return;
                }

                if (realform.goodname.value =="") {
                        alert("상품명을 입력하세요.");
                        realform.goodname.focus();
                        return; 
                }

                if (realform.amount.value =="") {
                        alert("결제금액을 입력하세요.");
                        realform.amount.focus();
                        return; 
                }

                if(!isNumber(realform.amount)) {
                        alert("숫자만 입력해 주세요.");
                        realform.amount.focus();
                        return;
                }

                if (realform.expdt_temp.value =="") {
                        alert("유효기간을 입력하세요.");
                        realform.expdt_temp.focus();
                        return; 
                }

                if(!isNumber(realform.expdt)) {
                        alert("숫자만 입력해 주세요.");
                        realform.expdt.focus();
                        return;
                }
				
				if (realform.interest[0].checked) {
					frm.interest.value = "1";
				} else {
					frm.interest.value = "2";
				} 	
				
				frm.cardno.value = realform.cardno.value;
                frm.expdt.value = realform.expdt_temp.value.substring(2) + realform.expdt_temp.value.substring(0,2);
                frm.installment.value = realform.installment.value;
                					
                // 비자인증에 필요한 param과 KSNET결제에 필요한 param 중 동일한 것이 존재하므로, 비자인증과정에 필요한 카드번호를 pan에 세팅한다.
                frm.pan.value = realform.cardno.value;
                // 비자인증과정에 필요한 유효기간을 expiry에 세팅한다(YYMM 형태로 세팅하여야 한다.).
                frm.expiry.value = realform.expdt_temp.value.substring(2) + realform.expdt_temp.value.substring(0,2);


                frm.ordernumber.value = realform.ordernumber.value;
                frm.ordername.value = realform.ordername.value;
                frm.phoneno.value = realform.phoneno.value;
                frm.email.value = realform.email.value;
                frm.goodname.value = realform.goodname.value;
                
                frm.submit();
        
        }

        // 실제 승인페이지로 넘겨주는 form에 xid, eci, cavv를 세팅한다.
        function Paramset(xid, eci, cavv)
        {
                
           		alert(xid);
                alert(eci);
                alert(cavv);
                var frm = document.KSPayAuthForm;
                frm.xid.value = xid;
                frm.eci.value = eci;
                frm.cavv.value = cavv;
                
        }

/* realSubmit을 진행할 것인가 아닌가를 판단하는 함수. 이 함수의 호출은 승인 페이지가 아닌 return.jsp로 하게 되며, 
페이지가 받아두었던 인증값 파라메터들과 리얼서브밋진행여부를 받아 승인페이지로 되넘겨준다. */
        function proceed(arg)
        {
                var frm = document.KSPayAuthForm;
                if(arg == "TRUE") {
                        //frm.expdt.value = getValue("year").substring(2) + getValue("month");
                        frm.action      = "./KSPayCreditPostM.php";
                        frm.submit();
                }
        }
/************************** ILK Modification end ******************************/

        function F_PeopNoCheck(s) {
                if( s.charAt(6) == 1 || s.charAt(6) == 2 ) {
                if( s.charAt(12) == 
                        (( 11 - ((s.charAt(0)*2+s.charAt(1)*3+s.charAt(2)*4
                                                +s.charAt(3)*5+s.charAt(4)*6+s.charAt(5)*7
                                                +s.charAt(6)*8+s.charAt(7)*9+s.charAt(8)*2
                                                +s.charAt(9)*3+s.charAt(10)*4+s.charAt(11)*5)
                                                % 11)))%10)
                        return true; 
                } 
                return false; 
        }

        function F_CheckNull(Obj, Msg) {
                if (Obj.value == "") {
                        alert(Msg + " 입력하십시오.");
                        Obj.focus(); 
                        return true;   
                }
        
                return false; 
        }

        function lengthCheck1(form, str) {
                if (str.length >= 6) {
                        
                        var realform = document.KSPayAuthForm;

                        realform.idnum1.blur();
                        realform.idnum2.focus();
                }
        }

-->
</script>
</head>
<BODY onload="document.KSPayAuthForm.cardno.focus();">
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
        <TD align="left"><B>KSPAY 신용카드 결제 화면</B></TD>
        <TD align="right" width="300" align="center" bgcolor=silver>
                <INPUT type="hidden" name="page" value="<?echo $page?>">
        </TD>
</TR>
</TABLE>
<!--  mpi plugin설치 확인 -->
<!--<script language=javascript src="https://kspay.ksnet.to/ksmpi/mpiActxCheck.js"></script>-->
<script src="/inc/unicity_ssl.js" language="javascript"></script>
<script>
//        isActXInstalled();
</script>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<!--  mpi plugin설치 확인 -->
<form name=KSPayAuthForm method=post action="" onSubmit="">
<!--기본-------------------------------------------------------------------------------------------->
<input type=hidden name="storeid"         value="<? echo($storeid    )?>">
<input type=hidden name="storepasswd"     value="<? echo($storepasswd)?>">
<input type=hidden name="authty"          value="1000">

<!--일반신용카드------------------------------------------------------------------------------------>
<input type=hidden name="expdt"           value="">
<!--<input type=hidden name="email"           value="<? echo($email       ) ?>">-->
<!--<input type=hidden name="phoneno"         value="<? echo($phoneno     ) ?>">-->
<!--<input type=hidden name="interest"        value="<? echo($interest    ) ?>">-->
<!--<input type=hidden name="ordernumber"     value="<? echo($ordernumber ) ?>">-->
<!--<input type=hidden name="ordername"       value="<? echo($ordername   ) ?>">-->
<!--<input type=hidden name="idnum"           value="<? echo($idnum       ) ?>">-->
<!--<input type=hidden name="goodname"        value="<? echo($goodname    ) ?>">-->
<!--<input type=hidden name="amount"          value="<? echo($amount      ) ?>">-->
<input type=hidden name="currencytype"    value="<? echo($currencytype) ?>">
<input type=hidden name="idnum"           value="">

<!--Visa3d------------------------------------------------------------------------------------->
<input type="hidden" name="xid"           value="">
<input type="hidden" name="eci"           value="">
<input type="hidden" name="cavv"          value="">
<!--Visa3d------------------------------------------------------------------------------------->
<tr>
        <td align='center'>
<?
	$new_seq = sprintf("%08d", $iNewid);
?>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
        <th>
                주문번호 :
        </th>
        <td>
                <input type="text" name="ordernumber" size="20" value="<?echo $new_seq?> <?echo $s_adm_id?> M" readonly=1>
        </td>
</tr>
<tr>
        <th>
                주문자 :
        </th>
        <td>
                <input type="text" name="ordername" size="20" value="유니시티 회원">
        </td>
</tr>
<tr>
        <th>
                주문자 연락처:
        </th>
        <td>
                <input type="text" name="phoneno" size="14" value="0809088282"> '-'없이 숫자만 넣어 주세요 !
        </td>
</tr>
<tr>
        <th>
                주문자 이메일:
        </th>
        <td>
                <input type="text" name="email" size="45" value="customer_korea@unicitynetwork.com">
        </td>
</tr>
<tr>
        <th>
                상품명 :
        </th>
        <td>
                <input type="text" name="goodname" size="70" value="건강 보조 식품 / 화장품">
        </td>
</tr>
<tr>
        <th>
                신용카드 :
        </th>
        <td>
                <input type="text" name="cardno" size=20 maxlength=16 value="">
        </td>
</tr>
<tr>
        <th>
                유효기간 :
        </th>
        <td>
                <input type="text" name="expdt_temp" size=5 maxlength=4 value=""> ex) mm/yy
        </td>
</tr>
<tr>
        <th>
                할부 :
        </th>
        <td>
                <select name="installment">
                        <option value="00" selected>일시불</option>
                        <option value="02">02개월</option>
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
        <th>
                금액 :
        </th>
        <td>
                <input type="text" name="amount" size="20" value="" style="text-align:right"> 원 
        </td>
</tr>
<tr>
        <th>
                이자구분 :
        </th>
        <td>
                <input type="radio" name="interest" value="1" checked> 일반
                <input type="radio" name="interest" value="2"> 무이자
        </td>
</tr>
<!--
<tr>
        <th>
                주민번호 :
        </th>
        <td>
                <input type=text name=idnum1 size=10 maxlength=6 value="" onkeyup='lengthCheck1(this.form,this.value);'> - <input type=text name=idnum2 size=10 maxlength=7 value="">
        </td>
</tr>
<tr>
        <th>
                비밀번호 :
        </th>
        <td>
                <input type=password name=passwd size=4 maxlength=4 value="">
        </td>
</tr>
-->
<input type=hidden name=idnum1 value="">
<input type=hidden name=idnum2 value="">
<input type=hidden name=passwd size=4 maxlength=4 value="">

</table>
        </td>
</tr>
</table>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
        <td colspan=2 align=center>
                <input type=button onclick="javascript:submitPage()" value=" 승 인 ">
                <input type=button onclick="javascript:document.location='KSPAY_list.php'" value=" 작 업 취 소 ">
        </td>
</tr>
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
<IFRAME id=ILKFRAME name=ILKFRAME style="display:none" src="dummy.php" width="800" height="600"></IFRAME>
<? 
        $ret="https://www.makelifebetter.co.kr/manager/pg/return.php";
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
<!--소스 추가 부분-->
   <INPUT type="hidden"   name=storeid value="<?echo $storeid?>">   
   <INPUT type="hidden"   name=ordernumber value="">   
   <INPUT type="hidden"   name=ordername value="">   
   <INPUT type="hidden"   name=email value="">   
   <INPUT type="hidden"   name=goodname value="">   
   <INPUT type="hidden"   name=phoneno value="">   
   <INPUT type="hidden"   name=adm_id value="<?echo $s_adm_id?>">   

   <INPUT type="hidden"   name="interest" value="">
   <INPUT type="hidden"   name="cardno" value="">
   <INPUT type="hidden"   name="expdt" value="">
   <INPUT type="hidden"   name=installment value="">   
<!-- 여기 까지 -->
   <INPUT type="hidden"   name=pan             size="19" maxlength="19" value="">
   <INPUT type="hidden"   name=expiry          size="6"  maxlength="6"  value="">
   <!--<INPUT type="hidden"   name=purchase_amount size="20" maxlength="20" value="<? echo ($amount) ?>">-->
   <INPUT type="hidden"   name=purchase_amount size="20" maxlength="20" value="">
   <!--<INPUT type="hidden"   name=amount          size="20" maxlength="20" value="<? echo ($amount) ?>">-->
   <INPUT type="hidden"   name=amount          size="20" maxlength="20" value="">
   <INPUT type="hidden"   name=description     size="80" maxlength="80" value="none">
   <INPUT type="hidden"   name=currency        size="3"  maxlength="3"  value="<? echo ($currencytype) ?>">
   <INPUT type="hidden"   name=recur_frequency size="4"  maxlength="4"  value="">
   <INPUT type="hidden"   name=recur_expiry    size="8"  maxlength="8"  value="">
   <INPUT type="hidden"   name=installments    size="4"  maxlength="4"  value="">   
   <INPUT type="hidden"   name=device_category size="20" maxlength="20" value="0">
   <INPUT type="hidden"   name="name"          size="20"                value="">
   <INPUT type="hidden"   name="url"           size="20"                value="http://www.makelifebetter.co.kr">
   <INPUT type="hidden"   name="country"       size="20"                value="410">
   <INPUT type="password" name="dummy"                                  value="">
   <INPUT type="hidden"   name="returnUrl"                              value="<? echo ($ret) ?>">


<!--   <INPUT type="hidden"   name="TrackII" value="">-->

</FORM>
</div>
<!------------------------------------ ILK Modification end ----------------------------------------->
</body>
</html>
