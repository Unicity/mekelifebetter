<!------------------------------------------------------------------------------
 FILE NAME : AuthForm.php
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2004/02/13
 USE WITH : AuthForm.php
                                                         http://www.kspay.co.kr
                                                         http://www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
<!--이 페이지에 있는 모든 파라메터는 지우시거나 변경하시면 결제가 이루어지지 않습니다.-->
<html>
<head>
<title>KSPay</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<script src="/inc/unicity_ssl.js" language="javascript"></script>
<script language="javascript">
<!--
	function goOpen() { 

		var realform = document.KSPayWeb;
		
		if (realform.sndOrdernumber.value =="") {
			alert("주문번호를 입력하세요.");
			realform.sndOrdernumber.focus();
			return;	
		}

		if (realform.sndGoodname.value =="") {
			alert("상품명을 입력하세요.");
			realform.sndGoodname.focus();
			return;	
		}

		if (realform.sndAmount.value =="") {
			alert("결제금액을 입력하세요.");
			realform.sndAmount.focus();
			return;	
		}

		if(!isNumber(realform.sndAmount)) {
			alert("숫자만 입력해 주세요.");
			realform.sndAmount.focus();
			return;
		}

		if (realform.sndOrdername.value =="") {
			alert("주문자를 입력하세요.");
			realform.sndOrdername.focus();
			return;	
		}

		if(!isNumber(realform.sndMobile)) {
			alert("'-' 없이 숫자만 입력해 주세요.");
			realform.sndMobile.focus();
			return;
		}

        document.authFrmFrame.sndOrdernumber.value     = document.KSPayWeb.sndOrdernumber.value;
		document.authFrmFrame.sndGoodname.value        = document.KSPayWeb.sndGoodname.value;
		document.authFrmFrame.sndInstallmenttype.value = document.KSPayWeb.sndInstallmenttype.value;
		document.authFrmFrame.sndAmount.value          = document.KSPayWeb.sndAmount.value;
		document.authFrmFrame.sndOrdername.value       = document.KSPayWeb.sndOrdername.value;
		document.authFrmFrame.sndAllregid.value        = document.KSPayWeb.sndAllregid.value;
		document.authFrmFrame.sndEmail.value           = document.KSPayWeb.sndEmail.value;
		document.authFrmFrame.sndMobile.value          = document.KSPayWeb.sndMobile.value;
		document.authFrmFrame.sndInteresttype.value    = document.KSPayWeb.sndInteresttype.value;
		document.authFrmFrame.sndCurrencytype.value    = document.KSPayWeb.sndCurrencytype.value;
		document.authFrmFrame.sndWptype.value          = document.KSPayWeb.sndWptype.value;
		document.authFrmFrame.sndAdulttype.value       = document.KSPayWeb.sndAdulttype.value;

		var height_ = 420;
		var width_ = 400;
		var left_ = screen.width;
		var top_ = screen.height;
		
		left_ = left_/2 - (width_/2);
		top_ = top_/2 - (height_/2);
		
		src = window.open('about:blank','AuthFrmUp',
		        'height='+height_+',width='+width_+',status=yes,scrollbars=no,resizable=no,left='+left_+',top='+top_+'');
		document.authFrmFrame.target = 'AuthFrmUp';
		document.authFrmFrame.action ='https://kspay.ksnet.to/store/KSPayWebV1.3/KSPayWeb.jsp';
		document.authFrmFrame.submit();
    }
		
	function goResult(){
		document.KSPayWeb.target = "";
		document.KSPayWeb.submit();
	}
	
	function paramSet(authyn,trno,trdt,trtm,authno,ordno,msg1,msg2,amt,temp_v,isscd,aqucd,remark,result){
		document.KSPayWeb.reAuthyn.value 	= authyn;
		document.KSPayWeb.reTrno.value 		= trno  ;
		document.KSPayWeb.reTrddt.value 	= trdt  ;
		document.KSPayWeb.reTrdtm.value 	= trtm  ;
		document.KSPayWeb.reAuthno.value 	= authno;
		document.KSPayWeb.reOrdno.value 	= ordno ;
		document.KSPayWeb.reMsg1.value 		= msg1  ;
		document.KSPayWeb.reMsg2.value 		= msg2  ;
		document.KSPayWeb.reAmt.value 		= amt   ;
		document.KSPayWeb.reTemp_v.value 	= temp_v;
		document.KSPayWeb.reIsscd.value 	= isscd ;
		document.KSPayWeb.reAqucd.value 	= aqucd ;
		document.KSPayWeb.reRemark.value 	= remark;
		document.KSPayWeb.reResult.value 	= result;
	}
