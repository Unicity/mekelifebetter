<?
  header('Content-Type: text/html; charset=euc-kr');
?>
<? include "../inc/KSPayApprovalCancel.inc"; ?>
<?
	/* 전문조립 및 통신용함수는 KSPayApprovalCancel.inc 파일에 모아놓았습니다.*/

	//승인타입	 : A-인증없는승인, N-인증승인, M-MPI인증승인, I-ISP인증승인 
	$certitype       = $_POST["certitype"] ;	

//Header부 Data --------------------------------------------------
	$EncType         = "2" ;                                 //0: 암화안함, 1:ssl, 2: seed
	$Version         = "0603" ;                              //전문버전
	$Type            = "00" ;                                //구분
	$Resend          = "0" ;                                 //전송구분 : 0 : 처음,  2: 재전송
	$RequestDate     =                                       // 요청일자 : yyyymmddhhmmss
		SetZero(strftime("%Y"),4).
		SetZero(strftime("%m"),2).
		SetZero(strftime("%d"),2).
		SetZero(strftime("%H"),2).
		SetZero(strftime("%M"),2).
		SetZero(strftime("%S"),2);

	$KeyInType       = "K" ;                                 //KeyInType 여부 : S : Swap, K: KeyInType
	$LineType        = "1" ;                                 //lineType 0 : offline, 1:internet, 2:Mobile
	$ApprovalCount   = "1" ;                                 //복합승인갯수
	$GoodType        = "0" ;                                 //제품구분 0 : 실물, 1 : 디지털
	$HeadFiller      = ""	;                                  //예비

	$StoreId         = $_POST["storeid"] ;                   //상점아이디
	$OrderNumber     = $_POST["ordernumber"] ;               //주문번호
	$UserName        = $_POST["ordername"] ;                 //주문자명
	$IdNum           = "" ;                                  //주민번호 or 사업자번호 (공백)
	$Email           = $_POST["email"] ;                     //email
	$GoodName        = $_POST["goodname"] ;                  //제품명
	$PhoneNo         = $_POST["phoneno"] ;                   //휴대폰번호
//Header end -------------------------------------------------------------------

//Data Default-------------------------------------------------
	$ApprovalType    = "1000";                               //승인구분

	if($certitype == "N")
	{
	  $ApprovalType="1300" ;
	}else if($certitype == "I")
	{
		$ApprovalType="I000" ;
	}

	$InterestType    = $_POST["interest"] ;                  //일반/무이자구분 1:일반 2:무이자
	$TrackII         = $_POST["cardno"]."=".$_POST["expdt"]; // 카드번호=유효기간  or 거래번호
	$Installment     = $_POST["installment"] ;               //할부  00일시불
	$Amount          = $_POST["amount"] ;                    //금액
	$Passwd          = $_POST["passwd"] ;                    //비밀번호 앞2자리
	$LastIdNum       = $_POST["lastidnum"] ;                 //주민번호  앞6자리, 사업자번호10
	$CurrencyType    = $_POST["currencytype"] ;              //통화구분 0:원화 1: 미화
                                                           
	$BatchUseType    = "0" ;                                 //거래번호배치사용구분  0:미사용 1:사용
	$CardSendType    = "0" ;                                 //카드정보전송유무 0:미전송 1:카드번호,유효기간,할부,금액,가맹점번호 2:카드번호앞14자리 + "XXXX",유효기간,할부,금액,가맹점번호
	$VisaAuthYn      = "7" ;                                 //비자인증유무 0:사용안함,7:SSL,9:비자인증
	$Domain          = "" ;                                  //도메인 자체가맹점(PG업체용)
	$IpAddr          = ${"REMOTE_ADDR"};                     //IP ADDRESS 자체가맹점(PG업체용)
	$BusinessNumber  = "" ;                                  //사업자 번호 자체가맹점(PG업체용)
	$Filler          = "" ;                                  //예비
	$AuthType        = "" ;                                  //ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
	$MPIPositionType = "" ;                                  //K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
	$MPIReUseType    = "" ;                                  //Y : 재사용, N : 재사용아님
	$EncData         = "" ;                                  //MPI, ISP 데이터
	                                                         
	$cavv            = $_POST["cavv"] ;                      //MPI용
	$xid             = $_POST["xid"] ;                       //MPI용
	$eci             = $_POST["eci"] ;                       //MPI용

	$KVP_PGID        = "" ;
	$KVP_CARDCODE    = "" ;
	$KVP_SESSIONKEY  = "" ;
	$KVP_ENCDATA     = "" ;

	// KBAPP
	$kb_app_otp = $_POST["kb_app_otc"];
	
	/*ISP일경우*/
	if($certitype == "I")
	{
		$TrackII        = "" ;
		$InterestType	= $_POST["KVP_NOINT"];							//무이자구분
		$Installment	= $_POST["KVP_QUOTA"];							//할부:00일시불
		$KVP_PGID       = $_POST["KVP_PGID"];
		$KVP_CARDCODE	= $_POST["KVP_CARDCODE"];
		$KVP_SESSIONKEY	= $_POST["KVP_SESSIONKEY"];
		$KVP_ENCDATA    = $_POST["KVP_ENCDATA"];
	}
