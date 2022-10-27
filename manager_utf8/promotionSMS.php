<?
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "./inc/common_function.php";
    
    
    $ref1 = $_REQUEST['ref1'];
    $orderNum = $ref1['orderNum'];
    $htel = $ref1['sendNum'];
    $orderName = $ref1['orderName'];
    $orderPrice = $ref1['orderPrice'];
    $bankInfo = $ref1['bankInfo'];
    $paymethod = $ref1['paymethod'];
    $callback = $_REQUEST['callback'];
    $s_adm_id = 'alsrnkmg';
    $date = date('Y-m-d');
    //logging($s_adm_id,'open sms each input page(fromFoSend.php)');


    $callbackNum			= '15778269';
    if($paymethod=='무통장입금'){
        $contents			= "\n ◈ [유니시티코리아] 기획세트 무통장 입금 안내\n\n 예금주 : 유니시티 코리아\n주문번호 : ".$orderNum."\n은행 계좌번호 : ".$bankInfo."\n금액:".$orderPrice."원\n\n*준비된 재고(수량) 소진 시 주문은 자동 취소되므로 빠른 입금 처리 부탁드립니다\n*입력한 입금 성명 및 금액이 다를 경우 주문 불가합니다.\n\n감사합니다.";
    }else{
        $contents			= "\n ◈ [유니시티코리아] 기획세트 주문 완료 안내\n\n주문번호(이름) : ".$orderNum."(".$orderName.")\n 주문일자:".$date."\n 결제금액 : ".$orderPrice."원\n\n 감사합니다.";
    }
    
    //$mode					= str_quote_smart(trim($mode));
    $htel					= str_quote_smart(trim($htel));
    //$callback			= str_quote_smart(trim($callback));
    $contents			= str_quote_smart(trim($contents));
    
    
    $mode = 'SEND';
    
    if ($mode == "SEND") {
        $query = "insert into NEO_MSG (phone, callback, reqdate, msg, subject, type) values ('$htel', '$callbackNum', sysdate(), '$contents','유니시티코리아', 2)";
    
        //$query = "Insert into sms_msg (compkey, id, phone, callback, status, wrtdate, reqdate, msg, etc1, etc2) values('0','sponsor','$htel', '$callbackNum', '0', sysdate(), sysdate(), '$contents','sponsor','Web') ";
        mysql_query($query);
        
        //logging($s_adm_id,'send sms ('.substr($htel,-4).')');
        
    }
    
    $arr = array("message" => "발송이 완료 되었습니다.");
    
    
    
    $json_val =  json_encode($arr);
    
    //echo "${param.callback}(".$json_val.");";
    
    echo $callback."(".$json_val.")";
?>

<?
	mysql_close($connect);
?>