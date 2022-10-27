<?php
    session_start();
?>

<?
    header('Content-Type: text/html; charset=utf-8');
    require_once "../../dbconn_utf8.inc"; 
    require_once "../admin_session_check.inc";
    include "../../AES.php";
    $s_adm_dept = str_quote_smart_session($s_adm_dept);

    require_once "../PHPExcel/PHPExcel.php"; 
    $objPHPExcel = new PHPExcel();
    require_once "../PHPExcel/PHPExcel/IOFactory.php"; 

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
            $s_date = $objWorksheet->getCell('A' . $i)->getValue();  // 커미션 날짜
            $member_no = $objWorksheet->getCell('B' . $i)->getValue(); // 회원번홉
            $member_name = $objWorksheet->getCell('C' . $i)->getValue(); //회원이름
            $birthDay = $objWorksheet->getCell('D' . $i)->getValue(); //생년월일
            $bankCode = $objWorksheet->getCell('E' . $i)->getValue(); //은행코드
            $accountNum = $objWorksheet->getCell('F' . $i)->getValue(); //계좌번호
            $amount = $objWorksheet->getCell('G' . $i)->getValue(); //금액
            $errorCode = $objWorksheet->getCell('H' . $i)->getValue(); //에러코드
            $accountNum	= encrypt($key, $iv, $accountNum);
            echo ">>>>".$s_date . ",
                     " . $member_no . ",
                     " . $member_name .",
                     " . $birthDay .",
                     " . $bankCode .",
                     " . $accountNum .",
                     " . $amount .",
                     " . $errorCode ."<br>";


            
            $saveQuery = "insert into unclaimedCommission_for_minguk 
                        (id,
                        CommissionDate, 
                        memberName,
                        dob,
                        BankCode,
                        AccountNo,
                        Amount,
                        errorCode) 
            value('".$member_no."',
                    '".$s_date."',
                    '".$member_name."',
                    '".$birthDay."',
                    '".$bankCode."',
                    '".$accountNum."',
                    '".$amount."',
                    '".$errorCode."')";

            mysql_query($saveQuery) or die("excel_upload_error".mysql_error());
            echo "업로드 완료 ";
    }
}catch (exception $e) {
        echo "엑셀 파일을 읽는 도중 오류가 발생 하였습니다.==>".$e;
    }
?>
