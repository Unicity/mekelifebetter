<?
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache');
header('Expires: 0');

//강제로 www붙이기
if(!stristr($_SERVER['HTTP_HOST'],"www.")) {
	header("location: https://www.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); 
	exit;    
}
?>
<?
	//if ($_SERVER['SERVER_PORT'] == "80") {
?>
<!--<meta http-equiv='Refresh' content='0; URL=https://www.makelifebetter.co.kr/ssnPage/ssnReceiver.php'>-->
<?
		//exit;
	//}
?>
<?//=$_SERVER['HTTPS']?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>세금신고용 주민번호 등록 페이지</title>
<meta name="description" content="" />
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
<script type="text/javascript" src="./includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./includes/js/HydraDataReceiver.js?ver=<?=rand(111,999)?>""></script>	
<link rel="stylesheet" type="text/css" href="./css/joo.css" />
</head>
<body>
	<div class="wrapper">
		<!-- container start {-->
		<div class="main_wrapper">

			<div class="figure">
					<img src="./images/mainlogo.png" alt="유니시티 로고" />
			</div>
			<div class="main_box">
				<div class="main_inner_box">
					<div class="main_top">
						<h1>
							<span>세금신고용</span>
							주민번호 등록 페이지
						</h1>
						<p>세금 신고를 위해 회원번호와 주민번호를 <br>등록해 주시기 바랍니다.</p>
					</div>
					<div class="wrap_input">
						<div class="member">
							<h2>회원번호</h2>
							<div class="wrap">
								<input type="text" placeholder="회원번호" name="distID" id="distID" />
								<input type="hidden" id="chkID" name="chkID" value="false">
								<input type="hidden" id="chkJumin" name="chkJumin" value="false">
								<a href="javascript:js_search()">확인</a>
							</div>
							<p id="distName"><span></span></p>

						</div>
						<div style="margin-top:10px;">
						<p><i><font color="red">&#8251;수당 지급이 보류된 회원께서는 주민번호 등록 후 반드시 본인이 회사에 연락 주셔서 지급 요청 하시기 바랍니다.<br/>(본인이 아닐 경우,지급 요청이 불가 합니다.)</font></i></p>
					</div>
					</div>
				</div>
			</div>
		</div>
		<!-- } container end -->

		<!-- footer start {-->
		<div class="footer">
		Copyright © Unicity Korea, Co.,Ltd.  <br>All right reserved.
		</div>
		<!-- } footer end -->
	</div>
</body>
</html> 
<?php include_once("./includes/google.php");?>