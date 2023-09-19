
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>포털 회원 국내 거주 확인 등록</title>
<meta name="description" content="" />
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
<script type="text/javascript" src="./includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./includes/js/register.js"></script>
<link rel="stylesheet" type="text/css" href="./css/joo.css" />
</head>
<body>

<?php include "common_load.php" ?>

	<div class="wrapper" >
		<!-- container start {-->
		<div class="main_wrapper">

			<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
			</div>
			<div class="main_box">
				<div class="main_inner_box">
					<div class="main_top">
						<h1>
							<span>국내거주 확인등록</span>
						</h1>
						<p>한국 내에서 유니시티 제품 구매를 위해 <br>국내거주를 증명 할 수 있는 자료를 등록해 주시기 바랍니다.</p>
					</div>
					<div class="wrap_input">
						<div class="member">
							<h2>회원번호</h2>
							<div class="wrap">
								<input type="text" placeholder="회원번호" name="distID" id="distID" value="819245603" />

							 	<a href="javascript:js_search()">확인</a>
							</div>
							<p id="errortxt"><span></span></p>
						</div>
						<div id="memberInfo" class="contents">
							<h2>회원정보</h2>
							<form name="registerForm" id="registerForm" action="./includes/register.php" method="post" enctype="multipart/form-data">
						 		<div class="subtitle"> <h4>이름  <span id="distName" ></span></h4></div>
						 		<div class="subtitle"> <h4>생년월일 <span id="distDob"></span></h4>  </div>
						 		<!-- <div class="subtitle"> <h4>이메일 <span id="distEmail"></span></h4>  </div>
						 		 <div class="subtitle"> <h4>휴대폰번호 <span id="distMobilephone"></span></h4>  </div> -->
								<div class="subtitle"> <h4><b style="background-color: #7CF1F6;">외국인등록증 사본 업로드</b></h4></div>
								<div class="subtitle"><input type="file" name="myfile" id="myfile" size="60" accept="image/*"> </div>
								<div>
									<h4 style="width: 100%"><b>고유식별 정보의 수집 및 이용에 대한 동의</b></h4>
								</div>
								<div style="height: 5px;"></div>
								<div>
									<h5>유니시티코리아(유) 재화의 주문, 원활한 상담, 각종 서비스의 제공을 위해 아래와 같은 개인정보를 수집하고 있습니다.</h5>
								</div>
								<div>
									<h5>[고유식별정보의 수집 항목,이용 목적과 기간 및 거부 권리에 대한 안내]</h5>
								</div>
								<div style="height: 10px;"></div>
								<div style="height:200px; overflow-y: scroll;">
									<table border="1" style="margin: 0px; padding: 0px;">
										<tr>
											<td style="width: 30%; background-color:#CECECE; font-size: 11px; text-align: center">
												수집 및 이용목적
											</td>
											<td style="width: 70%;font-size: 11px;padding-left: 8px;">
												이용자 식별을 통한 재화의 주문, 원활한 상담 및 각종 서비스 제공
											</td>
										</tr>
										<tr>
											<td style="width: 30%; font-size: 11px;text-align: center;background-color:#CECECE;">
												수집항목
											</td>
											<td style="width: 70%;font-size: 11px;padding-left: 8px;">
												외국인등록번호 또는 외국국적동포국내거소 신고증번호, 성명, 국적, 주소, 체류자격, 성별
											</td>										
										</tr>
										<tr>
											<td style="width: 30%;font-size: 11px;text-align:center;background-color:#CECECE;">
												보유 및 이용기간
											</td>
											<td style="width: 70%;font-size: 11px;padding-left: 8px;">
												회원쉽 탈퇴 시까지,다만 다른 법령에 따라 보존 하여야 하는 경우에는 그러한 규정에 따라 보관됩니다.<br/>
												▶회사 방침 및 절차에 의한 정보보유 사유: 부정이용기록-사업 종료시까지<br/>
												▶관련법령에 의한 정보보유 사유-사후관리(A/S,부품교체 및 정보 제공 등) 의 목적으로 개인정보,구매정보,정수기 및 공기청정기 부품교체 정보에 관한 기록:제품 구매일로부터 10년,계약 또는 청약철회 등에 관한 기록:5년,대금결제 및 재화 등의 공급에 관한 기록:5년,소비자의 불만 또는 분쟁처리에 관한 기록:3년
											</td>
										</tr>
										<tr>
											<td style="width: 30%;font-size: 11px;text-align: center;background-color:#CECECE;">
												수집 동의 거부 권리 및 불이익 사항
											</td>
											<td style="width: 70%;font-size: 11px;padding-left: 8px;">
												이용자는 고유식별정보 수집 및 이용을 거부할 권리가 있습니다. 단, 동의를 거부하실 경우 유니시티코리아(유)에서의 제품구매는 불가합니다. 
											</td>
										</tr>
									</table>
								</div>
								<div>
									<h4>☞ 위와 같이 고유식별정보를 수집 및 이용하는 데에 동의하십니까?&nbsp;&nbsp;동의<input type="radio" id="chk1" name="checkYN" value="Yes"/>&nbsp;&nbsp;미동의<input type="radio" id="chk2" name="checkYN" value="No"/></h4>
								</div>
								
								<div class="btn_box">
									<a href="javascript:js_register()">등록하기</a>
								</div>
								<input type="hidden" name="id">
								<input type="hidden" name="dob">
								<input type="hidden" name="fullname">
								<input type="hidden" name="applyDate">
								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html> 
<?php include_once("./includes/google.php");?>