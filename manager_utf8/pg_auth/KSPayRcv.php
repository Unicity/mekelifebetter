<?
// 공통--------------------------------------------------------------------------------------
    $reEncType       = $HTTP_POST_VARS["reEncType"];                          // 암호화구분                                  
    $reVersion       = $HTTP_POST_VARS["reVersion"];                          // 전문버전                                    
    $reType          = $HTTP_POST_VARS["reType"];                             // 전문구분                                    
    $reResend        = $HTTP_POST_VARS["reResend"];                           // 전송구분                                    
    $reRequestDate   = $HTTP_POST_VARS["reRequestDate"];                      // 요청일                                      
    $reStoreId       = $HTTP_POST_VARS["reStoreId"];                          // 상점아이디                                  
    $reOrderNumber   = $HTTP_POST_VARS["reOrderNumber"];                      // 주문번호                                    
    $reOrderName     = $HTTP_POST_VARS["reOrderName"];                        // 주문자명                                    
    $reAllRegid      = $HTTP_POST_VARS["reAllRegid"];                         // 주민번호                                    
    $reEmail         = $HTTP_POST_VARS["reEmail"];                            // 이메일                                      
    $reGoodType      = $HTTP_POST_VARS["reGoodType"];                         // 상품구분 1:실물, 2: 디지털                  
    $reGoodName      = $HTTP_POST_VARS["reGoodName"];                         // 상품명                                      
    $reKeyInType     = $HTTP_POST_VARS["reKeyInType"];                        // Keyin구분 K : Keyin                         
    $reLineType      = $HTTP_POST_VARS["reLineType"];                         // 유무선구분 0: offline, 1: online(internet)  
    $reMobile        = $HTTP_POST_VARS["reMobile"];                           // 휴대폰번호                                  
    $reApprovalCount = $HTTP_POST_VARS["reApprovalCount"];                    // 전문갯수                                    
    $reHeadFiller    = $HTTP_POST_VARS["reHeadFiller"];                       // 예비                                        
                                                                                                                             
    $reApprovalType  = $HTTP_POST_VARS["reApprovalType"];                     // 승인구분                                    
                                                                                                                             