//Data Default end -------------------------------------------------------------

//Server로 부터 응답이 없을시 자체응답
	$rApprovalType     = "1001" ; 
	$rTransactionNo    = "" ;              //거래번호
	$rStatus           = "X" ;             //상태 O : 승인, X : 거절
	$rTradeDate        = "" ;              //거래일자
	$rTradeTime        = "" ;              //거래시간
	$rIssCode          = "00" ;            //발급사코드
	$rAquCode          = "00" ;            //매입사코드
	$rAuthNo           = "9999" ;          //승인번호 or 거절시 오류코드
	$rMessage1         = "승인거절" ;      //메시지1
	$rMessage2         = "C잠시후재시도" ; //메시지2
	$rCardNo           = "" ;              //카드번호
	$rExpDate          = "" ;              //유효기간
	$rInstallment      = "" ;              //할부
	$rAmount           = "" ;              //금액
	$rMerchantNo       = "" ;              //가맹점번호
	$rAuthSendType     = "N" ;             //전송구분
	$rApprovalSendType = "N" ;             //전송구분(0 : 거절, 1 : 승인, 2: 원카드)
	$rPoint1           = "000000000000" ;  //Point1
	$rPoint2           = "000000000000" ;  //Point2
	$rPoint3           = "000000000000" ;  //Point3
	$rPoint4           = "000000000000" ;  //Point4
	$rVanTransactionNo = "" ;
	$rFiller           = "" ;              //예비
	$rAuthType         = "" ;              //ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
	$rMPIPositionType	 = "" ;              //K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
	$rMPIReUseType     = "" ;              //Y : 재사용, N : 재사용아님
	$rEncData          = "" ;              //MPI, ISP 데이터
