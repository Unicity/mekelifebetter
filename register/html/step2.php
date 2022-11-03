<?php include "./includes/top.php";?>
<!-- <!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<link rel="stylesheet" type="text/css" href="css/reset.css?v=1"/>
	<link rel="stylesheet" type="text/css" href="css/common.css?v=1"/>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>
	<title>본인인증</title>
</head>
<body>


<div id="pc_mo_bool"></div>
<div class="logo">
	<a href="javascript:history.back();" class="btn_back"></a>
</div> -->
<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <dt>회원가입 안내</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 Call Center로 문의하시기 바랍니다(1577-8269)</dd>
    </dl>

</div>

<div class="cont_wrap">
	<h2 class="certification_title">회원가입</h2>
	<div class="certification_box">
		<ul class="certification_list st_2">
			<li class="ty04"><a href="javascript:fnPopupCb('M');">FO회원 가입하기</a></li>
			<li class="ty05"><a href="javascript:fnPopupCb('X');">소비회원 가입하기</a></li>
		</ul>
	</div>

	<div class="certification_tab">
		<ul>
			<li>
				<a href="###">FO 회원가입안내</a>
				<div>
					<p class="certification_tab_tit">FO 회원이란</p>
					<p>FO(Franchise Owner)는 유니시티코리아(유)의 제품을 판매하고, 유니시사업에 대한 후원활동을 하여 보상프로그램에 참여하며, <br />실적에 따라 후원수당을 지급받는 독립적인
					자영사업자입니다.</p><br />
					<p>※ FO로 지급받은 후원수당은 사업소득으로 국세청에 신고되며, 귀하는 소득세 및 납세의무가 발생합니다.</p>
				</div>
			</li>
			<li>
				<a href="###">소비회원안내</a>
				<div>
					<p class="certification_tab_tit">소비회원이란</p>
					<p>소비회원은 자가소비 목적으로 유니시티코리아(유)의 제품 구입을 위해 등록하는 분입니다. 소비회원은 보상프로그램에 참여하지 않아 후원수당이 발생하지 않으며, <br />해당 구매실적은 상위 FO 실적으로 반영됩니다. </p>
				</div>
			</li>
		</ul>
	</div>
</div>
<?php include "./includes/footer.php";?>

<script type="text/javascript">

$(function(){
	$('.certification_tab a').click(function(e){
		e.preventDefault();

		$(this).closest('li').toggleClass('on');
		$(this).next('div').slideToggle('on');
	});
});

</script>


</body>
</html>
