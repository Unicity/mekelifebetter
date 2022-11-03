<?

    $baId = $_POST['baId'];
    $phNum = $_POST['phNum'];

    $urlData = 'https://hydra.unicity.net/v5a/customers?unicity='.$baId.'&expand=customer';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $result_BA = json_decode($response, true);

    

    $userHref = $result_BA["items"][0]["href"];
   

   
    $href ="https://hydra.unicity.net/v5a/customers?id.unicity=".$baId;

    $sendData["customer"]=array('href' => $href );
    $sendData["mobilePhone"]=$phNum;
            
    
    $url = "https://hydra.unicity.net/v5a/passwordcreatetokens";
   
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
    
    $token  = $json_result["token"];

    function passwordGenerator( $length=12 ){

        $counter = ceil($length/4);
        // 0보다 작으면 안된다.
        $counter = $counter > 0 ? $counter : 1;            

        $charList = array( 
                        array("0", "1", "2", "3", "4", "5","6", "7", "8", "9", "0"),
                        array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"),
                        array("!", "@", "#", "%", "^", "&", "*") 
                    );
        $password = "";
        for($i = 0; $i < $counter; $i++)
        {
            $strArr = array();
            for($j = 0; $j < count($charList); $j++)
            {
                $list = $charList[$j];

                $char = $list[array_rand($list)];
                $pattern = '/^[a-z]$/';
                // a-z 일 경우에는 새로운 문자를 하나 선택 후 배열에 넣는다.
                if( preg_match($pattern, $char) ) array_push($strArr, strtoupper($list[array_rand($list)]));
                array_push($strArr, $char);
            } 
            // 배열의 순서를 바꿔준다.
            shuffle( $strArr );

            // password에 붙인다.
            for($j = 0; $j < count($strArr); $j++) $password .= $strArr[$j];
        }
        // 길이 조정
        return substr($password, 0, $length);
    }


    $urlForPw = $userHref."/password";

    $pw_data = passwordGenerator();

    $sendPwData["value"]=$pw_data;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlForPw);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendPwData));
    $response = curl_exec($ch);
    $result_pw = json_decode($response, true);

    echo print_r($result_pw);


 


?>
