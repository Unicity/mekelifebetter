<?php
  header('Content-Type: text/html; charset=euc-kr');
?>
<?
	/*------------------------------------------------
	파일명      : KSPayCreditFormN.asp
	기능				: 승인/인증승인/MPI인증승인용 카드정보입력용 페이지
	-------------------------------------------------*/
	//신용카드승인종류 - A-인증없는승인, N-인증승인, M-MPI인증승인
	$certitype    = $_POST["certitype"] ;

	//기본거래정보
	$storeid      = $_POST["storeid"] ;      //상점아이디
	$ordername    = $_POST["ordername"] ;    //주문자명
	$ordernumber  = $_POST["ordernumber"] ;  //주문번호
	$amount       = $_POST["amount"] ;       //금액
	$goodname     = $_POST["goodname"] ;     //상품명
	$email        = $_POST["email"] ;        //주문자이메일
	$phoneno      = $_POST["phoneno"] ;      //주문자휴대폰번호
	$currencytype = $_POST["currencytype"] ; //통화구분 : "WON" : 원화, "USD" : 미화
	$interesttype = $_POST["interesttype"] ; //무이자구분 "NONE" : 무이자안함, "ALL" : 전체월 무이자, "3:6:9" : 3,6,9개월무이자

    //-------ISP 변수 start	
    $KVP_QUOTA_INF = "0:2:3:4:5:6:7:8:9:10:11:12";   //ISP용 할부개월수지정
    $KVP_NOINT_INF = "";

    //ISP용 무이자 할부개월 지정(BC:0100 / 국민:0204 / 수협:1800/ 전북:1600/ 광주:1500 )
    //Ex ) String KVP_NOINT_INF = "0204-3:4:5:6, 0100-3:4:5:6, 1800-3:4:5:6, 1600-3:4:5:6, 1500-3:4:5:6" ; - 각 카드사에 대해 3,4,5,6개월 할부건만 무이자처리
    //Ex ) String KVP_NOINT_INF ="ALL" - 모든개월수에 대하여 무이자처리함./ "NONE" - 모든개월수에 대하여 무이자처리하지않음.
    
    $KVP_CURRENCY = "";

    //interesttype으로 넘겨진 값으로 무이자처리
    if(strcmp($interesttype, "ALL") || strcmp($interesttype, "NONE")) $KVP_NOINT_INF = $interesttype;
    else if (strcmp($interesttype, "")) $KVP_NOINT_INF = "NONE";
    else $KVP_NOINT_INF = "0100-".$interesttype.",0204-".$interesttype.",1800-".$interesttype+",1600-".$interesttype+",1500-".$interesttype;      
    // -------------- ISP 변수 end
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html charset=euc-kr">
<title>KSPay</title>
<script language=javascript src="https://kspay.ksnet.to/popmpi/js/kspf_pop_mpi.js"></script>               <!-- MPI -->
<script language=javascript src="jquery-1.11.0.min.js" ></script> <!-- ISP -->
<script language=javascript src="https://www.vpay.co.kr/eISP/Wallet_layer_VP.js" charset="utf-8"></script> <!-- ISP -->
<script language="javascript">
<!--
// 이중승인의 가능성을 줄이기 위해 몇가지 이벤트를 막는다.
document.onmousedown=right;
document.onmousemove=right;

document.onkeypress = processKey;	
document.onkeydown  = processKey;

function processKey() { 
	if((event.ctrlKey == true && (event.keyCode == 8 || event.keyCode == 78 || event.keyCode == 82)) 
		|| ((typeof(event.srcElement.type) == "undefined" || typeof(event.srcElement.name) == "undefined" || event.srcElement.type != "text" || event.srcElement.name != "sndEmail") && event.keyCode >= 112 && event.keyCode <= 123)) {
		event.keyCode = 0; 
		event.cancelBubble = true; 
		event.returnValue = false; 
	} 
	if(event.keyCode == 8 && typeof(event.srcElement.value) == "undefined") {
		event.keyCode = 0; 
		event.cancelBubble = true; 
		event.returnValue = false; 
	} 
}

function right(e) {
	if(navigator.appName=='Netscape'&&(e.which==3||e.which==2)){
		alert('마우스 오른쪽 버튼을 사용할수 없습니다.');
		return;
	}else if(navigator.appName=='Microsoft Internet Explorer'&&(event.button==2||event.button==3)) {
		alert('마우스 오른쪽 버튼을 사용할수 없습니다.');
		return;
	}
}
-->
</script>

