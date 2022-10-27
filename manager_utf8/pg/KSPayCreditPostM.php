<!------------------------------------------------------------------------------
 FILE NAME : KSPayCreditPostM.php
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2004-05-03
                                                         http://www.kspay.co.kr
                                                         http://www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
<? include "./KSPayApprovalCancel.inc"; ?>
<?
// Default(수정항목이 아님)-------------------------------------------------------
        $EncType     = "2";     // 0: 암화안함, 1:openssl, 2: seed
        $Version     = "0210";  // 전문버전
        $VersionType = "00";    // 구분
        $Resend      = "0";     // 전송구분 : 0 : 처음,  2: 재전송

        $RequestDate=           // 요청일자 : yyyymmddhhmmss
                format_string(SetZero(strftime("%Y"),4),4,"Y").
                format_string(SetZero(strftime("%m"),2),2,"Y").
                format_string(SetZero(strftime("%d"),2),2,"Y").
                format_string(SetZero(strftime("%H"),2),2,"Y").
                format_string(SetZero(strftime("%M"),2),2,"Y").
                format_string(SetZero(strftime("%S"),2),2,"Y");
        $KeyInType     = "K";   // KeyInType 여부 : S : Swap, K: KeyInType
        $LineType      = "1";   // lineType 0 : offline, 1:internet, 2:Mobile
        $ApprovalCount = "1";   // 복합승인갯수
        $GoodType      = "0";   // 제품구분 0 : 실물, 1 : 디지털
        $HeadFiller     = "";    // 예비
//-------------------------------------------------------------------------------

// Header (입력값 (*) 필수항목)--------------------------------------------------
        $StoreId     =$HTTP_POST_VARS["storeid"];     // *상점아이디
        $OrderNumber =$HTTP_POST_VARS["ordernumber"]; // *주문번호
        $UserName    =$HTTP_POST_VARS["ordername"];   // *주문자명
        $IdNum       =$HTTP_POST_VARS["idnum"];       // 주민번호 or 사업자번호
        $Email       =$HTTP_POST_VARS["email"];       // *email
        $GoodName    =$HTTP_POST_VARS["goodname"];    // *제품명
        $PhoneNo     =$HTTP_POST_VARS["phoneno"];     // *휴대폰번호
// Header end -------------------------------------------------------------------
        
// Data Default(수정항목이 아님)-------------------------------------------------
        $ApprovalType    = $HTTP_POST_VARS["authty"]; // 승인구분
        $BatchUseType    = "0";                       // 거래번호배치사용구분  0:미사용 1:사용
        $CardSendType    = "1";                       // 카드정보전송 0:미전송 1:카드번호,유효기간,할부,금액,가맹점번호 2:카드번호앞14자리 + "XXXX",유효기간,할부,금액,가맹점번호
        $VisaAuthYn      = "7";                       // 비자인증유무 0:사용안함,7:SSL,9:비자인증
        $Domain          = "";                        // 도메인 자체가맹점(PG업체용)
        $IpAddr          = ${"REMOTE_ADDR"};          // IP ADDRESS 자체가맹점(PG업체용)
        $BusinessNumber  = "";                        // 사업자 번호 자체가맹점(PG업체용)
        $Filler          = "";                        // 예비
        $AuthType        = "";                        // ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
        $MPIPositionType = "";                        // K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
        $MPIReUseType    = "";                        // Y : 재사용, N : 재사용아님
        $EncData         = "";                        // MPI, ISP 데이터

        $cavv            = $HTTP_POST_VARS["cavv"];   // MPI
        $xid             = $HTTP_POST_VARS["xid"];    // MPI
        $eci             = $HTTP_POST_VARS["eci"];    // MPI
// Data Default end -------------------------------------------------------------
;
// Data (입력값 (*) 필수항목)----------------------------------------------------
        $InterestType    = $HTTP_POST_VARS["interest"];                            // 일반/무이자구분 1:일반 2:무이자
        $TrackII         = $HTTP_POST_VARS["cardno"]."=".$HTTP_POST_VARS["expdt"]; // 카드번호=유효기간  or 거래번호
        $Installment     = $HTTP_POST_VARS["installment"];                         // 할부  00일시불
        $Amount          = $HTTP_POST_VARS["amount"];                              // 금액
        $Passwd          = $HTTP_POST_VARS["passwd"];                              // 비밀번호 앞2자리
        $LastIdNum       = $HTTP_POST_VARS["lastidnum"];                           // 주민번호  뒤7자리, 사업자번호10
        $CurrencyType    = $HTTP_POST_VARS["currencytype"];                        // 통화구분 0:원화 1: 미화
