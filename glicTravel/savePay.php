<?php


header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Mon,26 Jul 1997 05:00:00 GMT");
?>

<?php

include "includes/config/config.php";
include "./includes/AES.php";

$custHref = $_POST['custHref'];
$orderNum = $_POST['orderNum'];
$taxAmount = $_POST['taxAmount'];
$taxtAxableTotal = $_POST['taxtAxableTotal'];
$totAmount = $_POST['totAmount'];
$expireDate = $_POST['expireDate'];
$birthday = $_POST['birthday'];
$password =  $_POST['password']; 
$installment = $_POST['installment'];
$cardNumber = $_POST['cardNumber'];
$shipToName = $_POST['shipToName'];
$token = $_POST['token'];
$paymentCard = $_POST['paymentCard'];
$memberNo = $_POST['member_no'];
$valNo = $_POST['valNo'];


$cardNumber = decrypt($key, $iv, $cardNumber);

$password  = decrypt($key, $iv, $password );
$CardNumber = substr($cardNumber,0,6)." **"." **** ".substr($cardNumber,12,4);







    function get_host()
	{
		$IS_TEST = true;
		
		if ($IS_TEST) return "http://210.181.28.134";
		
		return "https://kspay.ksnet.to";
	}
	function get_comid()
	{
		$send_url = "https://kspay.ksnet.to/store/PAY_PROXY/api001/txseq.jsp";
		$recv_msg = call_web_api($send_url ,"");

		if (false === strpos($recv_msg ,"\"wtr\":"))
		{
			return "";
		}

		$recv_obj = json_decode($recv_msg ,true);
		return $recv_obj["wtr"];
	}
	function get_etoken($mhkey, $curr_date_14, $sign_msg)
	{
		return ($curr_date_14 . ":" . strtoupper(hash('sha256' ,$curr_date_14 . ":" . $mhkey . $sign_msg )));
	}

	function pkcs5_pad($text, $blocksize = 16)
	{
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text.str_repeat(chr($pad), $pad);
    }

	function encrypt_msg($mekey ,$msg)
	{
		$kbytes = pack('H*' ,$mekey);
		$iv     = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";//pack('H*' ,"00000000000000000000000000000000");

		$pmsg = date("YmdHis") . ":" . $msg;
		$plen = 16 - (strlen($pmsg) % 16);

		$pmsg = $pmsg . str_repeat(chr($plen), $plen);

		return strtoupper(bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $kbytes ,$pmsg ,MCRYPT_MODE_CBC ,$iv)));
	}

	function decrypt_msg($mekey ,$msg)
	{
		$kbytes = pack('H*' ,$mekey);
		$iv     = "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";//pack('H*' ,"00000000000000000000000000000000");

		$dmsg = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $msg ,MCRYPT_MODE_CBC ,$iv);
		$dlen = strlen($dmsg);
		if (15 > $dlen || ':' != $dmsg[14]) {return "";}

		$plen = ord($dmsg[$dlen-1]);
		if (1 > $plen || 16 < $plen || $dlen < 14+$plen) {return "";}

		return substr($dmsg ,14 ,$dlen-14-$plen);
	}

	function call_web_api($jurl ,$post_msg)
	{
		$ch = curl_init();

		if (!empty($post_msg))
		{
			curl_setopt($ch ,CURLOPT_POST ,1);
			curl_setopt($ch ,CURLOPT_POSTFIELDS ,$post_msg);
		}
	
		if (substr($jurl,0,8) == "https://")
		{
			curl_setopt($ch ,CURLOPT_SSL_VERIFYPEER ,FALSE);
			curl_setopt($ch ,CURLOPT_SSL_VERIFYHOST ,0);
		}

		curl_setopt($ch ,CURLOPT_RETURNTRANSFER ,1);

		curl_setopt($ch ,CURLOPT_URL ,$jurl);

		curl_setopt($ch ,CURLOPT_HEADER ,1);
		curl_setopt($ch ,CURLOPT_HTTPHEADER ,array(
				"Content-Type: application/x-www-form-urlencoded"
		));


		$rdata = curl_exec($ch);

		$header_size = curl_getinfo($ch ,CURLINFO_HEADER_SIZE);
		$rbody = substr($rdata ,$header_size);

		curl_close($ch);
		
		return ($rbody);
	}
