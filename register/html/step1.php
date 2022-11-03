<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" type="text/css" href="css/reset.css?v=1"/>
	<link rel="stylesheet" type="text/css" href="css/common.css?v=1"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">
	<title>본인인증</title>
</head>
<body>

<div id="pc_mo_bool"></div>
<div class="logo">
	<a href="javascript:history.back();" class="btn_back"></a>
</div>
<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <dt>본인인증</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 지역센터로 문의 하시기 바랍니다. <a href="https://korea.unicity.com/contact-us/" target="_blank">지역센터보기</a></dd>
    </dl>

</div>

<div class="cont_wrap">
	<h2 class="certification_title">본인인증</h2>
	<div class="certification_box">
		<ul class="certification_list">
			<li class="ty01"><a href="javascript:fnPopupCb('M');">휴대폰 인증</a></li>
			<li class="ty02"><a href="javascript:fnPopupCb('X');">공동인증서 인증</a></li>
			<li class="ty03"><a href="javascript:fnPopupIp();">I - Pin 인증</a></li>
		</ul>
	</div>

</div>


<?php include "./includes/footer.php";?>


<script>
function device_check() {
	// 디바이스 종류 설정
	var pc_device = "win16|win32|win64|mac|macintel";

	// 접속한 디바이스 환경
	var this_device = navigator.platform;

	if ( this_device ) {

		if ( pc_device.indexOf(navigator.platform.toLowerCase()) < 0 ) {
			return 'mo'
		}
	}5
}
function pcMoType (){
  var pcMo = document.getElementById('pc_mo_bool');
  var pcMo_bool = ( window.getComputedStyle(pcMo).getPropertyValue('display') == 'block' ) ? 'pc' : 'mo';
  return pcMo_bool;
}

var timer;
var delta = 300;
$(window).on('resize' , function(){
	wheelMove = false;

	clearTimeout( timer );
	timer = setTimeout( resizeDone, delta );

});

function resizeDone(){
	if( device_check()  == 'mo' ){
		$('.ty02').hide();
	}else{
		$('.ty02').show();
	}
}
resizeDone();

</script>
</body>
</html>