// Data end ---------------------------------------------------------------------

// 승인거절 응답
// Server로 부터 응답이 없을시 자체응답
        $rApprovalType     = "1001";
        $rTransactionNo    = "";              // 거래번호
        $rStatus           = "X";             // 상태 O : 승인, X : 거절
        $rTradeDate        = "";              // 거래일자
        $rTradeTime        = "";              // 거래시간
        $rIssCode          = "00";            // 발급사코드
        $rAquCode          = "00";            // 매입사코드
        $rAuthNo           = "9999";          // 승인번호 or 거절시 오류코드
        $rMessage1         = "승인거절";      // 메시지1
        $rMessage2         = "C잠시후재시도"; // 메시지2
        $rCardNo           = "";              // 카드번호
        $rExpDate          = "";              // 유효기간
        $rInstallment      = "";              // 할부
        $rAmount           = "";              // 금액
        $rMerchantNo       = "";              // 가맹점번호
        $rAuthSendType     = "N";             // 전송구분
        $rApprovalSendType = "N";             // 전송구분(0 : 거절, 1 : 승인, 2: 원카드)
        $rPoint1           = "000000000000";  // Point1
        $rPoint2           = "000000000000";  // Point2
        $rPoint3           = "000000000000";  // Point3
        $rPoint4           = "000000000000";  // Point4
        $rVanTransactionNo = "";              
        $rFiller           = "";              // 예비
        $rAuthType         = "";              // ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
        $rMPIPositionType  = "";              // K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
        $rMPIReUseType     = "";              // Y : 재사용, N : 재사용아님
        $rEncData          = "";              // MPI, ISP 데이터
// --------------------------------------------------------------------------------

        KSPayApprovalCancel("localhost", 29991);
// URL로(Backend) 승인응답을 받고 싶을때 설정
// ipg.SendURL = "http:'172.16.0.100:7001/store/kspay/sample/BackURL/backUrl.jsp"
// ipg.SendURL = ""

        HeadMessage(
                $EncType       ,                  // 0: 암화안함, 1:openssl, 2: seed       
                $Version       ,                  // 전문버전                              
                $VersionType   ,                  // 구분                                  
                $Resend        ,                  // 전송구분 : 0 : 처음,  2: 재전송    
                $RequestDate   ,                  // 재사용구분                                       
                $StoreId       ,                  // 상점아이디                                   
                $OrderNumber   ,                  // 주문번호                                     
                $UserName      ,                  // 주문자명                                     
                $IdNum         ,                  // 주민번호 or 사업자번호                       
                $Email         ,                  // email                                        
                $GoodType      ,                  // 제품구분 0 : 실물, 1 : 디지털                
                $GoodName      ,                  // 제품명                                       
                $KeyInType     ,                  // KeyInType 여부 : S : Swap, K: KeyInType      
                $LineType      ,                  // lineType 0 : offline, 1:internet, 2:Mobile   
                $PhoneNo       ,                  // 휴대폰번호                                   
                $ApprovalCount ,                  // 복합승인갯수                                 
                $HeadFiller    );                 // 예비                                         

// 수정부분이 아님 --------------------------------------------------------------
        if ($cavv ==  null) {
                $ApprovalType    = "1000"; // MPI 적용안된 카드사는 인증후 승인으로 처리해야함
                $AuthType        = "";
                $MPIPositionType = "";
                $MPIReUseType    = "";
                $EncData         = "";
        }
        else
        {
                $ApprovalType    =  "1000"; // MPI 적용된 카드사는 승인만 처리 가능
                $AuthType        =  "";
                $MPIPositionType =  "";
                $MPIReUseType    =  "";
                //$AuthType        =  "M";
                //$MPIPositionType =  "K";
                //$MPIReUseType    =  "N";
                $cavv            =  format_string($cavv ,40,"Y");
                $xid             =  format_string($xid  ,40,"Y");
                $eci             =  format_string($eci  , 2,"Y");
                $EncData         =  format_string(SetZero(strlen($cavv.$xid.$eci), 5), 5, "Y") . $cavv . $xid . $eci;
        } 

        if ($CurrencyType == null) $CurrencyType="0";
        if ($CurrencyType == "840" || $CurrencyType == "USD")
                $CurrencyType="1";
        else
                $CurrencyType="0";

