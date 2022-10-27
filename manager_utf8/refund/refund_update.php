<?php
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../inc/common_function.php";
	include "../../AES.php";
	$type = isset($_POST['processType']) ? $_POST['processType'] : "aaaa";
	$query = "update refund set ";
	
	$postDate 			= isset($_POST['postDate']) ? $_POST['postDate'] : "";
	$baId 				= isset($_POST['baId']) ? $_POST['baId'] : "";
	$baName 			= isset($_POST['baName']) ? $_POST['baName'] : "";
	$comissionPeriod 	= isset($_POST['comissionPeriod']) ? $_POST['comissionPeriod'] : "";
	$orderNo 			= isset($_POST['orderNo']) ? $_POST['orderNo'] : "";
	$returnNo 			= isset($_POST['returnNo']) ? $_POST['returnNo'] : "";
	$orderDate 			= isset($_POST['orderDate']) ? $_POST['orderDate'] : "";
	$orderTotal 		= isset($_POST['orderTotal']) ? $_POST['orderTotal'] : 0;
	$rtnPvAdj 			= isset($_POST['rtnPvAdj']) ? $_POST['rtnPvAdj'] : 0;
	$rtnPvVol			= isset($_POST['rtnPvVol']) ? $_POST['rtnPvVol'] : 0;
	$rtnAmtAdj 			= isset($_POST['rtnAmtAdj']) ?  $_POST['rtnAmtAdj']  : 0;
	$rtnAmtVol 			= isset($_POST['rtnAmtVol']) ? $_POST['rtnAmtVol'] : 0;
	$deductCommision 	= isset($_POST['deductCommision']) ? $_POST['deductCommision'] : 0;
	$deductCardFee 		= isset($_POST['deductCardFee']) ? $_POST['deductCardFee'] : 0;
	$shopId 			= isset($_POST['shopId']) ? $_POST['shopId'] : "";
	$cardBankType 		= isset($_POST['cardBankType']) ? $_POST['cardBankType'] : "";			
	$cardAccountNo 		= isset($_POST['cardAccountNo']) ? $_POST['cardAccountNo'] : "";			
	$expireDate 		= isset($_POST['expireDate']) ? $_POST['expireDate'] : "";			
	$installment 		= isset($_POST['installment']) ? $_POST['installment'] : '0';			
	
	$cashAmount 		= isset($_POST['cashAmount']) ? $_POST['cashAmount'] : 0;			
	$cardAmount 		= isset($_POST['cardAmount']) ? $_POST['cardAmount'] : 0;			
	$etc 				= isset($_POST['etc']) ? $_POST['etc'] : "";

	$dsc 				= isset($_POST['dsc']) ? $_POST['dsc'] : "";	
	$approvalAmount 	= isset($_POST['approvalAmount']) ? $_POST['approvalAmount'] : 0;
	$approvalNo 		= isset($_POST['approvalNo']) ? $_POST['approvalNo'] : "";			
	$reApprovalAmount	= isset($_POST['reApprovalAmount']) ? $_POST['reApprovalAmount'] : 0;		 
	$reApprovalNo 		= isset($_POST['reApprovalNo']) ? $_POST['reApprovalNo'] : "";	 
	
	$processDate		= isset($_POST['processDate']) ? $_POST['processDate'] : "";
	$adminID			= isset($_POST['adminID']) ? $_POST['adminID'] : "";
	$RefundNo			= isset($_POST['RefundNo']) ? $_POST['RefundNo'] : "";
	


	
	if($type == 'new') {
	    
	    $postDate = substr($postDate,0,4).'/'.substr($postDate,4,2).'/'.substr($postDate,6,8);
	    $orderDate = substr($orderDate,0,4).'/'.substr($orderDate,4,2).'/'.substr($orderDate,6,8);
	    $comissionPeriod = substr($comissionPeriod,0,2).'/'.substr($comissionPeriod,2,4);

// 콤마제거
	    $orderTotal  = preg_replace("/[^\d]/","",$orderTotal);
	    $approvalAmount = preg_replace("/[^\d]/","",$approvalAmount);
	    $cashAmount = preg_replace("/[^\d]/","",$cashAmount);
	    $cardAmount = preg_replace("/[^\d]/","",$cardAmount);
	    //$rtnPvVol 	= preg_replace("/[^\d]/","",$rtnPvVol);
	    //`$rtnPvAdj =  preg_replace("/[^\d]/","",$rtnPvAdj);
	    //$rtnAmtAdj 	= preg_replace("/[^\d]/","",$rtnAmtAdj);
	    //$rtnAmtVol =  preg_replace("/[^\d]/","",$rtnAmtVol)
	    
	
	    for($i=0; $i<sizeof($expireDate); $i++){
	        
	        $expireDate[$i] = substr($expireDate[$i],0,2).'/'.substr($expireDate[$i],2,4);
	     
	    }
	   
	    
		$insertRefundMasterQuery = "INSERT INTO tb_refund_header (`PostDate`, `RefundStatus`, `memberID`, `memberName`, `CommissionPeriod`, `OrderNo`, `ReturnNo`,`OrderDate` ,`OrderTotal`,`ReturnPV`,`ReturnPVAdjustment`,`ReturnAmount`,`ReturnAmountAdjustment`,`CreatedDSC`,`CreatedDate`,`Creator` ) VALUES ('$postDate 00:00:00','10','$baId','$baName','$comissionPeriod','$orderNo','$returnNo','$orderDate 00:00:00', $orderTotal,$rtnPvVol, $rtnPvAdj,$rtnAmtVol,$rtnAmtAdj,'$dsc', now(),'$adminID')";
		
		$insertRefundMasterQueryResult = mysql_query($insertRefundMasterQuery);

		$refundID = mysql_insert_id();

		$insertRefundLineQuery = "INSERT INTO tb_refund_line (`RefundId`, `RefundLine`, `Commission`, `CardFee`, `StoreID`, `StoreName`, `CardNumber`,`ExpireDate` ,`Installment`,`ApprovalAmount`,`ApprovalNumber`,`Comments`,`ReApprovalAmount`,`ReApprovalNumber`,`cashAmount`,`cardAmount`, `LastModifiedDate`, `LastModifier`, `CreatedDate`, `Creator` ) VALUES ";

		for($i=0; $i<sizeof($cardBankType); $i++){
		    
		    $cardAccountNo[$i] = encrypt($key, $iv, $cardAccountNo[$i]);
		    
			$shopId[$i] 			= isset($shopId[$i]) ? $shopId[$i] : '';
			$cardAccountNo[$i]  	= isset($cardAccountNo[$i]) ? $cardAccountNo[$i] : '';
			$expireDate[$i] 		= isset($expireDate[$i]) ? $expireDate[$i] : '';
			$installment[$i] 		= isset($installment[$i]) ? $installment[$i] : '0';
			
			$cashAmount[$i]			= isset($cashAmount[$i]) ? $cashAmount[$i] : 0;
			$cardAmount[$i]			= isset($cardAmount[$i]) ? $cardAmount[$i] : 0;
			$etc[$i]				= isset($etc[$i]) ? $etc[$i] : '';
			$approvalAmount[$i]		= isset($approvalAmount[$i]) ? $approvalAmount[$i] : 0;
			$approvalNo[$i]			= isset($approvalNo[$i]) ? $approvalNo[$i] : '0';
			$reApprovalAmount[$i] 	= isset($reApprovalAmount[$i]) ? $reApprovalAmount[$i] : 0;
			$reApprovalNo[$i]       = isset($reApprovalNo[$i]) ? $reApprovalNo[$i] : '0';
			 
	
	
		
	
			
			$refundQueryValues[] = "( $refundID, $i, $deductCommision[$i], $deductCardFee[$i], '$shopId[$i]', '$cardBankType[$i]', '$cardAccountNo[$i]', '$expireDate[$i]', '$installment[$i]', '$approvalAmount[$i]', '$approvalNo[$i]', '$etc[$i]', $reApprovalAmount[$i], $reApprovalNo[$i],'$cashAmount[$i]','$cardAmount[$i]', now(), '$adminID', now(), '$adminID')";
		}

		$insertRefundLineQuery = $insertRefundLineQuery. implode(',',$refundQueryValues);
		//echo "ddd".$insertRefundLineQuery."<br/>";
		$insertRefundLineQueryResult = mysql_query($insertRefundLineQuery) or die("Query Error");
		
		//echo $insertRefundLineQueryResult;
		
		$alert = '저장이 완료 됐습니다.';
		echo "<script>alert('$alert');
		  history.go(-2);</script>";

	}else if ($type == 'modify'){
	    $alert = '수정이 완료 됐습니다.';

	    
	    $deductCommision = $deductCommision[0];
	    $deductCardFee = $deductCardFee[0];
	    $shopId = $shopId[0];
	    $cardBankType=$cardBankType[0];
	    $cardAccountNo = $cardAccountNo[0];
	    $expireDate = $expireDate[0];
	    $installment=$installment[0];
	    $approvalAmount=$approvalAmount[0];
	    $approvalNo=$approvalNo[0];
	    $etc=$etc[0];
	    $cashAmount=$cashAmount[0];
	    $cardAmount=$cardAmount[0];
	    
	    $postDate = substr($postDate,0,4).'/'.substr($postDate,4,2).'/'.substr($postDate,6,8);
	    $orderDate = substr($orderDate,0,4).'/'.substr($orderDate,4,2).'/'.substr($orderDate,6,8);
	    $comissionPeriod = substr($comissionPeriod,0,2).'/'.substr($comissionPeriod,2,4);
	    $cardAccountNo = encrypt($key, $iv, $cardAccountNo);
	    //콤마제거
	    $orderTotal  = preg_replace("/[^\d]/","",$orderTotal);
	    $approvalAmount = preg_replace("/[^\d]/","",$approvalAmount);
	    $cashAmount = preg_replace("/[^\d]/","",$cashAmount);
	    $cardAmount = preg_replace("/[^\d]/","",$cardAmount);
	    //$rtnPvVol 	= preg_replace("/[^\d]/","",$rtnPvVol);
	    //`$rtnPvAdj =  preg_replace("/[^\d]/","",$rtnPvAdj);
	    //$rtnAmtAdj 	= preg_replace("/[^\d]/","",$rtnAmtAdj);
	    //$rtnAmtVol =  preg_replace("/[^\d]/","",$rtnAmtVol);
	    
	    
	    $expireDate = substr($expireDate,0,2).'/'.substr($expireDate,2,4);
	        
	
	    
	    $udateRefund ="update tb_refund_header rh join tb_refund_line rl on rh.refundID = rl.refundID
	    set PostDate = '$postDate',
    	    memberID='$baId',
    	    memberName ='$baName',
    	    CommissionPeriod='$comissionPeriod',
    	    OrderNo='$orderNo',
    	    ReturnNo='$returnNo',
    	    OrderDate='$orderDate',
    	    Ordertotal='$orderTotal',
    	    ReturnPV='$rtnPvVol',
    	    ReturnPVAdjustment='$rtnPvAdj',
    	    ReturnAmount='$rtnAmtVol',
    	    ReturnAmountAdjustment='$rtnAmtAdj',
    	    Commission='$deductCommision',
    	    CardFee='$deductCardFee',
    	    StoreID='$shopId',
    	    StoreName='$cardBankType',
    	    CardNumber='$cardAccountNo',
    	    ExpireDate='$expireDate',
    	    Installment='$installment',
    	    ApprovalAmount='$approvalAmount',
    	    ApprovalNumber='$approvalNo',
    	    Comments='$etc',
    	    ReApprovalAmount='$reApprovalAmount',
    	    ReApprovalNumber='$reApprovalNo',
    	    LastModifiedDate= now(),
    	    LastModifier='$adminID',
    	    cashAmount='$cashAmount',
            cardAmount='$cardAmount'
        where rl.RefundNo ='$RefundNo'
	      and memberID='$baId'";
	    
	 
	    mysql_query($udateRefund) or die("Query Error");
	    
	    echo "<script>alert('$alert');
		  history.go(-2);</script>";
	}
?>
