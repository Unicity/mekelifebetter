
<?php

    include "../dbconn_utf8.inc";   
	///header('Content-Type: application/json'); 
	$sDate = $_POST['sDate'];

	$key57 = "PGK2001104957+CoYi1AXJbI="; //2001104957 
	$key65 = "PGK200110486599vRwEZlPaA="; //2001104865
	$key09 = "PGK2001106709c+npfisgxdQVOdFnSdHjHQ=="; //2001106709
	
	// 2001104957
/*
	$url = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=20221102&file_sele=PGLST";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $url);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key57));
	$response = curl_exec($ch);
	curl_close($ch);

	$result =iconv("EUC-KR", "UTF-8", $response); 

	$result = json_decode($result,true);
	$count = $result['data_cnt'];
	//echo $count;
	//return $result;

	//2001104865

	$urlFor65 = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=20221102&file_sele=PGLST";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlFor65);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key65));
	$responseFor65 = curl_exec($ch);
	curl_close($ch);

	$resultFor65 =iconv("EUC-KR", "UTF-8", $responseFor65); 

	$resultFor65 = json_decode($resultFor65,true);
	$countFor65 = $resultFor65['data_cnt'];

	//2001106709

	$urlFor09 = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=20221102&file_sele=PGLST";
	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlFor09);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key09));
	$responseFor09 = curl_exec($ch);
	curl_close($ch);

	$resultFor09 =iconv("EUC-KR", "UTF-8", $responseFor09); 

	$resultFor09 = json_decode($resultFor09,true);
	$countFor09 = $resultFor09['data_cnt'];

	// Allat unikrnt
	$urlForAllat = 'https://dn.mcash.co.kr/support/dn_file.php?id=unicityit02&pwd=Mingu1004!&no=42319&date=20221130';

	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlForAllat);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);    
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
*/
	$urlForAllatBo = 'https://dn.mcash.co.kr/support/dn_file.php?id=unicityit02&pwd=Mingu1004!&no=42211&date=20221126';

	$ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $urlForAllatBo);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:'.$key09));
	$responseAllatBo = curl_exec($ch);
	curl_close($ch);

	$responseAllatBo =iconv("EUC-KR", "UTF-8", $responseAllatBo); 
	$comma_Bo = str_replace("_", " ", $responseAllatBo);
	
	$commaBo = preg_replace("/\s+/", ",", $comma_Bo);

	$delimitersBo =",";
	$explodesBo = explode($delimitersBo, $commaBo);
	//echo print_r($explodesBo);
	unset($explodesBo[0]);
	echo print_r($explodesBo);
	$arrayDataBo=array_chunk($explodesBo, 11);
	//echo print_r($arrayDataBo);

	$countForAllatBo=count($arrayDataBo);

 	$allatBo = array();
	$arrayBo = array();

	
	for($i=1; $i<$countForAllatBo; $i++){
		$allatBo = $arrayDataBo[$i];
		$key = array_search( '<LIST>', $allatBo );
		array_splice( $allatBo, $key, -12 );

		//print_r($allatBo);

		$arrayBo["storeOrderNum"]= $allatBo[0];
		$arrayBo["allatOrderNum"]= $allatBo[1];
		$arrayBo["orderNum"]= $allatBo[2];
		$arrayBo["serviceId"]= $allatBo[3];
		$arrayBo["payDate"]= $allatBo[4];
		$arrayBo["cardNum"]= $allatBo[5];
		$arrayBo["authNum"]= $allatBo[6];
		$arrayBo["amount"]= $allatBo[7];

		$rstBo = json_encode($arrayBo);	
	}

	
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
						<th width="5%" style="text-align: center;">마지막 구분</th>
						<th width="5%" style="text-align: center;">주문자</th>
						<th width="5%" style="text-align: center;">금액</th>
						<th width="5%" style="text-align: center;">승인번호</th>
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
							$ksnet_rej_code = $result['data'][$i]['ksnet_rej_code']; //매입 반송 구분 if 신용카드 00정상, 그외 배입반송 else if 비신용카드: 공백 또는 00
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
								
								<td style="width: 5%" align="center"><?echo $order_last?></td>
								<td style="width: 5%" align="center"><?echo $order_pers_name ?></td>
								<td style="width: 5%" align="center"><?echo $amount ?></td>
								<td style="width: 5%" align="center"><?echo $appr_numb ?></td>
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
					?>
						<tr>
							<td style="width: 5%" align="center"><?echo $shop_id ?></td>
							<td style="width: 5%" align="center"><?echo $deal_start_date?></td>
							<!--td style="width: 5%" align="center"><?echo $order_numb?></td>-->
							<td style="width: 5%" align="center"><?echo $order_numb?></td>
							<td style="width: 5%" align="center"><?echo $order_pers_name ?></td>
							<td style="width: 5%" align="center"><?echo $amount ?></td>
							<td style="width: 5%" align="center"><?echo $appr_numb ?></td>
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
						?>
							<tr>
								<td style="width: 5%" align="center"><?echo $shop_id ?></td>
								<td style="width: 5%" align="center"><?echo $deal_start_date?></td>
								<!--td style="width: 5%" align="center"><?echo $order_numb?></td>-->
								<td style="width: 5%" align="center"><?echo $order_numb?></td>
								<td style="width: 5%" align="center"><?echo $order_pers_name ?></td>
								<td style="width: 5%" align="center"><?echo $amount ?></td>
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
						?>
							<tr>
								<td style="width: 5%" align="center"><?echo $array["storeOrderNum"]?></td>
								<td style="width: 5%" align="center"><?echo $array["allatOrderNum"]?></td>
								<td style="width: 5%" align="center"><?echo $array["orderNum"]?></td>
								<td style="width: 5%" align="center"><?echo $array["serviceId"]?></td>
								<td style="width: 5%" align="center"><?echo $array["payDate"]?></td>
								<td style="width: 5%" align="center"><?echo $array["cardNum"]?></td>
								<td style="width: 5%" align="center"><?echo $array["authNum"]?></td>
								<td style="width: 5%" align="center"><?echo $array["amount"]?></td>
								
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
								array_splice( $allatBo, $key, 1 );
						
								$arrayBo["storeOrderNum"]= $allatBo[0];
								$arrayBo["allatOrderNum"]= $allatBo[1];
								$arrayBo["orderNum"]= $allatBo[2];
								$arrayBo["serviceId"]= $allatBo[3];
								$arrayBo["payDate"]= $allatBo[4];
								$arrayBo["cardNum"]= $allatBo[5];
								$arrayBo["authNum"]= $allatBo[6];
								$arrayBo["amount"]= $allatBo[7];
						
								$rstBo = json_encode($arrayBo);	
						
							
						?>
							<tr>
								<td style="width: 5%" align="center"><?echo $arrayBo["storeOrderNum"]?></td>
								<td style="width: 5%" align="center"><?echo $arrayBo["allatOrderNum"]?></td>
								<td style="width: 5%" align="center"><?echo $arrayBo["orderNum"]?></td>
								<td style="width: 5%" align="center"><?echo $arrayBo["serviceId"]?></td>
								<td style="width: 5%" align="center"><?echo $arrayBo["payDate"]?></td>
								<td style="width: 5%" align="center"><?echo $arrayBo["cardNum"]?></td>
								<td style="width: 5%" align="center"><?echo $arrayBo["authNum"]?></td>
								<td style="width: 5%" align="center"><?echo $arrayBo["amount"]?></td>
								
							</tr>
						<?php 
						}
						?>
				</table>
			</body>
		</div>
      </li>
    </ul>
  </div>
</div>
	<script>
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
	</script>		  
</html>	
