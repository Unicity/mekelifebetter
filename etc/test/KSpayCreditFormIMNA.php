<?php
  header('Content-Type: text/html; charset=euc-kr');
?>
<?
	/*------------------------------------------------
	���ϸ�      : KSPayCreditFormN.asp
	���				: ����/��������/MPI�������ο� ī�������Է¿� ������
	-------------------------------------------------*/
	//�ſ�ī��������� - A-�������½���, N-��������, M-MPI��������
	$certitype    = $_POST["certitype"] ;

	//�⺻�ŷ�����
	$storeid      = $_POST["storeid"] ;      //�������̵�
	$ordername    = $_POST["ordername"] ;    //�ֹ��ڸ�
	$ordernumber  = $_POST["ordernumber"] ;  //�ֹ���ȣ
	$amount       = $_POST["amount"] ;       //�ݾ�
	$goodname     = $_POST["goodname"] ;     //��ǰ��
	$email        = $_POST["email"] ;        //�ֹ����̸���
	$phoneno      = $_POST["phoneno"] ;      //�ֹ����޴�����ȣ
	$currencytype = $_POST["currencytype"] ; //��ȭ���� : "WON" : ��ȭ, "USD" : ��ȭ
	$interesttype = $_POST["interesttype"] ; //�����ڱ��� "NONE" : �����ھ���, "ALL" : ��ü�� ������, "3:6:9" : 3,6,9����������

    //-------ISP ���� start	
    $KVP_QUOTA_INF = "0:2:3:4:5:6:7:8:9:10:11:12";   //ISP�� �Һΰ���������
    $KVP_NOINT_INF = "";

    //ISP�� ������ �Һΰ��� ����(BC:0100 / ����:0204 / ����:1800/ ����:1600/ ����:1500 )
    //Ex ) String KVP_NOINT_INF = "0204-3:4:5:6, 0100-3:4:5:6, 1800-3:4:5:6, 1600-3:4:5:6, 1500-3:4:5:6" ; - �� ī��翡 ���� 3,4,5,6���� �ҺΰǸ� ������ó��
    //Ex ) String KVP_NOINT_INF ="ALL" - ��簳������ ���Ͽ� ������ó����./ "NONE" - ��簳������ ���Ͽ� ������ó����������.
    
    $KVP_CURRENCY = "";

    //interesttype���� �Ѱ��� ������ ������ó��
    if(strcmp($interesttype, "ALL") || strcmp($interesttype, "NONE")) $KVP_NOINT_INF = $interesttype;
    else if (strcmp($interesttype, "")) $KVP_NOINT_INF = "NONE";
    else $KVP_NOINT_INF = "0100-".$interesttype.",0204-".$interesttype.",1800-".$interesttype+",1600-".$interesttype+",1500-".$interesttype;      
    // -------------- ISP ���� end
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
// ���߽����� ���ɼ��� ���̱� ���� ��� �̺�Ʈ�� ���´�.
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
		alert('���콺 ������ ��ư�� ����Ҽ� �����ϴ�.');
		return;
	}else if(navigator.appName=='Microsoft Internet Explorer'&&(event.button==2||event.button==3)) {
		alert('���콺 ������ ��ư�� ����Ҽ� �����ϴ�.');
		return;
	}
}
-->
</script>