<script language="javascript">
	// ISP용 함수 start 
	function submit_isp(form) 
	{
		MakePayMessage(form);
	}
	
	function VP_Ret_Pay(ret) 
	{
		if(ret == true)
		{        
			document.KSPayAuthForm.certitype.value = "I";
			document.KSPayAuthForm.action= "KSPayCreditPostIMNA.php";
			document.KSPayAuthForm.submit();
		}
		else
		{
			alert("지불에 실패하였습니다.");
		}
	}
	function kbparamSet(ret, isptype, cardCode, quota,noint, cardPrefix, kb_app_otc)
	{
		if(ret == "TRUE"||ret == "true"||ret == true)
		{
			if(isptype=="2"){
			  submit_isp(document.KSPayAuthForm);
			}else{
				document.KSPayAuthForm.certitype.value = "I";
				document.KSPayAuthForm.KVP_CARDCODE.value = cardCode;
				document.KSPayAuthForm.KVP_QUOTA.value = quota;
				document.KSPayAuthForm.KVP_NOINT.value = noint;
				document.KSPayAuthForm.KVP_CARD_PREFIX.value = cardPrefix ;    // 승인에 필요한데이터는아님, 제휴카드 체크등으로 사용가능.
				document.KSPayAuthForm.kb_app_otc.value = kb_app_otc ;
				document.KSPayAuthForm.action= "KSPayCreditPostIMNA.php";
				document.KSPayAuthForm.submit();
			}
		}else{
			alert("지불에 실패하였습니다.");
		}
	}
	// ISP용 함수 end
	 
	/*** MPI 인증용 스크립트 ***/
	//유효기간추출하기
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
	function getReturnUrl(url)
	{
		var myloc = location.href;
		return myloc.substring(0, myloc.lastIndexOf('/')) + '/' + url;	
	}
	function next() 
	{
		var realform = document.KSPayAuthForm;
		
		var sIndex = realform.selectcard.value;

		// 카드사 선택 여부 체크
		if (sIndex == 0) {
			alert('결제하실 카드사를 선택하시기 바랍니다.');
			realform.selectcard.focus();
			return;
		}
		if(realform.selectcard.value == "0100" || realform.selectcard.value == "0204" || realform.selectcard.value == "1800" || realform.selectcard.value == "1600" || realform.selectcard.value == "1500" || realform.selectcard.value == "KA")
		{
			//ISP
			submitISP();
		}else{
			//MPI
			submitV3d();
		}
	}
	function submitISP(){
    var realform = document.KSPayAuthForm;
    var kbappform = document.kbapp_req;
		realform.KVP_CARDCOMPANY.value = realform.selectcard.value ;
		
		// 카카오뱅크관련  VP_BC_ISSUERCODE 추가.
		realform.VP_BC_ISSUERCODE.value = "";
		if(realform.selectcard.value == "0204"){
			realform.VP_BC_ISSUERCODE.value = "KBC";
			kbappform.kbapp_issue_code.value = "KBC";  // 국민카드팝업에서 국민카드만표시, 공백일경우 모두 표시됨.
			kbappform.returnUrl.value = getReturnUrl("kbapp_return.php");
			kbappform.submit();
		}else{ 
			if(realform.selectcard.value == "KA")
			{
				realform.KVP_CARDCOMPANY.value = "0204";
				realform.VP_BC_ISSUERCODE.value = realform.selectcard.value ; 
			}
			if(realform.selectcard.value == "16"){
				realform.KVP_CARDCOMPANY.value = "0100";
				realform.VP_BC_ISSUERCODE.value = "0100";
			}
			submit_isp(realform);
		}
	}
	// 결제 처리- MPI
	function submitV3d()
	{
		var frm = document.Visa3d;
		var realform = document.KSPayAuthForm;
		
		SetInstallment(realform);

		//MPI 인증용 유효기간을 expiry에 세팅한다(YYMM 형태로 세팅하여야 한다.).
		frm.expiry.value = "4912";     //MPI 의 경우는 "4912"로 세팅
		realform.expdt.value = "4912"; //MPI 의 경우는 "4912"로 세팅

		var sIndex = realform.selectcard.value;

		// 카드사 선택 여부 체크
		if (sIndex == 0) {
			alert('결제하실 카드사를 선택하시기 바랍니다.');
			realform.selectcard.focus();
			return;
		}

		frm.instType.value = realform.interest.value;	
		frm.cardcode.value = realform.selectcard.value ;
		frm.returnUrl.value = getReturnUrl("return.php");
		
		_KSP_CALL_MPI(frm ,paramSet);   // 인증페이지 호출
	}
	/* 실제 승인페이지로 넘겨주는 form에 xid, eci, cavv, cardno를 세팅한다 */
	function paramSet()
	{
		var frm = document.KSPayAuthForm;
    
		var r_array;
		r_array = arguments[0].split('|');    

		if(r_array[6] == "Y")
		{
			frm.KVP_CARDCOMPANY.value = "0100" ;
			submitISP();
		}else{
			frm.certitype.value = "M" ;
			frm.xid.value = r_array[1];
			frm.eci.value = r_array[2];
			frm.cavv.value = r_array[3];
			frm.cardno.value = r_array[4];
			proceed(r_array[0]);	
		}
	}
	/* realSubmit을 진행할 것인가 아닌가를 판단하는 함수. 이 함수의 호출은 승인 페이지가 아닌 return.php로 하게 되며, 
	페이지가 받아두었던 인증값 파라메터들과 리얼서브밋진행여부를 받아 승인페이지로 되넘겨준다. */
	function proceed(arg)
	{
		var frm = document.KSPayAuthForm;
		var xid = frm.xid.value;
		var eci = frm.eci.value;
		var cavv = frm.cavv.value;
		var cardno = frm.cardno.value;

		if ((arg == "TRUE"||arg == "true"||arg == true) && check_param(xid, eci, cavv, cardno))
		{
			submitAuth() ;
		}
		else
		{
			//alert("인증실패") ;
		}
	}
	function check_param(xid, eci, cavv, cardno)
	{
	
		var ck_mpi = get_cookie("xid_eci_cavv_cardno");
	
		if (ck_mpi == xid + eci + cavv + cardno)
		{
			return false;
		}

		set_cookie("xid_eci_cavv_cardno", xid + eci + cavv + cardno);

		ck_mpi = get_cookie("xid_eci_cavv_cardno");
	
		return true;
	}
	function get_cookie(strName)
	{
		var strSearch = strName + "=";
		if ( document.cookie.length > 0 )
		{
			iOffset = document.cookie.indexOf( strSearch );
			if ( iOffset != -1 ) 
			{
				iOffset += strSearch.length;
				iEnd = document.cookie.indexOf( ";", iOffset );
				if ( iEnd == -1 )
					iEnd = document.cookie.length;

				return unescape(document.cookie.substring( iOffset, iEnd ));
			}
		}

		return "";
	}
	function set_cookie(strName, strValue) 
	{ 
	
		var strCookie = strName + "=" + escape(strValue);
		document.cookie = strCookie;
	}
	/*** MPI 인증용 스크립트 끝 ***/
	function submitAuth()
	{
		var frm = document.KSPayAuthForm;	
		alert("3");
		SetInstallment(frm);

		if (frm.expdt.value != "4912") // MPI결제가 아닌경우만 사용 , MPI의 경우는 위에서 "4912"로 세팅
		{
			frm.expdt.value	= getValue("year").substring(2) + getValue("month");
		}
		frm.action			= "KSPayCreditPostIMNA.php";
		frm.submit();
	}
	function SetInstallment(form)
	{
		var sInstallment = form.installment.value;
		var sInteresttype = form.interesttype.value.split(":");
		
		sInstallment = (sInstallment != null && sInstallment.length == 2) ? sInstallment.substring(0,2) : "00";
		
		if((sInstallment != "00" && sInstallment != "01" && sInstallment != "60" && sInstallment !="61")&& form.amount.value < 50000) {
			alert("50,000원 이상만 할부 가능합니다.");
			form.installment.value = form.installment.options[0].value;
			form.interestname.value = "";
			return;
		}
		
		if(sInteresttype[0] == "ALL")
		{
			if (sInstallment != "00")
				form.interestname.value = "무이자할부";
			else 
				form.interestname.value = "";
			
			form.interest.value = 2;
			return;
		}
		else if (sInteresttype[0] == "NONE" || sInteresttype[0] == "" || sInteresttype[0].substring(0,1) == " ")
		{
			if (sInstallment != "00")
				form.interestname.value = "일반할부";
			else 
				form.interestname.value = "";
			
			form.interest.value = 1;	
			return;
		}
		
		for (i=0; i < sInteresttype.length; i++)
		{
			if (sInteresttype[i].length == 1) 
				sInteresttype[i] = "0"+sInteresttype[i];
			else if (sInteresttype[i].length > 2) 
				sInteresttype[i] = sInteresttype[i].substring(0,2);
			
			
			if (sInteresttype[i] == sInstallment)
			{
				if (sInstallment != "00")
					form.interestname.value = "무이자할부";
				else 
					form.interestname.value = "";
				
				form.interest.value = 2;
				break;
				
			}
			else
			{
				if (sInstallment != "00")
					form.interestname.value = "일반할부";
				else 
					form.interestname.value = "";
				
				form.interest.value = 1;
			}
		}
	}
	function set_card(val)
	{
		if(val == "0100" || "0204" || "1800" || "1600" || "1500" || "16" || "KA" )
		{
			//할부비활성.
			document.getElementById("inst").style.display = "none" ;
		}else{
			document.getElementById("inst").style.display = "" ;
		}
	}
	