//--------------------------------------------------------------------------------

	/*전문을 송신할곳을 지정(중계데몬의 IP/port)  */
	KSPayApprovalCancel("localhost", 29991);

	/*요청전문조립(Header부)*/
	HeadMessage
	(
		$EncType,        // 0: 암화안함, 1:openssl, 2: seed       
		$Version,        // 전문버전                              
		$Type,           // 구분                                  
		$Resend,         // 전송구분 : 0 : 처음,  2: 재전송    
		$RequestDate,    // 전송일
		$StoreId,        // 상점아이디                                   
		$OrderNumber,    // 주문번호                                     
		$UserName,       // 주문자명                                     
		$IdNum,          // 주민번호 or 사업자번호                       
		$Email,          // email                                        
		$GoodType,       // 제품구분 0 : 실물, 1 : 디지털                
		$GoodName,       // 제품명                                       
		$KeyInType,      // KeyInType 여부 : S : Swap, K: KeyInType      
		$LineType,       // lineType 0 : offline, 1:internet, 2:Mobile   
		$PhoneNo,        // 휴대폰번호                                   
		$ApprovalCount,  // 복합승인갯수                                 
		$HeadFiller      // 예비                                         
	);

	/*인증방식에 따라 인증데이터부 세팅*/
	//일반승인인경우
	if($certitype == "A"||$certitype == "N")
	{
		$AuthType        = "" ;
		$MPIPositionType = "" ;
		$MPIReUseType    = "" ;
		$EncData         = "" ;
	}
	//MPI인증승인인경우
	else if($certitype == "M")
	{
		$AuthType        = "M" ;
		$MPIPositionType = "K" ;
		$MPIReUseType    = "N" ;
		$cavv            = format_string($cavv, 40, "Y");
		$xid             = format_string($xid,  40, "Y");
		$eci             = format_string($eci,   2, "Y");
		$EncData         = SetZero(strlen($cavv.$xid.$eci), 5).$cavv.$xid.$eci;
	}
	//ISP인증승인인경우
	else if($certitype == "I")
	{
		if($kb_app_otp != "")
		{
			$ApprovalType			= "1000";
			$TrackII = $kb_app_otp . "=8911";
			$Filler = "            KBA" ;
		}else
		{
			$AuthType        = "I";
			$MPIPositionType = "K";
			$MPIReUseType    = "N";
    	
			$KVP_SESSIONKEY  = urlencode($KVP_SESSIONKEY);
			$KVP_ENCDATA     = urlencode($KVP_ENCDATA);
			$KVP_SESSIONKEY  = SetZero(strlen($KVP_SESSIONKEY),4) . $KVP_SESSIONKEY;
			$KVP_ENCDATA     = SetZero(strlen($KVP_ENCDATA),4) . $KVP_ENCDATA;
			$KVP_CARDCODE    = SetZero(strlen($KVP_CARDCODE),2) . $KVP_CARDCODE;
			$KVP_CARDCODE    = format_string($KVP_CARDCODE, 22, "Y");
			$EncData         = SetZero(strlen($KVP_PGID.$KVP_SESSIONKEY.$KVP_ENCDATA.$KVP_CARDCODE),5).($KVP_PGID.$KVP_SESSIONKEY.$KVP_ENCDATA.$KVP_CARDCODE);
		}
		if($InterestType == "0"||$InterestType == null)	$InterestType = "1" ;
		else	$InterestType = "2" ;
	}

	if($CurrencyType == "WON"||$CurrencyType == "410"||$CurrencyType== "")	$CurrencyType = "0" ;
	else if($CurrencyType == "USD"||$CurrencyType == "840")	$CurrencyType = "1" ;
	else	$CurrencyType = "0" ;

	/*요청전문조립(Data부)*/
	CreditDataMessage
	(
		$ApprovalType,    // ApprovalType	 : 승인구분         
		$InterestType,    // InterestType    : 일반/무이자구분 1:일반 2:무이자                    
		$TrackII,         // TrackII		 : 카드번호=유효기간  or 거래번호                            
		$Installment,     // Installment	 : 할부  00일시불                    
		$Amount,          // Amount		 : 금액                            
		$Passwd,          // Passwd		 : 비밀번호 앞2자리                               
		$LastIdNum,       // IdNum		 : 주민번호  뒤7자리, 사업자번호10                      
		$CurrencyType,    // CurrencyType	 : 통화구분 0:원화 1: 미화                    
		$BatchUseType,    // BatchUseType	 : 거래번호배치사용구분  0:미사용 1:사용                      
		$CardSendType,    // CardSendType	 : 카드정보전송 0:미정송 1:카드번호,유효기간,할부,금액,가맹점번호 2:카드번호앞14자리 + "XXXX",유효기간,할부,금액,가맹점번호    
		$VisaAuthYn,      // VisaAuthYn	 : 비자인증유무 0:사용안함,7:SSL,9:비자인증                         
		$Domain,          // Domain		 : 도메인 자체가맹점(PG업체용)                               
		$IpAddr,          // IpAddr		 : IP ADDRESS 자체가맹점(PG업체용)                             
		$BusinessNumber,  // BusinessNumber: 사업자 번호 자체가맹점(PG업체용)                       
		$Filler,          // Filler	     : 예비                                         
		$AuthType,        // AuthType		 : ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래                              
		$MPIPositionType,	// MPIPositionType  : K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래                      
		$MPIReUseType,    // MPIReUseType  : Y :  재사용, N : 재사용아님                           
		$EncData          // EndData       : MPI, ISP 데이터                                
	);

	/*전문송수신*/
	if (SendSocket("1")) 
	{
		$rApprovalType     = $ApprovalType;
		$rTransactionNo    = $TransactionNo;     // 거래번호
		$rStatus           = $Status;            // 상태 O : 승인, X : 거절
		$rTradeDate        = $TradeDate;         // 거래일자
		$rTradeTime        = $TradeTime;         // 거래시간
		$rIssCode          = $IssCode;           // 발급사코드
		$rAquCode          = $AquCode;           // 매입사코드
		$rAuthNo           = $AuthNo;            // 승인번호 or 거절시 오류코드
		$rMessage1         = $Message1;          // 메시지1
		$rMessage2         = $Message2;          // 메시지2
		$rCardNo           = $CardNo;            // 카드번호
		$rExpDate          = $ExpDate;           // 유효기간
		$rInstallment      = $Installment;       // 할부
		$rAmount           = $Amount;            // 금액
		$rMerchantNo       = $MerchantNo;        // 가맹점번호
		$rAuthSendType     = $AuthSendType;      // 전송구분= new String(this.read(2))
		$rApprovalSendType = $ApprovalSendType;  // 전송구분(0 : 거절, 1 : 승인, 2: 원카드)
		$rPoint1           = $Point1;            // Point1
		$rPoint2           = $Point2;            // Point2
		$rPoint3           = $Point3;            // Point3
		$rPoint4           = $Point4;            // Point4
		$rVanTransactionNo = $VanTransactionNo;  // Van거래번호
		$rFiller           = $Filler;            // 예비
		$rAuthType         = $AuthType;          // ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
		$rMPIPositionType	 = $MPIPositionType;   // K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
		$rMPIReUseType     = $MPIReUseType;      // Y : 재사용, N : 재사용아님
		$rEncData          = $EncData;           // MPI, ISP 데이터
	}
