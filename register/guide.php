<?php
session_start();
//if(!isset($_SERVER["HTTPS"])) {
//	header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
//	exit;
//}
include "./includes/common_functions.php";
$user_device = mobile_check();  // return P or M

//cert_validation();

//세션아이디 초기화 후 재생성
$_SESSION['ssid'] == "";
$_SESSION['ssid'] = session_id().time();
$direct = $_GET['direct'];

include "./includes/top.php";
?>
<script type="text/javascript">
function goCertification(type){
	var direct='<?php echo $direct ?>'
//	if(direct == 'y'){
//		location.href = "registerForm.php?memberType="+type+"&direct="+direct ;
//	}else{
//		
//	}
	location.href = "certification.php?memberType="+type+"&direct="+direct;	
}
</script>
<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <dt>회원가입 안내</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 Call Center로 문의하시기 바랍니다(1577-8269)</dd>
    </dl>

</div>

<div class="cont_wrap">
	<h2 class="certification_title">회원가입</h2>
	<div class="certification_box st_2">
		<ul class="certification_list st_2">
			<li class="ty04">
				<div class="">
					<a href="javascript:;" onclick="goCertification('D');">회원 가입하기</a>
					<a class="cl_popup" href="#term0" onclick="$('#popup1').fadeIn(); return false;" class="popup_btn">회원이란?</a>
				</div>
			</li>
			<li class="ty05">
				<div class="">
					<a href="javascript:;" onclick="goCertification('C');">소비회원 가입하기</a>
					<a class="cl_popup" href="#term0" onclick="$('#popup2').fadeIn(); return false;" class="popup_btn">소비회원이란?</a>
				</div>
			</li>
		</ul>
	</div>
</div>

<div class="popup_wrap" id="popup1">
	<div class="popup_box">
		<div class="popup_top"><span>회원가입</span></div>
		<div class="popup_con">
			<p style="font-size:1.25em; margin-bottom:0.5em; font-weight:700;">회원이란?</p>
			<p><!-- FO(Franchise Owner) -->회원(Distributor)은 유니시티코리아(유)의 제품을 판매하고, 유니시티 사업에 대한 후원활동을 하여 보상프로그램에 참여하며, 실적에 따라 후원수당을 지급받는 독립적인 사업자입니다.</p><br />
			<p class="tc_1" style="color:#dd2222; font-weight:700;">※ <!-- FO -->회원으로 지급받은 후원수당은 사업소득으로 국세청에 신고되며, 귀하는 소득세 및 납세의무가 발생합니다.</p>
		</div>
		<div class="popup_close">
            <a href="javascript:void(0)" onclick="$('#popup1').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>

<div class="popup_wrap" id="popup2">
	<div class="popup_box">
		<div class="popup_top"><span>소비회원 가입</span></div>
		<div class="popup_con">
			<p style="font-size:1.25em; margin-bottom:0.5em; font-weight:700;">소비회원이란?</p>
			<p>소비회원은 자가소비 목적으로 유니시티코리아(유)의 제품 구입을 위해 등록하는 분입니다. 소비회원은 보상프로그램에 참여하지 않아 후원수당이 발생하지 않으며, 해당 구매실적은 상위 <!-- FO --> 회원 실적으로 반영됩니다. </p>
		</div>
		<div class="popup_close">
            <a href="javascript:void(0)" onclick="$('#popup2').fadeOut();" class="play_close" style="display: inline;"></a>
        </div>
	</div>
</div>


<?php include "./includes/footer.php";?>



</body>
</html>