/**test 환경 */
	//$MID                  = "2999199999";
	//$MSALT                = "MA01";
	//$HKEY                 = "A4E76BDA337DCCA95298FB495A84D369";
	//$EKEY                 = "68704BA2FF30FFE903774860D8FCCFF2";

    
	$MID                  = "2001104957";
	$MSALT                = "MA01";
	$HKEY                 = "3F75BAFE7A742952997D3F5244B3B92F";
	$EKEY                 = "F8ECDEF83483FF7A3ED45177BD55916C";
    
	
	$curr_date_14         = date("YmdHis");

	$_CHAR_SET            = isset($_REQUEST["sndCharset"           ]) ? trim($_REQUEST["sndCharset"           ]) : "utf-8";
	$_STORE_ID            = isset($_REQUEST["sndStoreid"           ]) ? trim($_REQUEST["sndStoreid"           ]) : $MID   ;
	$_MSALT               = isset($_REQUEST["sndMsalt"             ]) ? trim($_REQUEST["sndMsalt"             ]) : $MSALT ;
	$_AMOUNT              = isset($_REQUEST["sndAmount"            ]) ? trim($_REQUEST["sndAmount"            ]) : $totAmount;
	$_CURRENCY_TYPE       = isset($_REQUEST["sndCurrencyType"      ]) ? trim($_REQUEST["sndCurrencyType"      ]) : "0";
	
	$_ORDER_NAME          = isset($_REQUEST["sndOrderName"         ]) ? trim($_REQUEST["sndOrderName"         ]) : $shipToName;
	$_ORDER_NUMBER        = isset($_REQUEST["sndOrdernumber"       ]) ? trim($_REQUEST["sndOrdernumber"       ]) :  $orderNum;
	$_GOOD_NAME           = isset($_REQUEST["sndGoodName"          ]) ? trim($_REQUEST["sndGoodName"         ])  : "GLIC";
	$_EDATA               = isset($_REQUEST["sndEdata"             ]) ? trim($_REQUEST["sndEdata"             ]) : "";
	$_COMM_CON_ID         = get_comid();
	$_ACTION_TYPE         = "0";// 최초전송시 0, 결과조회시 1
	$_GOOD_HALBU           =$installment;
	
	$_e_card_no           = isset($_REQUEST["e_card_no"            ]) ? trim($_REQUEST["e_card_no"            ]) : $cardNumber ;
	$_e_expiry_yymm       = isset($_REQUEST["e_expiry_yymm"        ]) ? trim($_REQUEST["e_expiry_yymm"        ]) : $expireDate                              ;
	$_e_owner_id          = isset($_REQUEST["e_owner_id"           ]) ? trim($_REQUEST["e_owner_id"           ]) : $birthday                            ;
	$_e_pin_2             = isset($_REQUEST["e_pin_2"              ]) ? trim($_REQUEST["e_pin_2"              ]) : $password                              ;
	

	$_ETOKEN = get_etoken($HKEY, $curr_date_14, "");

	$send_url = "";
	$send_msg = "";
	$recv_msg = "";

	$r_authyn      = "";
	$r_trddt       = "";
	$r_trdtm       = "";
	$r_msg1        = "";
	$r_msg2        = "";

	if ("POST" == $_SERVER["REQUEST_METHOD"])
	{
		$p_data =   "e_card_no"     . '=' . $_e_card_no     . '&' .
					"e_expiry_yymm" . '=' . $_e_expiry_yymm . '&' .
					"e_owner_id" . '=' . $_e_owner_id . '&' .
					"e_pin_2"   . '=' . $_e_pin_2    ;


		$_EDATA           = encrypt_msg($EKEY ,$p_data);

		$send_msg =   "sndCharset"     . '=' . urlencode($_CHAR_SET     )    . '&' .
					  "sndStoreid"     . '=' . urlencode($_STORE_ID     )    . '&' .
					  "sndMsalt"       . '=' . urlencode($_MSALT        )    . '&' .
					  "sndEtoken"      . '=' . urlencode($_ETOKEN       )    . '&' .
					  "sndOrdernumber" . '=' . urlencode($_ORDER_NUMBER )    . '&' .
					  "sndEdata"       . '=' . urlencode($_EDATA        )    . '&' .
					  "sndCommConId"   . '=' . urlencode($_COMM_CON_ID  )    . '&' .
						"sndActionType"  . '=' . urlencode($_ACTION_TYPE  )    . '&' .
					  "sndAmount"      . '=' . urlencode($_AMOUNT       )    . '&' .
					  "sndOrderName"   . '=' . urlencode($_ORDER_NAME   )    . '&' .
					  "sndGoodName"    . '=' . urlencode($_GOOD_NAME    )    . '&' .
					  "sndHabu"      . '=' . urlencode($_GOOD_HALBU       )    . '&' .
					  "sndCurrencyType". '=' . urlencode($_CURRENCY_TYPE       );

		//$send_url = get_host() . "/store/PAY_PROXY/api001/cardauth.jsp";
		$send_url = "https://kspay.ksnet.to/store/PAY_PROXY/api001/cardauth.jsp";

		$recv_msg = call_web_api($send_url ,$send_msg);
		
		$recv_obj = json_decode($recv_msg ,true);
		
		$r_authyn    =  $recv_obj["authyn"   ];
		$r_trno      =  $recv_obj["trno"     ];
		$r_trddt     =  $recv_obj["trddt"    ];
		$r_trdtm     =  $recv_obj["trdtm"    ];
		$r_amt       =  $recv_obj["amt"      ];
		$r_authno    =  $recv_obj["authno"   ];
		$r_msg1      =  $recv_obj["msg1"     ];
		$r_msg2      =  $recv_obj["msg2"     ];
		$r_ordno     =  $recv_obj["ordno"    ];
		$r_isscd     =  $recv_obj["isscd"    ];
		$r_aqucd     =  $recv_obj["aqucd"    ];

	}

