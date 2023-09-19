<?

    include "./PHPExcel/PHPExcel.php";
    $objPHPExcel = new PHPExcel();
    $sheet = $objPHPExcel->getActiveSheet();
    
    $sheet->mergeCells('A1:C1');

    $today=date('Ymd');
    $file_name="마감자료".$today.".xls"; //저장할 파일이름
    header( "Content-type: application/octet-stream;charset=utf-8");
    header( "Content-Disposition: attachment; filename=$file_name" );
    header("Content-Type: application/ms-excel");
    header("Pragma: no-cache");
    header("Expires: 0");
    header( "Content-Description: PHP4 Generated Data" );
    echo '<?xml version="1.0" encoding="utf-8"?>';

    $sDate = $_POST['sDate'];

    $dateInfotrax = substr($sDate,0,4)."-".substr($sDate,4,2)."-".substr($sDate,6,2);

    $dateInfotrax = date("Y-m-d", strtotime($dateInfotrax." -1 day"));


	$dateKsnet = str_replace('-','',$dateInfotrax);


    $key57 = "PGK2001104957+CoYi1AXJbI="; //2001104957 
	$key65 = "PGK200110486599vRwEZlPaA="; //2001104865
	$key09 = "PGK2001106709c+npfisgxdQVOdFnSdHjHQ=="; //2001106709
	
    // 2001104957

    $url = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=$dateKsnet&file_sele=PGLST";

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



    //2001104865
	$urlFor65 = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=$dateKsnet&file_sele=PGLST";
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
	$urlFor09 = "https://pgims.ksnet.co.kr/payinfo/TradDataSvlt.do?&target_date=$dateKsnet&file_sele=PGLST";
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
	$urlForAllat = "https://dn.mcash.co.kr/support/dn_file.php?id=unicityit02&pwd=Mingu1004!&no=42319&date=$sDate";
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


//Allat unikrbo	
	$urlForAllatBo = "https://dn.mcash.co.kr/support/dn_file.php?id=unicityit02&pwd=Mingu1004!&no=42211&date=$sDate";
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
	unset($explodesBo[0]);
	array_unshift($explodesBo, "allatNo","initial");
	
	$arrayDataBo=array_chunk($explodesBo, 11);
	$countForAllatBo=count($arrayDataBo);

 	$allatBo = array();
	$arrayBo = array();


    // 효성 실시간 출금

   $hyUrl = "https://api.hyosungcms.co.kr/v1/custs/unicity0/payments/realcms?fromPaymentDate=$sDate&toPaymentDate=$sDate";
   $hyKey = "hK1fmDEKCSrj55zn:xHJjExZxIYUDZSSO"; 

   $ch = curl_init();                                 
	curl_setopt($ch, CURLOPT_URL, $hyUrl);         
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);    
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:VAN '.$hyKey));
	$hyResponse = curl_exec($ch);
	curl_close($ch);

	$hyResult = json_decode($hyResponse,true);
    $hyCount = $hyResult['totalCount'];


    	//infoTrax

        // 토큰 생성
	  $id='kr_ar';
      $pw='Nrwk%vOSo&ht&fJ!sxvVyjIwy8t4';
  
      //$id='minguk';
      //$pw='Mg555555';
    
  
      $ch = curl_init();
      $url = "https://hydra.unicity.net/v5a/loginTokens?expand=whoami";
      $sendData = array();
      $sendData["source"] = array("medium" => "Template");
      $sendData["type"] = "base64";
      $sendData["value"] = base64_encode("{$id}:{$pw}");
      $sendData["namespace"] = "https://hydra.unicity.net/v5a/employees";
      
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
      $infoTraxUrl = "https://hydra.unicity.net/v5a/reports/dailysalesreport?market=KR&dateCreated=$dateInfotrax";
  
      $ch = curl_init();                                 
      curl_setopt($ch, CURLOPT_URL, $infoTraxUrl);         
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);    
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer '.$token));
      $responseInfo = curl_exec($ch);
      $json_resultInfo = json_decode($responseInfo, true);
      $infoCnt=count($json_resultInfo);
      curl_close($ch);
 
