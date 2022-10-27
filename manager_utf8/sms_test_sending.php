<?
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
	}


	$mode					= str_quote_smart(trim($mode));
	$seq_no				= str_quote_smart(trim($seq_no));
	$qry_str			= str_quote_smart(trim($qry_str));
	$idxfield			= str_quote_smart(trim($idxfield));
	$page					= str_quote_smart(trim($page));
	$sms_no				= str_quote_smart(trim($sms_no));


	$query = "select * from tb_sms where sms_no = '".$sms_no."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$sql_table = $list[sql_table];
	$sql_str1 = $list[sql_str1];
	$sql_str2 = $list[sql_str2];
	$sql_str3 = $list[sql_str3];
	$sms_name = $list[sms_name];
	$sms_kind = $list[sms_kind];
	$sms_admin = $list[sms_admin];
	$sms_goal = $list[sms_goal];
	$sms_memo = $list[sms_memo];
	$sms_data = $list[sms_data];


	$select_phone = trim($select_phone);
	
	$arr_select_phone = explode("|", $select_phone);

	#===============================  문자 보내기  =================================
	include "../SMSSender/lib/Rmember.cfg.php";
	include "../SMSSender/lib/Rmember.func.php";

	$RSRVD_SND_DTTM = 'null';
	$USED_CD = '00';

	$R_RSRVD_ID = "lcp-kr";
	$R_USR_PASS = "30467";
	$SND_PHN_ID = "01191331446";
	$CALLBACK = "0809088181";

	$CMP_SND_DTTM = date( "YmdHis", time() );

	$SND_MSG = $sms_data;


	for ($i = 0 ; $i+1 < sizeof($arr_select_phone) ; $i++) {

		$hphone = $arr_select_phone[$i];
		$hphone = eregi_replace("-", "", $hphone);
		
		$RCV_PHN_ID = $hphone;


		$result = sms_send( $CMP_ID, $CMP_USR_ID, $R_RSRVD_ID, $R_USR_PASS, $SND_PHN_ID, $RCV_PHN_ID, $CALLBACK, $SND_MSG, $RSRVD_SND_DTTM, $MSG_GB_SMS , $SMS_GB, $USED_CD, $NAT_CD, $RSRVD);

		$result_arr = explode( chr(9), $result);

		if( $result_arr[0] != 'SS') {
	 		$error_str = "잘못된 형식이므로 수행하지 못하였습니다";
	 		$error_flag = "NO";
		} else  {
	
			switch( $result_arr[1]) {
			
			case(00) :
				$error_str = "정상적으로 문자 메세지를 보냈습니다\\n남은 캐쉬량 $result_arr[2]캐쉬\\n사용한 캐쉬량 : $result_arr[3]캐쉬";
				$error_flag = "OK";
				break;

			case(01) :
				$error_str = "잘못된 형식이므로 수행하지 못하였습니다 - 01";
				$error_flag = "NO";
				break;
		
			case(11) :
				$error_str = "캐쉬량이 부족합니다. \\n잔여 캐쉬량 : $result_arr[2]";
				$error_flag = "NO";
				break;
		
			case(12) :
				$error_str = "받고자 하는 핸드폰 번호를 정확히 입력하시기 바랍니다.";
				$error_flag = "NO";
				break;
		
			case(13) :
				$error_str = "아이피 오류 입니다.";
				$error_flag = "NO";
				break;

			case(99) :
				$error_str = "잘못된 형식이므로 수행하지 못하였습니다 -99";
				$error_flag = "NO";
				break;
			}
		}

	}

	#=============================== sms 발송 부분 끝 =============================================

	#echo $error_str;

	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n
	<script language=\"javascript\">\n
		alert('전송 되었습니다.');
		document.location = 'sms_test_send.php?sms_no=$sms_no';
		</script>";
	exit;

?>