?>

<html>
<head>
<title>KSPay</title>
<meta http-equiv="Content-Type" content="text/html charset=euc-kr">
<style type="text/css">
	TABLE{font-size:9pt; line-height:160%;}
	A {color:blueline-height:160% background-color:#E0EFFE}
	INPUT{font-size:9pt}
	SELECT{font-size:9pt}
	.emp{background-color:#FDEAFE}
	.white{background-color:#FFFFFF color:black border:1x solid white font-size: 9pt}
</style>
<script language="javascript">
<!--
document.onkeypress = processKey;
document.onkeydown  = processKey;

function processKey() {
        if(( event.ctrlKey == true && ( event.keyCode == 78 || event.keyCode == 82 ) ) ||
		( event.keyCode >= 112 && event.keyCode <= 123 ))
 	{
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
-->
</script>
</head>

<body onload="" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >
<table border=0 width=0>
<tr>
<td align=center>
<table width=320 cellspacing=0 cellpadding=0 border=0 bgcolor=#4F9AFF>
<tr>
<td>
<table width=100% cellspacing=1 cellpadding=2 border=0>
<tr bgcolor=#4F9AFF height=25>
<td align=left><font color="#FFFFFF">
KSPay 신용카드 결과
<?
	if($certitype == "A")      echo("(인증없는승인)") ;
	else if($certitype == "N") echo("(인증승인)") ;
	else if($certitype == "M") echo("(M-MPI인증승인)") ;
	else if($certitype == "I") echo("(I-ISP인증승인)") ;
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
	<td>거래종류 :</td>
	<td><?echo($rApprovalType)?></td>
</tr>
<tr>
	<td>거래번호 :</td>
	<td><?echo($rTransactionNo)?></td>
</tr>
<tr>
	<td>거래성공여부 :</td>
	<td><?echo($rStatus)?></td>
</tr>
<tr>
	<td>거래시간 :</td>
	<td><?echo($rTradeDate)?> / <?echo($rTradeTime)?></td>
</tr>
<tr>
	<td>발급사코드 :</td>
	<td><?echo($rIssCode)?></td>
</tr>
<tr>
	<td>매입사코드 :</td>
	<td><?echo($rAquCode)?></td>
</tr>
<tr>
	<td>승인번호 :</td>
	<td><?echo($rAuthNo)?></td>
</tr>
<tr>
	<td>메시지1 :</td>
	<td><?echo($rMessage1)?></td>
</tr>
<tr>
	<td>메시지2 :</td>
	<td><?echo($rMessage2)?></td>
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
</body>
</html>