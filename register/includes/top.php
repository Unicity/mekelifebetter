<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" type="text/css" href="./css/reset.css?v=1"/>
	<link rel="stylesheet" type="text/css" href="./css/common.css?v=154"/>
	<link rel="stylesheet" type="text/css" href="./css/style.css"/>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="./js/ui.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
	<title>유니시티 회원가입</title>
	<script type="text/javascript">
		$(document).on('click', '.HT_menu', function(){
			$('#top_menu').fadeToggle(200).toggleClass('on');
			if($('#top_menu').hasClass('on')){
				$('.HT_menu').attr('src', 'https://www.makelifebetter.co.kr/_m/images/main_topMenu_off.gif');
				$('body').css('overflow', 'hidden');
			}else{
				$('.HT_menu').attr('src', 'https://www.makelifebetter.co.kr/_m/images/main_topMenu.gif');
				$('body').css('overflow', 'visible');
			}
		});
		$(document).on('click', '.close', function(){
			$('#top_menu').fadeOut(200).removeClass('on');
			$('.HT_menu').attr('src', 'https://www.makelifebetter.co.kr/_m/images/main_topMenu.gif');
			$('body').css('overflow', 'visible');
		});
	</script>
</head>
<body>

<div id="pc_mo_bool"></div>
<div class="logo">
	<a href="javascript:history.back();" class="btn_back"></a>

	<div class="top">
		<img src="https://www.makelifebetter.co.kr/_m/images/main_topMenu.gif" class="HT_menu" alt="메뉴" />
	</div>
</div>



<div id="top_menu">
	<ul class="main_bt_wrap">

		<a class="mainBt" target="_blank" href="//korea.unicity.com">홈페이지</a>
		<a class="mainBt2" target="_blank" href="//ushop-kr.unicity.com"><!-- FO -->회원사이트</a>
		<a class="mainBt2" target="_self" href="//www.makelifebetter.co.kr/register/">회원가입하기</a>
		<a class="mainBt2" target="_self" href="//www.makelifebetter.co.kr/register/help.php">회원가입도우미</a>

		<p class="mainBt2 colorW close" style="cursor:pointer">닫기</p>

	</ul>
</div>
