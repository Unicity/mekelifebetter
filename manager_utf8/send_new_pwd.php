<?
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "../AES.php";

	function generateRandomPassword($length=8, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}

		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}


	function trimSpace ($array) {
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
	}

	$search_id		= str_quote_smart(trim($search_id));
	//$search_email	= str_quote_smart(trim($search_email));
	
	/* 20230119 email 값 제거*/  
	//$query = "select Email from tb_admin where id = '$search_id' and Email = '$search_email' ";
	$query = "select Email from tb_admin where id = '$search_id'";
	
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$Email = $list[Email];

	if ($Email) {
		
		// 이메일을 전송 합니다.
		$new_pwd = generateRandomPassword();



		$new_pass = str_quote_smart(trim($new_pwd));

		//$en_pass = encrypt($key, $iv, $new_pass);
		//$en_pass = password_hash($new_pass, PASSWORD_DEFAULT);
		$en_pass = base64_encode(hash('sha256', $new_pass, true));

		$query = "update tb_admin set passwd = '$en_pass', EN_PASS = '$en_pass', pw_update_date = now(), PWD_EMAIL_DATE = now() where id = '$search_id'";
		
		mysql_query($query) or die("Query Error");

	
		$main_body = "임시 비밀번호는 ".$new_pwd." 입니다. 로그인 후 새로운 비밀번호로 수정해 주세요.";
		$ch = curl_init();
		$url = "https://member-calls2.unicity.com/email-api/message";
		$sendData = array();
		$sendData["from"] = "[유니시티코리아] <cskorea@unicity.com>";
		$sendData["to"] = $Email;
		$sendData["subject"] ="유니코어 임시 비밀번호 안내";
		$sendData["body"] =$main_body ;
		
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
	
/*
		$subject = "유니시티 기업 홈 관리자 임시 비밀번호를 보내드립니다.";
		$sub_subject = "유니시티 기업 홈 관리자 임시 비밀번호를 보내드립니다.";

		$subject = iconv("utf-8","euc-kr",$subject);
		$sub_subject = iconv("utf-8","euc-kr",$sub_subject);

		$Email = $Email."<".$Email.">";
		$AdminMail = "cskorea@unicity.com";

		$header = "Return-Path:".$AdminMail."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: text/html; charset=euc-kr\r\n";
		$header .= "X-Mailer: PHP\r\n";
		$header .= "Content-Transfer-Encoding: 8bit\r\n";
		$header .= "From: 유니시티 코리아<".$AdminMail.">\r\n";
		$header .= "Reply-To: 유니시티 코리아<".$AdminMail.">\r\n";

		$header = iconv("utf-8","euc-kr",$header);

		$main_body = "임시 비밀번호는 ".$new_pwd." 입니다. 로그인 후 새로운 비밀번호로 수정해 주세요.";

		$mail_body = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
<title>::::: 유니시티 코리아 (주) :::::</title>
</head>
<body bgcolor='#FFFFFF' leftmargin=0 topmargin='0' marginwidth=0 marginheight=0>
<table width='100%'><tr><td align='center'>
<table width='602' height='302' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td align='center' bgcolor='#E5E5E5'><table width='600' height='300' border='0' cellpadding='0' cellspacing='0' bgcolor='#FFFFFF'>
        <tr> 
          <td width='17' height='20'></td>
          <td width='566'></td>
          <td width='17'></td>
        </tr>
        <tr> 
          <td></td>
          <td valign='top'><table width='566' height='100%' border='0' cellpadding='0' cellspacing='0' style='font-family:굴림; font-size: 9pt;color: #777777; line-height:12pt;'>
              <tr> 
                <td width='27' height='3' bgcolor='#FDA79E'></td>
                <td width='539' bgcolor='#FDA79E'></td>
              </tr>
              <tr> 
                <td></td>
                <td valign='top' style='padding-top:60px;padding-right:10px;padding-bottom:10px; line-height:15pt;'>
				<!-- 본문 -->
						".$main_body."
				<!-- 본문 END -->
                  </td>
              </tr>
            </table></td>
          <td></td>
        </tr>
      </table></td>
  </tr>
</table>
</td></tr></table>
</body>
</html>";

		//echo $mail_body;

		$mail_body = iconv("utf-8","euc-kr",$mail_body);

		$flag = mail($Email, $subject, stripslashes($mail_body), $header);
*/
		mysql_close($connect);

		echo "T";
	} else {
		echo "F";
	}

?>