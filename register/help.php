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

include "./includes/top.php";
?>
<script type="text/javascript">
function goCertification(type){
	location.href = "certification.php?memberType="+type;
}
</script>
<style media="screen">

</style>
<div class="cont_wrap">
    <dl class="conttit_wrap mo_none">
        <dt>회원가입 안내</dt>
        <dd>외국인 회원가입 또는 국제후원가입은 Call Center로 문의하시기 바랍니다(1577-8269)</dd>
    </dl>

</div>

<div class="cont_wrap">
	<h2 class="certification_title">회원가입도우미</h2>

    <ul class="help_wrap">
        <li class="help_text">
            <dl class="txt1">
                <dt>회원가입하기</dt>
                <dd><!-- FO -->회원가입과 소비회원가입에 대한 설명을 참조하신 후 “<!-- FO -->회원가입” 또는 “소비회원가입” 버튼을 누릅니다.</dd>
            </dl>
        </li>
        <li class="help_img">
            <div class="join_infoWrap">
                <img src="/register/images/join_img.jpg" alt="">
            </div>
        </li>
    </ul>
    <p class="join_arrow"><img src="/register/images/join_arrow.gif"></p>

    <ul class="help_wrap">
        <li class="help_text">
            <dl class="txt1">
                <dt>본인인증</dt>
                <dd>“휴대폰 인증“, 또는 “I-PIN 인증“ 버튼을 누릅니다. </dd>
            </dl>
        </li>
        <li class="help_img">
            <div class="join_infoWrap">
                <img src="/register/images/join_img1.jpg" alt="">
            </div>
        </li>
    </ul>
    <p class="join_arrow"><img src="/register/images/join_arrow.gif"></p>

    <ul class="help_wrap">
        <li class="help_text">
            <dl class="txt1">
                <dt>신청서 작성</dt>
                <dd>각 사항들을 숙지하시고, 항목들에 정보를입력 후 “확인하기” 버튼을 누릅니다.</dd>
            </dl>
        </li>
        <li class="help_img">
            <div class="join_infoWrap">
                <img src="/register/images/join_img2.jpg" alt="">
            </div>
        </li>
    </ul>
    <p class="join_arrow"><img src="/register/images/join_arrow.gif"></p>

    <ul class="help_wrap">
        <li class="help_text">
            <dl class="txt1">
                <dt>약관 및 개인정보 동의</dt>
                <dd>기본정보 입력 화면 하단에 “약관 및 개인정보 동의＂의 안내사항을 숙제하신 후 동의하는 경우 체크박스를 클릭합니다. 완료되면 “가입하기＂버튼을 누릅니다.</dd>
            </dl>
        </li>
        <li class="help_img">
            <div class="join_infoWrap">
                <img src="/register/images/join_img3.jpg" alt="">
            </div>
        </li>
    </ul>
    <p class="join_arrow"><img src="/register/images/join_arrow.gif"></p>

    <ul class="help_wrap">
        <li class="help_text">
            <dl class="txt1">
                <dt>회원가입 신청 완료</dt>
                <dd>- 소비회원 : 발급 받은 회원번호로 바로 활동 가능</dd>
                <dd>- <!-- FO -->회원 : 임시 회원번호가 발급되며 24시간 이내 심사 후 완료 메일 및 문자메시지를 발송하며, 수신 후 활동 가능</dd>
                <dd style="color:#2d6fd4">(단, 월말 및 신청 접수가 많은 경우 처리가 2~3일 지연 될 수 있으니 재가입 진행하지 마시고 꼭, 가입 완료 메일 및 문자 메시지를 기다려 주시기 바랍니다.)</dd>
            </dl>
        </li>
        <li class="help_img">
            <div class="join_infoWrap">
                <img src="/register/images/join_img4.jpg" alt="">
            </div>
        </li>
    </ul>
</div>



<?php include "./includes/footer.php";?>



</body>
</html>
