<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
	}


	$member_nos = str_quote_smart(trim($member_nos));
	$numbers		= str_quote_smart(trim($numbers));

	$a_member_nos = explode("|",$member_nos);
	$a_numbers = explode("|",$numbers);


	#===============================  문자 보내기  =================================
	include "../SMSSender/lib/Rmember.cfg.php";
	include "../SMSSender/lib/Rmember.func.php";

	$RSRVD_SND_DTTM = 'null';
	$USED_CD = '00';

	$R_RSRVD_ID = "lcp-kr";
	$R_USR_PASS = "30467";
	$SND_PHN_ID = "01191331446";
	$CALLBACK = "0809088181";

	
	for ($i=0 ; count($a_member_nos) > $i ; $i++) {

	if(trim($a_numbers[$i])){
		$query = "update tb_userinfo_dup set 
					number = '$a_numbers[$i]',  
					reg_status = '4',  
					confirm_date = now(),  
					confirm_ma = '$s_adm_name'  
					where member_no = '$a_member_nos[$i]'";
		

		mysql_query($query) or die("Query Error");
		
		#echo $query."<BR>";

		$query = "select * from tb_userinfo_dup where member_no = '$a_member_nos[$i]'";

		$result = mysql_query($query);
		$list = mysql_fetch_array($result);

		$name = $list[name];
		$email = $list[email];
		$member_no = $list[member_no];
		$hphone1 = $list[hpho1]."-".$list[hpho2]."-".$list[hpho3];
		$hphone2 = $list[hpho1].$list[hpho2].$list[hpho3];
		$User_name = $list[name];
		$number = $list[number];
		$password = $list[password];
		$UserID = $list[number];
		$member_kind = $list[member_kind];

		$CMP_SND_DTTM = date( "YmdHis", time() );

		$RCV_PHN_ID = $hphone2;
		
	

		if (trim($member_kind) == "C") {
		$SND_MSG ="
[유니시티코리아]
회원가입에감사드
립니다.회원번호:".$number."
비밀번호:".$password." ";
		} else {
		$SND_MSG ="
[유니시티코리아]
회원가입에감사드립
니다.FO:".$number."
비밀번호:".$password." ";
		}
	
		$result = sms_send( $CMP_ID, $CMP_USR_ID, $R_RSRVD_ID, $R_USR_PASS, $SND_PHN_ID, $RCV_PHN_ID, $CALLBACK, $SND_MSG, $RSRVD_SND_DTTM, $MSG_GB_SMS , $SMS_GB, $USED_CD, $NAT_CD, $RSRVD);

		$result_arr = explode( chr(9), $result);

		if( $result_arr[0] != 'SS')
		{
	 		$error_str = "잘못된 형식이므로 수행하지 못하였습니다";
	 		$error_flag = "NO";
		}
		else 
		{
	
		switch( $result_arr[1])
		{
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

		if( $error_flag == "OK" )
		{
			$query3 = "update tb_userinfo_dup set sms_date = now(), sms_ma = '$s_adm_name' where member_no = '$member_no' ";
			mysql_query($query3) or die("Query Error");	
		}	

#=============================== 메일 발송 부분 ================================================

		$name = trim($name);
		$tomail = trim($email);
		$subject = "유니시티 일원이 되신 귀하께 축하의 말씀을 드립니다.";
		$sub_subject = "유니시티 일원이 되신 귀하께 축하의 말씀을 드립니다.";

		$subject = iconv("utf-8","euc-kr",$subject);
		$sub_subject = iconv("utf-8","euc-kr",$sub_subject);

		$toMail = $tomail."<".$tomail.">";

		$AdminMail = $s_adm_email; 		

		$header = "Return-Path:".$AdminMail."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=euc-kr\r\n";
		$header .= "X-Mailer: PHP\r\n";
		$header .= "Content-Transfer-Encoding: 8bit\r\n";
		$header .= "From: 유니시티 코리아<".$AdminMail.">\r\n";
		$header .= "Reply-To: 유니시티 코리아<".$AdminMail.">\r\n";

		$header = iconv("utf-8","euc-kr",$header);

		if (trim($member_kind) == "C") {
			$str_ba = "회원번호";
		} else {
			$str_ba = "FO No";		
		}
		
		$mail_body = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>::::: 유니시티 코리아 (주) :::::</title>
</head>
<body bgcolor='#FFFFFF' leftmargin=0 topmargin='0' marginwidth=0 marginheight=0 >
<table width='671' height='582' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td align='center' valign='bottom' background='http://www.makelifebetter.co.kr/member/mail/images/join_bg.gif'>
	  <table width='520' border='0' cellspacing='0' cellpadding='0'style='font-family:굴림; font-size:9pt; color:#47A8BD;line-height:13pt;font-weight: bolder;'>
        <tr>
          <td height='66' align='center' valign='top'>".$name."님의 ".$str_ba."는 ".$number." 이고 비밀번호는 ".$password." 입니다.</td>
        </tr>
        <tr>
          <td height='56' align='center' valign='top'><a href='http://www.makelifebetter.co.kr'><img src='http://www.makelifebetter.co.kr/images/join_home.gif' width='109' height='19' hspace='5' border='0'></a>
          <!--<a href='http://www.makelifebetter.co.kr/member/mem_amend.php'><img src='http://www.makelifebetter.co.kr/images/join_amend.gif' width='86' height='19' hspace='5' border='0'></a>-->
          </td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>";
	
			$mail_body = iconv("utf-8","euc-kr",$mail_body);

			mail($toMail, $subject, stripslashes($mail_body), $header);


			$query4 = "update tb_userinfo_dup set email_date = now(), email_ma = '$s_adm_name' where member_no = '$member_no' ";
			mysql_query($query4) or die("Query Error");	
	}
	}
	#=============================== sms 발송 부분 끝 =============================================


	#echo $error_str;

	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
		alert('입력 되었습니다.');
		document.location = 'dup_user_list.php';
		</script>";
	exit;

?>