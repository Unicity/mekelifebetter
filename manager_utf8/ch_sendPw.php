<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?
    include "../dbconn_utf8.inc";
    $baId = $_POST['baId'];
    $phNum = $_POST['phNum'];
    $textNum = $_POST['textNum'];

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
                        array("!") 
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

    $pw_length  = strlen($pw_data);
    if($pw_length > 8){
        $pw_data=substr( $pw_data, 0, 8 );
    }


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

//문자 전송
 $callbackNum = '15778269';

$contents = "\n▶ 임시비밀번호 로그인 방법 안내
\n https://ushop-kr.unicity.com/ 접속 →  회원번호입력 → 받으신 임시비밀번호 입력 후 로그인 \n\n임시 비밀 번호는 \n\n".$pw_data."\n\n입니다.\n로그인 후 반드시 비밀번호를 변경해 주시기 바랍니다. \n
감사합니다.";
$htel= str_quote_smart(trim($textNum ));
$contents= str_quote_smart(trim($contents));

$query = "insert into NEO_MSG (phone, callback, reqdate, msg, subject, type) values ('$htel', '$callbackNum', sysdate(), '$contents','유니시티코리아', 2)";

mysql_query($query);
$arr = array("message" => "발송이 완료 되었습니다.");

//echo print_r($arr)."<br/>";
$alert = $arr['message']; 

echo "<script>alert('$alert');history.back();</script>";

//$json_val =  json_encode($arr);
//echo $json_val;
//echo $callback."(".$json_val.")";
?>
<script>

</script>    