-->
</script>
<style type="text/css">
	BODY{font-size:9pt; line-height:160%}
	TD{font-size:9pt; line-height:160%}
	A {color:blue;line-height:160%; background-color:#E0EFFE}
	INPUT{font-size:9pt;}
	SELECT{font-size:9pt;}
	.emp{background-color:#FDEAFE;}
</style>
</head>
<body>

<!----------------------------------------------- KSPayWeb Form에 대한 설명 ----------------------------------------------->
<!--고객이 결과를 받기원하는 URL을 action부분에 입력하세요-->
<!--KSPAY의 팝업결제창에서 결재가 이루어짐과 동시에 KSPayRcv.php가 구동되면서 아래의 value값이 채워지고 action경로로 값을 전달합니다-->
<!--action의 경로는 상대경로 절대경로 둘다 사용가능합니다-->
		<form name=KSPayWeb action = "./result.php" method=post>

<!-- 결과값 수신 파라메터 -->
<!-- 이곳의 value값을 채우지마십시오. KSPayRcv.php가 실행되면서 채워주는 값입니다-->
			<input type=hidden name=reAuthyn value="">
			<input type=hidden name=reTrno   value="">
			<input type=hidden name=reTrddt  value="">
			<input type=hidden name=reTrdtm  value="">
			<input type=hidden name=reAuthno value="">
			<input type=hidden name=reOrdno  value="">
			<input type=hidden name=reMsg1   value="">
			<input type=hidden name=reMsg2   value="">
			<input type=hidden name=reAmt    value="">
			<input type=hidden name=reTemp_v value="">
			<input type=hidden name=reIsscd  value="">
			<input type=hidden name=reAqucd  value="">
			<input type=hidden name=reRemark value="">
			<input type=hidden name=reResult value="">

<!--업체에서 추가하고자하는 임의의 파라미터를 입력하면 됩니다.-->
<!--이 파라메터는 위의 action 경로로 단순히 전달되는 값입니다.-->
			<input type=hidden name=a        value="a1">
			<input type=hidden name=b        value="b1">
			<input type=hidden name=c        value="c1">
			<input type=hidden name=d        value="d1">
<!--------------------------------------------------------------------------------------------------------------------------->


<table border=0 width=500>
	<tr>
	<td>
	<hr noshade size=1>
	<b>KSPay 결제</b>
	<hr noshade size=1>
	</td>
	</tr>
</table>
<br>

<table border=0 width=500>
<tr>
<td align=center>
<table width=400 cellspacing=0 cellpadding=0 border=0 bgcolor=#4F9AFF>
<tr>
<td>
<table width=100% cellspacing=1 cellpadding=2 border=0>
<tr bgcolor=#4F9AFF height=25>
<td align=center><font color="#FFFFFF">
정보를 기입하신 후 결제버튼을 눌러주십시오
</font></td>
</tr>
<tr bgcolor=#FFFFFF>
<td valign=top>
<table width=100% cellspacing=0 cellpadding=2 border=0>
<tr>
<td align=center>
<br>
<table>
<tr>

<!----------------------------------------------- 고객에게 보여지지 않는 항목 ----------------------------------------------->
<!--이부분은 고객에게 보여지지 않아야 하는 항복입니다.-->
<!--테스트를 마치신 후에 input type을 hidden으로 바꿔주십시오-->
	<td colspan=2><b>주문 관련 항목</b></td>
</tr>
<!--<tr>-->

<!--
화폐단위
원화 : 410 또는 WON
미화 : 840 또는 USD
주의 : 미화승인은 영업부 담당자와 문의하여 처리하셔야 합니다.
		미화로 결제시 1000원이 1달러 입니다.
		예를들어 55달러이면 55000 으로 결제하셔야 합니다.
-->
<!--
	<td>화폐단위 : </td>
	<td>--><input type=hidden name=sndCurrencytype size=30 maxlength=3 value="WON"><!--</td>-->
<!--</tr>-->

<tr>
<!--상품명은 30Byte(한글 15자) 입니다. 특수문자 ' " - ` 는 사용하실수 없습니다. 따옴표,쌍따옴표,빼기,쿼테이션 -->
	<td>주문번호 : </td>
	<td><input type=text name=sndOrdernumber size=30 maxlength=30 value=""></td>
</tr>
<tr>
<!--상품명은 50Byte(한글 25자) 입니다. 특수문자 ' " - ` 는 사용하실수 없습니다. 따옴표,쌍따옴표,빼기,쿼테이션 -->
	<td>상품명 : </td>
	<td><input type=text name=sndGoodname size=30 maxlength=30 value=""></td>
</tr>
<tr>
	<td>가격 : </td>
	<td><input type=text name=sndAmount size=27 maxlength=9 value="" style="text-align:right"> 원</td>
</tr>

<tr>
	<td>주문자 : </td>
	<td><input type=text name=sndOrdername size=30 maxlength=20 value=""></td>
</tr>
	<!--KSPAY에서 결제정보를 메일로 보내줍니다. 추후 적용-->
<tr>
	<td>전자우편 : </td>
	<td>
	<input type=text name=sndEmail size=30 maxlength=50 value="">
	</td>
</tr>	
	<!--카드사에 SMS 서비스를 등록하신 고객에 한해서 SMS 문자메세지를 전송해 드립니다. -->
	<!--전화번호 value 값에 -가 들어가면 안됩니다.-->
<tr>
	<td>이동전화 : </td>
	<td>
	<input type=text name=sndMobile size=30 maxlength=20 value="">	
	<font color=gray>"-"는 빼고 입력</font>
	</td>
</tr>

<!--<tr>-->

<!--주민등록번호는 계좌이체를 사용하시는 업체만 값을 넘겨주시면 됩니다. 사용하지 않는 업체는 value값을 ""로 넘겨주세요-->
<!--
	<td>주민번호 : </td>
	<td>--><input type=hidden name=sndAllregid size=30 maxlength=30 value=""><!--<font color=gray>"-"는 빼고 입력</font>
</td>-->
<!--</tr>-->
<tr>
	<td colspan=2><hr></td></tr>
<tr>
	<td colspan=2><b>신용카드 기본항목</b></td>
</tr>
<tr>

<!--상점에서 적용할 할부개월수를 세팅합니다. 여기서 세팅하신 값은 KSPAY결재팝업창에서 고객이 스크롤선택하게 됩니다 -->
	<td valign="top">할부개월수  : </td>
	<td>
		<input type=text name=sndInstallmenttype size=30 maxlength=30 value="0:2:3:4:5:6:7:8:9:10:11:12"><BR>
		예 : 0, 3,4,5,6개월 적용할 때는 <font color=red>0:3:4:5:6</font><BR>		
	</td>
</tr>


<tr>
<!--무이자 구분값은 중요합니다. 무이자선택하게 되면 상점쪽에서 이자를 내셔야합니다.-->
<!--무이자 할부를 적용하지 않는 업체는 value='NONE" 로 넘겨주셔야 합니다. -->
<!--예 : 3,4,5,6개월 무이자 적용할 때는 value="3:4:5:6" -->
<!--예 : 모두 무이자 적용할 때는 value="ALL" -->
<!--예 : 무이자 미적용할 때는 value="NONE" -->
	<td valign="top">무이자구분  : </td>
	<td>
		<input type=text name=sndInteresttype size=30 maxlength=30 value="NONE"><bR>
		예 : 3,4,5,6개월 무이자 적용할 때는 <font color=red>3:4:5:6</font><BR>
		예 : 모두 무이자 적용할 때는 <font color=red>ALL</font><BR>
		예 : 무이자 미적용할 때는 <font color=red>NONE</font>
	</td>
</tr>
<tr>
	<td colspan=2><hr></td></tr>
<tr>

	
<!--월드패스카드를 사용하시는 상점만 신경쓰시면 됩니다. 사용하지 않는 상점은 아무값이나 넘겨주시면 됩니다. 지우시면 안됩니다.-->	
	<td colspan=2>
		<b>월드패스카드 기본항목</b><br>
		월드패스카드를 사용하지 않는 상점은 아무값이나 넘겨주시면 됩니다.
	</td>
</tr>
<tr>
	<TD>선/후불카드구분 :</TD>
	<TD>
			<!--<input type=text    name=sndWptype value="1">-->
			<input type="radio" name="sndWptype" value="1" checked>선불카드
			<input type="radio" name="sndWptype" value="2">후불카드
			<input type="radio" name="sndWptype" value="0">모든카드
	</TD>
</TR>
<TR>
	<TD>성인확인여부 :</TD>
	<TD>
		<!--<input type="text" name="sndAdulttype" value="1">-->
		<input type="radio" name="sndAdulttype" value="1" checked>성인확인필요
		<input type="radio" name="sndAdulttype" value="0">성인확인불필요   

	</TD>
</tr>
<tr>
	<td colspan=2><hr></td>
</tr>
<tr>
	<td colspan=2 align=center>
	<br>
	<input type="button" value=" 결 제 " onClick="javascript:goOpen();">
	<br><br>
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
</td>
</tr>
</table>
<br>

<table border=0 width=500>
	<tr>
	<td>
	<font color=gray>
	전자우편과 이동전화번호를 입력받는 것은 지불내역을 이메일 또는 SMS로
	알려드리기 위함이오니 반드시 기입하여 주시기 바랍니다.
	</font>
	</td>
	</tr>
	
	<tr>
	<td><hr noshade size=1></td>
	</tr>
</table>
</form>

<!--dummy.php는 보안경고를 방지하기 위한 것입니다. 수정하지 마세요. -->
<IFRAME id=AuthFrame name=AuthFrame style="display:none" src="dummy.php"></IFRAME>
<div style="display:none"> 



<!----------------------------------------------- authFrmFrame Form에 대한 설명 ----------------------------------------------->
<!-- 상점에서 KSNET 결제팝업창으로 전송하는 파라메터입니다.-->
<form name=authFrmFrame target=AuthFrame method=post>

<!-- 상점아이디입니다. 초기 테스트아이디 2999199998. 테스트 종료 후 KSPAY에서 발급받은 실제 상점아이디로 바꿔주십시오.-->
<!-- 테스트아이디로 테스트하실 때 실제카드로 결제하셔도 고객에게 청구되지 않습니다. -->
	<input type=hidden name=sndStoreid         value="2999199999">

<!--
결제와동시에 실시간으로 결과전문이 업체에 전송되는 로그 URL
backUrl은 미경유 모듈에서 실시간 로그를 처리할 수 있게 만든 프로그램입니다.               
backUrl은 ip와 도메인 둘다 사용하실 수 있습니다. backUrl은 승인결과에 큰 영향을 미칩니다.
backUrl은 사용하지 않으셔도 무방합니다.
이 값을 잘못 설정할 경우 "잠시 후 재시도 / m_상점오류"라는 메세지가 나옵니다.
실제 경로와 다르게 링크시키실 경우 결제승인된 후 자동취소 되어버립니다.
backUrl 페이지를 수정하여, log가 떨어지는 실제 경로를 잡아주셔야 합니다.-->
	<input type=hidden name=sndBackUrl         value=""> 

<!-- 사용자가 접속한 URL을 받아와서 sndReply의 값에 세팅합니다.-->
 <?
	$server_protocal_tmp = explode("/", $_SERVER["SERVER_PROTOCOL"]);
	$server_protocal = $server_protocal_tmp[0];
	$http_host = $_SERVER["HTTP_HOST"];
	
	$path_info_tmp = explode("/", $_SERVER["SCRIPT_NAME"]);
	$path_info = "";
	for($i = 0; $i < count($path_info_tmp)-1; $i++) {
		$path_info .= $path_info_tmp[$i]."/";
	}
	$ret = $server_protocal."://".$http_host.$path_info."KSPayRcv.php";
?>

 <!-- sndReply는 KSPayRcv.php의 절대경로를 넣어줍니다.
 <!-- KSPayRcv.php 역할 : KSPAY 결제팝업창에서 결제승인후에 값들을 본창의 KSPayWeb Form에 넘겨줍니다-->	
 <!-- sndReply가 오류날 경우에 본창으로 결과페이지가 넘어가지 않습니다. -->
 <!-- KSPayRcv.php는 AuthFrm.php 파일과 같은 폴더안에 들어있어야 합니다. -->
	<input type=hidden name=sndReply           value="<?echo($ret)?>">

<!--KSPAY 결제팝업창에서 사용가능한 결제수단을 세팅합니다. -->
<!--신용카드 이외의 결제방식을 사용시에는 사업팀의 사전 승인을 받아야합니다.-->
<!--신용카드/가상계좌/계좌이체/월드패스카드/포인트-->
<!--예 : 신용카드만 선택 value="10000' -->
<!--예 : 신용카드,가상계좌,월드패스카드 선택 value="11010' -->
	<input type=hidden name=sndPaymethod       value="10000"> <!-- 순서 : 신용카드, 가상계좌, 계좌이체, 월드패스카드, OK Cashbag -->
 	<input type=hidden name=sndOrdernumber	   value="">
	<input type=hidden name=sndGoodname        value="">
	<input type=hidden name=madeCompany	       value="">
	<input type=hidden name=madeCountry	       value="">
	<input type=hidden name=sndInstallmenttype value="">
	<input type=hidden name=sndAmount          value="">
	<input type=hidden name=sndOrdername       value="">
	<input type=hidden name=sndAllregid        value="">
	<input type=hidden name=sndEmail           value="">
	<input type=hidden name=sndMobile          value="">
	<input type=hidden name=sndInteresttype    value="">
	<input type=hidden name=sndCurrencytype    value="">
	<input type=hidden name=sndCashbag         value="0">          <!--OK CashBag-- 0: 미사용, 1: 사용 -->
	<input type=hidden name=sndWptype          value="">
	<input type=hidden name=sndAdulttype       value="">
	<input type=hidden name=sndStoreName       value="유니시티코리아(주)">    <!--회사명을 한글로 넣어주세요 -->
	<input type=hidden name=sndStoreNameEng    value="Unicity Korea">   <!--회사명을 영어로 넣어주세요 -->
	<input type=hidden name=sndStoreDomain     value="http://www.makelifebetter.co.kr">   <!-- 회사 도메인을 http://를 포함해서 넣어주세요-->
</form>
</div>
</body>
</html>
<!--이 페이지에 있는 모든 파라메터는 지우시거나 변경하시면 결제가 이루어지지 않습니다.-->