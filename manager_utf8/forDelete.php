<?php session_start();?>
<?
   include "./admin_session_check.inc";
   include "./inc/global_init.inc";
   include "../dbconn_utf8.inc";

    $query = "select * from for_delete_autoship";
    $result = mysql_query($query);
    
    $TotalArticle = count($query);

     // 토큰 생성

     $id = 'kr_ar';
     $pw = 'Nrwk%vOSo&ht&fJ!sxvVyjIwy8t4';
 
     $ch = curl_init();
     $url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
     $sendData = array();
     $sendData["source"] = array("medium" => "Template");
     $sendData["type"] = "base64";
     $sendData["value"] = base64_encode("{$id}:{$pw}");
     $sendData["namespace"] = "https://hydra.unicity.net/v5a/employees";
     
     $ch = curl_init();  
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

     

   if ($TotalArticle) {
    echo "2";
       while($obj = mysql_fetch_object($result)) {
        echo "!1";
           $hrefUrl=$obj->autoorder_href;
echo "hruf".$hrefUrl;
           $header = array('Content-Type: application/json', 'Authorization: Bearer '.$token);

           curl_setopt($ch, CURLOPT_URL, $hrefUrl);
           curl_setopt($ch, CURLOPT_HEADER, false);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
           curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

           $response = curl_exec($ch);
           $json_result = json_decode($response, true);

           //echo "결과:".print_r($json_result);
           
           $updateQuery = "update for_delete_autoship set response_data = '$hrefUrl'
                                               where autoorder_href = '$hrefUrl'
                                               and member_number = $obj->member_number";
                                                   

           //echo "====>".$updateQuery;                                    
           mysql_query($updateQuery) or die("Query Error");                                    


       }
     
   }
?>