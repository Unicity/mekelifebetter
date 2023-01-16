<!DOCTYPE html>
<html lang="ko-KR" prefix="og: http://ogp.me/ns#" class=" footer-sticky-0">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="http://korea.unicity.com/xmlrpc.php">
<title>기업 | 유니시티코리아</title>

<link rel="canonical" href="http://korea.unicity.com/our-company/" />
<meta property="og:locale" content="ko_KR" />
<meta property="og:type" content="article" />
<meta property="og:title" content="기업 | 유니시티코리아" />
<meta property="og:url" content="http://korea.unicity.com/our-company/" />
<meta property="og:site_name" content="유니시티코리아" />
<meta property="article:publisher" content="https://www.facebook.com/unicitykorea.official" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="기업 | 유니시티코리아" />
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
function checkForm(){
	if($("#birth").val().length < 8){
		alert("생년월일 8자리를 입력해 주세요");
		$("#birth").focus();
	}else{
		$("#bth").val($("#birth").val());
		$("#frm").submit();
	}
}
function checkNum(){
    $("#birth").val($("#birth").val().replace(/[^0-9]/g,""));
}
</script>
</head>

<body>

<div class="all_unct_wrap">
	<div class="unct_form">
		<a href="http://www.makelifebetter.co.kr/" title="logo" class="unct_logo"><img src="images/logo.jpg"></a>
		<div class="unct_form_box">
			<div class="unct_form_txt">유니시티 코리아 FO등록증을 확인 하시려면 귀하의 [생년월일 8자리] 를 입력해 주십시오.</div>
			<div class="unct_form_input">
				<input type="text" name="birth" id="birth" maxlength="8" placeholder="YYYYMMDD" onkeyup="checkNum()">
				<button type="submit" onclick="checkForm()" style="cursor:pointer">확인</button>
			</div>
		</div>
	</div><!-- //unct_form -->
</div> <!-- //all_unct_wrap -->

<form name='frm' id='frm' method="POST" action="auth.php">
	<input type="hidden" name="auchCode" value="<?=$_GET['auchCode']?>">
	<input type="hidden" name="bth" id="bth" value="">
</form>
</body>
</html>