// 신용카드--------------------------------------------------------------------------------------                            
    $reTransactionNo     = $HTTP_POST_VARS["reTransactionNo"];                // 거래번호                                    
    $reStatus            = $HTTP_POST_VARS["reStatus"];                       // 상태                                        
    $reTradeDate         = $HTTP_POST_VARS["reTradeDate"];                    // 거래일자                                    
    $reTradeTime         = $HTTP_POST_VARS["reTradeTime"];                    // 거래시간                                    
    $reIssCode           = $HTTP_POST_VARS["reIssCode"];                      // 발급사코드                                  
    $reAquCode           = $HTTP_POST_VARS["reAquCode"];                      // 매입사코드                                  
    $reAuthNo            = $HTTP_POST_VARS["reAuthNo"];                       // 승인번호                                    
    $reMessage1          = $HTTP_POST_VARS["reMessage1"];                     // 메시지1                                     
    $reMessage2          = $HTTP_POST_VARS["reMessage2"];                     // 메시지2                                     
    $reCardNo            = $HTTP_POST_VARS["reCardNo"];                       // 카드번호14자리+XXXX                         
    $reExpDate           = $HTTP_POST_VARS["reExpDate"];                      // 유효기간 YYMM                               
    $reInstallment       = $HTTP_POST_VARS["reInstallment"];                  // 할부개월수                                  
    $reAmount            = $HTTP_POST_VARS["reAmount"];                       // 금액                                        
    $reMerchantNo        = $HTTP_POST_VARS["reMerchantNo"];                   // 가맹점번호                                  
    $reAuthSendType      = $HTTP_POST_VARS["reAuthSendType"];                 // 인증구분                                    
    $reApprovalSendType  = $HTTP_POST_VARS["reApprovalSendType"];             // 승인구분                                    
    $rePoint1            = $HTTP_POST_VARS["rePoint1"];                       //                                             
    $rePoint2            = $HTTP_POST_VARS["rePoint2"];                       //                                             
    $rePoint3            = $HTTP_POST_VARS["rePoint3"];                       //                                             
    $rePoint4            = $HTTP_POST_VARS["rePoint4"];                       //                                             
    $reVanTransactionNo  = $HTTP_POST_VARS["reVanTransactionNo"];             //                                             
    $reFiller            = $HTTP_POST_VARS["reFiller"];                       //                                             
    $reAuthType          = $HTTP_POST_VARS["reAuthType"];                     //                                             
    $reMPIPositionType   = $HTTP_POST_VARS["reMPIPositionType"];              //                                             
    $reMPIReUseType      = $HTTP_POST_VARS["reMPIReUseType"];                 //                                             
                                                                                                                             
    $reInterest          = $HTTP_POST_VARS["reInterest"];                     // 이자구분 1: 일반, 2: 무이자                 
                                                                                                                             
	$rePApprovalType     = $HTTP_POST_VARS["rePApprovalType"];                // 포인트승인구분                              
	$rePTransactionNo    = $HTTP_POST_VARS["rePTransactionNo"];               // 포인트거래번혼                              
	$rePStatus           = $HTTP_POST_VARS["rePStatus"];                      // 포인트승인상태                              
	$rePTradeDate        = $HTTP_POST_VARS["rePTradeDate"];                   // 포인트거래일자                              
	$rePTradeTime        = $HTTP_POST_VARS["rePTradeTime"];                   // 포인트거래시간                              
	$rePIssCode          = $HTTP_POST_VARS["rePIssCode"];                     // 포인트발급사코드                            
	$rePAuthNo           = $HTTP_POST_VARS["rePAuthNo"];                      // 포인트승인번호                              
	$rePMessage1         = $HTTP_POST_VARS["rePMessage1"];                    // 메시지1                                     
	$rePMessage2         = $HTTP_POST_VARS["rePMessage2"];                    // 메시지2                                     
	$rePPoint1           = $HTTP_POST_VARS["rePPoint1"];                      // 거래포인트                                  
	$rePPoint2           = $HTTP_POST_VARS["rePPoint2"];                      // 가용포인트                                  
	$rePPoint3           = $HTTP_POST_VARS["rePPoint3"];                      // 누적포인트                                  
	$rePPoint4           = $HTTP_POST_VARS["rePPoint4"];                      // 가맹점포인트                                
	$rePMerchantNo       = $HTTP_POST_VARS["rePMerchantNo"];                  // 가맹점번호                                  
	$rePNotice1          = $HTTP_POST_VARS["rePNotice1"];                     //                                             
	$rePNotice2          = $HTTP_POST_VARS["rePNotice2"];                     //                                             
	$rePNotice3          = $HTTP_POST_VARS["rePNotice3"];                     //                                             
	$rePNotice4          = $HTTP_POST_VARS["rePNotice4"];                     //                                             
	$rePFiller           = $HTTP_POST_VARS["rePFiller"];                      //                                             
                                                                                                                             
// 가상계좌--------------------------------------------------------------------------------------                            
	$reVATransactionNo   = $HTTP_POST_VARS["reVATransactionNo"];              // 가상계좌거래번호                            
	$reVAStatus          = $HTTP_POST_VARS["reVAStatus"];                     // 상태                                        
	$reVATradeDate       = $HTTP_POST_VARS["reVATradeDate"];                  // 거래일자                                    
	$reVATradeTime       = $HTTP_POST_VARS["reVATradeTime"];                  // 거래시간                                    
	$reVABankCode        = $HTTP_POST_VARS["reVABankCode"];                   // 은행코드                                    
	$reVAVirAcctNo       = $HTTP_POST_VARS["reVAVirAcctNo"];                  // 가상계좌번호                                
	$reVAName            = $HTTP_POST_VARS["reVAName"];                       // 예금주명                                    
	$reVAMessage1        = $HTTP_POST_VARS["reVAMessage1"];                   // 메시지1                                     
	$reVAMessage2        = $HTTP_POST_VARS["reVAMessage2"];                   // 메시지2                                     
	$reVAFiller          = $HTTP_POST_VARS["reVAFiller"];                     // 예비                                        
                                                                                                                             
