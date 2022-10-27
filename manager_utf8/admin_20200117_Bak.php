<?
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

	include "./inc/global_init.inc";

	include "../AES.php";

	$en_pass = encrypt($key, $iv, '%ujeqe$e');

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
.lg_box {background: url(images/boxbg_s.png) no-repeat 0 0; width:523px; margin:0 auto; position: relative; z-index:3;}
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
#pw_box {position: fixed; margin-left: -261px; left:50%; top:50%; margin-top: -220px; z-index: 90;}
#pw_box .close_btn {position: absolute;top:15px; right:-20px;}
.popup {display: none;}
</style>
<script type="text/javascript" src="/korea/js/jquery-1.9.1.min.js"></script>
<script language='javascript'>

	$(document).ready(function() {

		$("#adminpasswd").keypress(function( event ) {
			if ( event.which == 13 ) {
				js_login();
			}
		});

		form.adminid.focus();


	});

	function check_form(form) {
		if (form.adminid.value == "") {
			alter ('관리자의 아이디를 입력해 주세요.');
			form.adminid.focus();
			return false;
		}
		if (form.adminpasswd.value == "") {
			alter ('관리자의 패스워드를 입력해 주세요.');
			form.adminid.focus();
			return false;
		}
	}

	function js_login() {
		
		var frm = document.form;

		if (frm.adminid.value == "") {
			alert('관리자의 아이디를 입력해 주세요.');
			frm.adminid.focus();
			return;
		}
		if (frm.adminpasswd.value == "") {
			alert('관리자의 패스워드를 입력해 주세요.');
			frm.adminid.focus();
			return;
		}
		
		frm.action = "https://www.makelifebetter.co.kr/manager_utf8/admin_ok.php";
		frm.target = "";
		frm.submit();
	
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
			<h2><img src="images/login.png" alt="LOGIN"></h2>
			<form method='post' name='form' action='https://www.makelifebetter.co.kr/manager_utf8/admin_ok.php' onSubmit='return check_form(this);'>
				<ul>
					<li><h3>ID</h3><input type="text" name='adminid' id='adminid' size='20' maxlength='20' autocomplete='off' placeholder="관리자ID"></li>
					<li><h3>PW</h3><input type="password" name='adminpasswd' id='adminpasswd' size='20' maxlength='20' autocomplete='off' placeholder="비밀번호"></li>
					<li class="pwa"><a href="#pwsearch" onclick="openPopup();"><img src="images/icon.png" alt="">비밀번호 찾기</a></li>
					<li><input type="button" value="로그인" onClick="js_login();"></li>
				</ul>
			</form>
		</div>
		<!-- } 로그인 영역 -->

	</div>

	<!-- 비밀번호 찾기 { -->
	<div class="ov_bg popup" id="pw_box_bg"></div>
		<div class="popup lg_box" id="pw_box">
			<h2>비밀번호 찾기</h2>
			<form>
				<ul>
					<li><h3>ID</h3><input type="text" name="search_id" id="search_id" placeholder="관리자ID"></li>
					<li><h3>EMAIL</h3><input type="text" name="search_email" id="search_email" placeholder="이메일"></li>
					<li class="pwa"><p>등록된 이메일로 새 비밀번호가 발송됩니다.</p></li>
					<li><input type="button" value="확인" onclick="js_search_pwd();"></li>
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

	function js_search_pwd() {
		
		var search_id			= $("#search_id").val();
		var search_email	= $("#search_email").val();

		if (search_id == "") {
			alert("ID를 입력해 주세요.");
			$("#search_id").focus();
			return;
		}

		if (search_email == "") {
			alert("이메일을 입력해 주세요.");
			$("#search_email").focus();
			return;
		}
		
		var request = $.ajax({
			url:"send_new_pwd.php",
			type:"GET",
			data:{search_id:search_id, search_email:search_email},
			dataType:"html"
		});

		request.done(function(msg) {
			if (msg == "T") {
				alert("이메일로 임시 비밀번호를 보내 드렸습니다. ");
			} else {
				alert("해당 이메일로 등록된 관리자가 없습니다. ");
			}

			closePopup();

		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	}

</script>

</body>
</html>