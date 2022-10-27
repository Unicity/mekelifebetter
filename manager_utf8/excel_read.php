<?php
session_start();
?>

<?
  header('Content-Type: text/html; charset=utf-8');
require_once "../dbconn_utf8.inc"; 
require_once "./admin_session_check.inc";
$s_adm_dept = str_quote_smart_session($s_adm_dept);

require_once "./PHPExcel/PHPExcel.php"; 
$objPHPExcel = new PHPExcel();
require_once "./PHPExcel/PHPExcel/IOFactory.php"; 

$filename = $_FILES['excelFile']['tmp_name'];


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
        $s_date = $objWorksheet->getCell('A' . $i)->getValue(); 
        $member_no = $objWorksheet->getCell('B' . $i)->getValue(); 
        $member_name = $objWorksheet->getCell('C' . $i)->getValue(); 
        $order_no = $objWorksheet->getCell('D' . $i)->getValue(); 
        $back_no = $objWorksheet->getCell('E' . $i)->getValue(); 
        $amount = $objWorksheet->getCell('F' . $i)->getValue(); 
        $check_text = $objWorksheet->getCell('G' . $i)->getValue(); 
        $check_num = $objWorksheet->getCell('H' . $i)->getValue(); 
        $approval_num = $objWorksheet->getCell('I' . $i)->getValue(); 
        $cancel_no = $objWorksheet->getCell('J' . $i)->getValue(); 
        $check_result = $objWorksheet->getCell('K' . $i)->getValue(); 
        $cancel_reason = $objWorksheet->getCell('L' . $i)->getValue(); 
        
        //echo $s_date . ", " . $member_no . ", " . $member_name .", " . $order_no .", " . $amount .", " . $check_text .", " . $check_num .", " . $approval_num .", " . $check_result .", " . $cancel_reason . "<br>";
        
        $saveQuery = "insert into tb_cashReceipts (s_date, member_no, member_name, order_no,back_no,amount,check_text,check_num,approval_num,cancel_no, check_result, cancel_reason, center) 
                    value('".$s_date."','".$member_no."','".$member_name."','".$order_no."','".$back_no."','".$amount."','".$check_text."','".$check_num."','".$approval_num."','".$cancel_no."','".$check_result."','".$cancel_reason."','".$s_adm_dept."')";
       
                mysql_query($saveQuery) or die("excel_upload_error".mysql_error());
        
    }
}catch (exception $e) {
        echo "엑셀 파일을 읽는 도중 오류가 발생 하였습니다.";
    }
?>
