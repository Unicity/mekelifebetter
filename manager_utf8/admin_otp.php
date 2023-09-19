<?php
session_start();
if(!isset($_SERVER["HTTPS"])) {
	//header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
	//exit;
}

if(empty($_SESSION["tmp_s_adm_id"])){
	header('Location: admin.php');
	exit;
}

	header("X-Frame-Options: DENY");
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin.php
	// 	Description : 관리자 로그인 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "../dbconn_utf8.inc";
	include "./inc/global_init.inc";
	include "./inc/common_function.php";

	$query = "select id, passwd, Email, UserName, temp1, temp2, optpass, datediff(NOW(), pw_update_date) AS BB_DATEDIFF from tb_admin where id = '".$_SESSION["tmp_s_adm_id"]."'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	if($row['id'] == ""){
		header('Location: admin.php');
		exit;
	}

	include_once $_SERVER['DOCUMENT_ROOT']."/manager_utf8/otp/GoogleAuthenticator.php";


	$ga = new PHPGangsta_GoogleAuthenticator();

	//$secret = $ga->createSecret(); // 시크릿키 생성

	$secret = 'OQB6ZZGYHCPSX4AK'; //테스트를 위한 고정 시크릿키(16)

	$qrname = 'unicore.unicitykorea';
	$qrid = $qrname."-".$_SESSION["tmp_s_adm_id"];

	$qrCodeUrl = $ga->getQRCodeGoogleUrl($qrid, $qrname, $row['optpass']);
	//echo $qrCodeUrl."<br>";
	//$oneCode = $ga->getCode($secret);


	//echo $en_pass;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=1100">
<meta http-equiv="X-Frame-Options" content="deny" />
<meta name="autocomplete" content="off" />

<title><?echo $g_site_title?></title>
<style>
*, html, body {margin:0;padding:0;}
html,body {height:100%;}
ul,li {list-style: none;}
a {text-decoration: none;}
img, a {border:0;}
body {font-family:sans-serif,"NanumGothic","나눔고딕","맑은 고딕",HelveticaNeue-Light,AppleSDGothicNeo-Light,"돋움",dotum; font-size: 100%; 
				}