// ------------------------------------------------------------------------------
 		echo "ApprovalType : ".$ApprovalType."<br>";
 		echo "InterestType : ".$InterestType."<br>";
 		echo "TrackII : ".$TrackII."<br>";
 		echo "Installment : ".$Installment."<br>";
 		echo "Amount : ".$Amount."<br>";
 		echo "Passwd : ".$Passwd."<br>";
 		echo "LastIdNum : ".$LastIdNum."<br>";
 		echo "CurrencyType : ".$CurrencyType."<br>";
 		echo "BatchUseType : ".$BatchUseType."<br>";
 		echo "CardSendType : ".$CardSendType."<br>";
 		echo "VisaAuthYn : ".$VisaAuthYn."<br>";
 		echo "Domain : ".$Domain."<br>";
		echo "IpAddr : ".$IpAddr."<br>";
 		echo "Domain : ".$Domain."<br>";
 		echo "BusinessNumber : ".$BusinessNumber."<br>";
 		echo "Filler : ".$Filler."<br>";
 		echo "AuthType : ".$AuthType."<br>";
 		echo "MPIPositionType : ".$MPIPositionType."<br>";
		echo "MPIReUseType : ".$MPIReUseType."<br>";
 		echo "EncData : ".$EncData."<br>";

//		$InterestType
		 		
