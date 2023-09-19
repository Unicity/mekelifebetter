
<?php

    include "../dbconn_utf8.inc";   
	include "./lodingBar.html";   
	///header('Content-Type: application/json'); 
	$sDate = $_POST['sDate'];


	$dateInfotrax = substr($sDate,0,4)."-".substr($sDate,4,2)."-".substr($sDate,6,2);



	if ($page <> "") {
        $page = (int)($page);
    } else {
        $page = 1;
    }
    if ($nPageSize <> "") {
        $nPageSize = (int)($nPageSize);
    } else {
        $nPageSize = 50;
    }
    
    $nPageBlock	= 10;
    $offset = $nPageSize*($page-1);

	


	$key57 = "PGK2001104957+CoYi1AXJbI="; //2001104957 
	$key65 = "PGK200110486599vRwEZlPaA="; //2001104865
	$key09 = "PGK2001106709c+npfisgxdQVOdFnSdHjHQ=="; //2001106709
	
	// 2001104957

	$url = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=$sDate&file_sele=PGLST";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $url);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key57));
	$response = curl_exec($ch);
	curl_close($ch);

	$result =iconv("EUC-KR", "UTF-8", $response); 
	$result = json_decode($result,true);

	//echo "==>".print_r($result);

	$count = $result['data_cnt'];


  
	//2001104865
	$urlFor65 = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=$sDate&file_sele=PGLST";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlFor65);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key65));
	$responseFor65 = curl_exec($ch);
	curl_close($ch);

	$resultFor65 =iconv("EUC-KR", "UTF-8", $responseFor65); 
	$resultFor65 = json_decode($resultFor65,true);
	$countFor65 = $resultFor65['data_cnt'];


	


	//2001106709
	$urlFor09 = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=$sDate&file_sele=PGLST";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlFor09);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key09));
	$responseFor09 = curl_exec($ch);
	curl_close($ch);

	$resultFor09 =iconv("EUC-KR", "UTF-8", $responseFor09); 
	$resultFor09 = json_decode($resultFor09,true);
	$countFor09 = $resultFor09['data_cnt'];


	// Allat unikrnt
	$urlForAllat = "https://dn.mcash.co.kr/support/dn_file.php?id=unicityit02&pwd=Mingu1004!&no=42319&date=$sDate";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlForAllat);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key09));
	$responseAllat = curl_exec($ch);
	curl_close($ch);

	$resultForAllat =iconv("EUC-KR", "UTF-8", $responseAllat); 
	$comma = preg_replace("/\s+/", ",", $resultForAllat);

	//echo $comma;
	$content = $comma;
	$delimiters =",";
	$explodes = explode($delimiters, $content);

	$arrayData=array_chunk($explodes, 9);
	$countForAllat=count($arrayData);

 	$allat = array();
	$array = array();


//Allat unikrbo	
	$urlForAllatBo = "https://dn.mcash.co.kr/support/dn_file.php?id=unicityit02&pwd=Mingu1004!&no=42211&date=$sDate";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlForAllatBo);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key09));
	$responseAllatBo = curl_exec($ch);
	curl_close($ch);

	$responseAllatBo =iconv("EUC-KR", "UTF-8", $responseAllatBo); 
	$comma_Bo = str_replace("_", " ", $responseAllatBo);
	
	$commaBo = preg_replace("/\s+/", ",", $comma_Bo);

	$delimitersBo =",";
	$explodesBo = explode($delimitersBo, $commaBo);
	unset($explodesBo[0]);
	array_unshift($explodesBo, "allatNo","initial");
	
	$arrayDataBo=array_chunk($explodesBo, 11);
	$countForAllatBo=count($arrayDataBo);

 	$allatBo = array();
	$arrayBo = array();

	//for($i=1; $i<$countForAllatBo; $i++){
		//$allatBo = $arrayDataBo[$i];
		//$key = array_search( '<LIST>', $allatBo );
		//array_splice( $allatBo, $key, -12 );

		//print_r($allatBo);

		//$arrayBo["storeOrderNum"]= $allatBo[0];
		//$arrayBo["allatOrderNum"]= $allatBo[1];
		//$arrayBo["orderNum"]= $allatBo[2];
		//$arrayBo["serviceId"]= $allatBo[3];
		//$arrayBo["payDate"]= $allatBo[4];
		//$arrayBo["cardNum"]= $allatBo[5];
		//$arrayBo["authNum"]= $allatBo[6];
		//$arrayBo["amount"]= $allatBo[7];

		//echo $arrayBo["allatOrderNum"];

		//$rstBo = json_encode($arrayBo);	
	//}
	
	//infoTrax

