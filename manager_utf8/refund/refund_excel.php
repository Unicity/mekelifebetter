<?
ini_set('memory_limit',-1);
ini_set('max_execution_time', 60);
?>
<?
	include "../admin_session_check.inc";
	//include "./inc/global_init.inc";
	include "../../dbconn_utf8.inc";
	include "../../AES.php";

	
	$str_title = iconv("UTF-8","EUC-KR","환불등록");
	
	$file_name=$str_title."-".date("Ymd").".xls";
	header( "Content-type: application/vnd.ms-excel" ); // 헤더를 출력하는 부분 (이 프로그램의 핵심)
	header( "Content-Disposition: attachment; filename=$file_name" );
	header( "Content-Description: orion70kr@gmail.com" );
	
	$s_status = str_quote_smart(trim($r_status));
	$qry_str = str_quote_smart(trim($qry_str));
	$con_order = str_quote_smart(trim($con_order));
	$idxfield = str_quote_smart(trim($idxfield));
	$con_sort = str_quote_smart(trim($con_sort));
	
	$DSCSelect = str_quote_smart(trim($DSCSelect));
	
	echo $DSCSelect;
	
	if ($con_order == "con_a") {
	    $order = "asc";
	} else {
	    $order = "desc";
	    $con_order = "con_d";
	}
	
	function right($value, $count){
	    $value = substr($value, (strlen($value) - $count), strlen($value));
	    return $value;
	}
	
	function left($string, $count){
	    return substr($string, 0, $count);
	}
	
	
	   $con_sort = "CommissionDate";
	
	if (empty($con_order)) {
	    $order = "desc";
	}
	
	if (empty($idxfield)) {
	    $idxfield = "0";
	}
	if ((empty($s_status)) || ($s_status == "A")) {
	    $s_status = "A";
	} else {
	    $que = $que." and RefundStatus = '$s_status' ";
	}
	
	switch ($idxfield) {
	    
	    case '6':
	        $que = $que." and rh.ReturnNo = $qry_str ";
	        break;
	    case '1':
	        $que = $que." and rh.OrderNo = $qry_str ";
	        break;
	    case '2':
	        $que = $que." and rh.memberID = $qry_str ";
	        break;
	    case '3':
	        $que = $que." and rh.memberName like '%$qry_str%' ";
	        break;
	    case '4':
	        $que = $que." and rh.PostDate between '$qry_str' and '$qry_str1' ";
	        break;
	    case '5':
	        $que = $que." and rh.CreatedDSC like '%$qry_str%' ";
	        break;
	        
	    default:
	        $que = $que." ";
	        break;
	}
	
	
	switch ($DSCSelect) {
	    
	    case '1':
	        $que = $que." and rh.CreatedDSC = '서울DSC' ";
	        break;
	    case '2':
	        $que = $que." and rh.CreatedDSC = '인천DSC' ";
	        break;
	    case '3':
	        $que = $que." and rh.CreatedDSC = '안산DSC' ";
	        break;
	    case '4':
	        $que = $que." and rh.CreatedDSC = '대전DSC' ";
	        break;
	    case '5':
	        $que = $que." and rh.CreatedDSC = '광주DSC' ";
	        break;
	    case '6':
	        $que = $que." and rh.CreatedDSC = '원주DSC' ";
	        break;
	    case '7':
	        $que = $que." and rh.CreatedDSC = '대구DSC' ";
	        break;
	    case '8':
	        $que = $que." and rh.CreatedDSC = '부산DSC' ";
	        break;
	    case '9':
	        $que = $que." and rh.CreatedDSC = 'IT' ";
	        break;
	        
	    default:
	        $que = $que." ";
	        break;
	} 
	

		
	$query2 = "select * from tb_refund_header rh join tb_refund_line rl on rh.refundID = rl.refundID where 1 = 1".$que."order by rh.CreatedDate desc , refundNo asc"  ;	
	$result2 = mysql_query($query2);

