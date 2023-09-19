<?php
session_start();
error_reporting(1);
ini_set('display_errors', 1);
	header("X-Frame-Options: DENY");

	include "../dbconn_utf8.inc";
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
#login_top {padding:3% 0 3%;}
#login_top h1 {color:#fff; text-align: center; font-size:40px; font-weight: normal; letter-spacing: -4px;}
.lg_box {background-color:#eee; width:523px; margin:0 auto; opacity:0.9; position: relative; z-index:3;}
.popup {display: none;}
#footer { background:#899599;background: rgba(0,0,0,0.3);overflow: hidden; position: fixed; bottom:0; left:0; width:100%;}
#footer p {padding:10px; color:#fff; text-align: center;}
.lg_box h2 {text-align: center;}
.lg_box h2 {padding: 30px 0 34px; color:#003f85;}
.lg_box ul {padding:0 70px;}
.lg_box ul li {margin-bottom: 20px;}
.lg_box ul li h3 {font-size:16px; color:#333; padding-bottom: 10px;}
.lg_box ul li input[type=text], .lg_box ul li input[type=password] {height:40px; padding:0 15px; border-radius: 3px; border: 1px solid #ccc; width:336px; font-size:16px;}
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

		$("#adminpasswdcheck").keypress(function( event ) {
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
		if (frm.oldadminpasswd.value == "") {
			alert('관리자의 구 비밀번호를 입력해 주세요.');
			frm.oldadminpasswd.focus();
			return;
		}
		if (frm.newadminpasswd.value == "") {
			alert('관리자의 새 비밀번호를 입력해 주세요.');
			frm.newadminpasswd.focus();
			return;
		}
		if (frm.newadminpasswd.value == frm.oldadminpasswd.value) {
			alert('관리자의 구 비밀번호와 다시 사용할 수 없습니다.');
			frm.newadminpasswd.focus();
			return;
		}

		if (parseInt(frm.newadminpasswd.value.length) < 10) {
			alert("비밀번호는 문자, 숫자, 특수문자의 조합으로 10~16글자로 입력해주세요.");
			frm.newadminpasswd.focus();
			return;
		}

		if (frm.adminpasswdcheck.value == "") {
			alert('관리자의 비밀번호 확인을 입력해 주세요.');
			frm.adminpasswdcheck.focus();
			return;
		}

		if(!CheckPassWord(frm.newadminpasswd)) {
			frm.newadminpasswd.focus();
			return;
		}

		if (frm.newadminpasswd.value !== frm.adminpasswdcheck.value) {
			alert('관리자의 새 비밀번호를 확인 해 주세요.');
			frm.newadminpasswd.focus();
			return;
		}

		frm.action = "admin_db.php";
		frm.target = "";
		frm.submit();
	
	}
	// 비밀번호 유효성체크
	function CheckPassWord(ObjUserPassWord) {
		
		if(ObjUserPassWord.value.length < 10) {
			alert("비밀번호는 문자, 숫자, 특수문자의 조합으로 10~16글자로 입력해주세요.");
			return false;
		}

		if(ObjUserPassWord.value.length > 16) {
			alert("비밀번호는 문자, 숫자, 특수문자의 조합으로 16글자이하로 입력해주세요.");
			return false;
		}

		var icnt = 0;
		var scnt = 0;
		
		for(var i=0; i < ObjUserPassWord.value.length; i++) {
			ch = ObjUserPassWord.value.charCodeAt(i);
			// charCodeAt 으로 받은 아스키 코드값으로 위 표를 바탕으로 범위 설정
			// 숫자이면 카운트를 올림
			if(ch > 47 && ch < 58) {
				icnt = icnt + 1;
			}
			// 문자이면 카운트를 올림
			if((ch > 64 && ch < 91) || (ch > 96 && ch <123)) {
				scnt = scnt + 1;
			}
		}
		
	
		var special_pattern = /[`~!@#$%^&*|\\\'\";:\/?]/gi;
		if( special_pattern.test(ObjUserPassWord.value) == true ){
			// 
		} else {
			alert('하나 이상의 특수문자가 포함되어 있어야 합니다.');
			return false;
		}


		if(icnt >= 1 && scnt >= 1) {
		}else {
			// 숫자와 문자가 함께 들어가지 않았을 때 처리부분
			alert("비밀번호는 문자, 숫자, 특수문자 조합으로 입력해주세요.");
			return false;
		}

		var SamePass_0 = 0; //동일문자 카운트
		var SamePass_1 = 0; //연속성(+) 카운드
		var SamePass_2 = 0; //연속성(-) 카운드

		var chr_pass_0;
		var chr_pass_1;

		for(var i=0; i < ObjUserPassWord.value.length; i++) {
			chr_pass_0 = ObjUserPassWord.value.charAt(i);
			chr_pass_1 = ObjUserPassWord.value.charAt(i+1);

			//동일문자 카운트
			if(chr_pass_0 == chr_pass_1) {
				SamePass_0 = SamePass_0 + 1
			}

			//연속성(+) 카운드
			if(chr_pass_0.charCodeAt(0) - chr_pass_1.charCodeAt(0) == 1) {
				SamePass_1 = SamePass_1 + 1
			}

			//연속성(-) 카운드
			if(chr_pass_0.charCodeAt(0) - chr_pass_1.charCodeAt(0) == -1) {
				SamePass_2 = SamePass_2 + 1
			}
		}

		if(SamePass_0 > 4) {
			alert("동일문자를 4번 이상 사용할 수 없습니다.");
			return false;
		}

		if(SamePass_1 > 4 || SamePass_2 > 2 ) {
			alert("연속된 문자열(123 또는 321, abc, cba 등)을 3자 이상 사용 할 수 없습니다.");
			return false;
		}
		return true;
	}

</script>

</head>
<body>

<?php include "common_load.php" ?>

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
			<h2>비밀번호 재설정</h2>
			<form method='post' name='form' action='/manager_utf8/admin_db.php' onSubmit='return check_form(this);'>
				<input type="hidden" name="mode" value="reset">
				<ul>
					<li><h3>ID</h3><input type="text" name='adminid' id='adminid' size='20' maxlength='20' autocomplete='off' placeholder="관리자ID" value="<?=$_SESSION["s_adm_id"]?>" 
					<?php if($_SESSION["s_adm_id"] != ''){ echo 'readonly'; } ?>></li>
					<li><h3>구비밀번호</h3><input type="password" name='oldadminpasswd' id='oldadminpasswd' size='20' maxlength='20' autocomplete='off' placeholder="구비밀번호"></li>
					<li><h3>새비밀번호</h3><input type="password" name='newadminpasswd' id='newadminpasswd' size='20' maxlength='20' autocomplete='off' placeholder="새비밀번호"></li>
					<li><h3>비밀번호 확인</h3><input type="password" name='adminpasswdcheck' id='adminpasswdcheck' size='20' maxlength='20' autocomplete='off' placeholder="비밀번호확인"></li>
					<li><input type="button" value="변경하기" onClick="js_login();"></li>
				</ul>
			</form>
		</div>
		<!-- } 로그인 영역 -->

	</div>

	 
</div>

<div id="footer">
	<p>Copyright ⓒ Unicity Korea, Co., Ltd. All right reserved.</p>
</div>

<?php include $_SERVER['DOCUMENT_ROOT']."/manager_utf8/inc/google-analytics.php"; ?>

</body>
</html>

</body>
</html>