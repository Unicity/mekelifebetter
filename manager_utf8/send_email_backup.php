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

	$mode				= str_quote_smart(trim($mode));
	$qry_str		= str_quote_smart(trim($qry_str));
	$idxfield		= str_quote_smart(trim($idxfield));
	$page				= str_quote_smart(trim($page));
	$member_no	= str_quote_smart(trim($member_no));

	$query = "select * from tb_userinfo where member_no = '$member_no'";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$member_no = $list[member_no];
	$name = $list[name];
	$email = $list[email];
	$number = $list[number];
	$password = $list[password];
	$number = $list[number];
	$member_kind = $list[member_kind];


#=============================== 메일 발송 부분 ================================================

	$name = trim($name);
	$tomail = trim($email);
	$subject = "유니시티 일원이 되신 귀하께 축하의 말씀을 드립니다.";
	$sub_subject = "유니시티 일원이 되신 귀하께 축하의 말씀을 드립니다.";

	//$tomail = "orion70kr@hotmail.com";
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
<meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
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

	
	//echo $toMail."<br>"; 
	//echo $subject."<br>"; 
	//echo $mail_body."<br>"; 
	//echo $header."<br>"; 
	
	$mail_body = iconv("utf-8","euc-kr",$mail_body);

	$flag = mail($toMail, $subject, stripslashes($mail_body), $header);

	//echo "Flag : ".$flag;
	
	$query4 = "update tb_userinfo set email_date = now(), email_ma = '$s_adm_name' where member_no = '$member_no' ";
	mysql_query($query4) or die("Query Error");	


	#echo $error_str;

	mysql_close($connect);

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<script language=\"javascript\">\n
		alert('발송 되었습니다.');
		document.location = 'user_email_ba.php?member_no=$member_no';
		</script>";
	exit;

?>