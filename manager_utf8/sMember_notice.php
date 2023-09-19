
<? 
    
    include "../dbconn_utf8.inc";
    

	$strSubject = '※우수사원※ 강은영의 S회원 관리';
	$strTo = 'EunYoung.Kang@unicity.com';
    //$strTo = 'MinGu.Kim@unicity.com';


	$query = "select count(*) from tb_smember where 1 = 1 and date_format(end_date,'%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];


    $query2 = "select * from tb_smember where date_format(end_date,'%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')";
    $result2 = mysql_query($query2);
	
?>

<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>S회원 관리</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
    </head>
    <body>

        <?php
            $msg = '<table border=\"1\">';
            $msg .='<tr>';
            $msg .='<th width="5%" style="text-align: center;">일시정지</th>';
            $msg .='<th width="5%" style="text-align: center;">회원번호</th>';
            $msg .='<th width="5%" style="text-align: center;">성명</th>';
            $msg .='<th width="5%" style="text-align: center;">회원십전환 알람일자</th>';
            
            $msg .='<th width="5%" style="text-align: center;">오토쉽</th>';
            $msg .='<th width="5%" style="text-align: center;">사유</th>';
          
            $msg .='</tr>';
            while($obj = mysql_fetch_object($result2)) {
                $msg .='<tr>';
                $msg .='<td style="width: 5%" align="center">'.$obj->start_date.'</td>';
                $msg .='<td style="width: 5%" align="center">'.$obj->member_no.'</td>';
                $msg .='<td style="width: 5%" align="center">'.$obj->member_name.'</td>';
                $msg .='<td style="width: 5%" align="center">'.$obj->end_date.'</td>';
                
                $msg .='<td style="width: 5%" align="center">'.$obj->autoshipYn.'</td>';
                $msg .='<td style="width: 5%" align="center">'.$obj->note.'</td>';
                $msg .='<tr>';
            }    

            $msg .= '</table>';
            
			$dataFeedPM = "message=".$msg."&subject=".$strSubject."&email=".$strTo;
			if ($TotalArticle) {		
				$chMail = curl_init();
				curl_setopt($chMail, CURLOPT_URL,"https://member-calls4.unicity.com/ALL/email/mailgun.php");
				curl_setopt($chMail, CURLOPT_POST, 1);
				curl_setopt($chMail, CURLOPT_POSTFIELDS,$dataFeedPM);
				curl_setopt($chMail, CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($chMail, CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($chMail, CURLOPT_RETURNTRANSFER, true);
				
				$server_output = curl_exec ($chMail);
				
				curl_close ($chMail);
			}
         
        ?>
    </body>

	<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</html>
