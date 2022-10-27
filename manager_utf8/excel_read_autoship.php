<?php
session_start();
?>

<?
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    include "../AES.php";
    require_once "./PHPExcel/PHPExcel.php"; 
    $objPHPExcel = new PHPExcel();
    require_once "./PHPExcel/PHPExcel/IOFactory.php"; 

    $filename = $_FILES['excelFile']['tmp_name'];

// 오토쉽 직원 토큰 가져오기
    $id='kr_ar';
    $pw='Nrwk%vOSo&ht&fJ!sxvVyjIwy8t4';
  
    $ch = curl_init();
    $url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
    $sendData = array();
    $sendData["source"] = array("medium" => "Template");
    $sendData["type"] = "base64";
    $sendData["value"] = base64_encode("{$id}:{$pw}");
    $sendData["namespace"] = "https://hydra.unicity.net/v5a/employees";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendData));
    $response = curl_exec($ch);
    $json_result = json_decode($response, true);

    $token = $json_result['token']; 


      /**autoship DB연결 */
      $db_host = '54.180.152.178';
      $db_user = 'autoship';
      $db_passwd = 'inxide1!!';
      $db_name = 'autoship';
  
      $conn = mysql_connect($db_host,$db_user,$db_passwd) or die ("데이터베이스 연결에 실패!"); 
      mysql_select_db($db_name, $conn); // DB 선택 

      $times = mktime();  // 현재 서버의 시간을 timestamp 값으로 가져옴

      $nowDate = date("Y-m-d h:i:s", $times);  // 초 -> 년-월-일 시:분:초  변환

    try {
        $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
        
        $objReader->setReadDataOnly(true);
        
        $objExcel = $objReader->load($filename);
        $objExcel->setActiveSheetIndex(0);
        $objWorksheet = $objExcel->getActiveSheet();
        $rowIterator = $objWorksheet->getRowIterator();
        
        foreach ($rowIterator as $row) { // 모든 행에 대해서               
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
        }
        $maxRow = $objWorksheet->getHighestRow();
        
        for ($i = 2 ; $i <= $maxRow ; $i++) {
            $auto_no = $objWorksheet->getCell('A' . $i)->getValue(); 
            $order_no = $objWorksheet->getCell('B' . $i)->getValue(); 
            $amount = $objWorksheet->getCell('C' . $i)->getValue(); 
            $authorization = $objWorksheet->getCell('D' . $i)->getValue(); 
            $pay_method = $objWorksheet->getCell('E' . $i)->getValue(); 
            

            //echo $auto_no . "," .$order_no. "," . $member_name ."," . $amount ."," . $authorization ."," . $pay_method ."<br>";
                

            $queryList ="select * from smart_order_autoship_list where oal_autoship_ordernum =".$order_no." and oal_autoship_templatenum=".$auto_no;
    
                $resultList = mysql_query($queryList);       
                $list = mysql_fetch_array($resultList);
    
                $napyUniq = $list[npay_uniq];  // 승인번호
                $orderHref = $list[oal_autoship_order_href]; // 오더주소
                $orderTatal = $list[oal_price_total]; // 금액
                $payMethod = $list[oal_tax_TradeMethod]; //결제방법
                $cardNumber = $list[oal_autoship_cardno]; //카드번호
                $baName = $list[oal_oname]; //회원이름
                $expireDate = $list[oal_autoship_card_expire]; //유효기간
                $accountNumber = $list[oal_autoship_card_num_auto]; //계좌번호
                $oalOrdernum = $list[oal_ordernum]; //order key
                $autoNumber = $list[oal_autoship_templatenum]; //오토쉽 번호
                $baNo = $list[oal_mid]; //오토쉽 번호
                
            $queryCount = "select count(*) as cnt from smart_order_autoship_list where oal_ordernum='$oalOrdernum'";

                $resultList1 = mysql_query($queryCount);       
                $list1 = mysql_fetch_array($resultList1);
                $cnt = $list1[cnt]; //order  

                $sendUrl = $orderHref."/transactions";
                $sendData = array();
                $sendData['amount'] =$orderTatal    ;
                $sendData['type'] = "record";
                $sendData['method'] = $payMethod;
            if($payMethod=='BankWire'){
                $sendData['authorization'] =$autoNumber."A".$cnt;
                $sendData['methodDetails'] = array(
                    "bankName" => $payMethod ,
                    "creditCardNumber" => $accountNumber
                    
                );
            }else{
                $sendData['authorization'] =$napyUniq;
                $sendData['methodDetails'] = array(
                    "bankName" => $payMethod ,
                    "creditCardNumber" => $cardNumber,
                    "payer" => $baName ,
                    "creditCardExpires" => $expireDate,
                    "creditCardSecurityCode" => '**'
                );
            }
                
                

                echo print_r($sendData)."<br/>";
                   
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $sendUrl);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$token));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendData));
                $response = curl_exec($ch);
                $json_result = json_decode($response, true);


            echo "결과==>".print_r($json_result);

            
            //$result = print_r($json_result);

            if($payMethod=='BankWire'){
                $creditInfo = $accountNumber;
                $autoInfo = $autoNumber."A".$cnt;
            }else{
                $creditInfo = $cardNumber;
                $autoInfo = $napyUniq;
            }
          
            $saveQuery = "insert into autoship_update
                            (u_date,
                            autoship_num, 
                            member_no,
                            result,
                            amount,
                            member_name,
                            authorization,
                            paymethod,
                            bank_name,
                            credit_num,
                            payer,
                            expires_date,
                            token,
                            order_href) 
                            value('".$nowDate."',
                            '".$autoNumber."',
                            '".$baNo."',
                            '".print_r($json_result)."',
                            '".$orderTatal."',
                            '".$baName."',
                            '".$autoInfo."',
                            '".$payMethod."',
                            '".$payMethod."',
                            '".$creditInfo."',
                            '".$baName."',
                            '".$expireDate."',
                            '".$token."',
                            '".$orderHref."')";
            mysql_query($saveQuery) or die("autoship_update_error".mysql_error());
            echo "업로드 완료 ";
            
        }
    }catch (exception $e) {
        echo "엑셀 파일을 읽는 도중 오류가 발생 하였습니다.";
    }
?>
