<?php
error_reporting(1);

ini_set("display_errors", 1);
?>
<?
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

	$MID                  = "2999199999";
	$MSALT                = "MA01";
	$HKEY                 = "A4E76BDA337DCCA95298FB495A84D369";
	$EKEY                 = "68704BA2FF30FFE903774860D8FCCFF2";
	
	$curr_date_14         = date("YmdHis");

	$_CHAR_SET            = isset($_REQUEST["sndCharset"           ]) ? trim($_REQUEST["sndCharset"           ]) : "utf-8";
	$_STORE_ID            = isset($_REQUEST["sndStoreid"           ]) ? trim($_REQUEST["sndStoreid"           ]) : $MID   ;
	$_MSALT               = isset($_REQUEST["sndMsalt"             ]) ? trim($_REQUEST["sndMsalt"             ]) : $MSALT ;
	$_AMOUNT              = isset($_REQUEST["sndAmount"            ]) ? trim($_REQUEST["sndAmount"            ]) : "1004";
	$_CURRENCY_TYPE       = isset($_REQUEST["sndCurrencyType"      ]) ? trim($_REQUEST["sndCurrencyType"      ]) : "0";
	
	$_ORDER_NAME          = isset($_REQUEST["sndOrderName"         ]) ? trim($_REQUEST["sndOrderName"         ]) : "홍길동";
	$_ORDER_NUMBER        = isset($_REQUEST["sndOrdernumber"       ]) ? trim($_REQUEST["sndOrdernumber"       ]) : "KS"     . $curr_date_14            ;
	$_GOOD_NAME           = isset($_REQUEST["sndGoodName"          ]) ? trim($_REQUEST["sndGoodName"         ])  : "당근12kg";
	$_EDATA               = isset($_REQUEST["sndEdata"             ]) ? trim($_REQUEST["sndEdata"             ]) : "";
	$_COMM_CON_ID         = get_comid();
	$_ACTION_TYPE         = "0";// 최초전송시 0, 결과조회시 1
	
	$_e_card_no           = isset($_REQUEST["e_card_no"            ]) ? trim($_REQUEST["e_card_no"            ]) : "948250" . substr($curr_date_14 ,4) ;
	$_e_expiry_yymm       = isset($_REQUEST["e_expiry_yymm"        ]) ? trim($_REQUEST["e_expiry_yymm"        ]) : "2402"                              ;
	$_e_owner_id          = isset($_REQUEST["e_owner_id"           ]) ? trim($_REQUEST["e_owner_id"           ]) : "620101"                            ;
	$_e_pin_2             = isset($_REQUEST["e_pin_2"              ]) ? trim($_REQUEST["e_pin_2"              ]) : "11"                              ;
	

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
					  "sndCurrencyType". '=' . urlencode($_CURRENCY_TYPE       );

		$send_url = get_host() . "/store/PAY_PROXY/api001/cardauth.jsp";
		//$send_url = "https://kspay.ksnet.to/store/PAY_PROXY/api001/cardauth.jsp";

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
?>

<html>
<head>
	<meta HTTP-EQUIV="Content-Type" Content="text-html; charset=utf-8">
	<title>PG 구인증 승인 API 테스트</title>
	<style type="text/css">
		<!--
			table {
				font-size:13; word-break:break-all
			}
		-->
	</style>
</head>
<body>
	<p align=center><u><b>PG 구인증 승인 API 테스트</b></u></p>
<form name="mfrm" method="post" action="">
<script>


</script>
<table  border="1" cellspacing="0" width=100% cellpadding=2 bordercolordark="#ffffff" bordercolorlight="#aaaaaa">

	<tr>
		<td colspan=2>PG 구인증 승인 <input type=button name="sbtn" onclick="javascript:document.forms[0].submit();" value="전송"/></td>
	</tr>

	<tr>
		<td colspan=2>요청정보<hr/></td>
	</tr>
	<tr><td align="left" bgcolor="#f1f1f1">요청문자셋           </td><td><input type='text' name="sndCharset"        value='<? echo($_CHAR_SET       ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">상점아이디           </td><td><input type='text' name="sndStoreid"        value='<? echo($_STORE_ID       ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">MSALT               </td><td><input type='text' name="sndMsalt"          value='<? echo($_MSALT          ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">ETOKEN              </td><td><input type='text' name="sndEtoken"         value='<? echo($_ETOKEN         ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">주문자명             </td><td><input type='text' name="sndOrderName"      value='<? echo($_ORDER_NAME     ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">주문번호             </td><td><input type='text' name="sndOrdernumber"    value='<? echo($_ORDER_NUMBER   ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">상품명               </td><td><input type='text' name="sndGoodName"       value='<? echo($_GOOD_NAME      ) ?>' width=100 ></td></tr>	
	<tr><td align="left" bgcolor="#f1f1f1">결제금액             </td><td><input type='text' name="sndAmount"         value='<? echo($_AMOUNT         ) ?>' width=100 ></td></tr>	
	<tr><td align="left" bgcolor="#f1f1f1">통화구분             </td><td><input type='text' name="sndCurrencyType"   value='<? echo($_CURRENCY_TYPE  ) ?>' width=100 ></td></tr>	
	<tr><td align="left" bgcolor="#f1f1f1">통신용키             </td><td><input type='text' name="sndCommConId"      value='<? echo($_COMM_CON_ID   ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">카드번호             </td><td><input type='text' name="e_card_no"         value='<? echo($_e_card_no      ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">유효기간(YYMM)       </td><td><input type='text' name="e_expiry_yymm"     value='<? echo($_e_expiry_yymm  ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">생년월일             </td><td><input type='text' name="e_owner_id"        value='<? echo($_e_owner_id  ) ?>' width=100 ></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">비번앞2자리           </td><td><input type='text' name="e_pin_2"           value='<? echo($_e_pin_2  ) ?>' width=100 ></td></tr>
	
	<tr>
		<td colspan=2>응답정보<hr/></td>
	</tr>	
	<tr><td align="left" bgcolor="#f1f1f1">성공여부(O/X)        </td><td><? echo($r_authyn      ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">거래번호             </td><td><? echo($r_trno        ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">처리일자             </td><td><? echo($r_trddt       ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">처리시간             </td><td><? echo($r_trdtm       ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">승인금액             </td><td><? echo($r_amt         ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">승인번호             </td><td><? echo($r_authno      ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">응답메세지1          </td><td><? echo($r_msg1        ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">응답메세지2          </td><td><? echo($r_msg2        ) ?></td></tr>	
	<tr><td align="left" bgcolor="#f1f1f1">주문번호             </td><td><? echo($r_ordno       ) ?></td></tr>	
	<tr><td align="left" bgcolor="#f1f1f1">발급사코드           </td><td><? echo($r_isscd       ) ?></td></tr>
	<tr><td align="left" bgcolor="#f1f1f1">매입사코드           </td><td><? echo($r_aqucd       ) ?></td></tr>
	<tr>
		<td colspan=2>연동URL<hr/></td>
	</tr>
	<tr><td colspan="2"><? echo( $send_url ) ?></td></tr>
	<tr>
		<td colspan=2>요청메세지<hr/></td>
	</tr>
	
	<tr><td colspan="2"><? echo( $send_msg ) ?></td></tr>
	<tr>
		<td colspan=2>응답메세지<hr/></td>
	</tr>

	<tr><td colspan="2"><? echo( $recv_msg ) ?></td></tr>
</table>
		</td>
	</tr>
</table>

</form>
</body>
</html>