// 		4119044069321329

        CreditDataMessage(
				
                $ApprovalType,    // ApprovalType    : 승인구분                                                                                                                    
                $InterestType,    // InterestType    : 일반/무이자구분 1:일반 2:무이자                                                                                           
                $TrackII,         // TrackII         : 카드번호=유효기간  or 거래번호                                                                                              
                $Installment,     // Installment     : 할부  00일시불                                                                                                              
                $Amount,          // Amount          : 금액                                                                                                                    
                $Passwd,          // Passwd          : 비밀번호 앞2자리                                                                                                        
                $LastIdNum,       // IdNum           : 주민번호  뒤7자리, 사업자번호10                                                                                         
                $CurrencyType,    // CurrencyType    : 통화구분 0:원화 1: 미화                                                                                                     
                $BatchUseType,    // BatchUseType    : 거래번호배치사용구분  0:미사용 1:사용                                                                                       
                $CardSendType,    // CardSendType    : 카드정보전송 0:미정송 1:카드번호,유효기간,할부,금액,가맹점번호 2:카드번호앞14자리 + "XXXX",유효기간,할부,금액,가맹점번호    
                $VisaAuthYn,      // VisaAuthYn      : 비자인증유무 0:사용안함,7:SSL,9:비자인증                                                                                
                $Domain,          // Domain          : 도메인 자체가맹점(PG업체용)                                                                                             
                $IpAddr,          // IpAddr          : IP ADDRESS 자체가맹점(PG업체용)                                                                                         
                $BusinessNumber,  // BusinessNumber  : 사업자 번호 자체가맹점(PG업체용)                                                                                         
                $Filler,          // Filler          : 예비                                                                                                                    
                $AuthType,        // AuthType        : ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래                                                                         
                $MPIPositionType, // MPIPositionType : K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래                                                                     
                $MPIReUseType,    // MPIReUseType    : Y :  재사용, N : 재사용아님                                                                                             
                $EncData
        );        // EndData          : MPI, ISP 데이터                                                                                                          
		
				
        if (SendSocket("1")) {
                $rApprovalType          = $ApprovalType     ;
                $rTransactionNo         = $TransactionNo        ;       // 거래번호
                $rStatus                = $Status                       ;       // 상태 O : 승인, X : 거절
                $rTradeDate             = $TradeDate            ;       // 거래일자
                $rTradeTime             = $TradeTime            ;       // 거래시간
                $rIssCode               = $IssCode                      ;       // 발급사코드
                $rAquCode               = $AquCode                      ;       // 매입사코드
                $rAuthNo                = $AuthNo                       ;       // 승인번호 or 거절시 오류코드
                $rMessage1              = $Message1                     ;       // 메시지1
                $rMessage2              = $Message2                     ;       // 메시지2
                $rCardNo                = $CardNo                       ;       // 카드번호
                $rExpDate               = $ExpDate                      ;       // 유효기간
                $rInstallment           = $Installment          ;       // 할부
                $rAmount                = $Amount                       ;       // 금액
                $rMerchantNo            = $MerchantNo           ;       // 가맹점번호
                $rAuthSendType          = $AuthSendType         ;       // 전송구분= new String(this.read(2))
                $rApprovalSendType      = $ApprovalSendType     ;       // 전송구분(0 : 거절, 1 : 승인, 2: 원카드)
                $rPoint1                = $Point1                       ;       // Point1
                $rPoint2                = $Point2                       ;       // Point2
                $rPoint3                = $Point3                       ;       // Point3
                $rPoint4                = $Point4                       ;       // Point4
                $rVanTransactionNo  	= $VanTransactionNo ;   // Van거래번호
                $rFiller                = $Filler                       ;       // 예비
                $rAuthType              = $AuthType                     ;       // ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
                $rMPIPositionType       = $MPIPositionType      ;       // K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
                $rMPIReUseType          = $MPIReUseType         ;       // Y : 재사용, N : 재사용아님
                $rEncData               = $EncData                      ;       // MPI, ISP 데이터
        }

        include "../../dbconn_utf8.inc";

        function trimSpace ($array)
        {
                $temp = Array();
                for ($i = 0; $i < count($array); $i++)
                                $temp[$i] = trim($array[$i]);

                return $temp;
                
        }

        $OrderNumber = trim($OrderNumber);
        $UserName = trim($UserName);
        $IdNum = trim($IdNum);
        $Email = trim($Email);
        $GoodName = trim($GoodName);
        $PhoneNo = trim($PhoneNo);
        $InterestType = trim($InterestType);
        $Passwd = trim($Passwd);
        $ApprovalType = trim($rApprovalType);
        $TransactionNo = trim($rTransactionNo);
        $Status = trim($rStatus);
        $TradeDate = trim($rTradeDate);
        $TradeTime = trim($rTradeTime);
        $IssCode = trim($rIssCode);
        $AquCode = trim($rAquCode);
        $AuthNo = trim($rAuthNo);
        $Message1 = trim($rMessage1);
        $Message2 = trim($rMessage2);
        $CardNo = trim($rCardNo);
        $ExpDate = trim($rExpDate);
        $Installment = trim($rInstallment);
        $Amount = trim($rAmount);
        $MerchantNo = trim($rMerchantNo);
        $AuthSendType = trim($rAuthSendType);
        $ApprovalSendType = trim($rApprovalSendType);
        $Point1 = trim($rPoint1);
        $Point2 = trim($rPoint2);
        $Point3 = trim($rPoint3);
        $Point4 = trim($rPoint4);
        $VanTransactionNo = trim($rVanTransactionNo);
        $Filler = trim($rFiller);
        $AuthType = trim($rAuthType);
        $MPIPositionType = trim($rMPIPositionType);
        $EncData = trim($rEncData);

        $query = "insert into tb_kspay (ordernumber, ordername, phoneno, email, goodname,
                                amount, CardNo, ExpDate, installment, idnum, passwd,
                                ApprovalType, TransactionNo, Status, TradeDate, TradeTime, IssCode,
                                AquCode, AuthNo, Message1, Message2, MerchantNo, AuthSendType,
                                ApprovalSendType, Point1, Point2, Point3, Point4, VanTransactionNo,
                                Filler, AuthType, MPIPositionType, inputid, inputdate, InterestType) values 
                                ('$OrderNumber', '$UserName', '$PhoneNo', '$Email', '$GoodName',
                                 '$Amount', '$CardNo', '$ExpDate', '$Installment', '$IdNum', '$Passwd',
                                 '$ApprovalType', '$TransactionNo', '$Status', '$TradeDate', '$TradeTime', '$IssCode',
                                 '$AquCode', '$AuthNo', '$Message1', '$Message2', '$MerchantNo', '$AuthSendType', 
                             	 '$ApprovalSendType', '$Point1', '$Point2', '$Point3', '$Point4', '$VanTransactionNo', 
                             	 '$Filler', '$AuthType', '$MPIPositionType', '$adm_id' ,now(), '$InterestType')";

        mysql_query($query) or die("Query Error");
        mysql_close($connect);

        echo("OrderNumber           =[".$OrderNumber     ."]<br>");    
        echo("UserName          	=[".$UserName        ."]<br>");    
        echo("IdNum                 =[".$IdNum               ."]<br>");    
        echo("Email                 =[".$Email               ."]<br>");    
        echo("GoodName          	=[".$GoodName        ."]<br>");    
        echo("PhoneNo           	=[".$PhoneNo         ."]<br>");    
        echo("InterestType          =[".$InterestType    ."]<br>");    
        echo("Passwd            	=[".$Passwd          ."]<br>");    

        echo("ApprovalType          =[".$rApprovalType   ."]<br>");    
        echo("TransactionNo         =[".$rTransactionNo  ."]<br>");   // 거래번호
        echo("Status                =[".$rStatus                 ."]<br>");   // 상태 O : 승인, X : 거절
        echo("TradeDate             =[".$rTradeDate              ."]<br>");   // 거래일자
        echo("TradeTime             =[".$rTradeTime              ."]<br>");   // 거래시간
        echo("IssCode               =[".$rIssCode                ."]<br>");       // 발급사코드
        echo("AquCode               =[".$rAquCode                ."]<br>");       // 매입사코드
        echo("AuthNo                =[".$rAuthNo                 ."]<br>");   // 승인번호 or 거절시 오류코드
        echo("Message1              =[".$rMessage1               ."]<br>");       // 메시지1
        echo("Message2              =[".$rMessage2               ."]<br>");       // 메시지2
        echo("CardNo                =[".$rCardNo                 ."]<br>");   // 카드번호
        echo("ExpDate               =[".$rExpDate                ."]<br>");       // 유효기간
        echo("Installment           =[".$rInstallment    ."]<br>");       // 할부
        echo("Amount                =[".$rAmount                 ."]<br>");   // 금액
        echo("MerchantNo            =[".$rMerchantNo             ."]<br>");   // 가맹점번호
        echo("AuthSendType          =[".$rAuthSendType   ."]<br>");       // 전송구분
        echo("ApprovalSendType  	=[".$rApprovalSendType."]<br>");   // 전송구분(0 : 거절, 1 : 승인, 2: 원카드)
        echo("Point1                =[".$rPoint1                 ."]<br>");   // Point1
        echo("Point2                =[".$rPoint2                 ."]<br>");   // Point2
        echo("Point3                =[".$rPoint3                 ."]<br>");   // Point3
        echo("Point4                =[".$rPoint4                 ."]<br>");   // Point4
        echo("VanTransactionNo  	=[".$rVanTransactionNo."]<br>");   // Van거래번호
        echo("Filler                =[".$rFiller                 ."]<br>");   // 예비
        echo("AuthType              =[".$rAuthType           ."]<br>");       // ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
        echo("MPIPositionType   	=[".$rMPIPositionType ."]<br>");   // K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
        echo("EncData               =[".$rEncData                ."]<br>");   // MPI, ISP 데이터

	if (trim($Status) == "X") {
?>
<script language="javascript">
	alert("<?echo $Message1?>, <?echo $Message2?> 의 이유로 승인이 거절 되었습니다.\n\n입력 사항을 다시 한번 확인해 보십시오.");
</script>
<?
	} else {
?>
<script language="javascript">
	alert("<?echo $Message1?>, <?echo $Message2?> 의 승인번호로 승인 되었습니다.");
//    parent.location = 'KSPAY_list.php';
 </script>
<?
	}
   exit;
?>