</script>
<style type="text/css">
	TABLE{font-size:9pt; line-height:160%;}
	A {color:blueline-height:160% background-color:#E0EFFE}
	INPUT{font-size:9pt}
	SELECT{font-size:9pt}
	.emp{background-color:#FDEAFE}
	.white{background-color:#FFFFFF color:black border:1x solid white font-size: 9pt}
</style>
</head>

<body topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 onFocus="" >

<form name="KSPayAuthForm" method="post">
<!-- 공통 ---------------------------------------------------->
<input type=hidden name="expdt"          value="">
<input type=hidden name="email"          value="<?echo($email)?>">
<input type=hidden name="phoneno"        value="<?echo($phoneno)?>">
<input type=hidden name="interest"       value="">
<input type=hidden name="interesttype"   value="<?echo($interesttype)?>">
<input type=hidden name="ordernumber"    value="<?echo($ordernumber)?>">
<input type=hidden name="ordername"      value="<?echo($ordername)?>">
<input type=hidden name="goodname"       value="<?echo($goodname)?>">
<input type=hidden name="amount"         value="<?echo($amount)?>">
<input type=hidden name="currencytype"   value="<?echo($currencytype)?>">
<input type="hidden" name="storeid"       value="<?echo($storeid)?>">
<input type="hidden" name="certitype"     value="<?echo($certitype)?>">
<!--ISP------------------------------------------------------------>
<input type="hidden" name="KVP_PGID"         value="A0029">               <!-- PG -->
<input type="hidden" name="KVP_SESSIONKEY"   value="">                    <!-- 세션키  --> 
<input type="hidden" name="KVP_ENCDATA"      value="">                    <!-- 암호된데이터 -->
<input type="hidden" name="KVP_CURRENCY"     value="<?echo($currencytype)?>">   <!-- 지불 화폐 단위 (WON/USD) : 한화 - WON, 미화 - USD-->
<input type="hidden" name="KVP_NOINT"        value="">                    <!-- 무이자구분(1:무이자,0:일반) -->
<input type="hidden" name="KVP_QUOTA"        value="">                    <!-- 할부 -->
<input type="hidden" name="KVP_CARDCODE"     value="">                    <!-- 카드코드 -->
<input type="hidden" name="KVP_CONAME"       value="">                    <!-- 카드명 -->
<input type="hidden" name="KVP_RESERVED1"    value="">                    <!-- 예비1 -->
<input type="hidden" name="KVP_RESERVED2"    value="">                    <!-- 예비2 -->
<input type="hidden" name="KVP_RESERVED3"    value="">                    <!-- 예비3 -->
<input type="hidden" name="KVP_IMGURL"       value="">
<input type="hidden" name="KVP_QUOTA_INF"    value="<?echo($KVP_QUOTA_INF)?>">  <!--할부값-->
<input type="hidden" name="KVP_GOODNAME"     value="<?echo($goodname)?>">       <!--상품명-->
<input type="hidden" name="KVP_PRICE"        value="<?echo($amount)?>">         <!--금액-->
<input type="hidden" name="KVP_NOINT_INF"    value="<?echo($KVP_NOINT_INF)?>">  <!--일반, 무이자-->
<input type="hidden" name="KVP_CARDCOMPANY"  value="">
<input type="hidden" name="VP_BC_ISSUERCODE" value="">
<!--ISP------------------------------------------------------------>

<!--KBAPP------------------------------------------------------------>
<input type="hidden" name="KVP_CARD_PREFIX"  value="">  <!--  cardbin -->
<input type="hidden" name="kb_app_otc"       value="">
<!--KBAPP------------------------------------------------------------>

<!--MPI---------------------------------------------------------------->
<input type="hidden" name="xid"  value="">
<input type="hidden" name="eci"  value="">
<input type="hidden" name="cavv" value="">
<!--MPI---------------------------------------------------------------->

<?if($certitype=="IM"){?>
<input type="hidden" name="cardno"           value="">
<?}?>

<table border=0 width=0>
<tr>
<td align=center>
<table width=280 cellspacing=0 cellpadding=0 border=0 bgcolor=#4F9AFF>
<tr>
<td>
<table width=100% cellspacing=1 cellpadding=2 border=0>
<tr bgcolor=#4F9AFF height=25>
<td align=left><font color="#FFFFFF">
KSPay 신용카드 승인&nbsp;
<?
	if($certitype == "A")      echo("(인증없는승인)") ;
	else if($certitype == "N") echo("(일반인증승인)") ;
	else if($certitype == "IM") echo("(MPI,ISP인증승인)") ;
?>    
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

<!-- MPI 인증승인이면 MPI 인증 스크립트를 사용한다 -->
<?if($certitype == "IM"){?>
<tr>
	<td>신용카드종류 :</td>
	<td>
		<select name="selectcard" onchange="set_card(this.value)">
			<option value="0" selected>카드를 선택하세요</option>
			<option value="1">하나(구.외환)카드</option>
			<option value="2">삼성카드</option>
			<option value="4">현대카드</option>
			<option value="5">롯데카드</option>
			<option value="6">신한카드</option>
			<option value="7">씨티카드</option>		
			<option value="11">하나(구.하나SK)카드</option>
			<option value="14">농협카드</option>
			<option value="0100">비씨</option>
			<option value="0204">국민</option>
			<option value="1800">수협</option>
			<option value="1600">전북</option>
			<option value="1500">광주</option>     
			<option value="16">우리</option> 
			<option value="KA">카카오뱅크</option> 
		</select>
	</td>
</tr>
<?}?>

<!--MPI가 아닐때 카드번호/유효기간을 세팅.-->
<?if($certitype == "N" || $certitype == "A"){?>
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
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12" selected>12</option>
		</select>월
	</td>
</tr>
<?}?>

<tr id="inst">
	<td>할부 :</td>
	<td>
		<select name="installment" onchange="return SetInstallment(this.form);">
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
		<input type=text name=interestname size="10" readonly value="" style="border:0px" >
	</td>
</tr>
<!-- MPI 인증승인이면 MPI 인증 스크립트를 사용한다 -->
<?if($certitype == "IM"){?>
	<input type=hidden name=lastidnum value="">
	<input type=hidden name=passwd    value="">
<tr>
	<td colspan=2 align=center>
			<input type=button onclick="javascript:next()" value="인증승인">
	</td>
</tr>
<!-- 일반인증승인-->
<?}else if($certitype == "N"){?>
<tr>
	<td>생년월일(YYMMDD) :</td>
	<td>
		<input type=text name=lastidnum size=10 maxlength=6 value=""> - XXXXXXX 
	</td>
</tr>
<tr>
	<td>비밀번호 앞 두자리 :</td>
	<td><input type=password name=passwd size=4 maxlength=2 value="">XX</td>
</tr>
<tr>
	<td colspan=2 align=center>
		<input type=button onclick="javascript:submitAuth()" value="일반인증승인">
	</td>
</tr>
<!-- 인증없는승인-->
<?}else if($certitype == "A"){?>
	<input type=hidden name=lastidnum value="">
	<input type=hidden name=passwd    value="">
<tr>
	<td colspan=2 align=center>
		<input type=button onclick="javascript:submitAuth()" value="인증없는승인">
	</td>
</tr>
<?}?>

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
<?
	$server_protocal_tmp = explode("/", $_SERVER["SERVER_PROTOCOL"]);
    $server_protocal = $server_protocal_tmp[0];
    $http_host = $_SERVER["HTTP_HOST"];

    $path_info_tmp = explode("/", $_SERVER["SCRIPT_NAME"]);
    $path_info = "";
    for($i = 0; $i < count($path_info_tmp)-1; $i++) 
	{
		$path_info .= $path_info_tmp[$i]."/";
    }
    $ret = $server_protocal."://".$http_host.$path_info."return.php";

    if($currencytype == "WON"||$currencytype == null||$currencytype == "") $currencytype = "410" ;	//미화
    else if($currencytype == "USD")                           	           $currencytype = "840" ;  //원화
?>
<div style="display:none"> 
<FORM name=Visa3d action="" method=post>
   <INPUT type="hidden"   name=pan             value=""                     size="19" maxlength="19">
   <INPUT type="hidden"   name=expiry          value=""                     size="6"  maxlength="6">
   <INPUT type="hidden"   name=purchase_amount value="<?echo($amount)?>"          size="20" maxlength="20">
   <INPUT type="hidden"   name=amount          value="<?echo($amount)?>"          size="20" maxlength="20">
   <INPUT type="hidden"   name=description     value="none"                 size="80" maxlength="80">
   <INPUT type="hidden"   name=currency        value="<?echo($currencytype)?>"    size="3"  maxlength="3"	>
   <INPUT type="hidden"   name=recur_frequency value=""                     size="4"  maxlength="4"	>
   <INPUT type="hidden"   name=recur_expiry    value=""                     size="8"  maxlength="8"	>
   <INPUT type="hidden"   name=installments    value=""                     size="4"  maxlength="4"	>   
   <INPUT type="hidden"   name=device_category value="0"                    size="20" maxlength="20">
   <INPUT type="hidden"   name="name"          value="test store"           size="20">   <!--회사명을 영어로 넣어주세요(최대20byte)-->
   <INPUT type="hidden"   name="url"           value="http://www.store.com" size="20">   <!-- 회사 도메인을 http://를 포함해서 넣어주세요-->
   <INPUT type="hidden"   name="country"       value="410"                  size="20">
   <INPUT type="password" name="dummy"         value="">
   <INPUT type="hidden"   name="returnUrl"     value="<?echo($ret)?>">   <!--MPI인증후 결과값을받을 페이지-->
   <input type="hidden"   name=cardcode        value="">
   <input type="hidden"   name="merInfo"       value="<?echo($storeid)?>">
   <input type="hidden"   name="bizNo"         value="1208197322">
   <input type="hidden"   name="instType"      value="">
</FORM>
</div>
<!------------------------------------ ILK Modification end ----------------------------------------->
<!-- kbapp카드 추가 START -->
<form id="kbapp_req" name="kbapp_req" method="post" target="kframe" action="https://kspay.ksnet.to/store/PAY_PROXY/credit/KBAPP/KSPayKbapp.jsp">
	<input type="hidden" name="storeid"             value="<?echo($storeid)?>"/>                                                         <!-- 상점아이디 -->
	<input type="hidden" name="returnUrl"           value=""/>                                                                     <!-- returnUrl --> 
	<input type="hidden" name="kbapp_pay_type"      value="1"/>                                                                    <!-- 1. 온라인, 2. 모바일, 3: 오프라인 -->
	<input type="hidden" name="kbapp_shop_name"     value="테스트상점"/>                                                           <!-- 상점명  --> 
	<input type="hidden" name="kbapp_amount"        value="<?echo($amount)?>"/>                                                          <!-- 승인금액  --> 
	<input type="hidden" name="kbapp_currency_type" value="410"/>                                                                  <!-- 통화코드 840 , 410 -->
	<input type="hidden" name="kbapp_entr_numb"     value="1208197322"/>                                                           <!-- 사업자 번호 -->
	<input type="hidden" name="kbapp_noint_inf"     value="<?echo($KVP_NOINT_INF)?>"/>                                                   <!-- 일반, 무이자  -->
	<input type="hidden" name="kbapp_quota_inf"     value="<?echo($KVP_QUOTA_INF)?>"/>                                                   <!-- 할부값  -->
	<input type="hidden" name="kbapp_order_no"      value="<?echo($ordernumber)?>"/>                                                     <!-- 주문번호  -->
	<input type="hidden" name="kbapp_is_liquidity"  value=""/>                                                                     <!-- 환금성 상품 여부 ("Y": 환금성 상품, "N" 또는 "": 환금성 상품 아님)  -->
	<input type="hidden" name="kbapp_good_name"     value="<?echo($goodname)?>"/>   																										 <!-- 상품명  -->
	<input type="hidden" name="kbapp_issue_code"    value=""/>                                                                     <!-- KBC : 국민카드만표시 -->
</form>
<iframe name="kframe" id="kframe" src="" width="0" height="0" frameBorder="0" style="display:none"></iframe>
<!-- kbapp카드 추가 END -->
</body>
</html>