?>
<style>
.xlGeneral {
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:windowtext;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:\@;
	text-align:Left;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;
}
.backGround{
    background-color: #D5D5D5;
}
</style>
	<table border="1">
		<tr align="center">
			<th class="backGround" width="5%" style="text-align: center;" rowspan="3">Postdate</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">BA#</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">Name</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">커미션기간</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">ORD#</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">RTN#</th> 
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">주문일</th>
		  	<th class="backGround" width="6%" style="text-align: center;" rowspan="3">주문금액</th>
			<th class="backGround" width="12%" style="text-align: center;" colspan="2" rowspan="2">RTN Total PV</th>
			<th class="backGround" width="12%" style="text-align: center;" colspan="2"rowspan="2">RTN Total Amount</th>
			<th class="backGround" width="54%" style="text-align: center;" colspan="7">Deduction</th>
			<th class="backGround" width="12%" style="text-align: center;" colspan="4">Payment</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">DSC</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="3">etc</th>
			<!-- 
			
		 
			<th width="12%" style="text-align: center;" rowspan="2" colspan="2">재승인</th>
		  	<th width="6%" style="text-align: center;" rowspan="3" >재승인일</th>
		  	-->
		 
		</tr>     
		<tr>
			
			<th class="backGround" width="6%" style="text-align: center;" rowspan="2">Commission</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="2">Card Fee</th>
			<th class="backGround" width="6%" style="text-align: center;" colspan="5">결제정보</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="2">승인금액</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="2">승인번호</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="2">Cash</th>
			<th class="backGround" width="6%" style="text-align: center;" rowspan="2">Card</th>
			
		</tr>
		<tr>
			<th class="backGround" width="6%" style="text-align: center;">Adjustment</th>
			<th class="backGround" width="6%" style="text-align: center;">Volume</th>
			<th class="backGround" width="6%" style="text-align: center;">Adjustment</th>
			<th class="backGround" width="6%" style="text-align: center;">Payment</th>
			<th class="backGround" width="6%" style="text-align: center;">상점ID</th>
			<th class="backGround" width="6%" style="text-align: center;">Card/Bank</th>
			<th class="backGround" width="6%" style="text-align: center;">Card/Account#</th>

			<th class="backGround" width="6%" style="text-align: center;">유효기간</th>
			<th class="backGround" width="6%" style="text-align: center;">할부</th>
			
		
		
			
		<!--
			<th width="6%" style="text-align: center;">재승인금액</th>
			<th width="6%" style="text-align: center;">승인번호</th>
	       -->
		</tr>
<?
    while($obj = mysql_fetch_object($result2)) {
    
        $post_s = date("Y/m/d", strtotime($obj->PostDate));
        $order_s = date("Y-m-d", strtotime($obj->OrderDate));
        $Last_s = date("Y-m-d", strtotime($obj->LastModifiedDate));
        $CardNumber = decrypt($key, $iv, $obj-> CardNumber);
        $CardNumber = substr($CardNumber,0,4)." ".substr($CardNumber,4,4)." ".substr($CardNumber,8,4)." ".substr($CardNumber,12,4);
        
        $OrderTotal= number_format($obj-> OrderTotal);
        
        $ReturnAmountAdjustment =number_format($obj-> ReturnAmountAdjustment);
        $ReturnAmount =number_format($obj-> ReturnAmount);
        $cashAmount =number_format($obj-> cashAmount);
        $cardAmount =number_format($obj-> cardAmount);
        $ApprovalAmount=number_format($obj-> ApprovalAmount);
        
        if($obj-> StoreName == 'new'){
            $StoreName = '신상점';
        }else if($obj-> StoreName == 'internet'){
            $StoreName = '인터넷';
        }else if ($obj-> StoreName == 'allat'){
            $StoreName = '올앳';
        }else{
            $StoreName = '무통장';
        }
        
        if($obj-> ReturnPVAdjustment == 0){
            $obj-> ReturnPVAdjustment = '-';
        }
        
        if($obj-> ReturnAmountAdjustment == 0){
            $obj-> ReturnAmountAdjustment = '-';
        }
        
        if($obj-> Commission == 0){
            $obj-> Commission = '-';
        }
        
        if($obj-> CardFee == 0){
            $obj-> CardFee = '-';
        }
        
        if($obj-> cashAmount == 0){
            $obj-> cashAmount = '-';
        }
        
        if($obj-> cardAmount == 0){
            $obj-> cardAmount = '-';
        }
        
        //$CommissionPeriod = substr($obj-> CommissionPeriod,0,2)."/".substr($obj-> CommissionPeriod,2,4);
        
        //$ExpireDate = substr($obj-> ExpireDate,0,2)."/".substr($obj-> ExpireDate,2,4);
?>	
		<tr>
 			
 			<td style="width: 5%" align="center"><?echo $post_s?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> memberID?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> memberName?></td>
 		
 			
 		<?php if($obj->RefundLine > 0){?>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 			<td style="width: 5%;background-color: #D5D5D5" align="center"></td>
 		<?php }else{ ?>
 			<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> CommissionPeriod ?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> OrderNo?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnNo?></td>
 			<td style="width: 5%" align="center"><?echo $order_s?></td>
 			
 			<td style="width: 5%" align="center"><?echo $obj-> OrderTotal?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnPVAdjustment?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnPV?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnAmountAdjustment?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReturnAmount?></td>
 		<?php }?>
 			
 			<td style="width: 5%; " align="center"><?echo $obj-> Commission?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> CardFee?></td>
 			<td style="width: 5%" align="center"><?echo $StoreName?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> StoreID?></td>
 		
 			<td class="xlGeneral" style="width: 5%" align="center"><?echo $CardNumber?></td>
 			<td class="xlGeneral" style="width: 5%" align="center"><?echo $obj-> ExpireDate?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> Installment?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ApprovalAmount?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ApprovalNumber?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> cashAmount?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> cardAmount?></td>
 			
 			<td style="width: 5%" align="center"><?echo $obj-> CreatedDSC?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> Comments?>
 			
 			<!--</td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReApprovalAmount?></td>
 			<td style="width: 5%" align="center"><?echo $obj-> ReApprovalNumber?></td>
 			-->
 		<!-- <td style="width: 5%" align="center"><?echo $Last_s?></td> -->
 		
 		</tr>
<?
	}
?>

</table>



<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