#wrappter {background:url(images/bg.jpg) no-repeat 50% 50%; background-size:cover; min-height:800px; height:100%;}
#head {overflow: hidden;border-top:8px solid #003f85;}
#head #logo {text-align: center; margin-top: -8px; }
#login_top {padding:70px 0 20px;}
#login_top h1 {color:#fff; text-align: center; font-size:44px; font-weight: normal; letter-spacing: -4px;}
.lg_box {background: #ccc; width:523px; margin:0 auto; position: relative; z-index:3;}
.popup {display: none;}
#footer { background:#899599;background: rgba(0,0,0,0.3);overflow: hidden; position: fixed; bottom:0; left:0; width:100%;}
#footer p {padding:10px; color:#fff; text-align: center;}
.lg_box h2 {text-align: center;}
.lg_box h2 {padding: 40px 0 44px; color:#003f85;}
.lg_box ul {padding:0 70px;}
.lg_box ul li {margin-bottom: 20px;}
.lg_box ul li h3 {font-size:16px; color:#333; padding-bottom: 10px;}
.lg_box ul li input[type=text], .lg_box ul li input[type=password] {height:50px; padding:0 22px; border-radius: 3px; border: 1px solid #ccc; width:336px; font-size:16px;}
.pwa {text-align: right;}
.pwa img {vertical-align:middle; margin-right: 6px; margin-bottom: 2px;}
.pwa a, .pwa p {color:#6c7d81; margin-right: 10px;}
.lg_box ul li input[type=button] {width:382px; height:60px; background: #003f85; color:#fff;  font-size:20px; font-weight: bold; text-align: center; border-radius: 3px; border:0; cursor: pointer; margin:10px 0 50px;}
.ov_bg {position: fixed;top:0;left:0;bottom:0;right:0; background: #000; opacity: 0.9;filter:alpha(opacity=90); z-index: 80;}
#pw_box {position: fixed; margin-left: -261px; left:50%; top:50%; margin-top: -333px; z-index: 90;}
#pw_box .close_btn {position: absolute;top:15px; right:15px;}
.popup {display: none;}
</style>
<script type="text/javascript" src="/korea/js/jquery-1.9.1.min.js"></script>
<script language='javascript'> 

	$(document).ready(function() {
		form.optcode.focus();

		$('input[type="text"]').keydown(function() {
			if (event.keyCode === 13) {
				event.preventDefault();
				js_login();
			};
		});

	});

	function check_form(form) {
		if (form.optcode.value == "") {
			alert('OPT CODE를 입력해 주세요');
			form.optcode.focus();
			return false;
		}
		
	}

	function js_login() {
		
		var frm = document.form;

		if (form.optcode.value == "") {
			alert('OPT CODE를 입력해 주세요');
			form.optcode.focus();
			return false;
		}

		if (form.optcode.value.length != 6) {
			alert('OPT CODE를 바르게 입력해 주세요');
			form.optcode.focus();
			return false;
		}

		$.ajax({
			type: 'post',
			url: 'admin_opt_check.php',
			data: $('#form').serialize(),
			success: function(msg){
				console.log(msg);

				if(msg == 'passchg'){ //비밀번호 변경
					
					alert("비밀번호를 90일 이상 이용 하셨습니다.\n새로운 비밀번호로 수정 해 주세요.");
					document.location = "/manager_utf8/admin_password_reset.php";

				}else if(msg == 'login'){ //로그인 요청

					alert("로그인 후 이용하여 주세요");
					document.replace("/manager_utf8/admin.php");

				}else if(msg == 'success'){ //로그인 요청
					
					document.location = "/manager_utf8/admin_main.php";

				}else{
					
					alert(msg);
				}		
			},
			error: function( jqXHR, textStatus, errorThrown ) { 
				alert( textStatus + ", " + errorThrown ); 
			} 
		});	
	}

</script>

</head>
<body>
<div id="wrappter">
	<!-- ie8 사용가능 하도록 div사용 -->
	<div id="head">
			<div id="logo"><img src="images/logo.jpg" alt="UNICITY LOGO"></div>
	</div>

	<div id="content">
		<div id="login_top">
			<h1>유니시티의 핵심 <br><b>UNICORE</b></h1>
		</div>
		
		<!-- 로그인 영역 { -->
		<div id="login_box" class="lg_box">
			<h2>OTP Auth</h2>
			<form method='post' name='form' id='form' action='#' onSubmit='js_login();'>
				<input type="hidden" name="optpass" id="optpass" value="<?=$row['optpass']?>">
				<ul>
					<li><h3>OPT CODE</h3><input type="text" name='optcode' id='optcode' size='20' maxlength='6' autocomplete='off' placeholder="OPT CODE"></li>
					<li class="pwa"><a href="#pwsearch" onclick="openPopup();"><img src="images/icon.png" alt="">OTP 발급방법안내</a></li>
					<li><input type="button" value="확인" onClick="js_login();"></li>
				</ul>
			</form>
		</div>
		<!-- } 로그인 영역 -->

	</div>

	<!-- 비밀번호 찾기 { -->
	<div class="ov_bg popup" id="pw_box_bg"></div>
		<div class="popup lg_box" id="pw_box">
			<h2>OTP발급방법</h2>
			<form>
				<ul>
					<li>1. 모바일폰에서 Android 'Play 스토어' 또는 iOS 'App 스토어' 에서 Google opt 또는 Google Authenticator 검색 후 앱 설치</li>
					<li>2. 앱 실행 후 좌측하단의 '+' 클릭 후 'QR 코드 스캔' 선택</li>
					<li>3. 하단의 QR코드 스캔</li>
					<li style="text-align:center"><img src="<?=$qrCodeUrl?>"></li>
					<li><input type="button" value="닫기" onclick="closePopup();"></li>
				</ul>
			</form>
			<div class="close_btn"><a href="#close" onclick="closePopup();"><img src="images/close.png" alt="닫기"></a></div>
		</div>
	</div>
	<!-- } 비밀번호 찾기 -->
</div>

<div id="footer">
	<p>Copyright ⓒ Unicity Korea, Co., Ltd. All right reserved.</p>
</div>

<script type="text/javascript">

	var lypop = document.getElementById('pw_box');
	var lypopBg = document.getElementById('pw_box_bg');
	console.log(lypop);
	openPopup = function(){	//팝업 열기
		lypop.style.display = "block";
		lypopBg.style.display = "block";
	}
	closePopup = function(){	//팝업 닫기
		lypop.style.display = "none";
		lypopBg.style.display = "none";
	}

</script>
<?php //include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>
</body>
</html>