<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
include "../dbconn_utf8.inc";
if($_POST['type'] == "api"){
	
	$ekey = "A91D2B6121AA07C748B9CA4323963E69";
	$msalt = "MA01";
	$kscode = "1372";
	
	$sendDate = date("Ymd");
	$sendTime = date("Hid");
	$companyCode = "UPCHE214";
	$companyBankCode = "026";

	/*
	//개시전문전송
	echo "개시전문 전송<br>";
	$sendData = 'JSONData={
			"kscode":"'.$kscode.'","ekey":"'.$ekey.'","msalt":"'.$msalt.'",
			"reqdata":
			[
				{
					"date":"'.$sendDate.'",
					"time":"'.$sendTime.'",
					"seqNo":"'.(int)$sendTime.'",
					"compCode":"'.$companyCode.'",
					"bankCode":"'.$companyBankCode.'"
				}
			]
		}';


	$api_url = 'https://cmsapi.ksnet.co.kr/ksnet/rfb/bankstart';
	$ch = curl_init($api_url); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData); 
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$reponse = curl_exec($ch); 
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$resultJson = json_decode($reponse);

	echo "STATUS : ".$status."<br>";
	echo "수신결과 : ".$reponse."<br>";
	echo "전송결과 : ".($resultJson->replyCode == '0000') ? 'Y' : 'N';
	*/

	echo "<br>";
	echo "계좌인증 전송 데이터 : <br>";


	//계좌인증
	$sendData = 'JSONData={
					"kscode":"'.$kscode.'","ekey":"'.$ekey.'","msalt":"'.$msalt.'",
					"reqdata":
					[
						{
							"date":"'.$sendDate.'",
							"time":"'.$sendTime.'",
							"seqNo":"'.$sendTime.'",
							"accountBankCode":"'.$_POST['sel_account_bank'].'",
							"accountNo":"'.$_POST['account'].'",
							"agencyYn":"N",						
							"compCode":"'.$companyCode.'",
							"bankCode":"'.$companyBankCode.'"
						}
					]
				}';

	echo $sendData."<br>";

	$api_url = 'https://cmsapi.ksnet.co.kr/ksnet/rfb/account/accountname'; //운영
	//$api_url = 'https://cmsapitest.ksnet.co.kr/ksnet/rfb/account/accountname';  //개발

	$ch = curl_init($api_url); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData); 
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$reponse = curl_exec($ch); 
	$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch); 		

	$resultJson = json_decode($reponse);

	$yn = ($resultJson->accountName != "" && $resultJson->replyCode = "0000") ? "Y" : "N";

	echo "STATUS : ".$status."<br>";
	echo "수신결과 : ".$reponse."<br>";
	echo "전송결과 : ".$yn;


}else{  //전용선 방식



	include "TranseId.php";

	/*
	$sSiteID = "D798";  	// 사이트 id 
	$sSitePW = "17379399";   // 비밀번호

	$cb_encode_path = "/home/httpd/unicity/cb_namecheck/cb_namecheck";			// cb_namecheck 모듈이 설치된 위치 

	$strJumin= $reg_jumin1.$reg_jumin2;		// 주민번호
	$strName = $user_name;		//이름
	*/
	//$strName = iconv("utf-8","euc-kr",$strName);
	/*
	$iReturnCode  = "";	

	$iReturnCode = `$cb_encode_path $sSiteID $sSitePW $strJumin $strName`;

	if ($iReturnCode != "1") {
		
		if ($iReturnCode == "2") {
			$str_msg = "본인이 아닙니다.";
		} else if ($iReturnCode == "3") {
			$str_msg = "자료가 없습니다.";
		} else if ($iReturnCode == "4") {
			$str_msg = "kis 시스템 장애 입니다.";
		} else if ($iReturnCode == "5") {
			$str_msg = "주민번호 오류 입니다.";
		} else if ($iReturnCode == "9") {
			$str_msg = "request 데이타 오류.";
		} else if ($iReturnCode == "10") {
			$str_msg = "사이트코드 오류";
		} else if ($iReturnCode == "11") {
			$str_msg = "사이트 정지상태임";
		} else if ($iReturnCode == "12") {
			$str_msg = "사이트 패스워드 오류";
		} else if ($iReturnCode == "13") {
			$str_msg = "사이트 인증시스템 장애";
		} else if ($iReturnCode == "50") {
			$str_msg = "popup";
		} else {
			$str_msg = "네트워크 상태의 악화로 인한 장애 입니다.";
		}

		echo "<script language=\"javascript\">\n
			alert('".$str_msg."');
			</script>";

		exit;

	}

	*/


	//$Host = "129.200.9.18"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1
	$Host = "129.200.9.11"; //2008-04-24 서비스 IP 변경 기존 :129.200.9.1
	$Port = "9237"; //Real
	//$Port = "9238"; //Test


	/*
	공통부분...
	*/

	$space = "";
	$inc_code = "UPCHE214";
	$bank_code = "026";
	$no = "000001";
	$trans_date = date(Ymd);
	$trans_time = date(His);


	/*
	개시전문만드는곳...
	*/

	$msg_code = "0800";
	$gubun = "100";
	$no = "000001";

	$openMsg = sprintf("%9s%8s%2s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%15s%03s%13s%200s",$space,$inc_code,$space,$msg_code,$gubun,1,$no,$trans_date,$trans_time,$space,$space,$space,$space,$space,$bank_code,$space,$space);

	echo "<br>개시전문<br>".$openMsg."<br>";

	$ss = global_SendSocketToC($Host, $Port, $openMsg);

	//print $ss[msg];
	//print_r(substr($ss[msg],0);


	print "1 : ".substr($ss[msg],9,8)."<br>";	// 은행에서 부여한 업체 코드.
	print "2 : ".substr($ss[msg],17,2)."<br>";	// 은행코드(신한은행은 참고로 26번입니다.)
	print "3 : ".substr($ss[msg],19,4)."<br>";	// 0810 이란 전문 코드가 올겁니다. 그러면 그 코드는 개시되었다는 응답코드입니다.
	print "4 : ".substr($ss[msg],23,3)."<br>";	// 100 이란 전문코드가 올겁니다. 그러면 그 코드는 개시된 업무의 코드입니다.


	/*
	성명조회전문 만드는곳...
	*/

	$search_bank_code = $search_bank_code;

	$msg_code = "0600";
	$gubun = "400";
	$trade_date = date(md);

	$account = substr($account,0,16);
	$name = $user_name;
	$name_euckr = iconv("utf-8","euc-kr",$name);

	//기업은행의 경우 - 이름공백허용안함 자리수9자리 제한됨
	if($search_bank_code == "003") $name = substr(str_replace(" ","", $name),0,9);

	//$jumin = $reg_jumin1.$reg_jumin2;
	$jumin = $reg_jumin1.$space.$space.$space.$space.$space.$space.$space;
	//$jumin = $reg_jumin1."       ";



	$searchMsg = sprintf("%9s%8s%2s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%15s%03s%13s%4s%2s%-16s%-22s%-13s%22s%3s%93s%20s%1s%4s",$space,$inc_code,$space,$msg_code,$gubun,1,$no,$trans_date,$trans_time,$space,$space,$trans_date,$trans_time,$space,$bank_code,$space,$trade_date,$space,$account,$space,$jumin,$space,$search_bank_code,$space,$space,$space,$space);

	echo "<br>계좌조회전문<br>".$searchMsg."<br>";

	$ss = global_SendSocketToC($Host, $Port, $searchMsg);

	print_r($ss)."<br>";

	print "1 : ".substr($ss[msg],19,4)."<br>";		// 0610 이란 전문 코드가 올겁니다. 그러면 그 코드는 조회되었다는 응답코드입니다.
	print "2 : ".substr($ss[msg],23,3)."<br>";		// 400 이란 전문코드가 올겁니다. 그러면 그 코드는 조회된 업무의 코드입니다.
	print "3 : ".substr($ss[msg],100,4)."<br>"; 	// 거래일자
	print "4 : ".substr($ss[msg],104,2)."<br>";	    // 계좌은행
	print "5 : ".substr($ss[msg],106,16)."<br>";	// 조회한 계좌번호
	print "6 : ".substr($ss[msg],122,22)."<br>";	// 조회한 사람의 이름
	print "7 : ".substr($ss[msg],144,13)."<br>";	// 조회한 주민번호
	print "8 : ".substr($ss[msg],51,4)."<br>";	// 응답

	print "전문전체 : ".$ss[msg]."<br>";	// 전문 전체

	$compare_str = substr($ss[msg],122,22);

	$compare_str = iconv("euc-kr","utf-8",$compare_str);

	echo "<br>비교 문자열 : ".$compare_str."<br>";

	if ( strlen($compare_str) > strlen($name) ) {
		$compare_str = substr($compare_str,0, strlen($name));
	}

	echo " 비교 문자열 2 : ".$compare_str."<br>";

	echo " 비교하려는 이름 : ".$name."<br>";

	if($search_bank_code == "003"){
		if($user_name != $name){
			echo " <br> 원래 이름 : ".$user_name."<br>";
			echo " 기업은행의 경우 이름간 공백이 허용이 안되며 자리수는 9자리 이내 입니다. <br>";
		}
	}


	if (substr($ss[msg],51,4)!="0000") {
		echo "<script language=\"javascript\">\n
				alert('계좌정보가 일치하지 않습니다.".substr($ss[msg],51,4)."');
				</script>";
	} else {
		if ($name != trim($compare_str)) {

			echo "<script language=\"javascript\">\n
					alert('계좌 예금주와 입력하신 성명과 일치하지 않습니다.');
				</script>";

		} else {
			echo "<script language=\"javascript\">\n
					alert('조회 성공.');
				</script>";
		}
	}
	/*
	종료전문만드는곳...


	$msg_code = "0800";
	$gubun = "300";
	$no = "000001";

	$openMsg = sprintf("%9s%8s%02s%4s%3s%s%06s%8s%6s%4s%4s%8s%6s%31s%200s",$space,$inc_code,$bank_code,$msg_code,$gubun,1,$no,$trans_date,$trans_time,$space,$space,$trans_date,$space,$space,$space);

	$ss = global_SendSocketToC($Host, $Port, $openMsg);
	print_r($ss);
	*/
}
?>
<br>
<br>
<br>
<br>
<a href="index.php">다른 사람 조회 하기</a>
