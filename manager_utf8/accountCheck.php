<? 
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";
    global 	$user_device;
    $ref1 = $_REQUEST['ref1'];
	$newAccountNo = $ref1['newAccountNo'];
	$newAccountHolder = $ref1['newAccountHolder'];
    $newBankCodeVal = $ref1['newBankCodeVal'];
    $baId = $ref1['baId'];

    $ekey = "A91D2B6121AA07C748B9CA4323963E69";
    $msalt = "MA01";
    $kscode = "1372";
    
    $sendDate = date("Ymd");
    $sendTime = date("Hid");
    $companyCode = "UPCHE214";
    $companyBankCode = "026";

    $result = mysql_query("select count(*) as cnt from tb_log_v2_start where date = '".date("Y-m-d")."' and flag = 'Y'") or die(mysql_error());	
    $row = mysql_fetch_array($result);

	
    if($row['cnt'] < 1){
        $sendData = 'JSONData={
                "kscode":"'.$kscode.'","ekey":"'.$ekey.'","msalt":"'.$msalt.'",
                "reqdata":
                [
                    {
                        "date":"'.$sendDate.'",
                        "time":"'.$sendTime.'",
                        "seqNo":"'.$sendTime.'",
                        "compCode":"'.$companyCode.'",
                        "bankCode":"'.$companyBankCode.'"
                    }
                ]
            }';


        $api_url = 'https://cmsapi.ksnet.co.kr/ksnet/rfb/bankstart';
        $ch = curl_init($api_url); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $reponse = curl_exec($ch); 
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $resultJson = json_decode($reponse);

        //print_r($resultJson);

        $flag = ($resultJson->replyCode == '0000') ? 'Y' : 'N';

        //결과등록
        $sql = "insert into tb_log_v2_start (date, sendData, recieveData, flag, logdate) values ('".date("Y-m-d")."','".$sendData."','".$reponse."','".$flag."',now())";
        @mysql_query($sql);
    }

    //계좌인증
		$sendData = 'JSONData={
            "kscode":"'.$kscode.'","ekey":"'.$ekey.'","msalt":"'.$msalt.'",
            "reqdata":
            [
                {
                    "date":"'.$sendDate.'",
                    "time":"'.$sendTime.'",
                    "seqNo":"'.$sendTime.'",
                    "accountBankCode":"'.$newBankCodeVal .'",
                    "accountNo":"'.$newAccountNo.'",
                    "agencyYn":"N",						
                    "compCode":"'.$companyCode.'",
                    "bankCode":"'.$companyBankCode.'"
                }
            ]
        }';

        //계좌인증전송
        $qlog = "insert into tb_log_v2 (tmpId, gubun, check_kind, name, data1, sendData, flag, device, logdate) values 
        ( '".$baId."', '미지급 계좌인증API전송', 'bank', '".$newAccountHolder."', '".$sendTime."', '$sendData', 'N', '$user_device', now())";	
        @mysql_query($qlog);
        $log_id  = mysql_insert_id();

        $api_url = 'https://cmsapi.ksnet.co.kr/ksnet/rfb/account/accountname'; //운영
        //$api_url = 'https://cmsapitest.ksnet.co.kr/ksnet/rfb/account/accountname';  //개발

        $ch = curl_init($api_url); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $reponse = curl_exec($ch); 
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch); 		

        $resultJson = json_decode($reponse);

        $yn = ($resultJson->accountName != "" && $resultJson->replyCode = "0000") ? "Y" : "N";

        //계좌인증결과 업데이트
        $qlog = "update tb_log_v2 set 
                    gubun = '미지급 계좌인증API조회결과-".$yn."',
                    data2 = '".$resultJson->accountName."',
                    recieveData = '".$reponse."',
                    msg = '".$resultJson->replyCode."' ,
                    flag = '".$yn."'
                where uid = '".$log_id."'";
        mysql_query($qlog);
        
            
 
        $json_val =  json_encode($resultJson);
            

            
	    echo $callback."(". $json_val.")";
?>