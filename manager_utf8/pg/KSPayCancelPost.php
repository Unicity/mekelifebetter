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
// Default-------------------------------------------------------
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
	$StoreId     =$HTTP_POST_VARS["storeid"];     	// *상점아이디
	$OrderNumber =""; 								// *주문번호
	$UserName    ="";   							// *주문자명
	$IdNum       ="";       						// 주민번호 or 사업자번호
	$Email       ="";       						// *email
	$GoodName    ="";    							// *제품명
	$PhoneNo     ="";     							// *휴대폰번호
// Header end -------------------------------------------------------------------
	
// Data Default(수정항목이 아님)-------------------------------------------------
	$ApprovalType    = $HTTP_POST_VARS["authty"]; // 승인구분
	$TransactionNo   = $HTTP_POST_VARS["trno"];   // 거래번호
// Data Default end -------------------------------------------------------------

// 승인거절 응답
// Server로 부터 응답이 없을시 자체응답
	$rApprovalType     = "1011";
	$rTransactionNo    = "";              // 거래번호
	$rStatus           = "X";             // 상태 O : 승인, X : 거절
	$rTradeDate        = "";              // 거래일자
	$rTradeTime        = "";              // 거래시간
	$rIssCode          = "00";            // 발급사코드
	$rAquCode          = "00";            // 매입사코드
	$rAuthNo           = "9999";          // 승인번호 or 거절시 오류코드
	$rMessage1         = "취소거절";      // 메시지1
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


// ------------------------------------------------------------------------------
	CancelDataMessage(
		$ApprovalType,      // ApprovalType,	: 승인구분                       
		"0",       			// CancelType,	: 취소처리구분 1:거래번호, 2:주문번호
		$TransactionNo,     // TransactionNo,: 거래번호                          
		"",                 // TradeDate,	: 거래일자                           
		"",                 // OrderNumber,	: 주문번호                           
		"");                // Filler)		: 기타                               

	if (SendSocket("1")) {
		$rApprovalType		= $ApprovalType	    ;
		$rTransactionNo		= $TransactionNo	;  	// 거래번호
		$rStatus			= $Status		  	;	// 상태 O : 승인, X : 거절
		$rTradeDate			= $TradeDate		;  	// 거래일자
		$rTradeTime			= $TradeTime		;  	// 거래시간
		$rIssCode			= $IssCode		  	;	// 발급사코드
		$rAquCode			= $AquCode		  	;	// 매입사코드
		$rAuthNo			= $AuthNo		  	;	// 승인번호 or 거절시 오류코드
		$rMessage1			= $Message1		  	;	// 메시지1
		$rMessage2			= $Message2		  	;	// 메시지2
		$rCardNo			= $CardNo		  	;	// 카드번호
		$rExpDate			= $ExpDate		  	;	// 유효기간
		$rInstallment		= $Installment	  	;	// 할부
		$rAmount			= $Amount		  	;	// 금액
		$rMerchantNo		= $MerchantNo	  	;	// 가맹점번호
		$rAuthSendType		= $AuthSendType	  	;	// 전송구분= new String(this.read(2))
		$rApprovalSendType	= $ApprovalSendType	;	// 전송구분(0 : 거절, 1 : 승인, 2: 원카드)
		$rPoint1			= $Point1		  	;	// Point1
		$rPoint2			= $Point2		  	;	// Point2
		$rPoint3			= $Point3		  	;	// Point3
		$rPoint4			= $Point4		  	;	// Point4
		$rVanTransactionNo  = $VanTransactionNo ;   // Van거래번호
		$rFiller			= $Filler		  	;	// 예비
		$rAuthType			= $AuthType		  	;	// ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
		$rMPIPositionType	= $MPIPositionType 	;	// K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
		$rMPIReUseType		= $MPIReUseType		;	// Y : 재사용, N : 재사용아님
		$rEncData			= $EncData		  	;	// MPI, ISP 데이터
	}

	include "../../dbconn_utf8.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	$TransactionNo = trim($rTransactionNo);
	$rTradeDate = trim($rTradeDate);
	$rTradeTime = trim($rTradeTime);
	$rStatus = trim($rStatus);

	if ($rStatus == "O") {

		$query = "update tb_kspay set 
					CTradeDate = '$rTradeDate',  	
					CTradeTime = '$rTradeTime',  	
					CStatus = '$rStatus',
					Canceldate = now()  	
				 	where TransactionNo = '$rTransactionNo' ";

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('승인 취소 되었습니다.');
			opener.parent.frames[3].location = 'KSPAY_list.php';
			self.close();
			</script>";
		exit;

	} else {
		
		echo "<script language=\"javascript\">\n
			alert('이미 승인 취소되었거나, 승인 취소가 거절 되었습니다.\\n확인 후 다시 시도해 주십시오');
			opener.parent.frames[3].location = 'KSPAY_list.php';
			self.close();
			</script>";
		exit;

	}
/*	
	echo("ApprovalType	    =[".$rApprovalType	 ."]<br>");    
	echo("TransactionNo	    =[".$rTransactionNo	 ."]<br>");   // 거래번호
	echo("Status			=[".$rStatus		     ."]<br>");   // 상태 O : 승인, X : 거절
	echo("TradeDate		    =[".$rTradeDate		 ."]<br>");   // 거래일자
	echo("TradeTime		    =[".$rTradeTime		 ."]<br>");   // 거래시간
	echo("IssCode		    =[".$rIssCode		 ."]<br>");	  // 발급사코드
	echo("AquCode		    =[".$rAquCode		 ."]<br>");	  // 매입사코드
	echo("AuthNo			=[".$rAuthNo		     ."]<br>");   // 승인번호 or 거절시 오류코드
	echo("Message1		    =[".$rMessage1		 ."]<br>");	  // 메시지1
	echo("Message2		    =[".$rMessage2		 ."]<br>");	  // 메시지2
	echo("CardNo			=[".$rCardNo		     ."]<br>");   // 카드번호
	echo("ExpDate		    =[".$rExpDate		 ."]<br>");	  // 유효기간
	echo("Installment	    =[".$rInstallment	 ."]<br>");	  // 할부
	echo("Amount			=[".$rAmount		     ."]<br>");   // 금액
	echo("MerchantNo		=[".$rMerchantNo	     ."]<br>");   // 가맹점번호
	echo("AuthSendType	    =[".$rAuthSendType	 ."]<br>");	  // 전송구분
	echo("ApprovalSendType  =[".$rApprovalSendType."]<br>");   // 전송구분(0 : 거절, 1 : 승인, 2: 원카드)
	echo("Point1			=[".$rPoint1		     ."]<br>");   // Point1
	echo("Point2			=[".$rPoint2		     ."]<br>");   // Point2
	echo("Point3			=[".$rPoint3		     ."]<br>");   // Point3
	echo("Point4			=[".$rPoint4		     ."]<br>");   // Point4
	echo("VanTransactionNo  =[".$rVanTransactionNo."]<br>");   // Van거래번호
	echo("Filler			=[".$rFiller		     ."]<br>");   // 예비
	echo("AuthType		 	=[".$rAuthType		 ."]<br>");	  // ISP : ISP거래, MP1, MP2 : MPI거래, SPACE : 일반거래
	echo("MPIPositionType   =[".$rMPIPositionType ."]<br>");   // K : KSNET, R : Remote, C : 제3기관, SPACE : 일반거래
	echo("EncData		    =[".$rEncData		 ."]<br>");   // MPI, ISP 데이터
*/
?>