if($r_authyn=='O'){

/* 주문 업데이트 1->2 */

    $sendUrl = $custHref."/transactions";
    $sendData = array();
    $sendData['amount'] =$r_amt    ;
    $sendData['type'] = "record";
    $sendData['method'] = "CreditCard";
    $sendData['authorization'] =$r_authno;
    $sendData['methodDetails'] = array(
        "bankName" => $paymentCard ,
        "creditCardNumber" => $CardNumber,
        "payer" => $shipToName ,
        "creditCardExpires" => '20'.$expireDate,
        "creditCardSecurityCode" => '**'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $sendUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendData));
    $response = curl_exec($ch);
    $json_result = json_decode($response, true);


}
   

    $resultLog=json_encode(array($json_result));
	$sendDataLog=json_encode(array($sendData));

	//결제 로그 저장
	$insertQuery="insert into glic_Travel_log (
		member_no, 
		send_url,
		result_log,
		pay_log,
		send_log,
		red_date
		) 
		value(
		'".$memberNo."',
		'".$sendUrl."',
		'".$resultLog."',
		'".$recv_msg."',
		'".$sendDataLog."',
		'".date("Y-m-d H:i:s")."'
		)";
		mysql_query($insertQuery) or die("insertLogQuery Error");

	$updateQuery ="update tb_glicTravel set paid='Y' where No='$valNo' and member_no='$memberNo' and (paid !='Y' OR paid is null)";

	mysql_query($updateQuery) or die("updateQuery Error");

	
	$countChekc  = "select count(*) cnt from tb_glicTravel where (paid !='Y' OR paid is null)and(flagUD !='D' OR flagUD is null)";
	$resultCount = mysql_query($countChekc);
	$rowCk = mysql_fetch_array($resultCount);
	$cnt = $rowCk[cnt];

$okVal="OK";
	echo(json_encode(array("count"=>$cnt,"okVal"=>$okVal,"update"=>$updateQuery)));




 




/*
    echo(json_encode(array("authyn"=> $r_authyn,
                           "trno"=> $r_trno,
                           "trddt"=> $r_trddt,
                           "trdtm"=> $r_trdtm,
                           "amt"=> $r_amt,
                           "authno"=> $r_authno,
                           "msg1"=> $r_msg1,
                           "msg2"=> $r_msg2 ,
                           "ordno"=> $r_ordno , 
                           "isscd"=>$r_isscd ,
                           "aqucd"=> $r_aqucd ,
                           "href"=>$custHref,
                           "name"=>$shipToName,
                           "token"=>$token

                        )));
*/
      
?>

