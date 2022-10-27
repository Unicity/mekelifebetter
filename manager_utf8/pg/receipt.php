<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/11/08
	// 	Last Update : 2004/11/08
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.11.08 by Park ChanHo 
	// 	File Name 	: KSPAY_form.php
	// 	Description : KSPAY_form 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	function card_XXX($str){

		$slen = strlen($str);
		
		$tmp = substr($str,0,4)." - ".substr($str,4,4)." - **** - ****";

		return $tmp;
	}

	function date_format2($str){

		$slen = strlen($str);
		if ($slen <> 0) {
			$tmp = substr($str,2,2)."/".substr($str,4,2)."/".substr($str,6,2);
		} else {
			$tmp = "";
		}
		return $tmp;
	}

	function time_format($str){

		$slen = strlen($str);
		if ($slen <> 0) {
			$tmp = substr($str,0,2).":".substr($str,2,2);
		} else {
			$tmp = "";
		}
		return $tmp;
	}

	include "../inc/global_init.inc";
	include "../../dbconn_utf8.inc";

	$ks_id = trim($ks_id);

	$query = "select * from tb_kspay where ks_id = '".$ks_id."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$Message1 = $list[Message1];
	$CardNo = $list[CardNo];
	$installment = $list[installment];
	$amount = $list[amount];
	$TradeDate = $list[TradeDate];
	$TradeTime = $list[TradeTime];
	$goodname = $list[goodname];
	$MerchantNo = $list[MerchantNo];
	$AuthNo = $list[AuthNo];
	$ordername = $list[ordername];
	$ordernumber = $list[ordernumber];
	$ExpDate = $list[ExpDate];
	$CStatus = $list[CStatus];
	mysql_close($connect);
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?echo $g_site_title?></title>
<STYLE type=text/css>
BODY, TABLE, TR, TD{
    	font-family: 돋움, Gulim;
		font-size: 9pt;
		color:#24A5C2 ; 
		line-height: 13pt;
		font-weight: bolder;
}
input,form,option,{font-size: 9pt;font-family:돋움; line-height: 13pt;color: #24A5C2 }
.fo {  background-color: #ffffff; border: 1px #FFFFFF solid; color: #24A5C2;font-weight: bolder;}
</STYLE>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="394" height="630" border="0" cellpadding="0" cellspacing="0">
<form>
  <tr>
    <td height="668" align="center" valign="bottom" background="images/bg.gif"><table width="337" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" valign="bottom"><table width="337" height="19" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="237" style="padding:0 0 0 6">&nbsp;</td>
            <td width="100" align="right" style="padding:0 6 0 0"><?echo substr($ordernumber,0,8)?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
	  <tr>
        <td height="40" valign="bottom" style="padding:0 0 2 6"><?echo $Message1?></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom" style="padding:0 0 2 6"><?echo card_XXX($CardNo)?></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" height="19" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="54" align="center"><?echo substr($ExpDate,2,2)?>/<?echo substr($ExpDate,0,2)?></td>
            <td width="141" style="padding:0 0 0 7"><?echo date_format2($TradeDate)?> <?echo time_format($TradeTime)?></td>
            <td width="142" style="padding:0 0 0 7">
			<? if ($CStatus == "O") { ?>	
				<?echo date_format2($TradeDate)?> <?echo time_format($TradeTime)?>
            <?	} ?>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" height="19" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="169" style="padding:0 0 0 6">
            	
			<? if ($CStatus == "O") { ?>	
            	취소
            <?	} else {?>
				신용구매
            <?	} ?>
			<?
				if ($installment == "00") {
					echo " (일시불)";
				} else {
					echo " (".$installment."개월)";
				}
			?>
            
            </td>
            <td width="168" style="padding:0 0 0 6"><?echo $goodname?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="113" style="padding:0 0 0 6">
			<? if ($CStatus == "O") { ?>	
            	취소
            <?	} else {?>
				신용구매
            <?	} ?>
            </td>
            <td width="56">&nbsp;</td>
            <td width="16" align="center"><? if (strlen($amount) > 9) {?><?echo substr($amount, (strlen($amount)-10), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 8) {?><?echo substr($amount, (strlen($amount)-9), 1)?><? }?></td>
            <td width="16" align="center"><? if (strlen($amount) > 7) {?><?echo substr($amount, (strlen($amount)-8), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 6) {?><?echo substr($amount, (strlen($amount)-7), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 5) {?><?echo substr($amount, (strlen($amount)-6), 1)?><? }?></td>
            <td width="16" align="center"><? if (strlen($amount) > 4) {?><?echo substr($amount, (strlen($amount)-5), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 3) {?><?echo substr($amount, (strlen($amount)-4), 1)?><? }?></td>
            <td width="17" align="center"><? if (strlen($amount) > 2) {?><?echo substr($amount, (strlen($amount)-3), 1)?><? }?></td>
            <td width="16" align="center"><? if (strlen($amount) > 1) {?><?echo substr($amount, (strlen($amount)-2), 1)?><? }?></td>
            <td width="15" align="center"><? if (strlen($amount) > 0) {?><?echo substr($amount, (strlen($amount)-1), 1)?><? }?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="113" style="padding:0 0 0 6">&nbsp;</td>
            <td width="56">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="17" align="center">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="15" align="center">0</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="113" style="padding:0 0 0 6">&nbsp;</td>
            <td width="56">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="18" align="center">&nbsp;</td>
            <td width="17" align="center">&nbsp;</td>
            <td width="16" align="center">&nbsp;</td>
            <td width="15" align="center">0</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="113" style="padding:0 0 0 6">&nbsp;</td>
            <td width="56">&nbsp;</td>
            <td width="16" align="center"><? if (strlen($amount) > 9) {?><?echo substr($amount, (strlen($amount)-10), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 8) {?><?echo substr($amount, (strlen($amount)-9), 1)?><? }?></td>
            <td width="16" align="center"><? if (strlen($amount) > 7) {?><?echo substr($amount, (strlen($amount)-8), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 6) {?><?echo substr($amount, (strlen($amount)-7), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 5) {?><?echo substr($amount, (strlen($amount)-6), 1)?><? }?></td>
            <td width="16" align="center"><? if (strlen($amount) > 4) {?><?echo substr($amount, (strlen($amount)-5), 1)?><? }?></td>
            <td width="18" align="center"><? if (strlen($amount) > 3) {?><?echo substr($amount, (strlen($amount)-4), 1)?><? }?></td>
            <td width="17" align="center"><? if (strlen($amount) > 2) {?><?echo substr($amount, (strlen($amount)-3), 1)?><? }?></td>
            <td width="16" align="center"><? if (strlen($amount) > 1) {?><?echo substr($amount, (strlen($amount)-2), 1)?><? }?></td>
            <td width="15" align="center"><? if (strlen($amount) > 0) {?><?echo substr($amount, (strlen($amount)-1), 1)?><? }?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" height="19" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="114" style="padding:0 0 0 6">스튜어트 휴즈</td>
            <td width="223" style="padding:0 0 0 6"><?echo $AuthNo?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" height="19" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="169" style="padding:0 0 0 6">유니시티코리아(주)</td>
            <td width="87" style="padding:0 0 0 6">&nbsp;</td>
            <td width="81" style="padding:0 0 0 6">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom" style="padding:0 0 2 6">서울시 강남구 역삼동 708-1 동우B/D 3F</td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" height="19" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="169" style="padding:0 0 0 6"><?echo $MerchantNo?></td>
            <td width="168" style="padding:0 0 0 6">220-86-69820<!--40220-86-69820--></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="1" valign="bottom"></td>
      </tr>
      <tr>
        <td height="40" valign="bottom"><table width="337" height="19" border="0" cellpadding="0" cellspacing="0">
          <tr valign="top">
            <td width="169" style="padding:0 0 0 6">02-3450-1800</td>
            <td width="168" style="padding:0 0 0 6">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="31">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="40" align="center"><a href="javascript:print();"><img src="images/print.gif" width="57" height="19" hspace="5" border="0"></a><a href="javascript:self.close();"><img src="images/close.gif" width="60" height="19" hspace="5" border="0"></a></td>
  </tr>
  </form>
</table>
</body>
</html>
