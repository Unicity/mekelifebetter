<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_input.php
	// 	Description : 관리자 추가 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

  
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";
	



	
	$ref1 = $_REQUEST['ref1'];
	$senderName = $ref1['senderName'];
	$orderReal = $ref1['orderReal'];
	$bankID = $ref1['bankID'];
	$accountID = $ref1['accountID'];
	$htel = $ref1['sendNum'];
	$total = $ref1['total'];
	$callback = $_REQUEST['callback'];
	$s_adm_id = 'alsrnkmg';
	
	logging($s_adm_id,'open sms each input page(fromFoSend.php)');
	
	$callbackNum			= '15778269';
	$contents			= '주문번호 : '.$orderReal.' 입금자 : '.$senderName.' '.$bankID.' '.$accountID." 금액 : ".$total;
	
	$mode					= str_quote_smart(trim($mode));
	$htel					= str_quote_smart(trim($htel));
	//$callback			= str_quote_smart(trim($callback));
	$contents			= str_quote_smart(trim($contents));
	
	
	$mode = 'SEND';
	
	if ($mode == "SEND") {

	    $query = "Insert into sms_msg (compkey, id, phone, callback, status, wrtdate, reqdate, msg, etc1, etc2) values
									('0','FoShopping','$htel', '$callbackNum', '0', sysdate(), sysdate(), '$contents','FO','Web') ";
		mysql_query($query);

		logging($s_adm_id,'send sms ('.substr($htel,-4).')');

	}
	
	$arr = array("message" => "발송이 완료 되었습니다.");
	
	
	
	$json_val =  json_encode($arr);
	
	//echo "${param.callback}(".$json_val.");";
	
	echo $callback."(".$json_val.")";
?>

<?
	mysql_close($connect);
?>