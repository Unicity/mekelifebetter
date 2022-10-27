<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_input.php
	// 	Description : 관리자 추가 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

  
	include "./inc/global_init.inc";
	include "../dbconn_utf8.inc";
	include "./inc/common_function.php";
	


	$smsSend = $_POST['smsSend'];
	$phone = $_POST['phone'];
	$noVal=$_POST['noVal'];
	$baNo=$_POST['memberNo'];

	if($smsSend=='se'){
		$smsNumber = '025641880';
		$dsc = '서울DSC';
	}else if($smsSend=='an'){
		$smsNumber = '0314841880';
		$dsc = '안산DSC';
	}else if($smsSend=='de'){
		$smsNumber = '0424851860';
		$dsc = '대전DSC';
	}else if($smsSend=='wo'){
		$smsNumber = '0337668269';
		$dsc = '원주DSC';
	}else if($smsSend=='bu'){
		$smsNumber = '0518656669';
		$dsc = '부산DSC';
	}else if($smsSend=='dg'){
		$smsNumber = '0536569636';
		$dsc = '대구DSC';
	}else if($smsSend=='kw'){
		$smsNumber = '0623761880';
		$dsc = '광주DSC';
	}else if($smsSend=='in'){
		$smsNumber = '0325041880';
		$dsc = '인천DSC';
	}else if($smsSend=='je'){
		$smsNumber = '0647261882';
		$dsc = '제주DSC';
	}

	$contents = "[유니시티 코리아]\n 안녕하세요.유니시티코리아(유)입니다.\n 귀하께서 신청하신 후원자변경 요청 건은 처리 불가함을 안내 드립니다.\n 문의 사항은 ".$dsc."로 연락 주시기 바랍니다. 감사합니다. ";
	$query = "insert into NEO_MSG (phone, callback, reqdate, msg, subject, type) values ('$phone', '$smsNumber', sysdate(), '$contents','유티시티코리아', 2)";

	mysql_query($query);

	$updateQuery="update tb_change_sponsor set sms_yn = 'Y', send_date = now() where no = $noVal and member_no = '$baNo'"; 

	mysql_query($updateQuery);
	$okVal="OK";
	echo(json_encode(array("count"=>$smsSend, "OK"=>$okVal)));
	
?>
