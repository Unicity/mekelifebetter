<?php session_start();?>
<?
    include "./admin_session_check.inc";
    include "./inc/global_init.inc";
    include "../dbconn_utf8.inc";
    $s_adm_id = str_quote_smart_session($s_adm_id);
    
    $memberNo = $_POST['memberNo'];

    /**autoship DB연결 */
    $db_host = 'unicity-database.cluster-c4ao3svuphls.ap-northeast-2.rds.amazonaws.com';
    $db_user = 'autoship';
    $db_passwd = 'Bioslife1!';
    $db_name = 'autoship';

    $conn = mysql_connect($db_host,$db_user,$db_passwd) or die ("데이터베이스 연결에 실패!"); 
    mysql_select_db($db_name, $conn); // DB 선택 
  

    $count = "select count(*) from smart_order where 1=1 and o_mid =".$memberNo." and o_autoship_del_date = 0 and o_autoship_templatenum !=''";
    $result1 = mysql_query($count);
    $row = mysql_fetch_array($result1);
    $TotalArticle = $row[0];

    $query = "select * from smart_order where 1=1 and o_mid =".$memberNo." and o_autoship_del_date = 0 and o_autoship_templatenum !=''";
    $result = mysql_query($query);

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
        while($obj = mysql_fetch_object($result)) {
            $hrefUrl=$obj->o_autoship_autoorder;

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
            
            $updateQuery = "update smart_order set o_autoship_del_date = now(),
                                                   o_autoship_del_id = '$s_adm_id '
                                                where o_mid = '$memberNo'
                                                and o_autoship_autoorder = '$hrefUrl'
                                                and o_autoship_templatenum !=''";
                                                    

            //echo "====>".$updateQuery;                                    
            mysql_query($updateQuery) or die("Query Error");                                    


        }
        $resultVal='ok';
        echo(json_encode(array("resultVal"=>$resultVal)));
    }
    
    
    
    
  /*
    for($i = 0 ; $i < $TotalArticle ; $i++){	

        $query = "select * from smart_order where 1=1 and o_mid =".$memberNo." and o_autoship_del_date = 0 and o_autoship_templatenum !=''";
        $result = mysql_query($query);
        $list = mysql_fetch_array($result);

        $autoHref= $list[o_autoship_autoorder][$i];
        $orderHref = $list[$i][o_autoship_order_href];
  
        //echo(json_encode(array("autoshipHref"=>$autoHref,"orderHref"=>$orderHref)));
    }
    */


  
   

  

 
?>