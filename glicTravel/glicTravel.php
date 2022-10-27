<?php 	include "includes/config/common_functions.php";
		
?>
<? 

    include "includes/config/config.php";
    include "includes/config/nc_config.php";
	include "./includes/AES.php";



	/*
	$distID = $_POST['fId'];
	$distName = $_POST['fName'];
	$rsponsorId = $_POST['sponsorId'];
	$rsponsorName = $_POST['sponsorName'];
	$fAddress = $_POST['fAddress'];
	$entryDate = $_POST['entryDate'];
*/
	$loginId= $_GET['glicId'];


		$sumQuery = "select sum(member) tot from tb_glicTravel where select_date=0804 and flagUD is null ";

		$result = mysql_query($sumQuery);
		$list = mysql_fetch_array($result);

		$tot = $list[tot];



	
		$sumQuery2 = "select sum(member) tot2 from tb_glicTravel where select_date=0805 and flagUD is null ";

		$result2 = mysql_query($sumQuery2);
		$list2 = mysql_fetch_array($result2);

		$tot2 = $list2[tot2];

	


	?>
	
	
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>GLIC Travel pack 예약</title>
		<meta name="description" content="" />
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
		<link rel="stylesheet" type="text/css" href="./css/joo.css" />
		<style> 
			#mask {
				position: absolute;
				left: 0;
				top: 0;
				z-index: 999;
				background-color: #000000;
				display: none; }
		
			.layerpop {
				display: none;
				z-index: 1000;
				border: 2px solid #ccc;
				background: #fff;
				cursor: move; 
				position: fixed;
				left: 50%;
				top: 50%;
				width: 70%; /*가로길이 설정은 여기서*/
				margin-left: -1%; /*width의 반만큼 음수로*/
				height: 90%; /*세로길이 설정은 여기서*/
				margin-top: -150px; /*height의 반만큼 음수로*/
				z-index: 1000;  
				overflow-y:auto;
				-webkit-overflow-scrolling:touch;}

			.layerpop_area .title {
				padding: 10px 10px 10px 10px;
				border: 0px solid #aaaaaa;
				background: #f1f1f1;
				color: #3eb0ce;
				font-size: 1.3em;
				font-weight: bold;
				line-height: 24px; }

			.layerpop_area .layerpop_close {
				width: 40px;
				height: 25px;
				display: block;
				position: absolute;
				top: 10px;
				right: 10px;}

			.layerpop_area .layerpop_close:hover {
				
				cursor: pointer; }

			.layerpop_area .content {
				width: 96%;    
				margin: 2%;
				color: #828282; }         
        </style>
	</head>
	<body>
		<div class="wrapper" >
			<div class="main_wrapper">
				<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
				</div>
				<div class="main_box">
					<div class="main_inner_box">
						<div class="main_top">
							<h1>
								<span>GLIC 2022 Travel Package 예약</span>
							</h1>
						</div>
						<form name="sendForm" method="post">
							<input type="hidden" name="custHref" value="">
							<input type="hidden" name="expireDate" value="">
						
							
							<div class="wrap_input">
								<div class="member">
									<h4 style="margin-top: 9px;">신청 정보 입력&nbsp;&nbsp;&nbsp;&nbsp;</h4><br/>
									<h2 style="float: left; margin-top: 9px;">회원 번호&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="distID" id="distID" value="<?php echo $loginId?>" readonly="readonly"  style="width: 30%;  background-color: #d1d1d1;"; />
									</div>
									<div style="height: 5px;"></div>	
									<h2 style="float: left; margin-top: 9px;">회원 성명&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="distName" id="distName"  value="" readonly="readonly"  style="width: 30%;background-color: #d1d1d1;" />
									</div>
									<div style="height: 5px;"></div>	
									<h2 style="float: left; margin-top: 9px;">전화번호 &nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="phone" id="phone"  value="" maxlength='11' style="width: 30%;"oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
									</div>
									<div style="height: 5px;"></div>
									<h2 style="float: left; margin-top: 9px;">참석 인원&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">	
										<select name="attendP" id="attendP" title="참석인원" style="width:70px; height: 40px;">
											<option value="" selected>선택</option>
											<option value="1" >1명</option>
											<option value="2">2명</option>
										</select>
									</div>
									<div style="height: 5px;"></div>
									<h2 style="float: left; margin-top: 9px;">신청 날짜&nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">	
										<select name="selectDate" id="selectDate" title="신청날짜" style="width:140px; height: 40px;">
											<option value="" selected>선택</option>
											<option value="0804">선택1) 8월4일 출발</option>
											<option value="0805">선택2) 8월5일 출발</option>
										</select>
									</div>
									<div style="height: 2px;"></div>
									<h4 style="margin-top: 9px;">결제정보 입력&nbsp;&nbsp;&nbsp;&nbsp;</h4>
									<h2 style="margin-top: 9px;font-size:medium;color:blue;">(※ 카드 결제는 6월예정)</h2>
									<h2 style="margin-top: 9px;">(※ 카드 정보가 정확하지 않을 시 예약이 취소 될 수 있습니다)</h2>
									
									<div style="height: 15px;"></div>
									<h2 style="float: left; margin-top: 9px;">카드선택 &nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">	
										<select name="selectCard" id="selectCard" title="카드선택" style="width:130px; height: 40px;">
											<option value="" selected>선택</option>
											<option value="bc">BC카드</option></option>
											<option value="kb">국민카드</option>
											<option value="ss">삼성카드</option>
											<option value="sh">수협카드</option>
											<option value="jb">전북카드</option>
											<option value="kj">광주카드</option>
											<option value="hd">현대카드</option>
											<option value="lt">롯데카드</option>
											<option value="sinhan">신한카드</option>
											<option value="ct">시티카드</option>
											<option value="nh">농협카드</option>
											<option value="wo">우리카드</option>
											<option value="ha">하나카드</option>

										</select>
									</div>	
									<div style="height: 5px;"></div>
									<h2 style="float: left; margin-top: 9px;">카드번호 &nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="cardNumber" id="cardNumber" value="" style="width: 60%" maxlength='16' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/>
									</div>
									<h2 style="float: left; margin-top: 9px;">카드할부 &nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">	
										<select name="installment" id="installment" title="카드할부" style="width:130px; height: 40px;">
											<option value="" selected>선택</option>
											<option value="0">일시불</option></option>
											<option value="1">1개월</option>
											<option value="2">2개월</option>
											<option value="3">3개월</option>
											<option value="4">4개월</option>
											<option value="5">5개월</option>
											<option value="6">6개월</option>
											<option value="7">7개월</option>
											<option value="8">8개월</option>
											<option value="9">9개월</option>
											<option value="10">10개월</option>
											<option value="11">11개월</option>
											<option value="12">12개월</option>
										</select>
									</div>	
									<div style="height: 5px;"></div>
									<h2 style="float: left; margin-top: 9px;">유효기간 &nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<!--<input type="text"  name="expireDate" id="expireDate" value="" style="width: 30%" placeholder="MMYY" maxlength='5' onkeyup="inputNumberFormat(this)"/>-->
										<select name="month" id="month" title="월" class="select w80">
											<option value="" selected>월</option>
											
												<option value="01">1</option>
												<option value="02">2</option>
												<option value="03">3</option>
												<option value="04">4</option>
												<option value="05">5</option>
												<option value="06">6</option>
												<option value="07">7</option>
												<option value="08">8</option>
												<option value="09">9</option>
												<option value="10">10</option>
												<option value="11">11</option>
												<option value="12">12</option>

											</select>
										<select name="year" id="year" title="년도" class="select w80"></select>    
									</div>	
									<div style="height: 5px;"></div>
									<h2 style="float: left; margin-top: 9px;">생년월일 &nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="text"  name="birthDay" id="birthDay" value="" style="width: 30%" placeholder="YYMMDD" maxlength='6' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"/>
									</div>	
									<div style="height: 5px;"></div>
									<h2 style="float: left; margin-top: 9px;">비밀번호(앞두자리) &nbsp;&nbsp;&nbsp;&nbsp;</h2>
									<div class="wrap">
										<input type="password"  name="passWord" id="passWord" value="" style="width: 15%" maxlength='2' />**
									</div>	
								</div>
								<input type="checkbox" size="30px" id="agreeCheck" name="agreeCheck" value=""  onclick="allCheck()"/> 이용 약관 및 동의 사항에 모두 동의 합니다. 

								<h3 id="agreeDetailShow" style="margin-top: 9px;cursor: pointer;font-size: 17px;display:show;" onclick="agreeDetailshow()"><u>약관과 동의 모두 보기 ▼</u></h3><br/>
								<h3 id="agreeDetailNone" style="margin-top: 9px;cursor: pointer;font-size: 17px;display:none;" onclick="agreeDetailnone()"><u>약관과 동의 모두 보기 ▲</u></h3><br/>

								<div id="agreeShow" style="display:none;cursor: pointer;">
									<input type="checkbox" size="30px" id="agreeCheck1" name="agreeCheck1" value="" />[필수]이용약관에 동의 합니다.<a href="javascript:popupOpen(1)"><font color="red"> [보기]</font></a> <br/>
									<input type="checkbox" size="30px" id="agreeCheck2" name="agreeCheck2" value="" />[필수]개인정보의 수집 및 이용에 대해 동의 합니다.<a href="javascript:popupOpen(2)"><font color="red"> [보기]</font></a><br/>  
									<input type="checkbox" size="30px" id="agreeCheck3" name="agreeCheck3" value="" />[필수]개인정보 제3자 제공에 대한 동의 합니다.<a href="javascript:popupOpen(3)"><font color="red"> [보기]</font></a><br/> 
									<input type="checkbox" size="30px" id="agreeCheck4" name="agreeCheck4" value="" />[필수]본 상품은 주문에 의하여 개별적으로 생산되는 것으로 청약철회 등을 인정하면 회사에 회복할 수 없는 중대한 피해가 발생하는 경우로서 청약철회가 제한되는 것과 여행개시일까지는 국외여행표준약관에 따라 청약철회 시 취소료가 부과되고 그 차액이 환불되며, 여행개시 이후에는 청약철회가 제한되는 것에 대해 이해하고 동의합니다. <br/>  		
									<input type="checkbox" size="30px" id="agreeCheck5" name="agreeCheck5" value="" />[필수]본 상품의 실제 여행 이용자가 회사로부터 유예, 해지∙박탈 등의 제재 조치를 받은 사실이 있는 경우, 그 참석 및 이용이 제한되며, 이용이 제한된 경우 구매 대금은 약관 등에 따라 환불 조치되는 것에 대해 이해하고 동의합니다. <br/>  		
									<input type="checkbox" size="30px" id="agreeCheck6" name="agreeCheck6" value="" />[필수]본 상품의 실제 여행 이용자 확정 후(여행사 등록) 이용자 변경은 제한될 수 있음을 이해하고 동의합니다.<br/> 
									<input type="checkbox" size="30px" id="agreeCheck7" name="agreeCheck7" value="" />[필수]예약 및 주문할 상품의 상품명, 가격, 청약철회 정보를 확인하였으며, 예약 및 구매에 동의합니다.

								</div>
								<h3 id="installmentShow" style="margin-top: 3px;cursor: pointer;font-size: 17px;" onclick="installmentShow()">카드 무이자 확인 ▼</h3><br/>
								<h3 id="installmentNone" style="margin-top: 3px;display:none;cursor: pointer;font-size: 17px;" onclick="installmentNone()"><u>카드 무이자 확인 ▲</u></h3><br/>
								<div id="deteilCheck1" style="display:none;">
									<img src="https://www.makelifebetter.co.kr/cardEvent/cardEvent.png" alt="cardEvent" style="max-width:100%; height:auto;" />
								</div>

								<div align="center" id="agreementBut" style="background-color: #B2CCFF;width: 100px; height: 30px; margin-left: 40%; border-radius : 5px; text-shadow: 0px -1px 1px rgba(0,0,0,.3); border: 1px solid #4081AF;box-shadow : inset 0 1px 0 rgba(255,255,255,.3), inset 0 0 2px rgba(255,255,255,.3) 0 1px 2px rgba(0,0,0,.29); cursor: pointer; ">
									<!--<h2 style="margin-top: 5px;font-size:17px;"><a href="javascript:submit()" ><b>예약</b></a></h2>-->
								</div>
								<div id="mask"></div>   		
                           		<div id="layerbox" class="layerpop">
									<article class="layerpop_area">
									
										<h1 style="text-align: center">이용약관</h1>
										<div style="text-align: right"><a href="javascript:popupClose(1);"id="layerbox_close">닫기</a></div>
										<br>
										<h4>제 1 조 목적</h4><p><br></p>
										<p>이 약관은 유니시티 코리아(유)(이하 "유니시티"라 한다)가 운영하는 사이버몰(이하 "몰"이라 한다)에서 제공하는 인터넷 관련 서비스(이하 "서비스"라 한다)를 이용함에 있어 사이버몰과 이용자의 권리•의무 및 책임사항을 규정함을 목적으로 합니다. ※「pc통신, 무선 등을 이용하는 전자거래에 대해서도 그 성질에 반하지 않는 한 이 약관을 준용합니다」.</P>
										<p><br></P>
										<h4>제 2 조 정의</h4><p><br></P>
										<p>①"몰"이란 유니시티가 재화 또는 용역(이하 “재화등”이라 함)을 이용자에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 용역 등을 거래할 수 있도록 설정한 가상의 영업장을 말하며, 아울러 사이버몰을 운영하는 사업자의 의미로도 사용합니다. </P>
										<p>②"이용자"란 "몰"에 접속하여 이 약관에 따라 "몰"이 제공하는 서비스를 받는 회원 및 비회원을 말합니다. </P>
										<p>③‘회원’이라 함은 “몰”에 회원등록을 한 자로서, 계속적으로 “몰”이 제공하는 서비스를 이용할 수 있는 자를 말합니다 ④‘비회원’이라 함은 회원에 가입하지 않고 "몰"이 제공하는 서비스를 이용하는 자를 말합니다.</P>
										<p><br></P>
										<h4>제 3 조 약관의 명시와 설명 및 개정</h4><p><br></P>
										<p>①"몰"은 이 약관의 내용과 상호 및 대표자의 성명, 영업소 소재지 주소(소비자의 불만을 처리할 수 있는 곳의 주소를 포함), 전화번호, 모사전송번호, 전자우편주소, 사업자등록번호, 통신판매업신고번호, 개인정보관리책임자 등을 이용자가 쉽게 알 수 있도록 유니시티 사이버몰의 초기 서비스화면(전면)에 게시합니다. 다만, 약관의 내용은 이용자가 연결화면을 통하여 볼 수 있도록 할 수 있습니다.</P>
										<p> ②“몰은 이용자가 약관에 동의하기에 앞서 약관에 정하여져 있는 내용 중 청약철회․배송책임․환불조건 등과 같은 중요한 내용을 이용자가 이해할 수 있도록 별도의 연결화면 또는 팝업화면 등을 제공하여 이용자의 확인을 구하여야 합니다. </P>
										<p>③“몰”은 「전자상거래 등에서의 소비자보호에 관한 법률」, 「약관의 규제에 관한 법률」, 「전자문서 및 전자거래기본법」, 「전자금융거래법」, 「전자서명법」, 「정보통신망 이용촉진 및 정보보호 등에 관한 법률」, 「방문판매 등에 관한 법률」, 「소비자기본법」, 「관광진흥법」 등 관련 법을 위배하지 않는 범위에서 이 약관을 개정할 수 있습니다. </P>
										<p>④“몰”이 약관을 개정할 경우에는 적용일자 및 개정사유를 명시하여 현행약관과 함께 몰의 초기화면에 그 적용일자 7일 이전부터 적용일자 전일까지 공지합니다. 다만, 이용자에게 불리하게 약관내용을 변경하는 경우에는 최소한 30일 이상의 사전 유예기간을 두고 공지합니다. 이 경우 "몰“은 개정 전 내용과 개정 후 내용을 명확하게 비교하여 이용자가 알기 쉽도록 표시합니다.</P>
										<p>⑤“몰”이 약관을 개정할 경우에는 그 개정약관은 그 적용일자 이후에 체결되는 계약에만 적용되고 그 이전에 이미 체결된 계약에 대해서는 개정 전의 약관조항이 그대로 적용됩니다. 다만 이미 계약을 체결한 이용자가 개정약관 조항의 적용을 받기를 원하는 뜻을 제3항에 의한 개정약관의 공지기간 내에 “몰”에 송신하여 “몰”의 동의를 받은 경우에는 개정약관 조항이 적용됩니다. </P>
										<p>⑥이 약관에서 정하지 아니한 사항과 이 약관의 해석에 관하여는 전자상거래 등에서의 소비자보호에 관한 법률, 약관의 규제 등에 관한 법률, 공정거래위원회가 정하는 전자상거래 등에서의 소비자 보호지침 및 관계법령 또는 상관례에 따릅니다.</P>
										<p><br></P>
										<h4>제 4 조 서비스의 제공 및 변경</h4><p><br></P>
										<p>①"몰"은 다음과 같은 업무를 수행합니다. 1. 재화 또는 용역에 대한 정보 제공 및 구매계약의 체결 2. 구매계약이 체결된 재화 또는 용역의 배송 3. 기타 "유니시티"가 정하는 업무 </P>
										<p>②“몰”은 재화 또는 용역의 품절 또는 기술적 사양의 변경 등의 경우에는 장차 체결되는 계약에 의해 제공할 재화 또는 용역의 내용을 변경할 수 있습니다. 이 경우에는 변경된 재화 또는 용역의 내용 및 제공일자를 명시하여 현재의 재화 또는 용역의 내용을 게시한 곳에 즉시 공지합니다. </P>
										<p>③“몰”이 제공하기로 이용자와 계약을 체결한 서비스의 내용을 재화등의 품절 또는 기술적 사양의 변경 등의 사유로 변경할 경우에는 그 사유를 이용자에게 통지 가능한 주소로 즉시 통지합니다. </P>
										<p>④전항의 경우 “몰”은 이로 인하여 이용자가 입은 손해를 배상합니다. 다만, “몰”이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다.</P>
										<p><br></P>
										<h4>제 5 조 서비스의 중단</h4><p><br></P>
										<p>①"몰"은 컴퓨터 등 정보통신설비의 보수점검·교체 및 고장, 통신의 두절 등의 사유가 발생한 경우에는 서비스의 제공을 일시적으로 중단할 수 있습니다. </P>
										<p>②”몰”은 제①항의 사유로 서비스의 제공이 일시적으로 중단됨으로 인하여 이용자 또는 제3자가 입은 손해에 대하여 배상합니다. 단, “몰”이 고의 또는 과실이 없음을 입증하는 경우에는 그러하지 아니합니다. </P>
										<p>③사업종목의 전환, 사업의 포기, 업체 간의 통합 등의 이유로 서비스를 제공할 수 없게 되는 경우에는 “몰”은 제8조에 정한 방법으로 이용자에게 통지하고 당초 “몰”에서 제시한 조건에 따라 소비자에게 보상합니다. 다만, “몰”이 보상기준 등을 고지하지 아니한 경우에는 이용자들의 마일리지 또는 적립금 등을 “몰”에서 통용되는 통화가치에 상응하는 현물 또는 현금으로 이용자에게 지급합니다.</P>
										<p><br></P>
										<h4>제 6 조 회원가입</h4><p><br></P>
										<p>①이용자는 “몰”이 정한 가입 양식에 따라 회원정보를 기입한 후 이 약관에 동의한다는 의사표시를 함으로서 회원가입을 신청합니다. </P>
										<p>②“몰”은 제①항과 같이 회원으로 가입할 것을 신청한 이용자 중 다음 각호에 해당하지 않는 한 회원으로 등록합니다. 1. 가입신청자가 이 약관 약관 제7조제③항에 의하여 이전에 회원자격을 상실한 적이 있는 경우, 다만 제7조제③항에 의한 회원자격 상실후 3년이 경과한 자로서 “몰”의 회원재가입 승낙을 얻은 경우에는 예외로 한다. 2. 등록 내용에 허위, 기재누락, 오기가 있는 경우 3. 기타 회원으로 등록하는 것이 “몰”의 기술상 현저히 지장이 있다고 판단되는 경우 </P>
										<p>③회원가입계약의 성립시기는 “몰”의 승낙이 회원에게 도달한 시점으로 합니다. </P>
										<p>④회원은 회원가입 시 등록한 사항에 변경이 있는 경우, 14일 이내 몰에 대하여 회원정보 수정 및 전자우편, 기타 방법으로 그 변경사항을 알려야 합니다.</P>
										<p><br></P>
										<h4>제 7 조 회원 탈퇴 및 자격 상실 등</h4><p><br></P>
										<p>①회원은 “몰”에 언제든지 탈퇴를 요청할 수 있으며 “몰”은 즉시 회원탈퇴를 처리합니다. </P>
										<p>②회원이 다음 각호의 사유에 해당하는 경우, "몰"은 회원자격을 제한 및 정지시킬 수 있습니다. 1. 가입 신청서에 허위 내용을 등록한 경우 2. “몰”을 이용하여 구입한 재화등의 대금, 기타 “몰”이용에 관련하여 회원이 부담하는 채무를 기일에 지급하지 않는 경우 3. 다른 사람의 “몰” 이용을 방해하거나 그 정보를 도용하는 등 전자상거래 질서를 위협하는 경우 4. “몰”을 이용하여 법령 또는 이 약관이 금지하거나 공서양속에 반하는 행위를 하는 경우 </P>
										<p>③“몰”이 회원 자격을 제한, 정지 시킨후, 동일한 행위가 2회이상 반복되거나 30일이내에 그 사유가 시정되지 아니하는 경우 “몰”은 회원자격을 상실시킬 수 있습니다. </P>
										<p>④“몰”이 회원자격을 상실시키는 경우에는 회원등록을 말소합니다. 이 경우 회원에게 이를 통지하고, 회원등록 말소전에 최소한 30일 이상의 기간을 정하여 소명할 기회를 부여합니다.</P>
										<p><br></P>
										<h4>제 8 조 회원에 대한 통지</h4><p><br></P>
										<p>①"몰"이 회원에 대한 통지를 하는 경우, 회원이 "몰"과 미리 약정하여 지정한 전자우편 주소로 할 수 있습니다. ②“몰”은 불특정다수 회원에 대한 통지의 경우 1주일이상 “몰” 게시판에 게시함으로서 개별 통지에 갈음할 수 있습니다. 다만, 회원 본인의 거래와 관련하여 중대한 영향을 미치는 사항에 대하여는 개별통지를 합니다.</P>
										<p><br></P>
										<h4>제 9 조 구매신청 및 개인정보 제공 동의 등</h4><p><br></P>
										<p>①“몰”이용자는 “몰”상에서 다음 또는 이와 유사한 방법에 의하여 구매를 신청하며, “몰”은 이용자가 구매신청을 함에 있어서 다음의 각 내용을 알기 쉽게 제공하여야 합니다. 1. 재화등의 검색 및 선택 2. 받는 사람의 성명, 주소, 전화번호, 전자우편주소(또는 이동전화번호) 등의 입력 3. 약관내용, 청약철회권이 제한되는 서비스, 배송료․설치비 등의 비용부담과 관련한 내용에 대한 확인 4. 이 약관에 동의하고 위 3.호의 사항을 확인하거나 거부하는 표시(예, 마우스 클릭) 5. 재화등의 구매신청 및 이에 관한 확인 또는 “몰”의 확인에 대한 동의 6. 결제방법의 선택 </P>
										<p>②“몰”이 제3자에게 구매자 개인정보를 제공할 필요가 있는 경우 1) 개인정보를 제공받는 자, 2)개인정보를 제공받는 자의 개인정보 이용목적, 3) 제공하는 개인정보의 항목, 4) 개인정보를 제공받는 자의 개인정보 보유 및 이용기간을 구매자에게 알리고 동의를 받아야 합니다. (동의를 받은 사항이 변경되는 경우에도 같습니다.) </P>
										<p>③“몰”이 제3자에게 구매자의 개인정보를 취급할 수 있도록 업무를 위탁하는 경우에는 1) 개인정보 취급위탁을 받는 자, 2) 개인정보 취급위탁을 하는 업무의 내용을 구매자에게 알리고 동의를 받아야 합니다. (동의를 받은 사항이 변경되는 경우에도 같습니다.) 다만, 서비스제공에 관한 계약이행을 위해 필요하고 구매자의 편의증진과 관련된 경우에는 「정보통신망 이용촉진 및 정보보호 등에 관한 법률」에서 정하고 있는 방법으로 개인정보 취급방침을 통해 알림으로써 고지절차와 동의절차를 거치지 않아도 됩니다.</P>
										<p><br></P>
										<h4>제 10 조 계약의 성립</h4><p><br></P>
										<p>①"몰"은 제9조와 같은 구매신청에 대하여 다음 각호에 해당하면 승낙하지 않을 수 있습니다. 다만, 미성년자와 계약을 체결하는 경우에는 법정대리인의 동의를 얻지 못하면 미성년자 본인 또는 법정대리인이 계약을 취소할 수 있다는 내용을 고지하여야 합니다. 1. 신청 내용에 허위, 기재누락, 오기가 있는 경우 2. 미성년자가 담배, 주류 등 청소년보호법에서 금지하는 재화 및 용역을 구매하는 경우 3. 기타 구매신청에 승낙하는 것이 "몰" 기술상 현저히 지장이 있다고 판단하는 경우 </P>
										<p>②"몰"의 승낙이 제12조제①항의 수신확인통지형태로 이용자에게 도달한 시점에 계약이 성립한 것으로 봅니다. </P>
										<p>③ “몰”의 승낙의 의사표시에는 이용자의 구매 신청에 대한 확인 및 판매가능 여부, 구매신청의 정정 취소 등에 관한 정보 등을 포함하여야 합니다.</P>
										<p><br></P>
										<h4>제 11 조 지급방법</h4><p><br></P>
										<p>①"몰"에서 구매한 재화 또는 용역에 대한 대금지급방법은 다음 각호의 방법중 가용한 방법으로 할 수 있습니다. 단, “몰”은 이용자의 지급방법에 대하여 재화 등의 대금에 어떠한 명목의 수수료도 추가하여 징수할 수 없습니다. 1. 폰뱅킹, 인터넷뱅킹, 메일 뱅킹 등의 각종 계좌이체 2. 선불카드, 직불카드, 신용카드 등의 각종 카드 결제 3. 온라인무통장입금 4. 전자화폐에 의한 결제 5. 수령시 대금지급 6. 마일리지 등 “몰”이 지급한 포인트에 의한 결제 7. “몰”과 계약을 맺었거나 “몰”이 인정한 상품권에 의한 결제 8. 기타 전자적 지급 방법에 의한 대금 지급 등</P>
										<p><br></P>
										<h4>제 12 조 수신확인통지 구매신청 변경 및 취소</h4><p><br></P>
										<p>①"몰"은 이용자의 구매신청이 있는 경우 이용자에게 수신확인통지를 합니다. </P>
										<p>②수신확인통지를 받은 이용자는 의사표시의 불일치 등이 있는 경우에는 수신확인통지를 받은 후 즉시 구매신청 변경 및 취소를 요청할 수 있고, "몰"은 배송전에 이용자의 요청이 있는 경우에는 지체 없이 그 요청에 따라 처리하여야 합니다. 다만 이미 대금을 지불한 경우에는 제15조의 청약철회 등에 관한 규정에 따릅니다.</P>
										<p><br></P>
										<h4>제 13 조 재화등의 공급</h4><p><br></P>
										<p>①“몰”은 이용자와 재화 등의 공급시기에 관하여 별도의 약정이 없는 이상, 이용자가 청약을 한 날부터 7일 이내에 재화 등을 배송할 수 있도록 주문제작, 포장 등 기타의 필요한 조치를 취합니다. 다만, “몰”이 이미 재화 등의 대금의 전부 또는 일부를 받은 경우에는 대금의 전부 또는 일부를 받은 날부터 2영업일 이내에 조치를 취합니다. 이때 “몰”은 이용자가 재화 등의 공급 절차 및 진행 사항을 확인할 수 있도록 적절한 조치를 합니다. </P>
										<p>②“몰”은 이용자가 구매한 재화에 대해 배송수단, 수단별 배송비용 부담자, 수단별 배송기간 등을 명시합니다. 만약 “몰”이 약정 배송기간을 초과한 경우에는 그로 인한 이용자의 손해를 배상하여야 합니다. 다만 “몰”이 고의, 과실이 없음을 입증한 경우에는 그러하지 아니합니다.</P>
										<p><br></P>
										<h4>제 14 조 환급</h4><p><br></P>
										<p>①“몰”은 이용자가 구매신청한 재화 등이 품절 등의 사유로 인도 또는 제공을 할 수 없을 때에는 지체 없이 그 사유를 이용자에게 통지하고 사전에 재화 등의 대금을 받은 경우에는 대금을 받은 날부터 3영업일 이내에 환급하거나 환급에 필요한 조치를 취합니다.</P>
										<p><br></P>
										<h4>제 15 조 청약철회 등</h4><p><br></P>
										<p>①“몰”과 재화 등의 구매에 관한 계약을 체결한 이용자는 「방문판매 등에 관한 법률」 및 「전자상거래 등에서의 소비자보호에 관한 법률」,「국외여행표준약관」에 따라 청약철회를 할 수 있습니다. 해당 법률에 따르면, 계약내용에 관한 서면을 받은 날(그 서면을 받은 때보다 재화 등의 공급이 늦게 이루어진 경우에는 재화 등을 공급받거나 재화 등의 공급이 시작된 날을 말합니다)부터 회원(디스트리뷰터)은 3개월, 소비자는 20일 이내에는 청약의 철회를 할 수 있습니다.</P>
										<P>단, 여행상품은 주문에 의하여 개별적으로 생산되는 것으로 청약철회등을 인정하면 “몰” 및 “유니시티”에게 회복할 수 없는 중대한 피해가 예상되는 경우로 「방문판매등에관한법률」 제17조제2항제3호 및 동법 시행령 제25조제4호에 따라 청약철회가 제한됩니다. 따라서 여행 개시 된 후(여행 출발 후)에는 환불액이 없으며, 여행 개시 이전 예약 및 여행 청약철회 시에는 국외여행표준 약관 제16조(여행출발 전 계약해제)제1항에 따라 소비자분쟁해결기준(공정거래위원회 고시)에 근거하여 아래 비율로 취소료가 부과되고 그 차액이 환불됩니다.</P>
										<p>1. 여행 개시일로부터 30일 이전 취소 시: 예약금 (또는 여행가) 100% 환불 </p>
										<p>2. 여행 개시일로부터 20일 이전 취소 시: 여행가의 90% 환불</p>
										<p>3. 여행 개시일로부터 10일 이전 취소 시: 여행가의 85% 환불</p>
										<p>4. 여행 개시일로부터 08일 이전 취소 시: 여행가의 80% 환불</p>
										<p>5. 여행 개시일로부터 01일 이전 취소 시: 여행가의 70% 환불</p>
										<p>6. 여행 출발 당일(여행개시 전) 취소 시: 여행가의 50% 환불</p>
										<p>7. 여행 출발 이후(여행개시 후) 취소 시: 환불액 없음</p>
										<p>여행일 이전 취소의 사유가 상해, 질병(COVID-19확진), 입원, 사망 등 여행자가 여행을 진행할 수 없는 상황일 경우 해당 상황을 증빙할 수 있는 서류를 제출하여야 합니다.</p>
										<p>② 감염병(COVID-19등)의 발생과 관련 아래 각 호에 해당하는 사유가 발생할 경우 유니시티 또는 이용자는 배상없이 예약 취소 또는 계약을 해제할 수 있습니다.</p>
										
										<p>1. 태국정부가 대한민국 국민에 대해서 입국 금지 및 격리조치의 상향 및 이에 준하는 정책을 발하여 계약을 이행할 수 없는 경우 </p>
										<p>2. 계약 체결 후 대한민국 외교부의 여행경보 4단계(여행금지)에 해당하여 여행할 수 없는 경우</p>
										<p>3. 항공기의 운항이 중단되어 계약을 이행할 수 없는 경우</p>
										<p>4. WHO(세계보건기구), 태국정부 또는 대한민국 외교부가 태국의 감염병 확산에 따른 경보를 5단계 이상 상향 조정하여 계약을 이행함에 위험이 따른다고 판단될 경우</p>
										
										<p>③이용자는 재화 등을 배송 받은 경우 다음 각 호의 1에 해당하는 경우에는 반품 및 교환을 할 수 없습니다.</P>
										<p>1. 이용자에게 책임 있는 사유로 재화 등이 멸실 또는 훼손된 경우(다만, 재화 등의 내용을 확인하기 위하여 포장 등을 훼손한 경우에는 청약철회를 할 수 있습니다.)</P>

										<p>2. 이용자의 사용 또는 일부 소비에 의하여 재화 등의 가치가 현저히 감소한 경우</P>
										<p>3. 시간의 경과에 의하여 재판매가 곤란할 정도로 재화 등의 가치가 현저히 감소한 경우</P> 
										<p>4. 같은 성능을 지닌 재화 등으로 복제가 가능한 경우 그 원본인 재화 등의 포장을 훼손한 경우</P>
										<p>5. 회원(디스트리뷰터)의 경우, 재고 보유에 관하여 다단계판매업자에게 거짓으로 보고하는 등의 방법으로 과다하게 재화 등의 재고를 보유한 경우</P>
										<p>④제③항 제2호 내지 제4호의 경우에 “몰”이 사전에 청약철회 등이 제한되는 사실을 소비자가 쉽게 알 수 있는 곳에 명기하거나 시용상품을 제공하는 등의 조치를 하지 않았다면 이용자의 청약철회 등이 제한되지 않습니다.</P>
										<p>⑤이용자는 제①항 및 제②항 및 제③항의 규정에 불구하고 재화 등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행된 때에는 당해 재화 등을 공급받은 날부터 3월 이내, 그 사실을 안 날 또는 알 수 있었던 날부터 30일 이내에 청약철회 등을 할 수 있습니다.</P>
										<p><br></P>
										<h4>제 16 조 청약철회 등의 효과</h4><p><br></P>
										<p>①“몰”은 이용자로부터 재화 등을 반환 받은 경우 3영업일 이내에 이미 지급받은 재화 등의 대금을 환급합니다. 이 경우 “몰”이 이용자에게 재화 등의 환급을 지연한 때에는 그 지연기간에 대하여 「방문판매 등에 관한 법률」 및 「전자상거래 등에서의 소비자보호에 관한 법률 시행령」 제21조 3이 정하는 지연이자율(연 100분의 24)을 곱하여 산정한 지연이자를 지급합니다.</P>
										<p>②“몰”은 위 대금을 환급함에 있어서 이용자가 신용카드 또는 전자화폐 등의 결제수단으로 재화 등의 대금을 지급한 때에는 지체 없이 당해 결제수단을 제공한 사업자로 하여금 재화 등의 대금의 청구를 정지 또는 취소하도록 요청합니다.</P>
										<p>③“몰”은 이용자에게 청약철회 등을 이유로 위약금 또는 손해배상을 청구하지 않습니다. 다만 재화 등의 내용이 표시·광고 내용과 다르거나 계약내용과 다르게 이행되어 청약철회 등을 하는 경우 재화 등의 반환에 필요한 비용은 “몰”이 부담합니다.</P>
										<p>④이용자가 재화 등을 제공받을 때 발송비를 부담한 경우에 “몰”은 청약철회 시 그 비용을 누가 부담하는지를 이용자가 알기 쉽도록 명확하게 표시합니다.</P>
										<p><br></P>
										<h4>제 17 조 개인정보보호</h4><p><br></P>
										<p>①“몰”은 이용자의 개인정보 수집시 서비스제공을 위하여 필요한 범위에서 최소한의 개인정보를 수집합니다. </P>
										<p>② “몰”은 회원가입시 구매계약이행에 필요한 정보를 미리 수집하지 않습니다. 다만, 관련 법령상 의무이행을 위하여 구매계약 이전에 본인확인이 필요한 경우로서 최소한의 특정 개인정보를 수집하는 경우에는 그러하지 아니합니다. </P>
										<p>③“몰”은 이용자의 개인정보를 수집·이용하는 때에는 당해 이용자에게 그 목적을 고지하고 동의를 받습니다. </P>
										<p>④“몰”은 수집된 개인정보를 목적외의 용도로 이용할 수 없으며, 새로운 이용목적이 발생한 경우 또는 제3자에게 제공하는 경우에는 이용·제공단계에서 당해 이용자에게 그 목적을 고지하고 동의를 받습니다. 다만, 관련 법령에 달리 정함이 있는 경우에는 예외로 합니다. </P>
										<p>⑤“몰”이 제2항과 제3항에 의해 이용자의 동의를 받아야 하는 경우에는 개인정보보호법 제15조 및 17조 등이 규정한 사항을 고지해야 하며, 이용자로부터 동의를 받아야 한다. </P>
										<p>⑥이용자는 언제든지 “몰”이 가지고 있는 자신의 개인정보에 대해 열람 및 오류정정을 요구할 수 있으며 “몰”은 이에 대해 지체 없이 필요한 조치를 취할 의무를 집니다. 이용자가 오류의 정정을 요구한 경우에는 “몰”은 그 오류를 정정할 때까지 당해 개인정보를 이용하지 않습니다. </P>
										<p>⑦“몰”은 개인정보 보호를 위하여 이용자의 개인정보를 취급하는 자를 최소한으로 제한하여야 하며 신용카드, 은행계좌 등을 포함한 이용자의 개인정보의 분실, 도난, 유출, 동의 없는 제3자 제공, 변조 등으로 인한 이용자의 손해에 대하여 모든 책임을 집니다. </P>
										<p>⑧“몰” 또는 그로부터 개인정보를 제공받은 제3자는 개인정보의 수집목적 또는 제공받은 목적을 달성한 때에는 당해 개인정보를 지체 없이 파기합니다. </P>
										<p>⑨“몰”은 개인정보의 수집·이용·제공에 관한 동의 란을 미리 선택한 것으로 설정해두지 않습니다. 또한 개인정보의 수집·이용·제공에 관한 이용자의 동의거절시 제한되는 서비스를 구체적으로 명시하고, 필수수집항목이 아닌 개인정보의 수집·이용·제공에 관한 이용자의 동의 거절을 이유로 회원가입 등 서비스 제공을 제한하거나 거절하지 않습니다.</P>
										<p><br></P>
										<h4>제 18 조 “몰”의 의무</h4><p><br></P>
										<p>①"몰"은 법령과 이 약관이 금지하거나 공서양속에 반하는 행위를 하지 않으며 이 약관이 정하는 바에 따라 지속적이고, 안정적으로 재화, 용역을 제공하는 데 최선을 다하여야 합니다. </P>
										<p>②"몰"은 이용자가 안전하게 인터넷 서비스를 이용할 수 있도록 이용자의 개인정보(신용정보 포함)보호를 위한 보안 시스템을 갖추어야 합니다. </P>
										<p>③"몰"이 상품이나 용역에 대하여 「표시, 광고의 공정화에 관한 법률」 제3조 소정의 부당한 표시, 광고행위를 함으로써 이용자가 손해를 입은 때에는 이를 배상할 책임을 집니다. </P>
										<p>④"몰"은 이용자가 원하지 않는 영리목적의 광고성 전자우편을 발송하지 않습니다.</P>
										<p><br></P>
										<h4>제 19 조 회원의 ID 및 비밀번호에 대한 의무</h4><p><br></P>
										<p>①제17조의 경우를 제외한 ID와 비밀번호에 관한 관리책임은 회원에게 있습니다. </P>
										<p>②회원은 자신의 ID 및 비밀번호를 제3자에게 이용하게 해서는 안됩니다. </P>
										<p>③회원이 자신의 ID 및 비밀번호를 도난 당하거나 제3자가 사용하고 있음을 인지한 경우에는 바로 “몰”에 통보하고 “몰”의 안내가 있는 경우에는 그에 따라야 합니다.</P>
										<p><br></P>
										<h4>제 20 조 (이용자의 의무)</h4><p><br></P>
										<p>①이용자는 다음 행위를 하여서는 안됩니다. 1. 신청 또는 변경시 허위 내용의 등록 2. 타인의 정보 도용 3. "몰"에 게시된 정보의 변경 4. "몰"이 정한 정보 이외의 정보(컴퓨터 프로그램 등)등의 송신 또는 게시 5. "몰" 기타 제3자의 저작권 등 지적재산권에 대한 침해 6. "몰" 기타 제3자의 명예를 손상시키거나 업무를 방해하는 행위 7. 외설 또는 폭력적인 메시지, 화상, 음성, 기타 공서양속에 반하는 정보를 “몰”에 공개 또는 게시하는 행위</P>
										<p><br></P>
										<h4>제 21 조 연결"몰"과 피연결"몰" 간의 관계</h4><p><br></P>
										<p>①상위 "몰"과 하위 "몰"이 하이퍼 링크(예: 하이퍼 링크의 대상에는 문자, 그림 및 동화상 등이 포함됨)방식 등으로 연결 된 경우, 전자를 연결 "몰"(웹 사이트)이라고 하고 후자를 피연결 "몰"(웹사이트)이라고 합니다. </P>
										<p>②연결“몰”은 피연결“몰”이 독자적으로 제공하는 재화 등에 의하여 이용자와 행하는 거래에 대해서 보증 책임을 지지 않는다는 뜻을 연결“몰”의 초기화면 또는 연결되는 시점의 팝업화면으로 명시한 경우에는 그 거래에 대한 보증 책임을 지지 않습니다.</P>
										<p><br></P>
										<h4>제 22 조 저작권의 귀속 및 이용제한</h4><p><br></P>
										<p>①"몰"이 작성한 저작물에 대한 저작권 기타 지적재산권은 "몰"에 귀속합니다. </P>
										<p>②이용자는 “몰”을 이용함으로써 얻은 정보 중 “몰”에게 지적재산권이 귀속된 정보를 “몰”의 사전 승낙 없이 복제, 송신, 출판, 배포, 방송 기타 방법에 의하여 영리목적으로 이용하거나 제3자에게 이용하게 하여서는 안됩니다. </P>
										<p>③“몰”은 약정에 따라 이용자에게 귀속된 저작권을 사용하는 경우 당해 이용자에게 통보하여야 합니다.</P>
										<p><br></P>
										<h4>제 23 조 분쟁해결</h4><p><br></P>
										<p>①"몰"은 이용자가 제기하는 정당한 의견이나 불만을 반영하고 그 피해를 보상처리하기 위하여 피해보상처리기구를 설치, 운영합니다. </P>
										<p>②“몰”은 이용자로부터 제출되는 불만사항 및 의견은 우선적으로 그 사항을 처리합니다. 다만, 신속한 처리가 곤란한 경우에는 이용자에게 그 사유와 처리일정을 즉시 통보해 드립니다. </P>
										<p>③"몰"과 이용자간에 발생한 전자상거래 분쟁과 관련하여 이용자의 피해구제신청이 있는 경우에는 공정거래위원회 또는 시•도지사가 의뢰하는 분쟁조정기관의 조정에 따를 수 있습니다.</P>
										<p><br></P>
										<h4>제 24 조 재판권 및 준거법</h4><p><br></P>
										<p>①“몰”과 이용자 간에 발생한 전자상거래 분쟁에 관한 소송은 제소 당시의 이용자의 주소에 의하고, 주소가 없는 경우에는 거소를 관할하는 지방법원의 전속관할로 합니다. 다만, 제소 당시 이용자의 주소 또는 거소가 분명하지 않거나 외국 거주자의 경우에는 민사소송법상의 관할법원에 제기합니다. </P>
										<p>②“몰”과 이용자간에 제기된 전자상거래 소송에는 한국법을 적용합니다.</P>
										<p><br></P>
										<h4>제 25 조 여행 중 코로나-19 확진</h4><p><br></P>
										<p>① 이용자는 여행상품에 따른 태국 여행 후 한국 입국 시, 출국 48시간 전, PCR음성확인서제출이 필요하며, PCR검사 과정에서 양성으로 확인될 경우, 태국에서 약 10일간 격리조치 및 치료가 이루어집니다. (2022년5월1일기준)</p>
										<p>② 제①항의 사항은 실재 여행 시점의 태국 및 대한민국 정부의 입국 및 격리조치에 대한 정책에 따라 변동될 수 있습니다. </p>
										<p>③ 제①항에 따른 격리조치 및 치료에 대한 보장 및 본인 부담 사항은 다음 각 호와 같습니다. </p>
										<p>1.치료비용은 여행자보험의 질병/상해 보장 플랜 기준으로 최대 $10,000까지 보장됩니다.</p>
										<p>2.기타 격리에 필요한 숙박, 식비, 교통비, 여비는 모두 이용자 본인 부담입니다.</p>
									</article>
								</div>	
								<div id="layerbox1" class="layerpop">
									<article class="layerpop_area">		
										<h1 style="text-align: center">이용약관</h1>
										<div style="text-align: right"><a href="javascript:popupClose(2);"id="layerbox_close">닫기</a></div>
										<p>유니시티코리아(유)는 GLIC 참가의 원활한 서비스의 제공을 위해 아래와 같이 개인정보를 수집하고 있습니다. 개인정보의 수집 및 이용에 대한 동의 철회 시, 수집한 개인정보는 즉시 삭제 됩니다.</P>
										<br/>
										<h1>[수집 및 이용목적]</h1>
										<p>GLIC Travel package 예약</p>
										<br/>
										<h1>[수집항목]</h1>
										<p>회원번호, 회원성명, 전화번호, 결제정보(카드번호, 유효기간, 생년월일, 카드 비밀번호 앞2자리)</p>
										<br/>
										
										<h1>[보유 및 이용 기간]</h1>
										<p>원칙적으로 개인정보 수집 및 이용 목적이 달성된 후에는 해당 정보를 지체없이 복구할 수 없는 방법으로 파기 합니다. 다만, 다른 법령에 따라 보존 하여야 하는 경우에는 그러한 규정에 따라 보관 됩니다.</p>
										<br/>
										<p>관련법령에 의한 정보 보유 사유</p>
										<p>- 계약 또는 청약철회 등에 관한 기록 : 5년</p>
										<p>- 대금 결제 및 재화 등의 공급에 관한 기록 : 5년</p>
										<p>- 소비자의 불만 또는 분쟁처리에 관한 기록 : 5년</p>
										<br/>
										<h1>[수집 동의 거부 권리 및 불이익 사항]</h1>
										<p>이용자는 개인정보 수집 및 이용을 거부할 권리가 있습니다. 단, 개인정보의 수집 및 이용에 동의하지 않을 경우, 서비스의 제공에 제한이 있을 수 있습니다.</p>
									</article>	
								</div>
								<div id="layerbox2" class="layerpop">
									<article class="layerpop_area">	
										<h1 style="text-align: center">이용약관</h1>
										<div style="text-align: right"><a href="javascript:popupClose(3);"id="layerbox_close">닫기</a></div>
										<br/>
										<h1>개인정보의 제3자 제공 및 공유 목적과 기간 및 거부 권리에 대한 안내</h1>
										<br/>
										<h1>제공받는 자</h1>
										<p>(주)하나엠엔씨</p>
										<br/>
										<h1>제공 목적</h1>
										<p>GLIC 여행상품 이용자 정보 등록</p>
										<br/>
										<h1>제공하는 개인정보</h1>
										<p>회원번호, 회원성명, 전화번호</p>
										<br/>
										<h1>보유 및 이용기간</h1>
										<p>목적달성시 까지</p>
										<br/>
										<h1>제공 동의 거부 권리 및 불이익 사항</h1>
										<p>이용자는 위 개인정보의 제3자 제공에 대하여 동의를 거부할 권리가 있습니다. 단 동의를 거부하실 경우 서비스 제공이 불가할 수 있습니다</p>
									</article>
								</div>										
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>			
	</body>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src='./js/common.js'></script>
	<script type="text/javascript" src="./js/selectordie.min.js"></script>

	<script type="text/javascript">
    		window.name ="Parent_window";
    		var sendForm = document.sendForm;
			$( document ).ready(function() {
				var loginId = '<?php echo $loginId?>';
				/*
				if(loginId !=''){
					js_search11(<?php echo $loginId?>);	
				}else{
					location.href='https://ushop-kr.unicity.com/login'
				}
*/
				setDateBox();
			
			});
			function setDateBox(){
				var dt = new Date();
				var year = "";
				var com_year = dt.getFullYear();
				// 발행 뿌려주기
				$("#year").append("<option value=''>년도</option>");
				// 올해 기준으로 -1년부터 +5년을 보여준다.
				for(var y = (com_year); y <= (com_year+10); y++){
					$("#year").append("<option value='"+ y +"'>"+ y  +"</option>");
				}
				
				
    		}
			function js_search11(chNum){
				
				$.ajax({
					url: 'https://hydra.unicity.net/v5a/customers?unicity='+chNum+'&expand=customer',
					headers:{
						'Content-Type':'application/json'
					},
					type: 'GET',
					success: function(result) {
						console.log(result.items[0].href);
						if(typeof(result) != 'undefined' && typeof(result.items) != 'undefined' && result.items.length > 0) {
							var _oname = '';
							var href = result.items[0].href;
							$('[name=custHref]').val(href);
							if(typeof(result.items[0].humanName['fullName@ko']) != 'undefined') {
								_oname = result.items[0].humanName['fullName@ko'];
							}
							if(_oname == '') {
								_oname = result.items[0].humanName.fullName;
							}
							$('[name=distName]').val(_oname);
						}else{
						}		
						
					}, error: function() {
						alert('검색된 회원이 없습니다.');
					}
				})
			}	

			function submit(){
		
		var sDate0804= '<?php echo $tot?>';
		var sDate0805= '<?php echo $tot2?>';
				if((sendForm.selectDate.value=='0804') && (sDate0804 >=600)){
			
						alert('0804 해당일자 예약이 마감 마감되었습니다.');
						return false;
				
				}

				if(sendForm.selectDate.value=='0805' && sDate0805 >=400){
				
						alert('0805 해당일자 예약이 마감 마감되었습니다.');
						return false;
					
				}
						
				if(sendForm.distID.value == ""){
					alert("회원번호를 입력 하세요");
					sendForm.distID.focus();
					return false;
				}else if (sendForm.distName.value == ""){
					alert("회원이름을 입력 하세요");
					sendForm.distName.focus();
					return false;
				}else if (sendForm.phone.value == ""){
					alert("전화번호를 입력 하세요");
					sendForm.phone.focus();
					return false;
				}else if (sendForm.attendP.value == ""){
					alert("참석인원을 입력 하세요");
					sendForm.attendP.focus();
					return false;
				}else if (sendForm.selectDate.value == ""){
					alert("신청날짜를 선택 하세요");
					sendForm.selectDate.focus();
					return false;
				}else if (sendForm.selectCard.value == ""){
					alert("카드를 선택 하세요");
					sendForm.selectCard.focus();
					return false;
				}else if (sendForm.cardNumber.value == ""){
					alert("카드번호를 선택 하세요");
					sendForm.cardNumber.focus();
					return false;
				}else if (sendForm.installment.value == ""){
					alert("카드할부를 선택 하세요");
					sendForm.installment.focus();
					return false;
				}else if (sendForm.month.value == ""){
					alert("유효기간 월을 입력 하세요");
					sendForm.expireDate.focus();
					return false;
				}else if (sendForm.year.value == ""){
					alert("유효기간 년을 입력 하세요");
					sendForm.expireDate.focus();
					return false;
				}else if (sendForm.birthDay.value == ""){
					alert("생년월일을 입력 하세요");
					sendForm.birthDay.focus();
					return false;
				}else if (sendForm.passWord.value == ""){
					alert("비밀번호 앞두자리를 입력 하세요");
					sendForm.passWord.focus();
					return false;
				}

				if(document.getElementById("agreeCheck1").checked==false || document.getElementById("agreeCheck2").checked==false || document.getElementById("agreeCheck3").checked==false || document.getElementById("agreeCheck4").checked==false || document.getElementById("agreeCheck5").checked==false || document.getElementById("agreeCheck6").checked==false||document.getElementById("agreeCheck7").checked==false){
					alert("약관에 모두 동의 하셔야 합니다.");
					return false;
				}
				var yearDate = sendForm.year.value
				var expDate = yearDate.substr(2, 2) + sendForm.month.value
				
				if(confirm("본인은'GLIC 2022 Travel package 예약'에 따른 결제가 2022년 6월 중에 이루어짐을 인지 하였으며, 위와 같이 예약을 신청 합니다.")){
					sendForm.expireDate.value = expDate;
					sendForm.action = "saveData.php";
					sendForm.submit();
				}
			}

