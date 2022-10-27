<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/3/8
	// 	Last Update : 2004/3/8
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004/3/8 by Park ChanHo 
	// 	File Name 	: 
	// 	Description :  
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";

	$mode				= str_quote_smart(trim($mode));
	$qry_str		= str_quote_smart(trim($qry_str));
	$idxfield		= str_quote_smart(trim($idxfield));
	$page				= str_quote_smart(trim($page));
	$member_no	= str_quote_smart(trim($member_no));
	
	$member_no = "39552";
	//$member_no = "39383";

	$query = "select * from tb_userinfo where  member_no = '$member_no'";
	
	#echo $query; 

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$member_no = $list[member_no];
	$password = $list[password];
	$name = $list[name];
	$ename = $list[ename];
	$reg_jumin1 = $list[reg_jumin1];
	$reg_jumin2 = $list[reg_jumin2];
	$active_kind = $list[active_kind];
	$couple = $list[couple];
	$couple_name = $list[couple_name];
	$couple_ename = $list[couple_ename];
	$couple_reg_jumin1 = $list[couple_reg_jumin1];
	$couple_reg_jumin2 = $list[couple_reg_jumin2];
	$zip = $list[zip];
	$addr = $list[addr];
	$del_zip = $list[del_zip];
	$del_addr = $list[del_addr];
	$hpho1 = $list[hpho1];
	$hpho2 = $list[hpho2];
	$hpho3 = $list[hpho3];
	$pho1 = $list[pho1];
	$pho2 = $list[pho2];
	$pho3 = $list[pho3];
	$account = $list[account];
	$account_bank = $list[account_bank];
	$email = $list[email];
	$email_flag = $list[email_flag];
	$interest = $list[interest];
	$co_number = $list[co_number];
	$co_name = $list[co_name];
	$join_kind = $list[join_kind];

	$member_kind = $list[member_kind];
	$birth_y = $list[birth_y];
	$birth_m = $list[birth_m];
	$birth_d = $list[birth_d];
	$sex = $list[sex];


	$member_kind = trim($member_kind);
	$join_kind = trim($join_kind);


#=============================== 메일 발송 부분 ================================================

	$name = trim($name);
	$tomail = trim($email);
	$subject = "유니시티 회원가입 신청에 감사드립니다.";
	$sub_subject = "유니시티 회원가입 신청에 감사드립니다.";

	$subject = iconv("utf-8","euc-kr",$subject);
	$sub_subject = iconv("utf-8","euc-kr",$sub_subject);

	$tomail = "orion@giringrim.com";

	$toMail = $tomail."<".$tomail.">";
	
	$AdminMail = "customer_korea@unicity.net"; 		

	$header = "Return-Path:".$AdminMail."\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: text/html; charset=euc-kr\r\n";
	$header .= "X-Mailer: PHP\r\n";
	$header .= "Content-Transfer-Encoding: 8bit\r\n";
	$header .= "From: 유니시티 코리아<".$AdminMail.">\r\n";
	$header .= "Reply-To: 유니시티 코리아<".$AdminMail.">\r\n";

	$header = iconv("utf-8","euc-kr",$header);

    if ($active_kind == "1") {
    	$str_active = "후원사업자 (자가소비)";
    } else {
  		$str_active = "소매이익사업자(부가가치 신고대상자)"; 
  	}
		
	$mail_body = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=euc-kr'>
