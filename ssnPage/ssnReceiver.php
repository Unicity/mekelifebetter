<?
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache');
header('Expires: 0');

if(!isset($_SERVER["HTTPS"])) {
	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	exit;
}

//강제로 www붙이기
if(!stristr($_SERVER['HTTP_HOST'],"www.")) {
	header("location: https://www.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	exit;
}

$chkID  = $_POST['memberId'];
$birthDay  = $_POST['birthDay'];

$str0 = substr($birthDay, 2, 2); 
$str1 = substr($birthDay, 5, 2); 
$str2 = substr($birthDay, 8, 2); 

$str = $str0.$str1.$str2;
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
<script type="text/javascript" src="./includes/js/ssn.js?ver=<?=rand(111,999)?>"></script>
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
								<input type="text" placeholder="회원번호" name="distID" id="distID"/>
								<input type="hidden" id="chkID" name="chkID" value="false">
								<input type="hidden" id="chkJumin" name="chkJumin" value="false">
								<a href="javascript:js_search()">확인</a>
							</div>
							<p id="distName"><span></span></p>

						</div>
						<div class="number">
							<h2>주민번호</h2>
							<div>
								<span><input type="text" placeholder="" name="ssn1" id="ssn1" maxlength="6"/></span>
								<span class="dot">-</span>
								<span ><input type="text" placeholder="" name="ssn2" id="ssn2" maxlength="7" /></span>
							</div>
							<p id="SSNError"><span></span></p>
						</div>
						<div class="btn_box btn_register">
							<a href="javascript:js_register()">등록하기</a>
						</div>
						<div class="btn_box btn_register_ing" style="display:none">
							<a href="javascript:;" style="background:none; color:#11c2c9">등록중입니다. 잠시 기다려 주세요...</a>
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
	<!--서비스 일시중지 팝업 -->
	<!-- <div class="popup_wrap" id="popup200417">
	  <div class="p_con">
	    <h3>
	      서버 안정화 작업으로<br />
	      <span class="p_color1">서비스가 일시 중지</span>됩니다.
	    </h3>
	    <p class="p_bullet">
	      <span class="p_color1">작업일정</span> : 05/08(금) 22:00 ~ 05/09(토) 06:00
	    </p>
	  </div>
	  <div class="p_footer">
	    <a href="#" onclick="document.getElementById('popup200417').style.display='none' ; return false;" class="p_btn">close</a>
	  </div>
	</div>
	<style>
	@font-face {font-family: 'Noto Sans KR';font-style: normal;font-weight: 400;src: url(//fonts.gstatic.com/ea/notosanskr/v2/NotoSansKR-Regular.woff2) format('woff2'),url(//fonts.gstatic.com/ea/notosanskr/v2/NotoSansKR-Regular.woff) format('woff'),url(//fonts.gstatic.com/ea/notosanskr/v2/NotoSansKR-Regular.otf) format('opentype');}
	.popup_wrap{
	  position: fixed; color: #FFF;
	  width: 520px;
	  top: 50%; left: 50%;
	  -webkit-transform: translate(-50%, -50%);
	  -moz-transform: translate(-50%, -50%);
	  -o-transform: translate(-50%, -50%);
	  -ms-transform: translate(-50%, -50%);
	  transform: translate(-50%, -50%);
	}
	.popup_wrap * {font-family: 'Noto Sans KR' !important;}
	.popup_wrap .p_con{text-align: center; padding: 50px 10px; background-image: url(/korea/img/popup/popupbg_200417.jpg); background-repeat: no-repeat; background-size: cover; background-position: center center;}
	.popup_wrap .p_con h3{font-size: 26px; font-weight: 600; line-height: 1.5em;}
	.popup_wrap .p_color1{color: #eff15f; }
	.popup_wrap .p_bullet{position: relative;margin-top: 30px; font-size: 18px; line-height: 1.2}
	.popup_wrap .p_bullet::before{content:''; display: inline-block; width: 4px; height: 4px; border-radius: 100%; background: #eff15f;     margin-bottom: 0.2em;}
	.popup_wrap .p_footer{background: #333; padding: 8px 15px; text-align: right;}
	.popup_wrap .p_footer a{display:inline-block;background: #171717; color: #fff; padding: 2px 5px; text-decoration: none;}

	@media only screen and (max-width:520px){
	  .popup_wrap{
	      max-width: 520px; width: 100%; top: 20%; left: 0%;
	      -webkit-transform: none;
	      -moz-transform: none;
	      -o-transform: none;
	      -ms-transform: none;
	      transform: none;
	  }
	  .popup_wrap .p_con h3{font-size: 23px;}
	  .popup_wrap .p_bullet::before{width: 2px; height: 2px;}
	  .popup_wrap .p_bullet{font-size: 14px;}
	}
	</style> -->
	<!--//서비스 일시중지 팝업 -->
</body>
</html>
<?php include_once("./includes/google.php");?>