<script language="javascript">
	// ISP�� �Լ� start 
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
			alert("���ҿ� �����Ͽ����ϴ�.");
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
				document.KSPayAuthForm.KVP_CARD_PREFIX.value = cardPrefix ;    // ���ο� �ʿ��ѵ����ʹ¾ƴ�, ����ī�� üũ������ ��밡��.
				document.KSPayAuthForm.kb_app_otc.value = kb_app_otc ;
				document.KSPayAuthForm.action= "KSPayCreditPostIMNA.php";
				document.KSPayAuthForm.submit();
			}
		}else{
			alert("���ҿ� �����Ͽ����ϴ�.");
		}
	}
	// ISP�� �Լ� end
	 
	/*** MPI ������ ��ũ��Ʈ ***/
	//��ȿ�Ⱓ�����ϱ�
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

		// ī��� ���� ���� üũ
		if (sIndex == 0) {
			alert('�����Ͻ� ī��縦 �����Ͻñ� �ٶ��ϴ�.');
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
		
		// īī����ũ����  VP_BC_ISSUERCODE �߰�.
		realform.VP_BC_ISSUERCODE.value = "";
		if(realform.selectcard.value == "0204"){
			realform.VP_BC_ISSUERCODE.value = "KBC";
			kbappform.kbapp_issue_code.value = "KBC";  // ����ī���˾����� ����ī�常ǥ��, �����ϰ�� ��� ǥ�õ�.
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
	// ���� ó��- MPI
	function submitV3d()
	{
		var frm = document.Visa3d;
		var realform = document.KSPayAuthForm;
		
		SetInstallment(realform);

		//MPI ������ ��ȿ�Ⱓ�� expiry�� �����Ѵ�(YYMM ���·� �����Ͽ��� �Ѵ�.).
		frm.expiry.value = "4912";     //MPI �� ���� "4912"�� ����
		realform.expdt.value = "4912"; //MPI �� ���� "4912"�� ����

		var sIndex = realform.selectcard.value;

		// ī��� ���� ���� üũ
		if (sIndex == 0) {
			alert('�����Ͻ� ī��縦 �����Ͻñ� �ٶ��ϴ�.');
			realform.selectcard.focus();
			return;
		}

		frm.instType.value = realform.interest.value;	
		frm.cardcode.value = realform.selectcard.value ;
		frm.returnUrl.value = getReturnUrl("return.php");
		
		_KSP_CALL_MPI(frm ,paramSet);   // ���������� ȣ��
	}
	/* ���� ������������ �Ѱ��ִ� form�� xid, eci, cavv, cardno�� �����Ѵ� */
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
	/* realSubmit�� ������ ���ΰ� �ƴѰ��� �Ǵ��ϴ� �Լ�. �� �Լ��� ȣ���� ���� �������� �ƴ� return.php�� �ϰ� �Ǹ�, 
	�������� �޾Ƶξ��� ������ �Ķ���͵�� ���󼭺�����࿩�θ� �޾� ������������ �ǳѰ��ش�. */
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
			//alert("��������") ;
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
	/*** MPI ������ ��ũ��Ʈ �� ***/
	function submitAuth()
	{
		var frm = document.KSPayAuthForm;	
		alert("3");
		SetInstallment(frm);

		if (frm.expdt.value != "4912") // MPI������ �ƴѰ�츸 ��� , MPI�� ���� ������ "4912"�� ����
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
			alert("50,000�� �̻� �Һ� �����մϴ�.");
			form.installment.value = form.installment.options[0].value;
			form.interestname.value = "";
			return;
		}
		
		if(sInteresttype[0] == "ALL")
		{
			if (sInstallment != "00")
				form.interestname.value = "�������Һ�";
			else 
				form.interestname.value = "";
			
			form.interest.value = 2;
			return;
		}
		else if (sInteresttype[0] == "NONE" || sInteresttype[0] == "" || sInteresttype[0].substring(0,1) == " ")
		{
			if (sInstallment != "00")
				form.interestname.value = "�Ϲ��Һ�";
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
					form.interestname.value = "�������Һ�";
				else 
					form.interestname.value = "";
				
				form.interest.value = 2;
				break;
				
			}
			else
			{
				if (sInstallment != "00")
					form.interestname.value = "�Ϲ��Һ�";
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
			//�Һκ�Ȱ��.
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
<!-- ���� ---------------------------------------------------->
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
<input type="hidden" name="KVP_SESSIONKEY"   value="">                    <!-- ����Ű  --> 
<input type="hidden" name="KVP_ENCDATA"      value="">                    <!-- ��ȣ�ȵ����� -->
<input type="hidden" name="KVP_CURRENCY"     value="<?echo($currencytype)?>">   <!-- ���� ȭ�� ���� (WON/USD) : ��ȭ - WON, ��ȭ - USD-->
<input type="hidden" name="KVP_NOINT"        value="">                    <!-- �����ڱ���(1:������,0:�Ϲ�) -->
<input type="hidden" name="KVP_QUOTA"        value="">                    <!-- �Һ� -->
<input type="hidden" name="KVP_CARDCODE"     value="">                    <!-- ī���ڵ� -->
<input type="hidden" name="KVP_CONAME"       value="">                    <!-- ī��� -->
<input type="hidden" name="KVP_RESERVED1"    value="">                    <!-- ����1 -->
<input type="hidden" name="KVP_RESERVED2"    value="">                    <!-- ����2 -->
<input type="hidden" name="KVP_RESERVED3"    value="">                    <!-- ����3 -->
<input type="hidden" name="KVP_IMGURL"       value="">
<input type="hidden" name="KVP_QUOTA_INF"    value="<?echo($KVP_QUOTA_INF)?>">  <!--�Һΰ�-->
<input type="hidden" name="KVP_GOODNAME"     value="<?echo($goodname)?>">       <!--��ǰ��-->
<input type="hidden" name="KVP_PRICE"        value="<?echo($amount)?>">         <!--�ݾ�-->
<input type="hidden" name="KVP_NOINT_INF"    value="<?echo($KVP_NOINT_INF)?>">  <!--�Ϲ�, ������-->
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
KSPay �ſ�ī�� ����&nbsp;
<?
	if($certitype == "A")      echo("(�������½���)") ;
	else if($certitype == "N") echo("(�Ϲ���������)") ;
	else if($certitype == "IM") echo("(MPI,ISP��������)") ;
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
	<td>��ǰ�� :</td>
	<td><?echo($goodname)?></td>
</tr>
<tr>
	<td>�ݾ� :</td>
	<td><?echo($amount)?></td>
</tr>
<tr>
	<td colspan=3><hr noshade size=1></td>
</tr>

<!-- MPI ���������̸� MPI ���� ��ũ��Ʈ�� ����Ѵ� -->
<?if($certitype == "IM"){?>
<tr>
	<td>�ſ�ī������ :</td>
	<td>
		<select name="selectcard" onchange="set_card(this.value)">
			<option value="0" selected>ī�带 �����ϼ���</option>
			<option value="1">�ϳ�(��.��ȯ)ī��</option>
			<option value="2">�Ｚī��</option>
			<option value="4">����ī��</option>
			<option value="5">�Ե�ī��</option>
			<option value="6">����ī��</option>
			<option value="7">��Ƽī��</option>		
			<option value="11">�ϳ�(��.�ϳ�SK)ī��</option>
			<option value="14">����ī��</option>
			<option value="0100">��</option>
			<option value="0204">����</option>
			<option value="1800">����</option>
			<option value="1600">����</option>
			<option value="1500">����</option>     
			<option value="16">�츮</option> 
			<option value="KA">īī����ũ</option> 
		</select>
	</td>
</tr>
<?}?>

<!--MPI�� �ƴҶ� ī���ȣ/��ȿ�Ⱓ�� ����.-->
<?if($certitype == "N" || $certitype == "A"){?>
<tr>
	<td>�ſ�ī�� :</td>
	<td>
		<input type=text name=cardno size=20 maxlength=16 value="">
	</td>
</tr>
<tr>
	<td>��ȿ�Ⱓ :</td>
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
		</select>��/
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
		</select>��
	</td>
</tr>
<?}?>

<tr id="inst">
	<td>�Һ� :</td>
	<td>
		<select name="installment" onchange="return SetInstallment(this.form);">
			<option value="00" selected>�Ͻú�</option>
			<option value="02">02����</option>
			<option value="03">03����</option>
			<option value="04">04����</option>
			<option value="05">05����</option>
			<option value="06">06����</option>
			<option value="07">07����</option>
			<option value="08">08����</option>
			<option value="09">09����</option>
			<option value="10">10����</option>
			<option value="11">11����</option>
			<option value="12">12����</option>
		</select>
		<input type=text name=interestname size="10" readonly value="" style="border:0px" >
	</td>
</tr>
<!-- MPI ���������̸� MPI ���� ��ũ��Ʈ�� ����Ѵ� -->
<?if($certitype == "IM"){?>
	<input type=hidden name=lastidnum value="">
	<input type=hidden name=passwd    value="">
<tr>
	<td colspan=2 align=center>
			<input type=button onclick="javascript:next()" value="��������">
	</td>
</tr>
<!-- �Ϲ���������-->
<?}else if($certitype == "N"){?>
<tr>
	<td>�������(YYMMDD) :</td>
	<td>
		<input type=text name=lastidnum size=10 maxlength=6 value=""> - XXXXXXX 
	</td>
</tr>
<tr>
	<td>��й�ȣ �� ���ڸ� :</td>
	<td><input type=password name=passwd size=4 maxlength=2 value="">XX</td>
</tr>
<tr>
	<td colspan=2 align=center>
		<input type=button onclick="javascript:submitAuth()" value="�Ϲ���������">
	</td>
</tr>
<!-- �������½���-->
<?}else if($certitype == "A"){?>
	<input type=hidden name=lastidnum value="">
	<input type=hidden name=passwd    value="">
<tr>
	<td colspan=2 align=center>
		<input type=button onclick="javascript:submitAuth()" value="�������½���">
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

    if($currencytype == "WON"||$currencytype == null||$currencytype == "") $currencytype = "410" ;	//��ȭ
    else if($currencytype == "USD")                           	           $currencytype = "840" ;  //��ȭ
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
   <INPUT type="hidden"   name="name"          value="test store"           size="20">   <!--ȸ����� ����� �־��ּ���(�ִ�20byte)-->
   <INPUT type="hidden"   name="url"           value="http://www.store.com" size="20">   <!-- ȸ�� �������� http://�� �����ؼ� �־��ּ���-->
   <INPUT type="hidden"   name="country"       value="410"                  size="20">
   <INPUT type="password" name="dummy"         value="">
   <INPUT type="hidden"   name="returnUrl"     value="<?echo($ret)?>">   <!--MPI������ ����������� ������-->
   <input type="hidden"   name=cardcode        value="">
   <input type="hidden"   name="merInfo"       value="<?echo($storeid)?>">
   <input type="hidden"   name="bizNo"         value="1208197322">
   <input type="hidden"   name="instType"      value="">
</FORM>
</div>
<!------------------------------------ ILK Modification end ----------------------------------------->
<!-- kbappī�� �߰� START -->
<form id="kbapp_req" name="kbapp_req" method="post" target="kframe" action="https://kspay.ksnet.to/store/PAY_PROXY/credit/KBAPP/KSPayKbapp.jsp">
	<input type="hidden" name="storeid"             value="<?echo($storeid)?>"/>                                                         <!-- �������̵� -->
	<input type="hidden" name="returnUrl"           value=""/>                                                                     <!-- returnUrl --> 
	<input type="hidden" name="kbapp_pay_type"      value="1"/>                                                                    <!-- 1. �¶���, 2. �����, 3: �������� -->
	<input type="hidden" name="kbapp_shop_name"     value="�׽�Ʈ����"/>                                                           <!-- ������  --> 
	<input type="hidden" name="kbapp_amount"        value="<?echo($amount)?>"/>                                                          <!-- ���αݾ�  --> 
	<input type="hidden" name="kbapp_currency_type" value="410"/>                                                                  <!-- ��ȭ�ڵ� 840 , 410 -->
	<input type="hidden" name="kbapp_entr_numb"     value="1208197322"/>                                                           <!-- ����� ��ȣ -->
	<input type="hidden" name="kbapp_noint_inf"     value="<?echo($KVP_NOINT_INF)?>"/>                                                   <!-- �Ϲ�, ������  -->
	<input type="hidden" name="kbapp_quota_inf"     value="<?echo($KVP_QUOTA_INF)?>"/>                                                   <!-- �Һΰ�  -->
	<input type="hidden" name="kbapp_order_no"      value="<?echo($ordernumber)?>"/>                                                     <!-- �ֹ���ȣ  -->
	<input type="hidden" name="kbapp_is_liquidity"  value=""/>                                                                     <!-- ȯ�ݼ� ��ǰ ���� ("Y": ȯ�ݼ� ��ǰ, "N" �Ǵ� "": ȯ�ݼ� ��ǰ �ƴ�)  -->
	<input type="hidden" name="kbapp_good_name"     value="<?echo($goodname)?>"/>   																										 <!-- ��ǰ��  -->
	<input type="hidden" name="kbapp_issue_code"    value=""/>                                                                     <!-- KBC : ����ī�常ǥ�� -->
</form>
<iframe name="kframe" id="kframe" src="" width="0" height="0" frameBorder="0" style="display:none"></iframe>
<!-- kbappī�� �߰� END -->
</body>
</html>