?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:html="http://www.w3.org/TR/REC-html40"> 
    <Styles>
      <Style ss:ID="s77">
        <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
        <Borders>
          <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
          <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
          <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
          <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
        </Borders>
        <Interior ss:Color="#F2F2F2" ss:Pattern="Solid"/>
      </Style>
    </Styles>  
    <Worksheet ss:Name="효성">
      <Table>
        <Row>
          <Cell><Data ss:Type="String">상태</Data></Cell>
          <Cell><Data ss:Type="String">주문번호</Data></Cell>
          <Cell><Data ss:Type="String">회원번호</Data></Cell>
          <Cell><Data ss:Type="String">이름</Data></Cell>
          <Cell><Data ss:Type="String">날짜</Data></Cell>
          <Cell><Data ss:Type="String">금액</Data></Cell>
          <Cell><Data ss:Type="String">출금상태</Data></Cell>  
        </Row>
        <?
          	for($i = 0 ; $i < $hyCount ; $i++){	
              $hyStatus = $hyResult['payments'][$i]['status'];
              $hyOrderNum = $hyResult['payments'][$i]['transactionId'];
              $hyMemberId = $hyResult['payments'][$i]['memberId'];
              $hyMemberName = $hyResult['payments'][$i]['memberName'];
              $hyPaymentDate = $hyResult['payments'][$i]['paymentDate'];
              $hyAmount= $hyResult['payments'][$i]['actualAmount'];
              $hyMessage= $hyResult['payments'][$i]['result']['message'];

              $data = $hyOrderNum ;
              list($mNum, $trNum, $hyOrderNum) = explode("_", $data, 3);

              if($hyMessage=='정상'|| $hyMessage != 0){

                $hyTotalAmount += $hyAmount;
                $hyTotalCount += 1;

              }
        ?>
        <Row> 
              <Cell><Data ss:Type="String"><?echo $hyStatus?></Data></Cell>
              <Cell><Data ss:Type="String"><?echo $hyOrderNum?></Data></Cell>
              <Cell><Data ss:Type="String"><?echo $hyMemberId?></Data></Cell>
              <Cell><Data ss:Type="String"><?echo $hyMemberName?></Data></Cell>
              <Cell><Data ss:Type="String"><?echo $hyPaymentDate?></Data></Cell>
              <Cell><Data ss:Type="String"><?echo number_format($hyAmount)?></Data></Cell>
              <Cell><Data ss:Type="String"><?echo $hyMessage?></Data></Cell>
        </Row>
        <?
            }
        ?>
      </Table>  
    </Worksheet>  
    <Worksheet ss:Name="2001104957">
      <Table>
        <Row>
          <Cell><Data ss:Type="String">상점번호</Data></Cell>
          <Cell><Data ss:Type="String">신청일자</Data></Cell>
          <Cell><Data ss:Type="String">주문번호</Data></Cell>
          <Cell><Data ss:Type="String">이니셜</Data></Cell>
          <Cell><Data ss:Type="String">주문자</Data></Cell>
          <Cell><Data ss:Type="String">금액</Data></Cell>
          <Cell><Data ss:Type="String">승인번호</Data></Cell>
          <Cell><Data ss:Type="String">거래번호</Data></Cell>
          <Cell><Data ss:Type="String">매입사</Data></Cell>
          <Cell><Data ss:Type="String">승인구분</Data></Cell>
        </Row>
     
  <?      $bcCount97 = 0;	

	      for($i = 0 ; $i < $count ; $i++){	

              //JSONArray에서 [$i] 번째 행의 JSONObject [' '] 항목의 값을 가져옴
              $deal_start_date = $result['data'][$i]['deal_start_date']; //거래일자
              $pg_deal_numb = $result['data'][$i]['pg_deal_numb']; //거래번호
              $pg_deal_numb_j = $result['data'][$i]['pg_deal_numb']; //거래번호
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
              $auth_code1 = $result['data'][$i]['auth_code']; //승인구분

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

              $authCode = substr($auth_code,2,1);
              echo $authCode;
              
              if($authCode=="0"){
                $authChk = "승인"; 
              }else if ($authCode=="1"){
                $authChk = "취소"; 
                 $minusAmount -= $amount;
              }

              if($pg_deal_numb ==  $pg_deal_numb_j){
                $dobuleCount += 1;
              }else{
                $dobuleCount = 0;
              }

              //매입사 코드
              if($authCode=="0"){	
                if($purc_gove_code=='010001'){
                    $card_code ='비씨카드';
                    $bcCount97 +=1;	
                    $bcAmount97 += $amount;	
                }else if($purc_gove_code=='010002'){
                    $card_code ='국민카드';
                    $kCount97 +=1;	
                    $kAmount97 +=	$amount;		
                }else if($purc_gove_code=='010004'){
                    $card_code ='삼성카드';
                    $samCount97 +=1;	
                    $samAmount97 +=	$amount;			
                }else if($purc_gove_code=='010005'){
                    $card_code ='신한카드';						
                    $sinCount97 +=1;	
                    $sinAmount97 +=	$amount;			
                }else if($purc_gove_code=='010008'){
                    $card_code ='현대카드';						
                    $hyCount97 +=1;			
                    $hyAmount97 +=	$amount;	
                }else if($purc_gove_code=='010009'){
                    $card_code ='롯데아멕스';		
                    $lotCount97 +=1;		
                    $lotAmount97 +=	$amount;		
                }else if($purc_gove_code=='010014'){
                    $card_code ='우리은행';		
                    $bcCount97 +=1;	
                    $bcAmount97 +=	$amount;				
                }else if($purc_gove_code=='010015'){
                    $card_code ='농협';			
                    $nhCount97 += 1;			
                    $nhAmount97 +=	$amount;	
                }else if($purc_gove_code=='010016'){
                    $card_code ='제주은행';						
                    $bcCount97 +=1;	
                    $bcAmount97 +=	$amount;	
                }else if($purc_gove_code=='010017'){
                    $card_code ='광주은행';	
                    $bcCount97 +=1;					
                    $bcAmount97 +=	$amount;	
                }else if($purc_gove_code=='010018'){
                    $card_code ='전북은행';					
                    $bcCount97 +=1;	
                    $bcAmount97 +=	$amount;	
                }else if($purc_gove_code=='010021'){
                    $card_code ='롯데카드';						
                    $lotCount97 +=1;	
                    $lotAmount97 +=	$amount;	
                }else if($purc_gove_code=='010024'){
                    $card_code ='하나은행';					
                    $hanaCount97 +=1;	
                    $hanaAmount97 +=	$amount;	
                }else if($purc_gove_code=='010040'){
                    $card_code ='현대백화점';						
                    $hyCount97 +=1;	
                    $hyAmount97 +=	$amount;	
                }else if($purc_gove_code=='010003'){
                    $card_code ='외환카드';						
                    $ehCount97 +=1;	
                    $ehAmount97 +=	$amount;	
                }else if($purc_gove_code=='030020'){
                    $card_code ='비씨카드';						
                    $bcCount97 +=1;	
                    $bcAmount97 +=	$amount;	
                }

                if(($order_last=='A') && ($matches[1]=='YKS')){
                    $who = "손연경";
                    $yksAmount +=$amount;
                    $yksCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='BKK')){
                    $who = "김보경";
                    $bkkAmount +=$amount;
                    $bkkCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='HYJ')){
                    $who = "조혜양";
                    $hyjAmount +=$amount;
                    $hyjCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='SHK')){
                    $who = "강새해";
                    $shkAmount +=$amount;
                    $shkCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='HAK')){
                    $who = "강현아";
                    $hakAmount +=$amount;
                    $hakCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='SAK')){
                    $who = "김수아";
                    $sakAmount +=$amount;
                    $sakCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='KIM')){
                    $who = "김순정";
                    $kimAmount +=$amount;
                    $kimCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='YMK')){
                    $who = "김유미";
                    $ymkAmount +=$amount;
                    $ymkCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='YSK')){
                    $who = "김유선";
                    $yskAmount +=$amount;
                    $yskCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='JEK')){
                    $who = "김정환";
                    $jekAmount +=$amount;
                    $jekCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='JJK')){
                    $who = "김지민";
                    $jjkAmount +=$amount;
                    $jjkCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='GPA')){
                    $who = "박가영";
                    $gpaAmount +=$amount;
                    $gpaCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='KSP')){
                    $who = "박금순";
                    $kspAmount +=$amount;
                    $kspCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='SHP')){
                    $who = "박송현";
                    $shpAmount +=$amount;
                    $shpCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='YSP')){
                    $who = "박영숙";
                    $yspAmount +=$amount;
                    $yspCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='JMP')){
                    $who = "박종미";
                    $jmpAmount +=$amount;
                    $jmpCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='HNP')){
                    $who = "박하나";
                    $hnpAmount +=$amount;
                    $hnpCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='JSO')){
                    $who = "오정숙";
                    $jsoAmount +=$amount;
                    $jsoCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='KML')){
                    $who = "이경미";
                    $kmlAmount +=$amount;
                    $kmlCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='HGL')){
                    $who = "이현경";
                    $hglAmount +=$amount;
                    $hglCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='HRL')){
                    $who = "이희라";
                    $hrlAmount +=$amount;
                    $hrlCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='BKJ')){
                    $who = "정보겸";
                    $bkjAmount +=$amount;
                    $bkjCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='BUJ')){
                    $who = "조별희";
                    $bujAmount +=$amount;
                    $bujCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='YCH')){
                    $who = "천영";
                    $ychAmount +=$amount;
                    $ychCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='SYH')){
                    $who = "함수연";
                    $syhAmount +=$amount;
                    $syhCount +=1;
                }else if(($order_last=='A') && ($matches[1]=='KC1')){
                    $who = "CS1 - KR CS";
                    $kc1Amount +=$amount;
                    $kc1Count +=1;
                }else if(($order_last=='A') && ($matches[1]=='KC2')){
                    $who = "CS2 - KR CS";
                    $kc2Amount +=$amount;
                    $kc2Count +=1;
                }else if(($order_last=='A') && ($matches[1]=='KC3')){
                    $who = "CS3 - KR CS";
                    $kc3Amount +=$amount;
                    $kc3Count +=1;
                }else if($order_last=='M'){
                    $mCount +=1; 
                    $mAmount +=$amount; 
                }else{
                    $internetAmount +=$amount;
                    $internetCount +=1;
                }


      
                $totalAmount97 = $bcAmount97+$ehAmount97+$hyAmount97+$hanaAmount97+$lotAmount97+$nhAmount97+$sinAmount97+$samAmount97+$kAmount97;
                $totalCount97 = $bcCount97+$ehCount97+$hyCount97+$hanaCount97+$lotCount97+$nhCount97+$sinCount97+$samCount97+$kCount97;


              }

              /*
              if($order_last=='M'){
                $mCount +=1; 
                  $mAmount +=$amount; 
              }
            */
	?>
        <Row>   
              <Cell><Data ss:Type="String"><?echo $shop_id ?></Data></Cell>
              <Cell><Data ss:Type="String"><?echo $deal_start_date?></Data></Cell>
            <?if($order_last == 'A'){?>
              <Cell><Data ss:Type="String"><?echo print_r($orderArr[0],true)?></Data></Cell>
            <?}else if($order_last == 'M'){ ?>	
              <Cell><Data ss:Type="String"><?echo print_r($orderArrM[0],true)?></Data></Cell>
            <?}else{?>
              <Cell><Data ss:Type="String"><?echo $order_numb?></Data></Cell>
             <?}?>	  
             <Cell><Data ss:Type="String"><?echo $matches[1]?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo $order_pers_name?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo number_format($amount)?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo $appr_numb?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo $pg_deal_numb?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo $card_code?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo $authChk?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo $auth_code1?></Data></Cell>
             <Cell><Data ss:Type="String"><?echo $order_last?></Data></Cell>
       

      
            
        </Row>
  <?}?>
      </Table>
    </Worksheet>
    <Worksheet ss:Name="2001104865">
      <Table>
        <Row>
        <Cell><Data ss:Type="String">상점번호</Data></Cell>
        <Cell><Data ss:Type="String">신청일자</Data></Cell>
        <Cell><Data ss:Type="String">주문번호</Data></Cell>
        <Cell><Data ss:Type="String">주문자</Data></Cell>
        <Cell><Data ss:Type="String">금액</Data></Cell>
        <Cell><Data ss:Type="String">승인번호</Data></Cell>
        <Cell><Data ss:Type="String">매입사</Data></Cell>

        </Row>
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

          $authCode95 = substr($auth_code,2,1);
          echo $authCode;

          //매입사 코드
          if($authCode95=="0"){									
            if($purc_gove_code=='010001'){
                $card_code ='비씨카드';		
                $bcCount95 +=1;
                $bcAmount95 +=	$amount;		
            }else if($purc_gove_code=='010002'){
                    $card_code ='국민카드';
                $kCount95 +=1;	
                $kAmount95 +=	$amount;	
            }else if($purc_gove_code=='010004'){
                $card_code ='삼성카드';
                $samCount95 +=1;	
                $samAmount95 +=	$amount;	
            }else if($purc_gove_code=='010005'){
                $card_code ='신한카드';						
                $sinCount95 +=1;	
                $sinAmount95 +=	$amount;	
            }else if($purc_gove_code=='010008'){
                $card_code ='현대카드';						
                $hyCount95 +=1;
                $hyAmount95 +=	$amount;		
            }else if($purc_gove_code=='010009'){
                $card_code ='롯데아멕스';					
                $lotCount95 +=1;	
                $lotAmount95 +=	$amount;	
            }else if($purc_gove_code=='010014'){
                $card_code ='우리은행';
                $bcCount95 +=1;		
                $bcAmount95 +=	$amount;					
            }else if($purc_gove_code=='010015'){
                $card_code ='농협';					
                $nhCount95 +=1;	
                $nhAmount95 +=	$amount;	
            }else if($purc_gove_code=='010016'){
                $card_code ='제주은행';						
                $bcCount95 +=1;	
                $bcAmount95 +=	$amount;	
            }else if($purc_gove_code=='010017'){
                $card_code ='광주은행';		
                $bcCount95 +=1;		
                $bcAmount95 +=	$amount;			
            }else if($purc_gove_code=='010018'){
                $card_code ='전북은행';
                $bcCount95 +=1;			
                $bcAmount95 +=	$amount;				
            }else if($purc_gove_code=='010021'){
                $card_code ='롯데카드';			
                $lotCount95 +=1;
                $lotAmount95 +=	$amount;					
            }else if($purc_gove_code=='010024'){
                $card_code ='하나은행';	
                $hanaCount95 +=1;
                $hanaAmount95 +=	$amount;						
            }else if($purc_gove_code=='010040'){
                $card_code ='현대백화점';	
                $hyCount95 +=1;
                $hyAmount95 +=	$amount;							
            }else if($purc_gove_code=='010003'){
                $card_code ='외환카드';	
                $ehCount95 +=1;		
                $ehAmount95 +=	$amount;					
            }else if($purc_gove_code=='030020'){
                $card_code ='비씨카드';						
                $bcCount95 +=1;	
                $bcAmount95 +=	$amount;	
            }
            $totalAmount95 = $bcAmount95+$ehAmount95+$hyAmount95+$hanaAmount95+$lotAmount95+$nhAmount95+$sinAmount95+$samAmount95+$kAmount95;
            $totalCount95 = $bcCount95+$ehCount95+$hyCount95+$hanaCount95+$lotCount95+$nhCount95+$sinCount95+$samCount95+$kCount95;
        } 
  ?>
        <Row>
          <Cell><Data ss:Type="String"><?echo $shop_id ?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $deal_start_date?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $order_numb?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $order_pers_name?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo number_format($amount)?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $appr_numb?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $card_code?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $purc_gove_code?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $totalAmount95 ?></Data></Cell>
        </Row>
  <?}?>      
      </Table>
    </Worksheet>
    <Worksheet ss:Name="2001106709">
      <Table>
        <Row>
        <Cell><Data ss:Type="String">상점번호</Data></Cell>
        <Cell><Data ss:Type="String">신청일자</Data></Cell>
        <Cell><Data ss:Type="String">주문번호</Data></Cell>
        <Cell><Data ss:Type="String">주문자</Data></Cell>
        <Cell><Data ss:Type="String">금액</Data></Cell>
        <Cell><Data ss:Type="String">매입사</Data></Cell>
        </Row>
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
            
                
				if($purc_gove_code=='030003'){
					$card_code ='기업은행';		
                    $bCount +=1;
                    $bAmount +=  $amount;
				}else if($purc_gove_code=='030004'){
					$card_code ='국민은행';					
                    $kCount +=1;	
                    $kAmount += $amount;	
                }else if($purc_gove_code=='030005'){
					$card_code ='하나은행';
                    $hCount +=1;	
                    $hAmount += $amount;					
				}else if($purc_gove_code=='030011'){
					$card_code ='NH농협';	
                    $nCount +=1;
                    $nAmount += $amount;											
				}else if($purc_gove_code=='030019'){
					$card_code ='국민은행';	
                    $kCount +=1;	
                    $kAmount +=$amount;																	
				}else if($purc_gove_code=='030020'){
					$card_code ='우리은행';	
                    $wCount +=1;
                    $wAmount +=$amount;				
				}else if($purc_gove_code=='030023'){
					$card_code ='SC은행';
                    $scCount +=1;
                    $scAmount +=$amount;									
				}else if($purc_gove_code=='030026'){
					$card_code ='신한은행';
                    $sCount +=1;
                    $sAmount +=$amount;									
				}else if($purc_gove_code=='030031'){
					$card_code ='대구은행';
                    $dCount +=1;														
                    $dAmount +=$amount;	
				}else if($purc_gove_code=='030032'){
					$card_code ='부산은행';
                    $buCount +=1;	
                    $buAmount +=$amount;													
				}else if ($purc_gove_code=='030034'){
					$card_code ='광주은행';	
                    $kaCount +=1;													
                    $kaAmount +=$amount;	
				}else if($purc_gove_code=='030037'){
					$card_code ='전북은행';
                    $jeonCount +=1;													
                    $jeonAmount +=$amount;	
				}else if($purc_gove_code=='030039'){
					$card_code ='경남은행';		
                    $keongCount +=1;
                    $keongAmount +=$amount;												
				}else if($purc_gove_code=='030071'){
			        $card_code ='우체국';		
                    $wooCount +=1;
                    $wooAmount +=$amount;												
									}      
                  
                  $totalAmount = $wooAmount + $keongAmount + $jeonAmount + $kaAmount + $buAmount + $dAmount + $sAmount + $scAmount + $wAmount + $kAmount +$nAmount +$hAmount + $bAmount;
                  $totalCount = $wooCount + $keongCount + $jeonCount + $kaCount + + $buCount +  $dCount + $sCount + $scCount + $wCount + $kCount +$nCount +$hCount + $bCount;
        ?>              
        <Row>
          <Cell><Data ss:Type="String"><?echo $shop_id ?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $deal_start_date?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $order_numb?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $order_pers_name?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo number_format($amount)?></Data></Cell>
          <Cell><Data ss:Type="String"><?echo $card_code?></Data></Cell>
        </Row>
        <?}?>
      </Table>
    </Worksheet>
    <Worksheet ss:Name="allatUnikrnt">
      <Table>
        
      <Row>
        <Cell><Data ss:Type="String">주문번호</Data></Cell>
        <Cell><Data ss:Type="String">allat 거래번호</Data></Cell>
        <Cell><Data ss:Type="String">서비스 아이디</Data></Cell>
        <Cell><Data ss:Type="String">거래일자</Data></Cell>
        <Cell><Data ss:Type="String">카드번호</Data></Cell>
        <Cell><Data ss:Type="String">카드사</Data></Cell>
        <Cell><Data ss:Type="String">승인번호</Data></Cell>
        <Cell><Data ss:Type="String">거래금액</Data></Cell>
      </Row>

      <?php
            for($i=1; $i<$countForAllat; $i++){
              $allat = $arrayData[$i];
              //$key = array_search( '<LIST>', $allat );
              //array_splice( $allat, $key, 1 );
          
              $rst = json_encode($array);	

              //$storeOrder = $array["storeOrderNum"];

              $korOrderNum = $allat[1]; 
              $korOrderNum = str_replace('</LIST>', '', $korOrderNum);

              if($allat[6]=='삼성카드'){
                $allatSamCount += 1;
                $allatSamAmount += $allat[8];
              }else if($allat[6]=='국민카드'){
                $allatKokCount += 1;
                $allatKokAmount += $allat[8];
              }else if($allat[6]=='SSC'){
                $allatSSCCount +=1;
                $allatSSCAmount += $allat[8];
              }

              $totalAllatCount = $allatSamCount + $allatKokCount + $allatSSCCount;
              $totalAllatAmount = $allatSamAmount + $allatKokAmount + $allatSSCAmount;
                    
      ?>
      <!-- 20230302 소스(정상작동) -->
        <Row>
            <Cell><Data ss:Type="String"><?echo $korOrderNum?></Data></Cell>
            <Cell><Data ss:Type="String"><?echo $allat[2] ?></Data></Cell>
            <Cell><Data ss:Type="String"><?echo $allat[3] ?></Data></Cell>
            <Cell><Data ss:Type="String"><?echo $allat[4] ?></Data></Cell>
            <Cell><Data ss:Type="String"><?echo $allat[5] ?></Data></Cell>
            <Cell><Data ss:Type="String"><?echo $allat[6] ?></Data></Cell>
            <Cell><Data ss:Type="String"><?echo $allat[7] ?></Data></Cell>
            <Cell><Data ss:Type="String"><?echo $allat[8] ?></Data></Cell>
        </Row>
        <?  } ?>  
      </Table>
    </Worksheet>
    <Worksheet ss:Name="allatUnikrbo">
      <Table>
        <Row>
   
          <Cell><Data ss:Type="String">주문번호</Data></Cell>
          <Cell><Data ss:Type="String">모빌리언스 거래번호</Data></Cell>
          <Cell><Data ss:Type="String">서비스 아이디</Data></Cell>
          <Cell><Data ss:Type="String">거래일시</Data></Cell>
          <Cell><Data ss:Type="String">카드번호</Data></Cell>
          <Cell><Data ss:Type="String">카드사</Data></Cell>
          <Cell><Data ss:Type="String">승인번호</Data></Cell>
          <Cell><Data ss:Type="String">거래금액</Data></Cell>
          <Cell><Data ss:Type="String">이니셜</Data></Cell>
          <Cell><Data ss:Type="String">마지막 구분</Data></Cell>



        </Row>
        
        <?php
              for($i=1; $i<$countForAllatBo; $i++){

                $allatBo = $arrayDataBo[$i];

                $arrayBo["storeSumOrder"]= $korOrderNumBo[0]."_".$allatBo[1]."_".$allatBo[2];
               
            
                $rstBo = json_encode($arrayBo);	

                $newDate = date("Y-m-d", strtotime($arrayBo["payDate"]));

                $korOrderNumBo = $allatBo[0]; 
                $korOrderNumBo = str_replace('</LIST>','', $korOrderNumBo);

                if($allatBo[7]=='삼성카드'){
                   $allatSamCountBo +=1;  
                   $allatSamAmountBo +=$allatBo[9];  
                }else if($allatBo[7]=='국민카드'){
                  $allatKokCountBo +=1;  
                  $allatKokAmountBo +=$allatBo[9];  
                }else if($allatBo[7]=='SSC'){
                  $allatSSCCountBo +=1;  
                  $allatSSCAmountBo +=$allatBo[9];  
                }else if($allatBo[7]=='KBC'){
                  $allatKBCCountBo +=1;  
                  $allatKBCAmountBo +=$allatBo[9];  
                }


                if(($allatBo[2]=='A') && ($allatBo[1]=='YKS')){
                    $who = "손연경";
                    $yksAmountBo +=$allatBo[9];
                    $yksCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='BKK')){
                    $who = "김보경";
                    $bkkAmountBo +=$allatBo[9];
                    $bkkCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='HYJ')){
                    $who = "조혜양";
                    $hyjAmountBo +=$allatBo[9];
                    $hyjCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='SHK')){
                    $who = "강새해";
                    $shkAmountBo +=$allatBo[9];
                    $shkCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='HAK')){
                    $who = "강현아";
                    $hakAmountBo +=$allatBo[9];
                    $hakCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='SAK')){
                    $who = "김수아";
                    $sakAmountBo +=$allatBo[9];
                    $sakCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='KIM')){
                    $who = "김순정";
                    $kimAmountBo +=$allatBo[9];
                    $kimCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='YMK')){
                    $who = "김유미";
                    $ymkAmountBo +=$allatBo[9];
                    $ymkCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='YSK')){
                    $who = "김유선";
                    $yskAmountBo +=$allatBo[9];
                    $yskCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='JEK')){
                    $who = "김정환";
                    $jekAmountBo +=$allatBo[9];
                    $jekCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='JJK')){
                    $who = "김지민";
                    $jjkAmountBo +=$allatBo[9];
                    $jjkCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='GPA')){
                    $who = "박가영";
                    $gpaAmountBo +=$allatBo[9];
                    $gpaCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='KSP')){
                    $who = "박금순";
                    $kspAmountBo +=$allatBo[9];
                    $kspCountBo +=1;
                }else if(($allatBo[2]='A') && ($allatBo[1]=='SHP')){
                    $who = "박송현";
                    $shpAmountBo +=$allatBo[9];
                    $shpCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='YSP')){
                    $who = "박영숙";
                    $yspAmountBo +=$allatBo[9];
                    $yspCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='JMP')){
                    $who = "박종미";
                    $jmpAmountBo +=$allatBo[9];
                    $jmpCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='HNP')){
                    $who = "박하나";
                    $hnpAmountBo +=$allatBo[9];
                    $hnpCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='JSO')){
                    $who = "오정숙";
                    $jsoAmountBo +=$allatBo[9];
                    $jsoCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='KML')){
                    $who = "이경미";
                    $kmlAmountBo +=$allatBo[9];
                    $kmlCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='HGL')){
                    $who = "이현경";
                    $hglAmountBo +=$allatBo[9];
                    $hglCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='HRL')){
                    $who = "이희라";
                    $hrlAmountBo +=$allatBo[9];
                    $hrlCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='BKJ')){
                    $who = "정보겸";
                    $bkjAmountBo +=$allatBo[9];
                    $bkjCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='BUJ')){
                    $who = "조별희";
                    $bujAmountBo +=$allatBo[9];
                    $bujCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='YCH')){
                    $who = "천영";
                    $ychAmountBo +=$allatBo[9];
                    $ychCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='SYH')){
                    $who = "함수연";
                    $syhAmountBo +=$allatBo[9];
                    $syhCountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='KC1')){
                    $who = "CS1 - KR CS";
                    $kc1AmountBo +=$allatBo[9];
                    $kc1CountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='KC2')){
                    $who = "CS2 - KR CS";
                    $kc2AmountBo +=$allatBo[9];
                    $kc2CountBo +=1;
                }else if(($allatBo[2]=='A') && ($allatBo[1]=='KC3')){
                    $who = "CS3 - KR CS";
                    $kc3AmountBo +=$allatBo[9];
                    $kc3CountBo +=1;
                }else if($allatBo[2]=='M'){
                    $mCountBo +=1; 
                    $mAmountBo +=$allatBo[9]; 
                }

                $totalAllatCountBo = $allatSamCountBo + $allatKokCountBo + $allatSSCCountBo+ $allatKBCCountBo;
                $totalAllatAmountBo = $allatSamAmountBo + $allatKokAmountBo + $allatSSCAmountBo + $allatKBCAmountBo;
          

            ?>

        <Row>

          <Cell><Data ss:Type="String"><?php echo $korOrderNumBo ?></Data></Cell>
  
          <Cell><Data ss:Type="String"><?php echo $allatBo[3] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[4] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[5] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[6] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[7] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[8] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[9] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[1] ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatBo[2] ?></Data></Cell>
        </Row>
        <?php }?>
      </Table>
    </Worksheet>
    <Worksheet ss:Name="infoTrax">
      <Table>
        <Row>
          <Cell><Data ss:Type="String">Release_date</Data></Cell>
          <Cell><Data ss:Type="String">Date</Data></Cell>
          <Cell><Data ss:Type="String">Order_Type</Data></Cell>
          <Cell><Data ss:Type="String">usrr</Data></Cell>
          <Cell><Data ss:Type="String">Auth</Data></Cell>
          <Cell><Data ss:Type="String">Paymenmt_Type</Data></Cell>
          <Cell><Data ss:Type="String">Order_Number</Data></Cell>
          <Cell><Data ss:Type="String">Amount</Data></Cell>
          <Cell><Data ss:Type="String">Payments</Data></Cell>
        </Row>