//** 유효기간 자동 슬러쉬(/)표기 */
			function inputNumberFormat(obj) {
     			obj.value = comma(uncomma(obj.value));
 			}

			function comma(str) {
				str = String(str);
				return str.replace(/(\d)(?=(?:\d{2})+(?!\d))/g, '$1/');
			}

			function uncomma(str) {
				str = String(str);
				return str.replace(/[^\d]+/g, '');
			}


			function installmentShow(){
				
				$('#deteilCheck1').css("display", "block");
				$('#installmentShow').css("display", "none");
				$('#installmentNone').css("display", "block");
			}

			function installmentNone(){
				$('#deteilCheck1').css("display", "none");
				$('#installmentShow').css("display", "block");
				$('#installmentNone').css("display", "none");
			}	

			function agreeDetailshow(){
				$('#agreeShow').css("display", "block");
				$('#agreeDetailNone').css("display", "block");
				$('#agreeDetailShow').css("display", "none");	
			}
			
			function agreeDetailnone(){
			
				$('#agreeShow').css("display", "none");	
				$('#agreeDetailNone').css("display", "none");
				$('#agreeDetailShow').css("display", "block");

			}

			function allCheck(){
		
				if($("input:checkbox[id='agreeCheck']").is(":checked") == true){
					$("input:checkbox[id=agreeCheck1]").prop("checked", true); 
					$("input:checkbox[id=agreeCheck2]").prop("checked", true); 
					$("input:checkbox[id=agreeCheck3]").prop("checked", true); 
					$("input:checkbox[id=agreeCheck4]").prop("checked", true); 
					$("input:checkbox[id=agreeCheck5]").prop("checked", true); 
					$("input:checkbox[id=agreeCheck6]").prop("checked", true); 
					$("input:checkbox[id=agreeCheck7]").prop("checked", true); 
				}else{
					$("input:checkbox[id=agreeCheck1]").prop("checked", false); 
					$("input:checkbox[id=agreeCheck2]").prop("checked", false); 
					$("input:checkbox[id=agreeCheck3]").prop("checked", false); 
					$("input:checkbox[id=agreeCheck4]").prop("checked", false); 
					$("input:checkbox[id=agreeCheck5]").prop("checked", false); 
					$("input:checkbox[id=agreeCheck6]").prop("checked", false); 
					$("input:checkbox[id=agreeCheck7]").prop("checked", false); 
				}

			}


			function wrapWindowByMask() {
		        //화면의 높이와 너비를 구한다.
		        var maskHeight = $(document).height(); 
		        var maskWidth = $(window).width();

		        //문서영역의 크기 
		        console.log( "document 사이즈:"+ $(document).width() + "*" + $(document).height()); 
		        //브라우저에서 문서가 보여지는 영역의 크기
		        console.log( "window 사이즈:"+ $(window).width() + "*" + $(window).height());        

		        //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채운다.
		        $('#mask').css({
		            'width' : maskWidth,
		            'height' : maskHeight
		        });

		        //애니메이션 효과
		        //$('#mask').fadeIn(1000);      
		        $('#mask').fadeTo("slow", 0.5);
		    }

		    function popupOpen(val) {
		    	 wrapWindowByMask();
		

				$('.layerpop').css("position", "absolute");
		        //영역 가운에데 레이어를 뛰우기 위해 위치 계산 
		        $('.layerpop').css("top",(($(window).height() - $('.layerpop').outerHeight()) / 2) + $(window).scrollTop());
		        $('.layerpop').css("left",(($(window).width() - $('.layerpop').outerWidth()) / 2) + $(window).scrollLeft());
		        //$('.layerpop').draggable();
				if(val==1){
		        	$('#layerbox').show();
				}else if(val==2){
					$('#layerbox1').show();	
				}else if(val==3){
					$('#layerbox2').show();	
				}
		        
		    }

		    function popupClose(val) {
				if(val==1){
		        	$('#layerbox').hide();
		        	$('#mask').hide();
				}else if(val==2){
					$('#layerbox1').hide();
		        	$('#mask').hide();
				}else if(val==3){
					$('#layerbox2').hide();
		        	$('#mask').hide();
				}
		    }

			function space(obj){
				var a = $('#cardNumber').val().replace(/ /gi, '');
        			$('#cardNumber').val(a);
			}
		</script>
		
</html>