// 토큰 생성
	$id='kr_ar';
    $pw='Nrwk%vOSo&ht&fJ!sxvVyjIwy8t4';

	//$id='minguk';
    //$pw='Mg555555';
  

    $ch = curl_init();
    $url = "https://hydraqa.unicity.net/v5a-test/loginTokens?expand=whoami";
    $sendData = array();
    $sendData["source"] = array("medium" => "Template");
    $sendData["type"] = "base64";
    $sendData["value"] = base64_encode("{$id}:{$pw}");
    $sendData["namespace"] = "https://hydraqa.unicity.net/v5a-test/employees";
	
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
	$token = $json_result['token']; 



// 데이터 호출
	//$infoTraxUrl = "https://hydraqa.unicity.net/v5a-test/reports/dailysalesreport?market=KR&dateCreated=2022-10-04";
	/*$infoTraxUrl = "https://hydra.unicity.net/v5a/reports/dailysalesreport?market=KR&dateCreated=$dateInfotrax";

	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $infoTraxUrl);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer '.$token));
	$responseInfo = curl_exec($ch);
    $json_resultInfo = json_decode($responseInfo, true);
	$infoCnt=count($json_resultInfo);
	curl_close($ch);
	*/
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-Frame-Options" content="deny" />
		<title>마감관리</title>
		<link rel="stylesheet" href="./inc/admin.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>	
	<style>
		*{margin:0; padding:0;}
		ul{list-style:none;}
		a{text-decoration:none; color:#333;}
		.wrap{padding:15px; letter-spacing:-0.5px;}
		.tab_menu{position:relative;}
		.tab_menu .list{overflow:hidden;}
		.tab_menu .list li{float:left; margin-right:14px;}
		.tab_menu .list .btn{font-size:13px;}
		.tab_menu .list .cont{display:none; position:absolute; top:25px; left:0; background:#555; color:#fff; text-align:center; width:100%; height:100%; line-height:100px;}
		.tab_menu .list li.is_on .btn{font-weight:bold; color:green;}
		.tab_menu .list li.is_on .cont{display:block;}
	</style>	
	<div class="wrap">
		<table cellspacing="0" cellpadding="10" class="title" border="0">
			<tr>
				<td align="left"><b>마감 결과 조회</b></td>
			
				<td align="right" width="600" align="center" bgcolor=silver>
					<input type="button" value="엑셀 다운로드" onClick="excelDown();">
				</td>
			</tr>
		</table>
		<form name="frmSearch" method="post">
			<div class="tab_menu">
				<ul class="list">
					<li class="is_on">
						<a href="#tab1" class="btn">2001104957</a>
						<div id="tab1" class="cont">
							<body>
								<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
									<tr>
										<th width="5%" style="text-align: center;">상점번호</th>				
										<th width="5%" style="text-align: center;">신청일자</th>
										<!--<th width="5%" style="text-align: center;">전체</th>-->
										<th width="5%" style="text-align: center;">주문번호</th>
										<th width="5%" style="text-align: center;">이니셜</th>
										<!--<th width="5%" style="text-align: center;">마지막 구분</th>-->
										<th width="5%" style="text-align: center;">주문자</th>
										<th width="5%" style="text-align: center;">금액</th>
										<th width="5%" style="text-align: center;">승인번호</th>
										<th width="5%" style="text-align: center;">거래번호</th>
										<th width="5%" style="text-align: center;">매입사</th>
									</tr>
										<?php
										for($i = 0 ; $i < $count ; $i++){	

											//JSONArray에서 [$i] 번째 행의 JSONObject [' '] 항목의 값을 가져옴
											$deal_start_date = $result['data'][$i]['deal_start_date']; //거래일자
											$pg_deal_numb = $result['data'][$i]['pg_deal_numb']; //거래번호
											$fee = $result['data'][$i]['fee']; //수수료
											$appr_numb = $result['data'][$i]['appr_numb']; //승인번호
											
											$card_numb = $result['data'][$i]['card_numb']; //카드번호
											$purc_gove_code = $result['data'][$i]['purc_gove_code']; // 매입사코드
											$make_gove_code = $result['data'][$i]['make_gove_code']; // 발급사코드
											
											$purc_id = $result['data'][$i]['purc_id']; //가맹점 번호
											$stan_resp_code = $result['data'][$i]['stan_resp_code']; //거래응답코드 0000:정상, 나머지 거절
											$data_sele = $result['data'][$i]['data_sele']; //데애터 구분 DC :신용카드, DV : 가상계좌, DB 마이통장
											$canc_seq = $result['data'][$i]['canc_seq']; // 부분취소 시퀀스 
											
											$deal_start_time = $result['data'][$i]['deal_start_time']; //거래일자
											$purc_fini_date = $result['data'][$i]['purc_fini_date']; //매입완료일자
											
											$fee_vat = $result['data'][$i]['fee_vat']; //부가세
											$amount = $result['data'][$i]['amount'];// 금액
											$ex_rate = $result['data'][$i]['ex_rate']; // 신용카드 달러 환율
											$money_code = $result['data'][$i]['money_code']; // 승인통화 flag 410: 원화, 840 달러, 392 엔화
											
											$card_type = $result['data'][$i]['card_type']; // 체크카드 구분 0: 신용카드. 1:체크카드
											$ksnet_rej_code = $ersult['data'][$i]['ksnet_rej_code']; //매입 반송 구분 if 신용카드 00정상, 그외 배입반송 else if 비신용카드: 공백 또는 00
											$auth_code = $result['data'][$i]['auth_code']; //승인구분 
											$pay_prea_date = $result['data'][$i]['pay_prea_date']; // 지급예정일
											$shop_id = $result['data'][$i]['shop_id']; //상점 아이디
											$purc_requ_date = $result['data'][$i]['purc_requ_date']; //매읿요청일자
											$won_amt = $result['data'][$i]['won_amt'];// 신용카드 환율 적용된 결제금액
											$reserved = $result['data'][$i]['reserved'];//??
											$order_prod_code = $result['data'][$i]['order_prod_code'];//상품명
											$inte_sele = $result['data'][$i]['inte_sele'];//이자구분
											$allo_mont = $result['data'][$i]['allo_mont']; // 할부개월수 00: 일시불
											$order_numb = $result['data'][$i]['order_numb']; //주문번호
											$order_pers_name = $result['data'][$i]['order_pers_name']; // 주문자명	
								
										

											//마지막 문자 추출
											$order_last = substr($order_numb, -1);
											
											//이니셜 추출
											if($order_last == 'A'){
												$pattern = '#_(.*?)_#';
											}else if($order_last=='M'){
												$pattern = '# (.*?) #';
											}
											preg_match($pattern, $order_numb, $matches);
										
											//주문번호 추출
											$orderArr = explode('_',$order_numb);
											$orderArrM = explode(' ',$order_numb);

											
											//echo strlen($orderArr[0]);

											if(strlen($orderArr[0]) > 9){
												$orderArr[0] = substr($orderArr[0],0,9); 
												
											}

											//매입사 코드
											
											if($purc_gove_code=='010001'){
												$card_code ='비씨카드';
											}else if($purc_gove_code=='010002'){
												$card_code ='국민카드';
											}else if($purc_gove_code=='010003'){
												$card_code ='외환카드';
											}else if($purc_gove_code=='010004'){
												$card_code ='삼성카드';
											}else if($purc_gove_code=='010005'){
												$card_code ='신한카드';						
											}else if($purc_gove_code=='010008'){
												$card_code ='현대카드';						
											}else if($purc_gove_code=='010009'){
												$card_code ='롯데아멕스';					
											}else if($purc_gove_code=='010010'){
												$card_code ='구신한카드';					
											}else if($purc_gove_code=='010011'){
												$card_code ='한미은행';						
											}else if($purc_gove_code=='010012'){
												$card_code ='수협';						
											}else if($purc_gove_code=='010013'){
												$card_code ='신세계한미';						
											}else if($purc_gove_code=='010014'){
												$card_code ='우리은행';					
											}else if($purc_gove_code=='010015'){
												$card_code ='농협';					
											}else if($purc_gove_code=='010016'){
												$card_code ='제주은행';						
											}else if($purc_gove_code=='010017'){
												$card_code ='광주은행';					
											}else if($purc_gove_code=='010018'){
												$card_code ='전북은행';					
											}else if($purc_gove_code=='010021'){
												$card_code ='롯데카드';						
											}else if($purc_gove_code=='010022'){
												$card_code ='산업은행';				
											}else if($purc_gove_code=='010023'){
												$card_code ='주택은행';					
											}else if($purc_gove_code=='010024'){
												$card_code ='하나은행';					
											}else if($purc_gove_code=='010025'){
												$card_code ='해외카드사';						
											}else if($purc_gove_code=='010026'){
												$card_code ='씨티은행';					
											}else if ($purc_gove_code=='010030'){
												$card_code ='경인에너지';						
											}else if($purc_gove_code=='010040'){
												$card_code ='현대백화점';						
											}else if($purc_gove_code=='010099'){
												$card_code ='기타';					
											}else if($purc_gove_code=='020079'){
												$card_code ='위챗페이';					
											}else if($purc_gove_code=='020085'){
												$card_code ='알라페이';					
											}else if($purc_gove_code=='060002'){
												$card_code ='레일플러스';						
											}else if($purc_gove_code=='020084'){
												$card_code ='SSG머니';					
											}else if($purc_gove_code=='080001'){
												$card_code ='휴대폰-다날';					
											}else if($purc_gove_code=='080002'){
												$card_code ='휴대폰-모빌리언스';						
											}	
										?>
											<tr>
												<td style="width: 5%" align="center"><?echo $shop_id ?></td>
												<td style="width: 5%" align="center"><?echo $deal_start_date?></td>
												<!--td style="width: 5%" align="center"><?echo $order_numb?></td>-->
											<?if($order_last == 'A'){?>
												<td style="width: 5%" align="center"><?echo print_r($orderArr[0],true)?></td>
											<?}else if($order_last == 'M'){ ?>	
												<td style="width: 5%" align="center"><?echo print_r($orderArrM[0],true)?></td>
											<?}else{?>
												<td style="width: 5%" align="center"><?echo $order_numb?></td>
											<?}?>		
												<td style="width: 5%" align="center"><?echo $matches[1]?></td>
											<!--<td style="width: 5%" align="center"><?echo $order_last?></td>-->
												<td style="width: 5%" align="center"><?echo $order_pers_name ?></td>
												<td style="width: 5%" align="center"><?echo number_format($amount) ?></td>
												<td style="width: 5%" align="center"><?echo $appr_numb ?></td>
												<td style="width: 5%" align="center"><?echo $pg_deal_numb ?></td>
												<td style="width: 5%" align="center"><?echo $card_code ?></td>
											</tr>
										<?php 
										}
										?>
								</table>
							</body>
						</div>
					</li>

					<li>
						<a href="#tab2" class="btn">2001104865</a>
						<div id="tab2" class="cont">
							<body>
							
								<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
									<tr>
										<th width="5%" style="text-align: center;">상점번호</th>				
										<th width="5%" style="text-align: center;">신청일자</th>
										<!--<th width="5%" style="text-align: center;">전체</th>-->
										<th width="5%" style="text-align: center;">주문번호</th>
										<!--<th width="5%" style="text-align: center;">이니셜</th>
										<th width="5%" style="text-align: center;">마지막 구분</th>-->
										<th width="5%" style="text-align: center;">주문자</th>
										<th width="5%" style="text-align: center;">금액</th>
										<th width="5%" style="text-align: center;">승인번호</th>
										<th width="5%" style="text-align: center;">매입사</th>
									</tr>
										<?php
										for($i = 0 ; $i < $countFor65 ; $i++){	

											//JSONArray에서 [$i] 번째 행의 JSONObject [' '] 항목의 값을 가져옴
											$deal_start_date = 	$resultFor65['data'][$i]['deal_start_date']; //거래일자
											$pg_deal_numb = 	$resultFor65['data'][$i]['pg_deal_numb']; //거래번호
											$fee = 	$resultFor65['data'][$i]['fee']; //수수료
											$appr_numb = $resultFor65['data'][$i]['appr_numb']; //승인번호
											
											$card_numb = $resultFor65['data'][$i]['card_numb']; //카드번호
											$purc_gove_code = $resultFor65['data'][$i]['purc_gove_code']; // 매입사코드
											$make_gove_code = $resultFor65['data'][$i]['make_gove_code']; // 발급사코드
											
											$purc_id = $resultFor65['data'][$i]['purc_id']; //가맹점 번호
											$stan_resp_code = $resultFor65['data'][$i]['stan_resp_code']; //거래응답코드 0000:정상, 나머지 거절
											$data_sele = $resultFor65['data'][$i]['data_sele']; //데애터 구분 DC :신용카드, DV : 가상계좌, DB 마이통장
											$canc_seq = $resultFor65['data'][$i]['canc_seq']; // 부분취소 시퀀스 
											
											$deal_start_time = $resultFor65['data'][$i]['deal_start_time']; //거래일자
											$purc_fini_date = $resultFor65['data'][$i]['purc_fini_date']; //매입완료일자
											
											$fee_vat = $resultFor65['data'][$i]['fee_vat']; //부가세
											$amount = $resultFor65['data'][$i]['amount'];// 금액
											$ex_rate = $resultFor65['data'][$i]['ex_rate']; // 신용카드 달러 환율
											$money_code = $resultFor65['data'][$i]['money_code']; // 승인통화 flag 410: 원화, 840 달러, 392 엔화
											
											$card_type = $resultFor65['data'][$i]['card_type']; // 체크카드 구분 0: 신용카드. 1:체크카드
											$ksnet_rej_code = $resultFor65['data'][$i]['ksnet_rej_code']; //매입 반송 구분 if 신용카드 00정상, 그외 배입반송 else if 비신용카드: 공백 또는 00
											$auth_code = $resultFor65['data'][$i]['auth_code']; //승인구분 
											$pay_prea_date = $resultFor65['data'][$i]['pay_prea_date']; // 지급예정일
											$shop_id = $resultFor65['data'][$i]['shop_id']; //상점 아이디
											$purc_requ_date = $resultFor65['data'][$i]['purc_requ_date']; //매읿요청일자
											$won_amt = $resultFor65['data'][$i]['won_amt'];// 신용카드 환율 적용된 결제금액
											$reserved = $resultFor65['data'][$i]['reserved'];//??
											$order_prod_code = $resultFor65['data'][$i]['order_prod_code'];//상품명
											$inte_sele = $resultFor65['data'][$i]['inte_sele'];//이자구분
											$allo_mont = $resultFor65['data'][$i]['allo_mont']; // 할부개월수 00: 일시불
											$order_numb = $resultFor65['data'][$i]['order_numb']; //주문번호
											$order_pers_name = $resultFor65['data'][$i]['order_pers_name']; // 주문자명	
											
											//마지막 문자 추출
											$order_last = substr($order_numb, -1);
											
											//이니셜 추출
											if($order_last == 'A'){
												$pattern = '#_(.*?)_#';
											}else if($order_last=='M'){
												$pattern = '# (.*?) #';
											}
											preg_match($pattern, $order_numb, $matches);
											
											//주문번호 추출
											$orderArr = explode('_',$order_numb);
											$orderArrM = explode(' ',$order_numb);

											//매입사 코드
												
											
											if($purc_gove_code=='010001'){
												$card_code ='비씨카드';					
											}else if($purc_gove_code=='010002'){
												$card_code ='국민카드';					
											}else if($purc_gove_code=='010003'){
												$card_code ='외환카드';						
											}else if($purc_gove_code=='010004'){
												$card_code ='삼성카드';				
											}else if($purc_gove_code=='010005'){
												$card_code ='신한카드';						
											}else if($purc_gove_code=='010008'){
												$card_code ='현대카드';						
											}else if($purc_gove_code=='010009'){
												$card_code ='롯데아멕스';					
											}else if($purc_gove_code=='010010'){
												$card_code ='구신한카드';					
											}else if($purc_gove_code=='010011'){
												$card_code ='한미은행';						
											}else if($purc_gove_code=='010012'){
												$card_code ='수협';						
											}else if($purc_gove_code=='010013'){
												$card_code ='신세계한미';						
											}else if($purc_gove_code=='010014'){
												$card_code ='우리은행';					
											}else if($purc_gove_code=='010015'){
												$card_code ='농협';					
											}else if($purc_gove_code=='010016'){
												$card_code ='제주은행';						
											}else if($purc_gove_code=='010017'){
												$card_code ='광주은행';					
											}else if($purc_gove_code=='010018'){
												$card_code ='전북은행';					
											}else if($purc_gove_code=='010021'){
												$card_code ='롯데카드';						
											}else if($purc_gove_code=='010022'){
												$card_code ='산업은행';				
											}else if($purc_gove_code=='010023'){
												$card_code ='주택은행';					
											}else if($purc_gove_code=='010024'){
												$card_code ='하나은행';					
											}else if($purc_gove_code=='010025'){
												$card_code ='해외카드사';						
											}else if($purc_gove_code=='010026'){
												$card_code ='씨티은행';					
											}else if ($purc_gove_code=='010030'){
												$card_code ='경인에너지';						
											}else if($purc_gove_code=='010040'){
												$card_code ='현대백화점';						
											}else if($purc_gove_code=='010099'){
												$card_code ='기타';					
											}else if($purc_gove_code=='020079'){
												$card_code ='위챗페이';					
											}else if($purc_gove_code=='020085'){
												$card_code ='알라페이';					
											}else if($purc_gove_code=='060002'){
												$card_code ='레일플러스';						
											}else if($purc_gove_code=='020084'){
												$card_code ='SSG머니';					
											}else if($purc_gove_code=='080001'){
												$card_code ='휴대폰-다날';					
											}else if($purc_gove_code=='080002'){
												$card_code ='휴대폰-모빌리언스';						
											}	
										?>
											<tr>
												<td style="width: 5%" align="center"><?echo $shop_id ?></td>
												<td style="width: 5%" align="center"><?echo $deal_start_date?></td>
												<!--td style="width: 5%" align="center"><?echo $order_numb?></td>-->
												<td style="width: 5%" align="center"><?echo $order_numb?></td>
												<td style="width: 5%" align="center"><?echo $order_pers_name ?></td>
												<td style="width: 5%" align="center"><?echo number_format($amount) ?></td>
												<td style="width: 5%" align="center"><?echo $appr_numb ?></td>
												<td style="width: 5%" align="center"><?echo $card_code ?></td>
											</tr>
										<?php 
										}
										?>
								</table>
							</body>
						</div>
					</li>
					<li>
						<a href="#tab3" class="btn">2001106709</a>
						<div id="tab3" class="cont">
							<body>
						
								<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
									<tr>
										<th width="5%" style="text-align: center;">상점번호</th>				
										<th width="5%" style="text-align: center;">신청일자</th>
										<!--<th width="5%" style="text-align: center;">전체</th>-->
										<th width="5%" style="text-align: center;">주문번호</th>
										<!--<th width="5%" style="text-align: center;">이니셜</th>
										<th width="5%" style="text-align: center;">마지막 구분</th>-->
										<th width="5%" style="text-align: center;">주문자</th>
										<th width="5%" style="text-align: center;">금액</th>
										<th width="5%" style="text-align: center;">매입사</th>
										<!--<th width="5%" style="text-align: center;">승인번호</th>-->
									</tr>
										<?php
										for($i = 0 ; $i < $countFor09 ; $i++){	

											//JSONArray에서 [$i] 번째 행의 JSONObject [' '] 항목의 값을 가져옴
											$deal_start_date = 	$resultFor09['data'][$i]['deal_start_date']; //거래일자
											$pg_deal_numb = 	$resultFor09['data'][$i]['pg_deal_numb']; //거래번호
											$fee = 	$resultFor09['data'][$i]['fee']; //수수료
											$appr_numb = $resultFor09['data'][$i]['appr_numb']; //승인번호
											
											$card_numb = $resultFor09['data'][$i]['card_numb']; //카드번호
											$purc_gove_code = $resultFor09['data'][$i]['purc_gove_code']; // 매입사코드
											$make_gove_code = $resultFor09['data'][$i]['make_gove_code']; // 발급사코드
											
											$purc_id = $resultFor09['data'][$i]['purc_id']; //가맹점 번호
											$stan_resp_code = $resultFor09['data'][$i]['stan_resp_code']; //거래응답코드 0000:정상, 나머지 거절
											$data_sele = $resultFor09['data'][$i]['data_sele']; //데애터 구분 DC :신용카드, DV : 가상계좌, DB 마이통장
											$canc_seq = $resultFor09['data'][$i]['canc_seq']; // 부분취소 시퀀스 
											
											$deal_start_time = $resultFor09['data'][$i]['deal_start_time']; //거래일자
											$purc_fini_date = $resultFor09['data'][$i]['purc_fini_date']; //매입완료일자
											
											$fee_vat = $resultFor09['data'][$i]['fee_vat']; //부가세
											$amount = $resultFor09['data'][$i]['amount'];// 금액
											$ex_rate = $resultFor09['data'][$i]['ex_rate']; // 신용카드 달러 환율
											$money_code = $resultFor09['data'][$i]['money_code']; // 승인통화 flag 410: 원화, 840 달러, 392 엔화
											
											$card_type = $resultFor09['data'][$i]['card_type']; // 체크카드 구분 0: 신용카드. 1:체크카드
											$ksnet_rej_code = $resultFor09['data'][$i]['ksnet_rej_code']; //매입 반송 구분 if 신용카드 00정상, 그외 배입반송 else if 비신용카드: 공백 또는 00
											$auth_code = $resultFor09['data'][$i]['auth_code']; //승인구분 
											$pay_prea_date = $resultFor09['data'][$i]['pay_prea_date']; // 지급예정일
											$shop_id = $resultFor09['data'][$i]['shop_id']; //상점 아이디
											$purc_requ_date = $resultFor09['data'][$i]['purc_requ_date']; //매읿요청일자
											$won_amt = $resultFor09['data'][$i]['won_amt'];// 신용카드 환율 적용된 결제금액
											$reserved = $resultFor09['data'][$i]['reserved'];//??
											$order_prod_code = $resultFor09['data'][$i]['order_prod_code'];//상품명
											$inte_sele = $resultFor09['data'][$i]['inte_sele'];//이자구분
											$allo_mont = $resultFor09['data'][$i]['allo_mont']; // 할부개월수 00: 일시불
											$order_numb = $resultFor09['data'][$i]['order_numb']; //주문번호
											$order_pers_name = $resultFor09['data'][$i]['order_pers_name']; // 주문자명	
											
											//마지막 문자 추출
											$order_last = substr($order_numb, -1);
											
											//이니셜 추출
											if($order_last == 'A'){
												$pattern = '#_(.*?)_#';
											}else if($order_last=='M'){
												$pattern = '# (.*?) #';
											}
											preg_match($pattern, $order_numb, $matches);
											
											//주문번호 추출
											$orderArr = explode('_',$order_numb);
											$orderArrM = explode(' ',$order_numb);

											//매입사 코드
											
										
									if($purc_gove_code=='030001'){
										$card_code ='한국은행';					
									}else if($purc_gove_code=='030002'){
										$card_code ='산업은행';					
									}else if($purc_gove_code=='030003'){
										$card_code ='기업은행';						
									}else if($purc_gove_code=='030004'){
										$card_code ='국민은행';					
									}else if($purc_gove_code=='030005'){
										$card_code ='외환은행';						
									}else if($purc_gove_code=='030006'){
										$card_code ='주택은행';						
									}else if($purc_gove_code=='030007'){
										$card_code ='수협';					
									}else if($purc_gove_code=='030008'){
										$card_code ='수출입은행';					
									}else if($purc_gove_code=='030010'){
										$card_code ='농협(10)';						
									}else if($purc_gove_code=='030011'){
										$card_code ='농협중앙';						
									}else if($purc_gove_code=='030012'){
										$card_code ='단위농협';						
									}else if($purc_gove_code=='030013'){
										$card_code ='농협(13)';					
									}else if($purc_gove_code=='030014'){
										$card_code ='농협(14)';					
									}else if($purc_gove_code=='030015'){
										$card_code ='농협(15)';						
									}else if($purc_gove_code=='030016'){
										$card_code ='농협(16)';					
									}else if($purc_gove_code=='030017'){
										$card_code ='농협(17)';					
									}else if($purc_gove_code=='030019'){
										$card_code ='국민은행';						
									}else if($purc_gove_code=='030020'){
										$card_code ='우리은행';				
									}else if($purc_gove_code=='030023'){
										$card_code ='제일은행';					
									}else if($purc_gove_code=='030026'){
										$card_code ='신한은행';					
									}else if($purc_gove_code=='030031'){
										$card_code ='대구은행';						
									}else if($purc_gove_code=='030032'){
										$card_code ='부산은행';					
									}else if ($purc_gove_code=='030034'){
										$card_code ='광주은행';						
									}else if($purc_gove_code=='030035'){
										$card_code ='제주은행';						
									}else if($purc_gove_code=='030037'){
										$card_code ='전북은행';					
									}else if($purc_gove_code=='030039'){
										$card_code ='경남은행';					
									}else if($purc_gove_code=='030045'){
										$card_code ='새마을금고';					
									}else if($purc_gove_code=='030048'){
										$card_code ='신협';						
									}else if($purc_gove_code=='030050'){
										$card_code ='상호저축은행';					
									}else if($purc_gove_code=='030052'){
										$card_code ='모건스탠리은행';					
									}else if($purc_gove_code=='030053'){
										$card_code ='시티은행';
									}else if($purc_gove_code=='030089'){
										$card_code ='케이뱅크';
									}else if($purc_gove_code=='030090'){
										$card_code ='카카오뱅크';
									}

										?>
											<tr>
												<td style="width: 5%" align="center"><?echo $shop_id ?></td>
												<td style="width: 5%" align="center"><?echo $deal_start_date?></td>
												<!--td style="width: 5%" align="center"><?echo $order_numb?></td>-->
												<td style="width: 5%" align="center"><?echo $order_numb?></td>
												<td style="width: 5%" align="center"><?echo $order_pers_name ?></td>
												<td style="width: 5%" align="center"><?echo number_format($amount) ?></td>
												<td style="width: 5%" align="center"><?echo $card_code ?></td>
												<!--<td style="width: 5%" align="center"><?echo $appr_numb ?></td>-->
											</tr>
										<?php 
										}
										?>
								</table>
							</body>		
						</div>
					</li>
					<li>
						<a href="#tab4" class="btn">allatUnikrnt</a>
						<div id="tab4" class="cont">
							<body>
						
								<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
									<tr>
										<th width="5%" style="text-align: center;">상점거래번호</th>				
										<th width="5%" style="text-align: center;">모빌리언스 거래번호</th>
										<th width="5%" style="text-align: center;">서비스아이디</th>
										<th width="5%" style="text-align: center;">거래일자</th>
										<th width="5%" style="text-align: center;">카드번호</th>
										<th width="5%" style="text-align: center;">카드사</th>
										<th width="5%" style="text-align: center;">승인번호</th>
										<th width="5%" style="text-align: center;">거래금액</th>
									</tr>
										<?php
										for($i=1; $i<$countForAllat; $i++){
											$allat = $arrayData[$i];
										//echo print_r($allat);	
											$key = array_search( '<LIST>', $allat );
											array_splice( $allat, $key, 1 );
									
											$array["storeOrderNum"]= $allat[0];
											$array["allatOrderNum"]= $allat[1];
											$array["orderNum"]= $allat[2];
											$array["serviceId"]= $allat[3];
											$array["payDate"]= $allat[4];
											$array["cardNum"]= $allat[5];
											$array["authNum"]= $allat[6];
											$array["amount"]= $allat[7];
									
											$rst = json_encode($array);	

										//echo $rst['storeOrderNum'];
										?>
											<tr>
												<td style="width: 5%" align="center"><?echo $array["storeOrderNum"]?></td>
												<td style="width: 5%" align="center"><?echo $array["allatOrderNum"]?></td>
												<td style="width: 5%" align="center"><?echo $array["orderNum"]?></td>
												<td style="width: 5%" align="center"><?echo $array["serviceId"]?></td>
												<td style="width: 5%" align="center"><?echo $array["payDate"]?></td>
												<td style="width: 5%" align="center"><?echo $array["cardNum"]?></td>
												<td style="width: 5%" align="center"><?echo $array["authNum"]?></td>
												<td style="width: 5%" align="center"><?echo number_format($array["amount"])?></td>
												
											</tr>
										<?php 
										}
										?>
								</table>
							</body>
						</div>
					</li>
					<li>
						<a href="#tab5" class="btn">allatUnikrbo</a>
						<div id="tab5" class="cont">
							<body>
						
								<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
									<tr>
										<th width="5%" style="text-align: center;">상점거래번호</th>	
										<th width="5%" style="text-align: center;">거래번호</th>	
										<th width="5%" style="text-align: center;">이니셜</th>			
										<th width="5%" style="text-align: center;">구분</th>			
										<th width="5%" style="text-align: center;">모빌리언스 거래번호</th>
										<th width="5%" style="text-align: center;">서비스아이디</th>
										<th width="5%" style="text-align: center;">거래일자</th>
										<th width="5%" style="text-align: center;">카드번호</th>
										<th width="5%" style="text-align: center;">카드사</th>
										<th width="5%" style="text-align: center;">승인번호</th>
										<th width="5%" style="text-align: center;">거래금액</th>
									</tr>
										<?php
											for($i=1; $i<$countForAllatBo; $i++){


												$allatBo = $arrayDataBo[$i];
												$key = array_search( '<LIST>', $allatBo );
												array_splice( $allatBo, $key, 0 );
												$arrayBo["storeSumOrder"]= $allatBo[0]."_".$allatBo[1]."_".$allatBo[2];
												$arrayBo["storeOrderNum"]= $allatBo[0];
												$arrayBo["storeOrderNum1"]= $allatBo[1];
												$arrayBo["storeOrderNum2"]= $allatBo[2];
												$arrayBo["allatOrderNum"]= $allatBo[3];
												$arrayBo["orderNum"]= $allatBo[4];
												$arrayBo["serviceId"]= $allatBo[5];
												$arrayBo["payDate"]= $allatBo[6];
												$arrayBo["cardNum"]= $allatBo[7];
												$arrayBo["authNum"]= $allatBo[8];
												$arrayBo["amount"]= $allatBo[9];
										
												$rstBo = json_encode($arrayBo);	

												$newDate = date("Y-m-d", strtotime($arrayBo["payDate"]));

												if(strlen($arrayBo["storeOrderNum"]) > 9){
													$arrayBo["storeOrderNum"] = substr($arrayBo["storeOrderNum"],0,9); 
													
												}
											
										
										?>
											<tr>
												<td style="width: 5%" align="center"><?echo $arrayBo["storeSumOrder"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["storeOrderNum"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["storeOrderNum1"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["storeOrderNum2"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["allatOrderNum"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["orderNum"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["serviceId"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["payDate"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["cardNum"]?></td>
												<td style="width: 5%" align="center"><?echo $arrayBo["authNum"]?></td>
												<td style="width: 5%" align="center"><?echo number_format($arrayBo["amount"])?></td>
												
											</tr>
										<?php 
										}
										?>
								</table>
							</body>
						</div>
					</li>
					<li>
						<a href="#tab7" class="btn">총합</a>
						<div id="tab7" class="cont">
							<body>
							
								<table cellspacing="1" cellpadding="5" class="LIST" border="0" bgcolor="silver">
									<tr>
										<th width="5%" colspan="7"; style="text-align: center;">인터넷 마감</th>			
									</tr>
									<tr>
									<th width="5%" style="text-align: center;">총건수</th>
										<th width="5%" style="text-align: center;">날짜</th>
										<th width="5%" style="text-align: center;">담당자</th>		
									</tr>
									<tr>	
										<tr>
											<td style="width: 5%" align="center"><?echo $releaseDate ?></td>
											<td style="width: 5%" align="center"><?echo $date?></td>
											<td style="width: 5%" align="center"><?echo $orderType?></td>
											<td style="width: 5%" align="center"><?echo $user?></td>
											<td style="width: 5%" align="center"><?echo $auth ?></td>
											<td style="width: 5%" align="center"><?echo $paymentType ?></td>
											<td style="width: 5%" align="center"><?echo $orderNumber ?></td>
										
										</tr>	
										
							
									</tr>	
										
								</table>
							</body>
						</div>
					</li>

				</ul>
			</div>
		</form>
	</div>
	<script>
		$( document ).ready(function() {
			$('#loading').hide();;
		});
		const tabList = document.querySelectorAll('.tab_menu .list li');
		for(var i = 0; i < tabList.length; i++){
			tabList[i].querySelector('.btn').addEventListener('click', function(e){
				e.preventDefault();
				for(var j = 0; j < tabList.length; j++){
				tabList[j].classList.remove('is_on');
				}
				this.parentNode.classList.add('is_on');
			});
		}

		function excelDown(){

			document.frmSearch.target = "";
			document.frmSearch.action = "./sales_close_excel.php";
			document.frmSearch.submit();

		}


	</script>		  
</html>	