<title>::::: 유니시티 코리아 (주) :::::</title>
</head>
<body bgcolor='#FFFFFF' leftmargin=0 topmargin='0' marginwidth=0 marginheight=0 >
<table width='671' border='0' cellpadding='0' cellspacing='0'>
  <tr> 
    <td colspan='5' align='center' valign='bottom'><img src='http://www.makelifebetter.co.kr/images/join_topbg.gif' width='671' height='133'></td>
  </tr>
  <tr> 
    <td width='12' align='center' valign='bottom' background='http://www.makelifebetter.co.kr/images/join_leftbg.gif'>&nbsp;</td>
    <td width='27' align='center' valign='bottom'>&nbsp;</td>
    <td width='592' align='center' valign='bottom'>
	  <table width='592' border='0' cellspacing='0' cellpadding='0' style='font-family:굴림; font-size:9pt; color:#777777;line-height:13pt;'>
        <form>
          <tr> 
            <td width='125' height='3' bgcolor='#B298EE'></td>
            <td width='1' bgcolor='#B298EE'></td>
            <td width='466' bgcolor='#B298EE'></td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr valign='top'> 
            <td height='23' style='padding-left:23px;'>자격사항</td>
            <td><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='6'></td>
            <td style='padding-left:4px;'>본인은 <br>
              만 20세 이상의 성인이며, 재학중인 학생이 아닙니다.<br>
              법인 및 다단계 판매업자의 지배주주 또는 임직원이 아닙니다. <br>
              국가 공무원, 지방 공무원 또는 사립학교법에 의한 교원이 아닙니다. <br>
              방문판매등에 관한 법률을 위반한 사실이 없습니다. </td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='3' bgcolor='#B298EE'></td>
            <td bgcolor='#B298EE'></td>
            <td bgcolor='#B298EE'></td>
          </tr>
        </form>
      </table> 
      <table width='592' border='0' cellspacing='0' cellpadding='0' style='font-family:굴림; font-size:9pt; color:#777777;line-height:13pt;'>
        <form>
          <tr> 
            <td width='125' height='3' bgcolor='#B298EE'></td>
            <td width='1' bgcolor='#B298EE'></td>
            <td width='466' bgcolor='#B298EE'></td>
          </tr>
          <tr> 
            <td height='27' bgcolor='#F7F7F7' style='color:#B298EE;'><img src='http://www.makelifebetter.co.kr/images/mem_titimg.gif' width='20' height='27' align='absmiddle'><strong>신청인</strong></td>
            <td bgcolor='#F7F7F7'></td>
            <td align='right' bgcolor='#F7F7F7' style='color:#B298EE;padding-right:10px;'></td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='23' valign='top' style='padding-top:2px;padding-left:23px;'>성명</td>
            <td valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='5'></td>
            <td valign='top' style='padding-left:4px;'>".$name." (".$ename.")</td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='23' style='padding-top:2px;padding-left:23px;'>주민등록번호</td>
            <td><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='8'></td>
            <td style='padding-left:4px;'>".$reg_jumin1."-".$reg_jumin2."</td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='23' valign='top' style='padding-left:23px;'>활동유형</td>
            <td valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='2'></td>
            <td valign='top' style='padding-left:4px;'>
            ".$str_active." 
            </td>
          </tr>
          <tr> 
            <td colspan='3' height='1' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>";

	if ($couple == "Y") {
	
	$mail_body = $mail_body."
          <tr> 
            <td height='23' bgcolor='#F7F7F7' style='padding-left:23px;'>배우자</td>
            <td bgcolor='#F7F7F7'></td>
            <td style='padding-left:4px;' bgcolor='#F7F7F7'></td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='23' style='padding-top:2px;padding-left:23px;'>한글성명</td>
            <td><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='8'></td>
            <td style='padding-left:4px;'>".$couple_name." (".$couple_ename.")</td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='23' style='padding-top:2px;padding-left:23px;'>주민등록번호</td>
            <td><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='8'></td>
            <td style='padding-left:4px;'>".$couple_reg_jumin1."-".$couple_reg_jumin2."</td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>";
          
	}
          
	$mail_body = $mail_body."
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='23' valign='top' style='padding-top:2px;padding-left:23px;'>주민등록상 
              주소 </td>
            <td valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='5'></td>
            <td valign='top' style='padding-left:4px;'>[".$zip."] ".$addr."</td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='23' valign='top' style='padding-top:2px;padding-left:23px;'>우편물 
              수령 주소 </td>
            <td valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='2'></td>
            <td valign='top' style='padding-left:4px;'> [".$del_zip."] ".$del_addr." </td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='23' valign='top' style='padding-top:2px;padding-left:23px;'>휴대폰</td>
            <td valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='5'></td>
            <td valign='top' style='padding-left:4px;'>".$hpho1."-".$hpho2."-".$hpho3." </td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='23' style='padding-top:2px;padding-left:23px;'>자택전화번호</td>
            <td><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='8'></td>
            <td style='padding-left:4px;'>".$pho1."-".$pho2."-".$pho3."</td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='23' valign='top' style='padding-top:2px;padding-left:23px;'>거래은행</td>
            <td valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='2'></td>
            <td valign='top' style='padding-left:4px;'>은행 : ".$account_bank." 계좌번호 : ".$account."</td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='6'></td>
            <td></td>
            <td></td>
          </tr>
          <tr> 
            <td height='23' valign='top' style='padding-top:2px;padding-left:23px;'>e-mail주소</td>
            <td valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='5'></td>
            <td valign='top' style='padding-left:4px;'>".$email."</td>
          </tr>
          <tr> 
            <td colspan='3' height='1' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='23' bgcolor='#F7F7F7' style='padding-left:23px;'>후원자</td>
            <td bgcolor='#F7F7F7'></td>
            <td style='padding-left:4px;' bgcolor='#F7F7F7'></td>
          </tr>
          <tr> 
            <td height='1' colspan='3' background='http://www.makelifebetter.co.kr/images/mem_dotline01.gif'></td>
          </tr>
          <tr> 
            <td height='23' style='padding-top:2px;padding-left:23px;'>후원자 확인 
            </td>
            <td><img src='http://www.makelifebetter.co.kr/images/mem_log_bar.gif' width='1' height='10' vspace='8'></td>
            <td style='padding-left:4px;'>FO번호 : ".$co_number." 후원자 성명 : ".$co_name."</td>
          </tr>
          <tr> 
            <td height='3' bgcolor='#B298EE'></td>
            <td bgcolor='#B298EE'></td>
            <td bgcolor='#B298EE'></td>
          </tr>
        </form>
      </table></td>
    <td width='27' align='center' valign='bottom'>&nbsp;</td>
    <td width='13' align='center' valign='bottom' background='http://www.makelifebetter.co.kr/images/join_rightbg.gif'>&nbsp;</td>
  </tr>";
	if ($join_kind == "N") {

	$mail_body = $mail_body."
  <tr> 
    <td align='center' valign='bottom' background='http://www.makelifebetter.co.kr/images/join_leftbg.gif'>&nbsp;</td>
    <td colspan='3' align='right' valign='bottom'><img src='http://www.makelifebetter.co.kr/images/join_signbg.gif' width='210' height='85' hspace='27'></td>
    <td align='center' valign='bottom' background='http://www.makelifebetter.co.kr/images/join_rightbg.gif'>&nbsp;</td>
  </tr>
  <tr> 
    <td height='153' colspan='5' align='center' valign='bottom' background='http://www.makelifebetter.co.kr/images/join_botbg.gif'>
	<table width='588' height='135' border='0' cellpadding='0' cellspacing='0' style='font-family:굴림; font-size:9pt; color:#777777;line-height:13pt;'>
        <tr>
          <td valign='top'>공인인증서를 사용하시지 않고 회원등록을 진행하신 경우 본 신청서를 출력하시어 본인 서명후 각 
            지역 고객지원센타로 방문하시어 제출하시거나 FAX로 송부하여 주시기 바랍니다. <br>
            FAX로 송부하실 경우는 송부하신 후 송부된 각 지역 고객지원센타 또는 고객상담실(080-908-8181)로 연락<br>
            주시기 바랍니다.<br>
            온라인 가입후 7일이내에 본인 서명의 신청서가 접수되지 않을 경우 회원가입은 취소될 수 있습니다. <br>
            감사합니다.</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>";

	} else {

	$mail_body = $mail_body."
                <tr> 
                  <td height='50' colspan='3'><table width='592' border='0' cellspacing='0' cellpadding='0' style='font-family:굴림; font-size:9pt; color:#777777;line-height:13pt;'>
                      <tr> 
                        <td height='10'></td>
                        <td></td>
                      </tr>
					  <tr> 
                        <td width='23' align='right' valign='top'><img src='http://www.makelifebetter.co.kr/images/mem_log_arrow01.gif' width='10' height='11' hspace='5' vspace='2'></td>
                        <td>위의 기재된 내용으로 온라인 회원 가입 신청하신 것을 감사 드립니다. 내용의 수정 및 변경사항이 
                          있으시면, 각 지역 고객지원 센터나 고객상담실(080-908-8181)로 연락 주십시오.<br>
                          <br>
                          가입 자격 심사와 이중등록 확인 후 등록 축하 메세지를 핸드폰 SMS와 등록하신 이메일로 보내드리도록 
                          하겠습니다. </td>
                      </tr>
					  <tr> 
                        <td height='10'></td>
                        <td></td>
                      </tr>
                    </table></td>
                </tr>
      </table></td>
  </tr>
</table>
</body>
</html>";

	}

	$mail_body = iconv("utf-8","euc-kr",$mail_body);

	$flag = mail($toMail, $subject, stripslashes($mail_body), $header);
	

	mysql_close($connect);
	
	if ($mail_body) {
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n 
			alert('발송 되었습니다.');
			document.location = 'user_cemail_ba.php?member_no=$member_no';
			</script>";
	} else {
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
		echo "<script language=\"javascript\">\n 
			alert('정상적으로 발송되지 않았습니다.\\n사용자 정보(주소, 이름 등)를 확인해 주십시오.');
			document.location = 'user_cemail_ba.php?member_no=$member_no';
			</script>";
	}
	exit;

?>
