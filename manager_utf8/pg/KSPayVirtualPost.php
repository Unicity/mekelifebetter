<!------------------------------------------------------------------------------
 FILE NAME : KSPayVirtualPost.php
 AUTHOR : kspay@ksnet.co.kr
 DATE : 2004-05-03
                                                         http://www.kspay.co.kr
                                                         http://www.ksnet.co.kr
                                  Copyright 2003 KSNET, Co. All rights reserved
------------------------------------------------------------------------------->
 <? include "./KSPayApprovalCancel.inc"; ?> 
<?
// Default(수정항목이 아님)-------------------------------------------------------
	$EncType     = "0";     // 0: 암화안함, 1:openssl, 2: seed
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
    $BankCode         = $HTTP_POST_VARS["bankcode"]; //은행코드
    $Filler                 = "";

// 승인거절 응답
// Server로 부터 응답이 없을시 자체응답
	
	$rVATransactionNo    = "";              // 거래번호
	$rVAStatus           = "X";             // 상태 O : 승인, X : 거절
	$rVATradeDate        = "";              // 거래일자
	$rVATradeTime        = "";              // 거래시간
	$rVABankCode         = "";
    $rVAName               = "";
	$rVAMessage1         = "승인거절";      // 메시지1
	$rVAMessage2         = "C잠시후재시도"; // 메시지2
	$rVAFiller           = "";              // 예비

	KSPayApprovalCancel("localhost", 29991);

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

    VirtualAccountDataMessage (
	    $ApprovalType, 
		$BankCode, 
		$Amount, 
		$Filler           );

	if (SendSocket("6")) {
		$rVATransactionNo		= $VATransactionNo	;  	// 거래번호
		$rVAStatus			= $VAStatus		  	;	// 상태 O : 승인, X : 거절
		$rVATradeDate			= $VATradeDate		;  	// 거래일자
		$rVATradeTime			= $VATradeTime		;  	// 거래시간
		$rVABankCode			= $VABankCode		  	;	// 발급사코드
		$rVAVirAcctNo 			= $VAVirAcctNo		  	;	// 매입사코드
		$rVAName			= $VAName		  	;	// 승인번호 or 거절시 오류코드
		$rVAMessage1			= $VAMessage1		  	;	// 메시지1
		$rVAMessage2			= $VAMessage2		  	;	// 메시지2
		$rVAFiller			= $VAFiller		  	;	// 예비
    }

    
	echo("ApprovalType	    =[".$ApprovalType	   ."]<br>");    
	echo("VATransactionNo	    =[".$rVATransactionNo	 ."]<br>");   // 거래번호
	echo("VAStatus			=[".$rVAStatus		     ."]<br>");   // 상태 O : 승인, X : 거절
	echo("VATradeDate		    =[".$rVATradeDate		 ."]<br>");   // 거래일자
	echo("VATradeTime		    =[".$rVATradeTime		 ."]<br>");   // 거래시간
	echo("VABankCode		    =[".$rVABankCode		 ."]<br>");	  // 발급사코드
	echo("VAVirAcctNo		    =[".$rVAVirAcctNo		 ."]<br>");	  // 매입사코드
	echo("VAName			=[".$rVAName		     ."]<br>");   // 승인번호 or 거절시 오류코드
	echo("VAMessage1		    =[".$rVAMessage1		 ."]<br>");	  // 메시지1
	echo("VAMessage2		    =[".$rVAMessage2		 ."]<br>");	  // 메시지2
	echo("VAFiller			=[".$rVAFiller		     ."]<br>");   // 예비
?>