<?php  for($i = 0 ; $i < $infoCnt ; $i++){	
         $releaseDate = $json_resultInfo[$i]['Release Date'];     
         $date = $json_resultInfo[$i]['Date'];     
         $orderType = $json_resultInfo[$i]['Order Type'];     
         $user = $json_resultInfo[$i]['User'];  
         $auth = $json_resultInfo[$i]['Auth'];              
         $payMentType = $json_resultInfo[$i]['Payment Type'];  
         $orderNum = $json_resultInfo[$i]['Order #'];     
         $paymentAmount = $json_resultInfo[$i]['Payment Amount'];     
         $payments = $json_resultInfo[$i]['Payments'];     

         $paymentAmount = substr($paymentAmount,0,-1);
        if($releaseDate==$dateInfotrax){
            if(($user == "*ms" || $user == "*AS")&& ($payMentType=="Credit Card Event")){
                $infoPayForCard +=$paymentAmount ;			
            }

        
            if(($user == "*ms" || $user == "*AS")&& ($payMentType=="BW_RECORD")){
                $infoPayForBw +=$paymentAmount ;			
            }

            if(($user == "YKS")&& ($payMentType=="Credit Card Event") || ($user == "YKS")&& ($payMentType=="Teller Credit Card")){
                $yksForCard +=$paymentAmount ;			
            }else if(($user == "BKK")&& ($payMentType=="Credit Card Event") || ($user == "BKK")&& ($payMentType=="Teller Credit Card")){
                $bkkForCard +=$paymentAmount ;			
            }else if(($user == "HYJ")&& ($payMentType=="Credit Card Event") || ($user == "HYJ")&& ($payMentType=="Teller Credit Card")){
                $hyjForCard +=$paymentAmount ;			
            }else if(($user == "SHK")&& ($payMentType=="Credit Card Event") || ($user == "SHK")&& ($payMentType=="Teller Credit Card")){
                $shkForCard +=$paymentAmount ;			
            }else if(($user == "HAK")&& ($payMentType=="Credit Card Event") || ($user == "HAK")&& ($payMentType=="Teller Credit Card")){
                $hakForCard +=$paymentAmount ;			
            }else if(($user == "SAK")&& ($payMentType=="Credit Card Event") || ($user == "SAK")&& ($payMentType=="Teller Credit Card")){
                $sakForCard +=$paymentAmount ;			
            }else if(($user == "KIM")&& ($payMentType=="Credit Card Event") || ($user == "KIM")&& ($payMentType=="Teller Credit Card")){
                $kimForCard +=$paymentAmount ;			
            }else if(($user == "YMK")&& ($payMentType=="Credit Card Event") || ($user == "YMK")&& ($payMentType=="Teller Credit Card")){
                $ymkForCard +=$paymentAmount ;			
            }else if(($user == "YSK")&& ($payMentType=="Credit Card Event") || ($user == "YSK")&& ($payMentType=="Teller Credit Card")){
                $yskForCard +=$paymentAmount ;			
            }else if(($user == "JEK")&& ($payMentType=="Credit Card Event") || ($user == "JEK")&& ($payMentType=="Teller Credit Card")){
                $jekForCard +=$paymentAmount ;			
            }else if(($user == "JJK")&& ($payMentType=="Credit Card Event") || ($user == "JJK")&& ($payMentType=="Teller Credit Card")){
                $jjkForCard +=$paymentAmount ;			
            }else if(($user == "GPA")&& ($payMentType=="Credit Card Event") || ($user == "GPA")&& ($payMentType=="Teller Credit Card")){
                $gpaForCard +=$paymentAmount ;			
            }else if(($user == "KSP")&& ($payMentType=="Credit Card Event") || ($user == "KSP")&& ($payMentType=="Teller Credit Card")){
                $kspForCard +=$paymentAmount ;			
            }else if(($user == "SHP")&& ($payMentType=="Credit Card Event") || ($user == "SHP")&& ($payMentType=="Teller Credit Card")){
                $shpForCard +=$paymentAmount ;			
            }else if(($user == "YSP")&& ($payMentType=="Credit Card Event") || ($user == "YSP")&& ($payMentType=="Teller Credit Card")){
                $yspForCard +=$paymentAmount ;			
            }else if(($user == "JMP")&& ($payMentType=="Credit Card Event") || ($user == "JMP")&& ($payMentType=="Teller Credit Card")){
                $jmpForCard +=$paymentAmount ;			
            }else if(($user == "HNP")&& ($payMentType=="Credit Card Event") || ($user == "HNP")&& ($payMentType=="Teller Credit Card")){
                $hnpForCard +=$paymentAmount ;			
            }else if(($user == "JSO")&& ($payMentType=="Credit Card Event") || ($user == "JSO")&& ($payMentType=="Teller Credit Card")){
                $jsoForCard +=$paymentAmount ;			
            }else if(($user == "KML")&& ($payMentType=="Credit Card Event") || ($user == "KML")&& ($payMentType=="Teller Credit Card")){
                $kmlForCard +=$paymentAmount ;			
            }else if(($user == "HGL")&& ($payMentType=="Credit Card Event") || ($user == "HGL")&& ($payMentType=="Teller Credit Card")){
                $hglForCard +=$paymentAmount ;			
            }else if(($user == "HRL")&& ($payMentType=="Credit Card Event") || ($user == "HRL")&& ($payMentType=="Teller Credit Card")){
                $hrlForCard +=$paymentAmount ;			
            }else if(($user == "BKJ")&& ($payMentType=="Credit Card Event") || ($user == "BKJ")&& ($payMentType=="Teller Credit Card")){
                $bkjForCard +=$paymentAmount ;			
            }else if(($user == "BUJ")&& ($payMentType=="Credit Card Event") || ($user == "BUJ")&& ($payMentType=="Teller Credit Card")){
                $bujForCard +=$paymentAmount ;			
            }else if(($user == "YCH")&& ($payMentType=="Credit Card Event") || ($user == "YCH")&& ($payMentType=="Teller Credit Card")){
                $ychForCard +=$paymentAmount ;			
            }else if(($user == "SYH")&& ($payMentType=="Credit Card Event") || ($user == "SYH")&& ($payMentType=="Teller Credit Card")){
                $syhForCard +=$paymentAmount ;			
            }else if(($user == "KC1")&& ($payMentType=="Credit Card Event") || ($user == "KC1")&& ($payMentType=="Teller Credit Card")){
                $kc1ForCard +=$paymentAmount ;			
            }else if(($user == "KC2")&& ($payMentType=="Credit Card Event") || ($user == "KC2")&& ($payMentType=="Teller Credit Card")){
                $kc2ForCard +=$paymentAmount ;			
            }else if(($user == "KC3")&& ($payMentType=="Credit Card Event") || ($user == "KC3")&& ($payMentType=="Teller Credit Card")){
                $kc3ForCard +=$paymentAmount ;			
            }

        }    


?>
        <Row>
          <Cell><Data ss:Type="String"><?php echo $releaseDate?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $date?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $orderType?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $user?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $auth?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $payMentType?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $orderNum?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $paymentAmount?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $infoPayForCard?></Data></Cell>
        </Row>
<?php }?>        
      </Table>
    </Worksheet>
    <Worksheet ss:Name="합계">
      <Table>
       
        <Row>
          <Cell ss:MergeAcross="7" ss:StyleID="s77"><Data ss:Type="String">마감자료 </Data></Cell>
          
        </Row>
        <Row>
          <Cell><Data ss:Type="String">총 건수:</Data></Cell>
        </Row>  
        <Row>
          <Cell><Data ss:Type="String">날짜:</Data></Cell>
        </Row>  
        <Row>
          <Cell><Data ss:Type="String">담당자:</Data></Cell>
        </Row> 
        <Row>
          <Cell ss:MergeAcross="7" ss:StyleID="s77"><Data ss:Type="String">가상계좌 마감자료</Data></Cell>
        </Row> 
        <Row>
          <Cell><Data ss:Type="String"></Data></Cell>
          <Cell><Data ss:Type="String">결제 카운트</Data></Cell>
          <Cell><Data ss:Type="String">금액</Data></Cell> 
        </Row> 
        <Row>
          <Cell ss:StyleID="s77"><Data ss:Type="String">합계</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $totalCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($totalAmount)?></Data></Cell> 
        </Row>
        <Row>
          
        </Row>  
        
        <Row>
        
        </Row>      
        <?
          
          //카드별 합산

          $nhCardCount =$nhCount97 + $nhCount95;
          $nhCard =$nhAmount97 + $nhAmount95;

          $bcCard = $bcAmount97 + $bcAmount95;
          $bcCardCount =$bcCount97 + $bcCount95;

          $lotCard = $lotAmount97 + $lotAmount95;
          $lotCardCount =$lotCount97 + $lotCount95;

          $sinCard = $sinAmount97 + $sinAmount95;
          $sinCardCount =$sinCount97 + $sinCount95;

          $hanaCard = $hanaAmount97 + $hanaAmount95;
          $hanaCardCount =$hanaCount97 + $hanaCount95;

          $hyCard = $hyAmount97 + $hyAmount95;
          $hyCardCount =$hyCount97 + $hyCount95;

          $kCard = $kAmount97 + $kAmount95;
          $kCardCount =$kCount97 + $kCount95;

          $samCard = $samAmount97 + $samAmount95;
          $samCardCount =$samCount97 + $samCount95;

          $ehCard = $ehAmount97 + $ehAmount95;
          $ehCardCount =$ehCount97 + $ehCount95;

  
          
          $totalKsnetAmount = $totalAmount97 + $totalAmount95;
          $totalKsnetCount = $totalCount97 + $totalCount95;



        ?>    

        <Row>
          <Cell><Data ss:Type="String">농협카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $nhCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($nhCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">BC카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $bcCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($bcCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">롯데카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $lotCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($lotCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">신한카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $sinCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($sinCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">하나카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $hanaCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($hanaCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">현대카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $hyCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($hyCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">국민카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $kCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($kCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">외환카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $ehCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($ehCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell><Data ss:Type="String">삼성카드 전체</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $samCardCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($samCard)?></Data></Cell> 
        </Row>
        <Row>
          <Cell ss:StyleID="s77"><Data ss:Type="String">ksnet 전체 </Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $totalKsnetCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($totalKsnetAmount)?></Data></Cell> 
        </Row>
        <Row>
        </Row>
        <Row>
        </Row>
        <Row>
          <Cell ss:MergeAcross="7" ss:StyleID="s77"><Data ss:Type="String">KG 모빌리언스 인터넷 마감(unikrnt)</Data></Cell>
        </Row>
        <Row>
          <Cell><Data ss:Type="String">삼성카드</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatSamCount ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo number_format($allatSamAmount)?></Data></Cell> 
        </Row>
        <Row>
            <Cell><Data ss:Type="String">국민카드</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo $allatKokCount ?></Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($allatKokAmount) ?></Data></Cell>
        </Row>
        <Row>
            <Cell><Data ss:Type="String">SSC</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo $allatSSCCount ?></Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($allatSSCAmount) ?></Data></Cell>
        </Row>          

        <Row>
            <Cell><Data ss:Type="String">KBC</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo $allatKBCCount ?></Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($allatKBCAmount) ?></Data></Cell>
        </Row>
        <Row>
            <Cell ss:StyleID="s77"><Data ss:Type="String">총계</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo $totalAllatCount?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($totalAllatAmount)?></Data></Cell>
        </Row>    
        <Row>
        </Row>
        <Row>
        </Row>
        <Row>
            <Cell ss:MergeAcross="7" ss:StyleID="s77"><Data ss:Type="String">KG 모빌리언스 마감(unikrbo)</Data></Cell>
        </Row>
        <Row>  
          <Cell><Data ss:Type="String">삼성카드</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatSamCountBo ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo number_format($allatSamAmountBo)?></Data></Cell> 
        </Row> 

        <Row>
        <Cell><Data ss:Type="String">국민카드</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatKokCountBo ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo number_format($allatKokAmountBo) ?></Data></Cell>
        </Row> 
        <Row>
          <Cell><Data ss:Type="String">SSC</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatSSCCountBo ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo number_format($allatSSCAmountBo) ?></Data></Cell>
        </Row>
        <Row>
          <Cell><Data ss:Type="String">KBC</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $allatKBCCountBo ?></Data></Cell>
          <Cell><Data ss:Type="String"><?php echo number_format($allatKBCAmountBo) ?></Data></Cell>
        </Row> 
        <Row>
          <Cell ss:StyleID="s77"><Data ss:Type="String">총계</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $totalAllatCountBo?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($totalAllatAmountBo)?></Data></Cell> 
        </Row>  
        <Row>
        </Row>
        <Row>
        </Row>  
        <Row>
          <Cell ss:MergeAcross="7" ss:StyleID="s77"><Data ss:Type="String">효성</Data></Cell>
        </Row>  
        <Row>
          <Cell ss:StyleID="s77"><Data ss:Type="String">총계</Data></Cell>
          <Cell><Data ss:Type="String"><?php echo $hyTotalCount?></Data></Cell> 
          <Cell><Data ss:Type="String"><?php echo number_format($hyTotalAmount)?></Data></Cell>   
        </Row>    
        
        <Row>
        </Row>
        <Row>
        </Row>
        <Row>
            <Cell ss:MergeAcross="7" ss:StyleID="s77"><Data ss:Type="String">infoTrax 인터넷 마감</Data></Cell>
        </Row>  
        <Row>
            <Cell><Data ss:Type="String">카드</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($infoPayForCard) ?></Data></Cell> 
        </Row>    
        <Row>
            <Cell><Data ss:Type="String">이체</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($infoPayForBw ) ?></Data></Cell> 
        </Row>  
        <Row>
        </Row>
        <Row>
        </Row>
        <Row>
            <Cell><Data ss:Type="String">이름</Data></Cell>
            <Cell><Data ss:Type="String">Ksnet</Data></Cell>
            <Cell><Data ss:Type="String">Allat</Data></Cell>
            <Cell><Data ss:Type="String">합계</Data></Cell>
            <Cell><Data ss:Type="String">info_card</Data></Cell>
            
        </Row>
            
        <Row>
            <Cell><Data ss:Type="String">손연경</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($yksAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($yksAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $yksAmountBo + $yksAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $yksForCard ?></Data></Cell> 
        </Row>    

        <Row>
            <Cell><Data ss:Type="String">김보경</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($bkkAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($bkkAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $bkkAmountBo + $bkkAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $bkkForCard ?></Data></Cell> 
        </Row>    

        <Row>
            <Cell><Data ss:Type="String">조혜양</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($hyjAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($hyjAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hyjAmountBo + $hyjAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hyjForCard ?></Data></Cell> 
        </Row>    

        <Row>
            <Cell><Data ss:Type="String">강새해</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($shkAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($shkAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $shkAmountBo + $shkAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $shkForCard ?></Data></Cell> 
        </Row>    

        <Row>
            <Cell><Data ss:Type="String">강현아</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($hakAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($hakAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hakAmountBo + $hakAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hakForCard ?></Data></Cell> 
        </Row>    
    
        <Row>
            <Cell><Data ss:Type="String">김수아</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($sakAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($sakAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $sakAmountBo + $sakAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $sakForCard ?></Data></Cell> 
        </Row>    

        <Row>
            <Cell><Data ss:Type="String">김순정</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($kimAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($kimAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kimAmountBo + $kimAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kimForCard ?></Data></Cell> 
        </Row>    

        <Row>
            <Cell><Data ss:Type="String">김유미</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($ymkAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($ymkAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $ymkAmountBo + $ymkAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $ymkForCard ?></Data></Cell> 
        </Row>    

        <Row>
            <Cell><Data ss:Type="String">김유선</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($yskAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($yskAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $yskAmountBo + $yskAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $yskForCard ?></Data></Cell> 
        </Row> 

        <Row>
            <Cell><Data ss:Type="String">김정환</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($jekAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($jekAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $jekAmountBo + $jekAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $jekForCard ?></Data></Cell> 
        </Row> 
        
        <Row>
            <Cell><Data ss:Type="String">김지민</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($jjkAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($jjkAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $jjkAmountBo  + $jjkAmount?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $jjkForCard ?></Data></Cell> 
        </Row> 

        <Row>
            <Cell><Data ss:Type="String">박가영</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($gpaAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($gpaAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $gpaAmountBo + $gpaAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $gpaForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">박금순</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($kspAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($kspAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kspAmountBo + $kspAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kspForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">박송현</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($shpAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($shpAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $shpAmountBo + $kspAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $shpForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">박영숙</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($yspAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($yspAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $yspAmountBo + $kspAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $yspForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">박종미</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($jmpAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($jmpAmountBo) ?></Data></Cell>
            <Cell><Data ss:Type="String"><?php echo $jmpAmountBo + $jmpAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $jmpForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">박하나</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($hnpAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($hnpAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hnpAmountBo + $hnpAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $hnpForCard ?></Data></Cell> 
        </Row>
        
        <Row>
            <Cell><Data ss:Type="String">오정숙</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($jsoAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($jsoAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $jsoAmountBo + $jsoAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $jsoForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">이경미</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($kmlAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($kmlAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kmlAmountBo + $kmlAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $kmlForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">이현경</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($hglAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($hglAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hglAmountBo + $hglAmount ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hglForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">이희라</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($hrlAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($hrlAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $hrlAmountBo + $hrlAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $hrlForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">정보겸</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($bkjAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($bkjAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $bkjAmountBo + $bkjAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $bkjForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">조별희</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($bujAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($bujAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $bujAmountBo + $bujAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $bujForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">천영</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($ychAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($ychAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $ychAmountBo + $ychAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $ychForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">함수연</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($syhAmount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($syhAmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $syhAmountBo + $syhAmount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $syhForCard ?></Data></Cell> 
        </Row>

        <Row>
            <Cell><Data ss:Type="String">krcs1</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($kc1Amount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($kc1AmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kc1AmountBo + $kc1Amount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $kc1ForCard ?></Data></Cell> 
        </Row>
        <Row>
            <Cell><Data ss:Type="String">krcs2</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($kc2Amount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($kc2AmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kc2AmountBo + $kc2Amount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $kc2ForCard ?></Data></Cell> 
        </Row>
        <Row>
            <Cell><Data ss:Type="String">krcs3</Data></Cell>
            <Cell><Data ss:Type="String"><?php echo number_format($kc3Amount) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo number_format($kc3AmountBo) ?></Data></Cell> 
            <Cell><Data ss:Type="String"><?php echo $kc3AmountBo + $kc3Amount ?></Data></Cell>  
            <Cell><Data ss:Type="String"><?php echo $kc3ForCard ?></Data></Cell> 
        </Row>
      </Table>
    </Worksheet>
</Workbook>    
