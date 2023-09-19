<?
    $memberNo = $_POST['memberNo'];
    $hrefData = $_POST['href'];

        //$id = 'kr_ar';
        //$pw = 'Nrwk%vOSo&ht&fJ!sxvVyjIwy8t4';
        $id = 'minguk';
        $pw = 'Mg555555';
    
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

if($memberNo !=''){
        $customerUrl = "https://hydra.unicity.net/v5a/customers?unicity=".$memberNo."&expand=customer";
      
        $header = array('Content-Type: application/json', 'Authorization: Bearer '.$token);
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $customerUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);
        $json_result = json_decode($response, true);

        $right0 = $json_result['items'][0]['rights'];
        $href = $json_result['items'][0]['href'];
  
        $count = count($right0);

        for($i = 0 ; $i < $count ; $i++){	    
            if($json_result['items'][0]['rights'][$i]['type']=='Order'){
                $count = '1';
            }
        } 
        
        echo(json_encode(array("val"=> $count ,"href"=> $href)));

    }else if($hrefData !=''){
        $ch = curl_init();  
        $hrefUrl = $hrefData.'/rights';
        
        $header = array('Content-Type: application/json', 'Authorization: Bearer '.$token);
        $sendData["type"] = "Order";
        $sendData["holder"] = "Upline";
        curl_setopt($ch, CURLOPT_URL, $hrefUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendData));

        $response = curl_exec($ch);
        $json_result = json_decode($response,true);
        $resultValue = $json_result['holder'];
        echo(json_encode(array("hrefValue"=> $resultValue)));
    }

?>