<?
    include "../dbconn_utf8.inc";

    $before25day = date('Y-m-d',strtotime($now."-25 day")); // 25일전 
    $before30day = date('Y-m-d',strtotime($now."+5 day")); // 30일전 

    $query = "select count(*) from tb_change_sponsor where reg_status='5' and date_format(entry_date, '%Y-%m-%d')='$before25day'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$TotalArticle = $row[0];
    
    $sendQuery = "select * from tb_change_sponsor where reg_status='5' and date_format(entry_date, '%Y-%m-%d')='$before25day'";
    $result2 = mysql_query($sendQuery);
    
    echo $sendQuery."<br>";

    $smsNumber = '1577-8269';

    while($obj = mysql_fetch_object($result2)) {
        $msg = $obj->member_name." 님 유니시티코리아 입니다.\n\n 귀하의 ‘후원자 변경’ 신청이 현재 후원자 ".$obj->sponsor_name." 님의 동의가 완료되지 않아 처리되지 못하고 있습니다.\n\n".$before30day."까지 후원자 ".$obj->sponsor_name." 님의 동의가 완료되지 않을 경우, 귀하의 신청 건은 처리되지 않음을 사전 안내 드립니다.\n\n후원자 동의 링크주소(후원자 본인이 접속해야 합니다.)\n https://www.makelifebetter.co.kr/sp/sp.php";
            echo $msg."<br/>";
        $query = "insert into NEO_MSG (phone, callback, reqdate, msg, subject, type) values ('$obj->phoneNum', '$smsNumber', sysdate(), '$msg','유티시티-후원자변경 미처리안내', 2)";
        mysql_query($query);

        $updateQuery="update tb_change_sponsor set fiveday_sms = now() where no ='$obj->no' and member_no = '$obj->member_no' and phoneNum = '$obj->phoneNum'";
        echo $updateQuery;
        mysql_query($updateQuery) or die("fiveday_sms update Query Error".mysql_error());

	    
    }

?>