// 월드패스--------------------------------------------------------------------------------------                            
	$reWPTransactionNo   = $HTTP_POST_VARS["reWPTransactionNo"];              // 월드패스거래번호                            
	$reWPStatus          = $HTTP_POST_VARS["reWPStatus"];                     // 상태                                        
	$reWPTradeDate       = $HTTP_POST_VARS["reWPTradeDate"];                  // 거래일자                                    
	$reWPTradeTime       = $HTTP_POST_VARS["reWPTradeTime"];                  // 거래시간                                    
	$reWPIssCode         = $HTTP_POST_VARS["reWPIssCode"];                    // 발급사코드                                  
	$reWPAuthNo          = $HTTP_POST_VARS["reWPAuthNo"];                     // 승인번호                                    
	$reWPBalanceAmount   = $HTTP_POST_VARS["reWPBalanceAmount"];              // 잔액                                        
	$reWPLimitAmount     = $HTTP_POST_VARS["reWPLimitAmount"];                // 한도액                                      
	$reWPMessage1        = $HTTP_POST_VARS["reWPMessage1"];                   // 메시지1                                     
	$reWPMessage2        = $HTTP_POST_VARS["reWPMessage2"];                   // 메시지2                                     
	$reWPCardNo          = $HTTP_POST_VARS["reWPCardNo"];                     // 카드번호                                    
	$reWPAmount          = $HTTP_POST_VARS["reWPAmount"];                     // 금액                                        
	$reWPMerchantNo      = $HTTP_POST_VARS["reWPMerchantNo"];                 // 가맹점번호                                  
	$reWPFiller          = $HTTP_POST_VARS["reWPFiller"];                     // 예비                                        
    
    if(substr($reApprovalType,0,1) == "1" || substr($reApprovalType,0,1) == "I" ) {				// 신용카드(1=MPI, I=ISP)
        $authyn = $HTTP_POST_VARS["reStatus"];
        $trno   = $HTTP_POST_VARS["reTransactionNo"];
        $trddt  = $HTTP_POST_VARS["reTradeDate"];
        $trdtm  = $HTTP_POST_VARS["reTradeTime"];
        $amt    = $HTTP_POST_VARS["reAmount"];
        $authno = $HTTP_POST_VARS["reAuthNo"];
        $msg1   = $HTTP_POST_VARS["reMessage1"];
        $msg2   = $HTTP_POST_VARS["reMessage2"];
        $ordno  = $HTTP_POST_VARS["reOrderNumber"];
        $isscd  = $HTTP_POST_VARS["reIssCode"];
        $aqucd  = $HTTP_POST_VARS["reAquCode"];
        $temp_v = $HTTP_POST_VARS["reTemp_v"];
        $result = $HTTP_POST_VARS["reApprovalType"];
	}
    else if(substr($reApprovalType,0,1) == "4" ) {							// 포인트
        $authyn = $HTTP_POST_VARS["rePStatus"];
        $trno   = $HTTP_POST_VARS["rePTransactionNo"];
        $trddt  = $HTTP_POST_VARS["rePTradeDate"];
        $trdtm  = $HTTP_POST_VARS["rePTradeTime"];
        $amt    = $HTTP_POST_VARS["reAmount"];
        $authno = $HTTP_POST_VARS["rePAuthno"];
        $msg1   = $HTTP_POST_VARS["rePMessage1"];
        $msg2   = $HTTP_POST_VARS["rePMessage2"];
        $ordno  = $HTTP_POST_VARS["reOrderNumber"];
        $isscd  = $HTTP_POST_VARS["rePIssCode"];
        $aqucd  = "";
     	$temp_v = $HTTP_POST_VARS["reTemp_v"];
        $result = $HTTP_POST_VARS["reApprovalType"];
	}
    else if(substr($reApprovalType,0,1) == "6" ) {							// 가상계좌
        $authyn = $HTTP_POST_VARS["reVAStatus"];
        $trno   = $HTTP_POST_VARS["reVATransactionNo"];
        $trddt  = $HTTP_POST_VARS["reVATradeDate"];
        $trdtm  = $HTTP_POST_VARS["reVATradeTime"];
        $amt    = $HTTP_POST_VARS["reAmount"];
        $authno = $HTTP_POST_VARS["reVABankCode"];
        $msg1   = $HTTP_POST_VARS["reVAMessage1"];
        $msg2   = $HTTP_POST_VARS["reVAMessage2"];
        $ordno  = $HTTP_POST_VARS["reOrderNumber"];
        $isscd  = $HTTP_POST_VARS["reVAVirAcctNo"];
        $aqucd  = "";
        $temp_v = $HTTP_POST_VARS["reTemp_v"];
        $result = $HTTP_POST_VARS["reApprovalType"];
	}
	    else if(substr($reApprovalType,0,1) == "2" ) {							// 계좌이체
        $authyn = $HTTP_POST_VARS["reVAStatus"];
        $trno   = $HTTP_POST_VARS["reVATransactionNo"];
        $trddt  = $HTTP_POST_VARS["reVATradeDate"];
        $trdtm  = $HTTP_POST_VARS["reVATradeTime"];
        $amt    = $HTTP_POST_VARS["reAmount"];
        $authno = $HTTP_POST_VARS["reVABankCode"];
        $msg1   = $HTTP_POST_VARS["reVAMessage1"];
        $msg2   = $HTTP_POST_VARS["reVAMessage2"];
        $ordno  = $HTTP_POST_VARS["reOrderNumber"];
        $isscd  = $HTTP_POST_VARS["reVAVirAcctNo"];
        $aqucd  = "";
        $temp_v = $HTTP_POST_VARS["reTemp_v"];
        $result = $HTTP_POST_VARS["reApprovalType"];
	}
    else if(substr($reApprovalType,0,1) == "7" ) {							// 월드패스
        $authyn = $HTTP_POST_VARS["reWPStatus"];
        $trno   = $HTTP_POST_VARS["reWPTransactionNo"];
        $trddt  = $HTTP_POST_VARS["reWPTradeDate"];
        $trdtm  = $HTTP_POST_VARS["reWPTradeTime"];
        $amt    = $HTTP_POST_VARS["reAmount"];
        $authno = $HTTP_POST_VARS["reWPAuthNo"];
        $msg1   = $HTTP_POST_VARS["reWPMessage1"];
        $msg2   = $HTTP_POST_VARS["reWPMessage2"];
        $ordno  = $HTTP_POST_VARS["reOrderNumber"];
        $isscd  = "";
        $aqucd  = "";
        $temp_v = $HTTP_POST_VARS["reTemp_v"];
        $result = $HTTP_POST_VARS["reApprovalType"];
	}


	include "../../dbconn.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	$OrderNumber = trim($reOrderNumber);
	$UserName = trim($reOrderName);
	$IdNum = trim($reAllRegid);
	$Email = trim($reEmail);
	$GoodName = trim($reGoodName);
	$PhoneNo = trim($reMobile);
	$InterestType = trim($reInterest);
	$Passwd = trim($Passwd);
	$ApprovalType = trim($reApprovalSendType);
	$TransactionNo = trim($reTransactionNo);
	$Status = trim($reStatus);
	$TradeDate = trim($reTradeDate);
	$TradeTime = trim($reTradeTime);
	$IssCode = trim($reIssCode);
	$AquCode = trim($reAquCode);
	$AuthNo = trim($reAuthNo);
	$Message1 = trim($reMessage1);
	$Message2 = trim($reMessage2);
	$CardNo = trim($reCardNo);
	$ExpDate = trim($reExpDate);
	$Installment = trim($reInstallment);
	$Amount = trim($reAmount);
	$MerchantNo = trim($reMerchantNo);
	$AuthSendType = trim($reAuthSendType);
	$ApprovalSendType = trim($reApprovalSendType);
	$Point1 = trim($rePoint1);
	$Point2 = trim($rePoint2);
	$Point3 = trim($rePoint3);
	$Point4 = trim($rePoint4);
	$VanTransactionNo = trim($reVanTransactionNo);
	$Filler = trim($reFiller);
	$AuthType = trim($reAuthType);
	$MPIPositionType = trim($reMPIPositionType);
	$EncData = trim($reEncData);

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
			     '$Filler', '$AuthType', '$MPIPositionType', '$s_adm_id' ,now(), '$InterestType')";

	mysql_query($query) or die("Query Error");
	mysql_close($connect);

?>  
<script language="JavaScript">
<!--
    function init(){
        top.opener.paramSet("<?echo($authyn)?>","<?echo($trno)?>","<?echo($trddt)?>","<?echo($trdtm)?>","<?echo($authno)?>","<?echo($ordno)?>","<?echo($msg1)?>","<?echo($msg2)?>","<?echo($amt)?>","<?echo($temp_v)?>","<?echo($isscd)?>","<?echo($aqucd)?>","","<?echo($result)?>");
        top.opener.goResult();
        window.close();
    }
-->
</script>
<body onload="init();" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="white">
</body>